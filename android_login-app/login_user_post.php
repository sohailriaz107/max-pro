<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../config.php";

function sendPushNotification(string $to, string $title, string $description): void
{
    if (trim($to) === '') {
        return;
    }

    $data = [
        "to" => $to,
        "priority" => "high",
        "data" => [
            "title" => $title,
            "description" => $description
        ]
    ];

    $data_string = json_encode($data, JSON_UNESCAPED_UNICODE);
    $headers = [
        'Authorization: key=AAAAl_fFjAM:APA91bH85JXEKmBcH1JxQ7wadtiOLPP1uhhyhc5Kg9mWWlz2TiUIvKCfBCzmLH_Aun0cWfLs2Z2Y8rqMsEDR61ZEVg9N3TwcZzZCBAhwQYkNgy4zd8IOPDWsOn-w-VHC3AYMngY0i6oU',
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    if ($ch !== false) {
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

// get posted data
$rawData = file_get_contents("php://input");
$data = null;
if (!empty($rawData)) {
    $decoded = json_decode($rawData);
    if ($decoded instanceof stdClass) {
        $data = $decoded;
    }
}

$username     = isset($data->username) ? trim((string)$data->username) : '';
$password     = isset($data->password) ? (string)$data->password : '';
$device_token = isset($data->device_token) ? trim((string)$data->device_token) : '';
$device_id    = isset($data->device_id) ? trim((string)$data->device_id) : '';
$device_info  = isset($data->device_info) ? trim((string)$data->device_info) : '';
$app_version  = isset($data->app_version) ? trim((string)$data->app_version) : '';

if ($username !== '' && $device_id !== '' && $device_token !== '') {
    if ($app_version === '2.0.8') {
        echo json_encode([
            "msg" => 'Invalid Login details',
            "status" => 401
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    try {
        if (!isset($con) || !$con instanceof mysqli) {
            throw new Exception("Database connection unavailable");
        }

        $passwordHash = md5($password);
        $stmt = mysqli_prepare($con, "SELECT id, username, device_token, device_id FROM admins WHERE username = ? AND password = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $passwordHash);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && ($row = mysqli_fetch_assoc($result))) {
                $stored_device_token = (string)($row['device_token'] ?? '');
                $stored_device_id    = (string)($row['device_id'] ?? '');
                $user_id             = (int)$row['id'];

                if ($stored_device_id !== $device_id || $stored_device_token !== $device_token) {
                    $updateStmt = mysqli_prepare($con, "UPDATE admins SET device_token = ?, device_id = ?, device_info = ? WHERE id = ?");
                    if ($updateStmt) {
                        mysqli_stmt_bind_param($updateStmt, "sssi", $device_token, $device_id, $device_info, $user_id);
                        mysqli_stmt_execute($updateStmt);
                        mysqli_stmt_close($updateStmt);
                    }

                    if ($stored_device_token !== '') {
                        sendPushNotification($stored_device_token, 'remove_session', 'Looks like your Device Changed.');
                    }
                }

                echo json_encode([
                    "msg" => 'Welcome ' . $username,
                    "status" => 200
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    "msg" => 'Invalid Login details',
                    "status" => 401
                ], JSON_UNESCAPED_UNICODE);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode([
                "msg" => 'Invalid Login details',
                "status" => 401
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (Throwable $e) {
        error_log("Login post error: " . $e->getMessage());
        echo json_encode([
            "msg" => 'Invalid Login details',
            "status" => 401
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode([
        "msg" => 'Please Fill All Details.',
        "status" => 401
    ], JSON_UNESCAPED_UNICODE);
}
