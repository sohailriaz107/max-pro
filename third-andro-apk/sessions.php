<?php

if(0){
// 10 mins in seconds
$inactive = 600;  
if(isset($_SESSION['timeout'])){
$session_life = time() - $_SESSION['timeout'];
if($session_life > $inactive) {
   session_destroy();
}} 
$_SESSION['timeout']=time();
}

if (!isset($_SESSION['id'])) {
  session_destroy();
  header("location:index.php");
  exit;
}
?>