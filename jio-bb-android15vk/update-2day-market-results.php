<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-results';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/functions.php';

$file = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
$lines = file_exists($file) ? file($file) : [];
if (!is_array($lines)) {
    $lines = [];
}
$today = date('d-m-Y');

$cleanPipe = static function(?string $val): string {
    return str_replace(["|", "\r", "\n"], '', (string)$val);
};

$msg = '<h2>Update Result</h2>';

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
    $order          = $cleanPipe($_POST['order'] ?? '');
    $gName          = $cleanPipe($_POST['gName'] ?? '');
    $rawGResult     = (string)($_POST['gResult'] ?? '');
    $gResult        = $cleanPipe($rawGResult);
    $timeOpen       = $cleanPipe($_POST['timeOpen'] ?? '');
    $timeClose      = $cleanPipe($_POST['timeClose'] ?? '');
    $gBgcolor       = $cleanPipe($_POST['gBgcolor'] ?? '');
    $gFontcolor     = $cleanPipe($_POST['gFontcolor'] ?? '');
    $gLinecolor     = $cleanPipe($_POST['gLinecolor'] ?? '');
    $frequency      = (int)($_POST['frequency'] ?? 0);
    $liveTimeBefore = $cleanPipe($_POST['liveTimeBefore'] ?? '');
    $liveTimeAfter  = $cleanPipe($_POST['liveTimeAfter'] ?? '');
    $updateDatePost = $cleanPipe($_POST['updateDate'] ?? '');
    $status         = $cleanPipe($_POST['status'] ?? '');
    $expiryDate     = $cleanPipe($_POST['expiryDate'] ?? '');
    $jodiLink       = $cleanPipe($_POST['jodiLink'] ?? '');
    $panelLink      = $cleanPipe($_POST['panelLink'] ?? '');

    $gresult_pattern = '/^(\d{3}-\d{1}(-\d{3})?|\d{3}-\d{2}-\d{3}|✪|\*{3}-\*{1}|\*{3}-\*{2}-\*{3})$/';
    if (!preg_match($gresult_pattern, $gResult)) {
        echo 'Invalid Entry';
        exit;
    }

    if (strlen($gResult) === 10 && $gResult !== '***-**-***') {
        $num = (string)$gResult;
        $open_ank = (int)$num[0] + (int)$num[1] + (int)$num[2];
        $close_ank = (int)$num[7] + (int)$num[8] + (int)$num[9];
        if (substr((string)$open_ank, -1) !== $num[4] || substr((string)$close_ank, -1) !== $num[5]) {
            header("Location: invalid-data.php");
            exit;
        }
    } elseif (strlen($gResult) === 5 && $gResult !== '***-*') {
        $num = (string)$gResult;
        $open_ank = (int)$num[0] + (int)$num[1] + (int)$num[2];
        if (substr((string)$open_ank, -1) !== $num[4]) {
            header("Location: invalid-data.php");
            exit;
        }
    }

    $data = $order . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDatePost . '|' . $status . '|' . $expiryDate . '|' . $jodiLink . '|' . $panelLink . '|' . time() . '|';

    if ($id >= 0 && isset($lines[$id])) {
        $lines[$id] = $data . "\n";
    }

    $content = implode('', $lines);
    $insert = file_put_contents($file, $content, LOCK_EX);

    if ($insert !== false) {
        $re = '/^(\d{3}-\d{2}-\d{3})$/m';
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
                $isOpenPM = str_contains(date('h:i A', strtotime($timeOpen)), 'PM');
                $isCloseAM = str_contains(date('h:i A', strtotime($timeClose)), 'AM');

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
                        $date1 = strtotime($LastUpdateDate);
                        $date2 = strtotime(str_replace('/', '-', $updateDate));
                        $count = 0;
                        while (date('Y-m-d', $date1) < date('Y-m-d', $date2)) {
                            $count += date('N', $date1) < 8 ? 1 : 0;
                            $date1 = strtotime("+1 day", $date1);
                        }
                        if ($count > 1) {
                            $dateDiff = $count - 1;
                            for ($i = 1; $i <= $dateDiff; $i++) {
                                $incDate = date("d-m-Y", strtotime("$LastUpdateDate +{$i} day"));
                                $timestamp = strtotime($incDate);
                                if (date('D', $timestamp) === 'Sat') {
                                    $weekStart = $incDate;
                                    $weekEnd = date("d-m-Y", strtotime("$weekStart +1 day"));
                                    $wkrs = str_replace('-', '/', $weekStart);
                                    $wkre = str_replace('-', '/', $weekEnd);
                                    $stmtP = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, '*', '*', '*', '**', '*', '*', '*', 'red', ?, ?, ?, ?)");
                                    if ($stmtP) {
                                        mysqli_stmt_bind_param($stmtP, "sisss", $gName, $frequency, $wkrs, $wkre, $incDate);
                                        mysqli_stmt_execute($stmtP);
                                        mysqli_stmt_close($stmtP);
                                    }
                                } else {
                                    $stmtP = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, '*', '*', '*', '**', '*', '*', '*', 'red', ?, '', '', ?)");
                                    if ($stmtP) {
                                        mysqli_stmt_bind_param($stmtP, "sis", $gName, $frequency, $incDate);
                                        mysqli_stmt_execute($stmtP);
                                        mysqli_stmt_close($stmtP);
                                    }
                                }
                            }
                        }
                    } else {
                        $dateOne = date('d-m-Y', strtotime('saturday this week'));
                        $date1 = str_replace('/', '-', $dateOne);
                        $date2 = str_replace('/', '-', $updateDate);
                        $calcDiff = dateDiffInDays($date1, $date2);
                        if ($calcDiff > 1) {
                            $dateDiff = $calcDiff - 1;
                            for ($i = 0; $i <= $dateDiff; $i++) {
                                $incDate = date("d-m-Y", strtotime("$date1 +{$i} day"));
                                $timestamp = strtotime($incDate);
                                if (date('D', $timestamp) === 'Sat') {
                                    $weekStart = $incDate;
                                    $weekEnd = date("d-m-Y", strtotime("$weekStart +1 day"));
                                    $wkrs = str_replace('-', '/', $weekStart);
                                    $wkre = str_replace('-', '/', $weekEnd);
                                    $stmtP = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, '*', '*', '*', '**', '*', '*', '*', 'red', ?, ?, ?, ?)");
                                    if ($stmtP) {
                                        mysqli_stmt_bind_param($stmtP, "sisss", $gName, $frequency, $wkrs, $wkre, $incDate);
                                        mysqli_stmt_execute($stmtP);
                                        mysqli_stmt_close($stmtP);
                                    }
                                } else {
                                    $stmtP = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, '*', '*', '*', '**', '*', '*', '*', 'red', ?, '', '', ?)");
                                    if ($stmtP) {
                                        mysqli_stmt_bind_param($stmtP, "sis", $gName, $frequency, $incDate);
                                        mysqli_stmt_execute($stmtP);
                                        mysqli_stmt_close($stmtP);
                                    }
                                }
                            }
                        }
                    }
                    mysqli_stmt_close($stmtH);
                }

                $stmtSel = mysqli_prepare($con, "SELECT id FROM `panel` WHERE `updateDate` = ? AND `gameName` = ?");
                if ($stmtSel) {
                    mysqli_stmt_bind_param($stmtSel, "ss", $updateDate, $gName);
                    mysqli_stmt_execute($stmtSel);
                    $selectRes = mysqli_stmt_get_result($stmtSel);
                    $num_today = $selectRes ? mysqli_num_rows($selectRes) : 0;
                    mysqli_stmt_close($stmtSel);

                    if ($num_today > 0) {
                        $canUpdate = false;
                        if (strtotime(date('H:i')) < strtotime('23:59')) {
                            $canUpdate = true;
                        } elseif ($isOpenPM && $isCloseAM && strtotime(date('H:i')) < strtotime('03:00')) {
                            $canUpdate = true;
                        }
                        if ($canUpdate) {
                            $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a`=?, `b`=?, `c`=?, `d`=?, `e`=?, `f`=?, `g`=? WHERE `updateDate`=? AND `gameName`=?");
                            if ($stmtUp) {
                                mysqli_stmt_bind_param($stmtUp, "sssssssss", $a, $b, $c, $d, $e, $f, $g, $updateDate, $gName);
                                mysqli_stmt_execute($stmtUp);
                                mysqli_stmt_close($stmtUp);
                                $msg = '<div class="alert alert-success">Chart Updated</div>';
                            }
                        }
                    } else {
                        if ($isOpenPM && $isCloseAM) {
                            if (date('D') === 'Sun') {
                                if (strtotime(date('H:i')) < strtotime('03:00')) {
                                    $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a`=?, `b`=?, `c`=?, `d`=?, `e`=?, `f`=?, `g`=? WHERE `updateDate`=? AND `gameName`=?");
                                    if ($stmtUp) {
                                        mysqli_stmt_bind_param($stmtUp, "sssssssss", $a, $b, $c, $d, $e, $f, $g, $updateDate, $gName);
                                        mysqli_stmt_execute($stmtUp);
                                        mysqli_stmt_close($stmtUp);
                                        $msg = '<div class="alert alert-success">Chart Updated</div>';
                                    }
                                } else {
                                    $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                    if ($stmtIns) {
                                        mysqli_stmt_bind_param($stmtIns, "sssssssssisss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $frequency, $fromDate, $toDate, $updateDate);
                                        mysqli_stmt_execute($stmtIns);
                                        mysqli_stmt_close($stmtIns);
                                    }
                                }
                            } else {
                                $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if ($stmtIns) {
                                    mysqli_stmt_bind_param($stmtIns, "sssssssssis", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $frequency, $updateDate);
                                    mysqli_stmt_execute($stmtIns);
                                    mysqli_stmt_close($stmtIns);
                                }
                            }
                        } elseif (date('D') === 'Sat') {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssisss", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $frequency, $fromDate, $toDate, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        } else {
                            $stmtIns = mysqli_prepare($con, "INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmtIns) {
                                mysqli_stmt_bind_param($stmtIns, "sssssssssis", $gName, $a, $b, $c, $d, $e, $f, $g, $color, $frequency, $updateDate);
                                mysqli_stmt_execute($stmtIns);
                                mysqli_stmt_close($stmtIns);
                            }
                        }
                        $msg = '<div class="alert alert-success">Chart Also Updated Successfully</div>';
                    }
                }
            }
            $msg .= $msgUpdate;
        } else {
            $msg = '<div class="alert alert-success">Result Update Successfully! <b>Redirecting...</b></div>';
            header('refresh:2;url=update-main-market-results.php');
        }
    } else {
        $msg = 'Something Wrong!';
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
if (!empty($counter) && $counter >= 1 && !empty($expiryLines) && is_array($expiryLines)) {
    foreach ($expiryLines as $line_num => $line) {
        $expParts = array_pad(explode("|", trim($line)), 16, '');
        $gameName = $expParts[1];
        $expiryDate = $expParts[13];

        if (!empty($expiryPermissions) && in_array($gameName, $expiryPermissions, true)) {
            $date1 = str_replace('/', '-', date('Y-m-d'));
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2);

            if ($calcDiff >= 1 && $calcDiff <= 5 && strtotime($date1) < strtotime($date2)) {
                if (isset($_SESSION['username']) && $_SESSION['username'] !== 'vk') {
?>
<div class="alert alert-info alert-dismissible" role="alert"><strong>You have <?php echo (int)$calcDiff; ?> days left to renew <?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?></strong></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var opts = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-bottom-right",
        "timeOut": "16000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.warning("You have <?php echo (int)$calcDiff . ($calcDiff > 1 ? ' days' : ' day'); ?> left to renew <?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>", "Urgent Notice", opts);
});
</script>
<?php 
                }
            } elseif ($calcDiff == 0) {
                if (isset($_SESSION['username']) && $_SESSION['username'] !== 'vk') {
?>
<div class="alert alert-danger alert-dismissible" role="alert"><strong>Your plan has expired for <?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>. Please Renew it as soon as possible.</strong></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var opts = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-bottom-right",
        "timeOut": "15000",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    toastr.error("Your plan has expired for <?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>. Please Renew it as soon as possible.", "Plan Expired", opts);
});
</script>
<?php 
                }
            }
        }
    }
}

