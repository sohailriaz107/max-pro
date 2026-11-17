<?php 
session_start();

exit; 

$pageName='update-notice';
require('sessions.php');
require "../config.php";
if (isset($_SESSION['firstname'])) {
  if ($_SESSION['firstname']!='vk' AND $_SESSION['firstname']!='rohanji') {
    header("location:index.php");
    $_SESSION['illegal']='Illegal Activity Detected';
    exit;
  }
}
require('functions.php');
$today=date('d-m-Y');

$file='../txtdb/update-notice.txt';
if (isset($_POST['updateTrick'])) {
  $notice=$_POST['notice'];

$insert=file_put_contents('../txtdb/update-notice.txt', $notice);
	$msg='<div class="alert alert-success">Update Successfully</div>';
}
else{
	$msg='<h2>Update notice</h2>'; 
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="icon" href="assets/images/favicon.ico">

	<title>Update notice </title>

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
	
	<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

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
							Update Notice
						</div>
						
					</div>
					
					<div class="panel-body">

		<form role="form" class="form-horizontal form-groups-bordered" action="" method="post">

			<div class="row">
      <div class="col-md-12 col-sm-12">
          <label>Notice</label>
          <textarea class="form-control" name="notice"><?php echo file_get_contents($file); ?></textarea>
		  
		  <script>
                        CKEDITOR.replace( 'notice' );
          </script>
      </div>
      <div class="col-md-12 col-sm-12">
          <button type="submit" class="btn btn-primary" name="updateTrick" style="margin-top: 25px">UPDATE</button>
      </div>
  </div>
		</form>
		<hr>
					</div>
				
				</div>
			
			</div>
		</div>
		
		
<?php include 'footer.php' ?>
	</div>
	
</div>


	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
	<link rel="stylesheet" href="assets/js/codemirror/lib/codemirror.css">
	<link rel="stylesheet" href="assets/js/uikit/css/uikit.min.css">
	<link rel="stylesheet" href="assets/js/uikit/addons/css/markdownarea.css">

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>


	<!-- Imported scripts on this page -->
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>

	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>