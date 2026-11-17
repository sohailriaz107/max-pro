<?php 
session_start();
$pageName='update-results';
include '../config.php';
require('sessions.php');
require('functions.php');

$file='../txtdb/xzavierJoaquinosia.txt';
$lines= file($file);
$today=date('d-m-Y');


if (isset($_POST['updateResult'])) {
	
	  $id = strip_tags($_GET['id']);
  $order=strip_tags($_POST['order']);
  $gName=$_POST['gName'];
  
  
  $mPermissions= explode(',',$_SESSION['m_permissions']);
  if (in_array($gName, $mPermissions)) {
  //do nothing
  }else{
	echo 'You Dont have Permissions.';
	exit;
  }
  
  $gresult_pattern ='/^\d{3}-\d{1}(-\d{3})?$|^\d{3}-\d{2}-\d{3}$/';
	if(preg_match($gresult_pattern, $_POST['gResult'])) {
		//do nothing result is ok.
	} else {
		echo 'Invalid Entry';
		exit;
	}
	
if(preg_match("/^[A-Za-z0-9-*.\[\] ]+$/", $gName)) {
		//do nothing result is ok.
	} else {
		echo 'Invalid Entry';
		exit;
	}
	
	
  $gResult=htmlspecialchars(mysqli_real_escape_string($con,$_POST['gResult']));
  $timeOpen=htmlspecialchars(mysqli_real_escape_string($con,$_POST['timeOpen']));
  $timeClose=htmlspecialchars(mysqli_real_escape_string($con,$_POST['timeClose']));
  $gBgcolor=htmlspecialchars(mysqli_real_escape_string($con,$_POST['gBgcolor']));
  $gFontcolor=htmlspecialchars(mysqli_real_escape_string($con,$_POST['gFontcolor']));
  $gLinecolor=htmlspecialchars(mysqli_real_escape_string($con,$_POST['gLinecolor']));
  $frequency=htmlspecialchars(mysqli_real_escape_string($con,$_POST['frequency']));
  $liveTimeBefore=htmlspecialchars(mysqli_real_escape_string($con,$_POST['liveTimeBefore']));
  $liveTimeAfter=htmlspecialchars(mysqli_real_escape_string($con,$_POST['liveTimeAfter']));
  $updateDate=htmlspecialchars(mysqli_real_escape_string($con,$_POST['updateDate']));
  $status=htmlspecialchars(mysqli_real_escape_string($con,$_POST['status']));
  $expiryDate=htmlspecialchars(mysqli_real_escape_string($con,$_POST['expiryDate']));
  $jodiLink=htmlspecialchars(mysqli_real_escape_string($con,$_POST['jodiLink']));
  $panelLink=htmlspecialchars(mysqli_real_escape_string($con,$_POST['panelLink']));
  
  $frequency = mysqli_real_escape_string($con,$frequency);
  $data=$order.'|'.$gName.'|'.str_replace("|", "", $gResult).'|'.$timeOpen.'|'.$timeClose.'|'.$gBgcolor.'|'.$gFontcolor.'|'.$gLinecolor.'|'.$frequency.'|'.$liveTimeBefore.'|'.$liveTimeAfter.'|'.$updateDate.'|'.$status.'|'.$expiryDate.'|'.$jodiLink.'|'.$panelLink.'|'.time().'|';
foreach($lines as $index => $line){
   if($index == $id){
       $lines[$index] = $data . "\n";
   }

}




$content = implode($lines);
$insert=file_put_contents('../txtdb/xzavierJoaquinosia.txt', $content);
  if ($insert) {
    $re = '/^(\d{3}-\d{2}-\d{3})$/m';
    $updateDate=date('d/m/Y');
    $msgUpdate='<div class="alert alert-success">Result Update Successfully! <b>Redirecting...</b></div>';
    header("refresh:3;url=../index.php");
    if (preg_match_all($re, $gResult, $gResult)) {
      $num_test = htmlspecialchars(mysqli_real_escape_string($con,$_POST['gResult']));
      $num=(string)$num_test;
      $a=$num[0];
      $b=$num[1];
      $c=$num[2];
      $d=$num[4].$num[5];
      $e=$num[7];
      $f=$num[8];
      $g=$num[9];
      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
      if (preg_match_all($detectColor,$d)) {
        $color='red';
      }
      else{
        $color='black';
      }


      if ($frequency==7) {
        
        
        
      }elseif ($frequency==6) {
		  
      }elseif ($frequency==2) {
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+4 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
          if (date('A')=='AM') {
            $updateDate=date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
          }
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+1 day", time());
          $toDate=date('d/m/Y',$addDays);
        }

        $holidaySelect=mysqli_query($con,"SELECT * FROM `panel` WHERE `gameName`='$gName' ORDER BY id DESC LIMIT 1");
        $num_rows=mysqli_num_rows($holidaySelect);
        if($num_rows > 0){
            // start logic for inserting stars in holidays
            $fetched_row=mysqli_fetch_array($holidaySelect,MYSQLI_ASSOC);
            $date1 = strtotime(str_replace('/', '-', $fetched_row['updateDate'])); 
            $date2 = strtotime(str_replace('/', '-', $updateDate));
            $LastUpdateDate=str_replace('/', '-', $fetched_row['updateDate']);
            $count = 0;
            while(date('Y-m-d', $date1) < date('Y-m-d', $date2)){
              $count += date('N', $date1) < 8 ? 1 : 0; // 8 stands for complete week, 7 means without sunday, 6 means without saturday & sunday, and soo on...
              $date1 = strtotime("+1 day", $date1);
            }
            if ($count==0) {
              $dateDiff = 0;
            }
            else{
              $dateDiff = $count-1;
              
                for ($i=1; $i <= $dateDiff; $i++) {
                  $incDate = date("d-m-Y", str_replace('/', '-', strtotime("$LastUpdateDate +".$i." day")));
                  $timestamp = strtotime($incDate);
                  if (date('D', $timestamp)=='Sat') {
                    $weekStart=$incDate;
                    $weekEnd=date("d-m-Y", strtotime("$weekStart +1 day"));
                    $wkrs=str_replace('-', '/', $weekStart);// week start replace string from - to /
                    $wkre=str_replace('-', '/', $weekEnd);// week end replace string from - to /
                    $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$wkrs','$wkre','$incDate')");
                  }
                  else{
                    $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','','','$incDate')");
                  }
                
              } //end for loop
            } // start logic for inserting stars in holidays
            } // end condition if game already added
            else{
              $dateOne = date( 'd-m-Y', strtotime( 'saturday this week' ) );
              $date1 = str_replace('/', '-', $dateOne); 
              $date2 = str_replace('/', '-', $updateDate);
              $calcDiff = dateDiffInDays($date1, $date2); 
              if ($calcDiff==0) {
                $dateDiff = 0;
              }
              else{
                $dateDiff = $calcDiff-1;
                
                  for ($i=0; $i <= $dateDiff; $i++) {
                    $incDate = date("d-m-Y", str_replace('/', '-', strtotime("$date1 +".$i." day")));
                    $timestamp = strtotime($incDate);
                    if (date('D', $timestamp)=='Sat') {
                      $weekStart=$incDate;
                      $weekEnd=date("d-m-Y", strtotime("$weekStart +1 day"));
                      $wkrs=str_replace('-', '/', $weekStart);// week start replace string from - to /
                      $wkre=str_replace('-', '/', $weekEnd);// week end replace string from - to /
                      $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$wkrs','$wkre','$incDate')");
                    }
                    else{
                      $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','','','$incDate')");
                    }
                  
                } //end for loop
              } // start logic for inserting stars in holidays
            } // end else condition if game already addedd

        $select=mysqli_query($con,"SELECT * FROM `panel` WHERE `updateDate`='$updateDate' AND gameName='$gName'");
        $num_rows=mysqli_num_rows($select);
        if($num_rows > 0){
          if (strtotime(date('h:i'))<strtotime('23:59')) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
                elseif (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM') && strtotime(date('h:i'))<strtotime('03:00')) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
        }
        else{
            if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
              if (date('D')=='Sun') {
                if (strtotime(date('h:i'))<strtotime('03:00')) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
                else{
                $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
                  }
              }
              else{
                $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$updateDate')");
              }
            }
            elseif (date('D')=='Sat') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$updateDate')");
          }
          $msg='<div class="alert alert-success">Chart Also Updated Successfully</div>';
        }
      }
      $msg.=$msgUpdate;
    } //end if preg_match_all condition
    elseif ($_POST['gResult']=='✪') {
      

    }
    else{
      $msg='<div class="alert alert-success">Result Update Successfully! <b>Redirecting...</b></div>';
      header("refresh:3;url=https://dpbosss.com");
    }
  }
  else{
   $msg='Something Wrong!'; 
  }
  
  
			
			
}
else{
  $msg='<h2>Update Result</h2>';
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
  <meta http-equiv="refresh" content="30">

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

        <?php 
if ($counter>=1) {
  foreach ($expiryLines as $line_num => $line) {

  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate, $jodiLink, $panelLink)=explode("|", $line);
  if (in_array($gameName, $expiryPermissions)) {
    // start date difference logic

            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 
      //end date difference logic
        if ($calcDiff>=1 && $calcDiff<=5) {
          if (strtotime($date1)<strtotime($date2)) {
?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<?php }else{ ?>
<div class="alert alert-info alert-dismissible" role="alert"><strong>You have <?php echo $calcDiff ?> days left to renew <?php echo $gameName ?></strong></div>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
      // Sample Toastr Notification
      setTimeout(function()
      {
        var opts = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "16000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
    
        toastr.warning("You have <?php echo $calcDiff; if ($calcDiff>1) {echo ' days';} else{echo ' day';} ?> left to renew <?php echo $gameName ?>", "Urgent Notice", opts);
      }, 4000);
    
    });
    
    </script>
<?php }} ?>
                    
<?php
}
}
elseif ($calcDiff==0) {
?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<?php }else{ ?>
<div class="alert alert-danger alert-dismissible" role="alert"><strong>Your plan has expired for <?php echo $gameName ?>. Please Renew it as soon as possible.</strong></div>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
      // Sample Toastr Notification
      setTimeout(function()
      {
        var opts = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "15000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
    
        toastr.error("Your plan has expired for <?php echo $gameName ?>. Please Renew it as soon as possible.", "Plan Expired", opts);
      }, 3000);
    
    });
    
    </script>
