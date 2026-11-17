<?php 
session_start();
$pageName='select-charts';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

$file='../txtdb/xzavierJoaquinosia.txt';
$lines= file($file);
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
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body skin-red">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
	
	<?php include 'sidebar-menu.php'; ?>

	<div class="main-content">
				
		<div class="row">
		
			<!-- Profile Info and Notifications -->
			<?php include 'header.php'; ?>
		
		
			
		
		</div>
		
		<hr />
		
					
		<p> <div class="alert alert-info"><b>Be Careful!</b> All charts are updated automatically. If you add them manually, there are chances to mess with the respected game charts.</div></p>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					
				<table class="table table-bordered">
					<thead>
					<tr>
						<th><b>Market Name</b></th>
						<th>Edit</th>
					</tr>
					</thead>
<?php 
foreach ($lines as $line_num => $line) {
  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate)=explode("|", $line);
?>
<form action="add-charts.php" method="post">
					<tr>
						<td><b><?php echo $gameName ?></b></td>
						<td>
							<input type="hidden" name="gameName" value="<?php echo $gameName; ?>">
							<input type="hidden" name="frequency" value="<?php echo $frequency; ?>">
							<input type="submit" name="add-charts" class="btn btn-primary" value="Add Chart Manually">
						</td>
					</tr>
					</form>
<?php } ?>
				</table>
				
				</div>

		<?php include 'footer.php' ?>
	</div>
	
</div>




	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>


	<!-- Imported scripts on this page -->
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>