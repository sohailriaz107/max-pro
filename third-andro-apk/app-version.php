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

$version = isset($data['version']) ? trim((string)$data['version']) : '';
$package_name = isset($data['package_name']) ? trim((string)$data['package_name']) : '';

$app = array(
    "version" => '2.0.0',
    "apk" => 'https://dpboss.com/apk/third-party-admin.apk',
    "date" => '2023-04-20',
    "onehourlogout" => 1
);

echo json_encode($app);

if (isset($con) && $con instanceof mysqli) {
    mysqli_close($con);
}
