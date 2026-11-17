<?php 
declare(strict_types=1);

session_start();
$pageName = 'add-charts';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

$msg = 'Add Chart';
$msg2 = '';

if (isset($_POST['add-charts'])) {
    $gName = trim((string)($_POST['gameName'] ?? ''));
    $freq = isset($_POST['frequency']) ? (int)$_POST['frequency'] : 0;

    if (isset($_POST['insertChart'])) {
        $fromDate = trim((string)($_POST['fromDate'] ?? ''));
        $toDate = trim((string)($_POST['toDate'] ?? ''));
        $updateDate = date('d/m/Y');

        if ($freq === 7 || $freq === 6 || $freq === 5) {
            $valid = true;
            $rowsToInsert = [];

            for ($d = 1; $d <= $freq; $d++) {
                $openPatti = trim((string)($_POST['openPatti' . $d] ?? ''));
                $closePatti = trim((string)($_POST['closePatti' . $d] ?? ''));

                if (!preg_match('/^\d{3}$/', $openPatti) || !preg_match('/^\d{3}$/', $closePatti)) {
                    $valid = false;
                    break;
                }

                $oa = $openPatti[0];
                $ob = $openPatti[1];
                $oc = $openPatti[2];

                $ca = $closePatti[0];
                $cb = $closePatti[1];
                $cc = $closePatti[2];

                $jodiCalcOpen = (int)$oa + (int)$ob + (int)$oc;
                $jodiOpen = substr((string)$jodiCalcOpen, -1);

                $jodiCalcClose = (int)$ca + (int)$cb + (int)$cc;
                $jodiClose = substr((string)$jodiCalcClose, -1);

                $jodi = $jodiOpen . $jodiClose;

                $detectColor = '/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
                $color = preg_match($detectColor, $jodi) ? 'red' : 'black';

                $rowFromDate = ($d === 1) ? $fromDate : '';
                $rowToDate = ($d === 1) ? $toDate : '';

                $rowsToInsert[] = [
                    'oa' => $oa, 'ob' => $ob, 'oc' => $oc,
                    'jodi' => $jodi,
                    'ca' => $ca, 'cb' => $cb, 'cc' => $cc,
                    'color' => $color,
                    'fromDate' => $rowFromDate,
                    'toDate' => $rowToDate
                ];
            }

            if ($valid && count($rowsToInsert) === $freq) {
                $stmt = mysqli_prepare($con, "INSERT INTO `panel` (`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $allSuccess = true;
                    $freqStr = (string)$freq;
                    foreach ($rowsToInsert as $r) {
                        mysqli_stmt_bind_param(
                            $stmt,
                            "sssssssssssss",
                            $gName,
                            $r['oa'], $r['ob'], $r['oc'],
                            $r['jodi'],
                            $r['ca'], $r['cb'], $r['cc'],
                            $r['color'],
                            $freqStr,
                            $r['fromDate'],
                            $r['toDate'],
                            $updateDate
                        );
                        if (!mysqli_stmt_execute($stmt)) {
                            $allSuccess = false;
                            break;
                        }
                    }
                    mysqli_stmt_close($stmt);

                    if ($allSuccess) {
                        $msg2 = '<div class="alert alert-success">Good! All record of ' . htmlspecialchars($gName, ENT_QUOTES, 'UTF-8') . ' are updated successfully!</div>';
                    } else {
                        $msg2 = '<div class="alert alert-danger">Something Wrong!</div>';
                    }
                } else {
                    $msg2 = '<div class="alert alert-danger">Something Wrong!</div>';
                }
            } else {
                $msg2 = '<div class="alert alert-danger">Format Not Matched!</div>';
            }
        } else {
            $msg2 = '<div class="alert alert-danger">Frequency Not Matched!</div>';
        }
    }
} else {
    header('Location: select-charts.php');
    exit;
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
		<h2><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></h2>
		<?php echo $msg2; ?>
		<br />

		<div class="row">
			<div class="col-md-12">
				<form role="form" class="form-horizontal" action="" method="post">
					<div class="form-group">
						<div class="col-md-6">
							<label>From Date</label>
							<input type="text" name="fromDate" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required="">
						</div>
						<div class="col-md-6">
							<label>To Date</label>
							<input type="text" name="toDate" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required="">
						</div>
					</div>
<?php 
for ($i = 0; $i < $freq; $i++) { 
?>
					<div class="form-group">
						<div class="col-md-3">
							<label>Open Patti (Day <?php echo $i + 1; ?>)</label>
							<input type="text" name="openPatti<?php echo $i + 1; ?>" class="form-control" pattern="\d{3}" placeholder="123" required maxlength="3">
						</div>
						<div class="col-md-3">
							<label>Close Patti (Day <?php echo $i + 1; ?>)</label>
							<input type="text" name="closePatti<?php echo $i + 1; ?>" class="form-control" pattern="\d{3}" placeholder="456" required maxlength="3">
						</div>
					</div>
<?php
}
?>
					<div class="form-group">
						<div class="col-md-12">
							<input type="hidden" name="add-charts" value="1">
							<input type="hidden" name="gameName" value="<?php echo htmlspecialchars($gName, ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="frequency" value="<?php echo htmlspecialchars((string)$freq, ENT_QUOTES, 'UTF-8'); ?>">
							<button type="submit" class="btn btn-success" name="insertChart" style="margin-top: 25px;">Update</button>
						</div>
					</div>
				</form>
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
