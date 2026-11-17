<?php 
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../config.php";

$rawInput = file_get_contents("php://input");
$data = (is_string($rawInput) && $rawInput !== '') ? json_decode($rawInput, true) : [];
if (!is_array($data)) {
    $data = [];
}

$first_name   = trim((string)($data['first_name'] ?? ''));
$last_name    = trim((string)($data['last_name'] ?? ''));
$username     = trim((string)($data['username'] ?? ''));
$mobile       = trim((string)($data['mobile'] ?? ''));
$passwordRaw  = (string)($data['password'] ?? '');
$device_id    = trim((string)($data['device_id'] ?? ''));
$device_token = trim((string)($data['device_token'] ?? ''));
$device_info  = trim((string)($data['device_info'] ?? ''));
$date_created = date('Y-m-d');

if ($first_name !== '' && $username !== '' && $mobile !== '' && $passwordRaw !== '') {
    $password = md5($passwordRaw);
    $err_msg = '';

    // Check username in users
    $stmtUser = mysqli_prepare($con, "SELECT id FROM users WHERE username = ?");
    $username_check = 0;
    if ($stmtUser) {
        mysqli_stmt_bind_param($stmtUser, "s", $username);
        mysqli_stmt_execute($stmtUser);
        $resUser = mysqli_stmt_get_result($stmtUser);
        $username_check = $resUser ? mysqli_num_rows($resUser) : 0;
        mysqli_stmt_close($stmtUser);
    }

    // Check mobile in users
    $stmtPhone = mysqli_prepare($con, "SELECT id FROM users WHERE mobile = ?");
    $phone_check = 0;
    if ($stmtPhone) {
        mysqli_stmt_bind_param($stmtPhone, "s", $mobile);
        mysqli_stmt_execute($stmtPhone);
        $resPhone = mysqli_stmt_get_result($stmtPhone);
        $phone_check = $resPhone ? mysqli_num_rows($resPhone) : 0;
        mysqli_stmt_close($stmtPhone);
    }

    if ($username_check > 0) {
        $err_msg .= 'Username Already Exist. ';
    }
    if ($phone_check > 0) {
        $err_msg .= 'Mobile No Already Exist. ';
    }

    if ($username_check === 0 && $phone_check === 0) {
        $stmtIns = mysqli_prepare($con, "INSERT INTO admins (firstname, lastname, username, mobile, password, status, date_created, device_id, device_token, device_info) VALUES (?, ?, ?, ?, ?, '0', ?, ?, ?, ?)");
        if ($stmtIns) {
            mysqli_stmt_bind_param(
                $stmtIns,
                "sssssssss",
                $first_name,
                $last_name,
                $username,
                $mobile,
                $password,
                $date_created,
                $device_id,
                $device_token,
                $device_info
            );
            $res = mysqli_stmt_execute($stmtIns);
            $last_id = mysqli_insert_id($con);
            mysqli_stmt_close($stmtIns);

            if ($res && $last_id >= 1) {
                $output = array(
                    "msg" => 'Account Created',
                    "status" => 200
                );
                echo json_encode($output);
            } else {
                $output = array(
                    "msg" => 'error creating account',
                    "status" => 401
                );
                echo json_encode($output);
            }
        } else {
            $output = array(
                "msg" => 'Database error',
                "status" => 401
            );
            echo json_encode($output);
        }
    } else {
        $output = array(
            "msg" => $err_msg,
            "status" => 401
        );
        echo json_encode($output);
    }
} else {
    $output = array(
        "msg" => 'Please Fill All Details. ',
        "status" => 401
    );
    echo json_encode($output);
}

if (isset($con) && $con instanceof mysqli) {
    mysqli_close($con);
}
