<?php
session_start();
$pageName='add-game-owners';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

date_default_timezone_set('Asia/Kolkata');
$date=date('d-m-Y');
if(isset($_POST['Submit']))
{

  $username=$_POST['username'];
  $firstname=$_POST['firstname'];
  $lastname=$_POST['lastname'];
  $password=md5($_POST['password']);
  $mobile=$_POST['mobile'];
  $status=1;

    $checkUser = mysqli_query($con,"SELECT username FROM admins where username='".$username."'");
            $num_rows = mysqli_num_rows($checkUser);
            if($num_rows >= 1){
                $user_feedback="<div class='alert alert-danger'>This Username Already Exits!</div>";
            }
            else {
              $insert=mysqli_query($con,"INSERT INTO admins(username, firstname, lastname, password, status, mobile) VALUES ('$username','$firstname','$lastname','$password','$status','$mobile')");
              $user_feedback="<div class='alert alert-success'>User Added Successfully</div>";
              ?>
  <script type="text/javascript">
  // alert('users Added successfully');
  // window.location.href='add-game-owners.php';
  </script>
  <?php
            }
}

else {
    $user_feedback="Please enter login info...";
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
		
					
		<h2>  <?php echo $user_feedback ?></h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">			
				<form action="" method="post">
                                  <div class="form-group">
                                      <label>Username</label>
                                      <input type="text" name="username" class="form-control" placeholder="Username">
                                  </div>
                                  <div class="form-group">
                                      <label>First Name</label>
                                      <input type="text" name="firstname" class="form-control" placeholder="First Name">
                                  </div>
                                  <div class="form-group">
                                      <label>Last Name</label>
                                      <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                                  </div>
                                  <div class="form-group">
                                      <label>Password</label>
                                      <input type="password" name="password" class="form-control" placeholder="Password">
                                  </div>
                                  <div class="form-group">
                                      <label>Mobile Number</label>
                                      <input type="text" name="mobile" class="form-control" placeholder="Mobile">
                                  </div>
                                  <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
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