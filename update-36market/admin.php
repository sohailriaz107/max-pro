<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config.php';

$filePath = "../txtdb/expiry.txt";

// Check if file exists
if (!file_exists($filePath)) {
    echo "<div class='alert alert-danger text-center'>
        <strong>Error:</strong> expiry.txt file not found.
    </div>";
    exit;
}

// Read the file lines
$lines = file_exists($filePath) ? (file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

// Extract market name and expiry date (index 1) for 36_market
[$marketName, $expiryDate] = array_pad(explode("|", trim((string)($lines[1] ?? ''))), 2, '');

$currentDate = date("Y-m-d");

// Compare dates
if ($expiryDate !== '' && $currentDate > $expiryDate) {
    $escMarket = htmlspecialchars($marketName, ENT_QUOTES, 'UTF-8');
    $escExpiry = htmlspecialchars($expiryDate, ENT_QUOTES, 'UTF-8');
    echo "<div class='alert alert-danger text-center mb-2'>
        <strong>Access Denied:</strong> {$escMarket} has expired on {$escExpiry}.
    </div>";
    exit;
}

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header("Location: index.php");
    exit;
}

// Time labels
$time_labels = [
    "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM",
    "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM",
    "01:00 PM", "01:15 PM", "01:30 PM", "01:45 PM",
    "02:00 PM", "02:15 PM", "02:30 PM", "02:45 PM",
    "03:00 PM", "03:15 PM", "03:30 PM", "03:45 PM",
    "04:00 PM", "04:15 PM", "04:30 PM", "04:45 PM",
    "05:00 PM", "05:15 PM", "05:30 PM", "05:45 PM",
    "06:00 PM", "06:15 PM", "06:30 PM", "06:45 PM",
    "07:00 PM", "07:15 PM", "07:30 PM", "07:45 PM"
];

$txt_path = '../txtdb/main_bombay_36_results.txt';
$results = array_fill(0, 36, '');
$errors = [];
$message = "";

// Handle individual input submission
for ($i = 1; $i <= 36; $i++) {
    if (isset($_POST["submit_col$i"])) {
        $value = trim((string)($_POST["col$i"] ?? ''));
        $index = $i - 1;

        if ($value !== '' && !preg_match('/^\d{3}-\d{1}$/', $value)) {
            $errors[] = "Invalid format at box $i ({$time_labels[$index]}): '" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "'";
        } else {
            // Update specific line in TXT file (do NOT skip empty lines so slot indices remain fixed)
            if (file_exists($txt_path)) {
                $lines = file($txt_path, FILE_IGNORE_NEW_LINES) ?: [];
            } else {
                $lines = array_fill(0, 36, '');
            }
            if (count($lines) < 36) {
                $lines = array_pad($lines, 36, '');
            }
            $lines[$index] = $value;
            file_put_contents($txt_path, implode(PHP_EOL, $lines), LOCK_EX);

            // Update DB securely
            $col_name = "col$i";
            if (!preg_match('/^col([1-9]|[1-2][0-9]|3[0-6])$/', $col_name)) {
                $errors[] = "Invalid column name.";
                break;
            }

            $date = date('Y-m-d');

            // Check if row exists
            $stmt = mysqli_prepare($con, "SELECT id FROM main_bombay_36 WHERE game_date = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $date);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $hasRow = mysqli_stmt_num_rows($stmt) > 0;
                mysqli_stmt_close($stmt);

                if ($hasRow) {
                    $query = "UPDATE main_bombay_36 SET `$col_name` = ? WHERE game_date = ?";
                    $stmt2 = mysqli_prepare($con, $query);
                    if ($stmt2) {
                        mysqli_stmt_bind_param($stmt2, "ss", $value, $date);
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_close($stmt2);
                    }
                } else {
                    $query = "INSERT INTO main_bombay_36 (game_date, `$col_name`) VALUES (?, ?)";
                    $stmt3 = mysqli_prepare($con, $query);
                    if ($stmt3) {
                        mysqli_stmt_bind_param($stmt3, "ss", $date, $value);
                        mysqli_stmt_execute($stmt3);
                        mysqli_stmt_close($stmt3);
                    }
                }
            }

            $message = "<div class='alert alert-success'>Result saved for box $i ({$time_labels[$index]}).</div>";
        }
        break;
    }
}

// Handle clear from modal
if (isset($_POST['confirm_clear_all']) && $_POST['confirm_clear_all'] === 'yes') {
    file_put_contents($txt_path, '', LOCK_EX);
    $results = array_fill(0, 36, '');
    $message = "<div class='alert alert-success'>Today's result cleared from file.</div>";
}

// Load data from TXT file (preserving empty lines so indices match slot numbers)
if (file_exists($txt_path)) {
    $lines = file($txt_path, FILE_IGNORE_NEW_LINES) ?: [];
    for ($i = 0; $i < count($lines) && $i < 36; $i++) {
        $results[$i] = (string)$lines[$i];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Main Bombay 36 – Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-danger">Main Bombay 36 Bazar – Admin Panel</h3>
            <div>
                <a href="edit_past_results.php" class="btn btn-outline-primary btn-sm">Edit Past</a>
                <a href="admin.php?logout=1" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>

        <?php if (!empty($message)) echo $message; ?>
        <?php if (!empty($errors)) echo "<div class='alert alert-danger'><strong>Errors:</strong><br>" . implode("<br>", $errors) . "</div>"; ?>

        <form method="post" class="bg-white p-4 shadow-sm rounded mb-4">
            <div class="row">
                <?php for ($i = 1; $i <= 36; $i++): ?>
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label"><?= $i ?>. <?= htmlspecialchars($time_labels[$i - 1], ENT_QUOTES, 'UTF-8') ?></label>
                        <div class="input-group">
                            <input type="text" name="col<?= $i ?>" value="<?= htmlspecialchars((string)$results[$i - 1], ENT_QUOTES, 'UTF-8') ?>"
                                class="form-control" pattern="^\d{3}-\d{1}$" title="Format must be 123-4 or leave blank">
                            <button type="submit" name="submit_col<?= $i ?>" class="btn btn-outline-success">Save</button>
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
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <input type="hidden" name="confirm_clear_all" value="yes">
            <button type="submit" class="btn btn-danger">Yes, Clear</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
