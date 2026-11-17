<?php
// Read the data from the text file
$resultFile = '../txtdb/xzavierJoaquinosia.txt';
$lines = file($resultFile, FILE_IGNORE_NEW_LINES);

// Process each line of the text file
$data = [];
foreach ($lines as $line) {
    $fields = explode("|", $line);

    $entry = [
        'order' => $fields[0],
        'gameName' => $fields[1],
        'gameResult' => $fields[2],
        'timeOpen' => $fields[3],
        'timeClose' => $fields[4],
        'gameBgcolor' => $fields[5],
        'gameFontcolor' => $fields[6],
        'gameLinecolor' => $fields[7],
        'frequency' => $fields[8],
        'liveTimeBefore' => $fields[9],
        'liveTimeAfter' => $fields[10],
        'updateDate' => $fields[11],
        'status' => $fields[12],
        'expiryDate' => $fields[13],
        'jodiLink' => $fields[14],
        'panelLink' => $fields[15],
        'temp_last_updated_result' => $fields[16]
    ];

    $data[] = $entry;
}

// Sort the data based on the "order" field
//usort($data, function ($a, $b) {
//    return $a['order'] - $b['order'];
//});

// Set the appropriate headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Return the data as JSON
echo json_encode($data);