<?php 
session_start();
$pageName='update-weekly';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

$file='../txtdb/weekly.txt';
$lines= file($file);

// echo memory_get_usage();
if(isset($_POST['addMarket'])) {
  $line0=htmlspecialchars($_POST['0'])."\n";
  $line1=htmlspecialchars($_POST['1'])."\n";
  $line2=htmlspecialchars($_POST['2'])."\n";
  $line3=htmlspecialchars($_POST['3'])."\n";
  $line4=htmlspecialchars($_POST['4'])."\n";
  $line5=htmlspecialchars($_POST['5'])."\n";
  $line6=htmlspecialchars($_POST['6'])."\n";
  $line7=htmlspecialchars($_POST['7'])."\n";
  $line8=htmlspecialchars($_POST['8'])."\n";
  $line9=htmlspecialchars($_POST['9'])."\n";
  $line10=htmlspecialchars($_POST['10'])."\n";
  $line11=htmlspecialchars($_POST['11'])."\n";
  $line12=htmlspecialchars($_POST['12'])."\n";
  $line13=htmlspecialchars($_POST['13'])."\n";
  $line14=htmlspecialchars($_POST['14'])."\n";
  $line15=htmlspecialchars($_POST['15'])."\n";
  $line16=htmlspecialchars($_POST['16'])."\n";
  $line17=htmlspecialchars($_POST['17'])."\n";
  $line18=htmlspecialchars($_POST['18'])."\n";
  $line19=htmlspecialchars($_POST['19'])."\n";
  $line20=htmlspecialchars($_POST['20'])."\n";
  $line21=htmlspecialchars($_POST['21'])."\n";
  $line22=htmlspecialchars($_POST['22'])."\n";
  $line23=htmlspecialchars($_POST['23'])."\n";
  $line24=htmlspecialchars($_POST['24'])."\n";
  $line25=htmlspecialchars($_POST['25'])."\n";
  $data=$line0.$line1.$line2.$line3.$line4.$line5.$line6.$line7.$line8.$line9.$line10.$line11.$line12.$line13.$line14.$line15.$line16.$line17.$line18.$line19.$line20.$line21.$line22.$line23.$line24.$line25;
	$myFile = "../txtdb/weekly.txt";
     $fh = fopen($myFile, 'w') or die("can't open file");
     $write=fwrite($fh, $data);
    if ($write) {
      $msg='<div class="alert alert-success">Market Added Successfully! Redirecting...</div>';
      header( "refresh:3;url=update-weekly.php" );
    }
    else{
      $msg='<div class="alert alert-danger">Something Wrong!</div>';
    }
    fclose ($fh);
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
									<label>WEEKLY PANEL CHART</label>
									<input type="text" name="0" class="form-control" value="<?php echo $lines[0]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="1" class="form-control" value="<?php echo $lines[1]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="2" class="form-control" value="<?php echo $lines[2]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="3" class="form-control" value="<?php echo $lines[3]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="4" class="form-control" value="<?php echo $lines[4]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="5" class="form-control" value="<?php echo $lines[5]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="6" class="form-control" value="<?php echo $lines[6]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="7" class="form-control" value="<?php echo $lines[7]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="8" class="form-control" value="<?php echo $lines[8]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="9" class="form-control" value="<?php echo $lines[9]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="10" class="form-control" value="<?php echo $lines[10]; ?>">
								</div>
							</div>
							<br>
							<div class="form-group">
								<div class="col-md-12">
									<label>WEEKLY LINES</label>
									<input type="text" name="11" class="form-control" value="<?php echo $lines[11]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="12" class="form-control" value="<?php echo $lines[12]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="13" class="form-control" value="<?php echo $lines[13]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="14" class="form-control" value="<?php echo $lines[14]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="15" class="form-control" value="<?php echo $lines[15]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="16" class="form-control" value="<?php echo $lines[16]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="17" class="form-control" value="<?php echo $lines[17]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="18" class="form-control" value="<?php echo $lines[18]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									<label>WEEKLY Brakets</label>
									<input type="text" name="19" class="form-control" value="<?php echo $lines[19]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="20" class="form-control" value="<?php echo $lines[20]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="21" class="form-control" value="<?php echo $lines[21]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="22" class="form-control" value="<?php echo $lines[22]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="23" class="form-control" value="<?php echo $lines[23]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="24" class="form-control" value="<?php echo $lines[24]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                                  	<input type="text" name="25" class="form-control" value="<?php echo $lines[25]; ?>">
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