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

$file = __DIR__ . '/../txtdb/golden-ank.txt';
if (file_exists($file) && is_readable($file)) {
    $content = file_get_contents($file);
    if ($content !== false) {
        echo $content;
        exit;
    }
}

echo '';
