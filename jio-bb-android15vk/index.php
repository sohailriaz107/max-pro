<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/functions.php";

$maxAttempts = 5;
$timeWindow  = 60; // 1 minute

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (isset($_SESSION['last_login_attempt'])) {
    $timeSinceLastAttempt = time() - (int)$_SESSION['last_login_attempt'];
    if ($timeSinceLastAttempt > $timeWindow) {
        $_SESSION['login_attempts'] = 0;
    }
}

$usr_feedback = "Please Enter Login Info...";

if (isset($_POST['login'])) {
    $_SESSION['login_attempts'] = ((int)$_SESSION['login_attempts']) + 1;
    $_SESSION['last_login_attempt'] = time();

    if ($_SESSION['login_attempts'] > $maxAttempts) {
        echo "Too many login attempts. Please try again later.";
        exit;
    }

    $username     = trim((string)($_POST['username'] ?? ''));
    $rawPassword  = (string)($_POST['password'] ?? '');
    $password     = md5($rawPassword);
    $password2nd  = trim((string)($_POST['password2nd'] ?? ''));

    if ($username !== '' && $rawPassword !== '' && isset($con) && $con instanceof mysqli) {
        $stmt = mysqli_prepare($con, "SELECT id, username, status FROM admins WHERE username = ? AND password = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && ($userRow = mysqli_fetch_assoc($result))) {
                if ((int)$userRow['status'] === 1) {
                    $_SESSION['id']       = (int)$userRow['id'];
                    $_SESSION['username'] = (string)$userRow['username'];

                    $expirtyFile = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
                    $expiryLines = (file_exists($expirtyFile) && is_readable($expirtyFile)) ? (file($expirtyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

                    $expiryPermissions = [];
                    $expiryStmt = mysqli_prepare($con, "SELECT permissions FROM admins WHERE id = ? LIMIT 1");
                    if ($expiryStmt) {
                        $adminId = (int)$_SESSION['id'];
                        mysqli_stmt_bind_param($expiryStmt, "i", $adminId);
                        mysqli_stmt_execute($expiryStmt);
                        $expRes = mysqli_stmt_get_result($expiryStmt);
                        if ($expRes && ($expRow = mysqli_fetch_assoc($expRes))) {
                            $expiryPermissions = explode(',', (string)($expRow['permissions'] ?? ''));
                        }
                        mysqli_stmt_close($expiryStmt);
                    }

                    $hasAllowedPermission = false;
                    foreach ($expiryLines as $line) {
                        $parts = explode("|", (string)$line);
                        $gameName   = trim((string)($parts[1] ?? ''));
                        $expiryDate = trim((string)($parts[13] ?? $parts[12] ?? ''));

                        if ($gameName !== '' && in_array($gameName, $expiryPermissions, true)) {
                            $hasAllowedPermission = true;
                            $date1 = str_replace('/', '-', date('Y-m-d')); 
                            $date2 = str_replace('/', '-', $expiryDate);
                            $t1 = strtotime($date1);
                            $t2 = strtotime($date2);

                            if ($t1 !== false && $t2 !== false && $t1 <= $t2) {
                                if ($_SESSION['username'] === 'vk' && $password2nd === '1292') {
                                    header("Location: update-main-market-results.php");
                                    exit;
                                } else {
                                    header("Location: logout.php");
                                    exit;
                                }
                            } elseif ($t1 !== false && $t2 !== false && $t1 > $t2) {
                                $usr_feedback = "<div class='alert alert-danger'><strong>Sorry</strong> Your plan has been expired. Please contact admin</div>";
                            }
                        }
                    }

                    if (!$hasAllowedPermission) {
                        $usr_feedback = "<div class='alert alert-danger'><strong>Sorry!</strong> You don't have any game permissions added by admin</div>";
                    }
                } else {
                    $usr_feedback = "<div class='alert alert-danger'><strong>Sorry</strong> Your ID is not active. Please contact Admin</div>";
                }
            } else {
                $usr_feedback = "Invalid Login details";
            }
            mysqli_stmt_close($stmt);
        } else {
            $usr_feedback = "Invalid Login details";
        }
    } else {
        $usr_feedback = "Invalid Login details";
    }
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Administrator Login Panel" />
    <title>ADMINISTRATOR LOGIN</title>

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

    <style>
    .login-page .login-content a {
        color: cyan;
    }
    </style>
</head>
<body class="page-body skin-red login-page">

<div class="login-container">
    <div class="login-header login-caret">
        <div class="login-content">
            <h1><a href="index.php" class="logo">Amul</a></h1>
            <div class="description"><?php 
                if (isset($_SESSION['illegal'])) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars((string)$_SESSION['illegal'], ENT_QUOTES, 'UTF-8') . '</div>';
                    unset($_SESSION['illegal']);
                } else {
                    echo $usr_feedback;
                }
            ?></div>
        </div>
    </div>
    
    <div class="login-form">
        <div class="login-content">
            <form method="post" action="">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-user"></i>
                        </div>
                        <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off" required />
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>
                        <input type="number" class="form-control" name="password2nd" id="password2nd" placeholder="Auth Key" autocomplete="off" required />
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-login" name="login">
                        <i class="entypo-login"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/gsap/TweenMax.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/joinable.js"></script>
<script src="assets/js/resizeable.js"></script>
<script src="assets/js/neon-api.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script src="assets/js/neon-login.js"></script>
<script src="assets/js/neon-custom.js"></script>
<script src="assets/js/neon-demo.js"></script>

</body>
</html>
