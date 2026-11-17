<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

include('../config.php');

date_default_timezone_set('Asia/Kolkata');

$TABLE = "mumbai_standard_single_khech";

$cache_file = '../cache/mumbai-standard-single-khech.html';

$cache_time = 60;

if (
    file_exists($cache_file)
    &&
    (time() - filemtime($cache_file)) < $cache_time
) {
    readfile($cache_file);
    exit;
}

ob_start();

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

$query = mysqli_query(
    $con,
    "SELECT *
    FROM $TABLE
    ORDER BY game_date DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>
        Mumbai Standard Single Khech Results
    </title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body{
            background:#f4f4f4;
            font-family:Arial,sans-serif;
        }

        .main-box{
            background:#fff;
            border-radius:10px;
            overflow:hidden;
            margin-bottom:25px;
            box-shadow:0 2px 12px rgba(0,0,0,0.08);
        }

        .date-header{
            background:#dc3545;
            color:#fff;
            text-align:center;
            padding:12px;
            font-size:18px;
            font-weight:bold;
        }

        .result-grid{
            display:flex;
            flex-wrap:wrap;
            padding:10px;
        }

        .result-item{
            width:16.66%;
            padding:6px;
        }

        .result-inner{
            border:1px solid #ddd;
            border-radius:8px;
            padding:10px 5px;
            text-align:center;
            background:#fafafa;
        }

        .time-label{
            font-size:11px;
            font-weight:bold;
            color:#666;
            margin-bottom:5px;
        }

        .result-value{
            font-size:22px;
            font-weight:bold;
            color:#dc3545;
        }

        @media(max-width:768px){

            .result-item{
                width:25%;
            }

            .result-value{
                font-size:18px;
            }

        }

        @media(max-width:480px){

            .result-item{
                width:33.33%;
            }

            .time-label{
                font-size:10px;
            }

        }

    </style>

</head>

<body>

<div class="container-fluid py-3">

    <h2 class="text-center mb-4">
        Mumbai Standard Single Khech Results
    </h2>

    <?php while($row = mysqli_fetch_assoc($query)): ?>

        <?php

        $results = json_decode($row['results'], true);

        if (!is_array($results)) {
            $results = [];
        }

        $results = array_pad(
            $results,
            $TOTAL_SLOTS,
            ''
        );

        ?>

        <div class="main-box">

            <div class="date-header">

                <?= date(
                    'd M Y',
                    strtotime($row['game_date'])
                ) ?>

            </div>

            <div class="result-grid">

                <?php foreach($time_labels as $index => $time): ?>

                    <div class="result-item">

                        <div class="result-inner">

                            <div class="time-label">

                                <?= $time ?>

                            </div>

                            <div class="result-value">

                                <?= $results[$index] !== ''
                                    ? htmlspecialchars($results[$index])
                                    : '*'
                                ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endwhile; ?>

</div>

</body>
</html>

<?php

$html = ob_get_contents();

ob_end_flush();

file_put_contents(
    $cache_file,
    $html
);