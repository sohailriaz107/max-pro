<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageName = 'edit-game-owners';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

$edit_id = (int)($_GET['edit_id'] ?? 0);
$fetched_row = [];

if ($edit_id > 0 && isset($con) && $con instanceof mysqli) {
    $selStmt = mysqli_prepare($con, "SELECT id, username FROM admins WHERE id = ? LIMIT 1");
    if ($selStmt) {
        mysqli_stmt_bind_param($selStmt, "i", $edit_id);
        mysqli_stmt_execute($selStmt);
        $res = mysqli_stmt_get_result($selStmt);
        if ($res && ($row = mysqli_fetch_assoc($res))) {
            $fetched_row = $row;
        }
        mysqli_stmt_close($selStmt);
    }
}

$msg_script = '';

if (isset($_POST['btn-update']) && $edit_id > 0 && isset($con) && $con instanceof mysqli) {
    $rawPassword = (string)($_POST['password'] ?? '');
    if ($rawPassword !== '') {
        $password = md5($rawPassword);
        $upStmt = mysqli_prepare($con, "UPDATE admins SET password = ? WHERE id = ?");
        if ($upStmt) {
            mysqli_stmt_bind_param($upStmt, "si", $password, $edit_id);
            if (mysqli_stmt_execute($upStmt)) {
                $msg_script = '<script type="text/javascript">alert("password updated successfully"); window.location.href="all-game-owners.php";</script>';
            } else {
                $msg_script = '<script type="text/javascript">alert("error occurred while updating password");</script>';
            }
            mysqli_stmt_close($upStmt);
        }
    } else {
        $msg_script = '<script type="text/javascript">alert("Password cannot be empty");</script>';
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
	<title>Edit Game Owner Password</title>

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
    <?php echo $msg_script; ?>
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
	              <form method="post" action="">
	                  <div class="form-group">
	                      <label>New Password for <?php echo htmlspecialchars((string)($fetched_row['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></label>
	                      <input type="password" name="password" class="form-control" placeholder="New Password" required>
	                  </div>
	                  <button type="submit" name="btn-update" class="btn btn-primary">Submit</button>
	              </form>
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
