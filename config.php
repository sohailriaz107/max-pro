<?php 
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "dpboss_net_new";

$con = null;
try {
    mysqli_report(MYSQLI_REPORT_OFF);
    $con = mysqli_init();
    if ($con) {
        $con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        @$con->real_connect($dbHost, $dbUser, $dbPass, $dbName);
        if ($con && !$con->connect_error) {
            $con->set_charset("utf8mb4");
        }
    }
} catch (Throwable $e) {
    error_log("Database connection error in config.php: " . $e->getMessage());
}

$localJson = __DIR__ . '/dpboss-admin-third-party-firebase-adminsdk-flael-1b6231fe51.json';
$serverJson = '/home/websites/maxpro.dpboss.town/dpboss-admin-third-party-firebase-adminsdk-flael-1b6231fe51.json';
$service_json = file_exists($localJson) ? $localJson : $serverJson;

if (!defined('SERVICE_ACCOUNT_KEY_FCM')) {
    define('SERVICE_ACCOUNT_KEY_FCM', (string)$service_json);
}
