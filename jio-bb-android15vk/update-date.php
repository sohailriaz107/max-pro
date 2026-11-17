<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-date';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

$today = date('d-m-Y');
$file = __DIR__ . '/../txtdb/date.txt';
$msg = '<h2>Update Date</h2>';

if (isset($_POST['updateDate'])) {
    $oldDate = isset($_POST['oldDate']) ? str_replace(["\r", "\n"], '', strip_tags((string)$_POST['oldDate'])) : '';
    $newDate = isset($_POST['newDate']) ? str_replace(["\r", "\n"], '', strip_tags((string)$_POST['newDate'])) : '';
    $data = $oldDate . "\n" . $newDate . "\n";
    $insert = file_put_contents($file, $data, LOCK_EX);
    if ($insert !== false) {
        $msg = '<div class="alert alert-success">Update Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to update date.</div>';
    }
}

$lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$oldDateVal = (is_array($lines) && isset($lines[0])) ? trim($lines[0]) : '';
$newDateVal = (is_array($lines) && isset($lines[1])) ? trim($lines[1]) : '';
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
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Update Date</div>
					</div>
					<div class="panel-body">
						<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<label>Old Date</label>
									<input type="text" name="oldDate" class="form-control" required value="<?php echo htmlspecialchars($oldDateVal, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-md-12 col-sm-12">
									<label>New Date</label>
									<input type="text" name="newDate" class="form-control" required value="<?php echo htmlspecialchars($newDateVal, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-md-12 col-sm-12">
									<button type="submit" class="btn btn-primary" name="updateDate" style="margin-top: 25px">UPDATE</button>
								</div>
							</div>
						</form>
						<hr>
					</div>
				</div>
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
