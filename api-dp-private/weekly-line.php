<?php
header('Content-Type: application/json');

$file_path = '../txtdb/weekly.txt';

if (!file_exists($file_path)) {
    echo json_encode(['error' => 'File not found']);
    exit;
}

$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Headings
$panna_heading = trim($lines[0]);
$ank_heading = trim($lines[11]);
$jodi_heading = trim($lines[19]);

// Data Ranges
$panna_lines = array_slice($lines, 1, 10);   // Lines 2–11
$ank_lines = array_slice($lines, 12, 7);     // Lines 13–19
$jodi_lines = array_slice($lines, 20);       // Lines 21–end

// Panna
$panna = [];
foreach ($panna_lines as $line) {
    $parts = explode('-', $line);
    $panna[] = array_slice(array_map('trim', $parts), 0, 4);
}

// Ank
$ank = [];
foreach ($ank_lines as $line) {
    $parts = explode('-', $line);
    $ank[] = array_slice(array_map('trim', $parts), 0, 4);
}

// Jodi
$jodi = [];
foreach ($jodi_lines as $line) {
    $parts = preg_split('/\s+/', trim($line));
    $jodi[] = array_slice($parts, 0, 4);
}

// Final Response
$response = [
    'panna_heading' => $panna_heading,
    'panna' => $panna,

    'ank_heading' => $ank_heading,
    'ank' => $ank,

    'jodi_heading' => $jodi_heading,
    'jodi' => $jodi
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
