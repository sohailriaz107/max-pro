<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-results';
require_once '../config.php';
require_once 'sessions.php';
require_once 'functions.php';

$file = '../txtdb/xzavierJoaquinosia.txt';
$lines = file_exists($file) ? (file($file) ?: []) : [];
$today = date('d-m-Y');

// main star line
$main_slfile = '../txtdb/main_star_line_results.txt';
$main_sllines = file_exists($main_slfile) ? (file($main_slfile) ?: []) : [];

// cm star line
$cm_slfile = '../txtdb/cm_star_line_results.txt';
$cm_sllines = file_exists($cm_slfile) ? (file($cm_slfile) ?: []) : [];

// mr star line
$mr_slfile = '../txtdb/mr_star_line_results.txt';
$mr_sllines = file_exists($mr_slfile) ? (file($mr_slfile) ?: []) : [];

// main star line original
$main_sl_org_file = '../txtdb/main_original_sl.txt';
$msl_org_lines = file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : [];

// kalyan petti live star line
$kpl_slfile = '../txtdb/kpl_star_line_results.txt';
$kpl_sllines = file_exists($kpl_slfile) ? (file($kpl_slfile) ?: []) : [];

$kalyan_starline_file = '../txtdb/kalyan_star_line.txt';
$kalyan_starline_lines = file_exists($kalyan_starline_file) ? (file($kalyan_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$crr_starline_file = '../txtdb/crr_star_line.txt';
$crr_starline_lines = file_exists($crr_starline_file) ? (file($crr_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$silver_starline_file = '../txtdb/silver_line_star_line.txt';
$silver_starline_lines = file_exists($silver_starline_file) ? (file($silver_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$sai_starline_file = '../txtdb/sai_star_line.txt';
$sai_starline_lines = file_exists($sai_starline_file) ? (file($sai_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$gold_starline_file = '../txtdb/goldline_star_line.txt';
$gold_starline_lines = file_exists($gold_starline_file) ? (file($gold_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$sridevi_starline_file = '../txtdb/sridevi_star_line.txt';
$sridevi_starline_lines = file_exists($sridevi_starline_file) ? (file($sridevi_starline_file) ?: []) : (file_exists($main_sl_org_file) ? (file($main_sl_org_file) ?: []) : []);

$msg = '<h2>Update Result</h2>';

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
    $order = str_replace(["\r", "\n", "|"], '', (string)($_POST['order'] ?? ''));
    $gName = str_replace(["\r", "\n", "|"], '', trim((string)($_POST['gName'] ?? '')));

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = (string)$_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', (string)$_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ipList[0] ?? '');
    } else {
        $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    }
    $ip = preg_replace('/[^0-9a-fA-F:., ]/', '', $ip);

    $current_time = date("H:i");

    // Session and Post Values
    $log_username = str_replace(["\r", "\n", "|"], '', (string)($_SESSION['username'] ?? ''));
    $log_user_id  = str_replace(["\r", "\n", "|"], '', (string)($_SESSION['id'] ?? ''));
    $log_gResult  = str_replace(["\r", "\n", "|"], '', (string)($_POST['gResult'] ?? ''));
    $log_gName    = $gName;

    // Log file path
    $log_file = "../txtdb/game_logs.txt";
    $log_line = $log_gName . "|" . $log_gResult . "|" . $log_username . "|" . $log_user_id . "|" . $ip . "|" . $current_time . PHP_EOL;
    file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);

    $mPermissions = isset($_SESSION['m_permissions']) ? explode(',', (string)$_SESSION['m_permissions']) : [];
    if (!in_array($gName, $mPermissions, true)) {
        echo 'You Dont have Permissions.';
        exit;
    }

    $rawResult = trim((string)($_POST['gResult'] ?? ''));
    $gresult_pattern = '/^(\d{3}-\d{1}(-\d{3})?|\d{3}-\d{2}-\d{3}|✪)$/u';
    if (!preg_match($gresult_pattern, $rawResult)) {
        echo 'Invalid Entry';
        exit;
    }

    if (!preg_match('/^[\p{L}\p{M}0-9\-*.\[\] ]+$/u', $gName)) {
        echo 'Invalid Entry...';
        exit;
    }

    $gResult = str_replace(["\r", "\n", "|"], '', $rawResult);
    $timeOpen = str_replace(["\r", "\n", "|"], '', (string)($_POST['timeOpen'] ?? ''));
    $timeClose = str_replace(["\r", "\n", "|"], '', (string)($_POST['timeClose'] ?? ''));
    $gBgcolor = str_replace(["\r", "\n", "|"], '', (string)($_POST['gBgcolor'] ?? ''));
    $gFontcolor = str_replace(["\r", "\n", "|"], '', (string)($_POST['gFontcolor'] ?? ''));
    $gLinecolor = str_replace(["\r", "\n", "|"], '', (string)($_POST['gLinecolor'] ?? ''));
    $frequency = str_replace(["\r", "\n", "|"], '', (string)($_POST['frequency'] ?? ''));
    $liveTimeBefore = str_replace(["\r", "\n", "|"], '', (string)($_POST['liveTimeBefore'] ?? ''));
    $liveTimeAfter = str_replace(["\r", "\n", "|"], '', (string)($_POST['liveTimeAfter'] ?? ''));
    $updateDate = str_replace(["\r", "\n", "|"], '', (string)($_POST['updateDate'] ?? ''));
    $status = str_replace(["\r", "\n", "|"], '', (string)($_POST['status'] ?? ''));
    $expiryDate = str_replace(["\r", "\n", "|"], '', (string)($_POST['expiryDate'] ?? ''));
    $jodiLink = str_replace(["\r", "\n", "|"], '', (string)($_POST['jodiLink'] ?? ''));
    $panelLink = str_replace(["\r", "\n", "|"], '', (string)($_POST['panelLink'] ?? ''));

    if (strlen($gResult) === 10) {
        $num = (int)str_replace("-", '', $gResult);
        $sum = 0;
        for ($i = 0; $i <= 7; $i++) {
            $rem = $num % 10;
            $sum += $rem;
            $num = (int)($num / 10);
        }
        $finalAnk = substr((string)$sum, -1);
    } else {
        $finalAnk = str_replace(["\r", "\n", "|"], '', (string)($_POST['finalAnk'] ?? ''));
    }

    $data = $order . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDate . '|' . $status . '|' . $expiryDate . '|' . $jodiLink . '|' . $panelLink . '|' . time() . '|' . $finalAnk . '|';

    if ($id >= 0 && isset($lines[$id])) {
        $lines[$id] = $data . "\n";
    }

    $gResult_for_api = $gResult;
    $gName_for_api = $gName;

    $dateTime = strtotime(date('d-m-Y H:i'));
    if ((int)date('H') >= 0 && (int)date('H') <= 3) {
        $dateTime = strtotime('+1 days', strtotime(date('d-m-Y H:i')));
    }

    if (date('D') === 'Sun') {
        if ($gName === 'TIME BAZAR') {
            $timeOpen = '13:00';
            $timeClose = '15:14';
        } elseif ($gName === 'MILAN DAY') {
            $timeOpen = '14:14';
            $timeClose = '16:14';
        } elseif ($gName === 'MAIN DAY') {
            $timeOpen = '15:30';
            $timeClose = '17:30';
        } elseif ($gName === 'SUPER MATKA') {
            $timeOpen = '13:30';
            $timeClose = '15:00';
        }
    }

    $content = implode('', $lines);
    $insert = file_put_contents('../txtdb/xzavierJoaquinosia.txt', $content, LOCK_EX);

    if ($insert !== false) {
        $re = '/^(\d{3}-\d{2}-\d{3})$/m';
        $updateDate = date('d/m/Y');
        $msgUpdate = '<div class="alert alert-success">Result Update Successfully</div>';

        if (preg_match($re, $gResult)) {
            $num = $gResult;
            $a = $num[0];
            $b = $num[1];
            $c = $num[2];
            $d = $num[4] . $num[5];
            $e = $num[7];
            $f = $num[8];
            $g = $num[9];

            $detectColor = '/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
            $color = preg_match($detectColor, $d) ? 'red' : 'black';

            $freqInt = (int)$frequency;
            if ($freqInt === 7 || $freqInt === 6 || $freqInt === 5) {
                $daysAdd = ($freqInt === 7) ? 6 : (($freqInt === 6) ? 5 : 4);
                $toTime = strtotime((string)($_POST['timeOpen'] ?? ''));
                $tcTime = strtotime((string)($_POST['timeClose'] ?? ''));
                $isNight = ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM'));

                if ($isNight) {
                    $nightGameDate = strtotime("-1 day", time());
                    $fromDate = date('d/m/Y', $nightGameDate);
                    $addDays = strtotime("+{$daysAdd} day", $nightGameDate);
                    $toDate = date('d/m/Y', $addDays);
                    if (date('A') === 'AM') {
                        $updateDate = date('d-m-Y', (int)strtotime('-1 day', (int)strtotime(date('d-m-Y'))));
                    }
                } else {
                    $fromDate = date('d/m/Y');
                    $addDays = strtotime("+{$daysAdd} day", time());
                    $toDate = date('d/m/Y', $addDays);
                    if ((int)date('H') >= 0 && (int)date('H') <= 3) {
                        $updateDate = date('d-m-Y', (int)strtotime('-1 day', (int)strtotime(date('d-m-Y'))));
                    }
                }

                $stmtH = mysqli_prepare($con, "SELECT updateDate FROM `panel` WHERE `gameName` = ? ORDER BY id DESC LIMIT 1");
                if ($stmtH) {
                    mysqli_stmt_bind_param($stmtH, "s", $gName);
                    mysqli_stmt_execute($stmtH);
                    $holidaySelect = mysqli_stmt_get_result($stmtH);
                    $num_rows = $holidaySelect ? mysqli_num_rows($holidaySelect) : 0;

                    if ($num_rows > 0) {
                        $fetched_row = mysqli_fetch_assoc($holidaySelect);
                        $LastUpdateDate = str_replace('/', '-', (string)$fetched_row['updateDate']);
                        $date1 = (int)strtotime($LastUpdateDate);
                        $date2 = (int)strtotime(str_replace('/', '-', $updateDate));
                        $count = 0;
                        while (date('Y-m-d', $date1) < date('Y-m-d', $date2)) {
                            $count += date('N', $date1) < 8 ? 1 : 0;
                            $date1 = (int)strtotime("+1 day", $date1);
                        }
                        $dateDiff = ($count === 0) ? 0 : ($count - 1);

                        for ($i = 1; $i <= $dateDiff; $i++) {
                            $incDate = date("d-m-Y", (int)strtotime("$LastUpdateDate +" . $i . " day"));
                            $timestamp = (int)strtotime($incDate);
                            $dayD = date('D', $timestamp);
                            $starA = '*'; $starB = '*'; $starC = '*'; $starD = '**'; $starE = '*'; $starF = '*'; $starG = '*'; $redCol = 'red';
                            $freqStr = (string)$frequency;

                            if ($freqInt === 6 && $dayD === 'Sun') {
                                continue;
                            }
                            if ($freqInt === 5 && ($dayD === 'Sun' || $dayD === 'Sat')) {
                                continue;
                            }

                            if ($dayD === 'Mon') {
                                $weekStart = $incDate;
                                $weekEnd = date("d-m-Y", (int)strtotime("$weekStart +{$daysAdd} day"));
                                $wkrs = str_replace('-', '/', $weekStart);
                                $wkre = str_replace('-', '/', $weekEnd);
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $wkrs, $wkre, $incDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            } else {
                                $empty = '';
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $empty, $empty, $incDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            }
                        }
                    } else {
                        $dateOne = date('d-m-Y', (int)strtotime('monday this week'));
                        $date1 = str_replace('/', '-', $dateOne);
                        $date2 = str_replace('/', '-', $updateDate);
                        $calcDiff = dateDiffInDays($date1, $date2);
                        $dateDiff = ($calcDiff === 0) ? 0 : ($calcDiff - 1);

                        for ($i = 0; $i <= $dateDiff; $i++) {
                            $incDate = date("d-m-Y", (int)strtotime("$date1 +" . $i . " day"));
                            $timestamp = (int)strtotime($incDate);
                            $dayD = date('D', $timestamp);
                            $starA = '*'; $starB = '*'; $starC = '*'; $starD = '**'; $starE = '*'; $starF = '*'; $starG = '*'; $redCol = 'red';
                            $freqStr = (string)$frequency;

                            if ($dayD === 'Mon') {
                                $weekStart = $incDate;
                                $weekEnd = date("d-m-Y", (int)strtotime("$weekStart +{$daysAdd} day"));
                                $wkrs = str_replace('-', '/', $weekStart);
                                $wkre = str_replace('-', '/', $weekEnd);
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $wkrs, $wkre, $incDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            } else {
                                $empty = '';
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $empty, $empty, $incDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            }
                        }
                    }
                    mysqli_stmt_close($stmtH);
                }

                $stmtChk = mysqli_prepare($con, "SELECT id FROM `panel` WHERE `updateDate` = ? AND `gameName` = ? ORDER BY id");
                if ($stmtChk) {
                    mysqli_stmt_bind_param($stmtChk, "ss", $updateDate, $gName);
                    mysqli_stmt_execute($stmtChk);
                    $resChk = mysqli_stmt_get_result($stmtChk);
                    $num_rows = $resChk ? mysqli_num_rows($resChk) : 0;

                    if ($num_rows > 0) {
                        if (strtotime(date('h:i')) < strtotime('23:59') || ($isNight && (int)date('H') < 3)) {
                            $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a` = ?, `b` = ?, `c` = ?, `d` = ?, `e` = ?, `f` = ?, `g` = ?, `color` = ? WHERE `updateDate` = ? AND `gameName` = ?");
                            if ($stmtUp) {
                                mysqli_stmt_bind_param($stmtUp, "ssssssssss", $a, $b, $c, $d, $e, $f, $g, $color, $updateDate, $gName);
                                if (mysqli_stmt_execute($stmtUp)) {
                                    $msg = '<div class="alert alert-success">Chart Updated</div>';
                                }
                                mysqli_stmt_close($stmtUp);
                            }
                        }
                    } else {
                        $freqStr = (string)$frequency;
                        if ($isNight) {
                            if (date('D') === 'Tue') {
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $fromDate, $toDate, $updateDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            } else {
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $updateDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            }
                        } elseif (date('D') === 'Mon') {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $fromDate, $toDate, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        } else {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        }
                        $msg = '<div class="alert alert-success">Chart Also Updated Successfully</div>';
                    }
                    mysqli_stmt_close($stmtChk);
                }
            }
            $msg .= $msgUpdate;
        } elseif ($rawResult === '✪') {
            $freqInt = (int)$frequency;
            if ($freqInt === 7 || $freqInt === 6 || $freqInt === 5) {
                $daysAdd = ($freqInt === 7) ? 6 : (($freqInt === 6) ? 5 : 4);
                $toTime = strtotime((string)($_POST['timeOpen'] ?? ''));
                $tcTime = strtotime((string)($_POST['timeClose'] ?? ''));
                $isNight = ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM'));

                if ($isNight) {
                    $nightGameDate = strtotime("-1 day", time());
                    $fromDate = date('d/m/Y', $nightGameDate);
                    $addDays = strtotime("+{$daysAdd} day", $nightGameDate);
                    $toDate = date('d/m/Y', $addDays);
                } else {
                    $fromDate = date('d/m/Y');
                    $addDays = strtotime("+{$daysAdd} day", time());
                    $toDate = date('d/m/Y', $addDays);
                }

                $stmtChk = mysqli_prepare($con, "SELECT id FROM `panel` WHERE `updateDate` = ? AND `gameName` = ?");
                if ($stmtChk) {
                    mysqli_stmt_bind_param($stmtChk, "ss", $updateDate, $gName);
                    mysqli_stmt_execute($stmtChk);
                    $resChk = mysqli_stmt_get_result($stmtChk);
                    $num_rows = $resChk ? mysqli_num_rows($resChk) : 0;

                    if ($num_rows === 0) {
                        $starA = '*'; $starB = '*'; $starC = '*'; $starD = '**'; $starE = '*'; $starF = '*'; $starG = '*'; $redCol = 'red';
                        $freqStr = (string)$frequency;
                        if (date('D') === 'Mon') {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $fromDate, $toDate, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        } else {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redCol, $freqStr, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        }
                    }
                    mysqli_stmt_close($stmtChk);
                }
            }
            $msg .= $msgUpdate;
        } else {
            $msg = '<div class="alert alert-success">Result Update Successfully</div>';
        }
    } else {
        $msg = 'Something Wrong!';
    }

    if (in_array($gName, ['SRIDEVI', 'SRIDEVI NIGHT', 'KALYAN NIGHT', 'SRIDEVI MORNING', 'MILAN MORNING', 'MADHUR DAY', 'MADHUR NIGHT'], true)) {
        $url = "https://onlyus.dpboss.direct/api/forum/result-webhook";
        $webhookData = [
            "game_name" => $gName_for_api,
            "result" => $gResult_for_api
        ];
        $ch = curl_init($url);
        if ($ch) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = date("Y-m-d H:i:s") . " - cURL Error: " . curl_error($ch) . PHP_EOL . "\n\n================\n\n";
                @file_put_contents('result_post_api_response.txt', $error_msg, FILE_APPEND | LOCK_EX);
            } else {
                $response_to_save = date("Y-m-d H:i:s") . " - " . (string)$response . PHP_EOL . "\n\n================\n\n";
                @file_put_contents('result_post_api_response.txt', $response_to_save, FILE_APPEND | LOCK_EX);
            }
            curl_close($ch);
        }
    }

    $specialGames = [
        'TIME',
        'MILAN DAY',
        'MILAN NIGHT',
        'SRIDEVI MORNING',
        'SRIDEVI DAY',
        'SRIDEVI NIGHT',
        'RAJDHANI DAY',
        'RAJDHANI NIGHT',
        'KALYAN MORNING',
        'MILAN MORNING',
        'MAIN BAZAR DAY',
        'RATAN KHATRI',
        'KALYAN NIGHT',
        'MAIN BAZAR',
        'MAIN MUMBAI NIGHT'
    ];

    if (in_array($gName_for_api, $specialGames, true)) {
        $botToken = "8778022260:AAGTolYVJY08hZ_my1oaQ6WP5_T9mVtssts";
        $chatId = "@dpbossofiicial";

        $message = "🎯 <b>DPBOSS LIVE RESULT</b>\n\n";
        $message .= "🎲 <b>{$gName_for_api}</b>\n\n";
        $message .= "💎 <code>{$gResult_for_api}</code>\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "\n🌐 <a href='https://dpbossss.com/'>Visit DPBoss Official Website</a>";

        $tgUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $tgData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($tgData),
                'timeout' => 5,
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($options);
        @file_get_contents($tgUrl, false, $context);
    }
} elseif (isset($_POST['update_main_slResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result' . $id] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $main_sllines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/main_star_line_results.txt', implode('', $main_sllines), LOCK_EX);

        if ($insert !== false) {
            $stmtQ = mysqli_prepare($con, "SELECT id FROM `main_sl` WHERE `updateDate` = ? LIMIT 1");
            if ($stmtQ) {
                mysqli_stmt_bind_param($stmtQ, "s", $today);
                mysqli_stmt_execute($stmtQ);
                $resQ = mysqli_stmt_get_result($stmtQ);
                $day = date('l');
                if ($resQ && $row = mysqli_fetch_assoc($resQ)) {
                    $rowId = (int)$row['id'];
                    $col = "result" . $id;
                    $stmtUp = mysqli_prepare($con, "UPDATE `main_sl` SET `{$col}` = ? WHERE `id` = ?");
                    if ($stmtUp) {
                        mysqli_stmt_bind_param($stmtUp, "si", $data, $rowId);
                        mysqli_stmt_execute($stmtUp);
                        mysqli_stmt_close($stmtUp);
                    }
                } else {
                    $col = "result" . $id;
                    $stmtIn = mysqli_prepare($con, "INSERT INTO `main_sl` (`{$col}`, `updateDate`, `updateDay`) VALUES (?, ?, ?)");
                    if ($stmtIn) {
                        mysqli_stmt_bind_param($stmtIn, "sss", $data, $today, $day);
                        mysqli_stmt_execute($stmtIn);
                        mysqli_stmt_close($stmtIn);
                    }
                }
                mysqli_stmt_close($stmtQ);
            }
            $msg = '<div class="alert alert-success">Result Update Successfully</div>';
        } else {
            $msg = 'Something Wrong!';
        }
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_cm_slResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result' . $id] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $cm_sllines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/cm_star_line_results.txt', implode('', $cm_sllines), LOCK_EX);

        if ($insert !== false) {
            $stmtQ = mysqli_prepare($con, "SELECT id FROM `cm_sl` WHERE `updateDate` = ? LIMIT 1");
            if ($stmtQ) {
                mysqli_stmt_bind_param($stmtQ, "s", $today);
                mysqli_stmt_execute($stmtQ);
                $resQ = mysqli_stmt_get_result($stmtQ);
                $day = date('l');
                if ($resQ && $row = mysqli_fetch_assoc($resQ)) {
                    $rowId = (int)$row['id'];
                    $col = "result" . $id;
                    $stmtUp = mysqli_prepare($con, "UPDATE `cm_sl` SET `{$col}` = ? WHERE `id` = ?");
                    if ($stmtUp) {
                        mysqli_stmt_bind_param($stmtUp, "si", $data, $rowId);
                        mysqli_stmt_execute($stmtUp);
                        mysqli_stmt_close($stmtUp);
                    }
                } else {
                    $col = "result" . $id;
                    $stmtIn = mysqli_prepare($con, "INSERT INTO `cm_sl` (`{$col}`, `updateDate`, `updateDay`) VALUES (?, ?, ?)");
                    if ($stmtIn) {
                        mysqli_stmt_bind_param($stmtIn, "sss", $data, $today, $day);
                        mysqli_stmt_execute($stmtIn);
                        mysqli_stmt_close($stmtIn);
                    }
                }
                mysqli_stmt_close($stmtQ);
            }
            $msg = '<div class="alert alert-success">Result Update Successfully</div>';
        } else {
            $msg = 'Something Wrong!';
        }
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_kpl_slResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $kpl_sllines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/kpl_star_line_results.txt', implode('', $kpl_sllines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_mr_slResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $mr_sllines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/mr_star_line_results.txt', implode('', $mr_sllines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_main_org_slResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $msl_org_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/main_original_sl.txt', implode('', $msl_org_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_kalyan_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $kalyan_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/kalyan_star_line.txt', implode('', $kalyan_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_crr_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $crr_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/crr_star_line.txt', implode('', $crr_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_silver_line_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $silver_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/silver_line_star_line.txt', implode('', $silver_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_sai_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $sai_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/sai_star_line.txt', implode('', $sai_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_goldline_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $gold_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/goldline_star_line.txt', implode('', $gold_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
} elseif (isset($_POST['update_sridevi_star_line'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $result = str_replace(["\r", "\n", "|"], '', (string)($_POST['result'] ?? ''));
    $data = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

    if ($id >= 1 && $id <= 12) {
        $sridevi_starline_lines[$id] = $data . "\n";
        $insert = file_put_contents('../txtdb/sridevi_star_line.txt', implode('', $sridevi_starline_lines), LOCK_EX);
        $msg = ($insert !== false) ? '<div class="alert alert-success">Result Update Successfully</div>' : 'Something Wrong!';
    } else {
        $msg = 'Invalid ID';
    }
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Neon Admin Panel" />
  <meta name="author" content="" />
  <meta http-equiv="refresh" content="30">

  <link rel="icon" href="assets/images/favicon.ico">

  <title>Update Results</title>

  <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
  <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/neon-core.css">
  <link rel="stylesheet" href="assets/css/neon-theme.css">
  <link rel="stylesheet" href="assets/css/neon-forms.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/skins/red.css">

  <script src="assets/js/jquery-1.11.3.min.js"></script>

  <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->


</head>
<body class="page-body skin-red">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
  
  <?php include 'sidebar-menu.php'; ?>

  <div class="main-content">
        
    <div class="row">
    
      <!-- Profile Info and Notifications -->
      <?php include 'header.php'; ?>
    
    
      
    
    </div>
    
    <hr />
    
          
    <?php echo $msg; ?>

    <br />
    
    <div class="row">
      <div class="col-md-12">

        <?php 
if (!empty($counter) && $counter >= 1) {
  foreach ($expiryLines as $line_num => $line) {

  [$order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate, $jodiLink, $panelLink] = array_pad(explode("|", trim((string)$line)), 16, '');
  if (in_array($gameName, $expiryPermissions)) {
    // start date difference logic

            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 

            $parts = explode('-', $date2);
            $lastValue = end($parts);

      //end date difference logic
        if ($calcDiff>=1 && $calcDiff<=5) {
          if (strtotime($date1)<strtotime($date2)) {
?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<?php }else{ ?>

<div class="alert alert-info alert-dismissible" role="alert"><strong>आपके अकाउंट <?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?> की मासिक DATE : <?php echo htmlspecialchars((string)$lastValue, ENT_QUOTES, 'UTF-8'); ?> है। कृपया भुगतान समय पर करें। 
भुगतान की तारीख में विलम्ब होने पर 3 दिन बाद आपकी सुविधा बंद कर दी जाएगी। </strong></div>
<?php if(0){ ?>
<div class="alert alert-info alert-dismissible" role="alert"><strong>You have <?php echo (int)$calcDiff; ?> days left to renew <?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?></strong></div>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
      // Sample Toastr Notification
      setTimeout(function()
      {
        var opts = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "16000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
    
        toastr.warning("You have <?php echo (int)$calcDiff; if ($calcDiff > 1) {echo ' days';} else {echo ' day';} ?> left to renew <?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?>", "Urgent Notice", opts);
      }, 4000);
    
    });
    
    </script>
<?php }} ?>
                    
<?php
}
}
elseif ($calcDiff==0) {
?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<?php }else{ ?>
<div class="alert alert-danger alert-dismissible" role="alert"><strong>Your plan has expired for <?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?>. Please Renew it as soon as possible.</strong></div>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
      // Sample Toastr Notification
      setTimeout(function()
      {
        var opts = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "15000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
    
        toastr.error("Your plan has expired for <?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?>. Please Renew it as soon as possible.", "Plan Expired", opts);
      }, 3000);
    
    });
    
    </script>
<?php }} ?>
                    
<?php
}// end else if differenece is zero
else{

}


//$gameName = strtoupper(trim($gameName)); // normalize input

$marketList = [
    "RATNA MORNING",
    "RATNA DAY",
    "RATNA NIGHT",
    "MANGAL MORNING",
    "MANGAL DAY",
    "MANGAL NIGHT",
    "SITARA BAZAR",
    "MANGAL BAZAR",
    "NTR MORNING",
    "NTR DAY",
    "NTR NIGHT",
    "MAYA BAZAR",
    "STAR TARA MORNING",
    "STAR TARA DAY",
    "STAR TARA NIGHT",
    "SITA MORNING",
    "SITA DAY",
    "SITA NIGHT",
    "MAHADEVI",
    "MAHADEVI MORNING",
    "MAHADEVI NIGHT",
    "SUPER KING",
    "SUPERKING NIGHT",
    "MEENA MORNING",
    "MEENA BAZAR",
    "SRILAXMI DAY",
    "TULSI MORNING",
    "GEETA MORNING",
    "ANDHRA MORNING",
    "ANDHRA DAY",
    "ANDHRA NIGHT",
    "KAMAL MORNING",
    "KAMAL DAY",
    "KAMAL NIGHT",
    "GAMA MORNING",
    "GAMA DAY",
    "GAMA NIGHT",
    "MUMBAI MORNING",
    "NEW TIME BAZAR",
    "DELHI BAZAR",
    "PUNA NIGHT  [ main ]",
    "VENUS DAY",
    "VENUS NIGHT"
];

// check match
if (!in_array($gameName, $marketList)) { ?>

<?php 
}


}
}
}
?>    



<?php
$adminId = (int)($_SESSION['id'] ?? 0);
$permissions = [];
$number_permissions = 0;
$stmtAdmin = mysqli_prepare($con, "SELECT id, permissions, number_permissions, username FROM admins WHERE id = ?");
if ($stmtAdmin) {
    mysqli_stmt_bind_param($stmtAdmin, "i", $adminId);
    mysqli_stmt_execute($stmtAdmin);
    $resAdmin = mysqli_stmt_get_result($stmtAdmin);
    if ($resAdmin && $result = mysqli_fetch_assoc($resAdmin)) {
        $permissions = array_filter(array_map('trim', explode(',', (string)($result['permissions'] ?? ''))));
        $number_permissions = (int)($result['number_permissions'] ?? 0);
    }
    mysqli_stmt_close($stmtAdmin);
}

if ($number_permissions === 1) {
    $stmtVk = mysqli_prepare($con, "SELECT mobile FROM admins WHERE username = 'vk' LIMIT 1");
    if ($stmtVk) {
        mysqli_stmt_execute($stmtVk);
        $resVk = mysqli_stmt_get_result($stmtVk);
        if ($resVk && $fetchNum = mysqli_fetch_assoc($resVk)) {
            $mobileVk = (string)($fetchNum['mobile'] ?? '');
            if (($_SESSION['username'] ?? '') !== 'vk' && $mobileVk !== '') {
                $cleanMob = preg_replace('/[^0-9]/', '', $mobileVk);
                $escMob = htmlspecialchars($mobileVk, ENT_QUOTES, 'UTF-8');
                ?>
<div class="alert alert-info">DPBOSS Admin Direct Line - <span class="blink" style="font-size: 18px;"><a href="https://api.whatsapp.com/send?phone=+91<?php echo $cleanMob; ?>&text=I%27m%20coming%20from%20your%20website%20chat%20link"><img src="assets/images/whatsapp.png" width="30" style="vertical-align: middle;"><b> <?php echo $escMob; ?></b></a></span></div>
                <?php
            }
        }
        mysqli_stmt_close($stmtVk);
    }
}
foreach ($lines as $line_num => $line) {
  [$order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate, $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk] = array_pad(explode("|", trim((string)$line)), 18, '');
  if (in_array($gameName, $permissions)) {

$date1 = str_replace('/', '-', date('Y-m-d')); 
$date2 = str_replace('/', '-', $expiryDate);
$calcDiff = dateDiffInDays($date1, $date2); 
if ($_SESSION['username']=='vk') {
  ?>
            <form role="form" class="form-horizontal" action="?id=<?php echo (int)$line_num; ?>" method="post">
      
              <div class="form-group">
                <label class="col-sm-3"><?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?></label>
                <input type="hidden" name="gName" class="form-control" id="inlineFormInput" value="<?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="col-sm-5">
                  <input type="text" name="gResult" class="form-control" value="<?php echo htmlspecialchars((string)$gameResult, ENT_QUOTES, 'UTF-8'); ?>" required maxlength="10">
                  <input type="hidden" name="order" class="form-control" value="<?php echo htmlspecialchars((string)$order, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="timeOpen" class="form-control" value="<?php echo htmlspecialchars((string)$timeOpen, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="timeClose" class="form-control" value="<?php echo htmlspecialchars((string)$timeClose, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo htmlspecialchars((string)$gameBgcolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gFontcolor" value="<?php echo htmlspecialchars((string)$gameFontcolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gLinecolor" value="<?php echo htmlspecialchars((string)$gameLinecolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="frequency" value="<?php echo htmlspecialchars((string)$frequency, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php echo htmlspecialchars($liveTimeBefore === '' ? '5' : (string)$liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="updateDate" class="form-control" value="<?php 
            $toTime = strtotime((string)$timeOpen);
            $tcTime = strtotime((string)$timeClose);
            if ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM')){
              if((int)date('H') >= 0 && (int)date('H') <= 3){
                echo htmlspecialchars(date("d-m-Y", strtotime('-1 days')), ENT_QUOTES, 'UTF-8');
              } else {
                echo htmlspecialchars((string)$today, ENT_QUOTES, 'UTF-8');  
              }
            } else {
              echo htmlspecialchars((string)$today, ENT_QUOTES, 'UTF-8');
            }
          ?>">
          <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php echo htmlspecialchars($liveTimeAfter === '' ? '25' : (string)$liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="status" class="form-control" value="<?php echo (string)$status === '0' ? '0' : '1'; ?>">
          <input type="hidden" name="expiryDate" class="form-control" value="<?php echo htmlspecialchars((string)$expiryDate, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="jodiLink" class="form-control" value="<?php echo htmlspecialchars((string)$jodiLink, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="panelLink" class="form-control" value="<?php echo htmlspecialchars((string)$panelLink, ENT_QUOTES, 'UTF-8'); ?>">
		  <input type="hidden" name="finalAnk" class="form-control" value="<?php echo htmlspecialchars((string)$finalAnk, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-success" name="updateResult" style="margin-top: 5px;"><b>Update Result</b></button>
                </div>
              </div>
            </form>
  <?php
}//end if check user is admin

else{
  if (strtotime($date1)<=strtotime($date2)) {
    // if ($number_permissions==1) {
    //   echo 'ok';
    // }
  ?>
            <form role="form" class="form-horizontal" action="?id=<?php echo (int)$line_num; ?>" method="post">
      
              <div class="form-group">
                <label class="col-sm-3"><?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?></label>
                <input type="hidden" name="gName" class="form-control" id="inlineFormInput" value="<?php echo htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="col-sm-5">
                  <input type="text" name="gResult" class="form-control" value="<?php echo htmlspecialchars((string)$gameResult, ENT_QUOTES, 'UTF-8'); ?>" required maxlength="10">
                  <input type="hidden" name="order" class="form-control" value="<?php echo htmlspecialchars((string)$order, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="timeOpen" class="form-control" value="<?php echo htmlspecialchars((string)$timeOpen, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="timeClose" class="form-control" value="<?php echo htmlspecialchars((string)$timeClose, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo htmlspecialchars((string)$gameBgcolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gFontcolor" value="<?php echo htmlspecialchars((string)$gameFontcolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gLinecolor" value="<?php echo htmlspecialchars((string)$gameLinecolor, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="frequency" value="<?php echo htmlspecialchars((string)$frequency, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php echo htmlspecialchars($liveTimeBefore === '' ? '5' : (string)$liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="updateDate" class="form-control" value="<?php 
            $toTime = strtotime((string)$timeOpen);
            $tcTime = strtotime((string)$timeClose);
            if ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM')){
              if((int)date('H') >= 0 && (int)date('H') <= 3){
                echo htmlspecialchars(date("d-m-Y", strtotime('-1 days')), ENT_QUOTES, 'UTF-8');
              } else {
                echo htmlspecialchars((string)$today, ENT_QUOTES, 'UTF-8');  
              }
            } else {
              echo htmlspecialchars((string)$today, ENT_QUOTES, 'UTF-8');
            }
          ?>">
          <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php echo htmlspecialchars($liveTimeAfter === '' ? '25' : (string)$liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="status" class="form-control" value="<?php echo (string)$status === '0' ? '0' : '1'; ?>">
          <input type="hidden" name="expiryDate" class="form-control" value="<?php echo htmlspecialchars((string)$expiryDate, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="jodiLink" class="form-control" value="<?php echo htmlspecialchars((string)$jodiLink, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="panelLink" class="form-control" value="<?php echo htmlspecialchars((string)$panelLink, ENT_QUOTES, 'UTF-8'); ?>">
		  <input type="hidden" name="finalAnk" class="form-control" value="<?php echo htmlspecialchars((string)$finalAnk, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-success" name="updateResult" style="margin-top: 5px;"><b>Update Result</b></button>
                </div>
              </div>
            </form>
  <?php
}//end if expiry date is smaller than today
else{
  echo '<div class="alert alert-danger">' . htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8') . ' has been expired on ' . htmlspecialchars((string)$expiryDate, ENT_QUOTES, 'UTF-8') . '. Please contact Admin to update result of ' . htmlspecialchars((string)$gameName, ENT_QUOTES, 'UTF-8') . '</div>';
}
}

}
}
?>
<?php if (in_array('msl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>Main SL 09:05 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result1" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 10:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result2" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 11:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result3" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 12:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result4" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 01:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result5" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 02:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result6" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 03:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result7" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result8" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 05:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result9" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 06:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result10" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 07:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result11" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 08:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result12" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($main_sllines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<hr>
<?php } ?>



<!-- start cm star line -->
<?php if (in_array('cmsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>CM SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result1" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result2" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result3" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result4" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result5" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result6" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 04:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result7" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result8" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result9" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result10" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl11', $permissions) && 0) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 08:00 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result11" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl12', $permissions) && 0) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 09:00 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result12" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($cm_sllines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<hr>
<?php } ?>
<!-- end cm star line -->


<!-- start kpl star line -->
<?php if (in_array('kplsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>KALYAN PETTI LIVE SL 11:05 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 12:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 01:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 02:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 03:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 04:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 05:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 06:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 07:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 08:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 09:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 10:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kpl_sllines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end kpl star line -->




<!-- start mr star line -->
<?php if (in_array('mrsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>MR SL 09:30 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 10:30 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 11:30 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 12:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 01:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 02:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 03:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 05:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 06:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 07:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 08:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($mr_sllines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end mr star line -->





<!-- start main sl original star line -->
<?php if (in_array('msl_org1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>MAIN SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($msl_org_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end ms original star line -->




<!-- kalyan_star_line star line -->
<?php if (in_array('kalyan_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>KALYAN SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($kalyan_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end kalyan_star_line star line -->



<!-- crr_star_line star line -->
<?php if (in_array('crr_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>CRR SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($crr_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end crr_star_line star line -->


<!-- silver_star_line star line -->
<?php if (in_array('silver_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SILVER SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($silver_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end silver_star_line star line -->



<!-- sai_star_line star line -->
<?php if (in_array('sai_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SAI SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sai_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end sai_star_line star line -->



<!-- goldline_star_line star line -->
<?php if (in_array('goldline_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>GOLD SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($gold_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end goldline_star_line star line -->



<!-- sridevi_star_line star line -->
<?php if (in_array('sridevi_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SRIDEVI SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[1] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[2] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[3] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[4] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[5] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[6] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[7] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[8] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[9] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[10] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[11] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo htmlspecialchars(trim((string)($sridevi_starline_lines[12] ?? '')), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end sridevi_star_line star line -->
        
      
      </div>
    </div>
    
    
    <?php include 'footer.php' ?>
  </div>
  
</div>




  <!-- Bottom scripts (common) -->
  <script src="assets/js/gsap/TweenMax.min.js"></script>
  <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/joinable.js"></script>
  <script src="assets/js/resizeable.js"></script>
  <script src="assets/js/neon-api.js"></script>


  <!-- Imported scripts on this page -->
  <script src="assets/js/bootstrap-switch.min.js"></script>
  <script src="assets/js/neon-chat.js"></script>
  <script src="assets/js/toastr.js"></script>


  <!-- JavaScripts initializations and stuff -->
  <script src="assets/js/neon-custom.js"></script>


  <!-- Demo Settings -->
  <script src="assets/js/neon-demo.js"></script>

</body>
</html>
