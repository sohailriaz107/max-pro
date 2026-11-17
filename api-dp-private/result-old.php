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

$theDataRaw = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$all_result = [];
$live_result = [];

$currentDate = date('d-m-Y');
$currentTime = strtotime(date('d-m-Y h:i A'));
$currentDay = strtolower(date("D", $currentTime));

/**
 * Step 1: All Result (original order)
 */
foreach ($theDataRaw as $line) {
    $parts = explode("|", $line);
    if (count($parts) < 18) continue;

    list(
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ) = $parts;

    $all_result[] = [
        'name' => $gameName,
        'result' => $gameResult,
        'open_time' => $timeOpen,
        'close_time' => $timeClose,
        'days_of_week' => $frequency,
        'updated_date' => $updateDate,
        'highlight' => $highlight,
        'final_ank' => $finalAnk
    ];
}

/**
 * Step 2: Live Result (sorted order)
 */
$theDataSorted = $theDataRaw;
natsort($theDataSorted);

foreach ($theDataSorted as $line) {
    $parts = explode("|", $line);
    if (count($parts) < 18) continue;

    list(
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ) = $parts;

    // Skip unwanted names
    if (in_array($gameName, [
        'SHRI SHUBH LAKSHMI', 'MP NIGHT', 'MP DAY', 'JYA',
        'silvee', 'sai', 'gold line', 'sridevi star line'
    ])) {
        continue;
    }

    // Expiry check
    if (strtotime(date('Y-m-d')) > strtotime(str_replace('/', '-', $expiryDate))) {
        continue;
    }

    if ($gameName=='KALYAN'||$gameName=='KALYAN NIGHT'||$gameName=='BOMBAY NIGHT'||$gameName=='OLD MAIN MUMBAI' || $gameName=='OLD BAZAR'||$gameName=='RATAN KHATRI'||$gameName=='MADHURI'|| $gameName=='MAIN BAZAR' || $gameName=='KUBER111' || $gameName=='KARNATAKA DAY' || $gameName=='MUMBAI DAY [MAIN]' || $gameName=='PRABHAT') {
    $highlight = true;
    } else {
        $highlight = false;
    }

    $timeO = strtotime(date('d-m-Y ' . $timeOpen));
    $timeC = strtotime(date('d-m-Y ' . $timeClose));

    $startTime = strtotime('-' . $liveTimeBefore . ' minutes', $timeO);
    $startTimeC = strtotime('-' . $liveTimeBefore . ' minutes', $timeC);
    $endTime = strtotime('+' . $liveTimeAfter . ' minutes', $timeO);
    $endTimeC = strtotime('+' . $liveTimeAfter . ' minutes', $timeC);

    // Adjust for PM–AM games
    if (strpos(date('h:i A', strtotime($timeOpen)), 'PM') && strpos(date('h:i A', strtotime($timeClose)), 'AM')) {
        if (date('A') == 'PM') {
            $startTimeC = strtotime('+1 day -' . $liveTimeBefore . ' minutes', $timeC);
            $endTimeC = strtotime('+1 day +' . $liveTimeAfter . ' minutes', $timeC);
        }
        if (date('H') <= 3) {
            $startTimeC = strtotime('-1 day -' . $liveTimeBefore . ' minutes', $timeC);
            if ($updateDate == date('d-m-Y', strtotime('-1 day', $currentTime))) {
                $updateDate = date('d-m-Y', strtotime('+1 day', strtotime($updateDate)));
            }
        }
    } else {
        if (date('A') == 'AM') {
            $startTimeC = strtotime('-1 day -' . $liveTimeBefore . ' minutes', $timeC);
            $endTimeC = strtotime('-1 day +' . $liveTimeAfter . ' minutes', $timeC);
            if ($updateDate != date('d-m-Y')) {
                $updateDate = date('d-m-Y', strtotime('+1 day', strtotime($updateDate)));
            }
        }
    }

    // Live Result Conditions
    $isLive = false;
    if ($status != 0) {
        if ($frequency == 7 || $frequency == 2 || $frequency == 6 || $frequency == 5) {
            if ($frequency == 6) {
                if ($currentDay == 'mon' || $currentDay == 'sun' || !in_array($currentDay, ['mon', 'sun'])) {
                    if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                        ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate == $currentDate)) {
                        $isLive = true;
                    }
                }
            } elseif ($frequency == 5) {
                if ($currentDay == 'mon' || $currentDay == 'sat' || !in_array($currentDay, ['mon', 'sun', 'sat'])) {
                    if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                        ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate == $currentDate)) {
                        $isLive = true;
                    }
                }
            } else {
                if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                    ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate == $currentDate)) {
                    $isLive = true;
                }
            }
        }
    }

    if ($isLive) {
        if ($updateDate != $currentDate) {
            $gameResult = 'Loading...';
        }
        $live_result[] = [
            'name' => $gameName,
            'result' => $gameResult,
            'open_time' => $timeOpen,
            'close_time' => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank' => $finalAnk
        ];
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Data retrieved successfully',
    'data' => [
        'live_result' => $live_result,
        'all_result' => $all_result
    ]
], JSON_PRETTY_PRINT);
