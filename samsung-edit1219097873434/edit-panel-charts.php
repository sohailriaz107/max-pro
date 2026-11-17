<?php 
declare(strict_types=1);

session_start();
$pageName = 'panel-charts';
require('sessions.php');
require "../config.php";
require('functions.php');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'MBOYS') {
    $_SESSION['illegal'] = 'Illegal Activity Detected';
    header("Location: index.php");
    exit;
}

$msg = '<h3>Update Panel Chart</h3>';

if (isset($_GET['del_id'])) {
    if (preg_match("/^[0-9]+$/", (string)$_GET['del_id'])) {
        $delId = (int)$_GET['del_id'];
        $stmt = mysqli_prepare($con, "DELETE FROM panel WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $delId);
            if (mysqli_stmt_execute($stmt)) {
                $msg = '<div class="alert alert-success">Record deleted successfully.</div>';
            } else {
                $msg = '<div class="alert alert-danger">Error deleting record.</div>';
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Invalid ID.";
        exit;
    }
}

if (isset($_POST['update'])) {
    $rawId = (string)($_POST['id'] ?? '');
    if (!preg_match("/^[0-9]+$/", $rawId)) {
        echo 'Invalid Entry1';
        exit;
    }
    $id = (int)$rawId;

    $dateRegex = '/^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
    $rawFromDate = trim((string)($_POST['fromDate'] ?? ''));
    $rawToDate = trim((string)($_POST['toDate'] ?? ''));

    if ($rawFromDate !== '' && !preg_match($dateRegex, $rawFromDate)) {
        echo 'Invalid Entry2';
        exit;
    }

    if ($rawToDate !== '' && !preg_match($dateRegex, $rawToDate)) {
        echo 'Invalid Entry3';
        exit;
    }

    $a = (string)($_POST['a'] ?? '');
    $b = (string)($_POST['b'] ?? '');
    $c = (string)($_POST['c'] ?? '');
    $d = (string)($_POST['d'] ?? '');
    $e = (string)($_POST['e'] ?? '');
    $f = (string)($_POST['f'] ?? '');
    $g = (string)($_POST['g'] ?? '');

    if (strlen($a) > 1 || strlen($b) > 1 || strlen($c) > 1 || strlen($d) > 2 || strlen($e) > 1 || strlen($f) > 1 || strlen($g) > 1) {
        echo "Sorry!! Not Allowed";
        exit;
    }

    $detectColor = '/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99|✪)$/';
    $color = preg_match($detectColor, $d) ? 'red' : 'black';

    $fromDate = $rawFromDate;
    $toDate = $rawToDate;

    $stmtUp = mysqli_prepare($con, "UPDATE `panel` SET `a` = ?, `b` = ?, `c` = ?, `d` = ?, `e` = ?, `f` = ?, `g` = ?, `color` = ?, `fromDate` = ?, `toDate` = ? WHERE `id` = ?");
    if ($stmtUp) {
        mysqli_stmt_bind_param($stmtUp, "ssssssssssi", $a, $b, $c, $d, $e, $f, $g, $color, $fromDate, $toDate, $id);
        $executed = mysqli_stmt_execute($stmtUp);
        mysqli_stmt_close($stmtUp);

        if ($executed) {
            $msg = '<div class="alert alert-success" role="alert">Update Successfully! <a href="panel-charts.php">Back to chart list</a></div>';
        } else {
            $msg = '<div class="alert alert-danger">Update failed.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Update failed.</div>';
    }

    // Prepare log content safely
    $logUsername = (string)($_SESSION['username'] ?? 'unknown');
    $logData = "User: " . $logUsername . PHP_EOL;
    $logData .= "Date: " . date('Y-m-d H:i:s') . PHP_EOL;
    $logData .= "Updated Panel ID: " . $id . PHP_EOL;
    $logData .= "Inputs: a=$a, b=$b, c=$c, d=$d, e=$e, f=$f, g=$g, fromDate=$fromDate, toDate=$toDate" . PHP_EOL;
    $logData .= "---------------------------" . PHP_EOL;

    @file_put_contents('update_log.txt', $logData, FILE_APPEND | LOCK_EX);
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
		<h2><?php echo $msg; ?></h2>
		<br />

		<div class="row">
			<div class="col-md-12">
<?php 
if (isset($_GET['chartId']) && is_numeric($_GET['chartId']) && (int)$_GET['chartId'] > 0) {
    $chartId = (int)$_GET['chartId'];
    $stmtChart = mysqli_prepare($con, "SELECT * FROM panel WHERE id = ?");
    $result = null;
    if ($stmtChart) {
        mysqli_stmt_bind_param($stmtChart, "i", $chartId);
        mysqli_stmt_execute($stmtChart);
        $res = mysqli_stmt_get_result($stmtChart);
        $result = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmtChart);
    }
    if ($result) {
        $color = htmlspecialchars((string)($result['color'] ?? 'black'), ENT_QUOTES, 'UTF-8');
?>
<form method="post" action="">
<table class="table table-bordered">
<tr>
	<td style="color:<?php echo $color; ?>" width="100"><b>Game Name</b></td>
	<td style="color:<?php echo $color; ?>" colspan="3"><b><?php echo htmlspecialchars((string)$result['gameName'], ENT_QUOTES, 'UTF-8'); ?></b></td>
</tr>
<tr>
	<td style="color:<?php echo $color; ?>"><b>Open Patti</b></td>
	<td style="color:<?php echo $color; ?>" width="100"><input type="text" name="a" class="form-control" value="<?php echo htmlspecialchars((string)$result['a'], ENT_QUOTES, 'UTF-8'); ?>"></td>
	<td style="color:<?php echo $color; ?>"><input type="text" name="b" class="form-control" value="<?php echo htmlspecialchars((string)$result['b'], ENT_QUOTES, 'UTF-8'); ?>"></td>
	<td style="color:<?php echo $color; ?>"><input type="text" name="c" class="form-control" value="<?php echo htmlspecialchars((string)$result['c'], ENT_QUOTES, 'UTF-8'); ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $color; ?>" colspan="1"><b>Jodi</b></td>
	<td style="color:<?php echo $color; ?>" colspan="3"><input type="text" name="d" class="form-control" value="<?php echo htmlspecialchars((string)$result['d'], ENT_QUOTES, 'UTF-8'); ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $color; ?>"><b>Close Patti</b></td>
	<td style="color:<?php echo $color; ?>"><input type="text" name="e" class="form-control" value="<?php echo htmlspecialchars((string)$result['e'], ENT_QUOTES, 'UTF-8'); ?>"></td>
	<td style="color:<?php echo $color; ?>"><input type="text" name="f" class="form-control" value="<?php echo htmlspecialchars((string)$result['f'], ENT_QUOTES, 'UTF-8'); ?>"></td>
	<td style="color:<?php echo $color; ?>"><input type="text" name="g" class="form-control" value="<?php echo htmlspecialchars((string)$result['g'], ENT_QUOTES, 'UTF-8'); ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $color; ?>"><b>From Date</b></td>
	<td style="color:<?php echo $color; ?>" colspan="3"><input type="text" name="fromDate" class="form-control" value="<?php echo htmlspecialchars((string)$result['fromDate'], ENT_QUOTES, 'UTF-8'); ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $color; ?>"><b>To Date</b></td>
	<td style="color:<?php echo $color; ?>" colspan="3"><input type="text" name="toDate" class="form-control" value="<?php echo htmlspecialchars((string)$result['toDate'], ENT_QUOTES, 'UTF-8'); ?>"></td>
</tr>

<tr>
	<td colspan="4">
		<input type="hidden" name="id" value="<?php echo $chartId; ?>"/>
		<input type="submit" name="update" value="Update" class="btn btn-primary">
		<?php 
		$m_update_date = str_replace('/', '-', (string)($result['updateDate'] ?? ''));
		$today = date('d-m-Y');
		$yesterday = date('d-m-Y', strtotime('-1 day'));

		if ($m_update_date === $today || $m_update_date === $yesterday) { ?>
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="?del_id=<?php echo $chartId; ?>" class="btn btn-warning">Delete</a>
		<?php } ?>
	</td>
</tr>
</table>	
</form>
<?php 
    }
} 
?>
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
