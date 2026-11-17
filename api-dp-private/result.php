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

// Load free game data
$free_games = [];

// Load free-game.txt
$free_file_path = '../txtdb/free-game.txt';
if (file_exists($free_file_path)) {
    $free_lines = file($free_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!empty($free_lines)) {
        array_shift($free_lines); // Skip date
        for ($i = 0; $i < count($free_lines); $i += 4) {
            if (isset($free_lines[$i + 3])) {
                $game_name = trim($free_lines[$i]);
                $singles_str = trim($free_lines[$i + 1]);
                $pattis_str = trim($free_lines[$i + 2]);
                $jodis_str = trim($free_lines[$i + 3]);

                $singles = (strpos($singles_str, '*') === false) ? explode('-', $singles_str) : [];
                $pattis = (strpos($pattis_str, '*') === false) ? explode('-', $pattis_str) : [];
                $jodis = (strpos($jodis_str, '*') === false) ? explode('-', $jodis_str) : [];

                $free_games[$game_name] = [
                    'singles' => $singles,
                    'pattis' => $pattis,
                    'jodis' => $jodis
                ];
            }
        }
    }
}

// Load other-free-game.txt
$other_free_file_path = '../txtdb/other-free-game.txt';
if (file_exists($other_free_file_path)) {
    $other_lines = file($other_free_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($other_lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) >= 4) {
            $game_name = trim($parts[0]);
            $singles_str = trim($parts[1]);
            $pattis_str = trim($parts[2]);
            $jodis_str = trim($parts[3]);

            $singles = explode('-', $singles_str);
            $pattis = explode('-', $pattis_str);
            $jodis = explode('-', $jodis_str);

            $free_games[$game_name] = [
                'singles' => $singles,
                'pattis' => $pattis,
                'jodis' => $jodis
            ];
        }
    }
}

/**
 * Step 1: All Result (original order)
 */
foreach ($theDataRaw as $line) {
    $parts = explode("|", $line);
    if (count($parts) < 16) continue;

    list(
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ) = $parts;

    if ($gameName=='KALYAN'||$gameName=='KALYAN NIGHT'||$gameName=='BOMBAY NIGHT'||$gameName=='OLD MAIN MUMBAI' || $gameName=='OLD BAZAR'||$gameName=='RATAN KHATRI'||$gameName=='KALYAN MORNING'|| $gameName=='MAIN BAZAR' || $gameName=='KUBER111' || $gameName=='KARNATAKA DAY' || $gameName=='MUMBAI DAY [MAIN]' || $gameName=='PRABHAT') {
    $highlight = true;
    } else {
        $highlight = false;
    }

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


    $khabar_games = [
    'MAIN BAZAR MORNING', 'TIME BAZAR', 'BOMBAY DAY', 'KALYAN',
    'MILAN DAY', 'MILAN NIGHT', 'KALYAN MORNING', 'MILAN MORNING',
    'KALYAN NIGHT', 'RAJDHANI DAY', 'RAJDHANI NIGHT', 'MAIN BAZAR',
    'SRIDEVI', 'SRIDEVI NIGHT', 'OLD MAIN MUMBAI', 'SUPER DAY',
    'KARNATAKA DAY', 'MUMBAI MORNING', 'MAIN KALYAN', 'PRABHAT',
    'KHAJANA', 'NEW TIME BAZAR'
];




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

        $extra_text = '';

        if (isset($free_games[$gameName]) && $gameResult != 'Loading...' && strpos($gameResult, '*') === false) {
            $result_array = explode('-', $gameResult);
            if (count($result_array) >= 2) {
                $result_panna_open = trim($result_array[0]);
                $result_jodi_tmp = trim($result_array[1]);
                $result_panna_close = isset($result_array[2]) ? trim($result_array[2]) : '';

                $result_ank_open = substr($result_jodi_tmp, 0, 1);
                $result_ank_close = substr($result_jodi_tmp, 1, 1);
                $result_jodi = (strlen($result_jodi_tmp) == 2) ? $result_jodi_tmp : '';
                if ($result_jodi == '') {
                    $result_ank_close = '';
                }

                $messages = [];
                $free = $free_games[$gameName];

                if ($result_ank_open && in_array($result_ank_open, $free['singles'])) {
                    $messages[] = "open $result_ank_open Pass.";
                }
                if ($result_ank_close && in_array($result_ank_close, $free['singles'])) {
                    $messages[] = "close $result_ank_close Pass.";
                }
                if ($result_jodi && in_array($result_jodi, $free['jodis'])) {
                    $messages[] = "jodi $result_jodi pass.";
                }
                if ($result_panna_open && in_array($result_panna_open, $free['pattis'])) {
                    $messages[] = "open se panna $result_panna_open pass.";
                }
                if ($result_panna_close && in_array($result_panna_close, $free['pattis'])) {
                    $messages[] = "close se panna $result_panna_close pass.";
                }

                if (!empty($messages)) {
                    $game_display_name = ucfirst(strtolower($gameName));
                    //$extra_text = $game_display_name . " Free game se " . implode(" ", $messages);
                    $extra_text = "Free game se " . implode(" ", $messages);
                }
            }
        }


$khabar_text = '';

if (in_array($gameName, $khabar_games)) {
    if ($gameResult === 'Loading...') {
        $khabar_text = 'खबर लाइन चालू है';
    } else {
        $khabar_text = 'सबसे तेज सबसे सही';
    }
}


        $live_result[] = [
            'name' => $gameName,
            'result' => $gameResult,
            'open_time' => $timeOpen,
            'close_time' => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank' => $finalAnk,
            'extra_text' => $extra_text,
            'khabar_line' => $khabar_text
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