<?php
header('Content-Type: application/json');

// File paths
$file1 = "../txtdb/main_original_sl.txt";
$file2 = "../txtdb/mr_star_line_results.txt";
$file3 = "../txtdb/main_bombay_36_results.txt";

// Labels
$sl1_time_labels = [
    "09:05 AM", "10:05 AM", "11:05 AM", "12:05 PM",
    "01:05 PM", "02:05 PM", "03:05 PM", "04:05 PM",
    "05:05 PM", "06:05 PM", "07:05 PM", "08:05 PM"
];

$sl2_time_labels = [
    "09:05 AM", "10:05 AM", "11:05 AM", "12:05 PM",
    "01:05 PM", "02:05 PM", "03:05 PM", "04:05 PM",
    "05:05 PM", "06:05 PM", "07:05 PM", "08:05 PM"
];

$sl3_time_labels = [
    "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM",
    "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM",
    "01:00 PM", "01:15 PM", "01:30 PM", "01:45 PM",
    "02:00 PM", "02:15 PM", "02:30 PM", "02:45 PM",
    "03:00 PM", "03:15 PM", "03:30 PM", "03:45 PM",
    "04:00 PM", "04:15 PM", "04:30 PM", "04:45 PM",
    "05:00 PM", "05:15 PM", "05:30 PM", "05:45 PM",
    "06:00 PM", "06:15 PM", "06:30 PM", "06:45 PM",
    "07:00 PM", "07:15 PM", "07:30 PM", "07:45 PM"
];

// Function to read file and return trimmed lines
function getResults($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('trim', $lines);
}

// Prepare data
$data = [
    [
        "heading" => "Main Starline",
        "labels"  => $sl1_time_labels,
        "results" => getResults($file1)
    ],
    [
        "heading" => "Bombay Rajshree",
        "labels"  => $sl2_time_labels,
        "results" => getResults($file2)
    ],
    [
        "heading" => "Main Bombay 36",
        "labels"  => $sl3_time_labels,
        "results" => getResults($file3)
    ]
];

// Output JSON
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