$permissions = [];
if (isset($_SESSION['id'])) {
    $adminId = (int)$_SESSION['id'];
    $stmtA = mysqli_prepare($con, "SELECT id, permissions FROM admins WHERE id = ?");
    if ($stmtA) {
        mysqli_stmt_bind_param($stmtA, "i", $adminId);
        mysqli_stmt_execute($stmtA);
        $resA = mysqli_stmt_get_result($stmtA);
        if ($resA && $rowA = mysqli_fetch_assoc($resA)) {
            $permissions = explode(',', (string)$rowA['permissions']);
        }
        mysqli_stmt_close($stmtA);
    }
}

foreach ($lines as $line_num => $line) {
    $lineParts = array_pad(explode("|", trim($line)), 16, '');
    $order         = $lineParts[0];
    $gameName      = $lineParts[1];
    $gameResult    = $lineParts[2];
    $timeOpen      = $lineParts[3];
    $timeClose     = $lineParts[4];
    $gameBgcolor   = $lineParts[5];
    $gameFontcolor = $lineParts[6];
    $gameLinecolor = $lineParts[7];
    $frequency     = $lineParts[8];
    $liveTimeBefore= $lineParts[9];
    $liveTimeAfter = $lineParts[10];
    $updateDate    = $lineParts[11];
    $status        = $lineParts[12];
    $expiryDate    = $lineParts[13];
    $jodiLink      = $lineParts[14];
    $panelLink     = $lineParts[15];

    if (in_array($gameName, $permissions, true)) {
        if (isset($_SESSION['username']) && $_SESSION['username'] === 'vk') {
            if ($gameName === 'RAJDHANI SUNDAY' || $gameName === 'DEmo') {
?>
<form role="form" class="form-horizontal" action="?id=<?php echo (int)$line_num; ?>" method="post">
    <div class="form-group">
        <label class="col-sm-3"><?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?></label>
        <input type="hidden" name="gName" class="form-control" value="<?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>">
        <div class="col-sm-5">
            <input type="text" name="gResult" class="form-control" value="<?php echo htmlspecialchars($gameResult, ENT_QUOTES, 'UTF-8'); ?>" required>
            <input type="hidden" name="order" class="form-control" value="<?php echo htmlspecialchars($order, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="timeOpen" class="form-control" value="<?php echo htmlspecialchars($timeOpen, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="timeClose" class="form-control" value="<?php echo htmlspecialchars($timeClose, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo htmlspecialchars($gameBgcolor, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="gFontcolor" value="<?php echo htmlspecialchars($gameFontcolor, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="gLinecolor" value="<?php echo htmlspecialchars($gameLinecolor, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="frequency" value="<?php echo htmlspecialchars($frequency, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php echo htmlspecialchars($liveTimeBefore === '' ? '5' : $liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="updateDate" class="form-control" value="<?php 
                if (str_contains(date('h:i A', strtotime($timeOpen)), 'PM') && str_contains(date('h:i A', strtotime($timeClose)), 'AM')) {
                    $h = (int)date('H');
                    echo ($h >= 0 && $h <= 3) ? date("d-m-Y", strtotime('-1 days')) : $today;
                } else {
                    echo $today;
                }
            ?>">
            <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php echo htmlspecialchars($liveTimeAfter === '' ? '25' : $liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="status" class="form-control" value="<?php echo $status === '0' ? '0' : '1'; ?>">
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
            }
        }
    }
}
?>
			</div>
		</div>
		<?php include 'footer.php'; ?>
		<div style="position: fixed; right: 10px; bottom: 30px">
			<form action="cloud-flare-purge-all.php" method="post">
				<input type="submit" name="clear_cache" value="Clear Cache" class="btn btn-danger btn-sm">
			</form>
		</div>
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
	<script src="assets/js/toastr.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
</body>
</html>
