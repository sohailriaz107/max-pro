<?php 
declare(strict_types=1);

session_start();
$pageName = 'update-2day-market-results';
require "../config.php";
require('sessions.php');
require('functions.php');

$file = '../txtdb/xzavierJoaquinosia.txt';
$lines = file_exists($file) ? (file($file) ?: []) : [];
$today = date('d-m-Y');

$msg = '<h2>Update Result</h2>';

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : -1;

    $sanitizeField = static function(string $val): string {
        return str_replace(["|", "\r", "\n"], '', trim($val));
    };

    $order = $sanitizeField((string)($_POST['order'] ?? ''));
    $gName = $sanitizeField((string)($_POST['gName'] ?? ''));

    $mPermissions = array_filter(array_map('trim', explode(',', (string)($_SESSION['m_permissions'] ?? ''))));
    if (!in_array($gName, $mPermissions, true)) {
        echo 'You Dont have Permissions.';
        exit;
    }

    $rawGResult = (string)($_POST['gResult'] ?? '');
    $gresult_pattern = '/^(\d{3}-\d{1}(-\d{3})?|\d{3}-\d{2}-\d{3})$/';
    if (!preg_match($gresult_pattern, $rawGResult)) {
        echo 'Invalid Entry';
        exit;
    }

    if (!preg_match('/^[\p{L}\p{M}0-9\-*.\[\] ]+$/u', $gName)) {
        echo 'Invalid Entry';
        exit;
    }

    $gResult        = $sanitizeField($rawGResult);
    $timeOpen       = $sanitizeField((string)($_POST['timeOpen'] ?? ''));
    $timeClose      = $sanitizeField((string)($_POST['timeClose'] ?? ''));
    $gBgcolor       = $sanitizeField((string)($_POST['gBgcolor'] ?? ''));
    $gFontcolor     = $sanitizeField((string)($_POST['gFontcolor'] ?? ''));
    $gLinecolor     = $sanitizeField((string)($_POST['gLinecolor'] ?? ''));
    $frequency      = (int)($_POST['frequency'] ?? 0);
    $liveTimeBefore = $sanitizeField((string)($_POST['liveTimeBefore'] ?? ''));
    $liveTimeAfter  = $sanitizeField((string)($_POST['liveTimeAfter'] ?? ''));
    $updateDatePost = $sanitizeField((string)($_POST['updateDate'] ?? ''));
    $status         = $sanitizeField((string)($_POST['status'] ?? ''));
    $expiryDate     = $sanitizeField((string)($_POST['expiryDate'] ?? ''));
    $jodiLink       = $sanitizeField((string)($_POST['jodiLink'] ?? ''));
    $panelLink      = $sanitizeField((string)($_POST['panelLink'] ?? ''));

    $data = $order . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDatePost . '|' . $status . '|' . $expiryDate . '|' . $jodiLink . '|' . $panelLink . '|' . time() . '|';

    if ($id >= 0 && isset($lines[$id])) {
        $existingParts = explode('|', (string)$lines[$id]);
        $existingFinalAnk = $existingParts[17] ?? '';
        if ($existingFinalAnk !== '') {
            $data .= $existingFinalAnk . '|';
        }
        $lines[$id] = $data . "\n";
        $content = implode('', $lines);
        $insert = file_put_contents($file, $content, LOCK_EX);

        if ($insert !== false) {
            $re = '/^(\d{3}-\d{2}-\d{3})$/';
            $updateDate = date('d/m/Y');
            $msgUpdate = '<div class="alert alert-success">Result Update Successfully! <b>Redirecting...</b></div>';
            header("refresh:3;url=../index.php");

            if (preg_match($re, $gResult)) {
                $num = (string)$gResult;
                $a = $num[0];
                $b = $num[1];
                $c = $num[2];
                $d = $num[4] . $num[5];
                $e = $num[7];
                $f = $num[8];
                $g = $num[9];

                $detectColor = '/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
                $color = preg_match($detectColor, $d) ? 'red' : 'black';

                if ($frequency === 2) {
                    $isOpenPM = strpos(date('h:i A', (int)strtotime($timeOpen)), 'PM') !== false;
                    $isCloseAM = strpos(date('h:i A', (int)strtotime($timeClose)), 'AM') !== false;

                    if ($isOpenPM && $isCloseAM) {
                        $nightGameDate = strtotime("-1 day");
                        $fromDate = date('d/m/Y', $nightGameDate);
                        $addDays = strtotime("+4 day", $nightGameDate);
                        $toDate = date('d/m/Y', $addDays);
                        if (date('A') === 'AM') {
                            $updateDate = date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
                        }
                    } else {
                        $fromDate = date('d/m/Y');
                        $addDays = strtotime("+1 day");
                        $toDate = date('d/m/Y', $addDays);
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
                                $freqStr = (string)$frequency;
                                $starA = '*'; $starB = '*'; $starC = '*'; $starD = '**'; $starE = '*'; $starF = '*'; $starG = '*'; $redColor = 'red';

                                if (date('D', $timestamp) === 'Sat') {
                                    $weekStart = $incDate;
                                    $weekEnd = date("d-m-Y", (int)strtotime("$weekStart +1 day"));
                                    $wkrs = str_replace('-', '/', $weekStart);
                                    $wkre = str_replace('-', '/', $weekEnd);

                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redColor, $freqStr, $wkrs, $wkre, $incDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                } else {
                                    $empty = '';
                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redColor, $freqStr, $empty, $empty, $incDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                }
                            }
                        } else {
                            $dateOne = date('d-m-Y', (int)strtotime('saturday this week'));
                            $date1 = str_replace('/', '-', $dateOne);
                            $date2 = str_replace('/', '-', $updateDate);
                            $calcDiff = dateDiffInDays($date1, $date2);
                            $dateDiff = ($calcDiff === 0) ? 0 : ($calcDiff - 1);

                            for ($i = 0; $i <= $dateDiff; $i++) {
                                $incDate = date("d-m-Y", (int)strtotime("$date1 +" . $i . " day"));
                                $timestamp = (int)strtotime($incDate);
                                $freqStr = (string)$frequency;
                                $starA = '*'; $starB = '*'; $starC = '*'; $starD = '**'; $starE = '*'; $starF = '*'; $starG = '*'; $redColor = 'red';

                                if (date('D', $timestamp) === 'Sat') {
                                    $weekStart = $incDate;
                                    $weekEnd = date("d-m-Y", (int)strtotime("$weekStart +1 day"));
                                    $wkrs = str_replace('-', '/', $weekStart);
                                    $wkre = str_replace('-', '/', $weekEnd);

                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redColor, $freqStr, $wkrs, $wkre, $incDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                } else {
                                    $empty = '';
                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $starA, $starB, $starC, $starD, $starE, $starF, $starG, $redColor, $freqStr, $empty, $empty, $incDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                }
                            }
                        }
                        mysqli_stmt_close($stmtH);
                    }

                    $stmtSel = mysqli_prepare($con, "SELECT id FROM `panel` WHERE `updateDate` = ? AND gameName = ?");
                    $num_rows_sel = 0;
                    if ($stmtSel) {
                        mysqli_stmt_bind_param($stmtSel, "ss", $updateDate, $gName);
                        mysqli_stmt_execute($stmtSel);
                        $select = mysqli_stmt_get_result($stmtSel);
                        $num_rows_sel = $select ? mysqli_num_rows($select) : 0;
                        mysqli_stmt_close($stmtSel);
                    }

                    if ($num_rows_sel > 0) {
                        $nowTime = (int)strtotime(date('H:i'));
                        if ($nowTime < (int)strtotime('23:59')) {
                            $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a` = ?, `b` = ?, `c` = ?, `d` = ?, `e` = ?, `f` = ?, `g` = ? WHERE updateDate = ? AND gameName = ?");
                            if ($stmtUp) {
                                mysqli_stmt_bind_param($stmtUp, "sssssssss", $a, $b, $c, $d, $e, $f, $g, $updateDate, $gName);
                                mysqli_stmt_execute($stmtUp);
                                mysqli_stmt_close($stmtUp);
                                $msg = '<div class="alert alert-success">Chart Updated</div>';
                            }
                        } elseif ($isOpenPM && $isCloseAM && $nowTime < (int)strtotime('03:00')) {
                            $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a` = ?, `b` = ?, `c` = ?, `d` = ?, `e` = ?, `f` = ?, `g` = ? WHERE updateDate = ? AND gameName = ?");
                            if ($stmtUp) {
                                mysqli_stmt_bind_param($stmtUp, "sssssssss", $a, $b, $c, $d, $e, $f, $g, $updateDate, $gName);
                                mysqli_stmt_execute($stmtUp);
                                mysqli_stmt_close($stmtUp);
                                $msg = '<div class="alert alert-success">Chart Updated</div>';
                            }
                        }
                    } else {
                        $freqStr = (string)$frequency;
                        if ($isOpenPM && $isCloseAM) {
                            if (date('D') === 'Sun') {
                                if ((int)strtotime(date('H:i')) < (int)strtotime('03:00')) {
                                    $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a` = ?, `b` = ?, `c` = ?, `d` = ?, `e` = ?, `f` = ?, `g` = ? WHERE updateDate = ? AND gameName = ?");
                                    if ($stmtUp) {
                                        mysqli_stmt_bind_param($stmtUp, "sssssssss", $a, $b, $c, $d, $e, $f, $g, $updateDate, $gName);
                                        mysqli_stmt_execute($stmtUp);
                                        mysqli_stmt_close($stmtUp);
                                        $msg = '<div class="alert alert-success">Chart Updated</div>';
                                    }
                                } else {
                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $fromDate, $toDate, $updateDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                }
                            } else {
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $freqStr, $updateDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            }
                        } elseif (date('D') === 'Sat') {
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
                }
                $msg .= $msgUpdate;
            } else {
                $msg = '<div class="alert alert-success">Result Update Successfully! <b>Redirecting...</b></div>';
                header("refresh:3;url=https://dpbosss.com");
            }
        } else {
            $msg = 'Something Wrong!';
        }
    } else {
        $msg = 'Invalid Game Record';
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
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="page-body skin-red">

<div class="page-container">
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
$permissions = [];
$number_permissions = 0;
if (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $stmtAdm = mysqli_prepare($con, "SELECT id, permissions, number_permissions, username FROM admins WHERE id = ?");
    if ($stmtAdm) {
        $uId = (int)$_SESSION['id'];
        mysqli_stmt_bind_param($stmtAdm, "i", $uId);
        mysqli_stmt_execute($stmtAdm);
        $resAdm = mysqli_stmt_get_result($stmtAdm);
        if ($resAdm && $rowAdm = mysqli_fetch_assoc($resAdm)) {
            $permissions = array_filter(array_map('trim', explode(',', (string)($rowAdm['permissions'] ?? ''))));
            $number_permissions = (int)($rowAdm['number_permissions'] ?? 0);
        }
        mysqli_stmt_close($stmtAdm);
    }
}

if ($number_permissions === 1) {
    $stmtVk = mysqli_prepare($con, "SELECT mobile FROM admins WHERE username = 'vk'");
    if ($stmtVk) {
        mysqli_stmt_execute($stmtVk);
        $resVk = mysqli_stmt_get_result($stmtVk);
        if ($resVk && $fetchNum = mysqli_fetch_assoc($resVk)) {
            $mobileNum = htmlspecialchars((string)($fetchNum['mobile'] ?? ''), ENT_QUOTES, 'UTF-8');
            if (($_SESSION['username'] ?? '') !== 'vk') {
?>
                <div class="alert alert-info">DPBOSS Admin Direct Line - <span class="blink" style="font-size: 18px;"><a href="https://api.whatsapp.com/send?phone=+91<?php echo $mobileNum; ?>&amp;text=I%27m%20coming%20from%20your%20website%20chat%20link"><img src="assets/images/whatsapp.png" width="30" style="vertical-align: middle;"><b> <?php echo $mobileNum; ?></a></b></span></div>
<?php
            }
        }
        mysqli_stmt_close($stmtVk);
    }
}

foreach ($lines as $line_num => $line) {
    $parts = array_pad(explode("|", trim((string)$line)), 16, '');
    $order          = $parts[0];
    $gameName       = $parts[1];
    $gameResult     = $parts[2];
    $timeOpen       = $parts[3];
    $timeClose      = $parts[4];
    $gameBgcolor    = $parts[5];
    $gameFontcolor  = $parts[6];
    $gameLinecolor  = $parts[7];
    $frequency      = $parts[8];
    $liveTimeBefore = $parts[9];
    $liveTimeAfter  = $parts[10];
    $status         = $parts[12];
    $expiryDate     = $parts[13];
    $jodiLink       = $parts[14];
    $panelLink      = $parts[15];

    if ($frequency !== '2') {
        continue;
    }

    if (in_array($gameName, $permissions, true)) {
        $date1 = str_replace('/', '-', date('Y-m-d')); 
        $date2 = str_replace('/', '-', $expiryDate);
        $calcDiff = dateDiffInDays($date1, $date2); 
        $safeGame = htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8');

        if (($_SESSION['username'] ?? '') === 'vk' || strtotime($date1) <= strtotime($date2)) {
?>
            <form role="form" class="form-horizontal" action="?id=<?php echo (int)$line_num; ?>" method="post">
              <div class="form-group">
                <label class="col-sm-3"><?php echo $safeGame; ?></label>
                <input type="hidden" name="gName" class="form-control" value="<?php echo $safeGame; ?>">
                <div class="col-sm-5">
                  <input type="text" name="gResult" class="form-control" value="<?php echo htmlspecialchars($gameResult, ENT_QUOTES, 'UTF-8'); ?>" required maxlength="10">
                  <input type="hidden" name="order" class="form-control" value="<?php echo htmlspecialchars($order, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="timeOpen" class="form-control" value="<?php echo htmlspecialchars($timeOpen, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="timeClose" class="form-control" value="<?php echo htmlspecialchars($timeClose, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo htmlspecialchars($gameBgcolor, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="gFontcolor" value="<?php echo htmlspecialchars($gameFontcolor, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="gLinecolor" value="<?php echo htmlspecialchars($gameLinecolor, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="frequency" value="<?php echo htmlspecialchars($frequency, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php echo htmlspecialchars($liveTimeBefore === '' ? '5' : $liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="updateDate" class="form-control" value="<?php 
                    $toTime = strtotime($timeOpen);
                    $tcTime = strtotime($timeClose);
                    if ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM')) {
                        $hour = (int)date('H');
                        if ($hour >= 0 && $hour <= 3) {
                            echo date("d-m-Y", strtotime('-1 days'));
                        } else {
                            echo $today;  
                        }
                    } else {
                        echo $today;
                    }
                  ?>">
                  <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php echo htmlspecialchars($liveTimeAfter === '' ? '25' : $liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="status" class="form-control" value="<?php echo ($status === '0') ? '0' : '1'; ?>">
                  <input type="hidden" name="expiryDate" class="form-control" value="<?php echo htmlspecialchars($expiryDate, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="jodiLink" class="form-control" value="<?php echo htmlspecialchars($jodiLink, ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="panelLink" class="form-control" value="<?php echo htmlspecialchars($panelLink, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-success" name="updateResult" style="margin-top: 5px;"><b>Update Result</b></button>
                </div>
              </div>
            </form>
<?php
        } else {
            echo '<div class="alert alert-danger">' . $safeGame . ' has expired on ' . htmlspecialchars($expiryDate, ENT_QUOTES, 'UTF-8') . '. Please contact Admin to update result of ' . $safeGame . '</div>';
        }
    }
}
?>
			</div>
		</div>
		<?php include 'footer.php'; ?>
	</div>
</div>

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
