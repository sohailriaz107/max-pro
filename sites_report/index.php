<?php
// Retrieve the date parameter from the query string
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Map dates to their respective files
$fileMap = [
    '2024-11-18' => '18.txt',
    '2024-11-19' => '19.txt',
];

// Check if the provided date exists in the map
if (array_key_exists($date, $fileMap)) {
    $filePath = $fileMap[$date];
    
    // Check if the file exists before attempting to read it
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        echo $content;
    } else {
        echo "File not found.";
    }
} else {
    echo "No data found."; 
}
