<?php
// API endpoint to retrieve the weekly data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $File = '../txtdb/free-game.txt';
    $Data = file($File);
    
    // Assuming you want to return the first two lines of th
    $response = [
        'line0' => trim($Data[0]),
        'line1' => trim($Data[1]),
        'line2' => trim($Data[2]),
        'line3' => trim($Data[3]),
        'line4' => trim($Data[4]),
        'line5' => trim($Data[5]),
        'line6' => trim($Data[6]),
        'line7' => trim($Data[7]),
        'line8' => trim($Data[8]),
        'line9' => trim($Data[9]),
        'line10' => trim($Data[10]),
        'line11' => trim($Data[11]),
        'line12' => trim($Data[12]),
        'line13' => trim($Data[13]),
        'line14' => trim($Data[14]),
        'line15' => trim($Data[15]),
        'line16' => trim($Data[16]),
        'line17' => trim($Data[17]),
        'line18' => trim($Data[18]),
        'line19' => trim($Data[19]),
        'line20' => trim($Data[20]),
        'line21' => trim($Data[21]),
        'line22' => trim($Data[22]),
        'line23' => trim($Data[23]),
        'line24' => trim($Data[24]),
        'line25' => trim($Data[25]),
        'line26' => trim($Data[26]),
        'line27' => trim($Data[27]),
        'line28' => trim($Data[28]),
        'line29' => trim($Data[29]),
        'line30' => trim($Data[30]),
        'line31' => trim($Data[31]),
        'line32' => trim($Data[32]),
        'line33' => trim($Data[33]),
        'line34' => trim($Data[34]),
        'line35' => trim($Data[35]),
        'line36' => trim($Data[36]),
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}