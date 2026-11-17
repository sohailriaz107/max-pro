<?php 
session_start();
$pageName='update-results';
include '../config.php';
require('sessions.php');
require('functions.php');

$file='../txtdb/xzavierJoaquinosia.txt';
$lines= file($file);
$today=date('d-m-Y');
// main star line
$main_slfile='../txtdb/main_star_line_results.txt';
$main_sllines= file($main_slfile);

// cm star line
$cm_slfile='../txtdb/cm_star_line_results.txt';
$cm_sllines= file($cm_slfile);

// mr star line
$mr_slfile='../txtdb/mr_star_line_results.txt';
$mr_sllines= file($mr_slfile);

// main star line
$main_sl_org_file='../txtdb/main_original_sl.txt';
$msl_org_lines= file($main_sl_org_file);

// kalyan petti live star line
$kpl_slfile='../txtdb/kpl_star_line_results.txt';
$kpl_sllines= file($kpl_slfile);



$kalyan_starline_file='../txtdb/kalyan_star_line.txt';
$kalyan_starline_lines= file($main_sl_org_file);

$crr_starline_file='../txtdb/crr_star_line.txt';
$crr_starline_lines= file($main_sl_org_file);

$silver_starline_file='../txtdb/silver_line_star_line.txt';
$silver_starline_lines= file($main_sl_org_file);

$sai_starline_file='../txtdb/sai_star_line.txt';
$sai_starline_lines= file($main_sl_org_file);

$gold_starline_file='../txtdb/goldline_star_line.txt';
$gold_starline_lines= file($main_sl_org_file);

$sridevi_starline_file='../txtdb/sridevi_star_line.txt';
$sridevi_starline_lines= file($main_sl_org_file);


