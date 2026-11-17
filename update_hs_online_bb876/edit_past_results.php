<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Time labels for HS Online BB 15 Minutes (48 slots)
$time_labels = [
    "09:15 AM","09:30 AM","09:45 AM","10:00 AM",
    "10:15 AM","10:30 AM","10:45 AM","11:00 AM",
    "11:15 AM","11:30 AM","11:45 AM","12:00 PM",
    "12:15 PM","12:30 PM","12:45 PM","01:00 PM",
    "01:15 PM","01:30 PM","01:45 PM","02:00 PM",
    "02:15 PM","02:30 PM","02:45 PM","03:00 PM",
    "03:15 PM","03:30 PM","03:45 PM","04:00 PM",
    "04:15 PM","04:30 PM","04:45 PM","05:00 PM",
    "05:15 PM","05:30 PM","05:45 PM","06:00 PM",
    "06:15 PM","06:30 PM","06:45 PM","07:00 PM",
    "07:15 PM","07:30 PM","07:45 PM","08:00 PM",
    "08:15 PM","08:30 PM","08:45 PM","09:00 PM"
];

$TOTAL_SLOTS = 48;
$TABLE_NAME = 'hs_online_bb_52';

$results = array_fill(0, $TOTAL_SLOTS, '');
$message = "";
$selected_date = "";

// Load existing data
if (isset($_POST['load_date'])) {
    $selected_date = trim((string)($_POST['game_date'] ?? ''));

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
        $stmt = mysqli_prepare($con, "SELECT * FROM hs_online_bb_52 WHERE game_date = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $selected_date);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                for ($i = 1; $i <= $TOTAL_SLOTS; $i++) {
                    $results[$i - 1] = (string)($row["col$i"] ?? '');
                }
                $message = "<div class='alert alert-info'>Results loaded for " . htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') . ".</div>";
            } else {
                $message = "<div class='alert alert-warning'>No results found for " . htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') . ".</div>";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = "<div class='alert alert-danger'>Invalid date format.</div>";
    }
}

// Update results securely
if (isset($_POST['update_results'])) {
    $selected_date = trim((string)($_POST['game_date'] ?? ''));

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
        $message = "<div class='alert alert-danger'>Invalid date format.</div>";
    } else {
        $update_parts = [];
        $values = [];

        for ($i = 1; $i <= $TOTAL_SLOTS; $i++) {
            $col = "col$i";
            $val = trim((string)($_POST[$col] ?? ''));

            if (!empty($val) && !preg_match('/^\d{3}-\d{1}$/', $val)) {
                $message = "<div class='alert alert-danger'>Invalid format in Box $i: '" . htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . "'. Format should be 123-4 or blank.</div>";
                break;
            }

            $update_parts[] = "`$col` = ?";
            $values[] = $val === '' ? null : $val;
            $results[$i - 1] = $val;
        }

        if (empty($message)) {
            $types = str_repeat("s", count($values));
            $query = "UPDATE hs_online_bb_52 SET " . implode(", ", $update_parts) . " WHERE game_date = ?";
            $values[] = $selected_date;
            $types .= "s";

            $stmt = mysqli_prepare($con, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, $types, ...$values);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $message = "<div class='alert alert-success'>Results updated successfully for " . htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') . ".</div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to prepare update query.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Past Results – HS Online BB 15 Minutes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-danger">Edit Past Results – HS Online BB 15 Minutes</h3>
            <a href="admin.php" class="btn btn-outline-secondary btn-sm">Back to Admin Panel</a>
        </div>

        <?php if (!empty($message)) echo $message; ?>

        <!-- Date Selection Form -->
        <form method="post" class="bg-white p-3 shadow-sm rounded mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-sm-6">
                    <label class="form-label">Select Game Date</label>
                    <input type="date" name="game_date" value="<?= htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') ?>" class="form-control" required>
                </div>
                <div class="col-sm-3">
                    <button type="submit" name="load_date" class="btn btn-danger">Load Results</button>
                </div>
            </div>
        </form>

        <!-- Results Editor -->
        <?php if (!empty($selected_date) && $results !== array_fill(0, $TOTAL_SLOTS, '')): ?>
            <form method="post" class="bg-white p-4 shadow-sm rounded mb-4">
                <input type="hidden" name="game_date" value="<?= htmlspecialchars($selected_date, ENT_QUOTES, 'UTF-8') ?>">
                <div class="row">
                    <?php for ($i = 1; $i <= $TOTAL_SLOTS; $i++): ?>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><?= (int)$i ?>. <?= htmlspecialchars($time_labels[$i - 1] ?? '', ENT_QUOTES, 'UTF-8') ?></label>
                            <input type="text" name="col<?= (int)$i ?>" value="<?= htmlspecialchars((string)($results[$i - 1] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                   class="form-control" pattern="^\d{3}-\d{1}$" title="Format must be 123-4 or leave blank">
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" name="update_results" class="btn btn-success">Update Results</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
