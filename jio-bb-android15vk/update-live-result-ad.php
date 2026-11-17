<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'Live Result Ad Update';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

if (isset($_SESSION['firstname'])) {
    if ($_SESSION['firstname'] !== 'vk' && $_SESSION['firstname'] !== 'rohanji') {
        $_SESSION['illegal'] = 'Illegal Activity Detected';
        header("Location: index.php");
        exit;
    }
}

$file = __DIR__ . '/../txtdb/live-result-ad.txt';
$msg = '<h2>Update Live Result Ad</h2>';

if (isset($_POST['updateLiveAd'])) {
    $status     = isset($_POST['status']) ? str_replace(["|", "\r", "\n"], '', strip_tags((string)$_POST['status'])) : '0';
    $time1open  = isset($_POST['time1open']) ? str_replace(["|", "\r", "\n"], '', strip_tags((string)$_POST['time1open'])) : '';
    $time1close = isset($_POST['time1close']) ? str_replace(["|", "\r", "\n"], '', strip_tags((string)$_POST['time1close'])) : '';
    $time2open  = isset($_POST['time2open']) ? str_replace(["|", "\r", "\n"], '', strip_tags((string)$_POST['time2open'])) : '';
    $time2close = isset($_POST['time2close']) ? str_replace(["|", "\r", "\n"], '', strip_tags((string)$_POST['time2close'])) : '';

    $data = $status . '|' . $time1open . '|' . $time1close . '|' . $time2open . '|' . $time2close;

    $write = file_put_contents($file, $data, LOCK_EX);
    if ($write !== false) {
        $msg = '<div class="alert alert-success">Updated Successfully! Redirecting...</div>';
        header("refresh:3;url=update-live-result-ad.php");
    } else {
        $msg = '<div class="alert alert-danger">Something Wrong!</div>';
    }
}

$live_ad_file = file_exists($file) ? file_get_contents($file) : '';
if ($live_ad_file === false) {
    $live_ad_file = '';
}
$live_ad_array = array_pad(explode("|", trim($live_ad_file)), 5, '');
$live_ad_Status      = $live_ad_array[0];
$live_ad_time1_start = $live_ad_array[1];
$live_ad_time1_close = $live_ad_array[2];
$live_ad_time2_start = $live_ad_array[3];
$live_ad_time2_close = $live_ad_array[4];
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
	<title>Update Live Result Ad</title>
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
						<div class="panel-title">Update Ad</div>
					</div>
					<div class="panel-body">
						<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<label>Live Result Ad Status</label>
									<select name="status" class="form-control" required>
										<option value="1" <?php if ($live_ad_Status === '1') { echo 'selected'; } ?>>ON</option>
										<option value="0" <?php if ($live_ad_Status === '0') { echo 'selected'; } ?>>OFF</option>
									</select>
								</div>
								<div class="col-md-12 col-sm-12">
									<label>Time1 Open</label>
									<input type="text" name="time1open" class="form-control" value="<?php echo htmlspecialchars($live_ad_time1_start, ENT_QUOTES, 'UTF-8'); ?>">
								</div> 
								<div class="col-md-12 col-sm-12">
									<label>Time1 Close</label>
									<input type="text" name="time1close" class="form-control" value="<?php echo htmlspecialchars($live_ad_time1_close, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-md-12 col-sm-12">
									<label>Time2 Open</label>
									<input type="text" name="time2open" class="form-control" value="<?php echo htmlspecialchars($live_ad_time2_start, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-md-12 col-sm-12">
									<label>Time2 Close</label>
									<input type="text" name="time2close" class="form-control" value="<?php echo htmlspecialchars($live_ad_time2_close, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="col-md-12 col-sm-12">
									<button type="submit" class="btn btn-primary" name="updateLiveAd" style="margin-top: 25px">UPDATE</button>
								</div>
							</div>
						</form>
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
