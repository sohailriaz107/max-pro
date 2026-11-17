<?php 
session_start();
$pageName='update-free-game';
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

$file='../txtdb/free-game.txt';
$lines= file($file);

// echo memory_get_usage();
if(isset($_POST['addMarket'])) {
  $line0=$_POST['0']."\n";
  $line1=$_POST['1']."\n";
  $line2=$_POST['2']."\n";
  $line3=$_POST['3']."\n";
  $line4=$_POST['4']."\n";
  $line5=$_POST['5']."\n";
  $line6=$_POST['6']."\n";
  $line7=$_POST['7']."\n";
  $line8=$_POST['8']."\n";
  $line9=$_POST['9']."\n";
  $line10=$_POST['10']."\n";
  $line11=$_POST['11']."\n";
  $line12=$_POST['12']."\n";
  $line13=$_POST['13']."\n";
  $line14=$_POST['14']."\n";
  $line15=$_POST['15']."\n";
  $line16=$_POST['16']."\n";
  $line17=$_POST['17']."\n";
  $line18=$_POST['18']."\n";
  $line19=$_POST['19']."\n";
  $line20=$_POST['20']."\n";
  $line21=$_POST['21']."\n";
  $line22=$_POST['22']."\n";
  $line23=$_POST['23']."\n";
  $line24=$_POST['24']."\n";
  $line25=$_POST['25']."\n";
  $line26=$_POST['26']."\n";
  $line27=$_POST['27']."\n";
  $line28=$_POST['28']."\n";
  $line29=$_POST['29']."\n";
  $line30=$_POST['30']."\n";
  $line31=$_POST['31']."\n";
  $line32=$_POST['32']."\n";
  $line33=$_POST['33']."\n";
  $line34=$_POST['34']."\n";
  $line35=$_POST['35']."\n";
  $line36=$_POST['36']."\n";
  $line37=$_POST['37']."\n";
  $line38=$_POST['38']."\n";
  $line39=$_POST['39']."\n";
  $line40=$_POST['40']."\n";
  $data=$line0.$line1.$line2.$line3.$line4.$line5.$line6.$line7.$line8.$line9.$line10.$line11.$line12.$line13.$line14.$line15.$line16.$line17.$line18.$line19.$line20.$line21.$line22.$line23.$line24.$line25.$line26.$line27.$line28.$line29.$line30.$line31.$line32.$line33.$line34.$line35.$line36.$line37.$line38.$line39.$line40;
  
  $string = $data;
  if($string != strip_tags($string)) {
	  echo "not Allowed";
	  exit;
  }
  
  $data = htmlspecialchars($data, ENT_QUOTES);
	$myFile = "../txtdb/free-game.txt";
     $fh = fopen($myFile, 'w') or die("can't open file");
     $write=fwrite($fh, $data);
    if ($write) {
      $msg='<div class="alert alert-success">Game Updated Successfully! Redirecting...</div>';
      header( "refresh:3;url=update-free-game.php" );
    }
    else{
      $msg='<div class="alert alert-danger">Something Wrong!</div>';
    }
    fclose ($fh);
}
else{
  $msg='Update Free Game';
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

	<title>Update Free Game</title>

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
									<label>Date</label>
									<input type="text" name="0" class="form-control" value="<?php echo $lines[0]; ?>">
								</div>
							</div>

							<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="1" class="form-control" value="<?php echo $lines[1]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="2" class="form-control" value="<?php echo $lines[2]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="3" class="form-control" value="<?php echo $lines[3]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="4" class="form-control" value="<?php echo $lines[4]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="5" class="form-control" value="<?php echo $lines[5]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="6" class="form-control" value="<?php echo $lines[6]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="7" class="form-control" value="<?php echo $lines[7]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="8" class="form-control" value="<?php echo $lines[8]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="9" class="form-control" value="<?php echo $lines[9]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="10" class="form-control" value="<?php echo $lines[10]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="11" class="form-control" value="<?php echo $lines[11]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="12" class="form-control" value="<?php echo $lines[12]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="13" class="form-control" value="<?php echo $lines[13]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="14" class="form-control" value="<?php echo $lines[14]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="15" class="form-control" value="<?php echo $lines[15]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="16" class="form-control" value="<?php echo $lines[16]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="17" class="form-control" value="<?php echo $lines[17]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="18" class="form-control" value="<?php echo $lines[18]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="19" class="form-control" value="<?php echo $lines[19]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="20" class="form-control" value="<?php echo $lines[20]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="21" class="form-control" value="<?php echo $lines[21]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="22" class="form-control" value="<?php echo $lines[22]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="23" class="form-control" value="<?php echo $lines[23]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="24" class="form-control" value="<?php echo $lines[24]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="25" class="form-control" value="<?php echo $lines[25]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="26" class="form-control" value="<?php echo $lines[26]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="27" class="form-control" value="<?php echo $lines[27]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="28" class="form-control" value="<?php echo $lines[28]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="29" class="form-control" value="<?php echo $lines[29]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="30" class="form-control" value="<?php echo $lines[30]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="31" class="form-control" value="<?php echo $lines[31]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="32" class="form-control" value="<?php echo $lines[32]; ?>">
						      </div>
						  	</div>
						  	<hr>

						  	<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="33" class="form-control" value="<?php echo $lines[33]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="34" class="form-control" value="<?php echo $lines[34]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="35" class="form-control" value="<?php echo $lines[35]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="36" class="form-control" value="<?php echo $lines[36]; ?>">
						      </div>
						  	</div>
						  	<hr>

							<div class="row">
						      <div class="col-md-3 col-sm-12">
						          <label>Game Name</label>
						          <input type="text" name="37" class="form-control" value="<?php echo $lines[37]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Open</label>
						          <input type="text" name="38" class="form-control" value="<?php echo $lines[38]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Panna</label>
						          <input type="text" name="39" class="form-control" value="<?php echo $lines[39]; ?>">
						      </div>
						      <div class="col-md-2 col-sm-12">
						          <label>Jodi</label>
						          <input type="text" name="40" class="form-control" value="<?php echo $lines[40]; ?>">
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