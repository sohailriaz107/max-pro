<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

date_default_timezone_set("Asia/Kolkata");

$filePath = __DIR__ . "/../txtdb/xzavierJoaquinosia.txt";
if (!file_exists($filePath) || !is_readable($filePath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Data file not found'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$gameName = isset($_GET['game_name']) ? trim((string)$_GET['game_name']) : '';
if ($gameName === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Game name is required'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$theDataRaw = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$result = null;

if (is_array($theDataRaw)) {
    foreach ($theDataRaw as $line) {
        $parts = explode("|", (string)$line);
        if (count($parts) < 3) {
            continue;
        }

        $currentGameName = trim((string)$parts[1]);
        $gameResult      = trim((string)$parts[2]);

        if (strcasecmp($currentGameName, $gameName) === 0) {
            $result = $gameResult;
            break;
        }
    }
}

if ($result !== null) {
    echo json_encode([
        'success'   => true,
        'game_name' => $gameName,
        'result'    => $result
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Game not found'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
