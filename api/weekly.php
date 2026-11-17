<?php
// API endpoint to retrieve the weekly data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $weeklyFile = '../txtdb/weekly.txt';
    $weeklyData = file($weeklyFile);
    
    // Assuming you want to return the first two lines of th
    $response = [
        'line1' => trim($weeklyData[0]),
        'line2' => trim($weeklyData[1]),
        'line3' => trim($weeklyData[2]),
        'line4' => trim($weeklyData[3]),
        'line5' => trim($weeklyData[4]),
        'line6' => trim($weeklyData[5]),
        'line7' => trim($weeklyData[6]),
        'line8' => trim($weeklyData[7]),
        'line9' => trim($weeklyData[8]),
        'line10' => trim($weeklyData[9]),
        'line11' => trim($weeklyData[10]),
        'line12' => trim($weeklyData[11]),
        'line13' => trim($weeklyData[12]),
        'line14' => trim($weeklyData[13]),
        'line15' => trim($weeklyData[14]),
        'line16' => trim($weeklyData[15]),
        'line17' => trim($weeklyData[16]),
        'line18' => trim($weeklyData[17]),
        'line19' => trim($weeklyData[18]),
        'line20' => trim($weeklyData[19]),
        'line21' => trim($weeklyData[20]),
        'line22' => trim($weeklyData[21]),
        'line23' => trim($weeklyData[22]),
        'line24' => trim($weeklyData[23]),
        'line25' => trim($weeklyData[24]),
        'line26' => trim($weeklyData[25]),
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}