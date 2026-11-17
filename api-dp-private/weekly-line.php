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

$file_path = __DIR__ . '/../txtdb/weekly.txt';

if (!file_exists($file_path) || !is_readable($file_path)) {
    echo json_encode(['error' => 'File not found'], JSON_UNESCAPED_UNICODE);
    exit;
}

$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!is_array($lines)) {
    $lines = [];
}

// Headings
$panna_heading = isset($lines[0]) ? trim((string)$lines[0]) : '';
$ank_heading   = isset($lines[11]) ? trim((string)$lines[11]) : '';
$jodi_heading  = isset($lines[19]) ? trim((string)$lines[19]) : '';

// Data Ranges
$panna_lines = array_slice($lines, 1, 10);   // Lines 2–11
$ank_lines   = array_slice($lines, 12, 7);   // Lines 13–19
$jodi_lines  = array_slice($lines, 20);      // Lines 21–end

// Panna
$panna = [];
foreach ($panna_lines as $line) {
    $parts = explode('-', (string)$line);
    $panna[] = array_slice(array_map('trim', $parts), 0, 4);
}

// Ank
$ank = [];
foreach ($ank_lines as $line) {
    $parts = explode('-', (string)$line);
    $ank[] = array_slice(array_map('trim', $parts), 0, 4);
}

// Jodi
$jodi = [];
foreach ($jodi_lines as $line) {
    $parts = preg_split('/\s+/', trim((string)$line));
    if (is_array($parts)) {
        $jodi[] = array_slice($parts, 0, 4);
    }
}

// Final Response
$response = [
    'panna_heading' => $panna_heading,
    'panna'         => $panna,

    'ank_heading'   => $ank_heading,
    'ank'           => $ank,

    'jodi_heading'  => $jodi_heading,
    'jodi'          => $jodi
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
