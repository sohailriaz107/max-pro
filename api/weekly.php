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

$weeklyFile = __DIR__ . '/../txtdb/weekly.txt';
$weeklyData = (file_exists($weeklyFile) && is_readable($weeklyFile)) ? (file($weeklyFile, FILE_IGNORE_NEW_LINES) ?: []) : [];

$response = [];
for ($i = 1; $i <= 26; $i++) {
    $idx = $i - 1;
    $response['line' . $i] = isset($weeklyData[$idx]) ? trim((string)$weeklyData[$idx]) : '';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
