<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'send-notification';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

$msg = 'Send Notification';

if (isset($_POST['send_noti'])) {
    $title = isset($_POST['title']) ? (string)$_POST['title'] : '';
    $message = isset($_POST['message']) ? (string)$_POST['message'] : '';
    
    $topic = 'all';
    $title = str_replace(' ', '+', $title);
    $description = str_replace(' ', '+', $message);
    $res = sendPushNotificationFCM($topic, $title, $description);
    $msg = '<div class="alert alert-info">' . htmlspecialchars((string)$res, ENT_QUOTES, 'UTF-8') . '</div>';
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
	<title>Send Notification</title>
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
							<label>Title</label>
							<input type="text" name="title" placeholder="Enter Game Title" class="form-control" maxlength="30" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<label>Message</label>
							<textarea name="message" placeholder="Enter Message" class="form-control" maxlength="100" rows="3" required></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<button type="submit" name="send_noti" class="btn btn-primary">Send Notification</button>
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
</body>
</html>
