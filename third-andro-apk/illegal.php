<?php 
if (isset($_SESSION['firstname'])) {
  if ($_SESSION['firstname']!='vk') {
    header("location:index.php");
    $_SESSION['illegal']='Illegal Activity Detected';
    exit;
  }
}
?>