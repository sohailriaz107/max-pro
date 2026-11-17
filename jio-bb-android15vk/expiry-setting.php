<?php 
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'expiry-setting';
require_once __DIR__ . '/sessions.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/illegal.php';
require_once __DIR__ . '/functions.php';

$filePath = __DIR__ . "/../txtdb/expiry.txt";
$msg = "";

// Helper to read markets safely
$readMarkets = function(string $path): array {
    $markets = [];
    if (file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $parts = explode('|', trim($line));
                if (count($parts) >= 2) {
                    $markets[] = ['name' => trim($parts[0]), 'expiry' => trim($parts[1])];
                }
            }
        }
    }
    return $markets;
};

// Step 1: Read current expiry data
$markets = $readMarkets($filePath);

// Step 2: When form submitted, update expiry file
if (isset($_POST['update_expiry']) && isset($_POST['market_name']) && is_array($_POST['market_name'])) {
    $updatedMarkets = [];
    foreach ($_POST['market_name'] as $index => $mName) {
        $safeName = str_replace(["|", "\r", "\n"], '', (string)$mName);
        $rawExpiry = isset($_POST['expiry_date'][$index]) ? trim((string)$_POST['expiry_date'][$index]) : '';
        $safeExpiry = str_replace(["|", "\r", "\n"], '', $rawExpiry);
        if ($safeExpiry === '') {
            $safeExpiry = date('Y-m-d'); // fallback to current date if empty
        }
        $updatedMarkets[] = $safeName . "|" . $safeExpiry;
    }

    // Save all back into file
    file_put_contents($filePath, implode("\n", $updatedMarkets) . "\n", LOCK_EX);

    $msg = "<div class='alert alert-success text-center'>
                ✅ Expiry dates updated successfully.
            </div>";

    // Refresh data after update
    $markets = $readMarkets($filePath);
}
?>
<!DOCTYPE html>
<html lang="en-in">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<link rel="icon" href="assets/images/favicon.ico">
	<title>Update Expiry Dates</title>

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

		<hr />
		
		<?php if (!empty($msg)) echo $msg; ?>

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="panel-heading">
						<div class="panel-title">Update Expiry Dates</div>
					</div>
					<div class="panel-body">
						<form role="form" class="form-horizontal" method="post" action="">
							<?php if (!empty($markets)) { ?>
								<?php foreach ($markets as $index => $m) { ?>
									<div class="form-group">
										<label class="col-sm-4 control-label">Market Name</label>
										<div class="col-sm-4">
											<input type="text" name="market_name[]" class="form-control" value="<?php echo htmlspecialchars((string)$m['name'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
										</div>
										<div class="col-sm-4">
											<input type="date" name="expiry_date[]" class="form-control" value="<?php echo htmlspecialchars((string)$m['expiry'], ENT_QUOTES, 'UTF-8'); ?>">
										</div>
									</div>
								<?php } ?>
								<div class="form-group text-center">
									<button type="submit" name="update_expiry" class="btn btn-success">Update Expiry Dates</button>
								</div>
							<?php } else { ?>
								<p class="text-center text-muted">No expiry data found in expiry.txt.</p>
							<?php } ?>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php include 'footer.php'; ?>
	</div>
</div>

	<!-- Bottom scripts -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
</body>
</html>
