<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'panel-charts';
require('sessions.php');
require_once "../config.php";
require_once 'functions.php';

echo 'not allowed';
exit;
