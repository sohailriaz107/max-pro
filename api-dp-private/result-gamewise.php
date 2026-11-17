<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Kolkata");

$filePath = "../txtdb/xzavierJoaquinosia.txt";
if (!file_exists($filePath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Data file not found'
    ]);
    exit;
}

$gameName = isset($_GET['game_name']) ? trim($_GET['game_name']) : '';
if (empty($gameName)) {
    echo json_encode([
        'success' => false,
        'message' => 'Game name is required'
    ]);
    exit;
}

$theDataRaw = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$result = null;

foreach ($theDataRaw as $line) {
    $parts = explode("|", $line);
    if (count($parts) < 3) continue;

    list($order, $currentGameName, $gameResult) = $parts;

    if (strcasecmp($currentGameName, $gameName) === 0) { // Case-insensitive match
        $result = trim($gameResult);
        break;
    }
}

if ($result !== null) {
    echo json_encode([
        'success' => true,
        'game_name' => $gameName,
        'result' => $result
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Game not found'
    ], JSON_PRETTY_PRINT);
}
?>