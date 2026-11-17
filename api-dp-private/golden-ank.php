<?php
header("Content-Type: application/json");

// File path
$file = '../txtdb/golden-ank.txt';

// Check if file exists
if (!file_exists($file)) {
    echo json_encode([
        "status" => "error",
        "message" => "Golden ank file not."
    ]);
    exit;
}

// Read content
$content = trim(file_get_contents($file));

// Prepare response
$response = [
    "status" => "success",
    "golden_ank" => $content
];

// Output JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
