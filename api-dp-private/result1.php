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
 * Step 0: Load Free Game Zone data
 */
$freeGamesData = [];

// Parse other-free-game.txt
$otherFile = "../txtdb/other-free-game.txt";
if (file_exists($otherFile)) {
    $lines = file($otherFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) >= 4) {
            $gname = trim($parts[0]);
            $single = array_filter(explode("-", trim($parts[1])));
            $patti  = array_filter(explode("-", trim($parts[2])));
            $jodi   = array_filter(explode("-", trim($parts[3])));
            $freeGamesData[strtoupper($gname)] = [
                'single' => $single,
                'patti'  => $patti,
                'jodi'   => $jodi
            ];
        }
    }
}

// Parse free-game.txt
$freeFile = "../txtdb/free-game.txt";
if (file_exists($freeFile)) {
    $lines = file($freeFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // First line is date, so skip it
    $i = 1;
    while ($i < count($lines)) {
        $gname = strtoupper(trim($lines[$i] ?? ''));
        $single = array_filter(explode("-", trim($lines[$i+1] ?? '')));
        $patti  = array_filter(explode("-", trim($lines[$i+2] ?? '')));
        $jodi   = array_filter(explode("-", trim($lines[$i+3] ?? '')));

        // Ignore entries with * or *** (meaning no data)
        if ($gname && !in_array('*', $single) && !in_array('***', $patti)) {
            $freeGamesData[$gname] = [
                'single' => $single,
                'patti'  => $patti,
                'jodi'   => $jodi
            ];
        }
        $i += 4;
    }
}

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

    // Highlight list
    if (in_array($gameName, [
        'KALYAN', 'KALYAN NIGHT', 'BOMBAY NIGHT', 'OLD MAIN MUMBAI',
        'OLD BAZAR', 'RATAN KHATRI', 'MADHURI', 'MAIN BAZAR',
        'KUBER111', 'KARNATAKA DAY', 'MUMBAI DAY [MAIN]', 'PRABHAT'
    ])) {
        $highlight = true;
    } else {
        $highlight = false;
    }

    $highlight = false; // default
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

        // Default extra text
        $extraText = '';

        // Match only if we have free game data for this game
        $gkey = strtoupper($gameName);
        if (!empty($freeGamesData[$gkey]) && strpos($gameResult, '-') !== false && $gameResult != 'Loading...') {
            $fg = $freeGamesData[$gkey];

            // Extract parts from result
            $result_array = explode('-', $gameResult);
            $result_panna_open  = $result_array[0] ?? '';
            $result_jodi_tmp    = $result_array[1] ?? '';
            $result_panna_close = $result_array[2] ?? '';

            $result_ank_open  = substr($result_jodi_tmp, 0, 1);
            $result_ank_close = substr($result_jodi_tmp, 1, 1);
            $result_jodi      = (strlen($result_jodi_tmp) == 2) ? $result_jodi_tmp : '';

            $msgs = [];

            if (in_array($result_ank_open, $fg['single'])) {
                $msgs[] = "$gameName free game se open $result_ank_open pass.";
            }
            if (in_array($result_ank_close, $fg['single'])) {
                $msgs[] = "$gameName free game se close $result_ank_close pass.";
            }
            if ($result_jodi && in_array($result_jodi, $fg['jodi'])) {
                $msgs[] = "$gameName free game se jodi $result_jodi pass.";
            }
            if ($result_panna_open && in_array($result_panna_open, $fg['patti'])) {
                $msgs[] = "$gameName free game open se panna $result_panna_open pass.";
            }
            if ($result_panna_close && in_array($result_panna_close, $fg['patti'])) {
                $msgs[] = "$gameName free game close se panna $result_panna_close pass.";
            }

            $extraText = implode(' ', $msgs);
        }

        $live_result[] = [
            'name' => $gameName,
            'result' => $gameResult,
            'open_time' => $timeOpen,
            'close_time' => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank' => $finalAnk,
            'extra_text' => $extraText
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
]);
