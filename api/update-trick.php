<?php
declare(strict_types=1);

header('Content-Type: text/plain; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$adFile = __DIR__ . '/../txtdb/update-trick.txt';
if (file_exists($adFile) && is_readable($adFile)) {
    $content = file_get_contents($adFile);
    if ($content !== false && trim($content) !== '') {
        echo $content;
        exit;
    }
}

echo '';
