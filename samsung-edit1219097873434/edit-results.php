<?php
if (isset($_POST['clear_cache'])) {
    $url = "https://api.cloudflare.com/client/v4/zones/ece30536ca22648652b2a53dee7628f9/purge_cache";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "X-Auth-Email: 8dkboss86@gmail.com",
        "Authorization: Bearer BuCssShzJzvs-4BcQpmnq4U7lpuxZI64Z19caU-R",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $data = '{"purge_everything":true}';

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    var_dump($resp);
    echo 'Redirecting...';
    header('refresh:2;url=update-main-market-results.php');
}


if (isset($_GET['remove_sessions'])) {

    $path = session_save_path();
    $files = glob($path . '/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file))
            $file;
        unlink($file); // delete file
    }
    echo 'Redirecting...';
    header('refresh:2;url=edit-results.php');

}

?>

<?php
session_start();
$pageName = 'edit-results';
require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');
$today = date('d-m-Y');

$file = '../txtdb/xzavierJoaquinosia.txt';
$lines = file($file);
if (isset($_POST['updateResult'])) {
    $id = htmlspecialchars(mysqli_real_escape_string($con, $_GET['id']));
    $livePriority = htmlspecialchars(mysqli_real_escape_string($con, $_POST['livePriority']));
    $gName = htmlspecialchars(mysqli_real_escape_string($con, $_POST['gName']));
    $gResult = htmlspecialchars(mysqli_real_escape_string($con, $_POST['gResult']));
    $timeOpen = htmlspecialchars(mysqli_real_escape_string($con, $_POST['timeOpen']));
    $timeClose = htmlspecialchars(mysqli_real_escape_string($con, $_POST['timeClose']));
    $gBgcolor = htmlspecialchars(mysqli_real_escape_string($con, $_POST['gBgcolor']));
    $gFontcolor = htmlspecialchars(mysqli_real_escape_string($con, $_POST['gFontcolor']));
    $gLinecolor = htmlspecialchars(mysqli_real_escape_string($con, $_POST['gLinecolor']));
    $frequency = htmlspecialchars(mysqli_real_escape_string($con, $_POST['frequency']));
    $liveTimeBefore = htmlspecialchars(mysqli_real_escape_string($con, $_POST['liveTimeBefore']));
    $liveTimeAfter = htmlspecialchars(mysqli_real_escape_string($con, $_POST['liveTimeAfter']));
    $updateDate = htmlspecialchars(mysqli_real_escape_string($con, $_POST['updateDate']));
    $status = htmlspecialchars(mysqli_real_escape_string($con, $_POST['status']));
    $expirtyDate = htmlspecialchars(mysqli_real_escape_string($con, $_POST['expirtyDate']));
    $jodiLink = htmlspecialchars(mysqli_real_escape_string($con, $_POST['jodiLink']));
    $panelLink = htmlspecialchars(mysqli_real_escape_string($con, $_POST['panelLink']));
    $data = $livePriority . '|' . $gName . '|' . $gResult . '|' . $timeOpen . '|' . $timeClose . '|' . $gBgcolor . '|' . $gFontcolor . '|' . $gLinecolor . '|' . $frequency . '|' . $liveTimeBefore . '|' . $liveTimeAfter . '|' . $updateDate . '|' . $status . '|' . $expirtyDate . '|' . $jodiLink . '|' . $panelLink . '|';

    foreach ($lines as $index => $line) {
        if ($index == $id) {
            $lines[$index] = $data . "\n";
        }

    }

    $content = implode($lines);
    $insert = file_put_contents('../txtdb/xzavierJoaquinosia.txt', $content);
    $msg = '<div class="alert alert-success">Update Successfully</div>';
} else {
    $msg = '<h2>Edit Result</h2>';
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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body class="page-body skin-red">

    <div class="page-container">
        <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

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
                                list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $gameLinecolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expirtyDate, $jodiLink, $panelLink) = explode("|", $line);
                                ?>
                                <form role="form" class="form-horizontal form-groups-bordered"
                                    action="?id=<?php echo $line_num; ?>" method="post">

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <label>Game Name</label>
                                            <input type="text" name="gName" class="form-control" id="inlineFormInput"
                                                value="<?php echo $gameName ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Result</label>
                                            <input type="text" name="gResult" class="form-control" id="inlineFormInput"
                                                value="<?php echo $gameResult ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Time Open</label>
                                            <input type="time" name="timeOpen" class="form-control" id="inlineFormInput"
                                                value="<?php echo $timeOpen ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Time Close</label>
                                            <input type="time" name="timeClose" class="form-control" id="inlineFormInput"
                                                value="<?php echo $timeClose ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Background Color</label>
                                            <input type="color" name="gBgcolor" class="form-control"
                                                value="<?php echo $gameBgcolor ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Font Color</label>
                                            <input type="color" name="gFontcolor" class="form-control"
                                                value="<?php echo $gameFontcolor ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Line Color</label>
                                            <input type="color" name="gLinecolor" class="form-control"
                                                value="<?php echo $gameLinecolor ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Frequency</label>
                                            <select name="frequency" class="form-control">
                                                <option value="7" <?php if ($frequency == 7) {
                                                    echo 'selected';
                                                } ?>>Daily
                                                </option>
                                                <option value="6" <?php if ($frequency == 6) {
                                                    echo 'selected';
                                                } ?>>Monday to
                                                    Saturday</option>
                                                <option value="5" <?php if ($frequency == 5) {
                                                    echo 'selected';
                                                } ?>>Monday to
                                                    Friday</option>
                                                <option value="2" <?php if ($frequency == 2) {
                                                    echo 'selected';
                                                } ?>>Saturday &
                                                    Sunday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Priority</label>
                                            <input type="number" name="livePriority" class="form-control" min="0"
                                                value="<?php echo $order; ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Timing Before</label>
                                            <input type="number" name="liveTimeBefore" class="form-control" min="0"
                                                value="<?php if ($liveTimeBefore == '') {
                                                    $liveTimeBefore = '5';
                                                }
                                                echo $liveTimeBefore; ?>">
                                            <input type="hidden" name="updateDate" class="form-control" value="<?php
                                            if (strpos(date('h:i A', strtotime($timeOpen)), 'PM') && strpos(date('h:i A', strtotime($timeClose)), 'AM')) {
                                                if (date('H') >= 00 && date('H') <= 03) {
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
                                            <input type="number" name="liveTimeAfter" class="form-control" min="0"
                                                value="<?php if ($liveTimeAfter == '') {
                                                    $liveTimeAfter = '25';
                                                }
                                                echo $liveTimeAfter; ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Live Result On / Off?</label>
                                            <select name="status" class="form-control">
                                                <option disabled="">Select Option</option>
                                                <?php
                                                if ($status == '0') { ?>
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
                                            <input type="date" name="expirtyDate" class="form-control"
                                                value="<?php echo $expirtyDate; ?>" min="<?php echo date('Y-m-d') ?>"
                                                required>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Jodi Chart Link</label>
                                            <input type="text" name="jodiLink" class="form-control" id="inlineFormInput"
                                                value="<?php echo $jodiLink ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <label>Panel Chart Link</label>
                                            <input type="text" name="panelLink" class="form-control" id="inlineFormInput"
                                                value="<?php echo $panelLink ?>">
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <button type="submit" class="btn btn-primary" name="updateResult"
                                                style="margin-top: 25px">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <?php
                            }
                            ?>
                        </div>

                    </div>

                </div>
            </div>


            <?php include 'footer.php' ?>

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


    <!-- Imported scripts on this page -->
    <script src="assets/js/bootstrap-switch.min.js"></script>
    <script src="assets/js/neon-chat.js"></script>


    <!-- JavaScripts initializations and stuff -->
    <script src="assets/js/neon-custom.js"></script>


    <!-- Demo Settings -->
    <script src="assets/js/neon-demo.js"></script>

</body>

</html>