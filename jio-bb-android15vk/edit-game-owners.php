<?php 
session_start();
$pageName='edit-game-owners';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

if(isset($_GET['edit_id']))
{
$edit_id = mysqli_real_escape_string($con, $_GET['edit_id']);
 $sql_query="SELECT * FROM admins WHERE id=".$edit_id;
 $result_set=mysqli_query($con,$sql_query);
 $fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
}
if(isset($_POST['btn-update']))
{
 // variables for input data
     
$firstname = mysqli_real_escape_string($con,$_POST['firstname']);
$lastname = mysqli_real_escape_string($con,$_POST['lastname']);
$mobile = mysqli_real_escape_string($con,$_POST['mobile']);
$edit_id = mysqli_real_escape_string($con, $_GET['edit_id']);
        // variables for input data

 // sql query for update data into database
$sql_query="UPDATE admins SET `firstname`='$firstname',`lastname`='$lastname',`mobile`='$mobile' WHERE id=".$edit_id;

 // sql query for update data into database
 
 // sql query execution function
 if(mysqli_query($con,$sql_query))
 {
  ?>
  <script type="text/javascript">
  alert('users updated successfully');
  window.location.href='';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('error occured while updating data');
  </script>
  <?php
 }
 // sql query execution function
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

	<title>Add Game Owners</title>

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
		
					
		<h2>  Update Owner Details</h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">			
				<form action="" method="post">
	                  <div class="form-group">
	                      <label>Username</label>
	                      <p><b><?php echo $fetched_row['username']; ?></b></p>
	                  </div>
	                  <div class="form-group">
	                      <label>First Name</label>
	                      <input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?php echo $fetched_row['firstname'] ?>">
	                  </div>
	                  <div class="form-group">
	                      <label>Last Name</label>
	                      <input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo $fetched_row['lastname'] ?>">
	                  </div>
	                  <div class="form-group">
                          <label>Mobile Number</label>
                          <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?php echo $fetched_row['mobile'] ?>">
                      </div>
	                  <button type="submit" name="btn-update" class="btn btn-primary">Submit</button>
	              </form>
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