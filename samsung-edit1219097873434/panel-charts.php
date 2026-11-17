<?php 
declare(strict_types=1);

session_start();
$pageName = 'panel-charts';
require('sessions.php');
require "../config.php";
require('functions.php');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'MBOYS') {
    $_SESSION['illegal'] = 'Illegal Activity Detected..';
    header("Location: index.php");
    exit;
}

$start = 0;
$limit = 100;

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 1;
if ($id < 1) {
    $id = 1;
}
$start = ($id - 1) * $limit;

$file = '../txtdb/xzavierJoaquinosia.txt';
$lines = file_exists($file) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$query = null;
$msg = '';

if (isset($_POST['searchMarket'])) {
    $gameName = trim((string)($_POST['gameName'] ?? ''));
    $rawFrom = trim((string)($_POST['fromDate'] ?? ''));
    $rawTo = trim((string)($_POST['toDate'] ?? ''));

    $fromTime = strtotime($rawFrom);
    $toTime = strtotime($rawTo);

    $fromDate = ($fromTime !== false) ? date('d-m-Y', $fromTime) : '01-01-1970';
    $toDate = ($toTime !== false) ? date('d-m-Y', $toTime) : '01-01-1970';

    if ($fromDate === "01-01-1970" || $toDate === "01-01-1970") {
        $stmt = mysqli_prepare($con, "SELECT * FROM `panel` WHERE `gameName` = ? ORDER BY id DESC LIMIT 20");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $gameName);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
        }
    } else {
        $stmt = mysqli_prepare($con, "SELECT * FROM `panel` WHERE `gameName` = ? AND updateDate >= ? AND updateDate <= ? ORDER BY id DESC LIMIT 20");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $gameName, $fromDate, $toDate);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
        }
    }

    $foundCount = $query ? mysqli_num_rows($query) : 0;
    $msg = "Found <b>" . $foundCount . "</b> rows";
} else {
    $stmt = mysqli_prepare($con, "SELECT * FROM `panel` ORDER BY id DESC LIMIT 20");
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
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
		<h2>Search Table</h2>
		<br />

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<div class="col-md-12">
						<form role="form" class="form-horizontal" action="" method="post">
							<div class="form-group">
								<div class="col-md-3">
									<label>Select Game</label>
									<select name="gameName" class="select2" data-allow-clear="true" data-placeholder="Select one game...">
<?php 
foreach ($lines as $line) {
    $parts = array_pad(explode("|", trim((string)$line)), 2, '');
    $game = $parts[1];
    $safeGame = htmlspecialchars($game, ENT_QUOTES, 'UTF-8');
?>
										<option value="<?php echo $safeGame; ?>"><?php echo $safeGame; ?></option>
<?php } ?>
									</select>
								</div>
								<div class="col-md-3">
									<label>From Date</label>
									<input type="date" name="fromDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" min="2023-01-01">
								</div>
								<div class="col-md-3">
									<label>To Date</label>
									<input type="date" name="toDate" class="form-control" max="<?php echo date('Y-m-d'); ?>" min="2023-01-01">
								</div>
								<div class="col-md-3">
									<button type="submit" class="btn btn-success" name="searchMarket" style="margin-top: 25px;">Search</button>
								</div>
							</div>
						</form>
					</div>

<?php if (isset($_POST['searchMarket'])) { ?>
					<div class="col-md-12">
						<div class="alert alert-warning alert-dismissible">
							<?php echo $msg; ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
<?php } ?>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th><b>Market Name</b></th>
								<th colspan="3">Open Patti</th>
								<th colspan="1">Jodi</th>
								<th colspan="3">Close Patti</th>
								<th>Frequency</th>
								<th>From Date</th>
								<th>To Date</th>
								<th>Last Update</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
<?php
if ($query instanceof mysqli_result) {
    while ($result = mysqli_fetch_assoc($query)) {
        $color = htmlspecialchars((string)($result['color'] ?? 'black'), ENT_QUOTES, 'UTF-8');
?>
							<tr>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['gameName'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['a'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['b'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['c'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['d'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['e'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['f'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['g'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['frequency'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['fromDate'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['toDate'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td style="color:<?php echo $color; ?>"><?php echo htmlspecialchars((string)$result['updateDate'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td><a href="edit-panel-charts.php?chartId=<?php echo (int)$result['id']; ?>" class="btn btn-primary">Edit</a></td>
							</tr>
<?php 
    }
}
?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php include 'footer.php'; ?>
	</div>
</div>

	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>
