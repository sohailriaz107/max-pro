<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$file = __DIR__ . '/../txtdb/free-game.txt';
$data = [];

if (file_exists($file) && is_readable($file)) {
    $raw = file($file, FILE_IGNORE_NEW_LINES);
    if (is_array($raw)) {
        $data = $raw;
    }
}

$response = [];
for ($i = 0; $i <= 36; $i++) {
    $response['line' . $i] = isset($data[$i]) ? trim((string)$data[$i]) : '';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
