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

$file = __DIR__ . '/../txtdb/other-free-game.txt';
$lines = (file_exists($file) && is_readable($file)) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$data = [];
foreach ($lines as $line) {
    $lineStr = trim((string)$line);
    if ($lineStr === '') {
        continue;
    }

    $fields = explode("|", $lineStr);

    $entry = [
        'gameName' => isset($fields[0]) ? trim((string)$fields[0]) : '',
        'open'     => isset($fields[1]) ? trim((string)$fields[1]) : '',
        'panna'    => isset($fields[2]) ? trim((string)$fields[2]) : '',
        'jodi'     => isset($fields[3]) ? trim((string)$fields[3]) : '',
        'close'    => isset($fields[4]) ? trim((string)$fields[4]) : ''
    ];

    $data[] = $entry;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
