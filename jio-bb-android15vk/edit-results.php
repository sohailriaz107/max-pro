<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageName = 'edit-results';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

if (isset($_GET['remove_sessions']) && isset($_SESSION['username']) && (string)$_SESSION['username'] === 'vk') {
    $path = session_save_path();
    if (!empty($path) && is_dir($path)) {
        $files = glob($path . '/*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
    echo 'Redirecting...';
    header('refresh:2;url=edit-results.php');
    exit;
}

$today = date('d-m-Y');
$file = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
$lines = (file_exists($file) && is_readable($file)) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$msg = '<h2>Edit Result</h2>';

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
    $livePriority   = str_replace('|', '', trim((string)($_POST['livePriority'] ?? '')));
    $gName          = str_replace('|', '', trim((string)($_POST['gName'] ?? '')));
    $gResult        = str_replace('|', '', trim((string)($_POST['gResult'] ?? '')));
    $timeOpen       = str_replace('|', '', trim((string)($_POST['timeOpen'] ?? '')));
    $timeClose      = str_replace('|', '', trim((string)($_POST['timeClose'] ?? '')));
    $gBgcolor       = str_replace('|', '', trim((string)($_POST['gBgcolor'] ?? '')));
    $gFontcolor     = str_replace('|', '', trim((string)($_POST['gFontcolor'] ?? '')));
    $gLinecolor     = str_replace('|', '', trim((string)($_POST['gLinecolor'] ?? '')));
    $frequency      = str_replace('|', '', trim((string)($_POST['frequency'] ?? '')));
    $liveTimeBefore = str_replace('|', '', trim((string)($_POST['liveTimeBefore'] ?? '')));
    $liveTimeAfter  = str_replace('|', '', trim((string)($_POST['liveTimeAfter'] ?? '')));
    $updateDate     = str_replace('|', '', trim((string)($_POST['updateDate'] ?? '')));
    $status         = str_replace('|', '', trim((string)($_POST['status'] ?? '')));
    $expirtyDate    = str_replace('|', '', trim((string)($_POST['expirtyDate'] ?? '')));
    $jodiLink       = str_replace('|', '', trim((string)($_POST['jodiLink'] ?? '')));
    $panelLink      = str_replace('|', '', trim((string)($_POST['panelLink'] ?? '')));

    if (strlen($gResult) === 10) {
        $num = (int)str_replace("-", '', $gResult);
        $sum = 0;
        for ($i = 0; $i <= 7; $i++) {
            $rem = $num % 10;
            $sum = $sum + $rem;
            $num = (int)($num / 10);
        }
        $finalAnk = substr((string)$sum, -1);
    } else {
        $finalAnk = str_replace('|', '', trim((string)($_POST['finalAnk'] ?? '')));
    }

    $data = $livePriority . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDate . '|' . $status . '|' . $expirtyDate . '|' . $jodiLink . '|' . $panelLink . '|' . time() . '|' . $finalAnk . '|';

    if ($id >= 0 && isset($lines[$id])) {
        $lines[$id] = $data;
        $content = implode("\n", $lines) . "\n";
        file_put_contents($file, $content);
        $msg = '<div class="alert alert-success">Update Successfully</div>';
    } else {
        $msg = '<div class="alert alert-danger">Invalid row selected!</div>';
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
    <link rel="icon" href="assets/images/favicon.ico">
    <title>Update Results</title>

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
</head>

<body class="page-body skin-red">
    <div class="page-container">
        <?php include 'sidebar-menu.php'; ?>

        <div class="main-content">
            <div class="row">
                <?php include 'header.php'; ?>
            </div>

            <hr />

            <?php echo $msg; ?>
            <br />

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary" data-collapsed="0">
                        <div class="panel-heading">
                            <div class="panel-title">Edit Result</div>
                        </div>

                        <div class="panel-body">
                            <?php
                            foreach ($lines as $line_num => $line) {
                                $parts = array_pad(explode("|", (string)$line), 18, '');
                                [
                                    $order, $gameName, $gameResult, $timeOpen, $timeClose,
                                    $gameBgcolor, $gameFontcolor, $gameLinecolor,
                                    $frequency, $liveTimeBefore, $liveTimeAfter,
                                    $updateDate, $status, $expirtyDate,
                                    $jodiLink, $panelLink, $temp_last_updated_result, $finalAnk
                                ] = array_slice($parts, 0, 18);
                            ?>
                                <form role="form" class="form-horizontal form-groups-bordered" action="?id=<?php echo $line_num; ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <label>Game Name</label>
                                            <input type="text" name="gName" class="form-control" value="<?php echo htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Result</label>
                                            <input type="text" name="gResult" class="form-control" value="<?php echo htmlspecialchars($gameResult, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Time Open</label>
                                            <input type="time" name="timeOpen" class="form-control" value="<?php echo htmlspecialchars($timeOpen, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Time Close</label>
                                            <input type="time" name="timeClose" class="form-control" value="<?php echo htmlspecialchars($timeClose, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Background Color</label>
                                            <input type="color" name="gBgcolor" class="form-control" value="<?php echo htmlspecialchars($gameBgcolor, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Font Color</label>
                                            <input type="color" name="gFontcolor" class="form-control" value="<?php echo htmlspecialchars($gameFontcolor, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Line Color</label>
                                            <input type="color" name="gLinecolor" class="form-control" value="<?php echo htmlspecialchars($gameLinecolor, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Frequency</label>
                                            <select name="frequency" class="form-control">
                                                <option value="7" <?php if ($frequency == '7') echo 'selected'; ?>>Daily</option>
                                                <option value="6" <?php if ($frequency == '6') echo 'selected'; ?>>Monday to Saturday</option>
                                                <option value="5" <?php if ($frequency == '5') echo 'selected'; ?>>Monday to Friday</option>
                                                <option value="2" <?php if ($frequency == '2') echo 'selected'; ?>>Saturday & Sunday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Priority</label>
                                            <input type="number" name="livePriority" class="form-control" min="0" value="<?php echo htmlspecialchars($order, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Timing Before</label>
                                            <input type="number" name="liveTimeBefore" class="form-control" min="0" value="<?php echo htmlspecialchars(($liveTimeBefore === '') ? '5' : $liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="updateDate" class="form-control" value="<?php echo htmlspecialchars($updateDate, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Timing After</label>
                                            <input type="number" name="liveTimeAfter" class="form-control" min="0" value="<?php echo htmlspecialchars(($liveTimeAfter === '') ? '25' : $liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Result On / Off?</label>
                                            <select name="status" class="form-control">
                                                <option disabled="">Select Option</option>
                                                <?php if ($status == '0') { ?>
                                                    <option value="0" selected="">Off (Selected)</option>
                                                    <option value="1">On</option>
                                                <?php } else { ?>
                                                    <option value="1" selected="">On (Selected)</option>
                                                    <option value="0">Off</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Expiry Date</label>
                                            <input type="date" name="expirtyDate" class="form-control" value="<?php echo htmlspecialchars($expirtyDate, ENT_QUOTES, 'UTF-8'); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Jodi Chart Link</label>
                                            <input type="text" name="jodiLink" class="form-control" value="<?php echo htmlspecialchars($jodiLink, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Panel Chart Link</label>
                                            <input type="text" name="panelLink" class="form-control" value="<?php echo htmlspecialchars($panelLink, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="finalAnk" class="form-control" value="<?php echo htmlspecialchars($finalAnk, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        
                                        <div class="col-md-12 col-sm-12">
                                            <button type="submit" class="btn btn-primary" name="updateResult" style="margin-top: 25px">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'footer.php'; ?>

            <div style="position: fixed; right: 10px; bottom: 30px">
                <a href="?remove_sessions" style="background: #981b1b;color: white;padding: 5px;border-radius: 5px;margin: 12px 0px;display: block;float: right;">
                    Remove All Sessions
                </a>
                <form action="cloud-flare-purge-all.php" method="post">
                    <input type="submit" name="clear_cache" value="Clear Cache" style="float: right;">
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
    <script src="assets/js/bootstrap-switch.min.js"></script>
    <script src="assets/js/neon-custom.js"></script>
    <script src="assets/js/neon-demo.js"></script>
</body>
</html>
