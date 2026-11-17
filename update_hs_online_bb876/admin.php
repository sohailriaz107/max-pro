<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include('../config.php');


$filePath = "../txtdb/expiry.txt";

// Check if file exists
if (!file_exists($filePath)) {
    echo "<div class='alert alert-danger text-center'>
        <strong>Error:</strong> expiry.txt file not found.
    </div>";
    exit;
}

// Read the file lines
$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


// Extract market name and expiry date (index 0) for Hs_ online
list($marketName, $expiryDate) = explode("|", trim($lines[0]));

$currentDate = date("Y-m-d");

// Compare dates
if ($currentDate > $expiryDate) {
    echo "<div class='alert alert-danger text-center mb-2'>
        <strong>Access Denied:</strong> {$marketName} has expired on {$expiryDate}.
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
    session_destroy();
    header("Location: index.php");
    exit;
}

// Time labels (52 slots)
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

// Helper: load file lines preserving empty lines and pad to $TOTAL
function load_txt_lines($path, $total) {
    if (!file_exists($path)) {
        return array_fill(0, $total, '');
    }
    // Read without skipping empty lines so positions remain fixed
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return array_fill(0, $total, '');
    }
    // Remove possible trailing single newline that creates an extra empty element at end
    // (file() usually does not add extra, but be safe)
    if (count($lines) > 0 && trim(end($lines)) === '' && count($lines) > $total) {
        // nothing; keep as is — we'll pad/truncate below
    }
    // Ensure exactly $total entries
    if (count($lines) < $total) {
        $lines = array_pad($lines, $total, '');
    } elseif (count($lines) > $total) {
        $lines = array_slice($lines, 0, $total);
    }
    return $lines;
}

// Helper: write lines (preserve empty lines)
function write_txt_lines($path, $lines) {
    // Ensure array has correct ordering and length (no reindex weirdness)
    $content = implode(PHP_EOL, $lines) . PHP_EOL;
    file_put_contents($path, $content, LOCK_EX);
}

// Handle individual input submission (one box at a time)
for ($i = 1; $i <= $TOTAL; $i++) {
    if (isset($_POST["submit_col$i"])) {
        $value = trim($_POST["col$i"]);
        $index = $i - 1;

        // validation: allow empty or pattern 123-4
        if ($value !== "" && !preg_match('/^\d{3}-\d{1}$/', $value)) {
            $errors[] = "Invalid format at box $i ({$time_labels[$index]}): '$value'";
        } else {
            // Load existing lines (preserve empty lines)
            $lines = load_txt_lines($txt_path, $TOTAL);

            // Update the specific index
            $lines[$index] = $value;

            // Write back (preserving positions)
            write_txt_lines($txt_path, $lines);

            // Update DB securely
            $col_name = "col$i";
            if (!preg_match('/^col([1-9]|[1-4][0-9]|5[0-2])$/', $col_name)) {
                $errors[] = "Invalid column name.";
                break;
            }

            $date = date('Y-m-d');

            // Check if row exists
            $stmt = mysqli_prepare($con, "SELECT id FROM $TABLE WHERE game_date = ?");
            mysqli_stmt_bind_param($stmt, "s", $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $query = "UPDATE $TABLE SET `$col_name` = ? WHERE game_date = ?";
                $stmt2 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt2, "ss", $value, $date);
                mysqli_stmt_execute($stmt2);
            } else {
                // Insert a new row with only game_date + this column set
                $query = "INSERT INTO $TABLE (game_date, `$col_name`) VALUES (?, ?)";
                $stmt3 = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt3, "ss", $date, $value);
                mysqli_stmt_execute($stmt3);
            }

            $message = "<div class='alert alert-success'>Result saved for box $i ({$time_labels[$index]}).</div>";
        }
        break; // only one submit button is pressed at a time
    }
}

// Handle clear from modal (clear the txt file only, matching your old behaviour)
if (isset($_POST['confirm_clear_all']) && $_POST['confirm_clear_all'] === 'yes') {
    // Overwrite with $TOTAL empty lines
    $empty = array_fill(0, $TOTAL, '');
    write_txt_lines($txt_path, $empty);
    $results = array_fill(0, $TOTAL, '');
    $message = "<div class='alert alert-success'>Today's result cleared from file.</div>";
}

// Load data from TXT file into $results (preserve empty lines)
$lines = load_txt_lines($txt_path, $TOTAL);
for ($i = 0; $i < $TOTAL; $i++) {
    $results[$i] = isset($lines[$i]) ? $lines[$i] : '';
}
?>
<!DOCTYPE html>
<html>
<head>
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
                        <label class="form-label"><?= $i ?>. <?= $time_labels[$i - 1] ?></label>
                        <div class="input-group">
                            <input type="text" name="col<?= $i ?>" value="<?= htmlspecialchars($results[$i - 1]) ?>"
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
