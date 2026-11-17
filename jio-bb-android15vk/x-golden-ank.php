
<?php
session_start();

$pageName = 'x-golden-ank';

require('sessions.php');
require "../config.php";
require "illegal.php";
require('functions.php');

// Access restriction
if (isset($_SESSION['firstname'])) {
    if (
        $_SESSION['firstname'] != 'vk' &&
        $_SESSION['firstname'] != 'rohanji'
    ) {
        $_SESSION['illegal'] = 'Illegal Activity Detected';
        header("Location: index.php");
        exit;
    }
}

$file = '../txtdb/x-golden-ank.txt';

// Field names
$fields = [
    'Ank',
    'Jodi',
    'Patti',
    'Motor',
    'Cycle',
    'FM'
];

// Placeholder values only; these are NOT saved as defaults.
$defaultValues = [
    '7-8-9-3',
    '46-15-57-26',
    '123-456-789-456',
    '798798',
    '2345',
    '21341234'
];

$msg = '';

// Update TXT file
if (isset($_POST['updateMotor'])) {

    $values = [];

    foreach ($fields as $index => $field) {
        $value = $_POST['motor'][$index] ?? '';

        // Keep each field on a single line
        $value = str_replace(["\r", "\n"], '', $value);

        $values[] = $value;
    }

    // Save exactly 6 lines in the specified order
    $data = implode("\n", $values) . "\n";

    if (is_file($file) && is_writable($file)) {

        $write = file_put_contents($file, $data, LOCK_EX);

        if ($write !== false) {
            $msg = '<div class="alert alert-success">
                Game Updated Successfully!
            </div>';
        } else {
            $msg = '<div class="alert alert-danger">
                Unable to update file. Please check file permissions.
            </div>';
        }

    } else {
        $msg = '<div class="alert alert-danger">
            TXT file does not exist or is not writable.
        </div>';
    }
}

// Read actual values from TXT file
$currentValues = [];

if (is_file($file) && is_readable($file)) {
    $currentValues = file($file, FILE_IGNORE_NEW_LINES);
}

// Missing lines should appear as empty inputs
for ($i = 0; $i < count($fields); $i++) {
    if (!isset($currentValues[$i])) {
        $currentValues[$i] = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en-in">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Neon Admin Panel">
    <meta name="author" content="">

    <link rel="icon" href="assets/images/favicon.ico">

    <title>Update X Golden Ank</title>

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

        <hr>

        <h2>Update X Golden Ank</h2>

        <?php echo $msg; ?>

        <br>

        <div class="row">
            <div class="col-md-12">

                <form
                    role="form"
                    class="form-horizontal form-groups-bordered"
                    action=""
                    method="post"
                >

                    <?php foreach ($fields as $index => $field) { ?>

                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">

                                <label>
                                    <?php
                                    echo htmlspecialchars(
                                        $field,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </label>

                                <input
                                    type="text"
                                    name="motor[]"
                                    class="form-control"
                                    value="<?php
                                        echo htmlspecialchars(
                                            $currentValues[$index],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    ?>"
                                    placeholder="<?php
                                        echo htmlspecialchars(
                                            $defaultValues[$index],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                    ?>"
                                    autocomplete="off"
                                >

                            </div>
                        </div>

                    <?php } ?>

                    <div class="form-group">
                        <div class="col-md-12">

                            <button
                                type="submit"
                                class="btn btn-primary"
                                name="updateMotor"
                                value="1"
                                style="margin-top: 10px;"
                            >
                                UPDATE
                            </button>

                        </div>
                    </div>

                </form>

            </div>
        </div>

        <?php include 'footer.php'; ?>

    </div>
</div>

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
