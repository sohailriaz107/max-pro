<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'update-trick';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';

if (isset($_SESSION['firstname'])) {
    if ($_SESSION['firstname'] !== 'vk' && $_SESSION['firstname'] !== 'rohanji') {
        $_SESSION['illegal'] = 'Illegal Activity Detected';
        header("Location: index.php");
        exit;
    }
}
require_once __DIR__ . '/functions.php';
$today = date('d-m-Y');

$file = __DIR__ . '/../txtdb/update-trick.txt';
$msg = '<h2>Update Trick</h2>';

if (isset($_POST['updateTrick'])) {
    $rawTrick = isset($_POST['trick']) ? (string)$_POST['trick'] : '';
    $trick = htmlspecialchars($rawTrick, ENT_QUOTES, 'UTF-8');
    $insert = file_put_contents($file, $trick, LOCK_EX);
    if ($insert !== false) {
        $msg = '<div class="alert alert-success">Update Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger">Failed to save trick.</div>';
    }
}

$trickContent = file_exists($file) ? file_get_contents($file) : '';
if ($trickContent === false) {
    $trickContent = '';
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="assets/images/favicon.ico">
	<title>Update Trick </title>
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
						<div class="panel-title">Update Trick</div>
					</div>
					<div class="panel-body">
						<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<label>Trick</label>
									<textarea class="form-control wysihtml5" data-stylesheet-url="assets/css/wysihtml5-color.css" name="trick" id="sample_wysiwyg"><?php echo htmlspecialchars($trickContent, ENT_QUOTES, 'UTF-8'); ?></textarea>
								</div>
								<div class="col-md-12 col-sm-12">
									<button type="submit" class="btn btn-primary" name="updateTrick" style="margin-top: 25px">UPDATE</button>
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
	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" href="assets/js/codemirror/lib/codemirror.css">
	<link rel="stylesheet" href="assets/js/uikit/css/uikit.min.css">
	<link rel="stylesheet" href="assets/js/uikit/addons/css/markdownarea.css">
	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
</body>
</html>
