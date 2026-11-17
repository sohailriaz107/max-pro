<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

$filePath = "../txtdb/expiry.txt";

if (!file_exists($filePath)) {
    echo "<div class='alert alert-danger text-center'><strong>Error:</strong> expiry.txt file not found.</div>";
    exit;
}

$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false || empty($lines)) {
    echo "<div class='alert alert-danger text-center'><strong>Error:</strong> expiry.txt file is empty.</div>";
    exit;
}

$parts = explode("|", trim((string)($lines[0] ?? '')));
$marketName = $parts[0] ?? '';
$expiryDate = $parts[1] ?? '';

$currentDate = date("Y-m-d");

if ($expiryDate !== '' && $currentDate > $expiryDate) {
    echo "<div class='alert alert-danger text-center mb-2'><strong>Access Denied:</strong> " . htmlspecialchars($marketName, ENT_QUOTES, 'UTF-8') . " has expired on " . htmlspecialchars($expiryDate, ENT_QUOTES, 'UTF-8') . ".</div>";
    exit;
}

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Time labels (48 slots)
$time_labels = [
    "9:15 AM","9:30 AM","9:45 AM","10:00 AM","10:15 AM","10:30 AM","10:45 AM",
    "11:00 AM","11:15 AM","11:30 AM","11:45 AM",
    "12:00 PM","12:15 PM","12:30 PM","12:45 PM",
    "1:00 PM","1:15 PM","1:30 PM","1:45 PM",
    "2:00 PM","2:15 PM","2:30 PM","2:45 PM",
    "3:00 PM","3:15 PM","3:30 PM","3:45 PM",
    "4:00 PM","4:15 PM","4:30 PM","4:45 PM",
    "5:00 PM","5:15 PM","5:30 PM","5:45 PM",
    "6:00 PM","6:15 PM","6:30 PM","6:45 PM",
    "7:00 PM","7:15 PM","7:30 PM","7:45 PM",
    "8:00 PM","8:15 PM","8:30 PM","8:45 PM",
    "9:00 PM"
];

$TOTAL = 48;
$TABLE = 'hs_online_bb_52';
$txt_path = '../txtdb/hs_online_bb_52_results.txt';
$results = array_fill(0, $TOTAL, '');
$errors = [];
$message = "";

function load_txt_lines(string $path, int $total): array {
    if (!file_exists($path)) {
        return array_fill(0, $total, '');
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return array_fill(0, $total, '');
    }
    if (count($lines) < $total) {
        $lines = array_pad($lines, $total, '');
    } elseif (count($lines) > $total) {
        $lines = array_slice($lines, 0, $total);
    }
    return $lines;
}

function write_txt_lines(string $path, array $lines): void {
    $content = implode(PHP_EOL, $lines) . PHP_EOL;
    file_put_contents($path, $content, LOCK_EX);
}

// Handle individual input submission (one box at a time)
for ($i = 1; $i <= $TOTAL; $i++) {
    if (isset($_POST["submit_col$i"])) {
        $value = trim((string)($_POST["col$i"] ?? ''));
        $value = str_replace(["\r", "\n"], '', $value);
        $index = $i - 1;

        if ($value !== "" && !preg_match('/^\d{3}-\d{1}$/', $value)) {
            $errors[] = "Invalid format at box $i (" . htmlspecialchars($time_labels[$index] ?? '', ENT_QUOTES, 'UTF-8') . "): '" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "'";
        } else {
            $lines = load_txt_lines($txt_path, $TOTAL);
            $lines[$index] = $value;
            write_txt_lines($txt_path, $lines);

            $col_name = "col$i";
            if (!preg_match('/^col([1-9]|[1-4][0-9]|5[0-2])$/', $col_name)) {
                $errors[] = "Invalid column name.";
                break;
            }

            $date = date('Y-m-d');

            $check_stmt = mysqli_prepare($con, "SELECT id FROM hs_online_bb_52 WHERE game_date = ? LIMIT 1");
            if ($check_stmt) {
                mysqli_stmt_bind_param($check_stmt, "s", $date);
                mysqli_stmt_execute($check_stmt);
                mysqli_stmt_store_result($check_stmt);
                $has_row = (mysqli_stmt_num_rows($check_stmt) > 0);
                mysqli_stmt_close($check_stmt);

                if ($has_row) {
                    $stmt2 = mysqli_prepare($con, "UPDATE hs_online_bb_52 SET `{$col_name}` = ? WHERE game_date = ?");
                    if ($stmt2) {
                        mysqli_stmt_bind_param($stmt2, "ss", $value, $date);
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_close($stmt2);
                    }
                } else {
                    $stmt3 = mysqli_prepare($con, "INSERT INTO hs_online_bb_52 (game_date, `{$col_name}`) VALUES (?, ?)");
                    if ($stmt3) {
                        mysqli_stmt_bind_param($stmt3, "ss", $date, $value);
                        mysqli_stmt_execute($stmt3);
                        mysqli_stmt_close($stmt3);
                    }
                }
            }

            $message = "<div class='alert alert-success'>Result saved for box $i (" . htmlspecialchars($time_labels[$index] ?? '', ENT_QUOTES, 'UTF-8') . ").</div>";
        }
        break;
    }
}

// Handle clear from modal
if (isset($_POST['confirm_clear_all']) && $_POST['confirm_clear_all'] === 'yes') {
    $empty = array_fill(0, $TOTAL, '');
    write_txt_lines($txt_path, $empty);
    $results = array_fill(0, $TOTAL, '');
    $message = "<div class='alert alert-success'>Today's result cleared from file.</div>";
}

$lines = load_txt_lines($txt_path, $TOTAL);
for ($i = 0; $i < $TOTAL; $i++) {
    $results[$i] = isset($lines[$i]) ? $lines[$i] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>HS Online BB 15 Minutes – Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-danger">HS Online BB 15 Minutes (48 Slots) – Admin Panel</h3>
            <div>
                <a href="edit_past_results.php" class="btn btn-outline-primary btn-sm">Edit Past</a>
                <a href="admin.php?logout=1" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>

        <?php if (!empty($message)) echo $message; ?>
        <?php if (!empty($errors)) echo "<div class='alert alert-danger'><strong>Errors:</strong><br>" . implode("<br>", $errors) . "</div>"; ?>

        <form method="post" class="bg-white p-4 shadow-sm rounded mb-4">
            <div class="row">
                <?php for ($i = 1; $i <= $TOTAL; $i++): ?>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label"><?= (int)$i ?>. <?= htmlspecialchars($time_labels[$i - 1] ?? '', ENT_QUOTES, 'UTF-8') ?></label>
                        <div class="input-group">
                            <input type="text" name="col<?= (int)$i ?>" value="<?= htmlspecialchars((string)($results[$i - 1] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                class="form-control" pattern="^\d{3}-\d{1}$" title="Format must be 123-4 or leave blank">
                            <button type="submit" name="submit_col<?= (int)$i ?>" class="btn btn-outline-success">Save</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </form>

        <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#confirmClearModal">
                Clear All?
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmClearModal" tabindex="-1" aria-labelledby="confirmClearModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmClearModalLabel">Confirm Clear</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to clear all the results of today?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn secondary" data-bs-dismiss="modal">Cancel</button>
            <input type="hidden" name="confirm_clear_all" value="yes">
            <button type="submit" class="btn btn-danger">Yes, Clear</button>
          </div>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
