<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to admin.php
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

$admin_user = "admin007";
$admin_pass = "adminBack007";
$error = '';

if (isset($_POST['login'])) {
    $username = (string)($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (hash_equals($admin_user, $username) && hash_equals($admin_pass, $password)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid login!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-danger">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
