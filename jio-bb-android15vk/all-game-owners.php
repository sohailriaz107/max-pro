<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageName = 'all-game-owners';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

date_default_timezone_set('Asia/Kolkata');
$date = date('d-m-Y');

if (isset($_GET['delete_id']) && isset($con) && $con instanceof mysqli) {
    $deleteId = (int)$_GET['delete_id'];
    if ($deleteId > 0) {
        $delStmt = mysqli_prepare($con, "DELETE FROM admins WHERE id = ?");
        if ($delStmt) {
            mysqli_stmt_bind_param($delStmt, "i", $deleteId);
            mysqli_stmt_execute($delStmt);
            mysqli_stmt_close($delStmt);
        }
    }
    header("Location: all-game-owners.php");
    exit;
}

if (isset($_GET['changestatus_id']) && isset($_GET['status']) && isset($con) && $con instanceof mysqli) {
    $statusId = (int)$_GET['changestatus_id'];
    $newStatus = (int)$_GET['status'];
    if ($statusId > 0) {
        $statusStmt = mysqli_prepare($con, "UPDATE admins SET status = ? WHERE id = ?");
        if ($statusStmt) {
            mysqli_stmt_bind_param($statusStmt, "ii", $newStatus, $statusId);
            mysqli_stmt_execute($statusStmt);
            mysqli_stmt_close($statusStmt);
        }
    }
    header("Location: all-game-owners.php");
    exit;
}

$adminRows = [];
if (isset($con) && $con instanceof mysqli) {
    $query = mysqli_query($con, "SELECT * FROM admins ORDER BY id DESC");
    if ($query instanceof mysqli_result) {
        while ($row = mysqli_fetch_assoc($query)) {
            $adminRows[] = $row;
        }
        mysqli_free_result($query);
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
	<title>All Game Owners</title>

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

    <script type="text/javascript">
    function delete_id(id)
    {
        if(confirm('Sure to Delete ?'))
        {
            window.location.href='all-game-owners.php?delete_id='+encodeURIComponent(id);
        }
    }

    function changestatus_id(id,status)
    {
        if(confirm('Sure to Change Status?'))
        {
            window.location.href='all-game-owners.php?changestatus_id='+encodeURIComponent(id)+'&status='+encodeURIComponent(status);
        }
    }
    </script>
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
			<div class="col-md-12">
				<table class="table table-bordered responsive">
					<thead>
						<tr>
							<th width="5%">ID</th>
							<th>Username</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Mobile</th>
							<th>Operations</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                        foreach ($adminRows as $res) {
                            $adminId = (int)$res['id'];
                            $usernameEsc = htmlspecialchars((string)($res['username'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $firstEsc = htmlspecialchars((string)($res['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $lastEsc = htmlspecialchars((string)($res['lastname'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $mobileEsc = htmlspecialchars((string)($res['mobile'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $status = (int)($res['status'] ?? 0);
                        ?>
						<tr>
							<td><?php echo $adminId; ?></td>
							<td><?php echo $usernameEsc; ?></td>
							<td><?php echo $firstEsc; ?></td>
							<td><?php echo $lastEsc; ?></td>
							<td><?php echo $mobileEsc; ?></td>
							<td>
								<a href="edit-game-owners.php?edit_id=<?php echo $adminId; ?>" class="btn btn-default btn-sm btn-icon icon-left">
									<i class="entypo-pencil"></i>
									Edit
								</a>
								<a href="edit-game-owners-pass.php?edit_id=<?php echo $adminId; ?>" class="btn btn-default btn-sm btn-icon icon-left">
									<i class="entypo-pencil"></i>
									Password
								</a>
								<a href="permissions.php?permission_id=<?php echo $adminId; ?>" class="btn btn-default btn-sm btn-icon icon-left">
									<i class="entypo-pencil"></i>
									Permissions
								</a>
								<a href="free-game-permissions.php?fg_permission_id=<?php echo $adminId; ?>" class="btn btn-default btn-sm btn-icon icon-left">
									<i class="entypo-pencil"></i>
									Free Game Permissions
								</a>
								<a href="javascript:delete_id('<?php echo $adminId; ?>')" class="btn btn-danger btn-sm btn-icon icon-left">
									<i class="entypo-cancel"></i>
									Delete
								</a>
								<?php if ($status === 1) { ?>
								<a href="javascript:changestatus_id('<?php echo $adminId; ?>',0)" class="btn btn-success btn-sm btn-icon icon-left">
									<i class="entypo-cancel"></i>
									Active
								</a>
								<?php } else { ?>
								<a href="javascript:changestatus_id('<?php echo $adminId; ?>',1)" class="btn btn-danger btn-sm btn-icon icon-left">
									<i class="entypo-cancel"></i>
									Deactive
								</a>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
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
