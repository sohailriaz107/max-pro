<?php 
session_start();
$pageName='permissions';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

if(isset($_GET['permission_id']))
{
 $sql_query="SELECT * FROM admins WHERE id=".$_GET['permission_id'];
 $result_set=mysqli_query($con,$sql_query);
 $fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
}
else{
  session_destroy();
  header("location:index.php");
  exit;
}

$file='../txtdb/xzavierJoaquinosia.txt';
$lines= file($file);

//sl stands for star line
$slfile='../txtdb/star_line_results.txt';
$sllines= file($slfile);


$permissions = implode(',',array($fetched_row['permissions']));

if (isset($_POST['submit'])) {
  if (!empty($_POST['updatePermissions'])) {
    $updatePermissions=$_POST['updatePermissions'];
    $permissions = implode(',',$_POST['updatePermissions']);
    if (isset($_POST['numPermission'])) {
    	$numPermission = '1';
    }
    else{
    	$numPermission = '0';
    }
     $query=mysqli_query($con,"UPDATE admins SET `permissions`='$permissions', `number_permissions`='$numPermission' WHERE id=".$fetched_row['id']);
     if ($query) {
       $msg="Permissions Updated Successfully";
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
  $msg="Please Add Market Permissions For User <b class='text-uppercase'>".$fetched_row['username']."</b>";
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
  list($order, $gameName)=explode("|", $line);
?>
                                      <option value="<?php echo $gameName ?>" <?php 
  $parray = explode(',',$permissions);
  if (in_array($gameName, $parray)) {
    echo 'selected';
  }
  
                                       ?>><?php echo $gameName ?></option>
<?php } ?>
<?php 
$parray = explode(',',$permissions);
?>
                                      <option value="msl1" <?php if (in_array('msl1', $parray)) {echo 'selected';} ?>>Main SL 09:05 AM</option>
                                      <option value="msl2" <?php if (in_array('msl2', $parray)) {echo 'selected';} ?>>Main SL 10:05 AM</option>
                                      <option value="msl3" <?php if (in_array('msl3', $parray)) {echo 'selected';} ?>>Main SL 11:05 AM</option>
                                      <option value="msl4" <?php if (in_array('msl4', $parray)) {echo 'selected';} ?>>Main SL 12:05 PM</option>
                                      <option value="msl5" <?php if (in_array('msl5', $parray)) {echo 'selected';} ?>>Main SL 01:05 PM</option>
                                      <option value="msl6" <?php if (in_array('msl6', $parray)) {echo 'selected';} ?>>Main SL 02:05 PM</option>
                                      <option value="msl7" <?php if (in_array('msl7', $parray)) {echo 'selected';} ?>>Main SL 03:05 PM</option>
                                      <option value="msl8" <?php if (in_array('msl8', $parray)) {echo 'selected';} ?>>Main SL 04:05 PM</option>
                                      <option value="msl9" <?php if (in_array('msl9', $parray)) {echo 'selected';} ?>>Main SL 05:05 PM</option>
                                      <option value="msl10" <?php if (in_array('msl10', $parray)) {echo 'selected';} ?>>Main SL 06:05 PM</option>
                                      <option value="msl11" <?php if (in_array('msl11', $parray)) {echo 'selected';} ?>>Main SL 07:05 PM</option>
                                      <option value="msl12" <?php if (in_array('msl12', $parray)) {echo 'selected';} ?>>Main SL 08:05 PM</option>

                                      <option value="cmsl1" <?php if (in_array('cmsl1', $parray)) {echo 'selected';} ?>>CM SL 09:15 AM</option>
                                      <option value="cmsl2" <?php if (in_array('cmsl2', $parray)) {echo 'selected';} ?>>CM SL 10:15 AM</option>
                                      <option value="cmsl3" <?php if (in_array('cmsl3', $parray)) {echo 'selected';} ?>>CM SL 11:15 AM</option>
                                      <option value="cmsl4" <?php if (in_array('cmsl4', $parray)) {echo 'selected';} ?>>CM SL 12:15 PM</option>
                                      <option value="cmsl5" <?php if (in_array('cmsl5', $parray)) {echo 'selected';} ?>>CM SL 01:15 PM</option>
                                      <option value="cmsl6" <?php if (in_array('cmsl6', $parray)) {echo 'selected';} ?>>CM SL 02:15 PM</option>
                                      <option value="cmsl7" <?php if (in_array('cmsl7', $parray)) {echo 'selected';} ?>>CM SL 03:15 PM</option>
                                      <option value="cmsl8" <?php if (in_array('cmsl8', $parray)) {echo 'selected';} ?>>CM SL 04:15 PM</option>
                                      <option value="cmsl9" <?php if (in_array('cmsl9', $parray)) {echo 'selected';} ?>>CM SL 05:15 PM</option>
                                      <option value="cmsl10" <?php if (in_array('cmsl10', $parray)) {echo 'selected';} ?>>CM SL 06:15 PM</option>
                                      <option value="cmsl11" <?php if (in_array('cmsl11', $parray)) {echo 'selected';} ?>>CM SL 07:15 PM</option>
                                      <option value="cmsl12" <?php if (in_array('cmsl12', $parray)) {echo 'selected';} ?>>CM SL 08:15 PM</option>

                                      <option value="mrsl1" <?php if (in_array('mrsl1', $parray)) {echo 'selected';} ?>>MUM RAJ SL 09:30 AM</option>
                                      <option value="mrsl2" <?php if (in_array('mrsl2', $parray)) {echo 'selected';} ?>>MUM RAJ SL 10:30 AM</option>
                                      <option value="mrsl3" <?php if (in_array('mrsl3', $parray)) {echo 'selected';} ?>>MUM RAJ SL 11:30 AM</option>
                                      <option value="mrsl4" <?php if (in_array('mrsl4', $parray)) {echo 'selected';} ?>>MUM RAJ SL 12:30 PM</option>
                                      <option value="mrsl5" <?php if (in_array('mrsl5', $parray)) {echo 'selected';} ?>>MUM RAJ SL 01:30 PM</option>
                                      <option value="mrsl6" <?php if (in_array('mrsl6', $parray)) {echo 'selected';} ?>>MUM RAJ SL 02:30 PM</option>
                                      <option value="mrsl7" <?php if (in_array('mrsl7', $parray)) {echo 'selected';} ?>>MUM RAJ SL 03:30 PM</option>
                                      <option value="mrsl8" <?php if (in_array('mrsl8', $parray)) {echo 'selected';} ?>>MUM RAJ SL 04:30 PM</option>
                                      <option value="mrsl9" <?php if (in_array('mrsl9', $parray)) {echo 'selected';} ?>>MUM RAJ SL 05:30 PM</option>
                                      <option value="mrsl10" <?php if (in_array('mrsl10', $parray)) {echo 'selected';} ?>>MUM RAJ SL 06:30 PM</option>
                                      <option value="mrsl11" <?php if (in_array('mrsl11', $parray)) {echo 'selected';} ?>>MUM RAJ SL 07:30 PM</option>
                                      <option value="mrsl12" <?php if (in_array('mrsl12', $parray)) {echo 'selected';} ?>>MUM RAJ SL 08:30 PM</option>

                                      <option value="kplsl1" <?php if (in_array('kplsl1', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 11:05 AM</option>
                                      <option value="kplsl2" <?php if (in_array('kplsl2', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 12:05 AM</option>
                                      <option value="kplsl3" <?php if (in_array('kplsl3', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 01:05 PM</option>
                                      <option value="kplsl4" <?php if (in_array('kplsl4', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 02:05 PM</option>
                                      <option value="kplsl5" <?php if (in_array('kplsl5', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 03:05 PM</option>
                                      <option value="kplsl6" <?php if (in_array('kplsl6', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 04:05 PM</option>
                                      <option value="kplsl7" <?php if (in_array('kplsl7', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 05:05 PM</option>
                                      <option value="kplsl8" <?php if (in_array('kplsl8', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 06:05 PM</option>
                                      <option value="kplsl9" <?php if (in_array('kplsl9', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 07:05 PM</option>
                                      <option value="kplsl10" <?php if (in_array('kplsl10', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 08:05 PM</option>
                                      <option value="kplsl11" <?php if (in_array('kplsl11', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 09:05 PM</option>
                                      <option value="kplsl12" <?php if (in_array('kplsl12', $parray)) {echo 'selected';} ?>>KALYAN PETTI LIVE SL 10:05 PM</option>

                                      <option value="msl_org1" <?php if (in_array('msl_org1', $parray)) {echo 'selected';} ?>>MAIN SL 09:15 AM</option>
                                      <option value="msl_org2" <?php if (in_array('msl_org2', $parray)) {echo 'selected';} ?>>MAIN SL 10:15 AM</option>
                                      <option value="msl_org3" <?php if (in_array('msl_org3', $parray)) {echo 'selected';} ?>>MAIN SL 11:15 AM</option>
                                      <option value="msl_org4" <?php if (in_array('msl_org4', $parray)) {echo 'selected';} ?>>MAIN SL 12:15 PM</option>
                                      <option value="msl_org5" <?php if (in_array('msl_org5', $parray)) {echo 'selected';} ?>>MAIN SL 01:15 PM</option>
                                      <option value="msl_org6" <?php if (in_array('msl_org6', $parray)) {echo 'selected';} ?>>MAIN SL 02:15 PM</option>
                                      <option value="msl_org7" <?php if (in_array('msl_org7', $parray)) {echo 'selected';} ?>>MAIN SL 03:15 PM</option>
                                      <option value="msl_org8" <?php if (in_array('msl_org8', $parray)) {echo 'selected';} ?>>MAIN SL 04:15 PM</option>
                                      <option value="msl_org9" <?php if (in_array('msl_org9', $parray)) {echo 'selected';} ?>>MAIN SL 05:15 PM</option>
									  <option value="msl_org10" <?php if (in_array('msl_org10', $parray)) {echo 'selected';} ?>>MAIN SL 06:15 PM</option>
                                      <option value="msl_org11" <?php if (in_array('msl_org11', $parray)) {echo 'selected';} ?>>MAIN SL 07:15 PM</option>
                                      <option value="msl_org12" <?php if (in_array('msl_org12', $parray)) {echo 'selected';} ?>>MAIN SL 08:15 PM</option>
									  
									  
									  
<option value="kalyan_star_line1" <?php if (in_array('kalyan_star_line1', $parray)) {echo 'selected';} ?>>KALYAN SL 09:15 AM</option>
<option value="kalyan_star_line2" <?php if (in_array('kalyan_star_line2', $parray)) {echo 'selected';} ?>>KALYAN SL 10:15 AM</option>
<option value="kalyan_star_line3" <?php if (in_array('kalyan_star_line3', $parray)) {echo 'selected';} ?>>KALYAN SL 11:15 AM</option>
<option value="kalyan_star_line4" <?php if (in_array('kalyan_star_line4', $parray)) {echo 'selected';} ?>>KALYAN SL 12:15 PM</option>
<option value="kalyan_star_line5" <?php if (in_array('kalyan_star_line5', $parray)) {echo 'selected';} ?>>KALYAN SL 01:15 PM</option>
<option value="kalyan_star_line6" <?php if (in_array('kalyan_star_line6', $parray)) {echo 'selected';} ?>>KALYAN SL 02:15 PM</option>
<option value="kalyan_star_line7" <?php if (in_array('kalyan_star_line7', $parray)) {echo 'selected';} ?>>KALYAN SL 03:15 PM</option>
<option value="kalyan_star_line8" <?php if (in_array('kalyan_star_line8', $parray)) {echo 'selected';} ?>>KALYAN SL 04:15 PM</option>
<option value="kalyan_star_line9" <?php if (in_array('kalyan_star_line9', $parray)) {echo 'selected';} ?>>KALYAN SL 05:15 PM</option>
<option value="kalyan_star_line10" <?php if (in_array('kalyan_star_line10', $parray)) {echo 'selected';} ?>>KALYAN SL 06:15 PM</option>
<option value="kalyan_star_line11" <?php if (in_array('kalyan_star_line11', $parray)) {echo 'selected';} ?>>KALYAN SL 07:15 PM</option>
<option value="kalyan_star_line12" <?php if (in_array('kalyan_star_line12', $parray)) {echo 'selected';} ?>>KALYAN SL 08:15 PM</option>


<option value="crr_star_line1" <?php if (in_array('crr_star_line1', $parray)) {echo 'selected';} ?>>CRR SL 09:15 AM</option>
<option value="crr_star_line2" <?php if (in_array('crr_star_line2', $parray)) {echo 'selected';} ?>>CRR SL 10:15 AM</option>
<option value="crr_star_line3" <?php if (in_array('crr_star_line3', $parray)) {echo 'selected';} ?>>CRR SL 11:15 AM</option>
<option value="crr_star_line4" <?php if (in_array('crr_star_line4', $parray)) {echo 'selected';} ?>>CRR SL 12:15 PM</option>
<option value="crr_star_line5" <?php if (in_array('crr_star_line5', $parray)) {echo 'selected';} ?>>CRR SL 01:15 PM</option>
<option value="crr_star_line6" <?php if (in_array('crr_star_line6', $parray)) {echo 'selected';} ?>>CRR SL 02:15 PM</option>
<option value="crr_star_line7" <?php if (in_array('crr_star_line7', $parray)) {echo 'selected';} ?>>CRR SL 03:15 PM</option>
<option value="crr_star_line8" <?php if (in_array('crr_star_line8', $parray)) {echo 'selected';} ?>>CRR SL 04:15 PM</option>
<option value="crr_star_line9" <?php if (in_array('crr_star_line9', $parray)) {echo 'selected';} ?>>CRR SL 05:15 PM</option>
<option value="crr_star_line10" <?php if (in_array('crr_star_line10', $parray)) {echo 'selected';} ?>>CRR SL 06:15 PM</option>
<option value="crr_star_line11" <?php if (in_array('crr_star_line11', $parray)) {echo 'selected';} ?>>CRR SL 07:15 PM</option>
<option value="crr_star_line12" <?php if (in_array('crr_star_line12', $parray)) {echo 'selected';} ?>>CRR SL 08:15 PM</option>

<option value="silver_star_line1" <?php if (in_array('silver_star_line1', $parray)) {echo 'selected';} ?>>SILVER SL 09:15 AM</option>
<option value="silver_star_line2" <?php if (in_array('silver_star_line2', $parray)) {echo 'selected';} ?>>SILVER SL 10:15 AM</option>
<option value="silver_star_line3" <?php if (in_array('silver_star_line3', $parray)) {echo 'selected';} ?>>SILVER SL 11:15 AM</option>
<option value="silver_star_line4" <?php if (in_array('silver_star_line4', $parray)) {echo 'selected';} ?>>SILVER SL 12:15 PM</option>
<option value="silver_star_line5" <?php if (in_array('silver_star_line5', $parray)) {echo 'selected';} ?>>SILVER SL 01:15 PM</option>
<option value="silver_star_line6" <?php if (in_array('silver_star_line6', $parray)) {echo 'selected';} ?>>SILVER SL 02:15 PM</option>
<option value="silver_star_line7" <?php if (in_array('silver_star_line7', $parray)) {echo 'selected';} ?>>SILVER SL 03:15 PM</option>
<option value="silver_star_line8" <?php if (in_array('silver_star_line8', $parray)) {echo 'selected';} ?>>SILVER SL 04:15 PM</option>
<option value="silver_star_line9" <?php if (in_array('silver_star_line9', $parray)) {echo 'selected';} ?>>SILVER SL 05:15 PM</option>
<option value="silver_star_line10" <?php if (in_array('silver_star_line10', $parray)) {echo 'selected';} ?>>SILVER SL 06:15 PM</option>
<option value="silver_star_line11" <?php if (in_array('silver_star_line11', $parray)) {echo 'selected';} ?>>SILVER SL 07:15 PM</option>
<option value="silver_star_line12" <?php if (in_array('silver_star_line12', $parray)) {echo 'selected';} ?>>SILVER SL 08:15 PM</option>

<option value="sai_star_line1" <?php if (in_array('sai_star_line1', $parray)) {echo 'selected';} ?>>SAI SL 09:15 AM</option>
<option value="sai_star_line2" <?php if (in_array('sai_star_line2', $parray)) {echo 'selected';} ?>>SAI SL 10:15 AM</option>
<option value="sai_star_line3" <?php if (in_array('sai_star_line3', $parray)) {echo 'selected';} ?>>SAI SL 11:15 AM</option>
<option value="sai_star_line4" <?php if (in_array('sai_star_line4', $parray)) {echo 'selected';} ?>>SAI SL 12:15 PM</option>
<option value="sai_star_line5" <?php if (in_array('sai_star_line5', $parray)) {echo 'selected';} ?>>SAI SL 01:15 PM</option>
<option value="sai_star_line6" <?php if (in_array('sai_star_line6', $parray)) {echo 'selected';} ?>>SAI SL 02:15 PM</option>
<option value="sai_star_line7" <?php if (in_array('sai_star_line7', $parray)) {echo 'selected';} ?>>SAI SL 03:15 PM</option>
<option value="sai_star_line8" <?php if (in_array('sai_star_line8', $parray)) {echo 'selected';} ?>>SAI SL 04:15 PM</option>
<option value="sai_star_line9" <?php if (in_array('sai_star_line9', $parray)) {echo 'selected';} ?>>SAI SL 05:15 PM</option>
<option value="sai_star_line10" <?php if (in_array('sai_star_line10', $parray)) {echo 'selected';} ?>>SAI SL 06:15 PM</option>
<option value="sai_star_line11" <?php if (in_array('sai_star_line11', $parray)) {echo 'selected';} ?>>SAI SL 07:15 PM</option>
<option value="sai_star_line12" <?php if (in_array('sai_star_line12', $parray)) {echo 'selected';} ?>>SAI SL 08:15 PM</option>

<option value="goldline_star_line1" <?php if (in_array('goldline_star_line1', $parray)) {echo 'selected';} ?>>GOLDLINE SL 09:15 AM</option>
<option value="goldline_star_line2" <?php if (in_array('goldline_star_line2', $parray)) {echo 'selected';} ?>>GOLDLINE SL 10:15 AM</option>
<option value="goldline_star_line3" <?php if (in_array('goldline_star_line3', $parray)) {echo 'selected';} ?>>GOLDLINE SL 11:15 AM</option>
<option value="goldline_star_line4" <?php if (in_array('goldline_star_line4', $parray)) {echo 'selected';} ?>>GOLDLINE SL 12:15 PM</option>
<option value="goldline_star_line5" <?php if (in_array('goldline_star_line5', $parray)) {echo 'selected';} ?>>GOLDLINE SL 01:15 PM</option>
<option value="goldline_star_line6" <?php if (in_array('goldline_star_line6', $parray)) {echo 'selected';} ?>>GOLDLINE SL 02:15 PM</option>
<option value="goldline_star_line7" <?php if (in_array('goldline_star_line7', $parray)) {echo 'selected';} ?>>GOLDLINE SL 03:15 PM</option>
<option value="goldline_star_line8" <?php if (in_array('goldline_star_line8', $parray)) {echo 'selected';} ?>>GOLDLINE SL 04:15 PM</option>
<option value="goldline_star_line9" <?php if (in_array('goldline_star_line9', $parray)) {echo 'selected';} ?>>GOLDLINE SL 05:15 PM</option>
<option value="goldline_star_line10" <?php if (in_array('goldline_star_line10', $parray)) {echo 'selected';} ?>>GOLDLINE SL 06:15 PM</option>
<option value="goldline_star_line11" <?php if (in_array('goldline_star_line11', $parray)) {echo 'selected';} ?>>GOLDLINE SL 07:15 PM</option>
<option value="goldline_star_line12" <?php if (in_array('goldline_star_line12', $parray)) {echo 'selected';} ?>>GOLDLINE SL 08:15 PM</option>

<option value="sridevi_star_line11" <?php if (in_array('sridevi_star_line11', $parray)) {echo 'selected';} ?>>SRIDEVI SL 09:15 AM</option>
<option value="sridevi_star_line12" <?php if (in_array('sridevi_star_line12', $parray)) {echo 'selected';} ?>>SRIDEVI SL 10:15 AM</option>
<option value="sridevi_star_line13" <?php if (in_array('sridevi_star_line13', $parray)) {echo 'selected';} ?>>SRIDEVI SL 11:15 AM</option>
<option value="sridevi_star_line14" <?php if (in_array('sridevi_star_line14', $parray)) {echo 'selected';} ?>>SRIDEVI SL 12:15 PM</option>
<option value="sridevi_star_line15" <?php if (in_array('sridevi_star_line15', $parray)) {echo 'selected';} ?>>SRIDEVI SL 01:15 PM</option>
<option value="sridevi_star_line16" <?php if (in_array('sridevi_star_line16', $parray)) {echo 'selected';} ?>>SRIDEVI SL 02:15 PM</option>
<option value="sridevi_star_line17" <?php if (in_array('sridevi_star_line17', $parray)) {echo 'selected';} ?>>SRIDEVI SL 03:15 PM</option>
<option value="sridevi_star_line18" <?php if (in_array('sridevi_star_line18', $parray)) {echo 'selected';} ?>>SRIDEVI SL 04:15 PM</option>
<option value="sridevi_star_line19" <?php if (in_array('sridevi_star_line19', $parray)) {echo 'selected';} ?>>SRIDEVI SL 05:15 PM</option>
<option value="sridevi_star_line110" <?php if (in_array('sridevi_star_line110', $parray)) {echo 'selected';} ?>>SRIDEVI SL 06:15 PM</option>
<option value="sridevi_star_line111" <?php if (in_array('sridevi_star_line111', $parray)) {echo 'selected';} ?>>SRIDEVI SL 07:15 PM</option>
<option value="sridevi_star_line112" <?php if (in_array('sridevi_star_line112', $parray)) {echo 'selected';} ?>>SRIDEVI SL 08:15 PM</option>

                                    </select>
                              </div>

                          </div>
                          <div class="form-group row">
						      <label class="control-label col-md-3">Show Admin Mobile Number?</label>
						      <div class="col-md-5">
						          <div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
<?php 
$result_set=mysqli_query($con,$sql_query);
$fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
?>
										<input type="checkbox" name="numPermission" <?php if ($fetched_row['number_permissions']=='1') {echo 'checked';} ?> />
									</div>
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