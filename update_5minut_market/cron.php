<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');
require_once '../config.php';

$TABLE = "mumbai_standard_single_khech";
$txt_path = '../txtdb/mumbai_standard_single_khech.txt';

function generateSlotTimes(string $start = '11:00', string $end = '19:00', int $interval = 5): array
{
    $slots = [];

    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current !== false && $endTime !== false && $current < $endTime) {
        $runTime = strtotime('+4 minutes', $current);
        if ($runTime !== false) {
            $slots[] = date('H:i', $runTime);
        }
        $current = strtotime("+{$interval} minutes", $current);
    }

    return $slots;
}

function generateDisplayTimes(string $start = '11:00', string $end = '19:00', int $interval = 5): array
{
    $times = [];

    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current !== false && $endTime !== false && $current < $endTime) {
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
    @file_put_contents($txt_path, '', LOCK_EX);
}

$results = file_exists($txt_path) ? (file($txt_path, FILE_IGNORE_NEW_LINES) ?: []) : [];

if (count($results) < $TOTAL_SLOTS) {
    $results = array_pad($results, $TOTAL_SLOTS, '');
}

foreach ($slot_times as $index => $time) {

    if ($current_time === $time) {

        if (empty($results[$index])) {

            $random_result = $possible_results[array_rand($possible_results)];
            $results[$index] = $random_result;

            file_put_contents(
                $txt_path,
                implode(PHP_EOL, $results),
                LOCK_EX
            );

            $json_results = json_encode($results);

            $check = mysqli_prepare(
                $con,
                "SELECT id FROM $TABLE WHERE game_date=?"
            );

            if ($check) {
                mysqli_stmt_bind_param($check, "s", $today);
                mysqli_stmt_execute($check);
                mysqli_stmt_store_result($check);
                $hasRow = mysqli_stmt_num_rows($check) > 0;
                mysqli_stmt_close($check);

                if ($hasRow) {
                    $stmt = mysqli_prepare(
                        $con,
                        "UPDATE $TABLE SET results=? WHERE game_date=?"
                    );
                    if ($stmt) {
                        mysqli_stmt_bind_param(
                            $stmt,
                            "ss",
                            $json_results,
                            $today
                        );
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $stmt = mysqli_prepare(
                        $con,
                        "INSERT INTO $TABLE(game_date, results) VALUES(?, ?)"
                    );
                    if ($stmt) {
                        mysqli_stmt_bind_param(
                            $stmt,
                            "ss",
                            $today,
                            $json_results
                        );
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }

                echo "Slot ".($index + 1)." Result Generated Successfully";
            }
        } else {
            echo "Slot already filled";
        }

        exit;
    }
}

echo "No Slot Matched";
