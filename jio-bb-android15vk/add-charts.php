<?php 
session_start();
$pageName='add-charts';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

if(isset($_POST['add-charts'])) {
	$msg='Add Chart';
	$gName=$_POST['gameName'];
	$freq=$_POST['frequency'];

	if (isset($_POST['insertChart'])) {
		
		$gName=$_POST['gameName'];
	$freq=$_POST['frequency'];
	
		if ($freq==7) {
			$fromDate=$_POST['fromDate'];
			$toDate=$_POST['toDate'];
			$openPatti1=$_POST['openPatti1'];
			$closePatti1=$_POST['closePatti1'];
			$openPatti2=$_POST['openPatti2'];
			$closePatti2=$_POST['closePatti2'];
			$openPatti3=$_POST['openPatti3'];
			$closePatti3=$_POST['closePatti3'];
			$openPatti4=$_POST['openPatti4'];
			$closePatti4=$_POST['closePatti4'];
			$openPatti5=$_POST['openPatti5'];
			$closePatti5=$_POST['closePatti5'];
			$openPatti6=$_POST['openPatti6'];
			$closePatti6=$_POST['closePatti6'];
			$openPatti7=$_POST['openPatti7'];
			$closePatti7=$_POST['closePatti7'];
			$updateDate = date('d/m/Y', strtotime('-1 day'));

			$re = '/^(\d{3})$/m';
			if (preg_match_all($re, $openPatti1, $openPatti1) && preg_match_all($re, $closePatti1, $closePatti1) && preg_match_all($re, $openPatti2, $openPatti2) && preg_match_all($re, $closePatti2, $closePatti2) && preg_match_all($re, $openPatti3, $openPatti3) && preg_match_all($re, $closePatti3, $closePatti3) && preg_match_all($re, $openPatti4, $openPatti4) && preg_match_all($re, $closePatti4, $closePatti4) && preg_match_all($re, $openPatti5, $openPatti5) && preg_match_all($re, $closePatti5, $closePatti5) && preg_match_all($re, $openPatti6, $openPatti6) && preg_match_all($re, $closePatti6, $closePatti6) && preg_match_all($re, $openPatti7, $openPatti7) && preg_match_all($re, $closePatti7, $closePatti7)) {
				  $openNum1=(string)$_POST['openPatti1'];
				  $closeNum1=(string)$_POST['closePatti1'];
			      $oa1=$openNum1[0]; $ob1=$openNum1[1]; $oc1=$openNum1[2];
			      $ca1=$closeNum1[0]; $cb1=$closeNum1[1]; $cc1=$closeNum1[2];
			      $jodiCalcOpen1=$oa1+$ob1+$oc1;
			      $jodiOpen1=substr($jodiCalcOpen1, -1);
			      $jodiCalcClose1=$ca1+$cb1+$cc1;
			      $jodiClose1=substr($jodiCalcClose1, -1);
			      $jodi1=$jodiOpen1.$jodiClose1;

			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi1)) { $color1='red'; }
			      else{ $color1='black'; }
			      //number 2

			      $openNum2=(string)$_POST['openPatti2'];
				  $closeNum2=(string)$_POST['closePatti2'];
			      $oa2=$openNum2[0]; $ob2=$openNum2[1]; $oc2=$openNum2[2];
			      $ca2=$closeNum2[0]; $cb2=$closeNum2[1]; $cc2=$closeNum2[2];
			      $jodiCalcOpen2=$oa2+$ob2+$oc2;
			      $jodiOpen2=substr($jodiCalcOpen2, -1);
			      $jodiCalcClose2=$ca2+$cb2+$cc2;
			      $jodiClose2=substr($jodiCalcClose2, -1);
			      $jodi2=$jodiOpen2.$jodiClose2;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi2)) { $color2='red'; }
			      else{ $color2='black'; }
			      //number 3

			      $openNum3=(string)$_POST['openPatti3'];
				  $closeNum3=(string)$_POST['closePatti3'];
			      $oa3=$openNum3[0]; $ob3=$openNum3[1]; $oc3=$openNum3[2];
			      $ca3=$closeNum3[0]; $cb3=$closeNum3[1]; $cc3=$closeNum3[2];
			      $jodiCalcOpen3=$oa3+$ob3+$oc3;
			      $jodiOpen3=substr($jodiCalcOpen3, -1);
			      $jodiCalcClose3=$ca3+$cb3+$cc3;
			      $jodiClose3=substr($jodiCalcClose3, -1);
			      $jodi3=$jodiOpen3.$jodiClose3;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi3)) { $color3='red'; }
			      else{ $color3='black'; }
			      //number 4
			      $openNum4=(string)$_POST['openPatti4'];
				  $closeNum4=(string)$_POST['closePatti4'];
			      $oa4=$openNum4[0]; $ob4=$openNum4[1]; $oc4=$openNum4[2];
			      $ca4=$closeNum4[0]; $cb4=$closeNum4[1]; $cc4=$closeNum4[2];
			      $jodiCalcOpen4=$oa4+$ob4+$oc4;
			      $jodiOpen4=substr($jodiCalcOpen4, -1);
			      $jodiCalcClose4=$ca4+$cb4+$cc4;
			      $jodiClose4=substr($jodiCalcClose4, -1);
			      $jodi4=$jodiOpen4.$jodiClose4;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi4)) { $color4='red'; }
			      else{ $color4='black'; }
			      //number 5
			      $openNum5=(string)$_POST['openPatti5'];
				  $closeNum5=(string)$_POST['closePatti5'];
			      $oa5=$openNum5[0]; $ob5=$openNum5[1]; $oc5=$openNum5[2];
			      $ca5=$closeNum5[0]; $cb5=$closeNum5[1]; $cc5=$closeNum5[2];
			      $jodiCalcOpen5=$oa5+$ob5+$oc5;
			      $jodiOpen5=substr($jodiCalcOpen5, -1);
			      $jodiCalcClose5=$ca5+$cb5+$cc5;
			      $jodiClose5=substr($jodiCalcClose5, -1);
			      $jodi5=$jodiOpen5.$jodiClose5;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi5)) { $color5='red'; }
			      else{ $color5='black'; }
			      //number 6
			      $openNum6=(string)$_POST['openPatti6'];
				  $closeNum6=(string)$_POST['closePatti6'];
			      $oa6=$openNum6[0]; $ob6=$openNum6[1]; $oc6=$openNum6[2];
			      $ca6=$closeNum6[0]; $cb6=$closeNum6[1]; $cc6=$closeNum6[2];
			      $jodiCalcOpen6=$oa6+$ob6+$oc6;
			      $jodiOpen6=substr($jodiCalcOpen6, -1);
			      $jodiCalcClose6=$ca6+$cb6+$cc6;
			      $jodiClose6=substr($jodiCalcClose6, -1);
			      $jodi6=$jodiOpen6.$jodiClose6;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi6)) { $color6='red'; }
			      else{ $color6='black'; }
			      //number 7
			      $openNum7=(string)$_POST['openPatti7'];
				  $closeNum7=(string)$_POST['closePatti7'];
			      $oa7=$openNum7[0]; $ob7=$openNum7[1]; $oc7=$openNum7[2];
			      $ca7=$closeNum7[0]; $cb7=$closeNum7[1]; $cc7=$closeNum7[2];
			      $jodiCalcOpen7=$oa7+$ob7+$oc7;
			      $jodiOpen7=substr($jodiCalcOpen7, -1);
			      $jodiCalcClose7=$ca7+$cb7+$cc7;
			      $jodiClose7=substr($jodiCalcClose7, -1);
			      $jodi7=$jodiOpen7.$jodiClose7;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi7)) { $color7='red'; }
			      else{ $color7='black'; }

				  $records = [
					[$gName, $oa1, $ob1, $oc1, $jodi1, $ca1, $cb1, $cc1, $color1, $freq, $fromDate, $toDate, $updateDate],
					[$gName, $oa2, $ob2, $oc2, $jodi2, $ca2, $cb2, $cc2, $color2, $freq, '', '', $updateDate],
					[$gName, $oa3, $ob3, $oc3, $jodi3, $ca3, $cb3, $cc3, $color3, $freq, '', '', $updateDate],
					[$gName, $oa4, $ob4, $oc4, $jodi4, $ca4, $cb4, $cc4, $color4, $freq, '', '', $updateDate],
					[$gName, $oa5, $ob5, $oc5, $jodi5, $ca5, $cb5, $cc5, $color5, $freq, '', '', $updateDate],
					[$gName, $oa6, $ob6, $oc6, $jodi6, $ca6, $cb6, $cc6, $color6, $freq, '', '', $updateDate],
					[$gName, $oa7, $ob7, $oc7, $jodi7, $ca7, $cb7, $cc7, $color7, $freq, '', '', $updateDate]
				];
				
				$success = true;
				
				foreach ($records as $record) {
					// Destructure the record array to extract necessary values
					[$gName, $oa, $ob, $oc, $jodi, $ca, $cb, $cc, $color, $freq, $fromDate, $toDate, $updateDate] = $record;
				
					// Skip insertion if $oa, $ob, $oc, $ca, $cb, $cc all have 0 value
					if ($oa == 0 && $ob == 0 && $oc == 0 && $ca == 0 && $cb == 0 && $cc == 0) {
						continue;
					}
				
					// Build and execute the query
					$query = "INSERT INTO `panel` (`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) 
							  VALUES ('$gName', '$oa', '$ob', '$oc', '$jodi', '$ca', '$cb', '$cc', '$color', '$freq', '$fromDate', '$toDate', '$updateDate')";
				
					if (!mysqli_query($con, $query)) {
						$success = false;
						break;
					}
				}
				
				if ($success) {
					$msg2 = '<div class="alert alert-success">Good! All records of ' . $gName . ' are updated successfully! Redirecting...</div>';
					//header("refresh: 2; url = select-charts.php");
				} else {
					$msg2 = '<div class="alert alert-danger">Failed to update records for ' . $gName . '!</div>';
				}
				
			}
			else{
				$msg2='<div class="alert alert-danger">Format Not Matched!</div>';
			}
		} //frequency
		elseif ($freq==6) {
			$fromDate=$_POST['fromDate'];
			$toDate=$_POST['toDate'];
			$openPatti1=$_POST['openPatti1'];
			$closePatti1=$_POST['closePatti1'];
			$openPatti2=$_POST['openPatti2'];
			$closePatti2=$_POST['closePatti2'];
			$openPatti3=$_POST['openPatti3'];
			$closePatti3=$_POST['closePatti3'];
			$openPatti4=$_POST['openPatti4'];
			$closePatti4=$_POST['closePatti4'];
			$openPatti5=$_POST['openPatti5'];
			$closePatti5=$_POST['closePatti5'];
			$openPatti6=$_POST['openPatti6'];
			$closePatti6=$_POST['closePatti6'];
			$updateDate = date('d/m/Y', strtotime('-1 day'));

			$re = '/^(\d{3})$/m';
			if (preg_match_all($re, $openPatti1, $openPatti1) && preg_match_all($re, $closePatti1, $closePatti1) && preg_match_all($re, $openPatti2, $openPatti2) && preg_match_all($re, $closePatti2, $closePatti2) && preg_match_all($re, $openPatti3, $openPatti3) && preg_match_all($re, $closePatti3, $closePatti3) && preg_match_all($re, $openPatti4, $openPatti4) && preg_match_all($re, $closePatti4, $closePatti4) && preg_match_all($re, $openPatti5, $openPatti5) && preg_match_all($re, $closePatti5, $closePatti5) && preg_match_all($re, $openPatti6, $openPatti6) && preg_match_all($re, $closePatti6, $closePatti6)) {
				  $openNum1=(string)$_POST['openPatti1'];
				  $closeNum1=(string)$_POST['closePatti1'];
			      $oa1=$openNum1[0]; $ob1=$openNum1[1]; $oc1=$openNum1[2];
			      $ca1=$closeNum1[0]; $cb1=$closeNum1[1]; $cc1=$closeNum1[2];
			      $jodiCalcOpen1=$oa1+$ob1+$oc1;
			      $jodiOpen1=substr($jodiCalcOpen1, -1);
			      $jodiCalcClose1=$ca1+$cb1+$cc1;
			      $jodiClose1=substr($jodiCalcClose1, -1);
			      $jodi1=$jodiOpen1.$jodiClose1;

			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi1)) { $color1='red'; }
			      else{ $color1='black'; }
			      //number 2

			      $openNum2=(string)$_POST['openPatti2'];
				  $closeNum2=(string)$_POST['closePatti2'];
			      $oa2=$openNum2[0]; $ob2=$openNum2[1]; $oc2=$openNum2[2];
			      $ca2=$closeNum2[0]; $cb2=$closeNum2[1]; $cc2=$closeNum2[2];
			      $jodiCalcOpen2=$oa2+$ob2+$oc2;
			      $jodiOpen2=substr($jodiCalcOpen2, -1);
			      $jodiCalcClose2=$ca2+$cb2+$cc2;
			      $jodiClose2=substr($jodiCalcClose2, -1);
			      $jodi2=$jodiOpen2.$jodiClose2;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi2)) { $color2='red'; }
			      else{ $color2='black'; }
			      //number 3

			      $openNum3=(string)$_POST['openPatti3'];
				  $closeNum3=(string)$_POST['closePatti3'];
			      $oa3=$openNum3[0]; $ob3=$openNum3[1]; $oc3=$openNum3[2];
			      $ca3=$closeNum3[0]; $cb3=$closeNum3[1]; $cc3=$closeNum3[2];
			      $jodiCalcOpen3=$oa3+$ob3+$oc3;
			      $jodiOpen3=substr($jodiCalcOpen3, -1);
			      $jodiCalcClose3=$ca3+$cb3+$cc3;
			      $jodiClose3=substr($jodiCalcClose3, -1);
			      $jodi3=$jodiOpen3.$jodiClose3;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi3)) { $color3='red'; }
			      else{ $color3='black'; }
			      //number 4
			      $openNum4=(string)$_POST['openPatti4'];
				  $closeNum4=(string)$_POST['closePatti4'];
			      $oa4=$openNum4[0]; $ob4=$openNum4[1]; $oc4=$openNum4[2];
			      $ca4=$closeNum4[0]; $cb4=$closeNum4[1]; $cc4=$closeNum4[2];
			      $jodiCalcOpen4=$oa4+$ob4+$oc4;
			      $jodiOpen4=substr($jodiCalcOpen4, -1);
			      $jodiCalcClose4=$ca4+$cb4+$cc4;
			      $jodiClose4=substr($jodiCalcClose4, -1);
			      $jodi4=$jodiOpen4.$jodiClose4;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi4)) { $color4='red'; }
			      else{ $color4='black'; }
			      //number 5
			      $openNum5=(string)$_POST['openPatti5'];
				  $closeNum5=(string)$_POST['closePatti5'];
			      $oa5=$openNum5[0]; $ob5=$openNum5[1]; $oc5=$openNum5[2];
			      $ca5=$closeNum5[0]; $cb5=$closeNum5[1]; $cc5=$closeNum5[2];
			      $jodiCalcOpen5=$oa5+$ob5+$oc5;
			      $jodiOpen5=substr($jodiCalcOpen5, -1);
			      $jodiCalcClose5=$ca5+$cb5+$cc5;
			      $jodiClose5=substr($jodiCalcClose5, -1);
			      $jodi5=$jodiOpen5.$jodiClose5;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi5)) { $color5='red'; }
			      else{ $color5='black'; }
			      //number 6
			      $openNum6=(string)$_POST['openPatti6'];
				  $closeNum6=(string)$_POST['closePatti6'];
			      $oa6=$openNum6[0]; $ob6=$openNum6[1]; $oc6=$openNum6[2];
			      $ca6=$closeNum6[0]; $cb6=$closeNum6[1]; $cc6=$closeNum6[2];
			      $jodiCalcOpen6=$oa6+$ob6+$oc6;
			      $jodiOpen6=substr($jodiCalcOpen6, -1);
			      $jodiCalcClose6=$ca6+$cb6+$cc6;
			      $jodiClose6=substr($jodiCalcClose6, -1);
			      $jodi6=$jodiOpen6.$jodiClose6;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi6)) { $color6='red'; }
			      else{ $color6='black'; }


				  $records = [
					[$gName, $oa1, $ob1, $oc1, $jodi1, $ca1, $cb1, $cc1, $color1, $freq, $fromDate, $toDate, $updateDate],
					[$gName, $oa2, $ob2, $oc2, $jodi2, $ca2, $cb2, $cc2, $color2, $freq, '', '', $updateDate],
					[$gName, $oa3, $ob3, $oc3, $jodi3, $ca3, $cb3, $cc3, $color3, $freq, '', '', $updateDate],
					[$gName, $oa4, $ob4, $oc4, $jodi4, $ca4, $cb4, $cc4, $color4, $freq, '', '', $updateDate],
					[$gName, $oa5, $ob5, $oc5, $jodi5, $ca5, $cb5, $cc5, $color5, $freq, '', '', $updateDate],
					[$gName, $oa6, $ob6, $oc6, $jodi6, $ca6, $cb6, $cc6, $color6, $freq, '', '', $updateDate]
				];
				
				$success = true;
				
				foreach ($records as $record) {
					// Destructure the record array to extract necessary values
					[$gName, $oa, $ob, $oc, $jodi, $ca, $cb, $cc, $color, $freq, $fromDate, $toDate, $updateDate] = $record;
				
					// Skip insertion if $oa, $ob, $oc, $ca, $cb, $cc all have 0 value
					if ($oa == 0 && $ob == 0 && $oc == 0 && $ca == 0 && $cb == 0 && $cc == 0) {
						continue;
					}
				
					// Build and execute the query
					$query = "INSERT INTO `panel` (`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) 
							  VALUES ('$gName', '$oa', '$ob', '$oc', '$jodi', '$ca', '$cb', '$cc', '$color', '$freq', '$fromDate', '$toDate', '$updateDate')";
				
					if (!mysqli_query($con, $query)) {
						$success = false;
						break;
					}
				}
				
				if ($success) {
					$msg2 = '<div class="alert alert-success">Good! All records of ' . $gName . ' are updated successfully! Redirecting...</div>';
					//header("refresh: 2; url = select-charts.php");
				} else {
					$msg2 = '<div class="alert alert-danger">Failed to update records for ' . $gName . '!</div>';
				}
			}
			else{
				$msg2='<div class="alert alert-danger">Format Not Matched!</div>';
			}
		} //frequency
		elseif ($freq==5) {
			$fromDate=$_POST['fromDate'];
			$toDate=$_POST['toDate'];
			$openPatti1=$_POST['openPatti1'];
			$closePatti1=$_POST['closePatti1'];
			$openPatti2=$_POST['openPatti2'];
			$closePatti2=$_POST['closePatti2'];
			$openPatti3=$_POST['openPatti3'];
			$closePatti3=$_POST['closePatti3'];
			$openPatti4=$_POST['openPatti4'];
			$closePatti4=$_POST['closePatti4'];
			$openPatti5=$_POST['openPatti5'];
			$closePatti5=$_POST['closePatti5'];
			$updateDate = date('d/m/Y', strtotime('-1 day'));

			$re = '/^(\d{3})$/m';
			if (preg_match_all($re, $openPatti1, $openPatti1) && preg_match_all($re, $closePatti1, $closePatti1) && preg_match_all($re, $openPatti2, $openPatti2) && preg_match_all($re, $closePatti2, $closePatti2) && preg_match_all($re, $openPatti3, $openPatti3) && preg_match_all($re, $closePatti3, $closePatti3) && preg_match_all($re, $openPatti4, $openPatti4) && preg_match_all($re, $closePatti4, $closePatti4) && preg_match_all($re, $openPatti5, $openPatti5) && preg_match_all($re, $closePatti5, $closePatti5)) {
				  $openNum1=(string)$_POST['openPatti1'];
				  $closeNum1=(string)$_POST['closePatti1'];
			      $oa1=$openNum1[0]; $ob1=$openNum1[1]; $oc1=$openNum1[2];
			      $ca1=$closeNum1[0]; $cb1=$closeNum1[1]; $cc1=$closeNum1[2];
			      $jodiCalcOpen1=$oa1+$ob1+$oc1;
			      $jodiOpen1=substr($jodiCalcOpen1, -1);
			      $jodiCalcClose1=$ca1+$cb1+$cc1;
			      $jodiClose1=substr($jodiCalcClose1, -1);
			      $jodi1=$jodiOpen1.$jodiClose1;

			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi1)) { $color1='red'; }
			      else{ $color1='black'; }
			      //number 2

			      $openNum2=(string)$_POST['openPatti2'];
				  $closeNum2=(string)$_POST['closePatti2'];
			      $oa2=$openNum2[0]; $ob2=$openNum2[1]; $oc2=$openNum2[2];
			      $ca2=$closeNum2[0]; $cb2=$closeNum2[1]; $cc2=$closeNum2[2];
			      $jodiCalcOpen2=$oa2+$ob2+$oc2;
			      $jodiOpen2=substr($jodiCalcOpen2, -1);
			      $jodiCalcClose2=$ca2+$cb2+$cc2;
			      $jodiClose2=substr($jodiCalcClose2, -1);
			      $jodi2=$jodiOpen2.$jodiClose2;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi2)) { $color2='red'; }
			      else{ $color2='black'; }
			      //number 3

			      $openNum3=(string)$_POST['openPatti3'];
				  $closeNum3=(string)$_POST['closePatti3'];
			      $oa3=$openNum3[0]; $ob3=$openNum3[1]; $oc3=$openNum3[2];
			      $ca3=$closeNum3[0]; $cb3=$closeNum3[1]; $cc3=$closeNum3[2];
			      $jodiCalcOpen3=$oa3+$ob3+$oc3;
			      $jodiOpen3=substr($jodiCalcOpen3, -1);
			      $jodiCalcClose3=$ca3+$cb3+$cc3;
			      $jodiClose3=substr($jodiCalcClose3, -1);
			      $jodi3=$jodiOpen3.$jodiClose3;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi3)) { $color3='red'; }
			      else{ $color3='black'; }
			      //number 4
			      $openNum4=(string)$_POST['openPatti4'];
				  $closeNum4=(string)$_POST['closePatti4'];
			      $oa4=$openNum4[0]; $ob4=$openNum4[1]; $oc4=$openNum4[2];
			      $ca4=$closeNum4[0]; $cb4=$closeNum4[1]; $cc4=$closeNum4[2];
			      $jodiCalcOpen4=$oa4+$ob4+$oc4;
			      $jodiOpen4=substr($jodiCalcOpen4, -1);
			      $jodiCalcClose4=$ca4+$cb4+$cc4;
			      $jodiClose4=substr($jodiCalcClose4, -1);
			      $jodi4=$jodiOpen4.$jodiClose4;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi4)) { $color4='red'; }
			      else{ $color4='black'; }
			      //number 5
			      $openNum5=(string)$_POST['openPatti5'];
				  $closeNum5=(string)$_POST['closePatti5'];
			      $oa5=$openNum5[0]; $ob5=$openNum5[1]; $oc5=$openNum5[2];
			      $ca5=$closeNum5[0]; $cb5=$closeNum5[1]; $cc5=$closeNum5[2];
			      $jodiCalcOpen5=$oa5+$ob5+$oc5;
			      $jodiOpen5=substr($jodiCalcOpen5, -1);
			      $jodiCalcClose5=$ca5+$cb5+$cc5;
			      $jodiClose5=substr($jodiCalcClose5, -1);
			      $jodi5=$jodiOpen5.$jodiClose5;
			      $detectColor='/^(00|05|11|16|22|27|33|38|44|49|50|55|61|66|72|77|83|88|94|99)$/';
			      if (preg_match_all($detectColor,$jodi5)) { $color5='red'; }
			      else{ $color5='black'; }

			      $records = [
					[$gName, $oa1, $ob1, $oc1, $jodi1, $ca1, $cb1, $cc1, $color1, $freq, $fromDate, $toDate, $updateDate],
					[$gName, $oa2, $ob2, $oc2, $jodi2, $ca2, $cb2, $cc2, $color2, $freq, '', '', $updateDate],
					[$gName, $oa3, $ob3, $oc3, $jodi3, $ca3, $cb3, $cc3, $color3, $freq, '', '', $updateDate],
					[$gName, $oa4, $ob4, $oc4, $jodi4, $ca4, $cb4, $cc4, $color4, $freq, '', '', $updateDate],
					[$gName, $oa5, $ob5, $oc5, $jodi5, $ca5, $cb5, $cc5, $color5, $freq, '', '', $updateDate]
				];
				
				$success = true;
				
				foreach ($records as $record) {
					// Destructure the record array to extract necessary values
					[$gName, $oa, $ob, $oc, $jodi, $ca, $cb, $cc, $color, $freq, $fromDate, $toDate, $updateDate] = $record;
				
					// Skip insertion if $oa, $ob, $oc, $ca, $cb, $cc all have 0 value
					if ($oa == 0 && $ob == 0 && $oc == 0 && $ca == 0 && $cb == 0 && $cc == 0) {
						continue;
					}
				
					// Build and execute the query
					$query = "INSERT INTO `panel` (`gameName`, `a`, `b`, `c`, `d`, `e`, `f`, `g`, `color`, `frequency`, `fromDate`, `toDate`, `updateDate`) 
							  VALUES ('$gName', '$oa', '$ob', '$oc', '$jodi', '$ca', '$cb', '$cc', '$color', '$freq', '$fromDate', '$toDate', '$updateDate')";
				
					if (!mysqli_query($con, $query)) {
						$success = false;
						break;
					}
				}
				
				if ($success) {
					$msg2 = '<div class="alert alert-success">Good! All records of ' . $gName . ' are updated successfully! Redirecting...</div>';
					//header("refresh: 2; url = select-charts.php");
				} else {
					$msg2 = '<div class="alert alert-danger">Failed to update records for ' . $gName . '!</div>';
				}
			}
			else{
				$msg2='<div class="alert alert-danger">Format Not Matched!</div>';
			}
		} //frequency
		else{
			$msg2='<div class="alert alert-danger">Frequency Not Matched!</div>';
		}
	} //if isset add-charts
	else{
		$msg2='';
	}
}
else{
  header('location:select-charts.php');
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
		
					
		<h2> <?php echo $msg; ?></h2>
		<?php echo $msg2; ?>
		<br />
		
		
		<div class="row">
			<div class="col-md-12">
				
						<form role="form" class="form-horizontal" action="" method="post">
			
							<div class="form-group">
								<div class="col-md-6">
									<label>From Date</label>
									<input type="text" name="fromDate" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required="">
								</div>
								<div class="col-md-6">
									<label>To Date</label>
									<input type="text" name="toDate" class="form-control" pattern="\d{2}/\d{2}/\d{4}" required="">
								</div>
							</div>
<?php 

	for ($i=0; $i < $freq; $i++) { 
		?>
<div class="form-group">
	<div class="col-md-3">
		<label>Open Patti (Day <?php echo $i+1; ?>)</label>
		<input type="text" name="openPatti<?php echo $i+1; ?>" class="form-control" pattern="\d{3}" placeholder="123" required maxlength="3">
	</div>
	<div class="col-md-3">
		<label>Close Patti (Day <?php echo $i+1; ?>)</label>
		<input type="text" name="closePatti<?php echo $i+1; ?>" class="form-control" pattern="\d{3}" placeholder="456"  required maxlength="3">
	</div>
</div>
<?php
}
?>
							<div class="form-group">
								<div class="col-md-12">
									<input type="hidden" name="add-charts">
									<input type="hidden" name="gameName" value="<?php echo $gName; ?>">
									<input type="hidden" name="frequency" value="<?php echo $freq; ?>">
									<button type="submit" class="btn btn-success" name="insertChart" style="margin-top: 25px;">Update</button>
								</div>
							</div>
							
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