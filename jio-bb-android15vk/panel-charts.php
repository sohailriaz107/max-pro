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

$start=0;
$limit=100;

$id = isset($_GET['id']) ? max(1, (int)$_GET['id']) : 1;
$start = ($id - 1) * $limit;

if (isset($_GET['delete_id']) && isset($con) && $con instanceof mysqli) {
    $delId = (int)$_GET['delete_id'];
    $delStmt = mysqli_prepare($con, "DELETE FROM panel WHERE id = ?");
    if ($delStmt) {
        mysqli_stmt_bind_param($delStmt, "i", $delId);
        mysqli_stmt_execute($delStmt);
        mysqli_stmt_close($delStmt);
    }
    header("Location: panel-charts.php");
    exit;
}

$file = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
$lines = (file_exists($file) && is_readable($file)) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$query = null;
$rows = 0;

if (isset($_POST['searchMarket']) && isset($con) && $con instanceof mysqli) {
    $gameName = trim((string)($_POST['gameName'] ?? ''));
    $fromTs = !empty($_POST['fromDate']) ? strtotime((string)$_POST['fromDate']) : false;
    $toTs   = !empty($_POST['toDate']) ? strtotime((string)$_POST['toDate']) : false;
    $fromDate = ($fromTs !== false) ? date('d-m-Y', $fromTs) : '01-01-1970';
    $toDate   = ($toTs !== false) ? date('d-m-Y', $toTs) : '01-01-1970';

    if ($fromDate === '01-01-1970' || $toDate === '01-01-1970') {
        $stmt = mysqli_prepare($con, "SELECT * FROM `panel` WHERE `gameName` = ? ORDER BY id DESC");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $gameName);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            $rows = $query ? mysqli_num_rows($query) : 0;
            $msg = "Found <b>" . $rows . "</b> rows";
        }
    } else {
        $stmt = mysqli_prepare($con, "SELECT * FROM `panel` WHERE `gameName` = ? AND updateDate >= ? AND updateDate <= ? ORDER BY id DESC");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $gameName, $fromDate, $toDate);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            $rows = $query ? mysqli_num_rows($query) : 0;
            $msg = "Found <b>" . $rows . "</b> rows";
        }
    }
} else if (isset($con) && $con instanceof mysqli) {
    $countRes = mysqli_query($con, "SELECT COUNT(*) as total FROM panel");
    if ($countRes && ($cRow = mysqli_fetch_assoc($countRes))) {
        $rows = (int)($cRow['total'] ?? 0);
    }
    $listStmt = mysqli_prepare($con, "SELECT * FROM panel ORDER BY id DESC LIMIT ?, ?");
    if ($listStmt) {
        mysqli_stmt_bind_param($listStmt, "ii", $start, $limit);
        mysqli_stmt_execute($listStmt);
        $query = mysqli_stmt_get_result($listStmt);
    }
}

$total = ($limit > 0) ? (int)ceil($rows / $limit) : 1;
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
foreach ($lines as $line_num => $line) {
  list($order, $gameName)=explode("|", $line);
?>
          		<option value="<?php echo $gameName ?>"><?php echo $gameName ?></option>
<?php } ?>
          	</select>
		</div>
		<div class="col-md-3">
	          <label>From Date</label>
	          <input type="date" name="fromDate" class="form-control" max="<?php echo date('Y-m-d') ?>" min="2020-01-01">
	     </div>
		<div class="col-md-3">
	          <label>To Date</label>
	          <input type="date" name="toDate" class="form-control" max="<?php echo date('Y-m-d') ?>" min="2020-01-01">
	     </div>
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
<?php } ?>
</div>

				<table class="table table-bordered">
					<thead>
					<tr>
						<th><b>Market Name</b></th>
						<th colspan="3">Open Patti</th>
						<th colspan="1">Jodi</th>
						<th colspan="3">Close Patti</th>
						<th>Frequency</th>
						<th>From Date</th>
						<th>To Date</th>
						<th>Last Update</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
					</thead>
<?php
while($result=mysqli_fetch_array($query)){
?>
					<tr>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['gameName'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['a'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['b'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['c'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['d'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['e'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['f'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['g'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['frequency'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['fromDate'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['toDate'] ?></td>
						<td style="color:<?php echo $result['color'] ?>"><?php echo $result['updateDate'] ?></td>
						<td><a href="edit-panel-charts.php?chartId=<?php echo $result['id'] ?>" class="btn btn-primary">Edit</a></td>
						<td><a href="javascript:delete_id('<?php echo $result['id']; ?>')" class="btn btn-danger">Delete</a></td>
					</tr>
<?php } ?>
				</table>
				</div>

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