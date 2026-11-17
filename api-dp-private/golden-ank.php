<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$file = __DIR__ . '/../txtdb/golden-ank.txt';

if (!file_exists($file) || !is_readable($file)) {
    echo json_encode([
        "status" => "error",
        "message" => "Golden ank file not found."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

$content = file_get_contents($file);
$contentStr = ($content !== false) ? trim($content) : '';

$response = [
    "status" => "success",
    "golden_ank" => $contentStr
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
