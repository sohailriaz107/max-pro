<?php 
session_start();
$pageName='update-image';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');
$today=date('d-m-Y');

if (isset($_POST['updateImage'])) {

$target_dir = "../upload-date/";
$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . 'advertisement-image' . '.jpg';
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_dir . basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check !== false) {
  $uploadOk = 1;
} else {
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 40960) {
  $msg = "<div class='alert alert-info'>Sorry, your file is too large. Max Limit is ".(40960/1024)."KB</div>";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "webp" && $imageFileType != "jpeg") {
  $msg = "<div class='alert alert-info'>Sorry, only JPG, JPEG, WEBP, & PNG files are allowed.</div>";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $msg .= "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $msg = "<div class='alert alert-success'>The file has been uploaded.</div>";
  } else {
    $msg = "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
  }
}

}
else{
	$msg='<h2>Update Image</h2>';
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
		
					
		<?php echo $msg; ?>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				
				<div class="panel panel-primary" data-collapsed="0">
				
					<div class="panel-heading">
						<div class="panel-title">
							Update Image
						</div>
						
					</div>
					
					<div class="panel-body">

		<form class="form-horizontal form-groups-bordered" action="" method="post" enctype="multipart/form-data">

			<div class="row">
      <div class="col-md-12 col-sm-12">
          <label>Select Image</label>
          <input type="file" name="fileToUpload" class="form-control">
      </div>
      <div class="col-md-12 col-sm-12">
          <button type="submit" class="btn btn-primary" name="updateImage" style="margin-top: 25px">UPDATE</button>
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