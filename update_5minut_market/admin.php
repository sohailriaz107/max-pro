<?php
session_start();
include('../config.php');

date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$TABLE = "mumbai_standard_single_khech";
$txt_path = '../txtdb/mumbai_standard_single_khech.txt';

function generateTimeSlots($start = '11:00', $end = '19:00', $interval = 5)
{
    $slots = [];

    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current < $endTime) {
        $slots[] = date('h:i A', $current);
        $current = strtotime("+{$interval} minutes", $current);
    }

    return $slots;
}

$time_labels = generateTimeSlots();
$TOTAL_SLOTS = count($time_labels);

if (!file_exists($txt_path)) {
    file_put_contents($txt_path, '');
}

$results = file($txt_path, FILE_IGNORE_NEW_LINES);

if (count($results) < $TOTAL_SLOTS) {
    $results = array_pad($results, $TOTAL_SLOTS, '');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    for ($i = 0; $i < $TOTAL_SLOTS; $i++) {

        $value = trim($_POST['col'][$i] ?? '');

        if ($value !== '' && !preg_match('/^[0-9]$/', $value)) {
            $message = "
            <div class='alert alert-danger'>
                Invalid value at slot ".($i + 1)."
            </div>";
            break;
        }

        $results[$i] = $value;
    }

    if (empty($message)) {

        file_put_contents(
            $txt_path,
            implode(PHP_EOL, $results)
        );

        $today = date('Y-m-d');

        $json_results = json_encode($results);

        $check = mysqli_prepare(
            $con,
            "SELECT id FROM $TABLE WHERE game_date=?"
        );

        mysqli_stmt_bind_param($check, "s", $today);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            $stmt = mysqli_prepare(
                $con,
                "UPDATE $TABLE SET results=? WHERE game_date=?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $json_results,
                $today
            );

        } else {

            $stmt = mysqli_prepare(
                $con,
                "INSERT INTO $TABLE(game_date, results)
                VALUES(?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $today,
                $json_results
            );
        }

        mysqli_stmt_execute($stmt);

        $message = "
        <div class='alert alert-success'>
            Results Updated Successfully
        </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Mumbai Standard Single Khech Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f5f5f5;
        }

        .result-box{
            border-radius:10px;
            background:#fff;
            padding:12px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            height:100%;
        }

        .time-label{
            font-size:13px;
            font-weight:bold;
            margin-bottom:8px;
            color:#dc3545;
        }

        .slot-input{
            text-align:center;
            font-size:20px;
            font-weight:bold;
            height:55px;
        }

    </style>

</head>

<body>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            Mumbai Standard Single Khech
        </h3>

        <div>

            <a href="edit_past_results.php"
               class="btn btn-dark btn-sm">
                Edit Past Results
            </a>

            <a href="?logout=1"
               class="btn btn-danger btn-sm">
                Logout
            </a>

        </div>

    </div>

    <?= $message ?>

    <form method="post">

        <div class="row">

            <?php foreach($time_labels as $index => $time): ?>

                <div class="col-6 col-md-3 col-lg-2 mb-3">

                    <div class="result-box">

                        <div class="time-label">
                            <?= $time ?>
                        </div>

                        <input
                            type="text"
                            name="col[<?= $index ?>]"
                            maxlength="1"
                            class="form-control slot-input"
                            value="<?= htmlspecialchars($results[$index]) ?>"
                        >

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="text-center mt-4">

            <button
                type="submit"
                class="btn btn-danger btn-lg px-5">

                SAVE RESULTS

            </button>

        </div>

    </form>

</div>

</body>
</html>