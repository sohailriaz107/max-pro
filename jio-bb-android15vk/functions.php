<?php 
function dateDiffInDays($date1, $date2)  
{ 
$diff = strtotime($date2) - strtotime($date1); 
return abs(round($diff / 86400)); 
}



// Function to create a base64 URL-encoded string
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function sendPushNotificationFCM($topic,$title,$description){
	
	$date = date('Y-m-d');
	$time = date('h:i:s');
	
$serviceAccountPath = SERVICE_ACCOUNT_KEY_FCM; // Update this path in connect.php file
$serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

// Set the required values
$privateKey = $serviceAccount['private_key'];
$clientEmail = $serviceAccount['client_email'];
$projectId = $serviceAccount['project_id'];

// Create a JWT for authentication
$now = time();
$expires = $now + 3600; // Token valid for 1 hour

$header = [
    'alg' => 'RS256',
    'typ' => 'JWT'
];

$payload = [
    'iss' => $clientEmail,
    'sub' => $clientEmail,
    'aud' => 'https://oauth2.googleapis.com/token',
    'iat' => $now,
    'exp' => $expires,
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
];

// Encode the header and payload
$base64UrlHeader = base64UrlEncode(json_encode($header));
$base64UrlPayload = base64UrlEncode(json_encode($payload));

// Create the signature
$signature = '';
openssl_sign($base64UrlHeader . '.' . $base64UrlPayload, $signature, $privateKey, 'SHA256');
$base64UrlSignature = base64UrlEncode($signature);

// Create the JWT
$jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

// Get an OAuth2 access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt
]));

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);
$accessToken = $data['access_token'];

if($topic !=''){
$message = [
    "message" => [
        "topic" => $topic,
        "data" => [
            "title" => $title,
            "msg" => $description,
            "vibrate" => '3',
            "icon" => 'myIcon',
            "sound" => '1'
        ]
    ]
]; 

}else{
$message = [
    "message" => [
        "token" => $token,
        "data" => [
            "title" => $title,
            "description" => $description,
            "date" => $date,
            "time" => $time,
            "icon" => $home_url.'img/icon.png',
            "redirection" => $redirection,
			"redirection_to" => $redirection_to,
			"game_id" => $game_id,
			"url" => $action_url
        ]
    ]
]; 	
}

// Send the notification
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

$result = curl_exec($ch);
curl_close($ch);

return $result;
}

?>