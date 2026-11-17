<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageName = 'add-game-owners';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

date_default_timezone_set('Asia/Kolkata');
$date = date('d-m-Y');

$user_feedback = "Please enter login info...";

if (isset($_POST['Submit']) && isset($con) && $con instanceof mysqli) {
    $username  = trim((string)($_POST['username'] ?? ''));
    $firstname = trim((string)($_POST['firstname'] ?? ''));
    $lastname  = trim((string)($_POST['lastname'] ?? ''));
    $passwordRaw = (string)($_POST['password'] ?? '');
    $password  = md5($passwordRaw);
    $mobile    = trim((string)($_POST['mobile'] ?? ''));
    $status    = 1;

    if ($username === '' || $passwordRaw === '') {
        $user_feedback = "<div class='alert alert-danger'>Username and Password cannot be empty!</div>";
    } else {
        $checkStmt = mysqli_prepare($con, "SELECT username FROM admins WHERE username = ? LIMIT 1");
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "s", $username);
            mysqli_stmt_execute($checkStmt);
            $checkRes = mysqli_stmt_get_result($checkStmt);

            if ($checkRes && mysqli_num_rows($checkRes) >= 1) {
                $user_feedback = "<div class='alert alert-danger'>This Username Already Exists!</div>";
            } else {
                $insertStmt = mysqli_prepare($con, "INSERT INTO admins (username, firstname, lastname, password, status, mobile) VALUES (?, ?, ?, ?, ?, ?)");
                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "ssssis", $username, $firstname, $lastname, $password, $status, $mobile);
                    if (mysqli_stmt_execute($insertStmt)) {
                        $user_feedback = "<div class='alert alert-success'>User Added Successfully</div>";
                    } else {
                        $user_feedback = "<div class='alert alert-danger'>Error adding user!</div>";
                    }
                    mysqli_stmt_close($insertStmt);
                }
            }
            mysqli_stmt_close($checkStmt);
        }
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
	<title>Add Game Owners</title>

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
</head>
<body class="page-body skin-red">

<div class="page-container">
	<?php include 'sidebar-menu.php'; ?>

	<div class="main-content">
		<div class="row">
			<?php include 'header.php'; ?>
		</div>
		
		<hr />
		
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">
							Add Game Owner Info
						</div>
					</div>
					
					<div class="panel-body">
						<?php echo $user_feedback; ?>
						<form role="form" method="post" action="" class="form-horizontal form-groups-bordered">
							<div class="form-group">
								<label for="username" class="col-sm-3 control-label">Username</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="username" placeholder="Username" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="firstname" class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="firstname" placeholder="First Name">
								</div>
							</div>
							
							<div class="form-group">
								<label for="lastname" class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="lastname" placeholder="Last Name">
								</div>
							</div>
							
							<div class="form-group">
								<label for="password" class="col-sm-3 control-label">Password</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="password" placeholder="Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="mobile" class="col-sm-3 control-label">Mobile</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="mobile" placeholder="Mobile Number">
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-5">
									<button type="submit" name="Submit" class="btn btn-default">Submit</button>
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

<script src="assets/js/gsap/TweenMax.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/joinable.js"></script>
<script src="assets/js/resizeable.js"></script>
<script src="assets/js/neon-api.js"></script>
<script src="assets/js/neon-custom.js"></script>
<script src="assets/js/neon-demo.js"></script>

</body>
</html>
