<?php 
session_start();
$pageName='free-game-permissions';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

if(isset($_GET['fg_permission_id']))
{
 $fgId = (int)$_GET['fg_permission_id'];
 $stmt = mysqli_prepare($con, "SELECT * FROM admins WHERE id = ?");
 mysqli_stmt_bind_param($stmt, "i", $fgId);
 mysqli_stmt_execute($stmt);
 $result_set = mysqli_stmt_get_result($stmt);
 $fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
}
else{
  session_destroy();
  header("location:index.php");
  exit;
}

$file='../txtdb/other-free-game.txt';
$lines= file($file);



$permissions = implode(',',array($fetched_row['fg_permissions']));

if (isset($_POST['submit'])) {
  if (!empty($_POST['updatePermissions'])) {
    $updatePermissions=$_POST['updatePermissions'];
    $permissions = implode(',',$_POST['updatePermissions']);
     $upStmt = mysqli_prepare($con, "UPDATE admins SET `fg_permissions`=? WHERE id=?");
     $adminId = (int)$fetched_row['id'];
     mysqli_stmt_bind_param($upStmt, "si", $permissions, $adminId);
     $query = mysqli_stmt_execute($upStmt);
     mysqli_stmt_close($upStmt);
     if ($query) {
       $msg="Free Game Permissions Updated Successfully";
     }
     else{
      $msg="Something Wrong!";
     }
   }
   else{
      $msg="Sorry! Please select atleast one game to update.";
   }
}
else{
  $msg="Please Add Free Permissions For User <b class='text-uppercase'>".htmlspecialchars((string)($fetched_row['username'] ?? ''), ENT_QUOTES, 'UTF-8')."</b>";
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
		
					
		<h2>  <?php echo $msg; ?></h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">			
				<form action="" class="form-horizontal tasi-form" method="post">

                              <div class="form-group row last">
                                    <label class="control-label col-md-3">Search Market Name</label>
                                    <div class="col-md-9">
                                    <select name="updatePermissions[]" class="multi-select" multiple="" id="my_multi_select3" >
<?php 
foreach ($lines as $line_num => $line) {
  list($gameName, $open, $panna, $jodi, $close)=explode("|", $line);
?>
                                      <option value="<?php echo $gameName ?>" <?php 
  $parray = explode(',',$permissions);
  if (in_array($gameName, $parray)) {
    echo 'selected';
  }
  
                                       ?>><?php echo $gameName ?></option>
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
		
		
		<?php include 'footer.php' ?>
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

	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>