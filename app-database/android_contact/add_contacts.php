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

require_once __DIR__ . "/functions.php";

$phoneListRaw = $_REQUEST['phone_list'] ?? '';
$userId = isset($_REQUEST['user_id']) ? trim((string)$_REQUEST['user_id']) : '';

$obj = [
    'status' => false,
    'msg'    => 'Fill all required fields !'
];

if ($phoneListRaw !== '' && $userId !== '') {
    $phone_list = is_array($phoneListRaw) ? $phoneListRaw : json_decode((string)$phoneListRaw, true);

    if (is_array($phone_list)) {
        $conn = (isset($db_obj) && method_exists($db_obj, 'get_connection')) ? $db_obj->get_connection() : null;

        if ($conn instanceof mysqli) {
            $stmt = mysqli_prepare($conn, "INSERT INTO android_contact_list (contact, name, user_id) VALUES (?, ?, ?)");
            if ($stmt) {
                foreach ($phone_list as $phone) {
                    $name    = isset($phone['name']) ? trim((string)$phone['name']) : '';
                    $contact = isset($phone['contact']) ? trim((string)$phone['contact']) : '';

                    if ($name === '' && $contact === '') {
                        continue;
                    }

                    mysqli_stmt_bind_param($stmt, "sss", $contact, $name, $userId);
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }

        $obj['status'] = true;
        $obj['msg']    = 'Contacts Added Successfully !';
    }
}

echo json_encode($obj, JSON_UNESCAPED_UNICODE);
exit;
