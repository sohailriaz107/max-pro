<?php

date_default_timezone_set('Asia/Kolkata');

include('../config.php');

$TABLE = "mumbai_standard_single_khech";

$txt_path = '../txtdb/mumbai_standard_single_khech.txt';

function generateSlotTimes($start = '11:00', $end = '19:00', $interval = 5)
{
    $slots = [];

    $current = strtotime($start);

    $endTime = strtotime($end);

    while ($current < $endTime) {

        $runTime = strtotime('+4 minutes', $current);

        $slots[] = date('H:i', $runTime);

        $current = strtotime("+{$interval} minutes", $current);
    }

    return $slots;
}

function generateDisplayTimes($start = '11:00', $end = '19:00', $interval = 5)
{
    $times = [];

    $current = strtotime($start);

    $endTime = strtotime($end);

    while ($current < $endTime) {

        $times[] = date('h:i A', $current);

        $current = strtotime("+{$interval} minutes", $current);
    }

    return $times;
}

$slot_times = generateSlotTimes();

$display_times = generateDisplayTimes();

$TOTAL_SLOTS = count($slot_times);

$possible_results = ['0','1','2','3','4','5','6','7','8','9'];

$today = date('Y-m-d');

$current_time = date('H:i');

if (!file_exists($txt_path)) {

    file_put_contents($txt_path, '');
}

$results = file($txt_path, FILE_IGNORE_NEW_LINES);

if (count($results) < $TOTAL_SLOTS) {

    $results = array_pad($results, $TOTAL_SLOTS, '');
}

foreach ($slot_times as $index => $time) {

    if ($current_time == $time) {

        if (empty($results[$index])) {

            $random_result = $possible_results[array_rand($possible_results)];

            $results[$index] = $random_result;

            file_put_contents(
                $txt_path,
                implode(PHP_EOL, $results)
            );

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
                    "UPDATE $TABLE
                    SET results=?
                    WHERE game_date=?"
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

            echo "Slot ".($index + 1)." Result Generated Successfully";

        } else {

            echo "Slot already filled";
        }

        exit;
    }
}

echo "No Slot Matched";