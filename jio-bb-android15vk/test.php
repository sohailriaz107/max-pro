<?php
// Enable PHP error reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1); // Show errors on screen
ini_set('log_errors', 1);     // Log PHP errors to a file
date_default_timezone_set('Asia/Kolkata'); 
echo 'Start<br>';
$gName_for_api = 'MAIN BAZAR MORNING';
$gResult_for_api = '345-23-874';

$url = "https://app.dpbossx.net/guessing-forum-post-by-api-v2.php?game_name=" . urlencode($gName_for_api) . "&gameResult=" . urlencode($gResult_for_api);
  
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, "");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 
echo $response = curl_exec($curl);

if (curl_errno($curl)) {
    $error_msg = date("Y-m-d H:i:s") . " - cURL Error: " . curl_error($curl) . PHP_EOL;
    $fp = fopen('result_post_api_response.txt', 'a'); // Open file in append mode  
    fwrite($fp, $error_msg);
    fwrite($fp, "\n\n================\n\n");
    fclose($fp);

} else {
    $response_to_save = date("Y-m-d H:i:s") . " - " . $response . PHP_EOL;
    $fp = fopen('result_post_api_response.txt', 'a'); // Open file in append mode  
    fwrite($fp, $response_to_save);
    fwrite($fp, "\n\n================\n\n");
    fclose($fp);
}

curl_close($curl);
echo '<br>end';

