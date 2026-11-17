<?php 
session_start();
$pageName='all-game-owners';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

date_default_timezone_set('Asia/Kolkata');
$date=date('d-m-Y');
//Fetch from database first 10 items which is its limit. For that when page open you can see first 10 items. 
$query=mysqli_query($con,"SELECT * FROM admins ORDER BY id DESC");

if(isset($_GET['delete_id']))
{
 $sql_query="DELETE FROM admins WHERE id=".$_GET['delete_id'];
 mysqli_query($con,$sql_query);
 header("Location: $_SERVER[PHP_SELF]");
}
if(isset($_GET['changestatus_id']))
{
 $sql_query="UPDATE admins SET `status`='".$_GET['status']."' WHERE id=".$_GET['changestatus_id'];
 mysqli_query($con,$sql_query);
 header("Location: $_SERVER[PHP_SELF]");
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

	<title>All Game Owners</title>

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
<script type="text/javascript">
function edit_id(id)
{
  window.location.href='edit-game-owners.php?edit_id='+id;
}
function permission_id(id)
{
  window.location.href='permissions.php?permission_id='+id;
}
function fg_permission_id(id)
{
  window.location.href='free-game-permissions.php?fg_permission_id='+id;
}
function delete_id(id)
{
 if(confirm('Sure to Delete ?'))
 {
  window.location.href='?delete_id='+id;
 }
}
function changestatus_id(id,status)
{
  window.location.href='?changestatus_id='+id+'&status='+status;
}
</script>

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
		
					
		<h2> All Game Owners</h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">			
				<div class="table-responsive">
                        <table class="table table-hover table-bordered">
                              <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>Username</th>
                                  <th>Status</th>
                                  <th colspan="4" style="text-align: center;">Actions</th>
                              </tr>
                              </thead>
                              <tbody>
<?php
while($result=mysqli_fetch_array($query))
{
?>
                              <tr>
                                  <td><?php echo $result['firstname'] ?></td>
                                  <td><?php echo $result['username'] ?></td>
                                  <td>
                                    <?php 
                      if ($result['status']==0) { ?>
                         <a href="javascript:changestatus_id('<?php echo $result['id']; ?>',1)" class="btn btn-success btn-sm">Activate</a>
                         <?php } else { ?>
                         <a href="javascript:changestatus_id('<?php echo $result['id']; ?>',0)" class="btn-danger btn-sm">Deactivate</a>
                         <?php } ?>
                                  </td>
                                  <td><a href="javascript:edit_id('<?php echo $result['id']; ?>')" class="btn-info btn-sm">Edit</a></td>
								  <td><a href="edit-game-owners-pass.php?edit_id=<?php echo $result['id']; ?>" class="btn-info btn-sm">Password</a></td>
                                  <td><a href="javascript:permission_id('<?php echo $result['id']; ?>')" class="btn-info btn-sm">Permissions</a></td>
                                  <td><a href="javascript:fg_permission_id('<?php echo $result['id']; ?>')" class="btn-success btn-sm">Free Game Permission</a></td>
                                  <td><a href="javascript:delete_id('<?php echo $result['id']; ?>')" class="btn btn-danger btn-sm">Delete</a></td>
                              </tr>
<?php } ?>
                              </tbody>
                          </table>
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