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

$theDataRaw = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (!is_array($theDataRaw)) {
    $theDataRaw = [];
}

$all_result = [];
$live_result = [];

$currentDate = date('d-m-Y');
$currentTime = strtotime(date('d-m-Y h:i A'));
$currentDay  = strtolower(date("D", $currentTime));

$highlightGames = [
    'KALYAN', 'KALYAN NIGHT', 'BOMBAY NIGHT', 'OLD MAIN MUMBAI',
    'OLD BAZAR', 'RATAN KHATRI', 'MADHURI', 'MAIN BAZAR',
    'KUBER111', 'KARNATAKA DAY', 'MUMBAI DAY [MAIN]', 'PRABHAT'
];

/**
 * Step 1: All Result (original order)
 */
foreach ($theDataRaw as $line) {
    $parts = explode("|", (string)$line);
    if (count($parts) < 18) {
        continue;
    }

    [
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ] = array_slice($parts, 0, 18);

    $highlight = in_array($gameName, $highlightGames, true);

    $all_result[] = [
        'name'         => $gameName,
        'result'       => $gameResult,
        'open_time'    => $timeOpen,
        'close_time'   => $timeClose,
        'days_of_week' => $frequency,
        'updated_date' => $updateDate,
        'highlight'    => $highlight,
        'final_ank'    => $finalAnk
    ];
}

/**
 * Step 2: Live Result (sorted order)
 */
$theDataSorted = $theDataRaw;
natsort($theDataSorted);

foreach ($theDataSorted as $line) {
    $parts = explode("|", (string)$line);
    if (count($parts) < 18) {
        continue;
    }

    [
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ] = array_slice($parts, 0, 18);

    // Skip unwanted names
    if (in_array($gameName, [
        'SHRI SHUBH LAKSHMI', 'MP NIGHT', 'MP DAY', 'JYA',
        'silvee', 'sai', 'gold line', 'sridevi star line'
    ], true)) {
        continue;
    }

    // Expiry check
    $expiryTimestamp = strtotime(str_replace('/', '-', (string)$expiryDate));
    if ($expiryTimestamp !== false && strtotime(date('Y-m-d')) > $expiryTimestamp) {
        continue;
    }

    $timeO = strtotime(date('d-m-Y ' . $timeOpen));
    $timeC = strtotime(date('d-m-Y ' . $timeClose));
    if ($timeO === false || $timeC === false) {
        continue;
    }

    $liveBefore = is_numeric($liveTimeBefore) ? (int)$liveTimeBefore : 0;
    $liveAfter  = is_numeric($liveTimeAfter) ? (int)$liveTimeAfter : 0;

    $startTime  = strtotime('-' . $liveBefore . ' minutes', $timeO);
    $startTimeC = strtotime('-' . $liveBefore . ' minutes', $timeC);
    $endTime    = strtotime('+' . $liveAfter . ' minutes', $timeO);
    $endTimeC   = strtotime('+' . $liveAfter . ' minutes', $timeC);

    // Adjust for PM–AM games
    $openStr = strtotime($timeOpen);
    $closeStr = strtotime($timeClose);
    if ($openStr !== false && $closeStr !== false && strpos(date('h:i A', $openStr), 'PM') !== false && strpos(date('h:i A', $closeStr), 'AM') !== false) {
        if (date('A') === 'PM') {
            $startTimeC = strtotime('+1 day -' . $liveBefore . ' minutes', $timeC);
            $endTimeC   = strtotime('+1 day +' . $liveAfter . ' minutes', $timeC);
        }
        if ((int)date('H') <= 3) {
            $startTimeC = strtotime('-1 day -' . $liveBefore . ' minutes', $timeC);
            if ($updateDate === date('d-m-Y', strtotime('-1 day', $currentTime))) {
                $updateDate = date('d-m-Y', strtotime('+1 day', strtotime($updateDate)));
            }
        }
    } else {
        if (date('A') === 'AM') {
            $startTimeC = strtotime('-1 day -' . $liveBefore . ' minutes', $timeC);
            $endTimeC   = strtotime('-1 day +' . $liveAfter . ' minutes', $timeC);
            if ($updateDate !== date('d-m-Y')) {
                $updateDate = date('d-m-Y', strtotime('+1 day', strtotime($updateDate)));
            }
        }
    }

    // Live Result Conditions
    $isLive = false;
    $freq = (int)$frequency;
    if ($status != 0) {
        if (in_array($freq, [7, 2, 6, 5], true)) {
            if ($freq === 6) {
                if ($currentDay === 'mon' || $currentDay === 'sun' || !in_array($currentDay, ['mon', 'sun'], true)) {
                    if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                        ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate === $currentDate)) {
                        $isLive = true;
                    }
                }
            } elseif ($freq === 5) {
                if ($currentDay === 'mon' || $currentDay === 'sat' || !in_array($currentDay, ['mon', 'sun', 'sat'], true)) {
                    if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                        ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate === $currentDate)) {
                        $isLive = true;
                    }
                }
            } else {
                if (($currentTime >= $startTime && $currentTime <= $endTime) ||
                    ($currentTime >= $startTimeC && $currentTime <= $endTimeC && $updateDate === $currentDate)) {
                    $isLive = true;
                }
            }
        }
    }

    if ($isLive) {
        if ($updateDate !== $currentDate) {
            $gameResult = 'Loading...';
        }
        $live_result[] = [
            'name'         => $gameName,
            'result'       => $gameResult,
            'open_time'    => $timeOpen,
            'close_time'   => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank'    => $finalAnk
        ];
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Data retrieved successfully',
    'data'    => [
        'live_result' => $live_result,
        'all_result'  => $all_result
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
