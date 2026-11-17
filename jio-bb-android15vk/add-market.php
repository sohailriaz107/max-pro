<?php 
session_start();
$pageName='add-market';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');
$today=date('d-m-Y');

if(isset($_POST['addMarket'])) {
  $clean = fn($k) => str_replace('|', '', trim((string)($_POST[$k] ?? '')));
  $livePriority   = $clean('livePriority');
  $gameName       = $clean('gameName');
  $result         = $clean('result');
  $timeOpen       = $clean('timeOpen');
  $timeClose      = $clean('timeClose');
  $bgColor        = $clean('bgColor');
  $fontColor      = $clean('fontColor');
  $lineColor      = $clean('lineColor');
  $frequency      = $clean('frequency');
  $liveTimeBefore = $clean('liveTimeBefore');
  $liveTimeAfter  = $clean('liveTimeAfter');
  $updateDate     = $clean('updateDate');
  $status         = $clean('status');
  $expiryDate     = $clean('expiryDate');
  $jodiLink       = $clean('jodiLink');
  $panelLink      = $clean('panelLink');

  $data = $livePriority . '|' . $gameName . '|' . $result . '|' . $timeOpen . '|' . $timeClose . '|' . $bgColor . '|' . $fontColor . '|' . $lineColor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDate . '|' . $status . '|' . $expiryDate . '|' . $jodiLink . '|' . $panelLink . "\n";

  $myFile = __DIR__ . "/../txtdb/xzavierJoaquinosia.txt";
  $write = file_put_contents($myFile, $data, FILE_APPEND | LOCK_EX);
  if ($write !== false) {
    $msg = '<div class="alert alert-success">Market Added Successfully!</div>';
  } else {
    $msg = '<div class="alert alert-danger">Something Wrong!</div>';
  }
}
else{
  $msg='Add New Market';
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
		
					
		<h2> <?php echo $msg; ?></h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				



						<form role="form" class="form-horizontal" action="" method="post">
			
							<div class="form-group">
								<div class="col-md-12">
									<label>Game Name</label>
									<input type="text" name="gameName" placeholder="Enter Game Name" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Result</label>
                                  	<input type="text" name="result" placeholder="Enter Result" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Time Open</label>
                                  	<input type="time" name="timeOpen" placeholder="Enter Time Open" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Time Close</label>
                                  	<input type="time" name="timeClose" placeholder="Enter Time Close" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Background Color</label>
                                  	<input type="color" name="bgColor" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Font C</label>
                                  	<input type="color" name="fontColor" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Line C</label>
                                  	<input type="color" name="lineColor" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Set Frequency</label>
                                  	<select name="frequency" class="form-control">
                                  		<option value="7">Daily</option>
                                  		<option value="6">Monday to Saturday</option>
                                  		<option value="5">Monday to Friday</option>
										<option value="2">Saturday & Sunday</option>
                                  	</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Live Result Priority</label>
                                  	<input type="number" name="livePriority" class="form-control" min="0" value="99">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Live Timing Before</label>
                                  	<input type="number" name="liveTimeBefore" class="form-control" min="0" value="5">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Live Timing After</label>
									<input type="hidden" name="updateDate" class="form-control" value="<?php echo $today; ?>">
                                  	<input type="number" name="liveTimeAfter" class="form-control" min="0" value="25">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>On / Off?</label>
							          <select name="status" class="form-control">
							          	<option disabled="" selected="">Select Option</option>					          	
						          		<option value="0">Off</option>
						          		<option value="1">On</option>
							          </select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12 col-sm-12">
						          <label>Expiry Date</label>
						          <input type="date" name="expiryDate" class="form-control" min="<?php echo date('Y-m-d') ?>" required>
							     </div>
						  	</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Jodi Chart Link</label>
                                  	<input type="text" name="jodiLink" placeholder="Jodi Chart Link" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Panel Chart Link</label>
                                  	<input type="text" name="panelLink" placeholder="Panel Chart Link" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<button type="submit" class="btn btn-success" name="addMarket" style="margin-top: 25px;">Update</button>
								</div>
							</div>
							</div>
						</form>


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