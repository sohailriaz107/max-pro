<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cloudflare_api_token = 'eV7QJE2CV2Sc3qOV-KPyECvXTsJVaogRQjnxuU6m';
$zone_ids = [
    '692b29f71420d40021b5bdfdb80dcd66', // spboss.mobi
    'a74e3dbf5d5cd31cec4d7f4761fb49a5'  // dpbossss.com
];

// Validate redirect URL safely to prevent Open Redirect
$raw_referer = $_SERVER['HTTP_REFERER'] ?? 'update-results.php';
$parsed = parse_url($raw_referer);
$redirect_url = 'update-results.php';
if ($parsed !== false) {
    // If it's a relative path or on the same host, allow it
    if (!isset($parsed['host']) || (isset($_SERVER['HTTP_HOST']) && $parsed['host'] === $_SERVER['HTTP_HOST'])) {
        $redirect_url = $raw_referer;
    }
}

$results = [];

// Only run if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cache'])) {
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($http_code === 200 && is_string($response)) {
            $data = json_decode($response, true);
            if (is_array($data) && !empty($data['success'])) {
                $results[] = "Cache cleared successfully for Zone ID: {$zone_id}.";
            } else {
                $errDetail = isset($data['errors']) ? json_encode($data['errors']) : 'Unknown error';
                $results[] = "Failed to clear cache for Zone ID: {$zone_id}: " . $errDetail;
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
    <meta http-equiv="refresh" content="3;url=<?php echo htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>">
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
            <p><?php echo htmlspecialchars((string)$msg, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
        <p>You will be redirected back shortly...</p>
    </div>
</body>
</html>
