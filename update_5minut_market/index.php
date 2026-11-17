<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    isset($_SESSION['admin'])
    &&
    $_SESSION['admin'] === true
) {
    header("Location: admin.php");
    exit;
}

$admin_user = "admin009";
$admin_pass = "admin009";

$error = '';

if (isset($_POST['login'])) {

    $username = trim((string)($_POST['username'] ?? ''));
    $password = trim((string)($_POST['password'] ?? ''));

    if (
        hash_equals($admin_user, $username)
        &&
        hash_equals($admin_pass, $password)
    ) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "
        <div class='alert alert-danger'>
            Invalid Login Details
        </div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mumbai Standard Single Khech Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#f4f4f4;
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .login-card{
            width:100%;
            max-width:400px;
            background:#fff;
            border-radius:15px;
            overflow:hidden;
            box-shadow:0 5px 25px rgba(0,0,0,0.1);
        }
        .login-header{
            background:#dc3545;
            color:#fff;
            text-align:center;
            padding:20px;
        }
        .login-body{
            padding:25px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h3 class="mb-0">Admin Login</h3>
    </div>
    <div class="login-body">
        <?= $error ?>
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autocomplete="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            <button type="submit" name="login" class="btn btn-danger w-100">
                LOGIN
            </button>
        </form>
    </div>
</div>

</body>
</html>