if (isset($_POST['updateResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $order=htmlspecialchars(mysqli_real_escape_string($con,$_POST['order']));
  $gName=htmlspecialchars(mysqli_real_escape_string($con,$_POST['gName']));
  
  
  

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim($ipList[0]); // Take the first IP from the list
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

  
$current_time = date("H:i");

// Session and Post Values
$log_username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$log_user_id  = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$log_gResult  = isset($_POST['gResult']) ? $_POST['gResult'] : '';
$log_gName    = isset($_POST['gName']) ? $_POST['gName'] : '';

// Log file path
$log_file = "../txtdb/game_logs_browser.txt";  // create folder "logs" if not exists

// Prepare log line
$log_line = $log_gName . "|" . $log_gResult . "|" . $log_username . "|" . $log_user_id . "|" . $ip . "|" . $current_time . PHP_EOL;

// Save the log
file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);



  if(0){
	if ($gName == "DIAMOND" && $current_time >= "13:35" && $current_time <= "14:50") {
		echo 'you missed the time. You have to put result on time.';
		exit;
	}elseif($gName == "DIAMOND" && $current_time >= "15:05" && $current_time <= "18:00"){
		echo 'you missed the time. You have to put result on time.';
		exit;
	}
	
}
  
  
  
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
  
  
  if(strlen($gResult) == 10){
		$num = str_replace("-", '', $gResult);
		$sum=0;
		$rem=0;
		for($i=0;$i<=7;$i++)
		{
			$rem = $num % 10;
			$sum = $sum + $rem;
			$num = $num / 10;
		}
			
			$finalAnk = substr($sum,-1);
		}else{
			
			$finalAnk=htmlspecialchars(mysqli_real_escape_string($con,$_POST['finalAnk']));
		}
		
		
  $data=$order.'|'.$gName.'|'.str_replace("|", "", $gResult).'|'.$timeOpen.'|'.$timeClose.'|'.$gBgcolor.'|'.$gFontcolor.'|'.$gLinecolor.'|'.$frequency.'|'.$liveTimeBefore.'|'.$liveTimeAfter.'|'.$updateDate.'|'.$status.'|'.$expiryDate.'|'.$jodiLink.'|'.$panelLink.'|'.time().'|'.$finalAnk.'|';
foreach($lines as $index => $line){
   if($index == $id){
       $lines[$index] = $data . "\n";
   }

}


$gResult_for_api = $gResult;
$gName_for_api = $gName;

$dateTime = strtotime(date('d-m-Y H:i'));
if(date('H') >= 00 && date('H') <= 03){
  $dateTime = strtotime('+1 days',strtotime(date('d-m-Y H:i')));
}
if (date('D')=='Sun') {
  if ($gName=='TIME BAZAR') {
  $timeOpen = date('13:00');
  $timeClose = date('15:14');
  }
  if ($gName=='MILAN DAY') {
  $timeOpen = date('14:14');
  $timeClose = date('16:14');
  }
  if ($gName=='MAIN DAY') {
  $timeOpen = date('15:30');
  $timeClose = date('17:30');
  }
  if ($gName=='SUPER MATKA') {
  $timeOpen = date('13:30');
  $timeClose = date('15:00');
  }
}




$content = implode($lines);
$insert=file_put_contents('../txtdb/xzavierJoaquinosia.txt', $content);
  if ($insert) {
    $re = '/^(\d{3}-\d{2}-\d{3})$/m';
    $updateDate=date('d/m/Y');
    $msgUpdate='<div class="alert alert-success">Result Update Successfully</div>';
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
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+6 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
          if (date('A')=='AM') {
            $updateDate=date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
          }
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+6 day", time());
          $toDate=date('d/m/Y',$addDays);
		  
		  if(date('H') >= 00 && date('H') <= 03){
			  $updateDate = date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
			}
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
              $count += date('N', $date1) < 8 ? 1 : 0; // 8 stands for complete week, 7 means without sunday, 6 means without saturday, and soo on...
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
                  if (date('D', $timestamp)=='Mon') {
                    $weekStart=$incDate;
                    $weekEnd=date("d-m-Y", strtotime("$weekStart +6 day"));
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
              $dateOne = date( 'd-m-Y', strtotime( 'monday this week' ) );
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
                    if (date('D', $timestamp)=='Mon') {
                      $weekStart=$incDate;
                      $weekEnd=date("d-m-Y", strtotime("$weekStart +6 day"));
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
        
        
        

        $select=mysqli_query($con,"SELECT * FROM `panel` WHERE `updateDate`='$updateDate' AND gameName='$gName' ORDER BY id");
        $num_rows=mysqli_num_rows($select);
        if($num_rows > 0){
          if (strtotime(date('h:i'))<strtotime('23:59')) {
            $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
            if ($panelChart) {
              $msg='<div class="alert alert-success">Chart Updated</div>';
            }
          }
          elseif (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM') && date('H') < 3) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
        }
        else{
            if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
              if (date('D')=='Tue') {
                if (date('H') < 3) {
                  $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
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
            elseif (date('D')=='Mon') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$updateDate')");
          }
          $msg='<div class="alert alert-success">Chart Also Updated Successfully</div>';
        }
        
      }elseif ($frequency==6) {
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+5 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
          if (date('A')=='AM') {
            $updateDate=date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
          }
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+5 day", time());
          $toDate=date('d/m/Y',$addDays);
		  
		  if(date('H') >= 00 && date('H') <= 03){
			  $updateDate = date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
			}
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
              $count += date('N', $date1) < 8 ? 1 : 0; // 8 stands for complete week, 7 means without sunday, 6 means without saturday, and soo on...
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
                  if (date('D', $timestamp)=='Sun') {
                    # code...
                  }
                  elseif (date('D', $timestamp)=='Mon') {
                    $weekStart=$incDate;
                    $weekEnd=date("d-m-Y", strtotime("$weekStart +5 day"));
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
              $dateOne = date( 'd-m-Y', strtotime( 'monday this week' ) );
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
                    if (date('D', $timestamp)=='Mon') {
                      $weekStart=$incDate;
                      $weekEnd=date("d-m-Y", strtotime("$weekStart +5 day"));
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
              $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
              if ($panelChart) {
                $msg='<div class="alert alert-success">Chart Updated</div>';
              }
            }
            elseif (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM') && date('H') < 3) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
        }
        else{
            if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
              if (date('D')=='Tue') {
                if (date('H') < 3) {
                  $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
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
            elseif (date('D')=='Mon') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$updateDate')");
          }
          $msg='<div class="alert alert-success">Chart Also Updated Successfully</div>';
        }
      }elseif ($frequency==5) {
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
          $addDays=strtotime("+4 day", time());
          $toDate=date('d/m/Y',$addDays);
		  
		  if(date('H') >= 00 && date('H') <= 03){
			  $updateDate = date('d-m-Y', strtotime('-1 day', strtotime(date('d-m-Y'))));
			}
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
                  if (date('D', $timestamp)=='Sun'||date('D', $timestamp)=='Sat') {
                    # code...
                  }
                  elseif (date('D', $timestamp)=='Mon') {
                    $weekStart=$incDate;
                    $weekEnd=date("d-m-Y", strtotime("$weekStart +4 day"));
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
              $dateOne = date( 'd-m-Y', strtotime( 'monday this week' ) );
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
                    if (date('D', $timestamp)=='Mon') {
                      $weekStart=$incDate;
                      $weekEnd=date("d-m-Y", strtotime("$weekStart +4 day"));
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
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
                elseif (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM') && date('H') < 3) {
                  $panelChart=mysqli_query($con,"UPDATE `panel` SET `a`='$a',`b`='$b',`c`='$c',`d`='$d',`e`='$e',`f`='$f',`g`='$g',`color`='$color' WHERE updateDate='$updateDate' AND gameName='$gName'");
                  if ($panelChart) {
                    $msg='<div class="alert alert-success">Chart Updated</div>';
                  }
                }
        }
        else{
            if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
              if (date('D')=='Tue') {
                if (date('H') < 3) {
                  $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','$a','$b','$c','$d','$e','$f','$g','$color','$frequency','$fromDate','$toDate','$updateDate')");
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
            elseif (date('D')=='Mon') {
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
      if ($frequency==7) {
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+6 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+6 day", time()); 
          $toDate=date('d/m/Y',$addDays);
        }

        $select=mysqli_query($con,"SELECT * FROM `panel` WHERE `updateDate`='$updateDate' AND gameName='$gName'");
        $num_rows=mysqli_num_rows($select);
        if($num_rows > 0){
          $msg='';
        }
        else{
            if (date('D')=='Mon') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$updateDate')");
          }
          $msg='';
        }
        
      }elseif ($frequency==6) {
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+5 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+5 day", time());
          $toDate=date('d/m/Y',$addDays);
        }

        $select=mysqli_query($con,"SELECT * FROM `panel` WHERE `updateDate`='$updateDate' AND gameName='$gName'");
        $num_rows=mysqli_num_rows($select);
        if($num_rows > 0){
          $msg='';
        }
        else{
            if (date('D')=='Mon') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$updateDate')");
          }
          $msg='';
        }
      }elseif ($frequency==5) {
        if (strpos(date('h:i A', strtotime($_POST['timeOpen'])), 'PM') && strpos(date('h:i A', strtotime($_POST['timeClose'])), 'AM')) {
          $nightGameDate=strtotime("-1 day", time());
          $fromDate=date('d/m/Y',$nightGameDate);
          $addDays=strtotime("+4 day", $nightGameDate);
          $toDate=date('d/m/Y',$addDays);
        }
        else{
          $fromDate=date('d/m/Y');
          $addDays=strtotime("+4 day", time());
          $toDate=date('d/m/Y',$addDays);
        }

        $select=mysqli_query($con,"SELECT * FROM `panel` WHERE `updateDate`='$updateDate' AND gameName='$gName'");
        $num_rows=mysqli_num_rows($select);
        if($num_rows > 0){
          $msg='';
        }
        else{
            if (date('D')=='Mon') {
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$fromDate','$toDate','$updateDate')");
          }
          else{
            $panelChart=mysqli_query($con,"INSERT INTO `panel`(`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `updateDate`) VALUES ('$gName','*','*','*','**','*','*','*','red','$frequency','$updateDate')");
          }
          $msg='';
        }
      }
      $msg.=$msgUpdate;
    }
    else{
      $msg='<div class="alert alert-success">Result Update Successfully</div>';
    }
  }
  else{
   $msg='Something Wrong!'; 
  }
  



if($gName =='SRIDEVI' || $gName =='SRIDEVI NIGHT' || $gName =='KALYAN NIGHT' || $gName =='SRIDEVI MORNING' || $gName =='MILAN MORNING' || $gName =='MADHUR DAY' || $gName =='MADHUR NIGHT'){

  $url = "https://onlyus.dpboss.direct/api/forum/result-webhook";

// Create an array with the data to send
$data = [
    "game_name" => $gName_for_api,
    "result" => $gResult_for_api
];

// Initialize cURL
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the request
$response = curl_exec($ch);

  if (curl_errno($ch)) {
    $error_msg = date("Y-m-d H:i:s") . " - cURL Error: " . curl_error($ch) . PHP_EOL;
    $fp = fopen('result_post_api_response.txt', 'a'); // Open file in append mode  
    fwrite($fp, $error_msg);
    fwrite($fp, "\n\n================\n\n");
    fclose($fp);

} else {
    $response_to_save = date("Y-m-d H:i:s") . " - " . $response . PHP_EOL;
    $fp = fopen('result_post_api_response.txt', 'a'); // Open file in append mode  
    fwrite($fp, $response_to_save);
    fwrite($fp, "\n\n================\n\n");
    fclose($fp);
}

// Close cURL
curl_close($ch);
  }

  
			
			
}elseif (isset($_POST['update_main_slResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'.$id];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($main_sllines as $main_slindex => $main_slline){
   if($main_slindex == $id){
       $main_sllines[$main_slindex] = $data . "\n";
   }

}

$content = implode($main_sllines);
$insert=file_put_contents('../txtdb/main_star_line_results.txt', $content);
if ($insert) {
$query = mysqli_query($con,"SELECT * FROM main_sl WHERE updateDate='$today'") or die(mysqli_error());
$num_rows=mysqli_num_rows($query);
$row = mysqli_fetch_array($query);
$day=date('l');
if($num_rows == 1){
  mysqli_query($con,"UPDATE `main_sl` SET `result".$id."`='".$data."' WHERE id='".$row['id']."'");
}
else{
  mysqli_query($con,"INSERT INTO `main_sl`(`result".$id."`, `updateDate`, `updateDay`) VALUES ('".$data."','$today','$day')");
}
$msg='<div class="alert alert-success">Result Update Successfully</div>';
}
else{
 $msg='Something Wrong!'; 
}
}elseif (isset($_POST['update_cm_slResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'.$id];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($cm_sllines as $cm_slindex => $cm_slline){
   if($cm_slindex == $id){
       $cm_sllines[$cm_slindex] = $data . "\n";
   }

}

$content = implode($cm_sllines);
$insert=file_put_contents('../txtdb/cm_star_line_results.txt', $content);
if ($insert) {
$query = mysqli_query($con,"SELECT * FROM cm_sl WHERE updateDate='$today'") or die(mysqli_error());
$num_rows=mysqli_num_rows($query);
$row = mysqli_fetch_array($query);
$day=date('l');
if($num_rows == 1){
  mysqli_query($con,"UPDATE `cm_sl` SET `result".$id."`='".$data."' WHERE id='".$row['id']."'");
}
else{
  mysqli_query($con,"INSERT INTO `cm_sl`(`result".$id."`, `updateDate`, `updateDay`) VALUES ('".$data."','$today','$day')");
}
$msg='<div class="alert alert-success">Result Update Successfully</div>';
}
else{
 $msg='Something Wrong!'; 
}
}elseif (isset($_POST['update_kpl_slResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($kpl_sllines as $kpl_slindex => $kpl_slline){
   if($kpl_slindex == $id){
       $kpl_sllines[$kpl_slindex] = $data . "\n";
   }

}

$content = implode($kpl_sllines);
$insert=file_put_contents('../txtdb/kpl_star_line_results.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_mr_slResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($mr_sllines as $mr_slindex => $mr_slline){
   if($mr_slindex == $id){
       $mr_sllines[$mr_slindex] = $data . "\n";
   }

}

$content = implode($mr_sllines);
$insert=file_put_contents('../txtdb/mr_star_line_results.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_main_org_slResult'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($msl_org_lines as $msl_org_slindex => $msl_org_slline){
   if($msl_org_slindex == $id){
       $msl_org_lines[$msl_org_slindex] = $data . "\n";
   }

}

$content = implode($msl_org_lines);
$insert=file_put_contents('../txtdb/main_original_sl.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_kalyan_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($kalyan_starline_lines as $kalyan_starline_index => $kalyan_msl_org_slline){
   if($kalyan_starline_index == $id){
       $kalyan_starline_lines[$kalyan_starline_index] = $data . "\n";
   }

}

$content = implode($kalyan_starline_lines);
$insert=file_put_contents('../txtdb/kalyan_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_crr_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($crr_starline_lines as $crr_starline_index => $crr_msl_org_slline){
   if($crr_starline_index == $id){
       $crr_starline_lines[$crr_starline_index] = $data . "\n";
   }

}

$content = implode($crr_starline_lines);
$insert=file_put_contents('../txtdb/crr_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_silver_line_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($silver_starline_lines as $silver_starline_index => $silver_msl_org_slline){
   if($silver_starline_index == $id){
       $silver_starline_lines[$silver_starline_index] = $data . "\n";
   }

}

$content = implode($silver_starline_lines);
$insert=file_put_contents('../txtdb/silver_line_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_sai_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($sai_starline_lines as $sai_starline_index => $sai_msl_org_slline){
   if($sai_starline_index == $id){
       $sai_starline_lines[$sai_starline_index] = $data . "\n";
   }

}

$content = implode($sai_starline_lines);
$insert=file_put_contents('../txtdb/sai_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_goldline_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($gold_starline_lines as $gold_starline_index => $gold_msl_org_slline){
   if($gold_starline_index == $id){
       $gold_starline_lines[$gold_starline_index] = $data . "\n";
   }

}

$content = implode($gold_starline_lines);
$insert=file_put_contents('../txtdb/goldline_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
  }
  else{
   $msg='Something Wrong!'; 
  }
}elseif (isset($_POST['update_sridevi_star_line'])) {
  $id = htmlspecialchars(mysqli_real_escape_string($con,$_GET['id']));
  $result=$_POST['result'];
  $data=htmlspecialchars(mysqli_real_escape_string($con,$result));

foreach($sridevi_starline_lines as $sridevi_starline_index => $sridevi_msl_org_slline){
   if($sridevi_starline_index == $id){
       $sridevi_starline_lines[$sridevi_starline_index] = $data . "\n";
   }

}

$content = implode($sridevi_starline_lines);
$insert=file_put_contents('../txtdb/sridevi_star_line.txt', $content);
  if ($insert) {
    $msg='<div class="alert alert-success">Result Update Successfully</div>';
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
  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate, $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk)=explode("|", $line);
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
		  <input type="hidden" name="finalAnk" class="form-control" value="<?php echo $finalAnk; ?>">
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
		  <input type="hidden" name="finalAnk" class="form-control" value="<?php echo $finalAnk; ?>">
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




<?php if (in_array('msl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>Main SL 09:05 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result1" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[1] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 10:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result2" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[2] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 11:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result3" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[3] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 12:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result4" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[4] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 01:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result5" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[5] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 02:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result6" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[6] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 03:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result7" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[7] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result8" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[8] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 05:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result9" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[9] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 06:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result10" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[10] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 07:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result11" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[11] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>Main SL 08:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result12" class="form-control mb-2" id="inlineFormInput" value="<?php echo $main_sllines[12] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<hr>
<?php } ?>



<!-- start cm star line -->
<?php if (in_array('cmsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>CM SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result1" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[1] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result2" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[2] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result3" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[3] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result4" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[4] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result5" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[5] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result6" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[6] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 04:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result7" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[7] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result8" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[8] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result9" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[9] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result10" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[10] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl11', $permissions) && 0) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 08:00 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result11" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[11] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('cmsl12', $permissions) && 0) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CM SL 09:00 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result12" class="form-control mb-2" id="inlineFormInput" value="<?php echo $cm_sllines[12] ?>" pattern="[\d*]+" maxlength="3">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_cm_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<hr>
<?php } ?>
<!-- end cm star line -->


<!-- start kpl star line -->
<?php if (in_array('kplsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>KALYAN PETTI LIVE SL 11:05 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 12:05 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 01:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 02:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 03:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 04:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 05:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 06:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 07:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 08:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 09:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kplsl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN PETTI LIVE SL 10:05 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kpl_sllines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kpl_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end kpl star line -->




<!-- start mr star line -->
<?php if (in_array('mrsl1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>MR SL 09:30 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 10:30 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 11:30 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 12:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 01:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 02:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 03:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 05:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 06:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 07:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('mrsl12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MR SL 08:30 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $mr_sllines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_mr_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end mr star line -->





<!-- start main sl original star line -->
<?php if (in_array('msl_org1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>MAIN SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('msl_org12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>MAIN SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $msl_org_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_main_org_slResult" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end ms original star line -->




<!-- kalyan_star_line star line -->
<?php if (in_array('kalyan_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>KALYAN SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('kalyan_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>KALYAN SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $kalyan_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_kalyan_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end kalyan_star_line star line -->



<!-- crr_star_line star line -->
<?php if (in_array('crr_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>CRR SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('crr_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>CRR SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $crr_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_crr_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end crr_star_line star line -->


<!-- silver_star_line star line -->
<?php if (in_array('silver_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SILVER SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('silver_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SILVER SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $silver_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_silver_line_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end silver_star_line star line -->



<!-- sai_star_line star line -->
<?php if (in_array('sai_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SAI SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sai_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SAI SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sai_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sai_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end sai_star_line star line -->



<!-- goldline_star_line star line -->
<?php if (in_array('goldline_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>GOLD SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('goldline_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>GOLD SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $gold_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_goldline_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end goldline_star_line star line -->



<!-- sridevi_star_line star line -->
<?php if (in_array('sridevi_star_line1', $permissions)) { ?>
<form action="?id=1" method="post">
  <div class="form-group row">
      <div class="col-md-3 col-sm-2">
          <p><b>SRIDEVI SL 09:15 AM</b></p>
      </div>
      <div class="col-md-5 col-sm-8">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[1] ?>">
      </div>
      <div class="col-md-3 col-sm-2">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line2', $permissions)) { ?>
<form action="?id=2" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 10:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[2] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line3', $permissions)) { ?>
<form action="?id=3" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 11:15 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[3] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line4', $permissions)) { ?>
<form action="?id=4" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 12:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[4] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line5', $permissions)) { ?>
<form action="?id=5" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 01:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[5] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line6', $permissions)) { ?>
<form action="?id=6" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 02:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[6] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line7', $permissions)) { ?>
<form action="?id=7" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 03:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[7] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line8', $permissions)) { ?>
<form action="?id=8" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 04:00 AM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[8] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line9', $permissions)) { ?>
<form action="?id=9" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 05:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[9] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line10', $permissions)) { ?>
<form action="?id=10" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 06:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[10] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line11', $permissions)) { ?>
<form action="?id=11" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 07:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[11] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<?php if (in_array('sridevi_star_line12', $permissions)) { ?>
<form action="?id=12" method="post">
  <div class="form-group row">
      <div class="col-md-3">
          <p><b>SRIDEVI SL 08:15 PM</b></p>
      </div>
      <div class="col-md-5">
          <input type="text" name="result" class="form-control mb-2" id="inlineFormInput" value="<?php echo $sridevi_starline_lines[12] ?>">
      </div>
      <div class="col-md-3">
          <button type="submit" class="btn btn-success mb-2" name="update_sridevi_star_line" style="margin-top: 5px;"><b>Update Result</b></button>
      </div>
  </div>
</form>
<?php } ?>
<!-- end sridevi_star_line star line -->
        
      
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
  <script src="assets/js/toastr.js"></script>


  <!-- JavaScripts initializations and stuff -->
  <script src="assets/js/neon-custom.js"></script>


  <!-- Demo Settings -->
  <script src="assets/js/neon-demo.js"></script>

</body>
</html>
