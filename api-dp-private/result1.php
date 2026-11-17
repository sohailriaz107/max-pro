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

/**
 * Step 0: Load Free Game Zone data
 */
$freeGamesData = [];

// Parse other-free-game.txt
$otherFile = __DIR__ . "/../txtdb/other-free-game.txt";
if (file_exists($otherFile) && is_readable($otherFile)) {
    $lines = file($otherFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $parts = explode("|", (string)$line);
            if (count($parts) >= 4) {
                $gname  = trim((string)$parts[0]);
                $single = array_values(array_filter(explode("-", trim((string)$parts[1]))));
                $patti  = array_values(array_filter(explode("-", trim((string)$parts[2]))));
                $jodi   = array_values(array_filter(explode("-", trim((string)$parts[3]))));
                $freeGamesData[strtoupper($gname)] = [
                    'single' => $single,
                    'patti'  => $patti,
                    'jodi'   => $jodi
                ];
            }
        }
    }
}

// Parse free-game.txt
$freeFile = __DIR__ . "/../txtdb/free-game.txt";
if (file_exists($freeFile) && is_readable($freeFile)) {
    $lines = file($freeFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        $totalLines = count($lines);
        $i = 1;
        while ($i < $totalLines) {
            $gname  = strtoupper(trim((string)($lines[$i] ?? '')));
            $single = array_values(array_filter(explode("-", trim((string)($lines[$i + 1] ?? '')))));
            $patti  = array_values(array_filter(explode("-", trim((string)($lines[$i + 2] ?? '')))));
            $jodi   = array_values(array_filter(explode("-", trim((string)($lines[$i + 3] ?? '')))));

            if ($gname !== '' && !in_array('*', $single, true) && !in_array('***', $patti, true)) {
                $freeGamesData[$gname] = [
                    'single' => $single,
                    'patti'  => $patti,
                    'jodi'   => $jodi
                ];
            }
            $i += 4;
        }
    }
}

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
    $openTimeTs  = strtotime($timeOpen);
    $closeTimeTs = strtotime($timeClose);
    if ($openTimeTs !== false && $closeTimeTs !== false && strpos(date('h:i A', $openTimeTs), 'PM') !== false && strpos(date('h:i A', $closeTimeTs), 'AM') !== false) {
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

        $extraText = '';

        $gkey = strtoupper($gameName);
        if (!empty($freeGamesData[$gkey]) && strpos($gameResult, '-') !== false && $gameResult !== 'Loading...') {
            $fg = $freeGamesData[$gkey];

            $result_array       = explode('-', $gameResult);
            $result_panna_open  = trim((string)($result_array[0] ?? ''));
            $result_jodi_tmp    = trim((string)($result_array[1] ?? ''));
            $result_panna_close = trim((string)($result_array[2] ?? ''));

            $result_ank_open  = substr($result_jodi_tmp, 0, 1);
            $result_ank_close = substr($result_jodi_tmp, 1, 1);
            $result_jodi      = (strlen($result_jodi_tmp) === 2) ? $result_jodi_tmp : '';

            $msgs = [];

            if ($result_ank_open !== '' && in_array($result_ank_open, $fg['single'], true)) {
                $msgs[] = "$gameName free game se open $result_ank_open pass.";
            }
            if ($result_ank_close !== '' && in_array($result_ank_close, $fg['single'], true)) {
                $msgs[] = "$gameName free game se close $result_ank_close pass.";
            }
            if ($result_jodi !== '' && in_array($result_jodi, $fg['jodi'], true)) {
                $msgs[] = "$gameName free game se jodi $result_jodi pass.";
            }
            if ($result_panna_open !== '' && in_array($result_panna_open, $fg['patti'], true)) {
                $msgs[] = "$gameName free game open se panna $result_panna_open pass.";
            }
            if ($result_panna_close !== '' && in_array($result_panna_close, $fg['patti'], true)) {
                $msgs[] = "$gameName free game close se panna $result_panna_close pass.";
            }

            $extraText = implode(' ', $msgs);
        }

        $live_result[] = [
            'name'         => $gameName,
            'result'       => $gameResult,
            'open_time'    => $timeOpen,
            'close_time'   => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank'    => $finalAnk,
            'extra_text'   => $extraText
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
