<?php 
session_start();
$pageName='panel-charts';
require('sessions.php');
require "../config.php";
require('functions.php');

$start=0;
$limit=100;

echo 'Not Allowed';
exit;

if(isset($_GET['id']))
{
  $id= mysqli_real_escape_string($con,$_GET['id']);
  $start=($id-1)*$limit;
}
else{
  $id=1;
}
if (isset($_POST['searchMarket'])) {
	$gameName = mysqli_real_escape_string($con,$_POST['gameName']);
	$query=mysqli_query($con,"SELECT * FROM PANEL ORDER BY id DESC LIMIT $start, $limit WHERE gameName='$gameName'");
}

//fetch all the data from database.
$rows=mysqli_num_rows(mysqli_query($con,"select * from panel"));

if(isset($_GET['delete_id']))
{
	exit;
 $sql_query="DELETE FROM panel WHERE id=".$_GET['delete_id'];
 mysqli_query($con,$sql_query);
 header("Location: $_SERVER[PHP_SELF]");
}

//search data
$file='../txtdb/xzavierJoaquinosia.txt';
$lines= file($file);
if (isset($_POST['searchMarket'])) {
	$gameName = mysqli_real_escape_string($con,$_POST['gameName']);
	$fromDate = mysqli_real_escape_string($con,$_POST['fromDate']);
	$toDate = mysqli_real_escape_string($con,$_POST['toDate']);
	$fromDate = date('d-m-Y',strtotime($fromDate));
	$toDate = date('d-m-Y',strtotime($toDate));

	$rows=mysqli_num_rows(mysqli_query($con,"SELECT * FROM `panel` WHERE `gameName` LIKE '$gameName' ORDER BY id DESC"));

	if ($fromDate=="01-01-1970"||$toDate=="01-01-1970") {
		$query = mysqli_query($con,"SELECT * FROM `panel` WHERE `gameName` LIKE '$gameName' ORDER BY id DESC limit 14");
		$msg = "Found <b>".mysqli_num_rows($query). "</b> rows";
		$msg = "Last <b>".mysqli_num_rows($query). "</b> rows";
	}
	else{
	$query = mysqli_query($con,"SELECT * FROM `panel` WHERE `gameName` LIKE '$gameName' AND updateDate >= '$fromDate' AND updateDate <= '$toDate' ORDER BY id DESC");
		$msg = "Found <b>".mysqli_num_rows($query). "</b> rows";
	}
}
//end search data

//calculate total page number for the given table in the database 
$total=ceil($rows/$limit);
// pagination off temprary
$total = 1;
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
<script type="text/javascript">
function delete_id(id)
{
 if(confirm('Sure to Delete ?'))
 {
  window.location.href='?delete_id='+id;
 }
}
</script>

<style>
td {
  padding: 4px !important;
  font-size: 12px;
}
th {
  font-size: 12px;
  padding: 2px !important;
}

td:first-child {
  word-spacing: normal;
  white-space: break-spaces !important;
  font-size: 10px;
}
</style>
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
		
					
		<h2>Search Table</h2>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">

<div class="col-md-12">
<form role="form" class="form-horizontal" action="" method="post">



	<div class="form-group">
		<div class="col-md-3">
			<label>Select Game</label>
          	<select name="gameName" class="select2" data-allow-clear="true" data-placeholder="Select one game...">
<?php
$adminQuery=mysqli_query($con,"SELECT id, permissions, number_permissions, username FROM admins WHERE id=".$_SESSION['id']);
while($result=mysqli_fetch_array($adminQuery))
{
  $permissions = explode(',',$result['permissions']);
}

foreach ($lines as $line_num => $line) {
  list($order, $gameName)=explode("|", $line);
  if (in_array($gameName, $permissions)) {
?>
          		<option value="<?php echo $gameName ?>"><?php echo $gameName ?></option>
<?php } } ?>
          	</select>
		</div>
		
		<!--
		<div class="col-md-3">
	          <label>From Date</label>
	          <input type="date" name="fromDate" class="form-control" max="<?php echo date('Y-m-d') ?>" min="2020-01-01">
	     </div>
		<div class="col-md-3">
	          <label>To Date</label>
	          <input type="date" name="toDate" class="form-control" max="<?php echo date('Y-m-d') ?>" min="2020-01-01">
	     </div>
		 
		 -->
	     <div class="col-md-3">
	          <button type="submit" class="btn btn-success" name="searchMarket" style="margin-top: 25px;">Search</button>
	     </div>
	</div>
</form>
</div>

<?php if (isset($_POST['searchMarket'])) {?>
<div class="col-md-12">
	<div class="alert alert-warning alert-dismissible">
  	<?php echo $msg; ?>
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
</div>

				<table class="table table-bordered">
					<thead>
					<tr>
						<th><b>Market</b></th>
						<th>Result</th>
						<th>Last Update</th>
						<th>Edit</th>
					</tr>
					</thead>
<?php
while($result=mysqli_fetch_array($query)){
?>
					<tr>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['gameName'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['a'] ?><?php echo $result['b'] ?><?php echo $result['c'] ?>-<?php echo $result['d'] ?>-<?php echo $result['e'] ?><?php echo $result['f'] ?><?php echo $result['g'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['updateDate'] ?></td>
						<td><a href="edit-panel-charts.php?chartId=<?php echo $result['id'] ?>" class="btn btn-primary">Edit</a></td>
						<?php if(0){ ?>
						<td><a href="javascript:delete_id('<?php echo $result['id']; ?>')" class="btn btn-danger">Delete</a></td>
						<?php } ?>
					</tr>
<?php } ?>
				</table>
				</div>
<?php } ?>

	<nav aria-label="...">

                          <ul class="pagination">
                        <?php if($id>1)
                        {
                            //Go to previous page to show previous 10 items. If its in page 1 then it is inactive
                            echo "<li><a href='?id=".($id-1)."' aria-label='Previous' class='button'><span aria-hidden='true'>&laquo;</span></a></li>";
                        }
                        ?>
                        <?php
                        //show all the page link with page number. When click on these numbers go to particular page. 
                                for($i=max(1, $id - 5);$i<=min($id + 5, $total);$i++)
                                {
                                    if($i==$id) { echo "<li class='active'><a href='#'>".$i."</a></li>"; }
                                    
                                    else { echo "<li><a href='?id=".$i."'>".$i."</a></li>"; }
                                }
                        ?>
                        <?php
                        if($id!=$total)
                        {
                            ////Go to previous page to show next 10 items.
                            echo "<li><a href='?id=".($id+1)."' aria-label='Next' class='button'><span aria-hidden='true'>&raquo;</span></a></li>";
                        }
                        ?>
                          </ul>
          </nav>		
		<?php include 'footer.php' ?>
	</div>
	
</div>



	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>


	<!-- Imported scripts on this page -->
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/bootstrap-switch.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>