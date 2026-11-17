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

$file  = __DIR__ . '/../txtdb/num_kalyan.txt';
$file1 = __DIR__ . '/../txtdb/num_main_bazar.txt';

$data  = (file_exists($file) && is_readable($file)) ? (file($file, FILE_IGNORE_NEW_LINES) ?: []) : [];
$data1 = (file_exists($file1) && is_readable($file1)) ? (file($file1, FILE_IGNORE_NEW_LINES) ?: []) : [];

$response = [
    'line0'  => isset($data[0]) ? trim((string)$data[0]) : '',
    'line1'  => isset($data[1]) ? trim((string)$data[1]) : '',
    'line2'  => isset($data[2]) ? trim((string)$data[2]) : '',
    'line3'  => isset($data[3]) ? trim((string)$data[3]) : '',
    'line4'  => isset($data[4]) ? trim((string)$data[4]) : '',
    'line5'  => isset($data[5]) ? trim((string)$data[5]) : '',
    'line6'  => isset($data1[0]) ? trim((string)$data1[0]) : '',
    'line7'  => isset($data1[1]) ? trim((string)$data1[1]) : '',
    'line8'  => isset($data1[2]) ? trim((string)$data1[2]) : '',
    'line9'  => isset($data1[3]) ? trim((string)$data1[3]) : '',
    'line10' => isset($data1[4]) ? trim((string)$data1[4]) : '',
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
