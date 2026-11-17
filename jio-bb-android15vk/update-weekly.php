<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-weekly';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

$file = __DIR__ . '/../txtdb/weekly.txt';
$msg = 'Update Weekly';

if (isset($_POST['addMarket'])) {
    $linesArr = [];
    for ($i = 0; $i <= 25; $i++) {
        $val = isset($_POST[(string)$i]) ? (string)$_POST[(string)$i] : '';
        $val = str_replace(["\r", "\n"], '', $val);
        $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        $linesArr[] = $val;
    }
    $data = implode("\n", $linesArr) . "\n";
    $write = file_put_contents($file, $data, LOCK_EX);
    if ($write !== false) {
        $msg = '<div class="alert alert-success">Weekly Updated Successfully! Redirecting...</div>';
        header("refresh:3;url=update-weekly.php");
    } else {
        $msg = '<div class="alert alert-danger">Something Wrong!</div>';
    }
}

$lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
if (!is_array($lines)) {
    $lines = [];
}
for ($i = 0; $i <= 25; $i++) {
    if (!isset($lines[$i])) {
        $lines[$i] = '';
    } else {
        $lines[$i] = trim($lines[$i]);
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
		<h2><?php echo $msg; ?></h2>
		<br />
		<div class="row">
			<div class="col-md-12">
				<form role="form" class="form-horizontal" action="" method="post">
					<div class="form-group">
						<div class="col-md-12">
							<label>WEEKLY PANEL CHART</label>
							<input type="text" name="0" class="form-control" value="<?php echo htmlspecialchars($lines[0], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php for ($i = 1; $i <= 10; $i++): ?>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" name="<?php echo $i; ?>" class="form-control" value="<?php echo htmlspecialchars($lines[$i], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php endfor; ?>
					<br>
					<div class="form-group">
						<div class="col-md-12">
							<label>WEEKLY LINES</label>
							<input type="text" name="11" class="form-control" value="<?php echo htmlspecialchars($lines[11], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php for ($i = 12; $i <= 18; $i++): ?>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" name="<?php echo $i; ?>" class="form-control" value="<?php echo htmlspecialchars($lines[$i], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php endfor; ?>
					<br>
					<div class="form-group">
						<div class="col-md-12">
							<label>WEEKLY Brakets</label>
							<input type="text" name="19" class="form-control" value="<?php echo htmlspecialchars($lines[19], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php for ($i = 20; $i <= 25; $i++): ?>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" name="<?php echo $i; ?>" class="form-control" value="<?php echo htmlspecialchars($lines[$i], ENT_QUOTES, 'UTF-8'); ?>">
						</div>
					</div>
					<?php endfor; ?>
					<div class="form-group">
						<div class="col-md-12">
							<button type="submit" class="btn btn-success" name="addMarket" style="margin-top: 25px;">Update</button>
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
