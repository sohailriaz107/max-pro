<?php
session_start();

// If already logged in, redirect to admin.php
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

$admin_user = "admin36update";
$admin_pass = "Pass36update";

if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid login!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
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
                        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
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
