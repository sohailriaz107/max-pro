<?php 
declare(strict_types=1);

session_start();
$pageName = 'free-game-permissions';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

$fgPermissionId = isset($_GET['fg_permission_id']) && is_numeric($_GET['fg_permission_id']) ? (int)$_GET['fg_permission_id'] : 0;
if ($fgPermissionId <= 0) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header("Location: index.php");
    exit;
}

$stmtUser = mysqli_prepare($con, "SELECT id, username, fg_permissions FROM admins WHERE id = ?");
if (!$stmtUser) {
    die("Database error");
}
mysqli_stmt_bind_param($stmtUser, "i", $fgPermissionId);
mysqli_stmt_execute($stmtUser);
$resUser = mysqli_stmt_get_result($stmtUser);
$fetched_row = $resUser ? mysqli_fetch_assoc($resUser) : null;
mysqli_stmt_close($stmtUser);

if (!$fetched_row) {
    header("Location: index.php");
    exit;
}

$file = '../txtdb/other-free-game.txt';
$lines = file_exists($file) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$permissions = (string)($fetched_row['fg_permissions'] ?? '');

$msg = '';

if (isset($_POST['submit'])) {
    if (!empty($_POST['updatePermissions']) && is_array($_POST['updatePermissions'])) {
        $cleanPermissions = [];
        foreach ($_POST['updatePermissions'] as $perm) {
            $p = trim((string)$perm);
            if ($p !== '') {
                $cleanPermissions[] = $p;
            }
        }
        $permissions = implode(',', $cleanPermissions);

        $stmtUp = mysqli_prepare($con, "UPDATE admins SET `fg_permissions` = ? WHERE id = ?");
        if ($stmtUp) {
            $adminId = (int)$fetched_row['id'];
            mysqli_stmt_bind_param($stmtUp, "si", $permissions, $adminId);
            $exec = mysqli_stmt_execute($stmtUp);
            mysqli_stmt_close($stmtUp);
            if ($exec) {
                $msg = "Free Game Permissions Updated Successfully";
                $fetched_row['fg_permissions'] = $permissions;
            } else {
                $msg = "Something Wrong!";
            }
        } else {
            $msg = "Something Wrong!";
        }
    } else {
        $msg = "Sorry! Please select atleast one game to update.";
    }
} else {
    $msg = "Please Add Free Permissions For User <b class='text-uppercase'>" . htmlspecialchars((string)($fetched_row['username'] ?? ''), ENT_QUOTES, 'UTF-8') . "</b>";
}

$parray = array_filter(array_map('trim', explode(',', $permissions)));
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

	<title>Permissions</title>

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
				<form action="" class="form-horizontal tasi-form" method="post">
					<div class="form-group row last">
						<label class="control-label col-md-3">Search Market Name</label>
						<div class="col-md-9">
							<select name="updatePermissions[]" class="multi-select" multiple="" id="my_multi_select3" >
<?php 
foreach ($lines as $line) {
    $parts = array_pad(explode("|", trim((string)$line)), 1, '');
    $gameName = $parts[0];
    $safeGame = htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8');
    $selected = in_array($gameName, $parray, true) ? 'selected' : '';
?>
								<option value="<?php echo $safeGame; ?>" <?php echo $selected; ?>><?php echo $safeGame; ?></option>
<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary mb-2" name="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
		<?php include 'footer.php'; ?>
	</div>
</div>

	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">
	<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">
	<link rel="stylesheet" href="assets/js/icheck/skins/minimal/_all.css">
	<link rel="stylesheet" href="assets/js/icheck/skins/square/_all.css">
	<link rel="stylesheet" href="assets/js/icheck/skins/flat/_all.css">
	<link rel="stylesheet" href="assets/js/icheck/skins/futurico/futurico.css">
	<link rel="stylesheet" href="assets/js/icheck/skins/polaris/polaris.css">

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>

	<!-- Imported scripts on this page -->
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/jquery.multi-select.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
