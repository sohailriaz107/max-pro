<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "../config.php";
require_once "functions.php";

$usr_feedback = "Please Enter Login Info...";

if (isset($_POST['login'])) {
    $username = trim((string)($_POST['username'] ?? ''));
    $rawPassword = (string)($_POST['password'] ?? '');
    $password = md5($rawPassword);

    $stmt = mysqli_prepare($con, "SELECT * FROM admins WHERE username = ? AND password = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $getUserRoleRow = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ((int)($getUserRoleRow['status'] ?? 0) === 1) {
                $_SESSION['id'] = $getUserRoleRow['id'];
                $_SESSION['username'] = $getUserRoleRow['username'];
                $_SESSION['firstname'] = $getUserRoleRow['firstname'] ?? $getUserRoleRow['username'];
                
                $expirtyFile = '../txtdb/xzavierJoaquinosia.txt';
                $expiryLines = file_exists($expirtyFile) ? (file($expirtyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

                $expiryPermissions = [];
                $stmtExp = mysqli_prepare($con, "SELECT id, permissions FROM admins WHERE id = ?");
                if ($stmtExp) {
                    $uid = (int)$_SESSION['id'];
                    mysqli_stmt_bind_param($stmtExp, "i", $uid);
                    mysqli_stmt_execute($stmtExp);
                    $resExp = mysqli_stmt_get_result($stmtExp);
                    if ($resExp && $rowExp = mysqli_fetch_assoc($resExp)) {
                        $expiryPermissions = array_filter(array_map('trim', explode(',', (string)($rowExp['permissions'] ?? ''))));
                        $_SESSION['m_permissions'] = (string)($rowExp['permissions'] ?? '');
                    }
                    mysqli_stmt_close($stmtExp);
                }

                $hasPermission = false;
                foreach ($expiryLines as $line) {
                    $parts = array_pad(explode("|", trim((string)$line)), 14, '');
                    $gameName = $parts[1];
                    $expiryDate = $parts[13] !== '' ? $parts[13] : ($parts[12] ?? '');
                    if (in_array($gameName, $expiryPermissions, true)) {
                        $hasPermission = true;
                        $date1 = str_replace('/', '-', date('Y-m-d')); 
                        $date2 = str_replace('/', '-', $expiryDate);

                        if (strtotime($date1) <= strtotime($date2)) {
                            header("Location: update-results.php");
                            exit;
                        } elseif (strtotime($date1) > strtotime($date2)) {
                            $usr_feedback = "<div class='alert alert-danger'><strong>Sorry</strong> Your plan has been expired. Please contact admin</div>";
                        }
                    }
                }
                if (!$hasPermission) {
                    $usr_feedback = "<div class='alert alert-danger'><strong>Sorry!</strong> You don't have any game permissions added by admin</div>";
                }
            } else {
                $usr_feedback = "<div class='alert alert-danger'><strong>Sorry</strong> Your ID is not active. Please contact Admin</div>";
            }
        } else {
            if ($stmt) {
                mysqli_stmt_close($stmt);
            }
            $usr_feedback = "Invalid Login";
        }
    } else {
        $usr_feedback = "Database error. Please try again later.";
    }
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Administrator Login Panel" />
	<meta name="author" content="" />

	<link rel="icon" type="image/png" href="/favicon.png"/>

	<title>ADMINISTRATOR LOGIN</title>

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

	<style>
	.login-page .login-content a {
	    color: cyan;
	}
	</style>
</head>
<body class="page-body skin-red login-page">

<div class="login-container">
	<div class="login-header login-caret">
		<div class="login-content">
			<h1><a href="index.php" class="logo">Amul</a></h1>
			<p class="description"><?php 
			if (!isset($_SESSION['illegal'])) {
			  echo $usr_feedback;
			} else {
			  echo '<div class="alert alert-danger">' . htmlspecialchars((string)$_SESSION['illegal'], ENT_QUOTES, 'UTF-8') . '</div>';
			  unset($_SESSION['illegal']);
			}
			?></p>
		</div>
	</div>
	
	<div class="login-form">
		<div class="login-content">
			<form method="post" action="">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						<input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required />
					</div>
				</div>
				
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" required />
					</div>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login" name="login">
						<i class="entypo-login"></i>
						Submit
					</button>
				</div>
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
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-login.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
