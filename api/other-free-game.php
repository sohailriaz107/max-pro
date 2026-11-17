<?php
// Read the data from the text file
$File = '../txtdb/other-free-game.txt';
$lines = file($File, FILE_IGNORE_NEW_LINES);

// Process each line of the text file
$data = [];
foreach ($lines as $line) {
    $fields = explode("|", $line);

    $entry = [
        'gameName' => $fields[0],
        'open' => $fields[1],
        'panna' => $fields[2],
        'jodi' => $fields[3],
        'close' => $fields[4]
    ];

    $data[] = $entry;
}

// Set the appropriate headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Return the data as JSON
echo json_encode($data);