<?php }} ?>
                    
<?php
}// end else if differenece is zero
else{

}

}
}
}
?>    



<?php 
$query=mysqli_query($con,"SELECT id, permissions, number_permissions, username FROM admins WHERE id=".$_SESSION['id']);
while($result=mysqli_fetch_array($query))
{
  $permissions = explode(',',$result['permissions']);
  $number_permissions = $result['number_permissions'];
}
if ($number_permissions==1) {
  $selectNum=mysqli_query($con,"SELECT mobile FROM admins WHERE username='vk'");
  $fetchNum=mysqli_fetch_array($selectNum);
  if ($_SESSION['username']!='vk') {
    ?>
<div class="alert alert-info">DPBOSS Admin Direct Line - <span class="blink" style="font-size: 18px;"><a href="https://api.whatsapp.com/send?phone=+91<?php echo $fetchNum['mobile']; ?>&text=I%27m%20coming%20from%20your%20website%20chat%20link"><img src="assets/images/whatsapp.png" width="30" style="vertical-align: middle;"><b> <?php echo $fetchNum['mobile']?></a></b></div>
    <?php
  }
}
foreach ($lines as $line_num => $line) {
  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate, $jodiLink, $panelLink)=explode("|", $line);
  if (in_array($gameName, $permissions)) {

$date1 = str_replace('/', '-', date('Y-m-d')); 
$date2 = str_replace('/', '-', $expiryDate);
$calcDiff = dateDiffInDays($date1, $date2); 
if ($_SESSION['username']=='vk') {
  ?>
            <form role="form" class="form-horizontal" action="?id=<?php echo $line_num; ?>" method="post">
      
              <div class="form-group">
                <label class="col-sm-3"><?php echo $gameName ?></label>
                <input type="hidden" name="gName" class="form-control" id="inlineFormInput" value="<?php echo $gameName ?>">
                
                <div class="col-sm-5">
                  <input type="text" name="gResult" class="form-control" value="<?php echo $gameResult ?>" required maxlength="10">
                  <input type="hidden" name="order" class="form-control" value="<?php echo $order ?>">
          <input type="hidden" name="timeOpen" class="form-control" value="<?php echo $timeOpen ?>">
          <input type="hidden" name="timeClose" class="form-control" value="<?php echo $timeClose ?>">
          <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo $gameBgcolor ?>">
          <input type="hidden" name="gFontcolor" value="<?php echo $gameFontcolor ?>">
          <input type="hidden" name="gLinecolor" value="<?php echo $gameLinecolor ?>">
          <input type="hidden" name="frequency" value="<?php echo $frequency ?>">
          <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php if($liveTimeBefore==''){$liveTimeBefore='5';} echo $liveTimeBefore; ?>">
          <input type="hidden" name="updateDate" class="form-control" value="<?php 
            if(strpos(date('h:i A', strtotime($timeOpen)), 'PM') && strpos(date('h:i A', strtotime($timeClose)), 'AM')){
              if(date('H') >= 00 && date('H') <= 03){
              echo date("d-m-Y", strtotime('-1 days'));
              }
              else{
                echo $today;  
              }
            }
            else{
              echo $today;
            }
          ?>">
          <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php if($liveTimeAfter==''){$liveTimeAfter='25';} echo $liveTimeAfter; ?>">
          <input type="hidden" name="status" class="form-control" value="<?php if($status=='0'){echo '0';} else{echo '1';} ?>">
          <input type="hidden" name="expiryDate" class="form-control" value="<?php echo $expiryDate; ?>">
          <input type="hidden" name="jodiLink" class="form-control" value="<?php echo $jodiLink; ?>">
          <input type="hidden" name="panelLink" class="form-control" value="<?php echo $panelLink; ?>">
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-success" name="updateResult" style="margin-top: 5px;"><b>Update Result</b></button>
                </div>
              </div>
            </form>
  <?php
}//end if check user is admin

else{
  if (strtotime($date1)<=strtotime($date2)) {
    // if ($number_permissions==1) {
    //   echo 'ok';
    // }
  ?>
            <form role="form" class="form-horizontal" action="?id=<?php echo $line_num; ?>" method="post">
      
              <div class="form-group">
                <label class="col-sm-3"><?php echo $gameName ?></label>
                <input type="hidden" name="gName" class="form-control" id="inlineFormInput" value="<?php echo $gameName ?>">
                
                <div class="col-sm-5">
                  <input type="text" name="gResult" class="form-control" value="<?php echo $gameResult ?>" required maxlength="10">
                  <input type="hidden" name="order" class="form-control" value="<?php echo $order ?>">
          <input type="hidden" name="timeOpen" class="form-control" value="<?php echo $timeOpen ?>">
          <input type="hidden" name="timeClose" class="form-control" value="<?php echo $timeClose ?>">
          <input type="hidden" name="gBgcolor" class="form-control" value="<?php echo $gameBgcolor ?>">
          <input type="hidden" name="gFontcolor" value="<?php echo $gameFontcolor ?>">
          <input type="hidden" name="gLinecolor" value="<?php echo $gameLinecolor ?>">
          <input type="hidden" name="frequency" value="<?php echo $frequency ?>">
          <input type="hidden" name="liveTimeBefore" class="form-control" value="<?php if($liveTimeBefore==''){$liveTimeBefore='5';} echo $liveTimeBefore; ?>">
          <input type="hidden" name="updateDate" class="form-control" value="<?php 
            if(strpos(date('h:i A', strtotime($timeOpen)), 'PM') && strpos(date('h:i A', strtotime($timeClose)), 'AM')){
              if(date('H') >= 00 && date('H') <= 03){
              echo date("d-m-Y", strtotime('-1 days'));
              }
              else{
                echo $today;  
              }
            }
            else{
              echo $today;
            }
          ?>">
          <input type="hidden" name="liveTimeAfter" class="form-control" value="<?php if($liveTimeAfter==''){$liveTimeAfter='25';} echo $liveTimeAfter; ?>">
          <input type="hidden" name="status" class="form-control" value="<?php if($status=='0'){echo '0';} else{echo '1';} ?>">
          <input type="hidden" name="expiryDate" class="form-control" value="<?php echo $expiryDate; ?>">
          <input type="hidden" name="jodiLink" class="form-control" value="<?php echo $jodiLink; ?>">
          <input type="hidden" name="panelLink" class="form-control" value="<?php echo $panelLink; ?>">
                </div>
                <div class="col-sm-3">
                  <button type="submit" class="btn btn-success" name="updateResult" style="margin-top: 5px;"><b>Update Result</b></button>
                </div>
              </div>
            </form>
  <?php
}//end if expiry date is smaller than today
else{
  echo '<div class="alert alert-danger">'.$gameName.' has been expired on '.$expiryDate.'. Please contact Admin to update result of '.$gameName.'</div>';
}
}

}
}
?>

			
			</div>
		</div>
		
		
		<?php include 'footer.php' ?>
<div style="position: fixed; right: 10px; bottom: 30px">
<form accept="" method="post">
  <input type="submit" name="clear_cache" value="Clear Cache">
</form>
</div>
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
  <script src="assets/js/toastr.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>