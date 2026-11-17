<?php
/**
 * 5-Minute Game Webhook
 *
 * Receives JSON webhook data and saves the latest payload to:
 * txtdb/5_minut_jackpot.json
 */

// ----------------------------------------------------
// CONFIG
// ----------------------------------------------------

define(
    'WEBHOOK_SECRET',
    'b110f61749cabc362dfdf3161c3b7868a08ad1603dd737eda031eebad4a35d04'
);

define(
    'JSON_FILE',
    __DIR__ . '/txtdb/5_minut_jackpot.json'
);


// ----------------------------------------------------
// RESPONSE HEADER
// ----------------------------------------------------

header('Content-Type: application/json; charset=utf-8');


// ----------------------------------------------------
// ONLY POST ALLOWED
// ----------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}


// ----------------------------------------------------
// CHECK WEBHOOK SECRET
// ----------------------------------------------------

$received_secret = $_SERVER['HTTP_X_WEBHOOK_SECRET'] ?? '';

if (!hash_equals(WEBHOOK_SECRET, $received_secret)) {

    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized'
    ]);

    exit;
}


// ----------------------------------------------------
// GET RAW JSON DATA
// ----------------------------------------------------

$raw_data = file_get_contents('php://input');

if ($raw_data === false || trim($raw_data) === '') {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Empty request body'
    ]);

    exit;
}


// ----------------------------------------------------
// DECODE JSON
// ----------------------------------------------------

$data = json_decode($raw_data, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON',
        'error'   => json_last_error_msg()
    ]);

    exit;
}


// ----------------------------------------------------
// OPTIONAL: CHECK EVENT
// ----------------------------------------------------

$allowed_events = [
    'market.started',
    'result.declared',
    'market.ended'
];

$event = $data['event'] ?? '';

if (!in_array($event, $allowed_events, true)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Unknown event',
        'event'   => $event
    ]);

    exit;
}


// ----------------------------------------------------
// CHECK DATA DIRECTORY
// ----------------------------------------------------

$directory = dirname(JSON_FILE);

if (!is_dir($directory)) {

    if (!mkdir($directory, 0755, true)) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Unable to create data directory'
        ]);

        exit;
    }
}


// ----------------------------------------------------
// ADD SERVER RECEIVED TIME
// ----------------------------------------------------
// This does not modify the original webhook data.
// It helps you know when your server received it.

$data['_webhook_received_at'] = date('c');


// ----------------------------------------------------
// CONVERT TO JSON
// ----------------------------------------------------

$json = json_encode(
    $data,
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE
);

if ($json === false) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to encode JSON',
        'error'   => json_last_error_msg()
    ]);

    exit;
}


// ----------------------------------------------------
// SAVE FILE ATOMICALLY
// ----------------------------------------------------
// LOCK_EX prevents simultaneous webhook requests
// from corrupting the JSON file.

$result = file_put_contents(
    JSON_FILE,
    $json . PHP_EOL,
    LOCK_EX
);

if ($result === false) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to save webhook data'
    ]);

    exit;
}


// ----------------------------------------------------
// SUCCESS
// ----------------------------------------------------

http_response_code(200);

echo json_encode([
    'success' => true,
    'message' => 'Webhook received and saved',
    'event'   => $event
]);

exit;
?>