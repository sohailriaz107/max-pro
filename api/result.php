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

$resultFile = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
$lines = (file_exists($resultFile) && is_readable($resultFile)) ? (file($resultFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$data = [];
foreach ($lines as $line) {
    $lineStr = trim((string)$line);
    if ($lineStr === '') {
        continue;
    }

    $fields = explode("|", $lineStr);

    $entry = [
        'order'                    => isset($fields[0]) ? (string)$fields[0] : '',
        'gameName'                 => isset($fields[1]) ? (string)$fields[1] : '',
        'gameResult'               => isset($fields[2]) ? (string)$fields[2] : '',
        'timeOpen'                 => isset($fields[3]) ? (string)$fields[3] : '',
        'timeClose'                => isset($fields[4]) ? (string)$fields[4] : '',
        'gameBgcolor'              => isset($fields[5]) ? (string)$fields[5] : '',
        'gameFontcolor'            => isset($fields[6]) ? (string)$fields[6] : '',
        'gameLinecolor'            => isset($fields[7]) ? (string)$fields[7] : '',
        'frequency'                => isset($fields[8]) ? (string)$fields[8] : '',
        'liveTimeBefore'           => isset($fields[9]) ? (string)$fields[9] : '',
        'liveTimeAfter'            => isset($fields[10]) ? (string)$fields[10] : '',
        'updateDate'               => isset($fields[11]) ? (string)$fields[11] : '',
        'status'                   => isset($fields[12]) ? (string)$fields[12] : '',
        'expiryDate'               => isset($fields[13]) ? (string)$fields[13] : '',
        'jodiLink'                 => isset($fields[14]) ? (string)$fields[14] : '',
        'panelLink'                => isset($fields[15]) ? (string)$fields[15] : '',
        'temp_last_updated_result' => isset($fields[16]) ? (string)$fields[16] : ''
    ];

    $data[] = $entry;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
