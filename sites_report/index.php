<?php
declare(strict_types=1);

// Retrieve the date parameter from the query string
$rawDate = isset($_GET['date']) ? (string)$_GET['date'] : '';
$date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate) ? $rawDate : '';

// Map dates to their respective files
$fileMap = [
    '2024-11-18' => '18.txt',
    '2024-11-19' => '19.txt',
];

// Check if the provided date exists in the map
if ($date !== '' && array_key_exists($date, $fileMap)) {
    $fileName = basename($fileMap[$date]);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $fileName;
    
    // Check if the file exists before attempting to read it
    if (file_exists($filePath) && is_file($filePath)) {
        $content = file_get_contents($filePath);
        if ($content !== false) {
            header('Content-Type: text/plain; charset=UTF-8');
            echo $content;
        } else {
            echo "Error reading file.";
        }
    } else {
        echo "File not found.";
    }
} else {
    echo "No data found."; 
}
