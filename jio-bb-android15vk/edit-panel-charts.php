<?php 
session_start();
$pageName='panel-charts';
require('sessions.php');
require "../config.php";
require('functions.php');
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk'||$_SESSION['username']=='SANTOSH') {
    
  }
  else{
  	header("location:index.php");
    $_SESSION['illegal']='Illegal Activity Detected';
    exit;
  }
}

if (isset($_POST['update'])) {
	
	if(preg_match("/^[0-9]+$/", $_POST['id'])) {
		//do nothing.
	}else {
		echo 'Invalid Entry1';
		exit;
	}
	
	$dateRegex = '/^(0[1-9]|1\d|2\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
	
	if(preg_match($dateRegex, $_POST['fromDate']) || $_POST['fromDate'] =='') {
		//do nothing.
	}else {
		echo 'Invalid Entry2';
		exit;
	}
	
	if(preg_match($dateRegex, $_POST['toDate']) || $_POST['fromDate'] =='') {
		//do nothing.
	}else {
		echo 'Invalid Entry3';
		exit;
	}
	
	
  $id = $_POST['id'];
  $a = $_POST['a'];
  $b = $_POST['b'];
  $c = $_POST['c'];
  $d = $_POST['d'];
  $e = $_POST['e'];
  $f = $_POST['f'];
  $g = $_POST['g'];
  $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99|✪)$/';
  if (preg_match_all($detectColor,$d)) {
    $color='red';
  }
  else{
    $color='black';
  }
  
  if(strlen($a) > 1 || strlen($b) > 1 || strlen($c) > 1 || strlen($d) > 2 || strlen($e) > 1 || strlen($f) > 1 || strlen($g) > 1 ){
  echo "Sorry!! Not Allowed";
  exit;
  }
  
  

  $updateDate=date('d/m/Y');
  
  $fromDate = isset($_POST['fromDate']) ? trim((string)$_POST['fromDate']) : '';
  $toDate   = isset($_POST['toDate']) ? trim((string)$_POST['toDate']) : '';

  if ($fromDate === '' && $toDate === '') {
      $stmt = mysqli_prepare($con, "UPDATE `panel` SET `a`=?, `b`=?, `c`=?, `d`=?, `e`=?, `f`=?, `g`=?, `color`=? WHERE `id`=?");
      if ($stmt) {
          mysqli_stmt_bind_param($stmt, "ssssssssi", $a, $b, $c, $d, $e, $f, $g, $color, $id);
          if (mysqli_stmt_execute($stmt)) {
              $msg = '<div class="alert alert-success" role="alert">Update Succesfully! <a href="panel-charts.php">Back to chart list</a></div>';
          } else {
              $msg = 'fails';
          }
          mysqli_stmt_close($stmt);
      } else {
          $msg = 'fails';
      }
  } else {
      $stmt = mysqli_prepare($con, "UPDATE `panel` SET `a`=?, `b`=?, `c`=?, `d`=?, `e`=?, `f`=?, `g`=?, `color`=?, `fromDate`=?, `toDate`=? WHERE `id`=?");
      if ($stmt) {
          mysqli_stmt_bind_param($stmt, "ssssssssssi", $a, $b, $c, $d, $e, $f, $g, $color, $fromDate, $toDate, $id);
          if (mysqli_stmt_execute($stmt)) {
              $msg = '<div class="alert alert-success" role="alert">Update Succesfully! <a href="panel-charts.php">Back to chart list</a></div>';
          } else {
              $msg = 'fails';
          }
          mysqli_stmt_close($stmt);
      } else {
          $msg = 'fails';
      }
  }
  
  // Prepare log content
    $logData = "User: " . $_SESSION['username'] . PHP_EOL;
    $logData .= "Date: " . date('Y-m-d H:i:s') . PHP_EOL;
    $logData .= "Updated Panel ID: " . $id . PHP_EOL;
    $logData .= "Inputs: a=$a, b=$b, c=$c, d=$d, e=$e, f=$f, g=$g, fromDate=$fromDate, toDate=$toDate" . PHP_EOL;
    $logData .= "---------------------------" . PHP_EOL;

    // Append to log file
    file_put_contents('update_log.txt', $logData, FILE_APPEND);

}
else {
  $msg = '<h3>Update Panel Chart</h3>';
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
<?php 
if (isset($_GET['chartId']) && is_numeric($_GET['chartId']) && $_GET['chartId'] > 0){
$chartId = $_GET['chartId'];
$query = mysqli_query($con,"SELECT * FROM panel WHERE id=$chartId") or die(mysql_error());
$result = mysqli_fetch_array($query);
?>
<form method="post" action="">
<table class="table table-bordered">
<tr>
	<td style="color:<?php echo $result['color'] ?>" width="100"><b>Game Name</b></td>
	<td style="color:<?php echo $result['color'] ?>" colspan="3"><b><?php echo $result['gameName'] ?></b></td>
</tr>
<tr>
	<td style="color:<?php echo $result['color'] ?>"><b>Open Patti</b></td>
	<td style="color:<?php echo $result['color'] ?>" width="100"><input type="text" name="a" class="form-control" value="<?php echo $result['a'] ?>"></td>
	<td style="color:<?php echo $result['color'] ?>"><input type="text" name="b" class="form-control" value="<?php echo $result['b'] ?>"></td>
	<td style="color:<?php echo $result['color'] ?>"><input type="text" name="c" class="form-control" value="<?php echo $result['c'] ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $result['color'] ?>" colspan="1"><b>Jodi</b></td>
	<td style="color:<?php echo $result['color'] ?>" colspan="3"><input type="text" name="d" class="form-control" value="<?php echo $result['d'] ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $result['color'] ?>"><b>Close Patti</b></td>
	<td style="color:<?php echo $result['color'] ?>"><input type="text" name="e" class="form-control" value="<?php echo $result['e'] ?>"></td>
	<td style="color:<?php echo $result['color'] ?>"><input type="text" name="f" class="form-control" value="<?php echo $result['f'] ?>"></td>
	<td style="color:<?php echo $result['color'] ?>"><input type="text" name="g" class="form-control" value="<?php echo $result['g'] ?>"></td>
</tr>
<?php 
// if (!empty($result['fromDate']) AND !empty($result['toDate'])) {
?>
<tr>
	<td style="color:<?php echo $result['color'] ?>"><b>From Date</b></td>
	<td style="color:<?php echo $result['color'] ?>" colspan="3"><input type="text" name="fromDate" class="form-control" value="<?php echo $result['fromDate'] ?>"></td>
</tr>
<tr>
	<td style="color:<?php echo $result['color'] ?>"><b>To Date</b></td>
	<td style="color:<?php echo $result['color'] ?>" colspan="3"><input type="text" name="toDate" class="form-control" value="<?php echo $result['toDate'] ?>">
	</td>
</tr>
<?php //} ?>

<tr>
	<td colspan="4">
		<input type="hidden" name="id" value="<?php echo $chartId; ?>"/>
		<input type="submit" name="update" value="Update" class="btn btn-primary"></td>
</tr>
</table>	
</form>
<?php } ?>
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