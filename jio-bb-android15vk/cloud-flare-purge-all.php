<?php
// Start session to store redirect info
session_start();

// Replace these with your real Cloudflare API details
$cloudflare_api_token = 'eV7QJE2CV2Sc3qOV-KPyECvXTsJVaogRQjnxuU6m'; // Bearer Token
$zone_ids = [
    '692b29f71420d40021b5bdfdb80dcd66', //spboss.mobi
    'a74e3dbf5d5cd31cec4d7f4761fb49a5' //dpbossss.com
];
// Get the referring page to redirect later
$redirect_url = $_SERVER['HTTP_REFERER'] ?? '/';

// Only run if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cache'])) {
    $results = [];

    foreach ($zone_ids as $zone_id) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/{$zone_id}/purge_cache");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$cloudflare_api_token}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "purge_everything" => true
        ]));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($http_code === 200) {
            $data = json_decode($response, true);
            if ($data['success']) {
                $results[] = "Cache cleared successfully for Zone ID: {$zone_id}.";
            } else {
                $results[] = "Failed to clear cache for Zone ID: {$zone_id}: " . json_encode($data['errors']);
            }
        } else {
            $results[] = "CURL Error for Zone ID: {$zone_id}: {$error}";
        }
    }
} else {
    $results[] = "Invalid Request.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cloudflare Cache Purge</title>
    <meta http-equiv="refresh" content="3;url=<?php echo htmlspecialchars($redirect_url); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
            text-align: center;
        }
        .message {
            background: #fff;
            display: inline-block;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>Cache Clear Status</h2>
        <?php foreach ($results as $msg): ?>
            <p><?php echo htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
        <p>You will be redirected back shortly...</p>
    </div>
</body>
</html>
