<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../config.php";

$username = isset($_REQUEST['username']) ? trim((string)$_REQUEST['username']) : '';
$password = isset($_REQUEST['password']) ? (string)$_REQUEST['password'] : '';

$obj = [
    'status' => false,
    'msg' => ''
];

if ($username !== '' && $password !== '') {
    $passwordHash = md5($password);

    try {
        if (!isset($con) || !$con instanceof mysqli) {
            throw new Exception("Database connection unavailable");
        }

        $stmt = mysqli_prepare($con, "SELECT id, username FROM admins WHERE username = ? AND password = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $passwordHash);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $obj['status'] = true;
                $obj['msg'] = 'Welcome ' . $username;
            } else {
                $obj['status'] = false;
                $obj['msg'] = 'Wrong Username or Password!';
            }
            mysqli_stmt_close($stmt);
        } else {
            $obj['status'] = false;
            $obj['msg'] = 'Database query failed';
        }
    } catch (Throwable $e) {
        error_log("Login error: " . $e->getMessage());
        $obj['status'] = false;
        $obj['msg'] = 'Wrong Username or Password!';
    }
} else {
    $obj['status'] = false;
    $obj['msg'] = 'Please fill the respective fields !';
}

echo json_encode($obj, JSON_UNESCAPED_UNICODE);
