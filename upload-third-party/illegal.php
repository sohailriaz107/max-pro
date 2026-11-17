<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['firstname'])) {
    if ((string)$_SESSION['firstname'] !== 'vk') {
        $_SESSION['illegal'] = 'Illegal Activity Detected';
        header("Location: index.php");
        exit;
    }
}
