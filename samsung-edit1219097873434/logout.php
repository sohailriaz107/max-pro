<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
header("Location: index.php");
exit;
