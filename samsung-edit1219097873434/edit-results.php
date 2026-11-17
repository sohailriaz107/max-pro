<?php
declare(strict_types=1);

session_start();
$pageName = 'edit-results';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

$today = date('d-m-Y');
$msg = '<h2>Edit Result</h2>';

if (isset($_POST['clear_cache'])) {
    $url = "https://api.cloudflare.com/client/v4/zones/ece30536ca22648652b2a53dee7628f9/purge_cache";
    $curl = curl_init($url);
    if ($curl) {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "X-Auth-Email: 8dkboss86@gmail.com",
            "Authorization: Bearer BuCssShzJzvs-4BcQpmnq4U7lpuxZI64Z19caU-R",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"purge_everything":true}');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_exec($curl);
        curl_close($curl);
    }
    $msg = '<div class="alert alert-success">Cache Purge Requested. Redirecting...</div>';
    header('refresh:2;url=edit-results.php');
}

if (isset($_GET['remove_sessions'])) {
    $path = session_save_path();
    if ($path !== '' && is_dir($path)) {
        $files = glob(rtrim($path, '/\\') . '/*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }
    $msg = '<div class="alert alert-success">Sessions Removed. Redirecting...</div>';
    header('refresh:2;url=edit-results.php');
}

$file = '../txtdb/xzavierJoaquinosia.txt';
$lines = file_exists($file) ? (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

if (isset($_POST['updateResult'])) {
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : -1;

    $sanitizeField = function(string $val): string {
        return str_replace(["|", "\r", "\n"], '', trim($val));
    };

    $livePriority   = $sanitizeField((string)($_POST['livePriority'] ?? ''));
    $gName          = $sanitizeField((string)($_POST['gName'] ?? ''));
    $gResult        = $sanitizeField((string)($_POST['gResult'] ?? ''));
    $timeOpen       = $sanitizeField((string)($_POST['timeOpen'] ?? ''));
    $timeClose      = $sanitizeField((string)($_POST['timeClose'] ?? ''));
    $gBgcolor       = $sanitizeField((string)($_POST['gBgcolor'] ?? ''));
    $gFontcolor     = $sanitizeField((string)($_POST['gFontcolor'] ?? ''));
    $gLinecolor     = $sanitizeField((string)($_POST['gLinecolor'] ?? ''));
    $frequency      = $sanitizeField((string)($_POST['frequency'] ?? ''));
    $liveTimeBefore = $sanitizeField((string)($_POST['liveTimeBefore'] ?? ''));
    $liveTimeAfter  = $sanitizeField((string)($_POST['liveTimeAfter'] ?? ''));
    $updateDate     = $sanitizeField((string)($_POST['updateDate'] ?? ''));
    $status         = $sanitizeField((string)($_POST['status'] ?? ''));
    $expirtyDate    = $sanitizeField((string)($_POST['expirtyDate'] ?? ''));
    $jodiLink       = $sanitizeField((string)($_POST['jodiLink'] ?? ''));
    $panelLink      = $sanitizeField((string)($_POST['panelLink'] ?? ''));

    $data = $livePriority . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDate . '|' . $status . '|' . $expirtyDate . '|' . $jodiLink . '|' . $panelLink . '|';

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
                            foreach ($lines as $line_num => $line) {
                                $parts = array_pad(explode("|", trim((string)$line)), 16, '');
                                $order          = $parts[0];
                                $gameName       = $parts[1];
                                $gameResult     = $parts[2];
                                $timeOpen       = $parts[3];
                                $timeClose      = $parts[4];
                                $gameBgcolor    = $parts[5];
                                $gameFontcolor  = $parts[6];
                                $gameLinecolor  = $parts[7];
                                $frequency      = $parts[8];
                                $liveTimeBefore = $parts[9];
                                $liveTimeAfter  = $parts[10];
                                $status         = $parts[12];
                                $expirtyDate    = $parts[13];
                                $jodiLink       = $parts[14];
                                $panelLink      = $parts[15];
                            ?>
                                <form role="form" class="form-horizontal form-groups-bordered" action="?id=<?php echo (int)$line_num; ?>" method="post">
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
                                                <option value="7" <?php if ($frequency === '7') echo 'selected'; ?>>Daily</option>
                                                <option value="6" <?php if ($frequency === '6') echo 'selected'; ?>>Monday to Saturday</option>
                                                <option value="5" <?php if ($frequency === '5') echo 'selected'; ?>>Monday to Friday</option>
                                                <option value="2" <?php if ($frequency === '2') echo 'selected'; ?>>Saturday &amp; Sunday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Priority</label>
                                            <input type="number" name="livePriority" class="form-control" min="0" value="<?php echo htmlspecialchars($order, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Timing Before</label>
                                            <input type="number" name="liveTimeBefore" class="form-control" min="0" value="<?php echo htmlspecialchars($liveTimeBefore === '' ? '5' : $liveTimeBefore, ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="updateDate" class="form-control" value="<?php
                                            $toTime = strtotime($timeOpen);
                                            $tcTime = strtotime($timeClose);
                                            if ($toTime !== false && $tcTime !== false && strpos(date('h:i A', $toTime), 'PM') && strpos(date('h:i A', $tcTime), 'AM')) {
                                                $hour = (int)date('H');
                                                if ($hour >= 0 && $hour <= 3) {
                                                    echo date("d-m-Y", strtotime('-1 days'));
                                                } else {
                                                    echo $today;
                                                }
                                            } else {
                                                echo $today;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Timing After</label>
                                            <input type="number" name="liveTimeAfter" class="form-control" min="0" value="<?php echo htmlspecialchars($liveTimeAfter === '' ? '25' : $liveTimeAfter, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Result On / Off?</label>
                                            <select name="status" class="form-control">
                                                <option disabled="">Select Option</option>
                                                <?php if ($status === '0') { ?>
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
                <form accept="" method="post">
                    <input type="submit" name="clear_cache" value="Clear Cache" style="float: right;">
                </form>
            </div>
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
