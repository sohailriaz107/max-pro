<?php 
declare(strict_types=1);

if (!function_exists('dateDiffInDays')) {
    function dateDiffInDays($date1, $date2): int 
    { 
        $t1 = strtotime(str_replace('/', '-', (string)$date1));
        $t2 = strtotime(str_replace('/', '-', (string)$date2));
        if ($t1 === false || $t2 === false) {
            return 0;
        }
        $diff = $t2 - $t1; 
        return (int)abs(round($diff / 86400)); 
    }
}

if (!function_exists('base64UrlEncode')) {
    function base64UrlEncode(string $data): string 
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}

if (!function_exists('sendPushNotificationFCM')) {
    function sendPushNotificationFCM($topic, $title, $description, $token = '', $extra = []): string
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $serviceAccountPath = defined('SERVICE_ACCOUNT_KEY_FCM') ? SERVICE_ACCOUNT_KEY_FCM : '';
        if ($serviceAccountPath === '' || !file_exists($serviceAccountPath) || !is_readable($serviceAccountPath)) {
            error_log("FCM service account file not found: " . $serviceAccountPath);
            return json_encode(['error' => 'Service account file not found']);
        }

        $serviceAccountRaw = file_get_contents($serviceAccountPath);
        if ($serviceAccountRaw === false) {
            return json_encode(['error' => 'Unable to read service account file']);
        }

        $serviceAccount = json_decode($serviceAccountRaw, true);
        if (!is_array($serviceAccount) || empty($serviceAccount['private_key']) || empty($serviceAccount['client_email']) || empty($serviceAccount['project_id'])) {
            error_log("Invalid FCM service account structure");
            return json_encode(['error' => 'Invalid service account file']);
        }

        $privateKey  = (string)$serviceAccount['private_key'];
        $clientEmail = (string)$serviceAccount['client_email'];
        $projectId   = (string)$serviceAccount['project_id'];

        $now     = time();
        $expires = $now + 3600;

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $payload = [
            'iss'   => $clientEmail,
            'sub'   => $clientEmail,
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $expires,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        $base64UrlHeader  = base64UrlEncode((string)json_encode($header));
        $base64UrlPayload = base64UrlEncode((string)json_encode($payload));

        $signature = '';
        $signSuccess = @openssl_sign($base64UrlHeader . '.' . $base64UrlPayload, $signature, $privateKey, 'SHA256');
        if (!$signSuccess) {
            error_log("OpenSSL failed to sign FCM JWT");
            return json_encode(['error' => 'OpenSSL sign failed']);
        }

        $base64UrlSignature = base64UrlEncode($signature);
        $jwt = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;

        $ch = curl_init();
        if ($ch === false) {
            return json_encode(['error' => 'cURL init failed']);
        }

        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            return json_encode(['error' => 'OAuth token request failed']);
        }

        $tokenData   = json_decode((string)$result, true);
        $accessToken = isset($tokenData['access_token']) ? (string)$tokenData['access_token'] : '';
        if ($accessToken === '') {
            return json_encode(['error' => 'Could not acquire access token', 'details' => $tokenData]);
        }

        $topicStr = trim((string)$topic);
        if ($topicStr !== '') {
            $message = [
                "message" => [
                    "topic" => $topicStr,
                    "data"  => [
                        "title"   => (string)$title,
                        "msg"     => (string)$description,
                        "vibrate" => '3',
                        "icon"    => 'myIcon',
                        "sound"   => '1'
                    ]
                ]
            ];
        } else {
            $home_url       = isset($extra['home_url']) ? (string)$extra['home_url'] : '';
            $redirection    = isset($extra['redirection']) ? (string)$extra['redirection'] : '';
            $redirection_to = isset($extra['redirection_to']) ? (string)$extra['redirection_to'] : '';
            $game_id        = isset($extra['game_id']) ? (string)$extra['game_id'] : '';
            $action_url     = isset($extra['url']) ? (string)$extra['url'] : '';

            $message = [
                "message" => [
                    "token" => (string)$token,
                    "data"  => [
                        "title"          => (string)$title,
                        "description"    => (string)$description,
                        "date"           => $date,
                        "time"           => $time,
                        "icon"           => $home_url . 'img/icon.png',
                        "redirection"    => $redirection,
                        "redirection_to" => $redirection_to,
                        "game_id"        => $game_id,
                        "url"            => $action_url
                    ]
                ]
            ];
        }

        $ch = curl_init();
        if ($ch === false) {
            return json_encode(['error' => 'cURL init failed']);
        }

        curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $fcmResult = curl_exec($ch);
        curl_close($ch);

        return ($fcmResult !== false) ? (string)$fcmResult : json_encode(['error' => 'FCM send failed']);
    }
}
