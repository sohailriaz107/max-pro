<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$TABLE = "mumbai_standard_single_khech";

function generateTimeSlots(string $start = '11:00', string $end = '19:00', int $interval = 5): array
{
    $slots = [];
    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current !== false && $endTime !== false && $current < $endTime) {
        $slots[] = date('h:i A', $current);
        $current = strtotime("+{$interval} minutes", $current);
    }

    return $slots;
}

$time_labels = generateTimeSlots();
$TOTAL_SLOTS = count($time_labels);
$results = array_fill(0, $TOTAL_SLOTS, '');
$message = '';
$selected_date = '';

if (isset($_POST['load_date'])) {
    $raw_date = trim((string)($_POST['game_date'] ?? ''));
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw_date)) {
        $message = "<div class='alert alert-danger'>Invalid Date Format</div>";
    } else {
        $selected_date = $raw_date;
        $stmt = mysqli_prepare($con, "SELECT results FROM mumbai_standard_single_khech WHERE game_date=? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $selected_date);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if ($res && mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                $db_results = json_decode((string)($row['results'] ?? ''), true);

                if (is_array($db_results)) {
                    $results = array_pad($db_results, $TOTAL_SLOTS, '');
                }

                $message = "<div class='alert alert-success'>Results Loaded Successfully</div>";
            } else {
                $message = "<div class='alert alert-danger'>No Results Found</div>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if (isset($_POST['update_results'])) {
    $raw_date = trim((string)($_POST['game_date'] ?? ''));
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw_date)) {
        $message = "<div class='alert alert-danger'>Invalid Date Format</div>";
    } else {
        $selected_date = $raw_date;
        $new_results = [];
        $col_inputs = isset($_POST['col']) && is_array($_POST['col']) ? $_POST['col'] : [];

        for ($i = 0; $i < $TOTAL_SLOTS; $i++) {
            $value = trim((string)($col_inputs[$i] ?? ''));

            if ($value !== '' && !preg_match('/^[0-9]$/', $value)) {
                $message = "<div class='alert alert-danger'>Invalid Value at Slot " . ($i + 1) . "</div>";
                break;
            }

            $new_results[] = $value;
        }

        if (empty($message)) {
            $json_results = json_encode($new_results, JSON_UNESCAPED_UNICODE);
            $stmt = mysqli_prepare($con, "UPDATE mumbai_standard_single_khech SET results=? WHERE game_date=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $json_results, $selected_date);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $results = $new_results;
                $message = "<div class='alert alert-success'>Results Updated Successfully</div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to prepare update query</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Past Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#f5f5f5;
        }
        .result-box{
            background:#fff;
            padding:12px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            height:100%;
        }
        .time-label{
            font-size:13px;
            font-weight:bold;
            color:#dc3545;
            margin-bottom:8px;
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

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Edit Past Results</h3>
        <a href="admin.php" class="btn btn-dark btn-sm">Back To Admin</a>
    </div>

    <?= $message ?>

    <form method="post" class="bg-white p-3 rounded shadow-sm mb-4">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Select Date</label>
                <input
                    type="date"
                    name="game_date"
                    value="<?= htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') ?>"
                    class="form-control"
                    required
                >
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="load_date" class="btn btn-danger w-100">
                    LOAD
                </button>
            </div>
        </div>
    </form>

    <?php if (!empty($selected_date)): ?>
    <form method="post">
        <input
            type="hidden"
            name="game_date"
            value="<?= htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="row">
            <?php foreach($time_labels as $index => $time): ?>
                <div class="col-6 col-md-3 col-lg-2 mb-3">
                    <div class="result-box">
                        <div class="time-label">
                            <?= htmlspecialchars((string)$time, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <input
                            type="text"
                            name="col[<?= (int)$index ?>]"
                            maxlength="1"
                            class="form-control slot-input"
                            value="<?= htmlspecialchars((string)($results[$index] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        >
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4 mb-5">
            <button type="submit" name="update_results" class="btn btn-success btn-lg px-5">
                UPDATE RESULTS
            </button>
        </div>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
