<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-cycle-motor-patti';
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

$file = __DIR__ . '/../txtdb/cycle-motor-patti.txt';
$msg = '<h2>Update Cycle Motor Patti</h2>';

if (isset($_POST['updateMotor'])) {
    $rawMotor = isset($_POST['motor']) ? (string)$_POST['motor'] : '';
    $data = strip_tags($rawMotor);
    $data = str_replace(["\r", "\n"], '', $data);
    $write = file_put_contents($file, $data . "\n", LOCK_EX);
    if ($write !== false) {
        $msg = '<div class="alert alert-success">Game Updated Successfully! Redirecting...</div>';
        header("refresh:3;url=update-cycle-motor-patti.php");
    } else {
        $msg = '<div class="alert alert-danger">Something Wrong!</div>';
    }
}

$lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$currentMotor = (is_array($lines) && isset($lines[0])) ? trim($lines[0]) : '';
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
	<title>Update Cycle Motor Patti</title>
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
				<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<label>Update Motor Patti</label>
							<input type="text" name="motor" class="form-control" required value="<?php echo htmlspecialchars($currentMotor, ENT_QUOTES, 'UTF-8'); ?>">
						</div>
						<div class="col-md-12 col-sm-12">
							<button type="submit" class="btn btn-primary" name="updateMotor" style="margin-top: 25px">UPDATE</button>
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
