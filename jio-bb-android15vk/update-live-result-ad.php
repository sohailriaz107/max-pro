<?php 
session_start();
$pageName='Live Result Ad Update';
require('sessions.php');
require "../config.php";
require "illegal.php";

require('functions.php');
if (isset($_SESSION['firstname'])) {
  if ($_SESSION['firstname']!='vk' AND $_SESSION['firstname']!='rohanji') {
    header("location:index.php");
    $_SESSION['illegal']='Illegal Activity Detected';
    exit;
  }
}

$file='../txtdb/live-result-ad.txt';
$lines= file($file);
if (isset($_POST['updateLiveAd'])) {


$data=strip_tags($_POST['status']).'|'.strip_tags($_POST['time1open']).'|'.strip_tags($_POST['time1close']).'|'.strip_tags($_POST['time2open']).'|'.strip_tags($_POST['time2close']);

	$myFile = "../txtdb/live-result-ad.txt";
     $fh = fopen($myFile, 'w') or die("can't open file");
     $write=fwrite($fh, $data);
    if ($write) {
      $msg='<div class="alert alert-success">Updated Successfully! Redirecting...</div>';
      header( "refresh:3;url=update-live-result-ad.php" );
    }
    else{
      $msg='<div class="alert alert-danger">Something Wrong!</div>';
    }
    fclose ($fh);
}
else{
	$msg='<h2>Update Live Result Ad</h2>';
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

	<title>Update Live Result Ad</title>

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
		
					
		<?php echo $msg; ?>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				
				<div class="panel panel-primary" data-collapsed="0">
				
					<div class="panel-heading">
						<div class="panel-title">
							Update Ad
						</div>
						
					</div>
					
					<div class="panel-body">

						<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">

						<div class="row">
						  <div class="col-md-12 col-sm-12">
						  <?php 
							$live_ad_file = file_get_contents('../txtdb/live-result-ad.txt');
							$live_ad_array = explode("|",$live_ad_file);
							$live_ad_Status = $live_ad_array[0];
							$live_ad_time1_start = $live_ad_array[1];
							$live_ad_time1_close = $live_ad_array[2];
							$live_ad_time2_start = $live_ad_array[3];
							$live_ad_time2_close = $live_ad_array[4];


							?>
							  <label>Live Result Ad Status</label>
							  <select name="status" class="form-control" required="">
							  <option value="1" <?php if($live_ad_Status ==1){echo 'selected';}?> >ON</option>
							  <option value="0" <?php if($live_ad_Status ==0){echo 'selected';}?> >OFF</option>
							  
							  </select>
						  </div>


							<div class="col-md-12 col-sm-12">
							  <label>Time1 Open</label>
							  <input type="text" name="time1open" value="<?php echo $live_ad_time1_start;?>">
						  </div> 

							<div class="col-md-12 col-sm-12">
							  <label>Time1 Close</label>
							  <input type="text" name="time1close" value="<?php echo $live_ad_time1_close;?>">
						  </div>

							<div class="col-md-12 col-sm-12">
							  <label>Time2 Open</label>
							  <input type="text" name="time2open" value="<?php echo $live_ad_time2_start;?>">
						  </div>
							<div class="col-md-12 col-sm-12">
							  <label>Time2 Close</label>
							  <input type="text" name="time2close" value="<?php echo $live_ad_time2_close;?>">
						  </div>


						  <div class="col-md-12 col-sm-12">
							  <button type="submit" class="btn btn-primary" name="updateLiveAd" style="margin-top: 25px">UPDATE</button>
						  </div>
					  </div>
					</form>
					</div>
				
				</div>
			
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-md-12">
				

					
				
				</div>
			
			</div>
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