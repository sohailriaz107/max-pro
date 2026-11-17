<?php
// API endpoint to retrieve the weekly data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $File = '../txtdb/num_kalyan.txt';
    $Data = file($File);
	
	$File1 = '../txtdb/num_main_bazar.txt';
    $Data1 = file($File1);
    
    // Assuming you want to return the first two lines of th
    $response = [
        'line0' => trim($Data[0]),
        'line1' => trim($Data[1]),
        'line2' => trim($Data[2]),
        'line3' => trim($Data[3]),
        'line4' => trim($Data[4]),
        'line5' => trim($Data[5]),
        'line6' => trim($Data1[0]),
        'line7' => trim($Data1[1]),
        'line8' => trim($Data1[2]),
        'line9' => trim($Data1[3]),
        'line10' => trim($Data1[4]),
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}