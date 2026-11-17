<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../config.php";

$API_ACCESS_KEY = 'AAAAl_fFjAM:APA91bH85JXEKmBcH1JxQ7wadtiOLPP1uhhyhc5Kg9mWWlz2TiUIvKCfBCzmLH_Aun0cWfLs2Z2Y8rqMsEDR61ZEVg9N3TwcZzZCBAhwQYkNgy4zd8IOPDWsOn-w-VHC3AYMngY0i6oU';

function sendPushNotification(string $to, string $title, string $description, string $action_url): string {
    global $API_ACCESS_KEY;
    if ($to === '') {
        return '';
    }

    $data = array(
        "to" => $to,  
        "priority" => "high",
        "data" => array(
            "title" => $title, 
            "description" => $description, 
            "date" => date('y-m-d'), 
            "time" => date('h:i A'),
            "redirection" => 0,
            "redirection_to" => 'home_page',
            "game_id" => 0,
            "icon" => 'https://dpboss.com/img/icon.png',
            "url" => $action_url
        )
    ); 

    $data_string = json_encode($data); 
    $headers = array('Authorization: key=' . $API_ACCESS_KEY, 'Content-Type: application/json'); 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send'); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch); 
    curl_close($ch); 
    return is_string($result) ? $result : '';
}

// get posted data
$rawInput = file_get_contents("php://input");
$data = (is_string($rawInput) && $rawInput !== '') ? json_decode($rawInput, true) : [];
if (!is_array($data)) {
    $data = [];
}

$username     = trim((string)($data['username'] ?? ''));
$passwordRaw  = (string)($data['password'] ?? '');
$device_token = trim((string)($data['device_token'] ?? ''));
$device_id    = trim((string)($data['device_id'] ?? ''));
$device_info  = trim((string)($data['device_info'] ?? ''));

if ($username !== '' && $device_id !== '' && $device_token !== '') {
    $password = md5($passwordRaw);
    $stmt = mysqli_prepare($con, "SELECT id, username, password, device_token, device_id FROM admins WHERE username = ? AND password = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res && $row = mysqli_fetch_assoc($res)) {
            mysqli_stmt_close($stmt);

            $stored_device_token = (string)($row['device_token'] ?? '');
            $stored_device_id    = (string)($row['device_id'] ?? '');
            $user_id             = (int)$row['id'];

            if ($stored_device_id !== $device_token || $stored_device_token !== $device_id) {
                $stmtUp = mysqli_prepare($con, "UPDATE admins SET device_token = ?, device_id = ?, device_info = ? WHERE id = ?");
                if ($stmtUp) {
                    mysqli_stmt_bind_param($stmtUp, "sssi", $device_token, $device_id, $device_info, $user_id);
                    mysqli_stmt_execute($stmtUp);
                    mysqli_stmt_close($stmtUp);
                }
                // remove session from old device
                if ($stored_device_token !== '') {
                    sendPushNotification($stored_device_token, 'remove_session', 'Looks like you’ve Logged in from a new device', 'action_url');
                }
            }

            $output = array(
                "msg" => 'Updated',
                "status" => 200
            );
            echo json_encode($output);
        } else {
            mysqli_stmt_close($stmt);
            $output = array(
                "msg" => 'Please Fill All Details.',
                "status" => 401
            );
            echo json_encode($output);
        }
    } else {
        $output = array(
            "msg" => 'Database error',
            "status" => 500
        );
        echo json_encode($output);
    }
} else {
    $output = array(
        "msg" => 'Please Fill All Details.',
        "status" => 401
    );
    echo json_encode($output);
}

if (isset($con) && $con instanceof mysqli) {
    mysqli_close($con);
}
