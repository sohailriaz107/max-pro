<?php 
declare(strict_types=1);

session_start();
$pageName = 'other-free-game';
require('sessions.php');
require "../config.php";
require "illegal.php";
require "functions.php";

$file = '../txtdb/other-free-game.txt';
$lines = file_exists($file) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$msg = '<h2>Edit Result</h2>';

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : -1;

    $sanitize = function(string $v): string {
        return str_replace(["|", "\r", "\n"], '', trim(strip_tags($v)));
    };

    $gName = $sanitize((string)($_POST['gName'] ?? ''));
    $open  = $sanitize((string)($_POST['open'] ?? ''));
    $panna = $sanitize((string)($_POST['panna'] ?? ''));
    $jodi  = $sanitize((string)($_POST['jodi'] ?? ''));
    $close = $sanitize((string)($_POST['close'] ?? ''));

    if (!preg_match("/^[a-zA-Z ]+$/", $gName)) {
        echo '<h1>Invalid Entry</h1>';
        exit;
    }
    
    if ($open !== '' && !preg_match("/^[0-9-]+$/", $open)) {
        echo '<h1>Invalid Entry <br> Only Number(0-9) and Hyphen(-) Allowed</h1>';
        exit;
    }
    
    if ($panna !== '' && !preg_match("/^[0-9-]+$/", $panna)) {
        echo '<h1>Invalid Entry <br> Only Number(0-9) and Hyphen(-) Allowed</h1>';
        exit;
    }
    
    if ($jodi !== '' && !preg_match("/^[0-9-]+$/", $jodi)) {
        echo '<h1>Invalid Entry <br> Only Number(0-9) and Hyphen(-) Allowed</h1>';
        exit;
    }
    
    if ($close !== '' && !preg_match("/^[0-9-]+$/", $close)) {
        echo '<h1>Invalid Entry <br> Only Number(0-9) and Hyphen(-) Allowed</h1>';
        exit;
    }

    $data = $gName . '|' . $open . '|' . $panna . '|' . $jodi . '|' . $close . '|';

    if ($id >= 0 && isset($lines[$id])) {
        $lines[$id] = $data;
        $content = implode("\n", $lines) . "\n";
        file_put_contents($file, $content, LOCK_EX);
        $msg = '<div class="alert alert-success">Update Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger">Invalid Game Record</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Neon Admin Panel" />
  <meta name="author" content="" />

  <link rel="icon" href="assets/images/favicon.ico">

  <title>Update Free Game</title>

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

  <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="page-body skin-red">

<div class="page-container">
  <?php include 'sidebar-menu.php'; ?>

  <div class="main-content">
    <div class="row">
      <!-- Profile Info and Notifications -->
      <?php include 'header.php'; ?>
    </div>
    <hr />
    <?php echo $msg; ?>
    <br />

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
          <div class="panel-heading">
            <div class="panel-title">
              Edit Result
            </div>
          </div>
          <div class="panel-body">
<?php 
$permissions = [];
if (isset($_SESSION['id']) && is_numeric($_SESSION['id'])) {
    $stmtAdm = mysqli_prepare($con, "SELECT id, fg_permissions, username FROM admins WHERE id = ?");
    if ($stmtAdm) {
        $uId = (int)$_SESSION['id'];
        mysqli_stmt_bind_param($stmtAdm, "i", $uId);
        mysqli_stmt_execute($stmtAdm);
        $resAdm = mysqli_stmt_get_result($stmtAdm);
        if ($resAdm && $rowAdm = mysqli_fetch_assoc($resAdm)) {
            $permissions = array_filter(array_map('trim', explode(',', (string)($rowAdm['fg_permissions'] ?? ''))));
        }
        mysqli_stmt_close($stmtAdm);
    }
}

foreach ($lines as $line_num => $line) {
    $parts = array_pad(explode("|", trim((string)$line)), 5, '');
    $gameName = $parts[0];
    $open     = $parts[1];
    $panna    = $parts[2];
    $jodi     = $parts[3];
    $close    = $parts[4];

    if (in_array($gameName, $permissions, true)) {
?>
    <form role="form" class="form-horizontal form-groups-bordered" action="?id=<?php echo (int)$line_num; ?>" method="post">
      <div class="row">
        <div class="col-md-2 col-sm-12">
            <label>Game Name</label>
            <input type="text" name="gName" class="form-control" value="<?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 col-sm-12">
            <label>Open</label>
            <input type="text" name="open" class="form-control" value="<?php echo htmlspecialchars($open, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 col-sm-12">
            <label>Panna</label>
            <input type="text" name="panna" class="form-control" value="<?php echo htmlspecialchars($panna, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 col-sm-12">
            <label>Jodi</label>
            <input type="text" name="jodi" class="form-control" value="<?php echo htmlspecialchars($jodi, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 col-sm-12">
            <label>Close</label>
            <input type="text" name="close" class="form-control" value="<?php echo htmlspecialchars($close, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="col-md-2 col-sm-12">
            <button type="submit" class="btn btn-primary" name="updateResult" style="margin-top: 25px">UPDATE</button>
        </div>
      </div>
    </form>
    <hr>
<?php
    }
}
?>
          </div>
        </div>
      </div>
    </div>
    
    <?php include 'footer.php'; ?>
  </div>
</div>

  <!-- Bottom scripts (common) -->
  <script src="assets/js/gsap/TweenMax.min.js"></script>
  <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/joinable.js"></script>
  <script src="assets/js/resizeable.js"></script>
  <script src="assets/js/neon-api.js"></script>
  <script src="assets/js/bootstrap-switch.min.js"></script>
  <script src="assets/js/neon-chat.js"></script>
  <script src="assets/js/neon-custom.js"></script>
  <script src="assets/js/neon-demo.js"></script>

</body>
</html>
