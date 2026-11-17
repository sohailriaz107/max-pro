<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Database connection
$con = null;
try {
    $con = mysqli_init();
    if ($con) {
        $con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        @$con->real_connect('15.235.214.152', 'dpboss_net_api_user', 'qwertGfde#2', 'dpboss_net_api');
        if ($con->connect_error) {
            throw new Exception("Connection error: " . $con->connect_error);
        }
        $con->set_charset("utf8mb4");
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "msg"    => "Database connection failed"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$con) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "msg"    => "Database connection failed"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Get raw JSON input
$rawData = file_get_contents("php://input");
$data = null;
if (!empty($rawData)) {
    $decoded = json_decode($rawData);
    if ($decoded instanceof stdClass) {
        $data = $decoded;
    }
}

// Validate input
if ($data && isset($data->phone_list) && !empty($data->user_id) && is_array($data->phone_list)) {
    $user_id  = trim((string)$data->user_id);
    $username = isset($data->username) ? trim((string)$data->username) : '';
    $inserted_count = 0;

    $checkStmt = mysqli_prepare($con, "SELECT COUNT(*) as count FROM android_contact_list WHERE contact = ? AND user_id = ?");
    $insertStmt = mysqli_prepare($con, "INSERT INTO `android_contact_list` (`contact`, `name`, `user_id`, `username`) VALUES (?, ?, ?, ?)");

    if ($checkStmt && $insertStmt) {
        foreach ($data->phone_list as $phone) {
            $name    = isset($phone->name) ? trim((string)$phone->name) : '';
            $contact = isset($phone->contact) ? trim((string)$phone->contact) : '';

            if ($name === '' || $contact === '') {
                continue;
            }

            // Check for duplicate
            mysqli_stmt_bind_param($checkStmt, "ss", $contact, $user_id);
            mysqli_stmt_execute($checkStmt);
            $checkResult = mysqli_stmt_get_result($checkStmt);
            $count = 0;
            if ($checkResult && ($row = mysqli_fetch_assoc($checkResult))) {
                $count = (int)($row['count'] ?? 0);
            }

            if ($count === 0) {
                mysqli_stmt_bind_param($insertStmt, "ssss", $contact, $name, $user_id, $username);
                if (mysqli_stmt_execute($insertStmt)) {
                    $inserted_count++;
                }
            }
        }

        mysqli_stmt_close($checkStmt);
        mysqli_stmt_close($insertStmt);
    }

    echo json_encode([
        "status" => true,
        "msg"    => "Contacts added successfully! ($inserted_count new contact(s))"
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "status" => false,
        "msg"    => "Fill all required fields!"
    ], JSON_UNESCAPED_UNICODE);
}

exit;
