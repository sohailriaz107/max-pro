<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']!='vk') {
    header("location:index.php");
    $_SESSION['illegal']='Illegal Activity Detected';
    exit;
  }
}
?>