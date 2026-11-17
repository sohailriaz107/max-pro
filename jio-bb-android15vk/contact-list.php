<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retain intentional disable
exit;

$pageName = 'contact-list';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';
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
<script type="text/javascript">
function delete_id(id)
{
    if(confirm('Sure to Delete ?'))
    {
        window.location.href='?delete_id='+encodeURIComponent(id);
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
				<div class="table-responsive">
				<h3>Contact List</h3>
		<br />
		<table class="table table-bordered datatable" id="table-4">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Contact No.</th>
				</tr>
			</thead>
			<tbody>
				<?php
$contact_con = @mysqli_connect("51.89.97.89", "dpbossapi", "TfyrEt%768U", "dpboss_net_api");
if ($contact_con instanceof mysqli) {
    mysqli_set_charset($contact_con, "utf8mb4");
    $stmt = mysqli_prepare($contact_con, "SELECT id, name, contact FROM android_contact_list ORDER BY id DESC");
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $queryResult = mysqli_stmt_get_result($stmt);
        if ($queryResult) {
            while ($result = mysqli_fetch_assoc($queryResult)) {
?>
					<tr>
						<td><?php echo htmlspecialchars((string)$result['id'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars((string)$result['name'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars((string)$result['contact'], ENT_QUOTES, 'UTF-8'); ?></td>
					</tr>
<?php 
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($contact_con);
}
?>
			</tbody>
			<tfoot>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Contact No.</th>
				</tr>
			</tfoot>
		</table>
		<?php include 'footer.php'; ?>
	</div>
</div>

	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/datatables/datatables.css">
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">
	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/datatables/datatables.js"></script>
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
</body>
</html>
