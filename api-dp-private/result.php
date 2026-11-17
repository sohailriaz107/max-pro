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

// Load free game data
$free_games = [];

// Load free-game.txt
$free_file_path = __DIR__ . '/../txtdb/free-game.txt';
if (file_exists($free_file_path) && is_readable($free_file_path)) {
    $free_lines = file($free_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($free_lines) && !empty($free_lines)) {
        array_shift($free_lines); // Skip date
        $freeCount = count($free_lines);
        for ($i = 0; $i < $freeCount; $i += 4) {
            if (isset($free_lines[$i + 3])) {
                $game_name   = trim((string)$free_lines[$i]);
                $singles_str = trim((string)$free_lines[$i + 1]);
                $pattis_str  = trim((string)$free_lines[$i + 2]);
                $jodis_str   = trim((string)$free_lines[$i + 3]);

                $singles = (strpos($singles_str, '*') === false) ? explode('-', $singles_str) : [];
                $pattis  = (strpos($pattis_str, '*') === false) ? explode('-', $pattis_str) : [];
                $jodis   = (strpos($jodis_str, '*') === false) ? explode('-', $jodis_str) : [];

                $free_games[$game_name] = [
                    'singles' => $singles,
                    'pattis'  => $pattis,
                    'jodis'   => $jodis
                ];
            }
        }
    }
}

// Load other-free-game.txt
$other_free_file_path = __DIR__ . '/../txtdb/other-free-game.txt';
if (file_exists($other_free_file_path) && is_readable($other_free_file_path)) {
    $other_lines = file($other_free_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($other_lines)) {
        foreach ($other_lines as $line) {
            $parts = explode('|', (string)$line);
            if (count($parts) >= 4) {
                $game_name   = trim((string)$parts[0]);
                $singles_str = trim((string)$parts[1]);
                $pattis_str  = trim((string)$parts[2]);
                $jodis_str   = trim((string)$parts[3]);

                $singles = ($singles_str !== '') ? explode('-', $singles_str) : [];
                $pattis  = ($pattis_str !== '') ? explode('-', $pattis_str) : [];
                $jodis   = ($jodis_str !== '') ? explode('-', $jodis_str) : [];

                $free_games[$game_name] = [
                    'singles' => $singles,
                    'pattis'  => $pattis,
                    'jodis'   => $jodis
                ];
            }
        }
    }
}

$highlightGames = [
    'KALYAN', 'KALYAN NIGHT', 'BOMBAY NIGHT', 'OLD MAIN MUMBAI',
    'OLD BAZAR', 'RATAN KHATRI', 'KALYAN MORNING', 'MAIN BAZAR',
    'KUBER111', 'KARNATAKA DAY', 'MUMBAI DAY [MAIN]', 'PRABHAT'
];

/**
 * Step 1: All Result (original order)
 */
foreach ($theDataRaw as $line) {
    $parts = explode("|", (string)$line);
    if (count($parts) < 16) {
        continue;
    }

    $padded = array_pad($parts, 18, '');
    [
        $order, $gameName, $gameResult, $timeOpen, $timeClose,
        $gameBgcolor, $gameFontcolor, $gameLinecolor,
        $frequency, $liveTimeBefore, $liveTimeAfter,
        $updateDate, $status, $expiryDate,
        $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
    ] = array_slice($padded, 0, 18);

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

$khabar_games = [
    'MAIN BAZAR MORNING', 'TIME BAZAR', 'BOMBAY DAY', 'KALYAN',
    'MILAN DAY', 'MILAN NIGHT', 'KALYAN MORNING', 'MILAN MORNING',
    'KALYAN NIGHT', 'RAJDHANI DAY', 'RAJDHANI NIGHT', 'MAIN BAZAR',
    'SRIDEVI', 'SRIDEVI NIGHT', 'OLD MAIN MUMBAI', 'SUPER DAY',
    'KARNATAKA DAY', 'MUMBAI MORNING', 'MAIN KALYAN', 'PRABHAT',
    'KHAJANA', 'NEW TIME BAZAR'
];

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

        $extra_text = '';

        if (isset($free_games[$gameName]) && $gameResult !== 'Loading...' && strpos($gameResult, '*') === false) {
            $result_array = explode('-', $gameResult);
            if (count($result_array) >= 2) {
                $result_panna_open  = trim((string)$result_array[0]);
                $result_jodi_tmp    = trim((string)$result_array[1]);
                $result_panna_close = isset($result_array[2]) ? trim((string)$result_array[2]) : '';

                $result_ank_open  = substr($result_jodi_tmp, 0, 1);
                $result_ank_close = substr($result_jodi_tmp, 1, 1);
                $result_jodi      = (strlen($result_jodi_tmp) === 2) ? $result_jodi_tmp : '';
                if ($result_jodi === '') {
                    $result_ank_close = '';
                }

                $messages = [];
                $free = $free_games[$gameName];

                if ($result_ank_open !== '' && in_array($result_ank_open, $free['singles'], true)) {
                    $messages[] = "open $result_ank_open Pass.";
                }
                if ($result_ank_close !== '' && in_array($result_ank_close, $free['singles'], true)) {
                    $messages[] = "close $result_ank_close Pass.";
                }
                if ($result_jodi !== '' && in_array($result_jodi, $free['jodis'], true)) {
                    $messages[] = "jodi $result_jodi pass.";
                }
                if ($result_panna_open !== '' && in_array($result_panna_open, $free['pattis'], true)) {
                    $messages[] = "open se panna $result_panna_open pass.";
                }
                if ($result_panna_close !== '' && in_array($result_panna_close, $free['pattis'], true)) {
                    $messages[] = "close se panna $result_panna_close pass.";
                }

                if (!empty($messages)) {
                    $extra_text = "Free game se " . implode(" ", $messages);
                }
            }
        }

        $khabar_text = '';
        if (in_array($gameName, $khabar_games, true)) {
            if ($gameResult === 'Loading...') {
                $khabar_text = 'खबर लाइन चालू है';
            } else {
                $khabar_text = 'सबसे तेज सबसे सही';
            }
        }

        $live_result[] = [
            'name'         => $gameName,
            'result'       => $gameResult,
            'open_time'    => $timeOpen,
            'close_time'   => $timeClose,
            'days_of_week' => $frequency,
            'updated_date' => $updateDate,
            'final_ank'    => $finalAnk,
            'extra_text'   => $extra_text,
            'khabar_line'  => $khabar_text
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
