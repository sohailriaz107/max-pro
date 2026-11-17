<?php 
session_start();
require "../config.php";

// Set maximum login attempts and time window (in seconds)
$maxAttempts = 5;
$timeWindow = 60; // 1 minute

// Check if login attempts session variable is set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if the time since last login attempt is within the time window
if (isset($_SESSION['last_login_attempt'])) {
    $timeSinceLastAttempt = time() - $_SESSION['last_login_attempt'];
    if ($timeSinceLastAttempt > $timeWindow) {
        // Reset attempts if the time window has passed
        $_SESSION['login_attempts'] = 0;
    }
}


if(isset($_POST['login'])){
	
	$_SESSION['login_attempts']++;
    
    // Update last login attempt time
    $_SESSION['last_login_attempt'] = time();
    
    // Check if the maximum attempts have been reached
    if ($_SESSION['login_attempts'] > $maxAttempts) {
        // Display error message or perform further action (e.g., temporarily block IP)
        echo "Too many login attempts. Please try again later.";
        exit(); // Exit to prevent further processing
    } 


    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $password2nd = $_POST['password2nd'];
    // Prepare the SQL statement
    $stmt = mysqli_prepare($con, "SELECT * FROM admins WHERE username=? AND password=?");
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if there is a row returned
    if(mysqli_num_rows($result) > 0){
        // Fetch the user row
        $userRow = mysqli_fetch_assoc($result);
        
        // Check user status
        if ($userRow['status'] == 1) {
            // Start the session and store user details
            session_start();
            $_SESSION['id'] = $userRow['id'];
            $_SESSION['username'] = $userRow['username'];
            
			
        //my code
$expirtyFile='../txtdb/xzavierJoaquinosia.txt';
$expiryLines= file($expirtyFile);
$ExpiryQuery=mysqli_query($con,"SELECT id, permissions FROM admins WHERE id=".$_SESSION['id']);
while($result=mysqli_fetch_array($ExpiryQuery))
{
  $expiryPermissions = explode(',',$result['permissions']);
}
function dateDiffInDays($date1, $date2)  
{ 
$diff = strtotime($date2) - strtotime($date1); 
return abs(round($diff / 86400)); 
}
foreach ($expiryLines as $line_num => $line) {

list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate)=explode("|", $line);
if (in_array($gameName, $expiryPermissions)) {
// start date difference logic

    $date1 = str_replace('/', '-', date('Y-m-d')); 
    $date2 = str_replace('/', '-', $expiryDate);
    $calcDiff = dateDiffInDays($date1, $date2); 
	//end date difference logic
	if (strtotime($date1)<=strtotime($date2)) {
		if ($_SESSION['username']=='MBOYS' && $password2nd == '6644') {
			header("location:panel-charts.php");
		}
		else{
			echo 'you are not allowed';
			header("location:logout.php");
			//header("location:update-results.php");
		}
		
}
elseif (strtotime($date1)>strtotime($date2)) {
	$usr_feedback="<div class='alert alert-danger'><strong>Sorry</strong> Your plan has been expired. Please contact admin</div>";
}


}
else{
	$usr_feedback="<div class='alert alert-danger'><strong>Sorry!</strong> You don't have any game permissions added by admin</div>";
}
}
        //my code end
        
      }
      else{
        $usr_feedback="<div class='alert alert-danger'><strong>Sorry</strong> Your ID is not active. Please contact Admin</div>";
      }
    }
    else {
      $usr_feedback="Invalid Login details";
    } 
}
else{
  $usr_feedback="Please Enter Login Info...";
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Administator Login Panel" />
	<meta name="author" content="" />

<link rel="icon" type="image/png" href="/favicon.png"/>

	<title>ADMINISTRATOR LOGIN</title>

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

<style>

.login-page .login-content a {
    color: cyan;
}

</style>
</head>
<body class="page-body skin-red login-page">


<div class="login-container">
	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<h1><a href="index.php" class="logo">Amul</a></h1>
			
			<p class="description"><?php 
if (!isset($_SESSION['illegal'])) {
  echo $usr_feedback;
}
if (isset($_SESSION['illegal'])) {
  echo '<div class="alert alert-danger">'.$_SESSION['illegal'].'</div>';
  session_destroy();
}
?></p>

		</div>
		
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<form method="post" action="">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off"  required />
					</div>
				
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="number" class="form-control" name="password2nd" id="password2nd" placeholder="Auth Key" autocomplete="off" required />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login" name="login">
						<i class="entypo-login"></i>
						Submit
					</button>
				</div>
				
				
				
				
				
			</form>
			
			
				
</div>


	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-login.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>