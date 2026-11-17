<?php
declare(strict_types=1);

$overall_total = 0;
$rawDate = isset($_GET['date']) ? (string)$_GET['date'] : date('Y-m-d');
$date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate) ? $rawDate : date('Y-m-d');

/* -------- MARKETS -------- */

$markets = [
    'Kalyan Morning Open',
    'Kalyan Morning Close',
    'main bazar day open',
    'main bazar day close',
    'ratan khatri open',
    'ratan khatri close',
    'main mumbai night open',
    'main mumbai night close',
    'sridevi morning open',
    'sridevi morning close',
    'main mumbai rk open',
    'main mumbai rk close',
];

$rawMarket = isset($_GET['market']) ? trim((string)$_GET['market']) : '';
$market = in_array($rawMarket, $markets, true) ? $rawMarket : '';

/* -------- SITES -------- */

$sites = [
    'https://matkaji.games/api_v2_2021/bidding_history_api_7827.php',	
    'https://dp777.app/api_v2_2021/bidding_history_api_7827.php',
    'https://leo777.games/api_ver3/bidding_history_api_7827.php',
    'https://jiogames.app/api_v2_2021/bidding_history_api_7827.php',
    'https://jodi99.com/api_v2_2021/bidding_history_api_7827.php'
];

/* -------- GAME TYPES -------- */

$game_types = [
    'single',
    'jodi',
    'single_patti',
    'double_patti',
    'triple_patti',
    'half_sangam',
    'full_sangam'
];

/* -------- API FUNCTION -------- */

function call_api(string $url): array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!is_string($response)) {
        return [];
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : [];
}

/* -------- FETCH DATA -------- */

$merged = [];

if ($market !== '') {
    foreach ($sites as $site) {
        $url = $site . "?game=" . urlencode($market) . "&date=" . urlencode($date);
        $data = call_api($url);

        if (empty($data) || !isset($data['status']) || $data['status'] != true || !isset($data['data']) || !is_array($data['data'])) {
            continue;
        }

        foreach ($data['data'] as $type => $digits) {
            if (!is_array($digits)) {
                continue;
            }
            foreach ($digits as $digit => $amount) {
                $digitKey = (string)$digit;
                $numAmount = is_numeric($amount) ? (float)$amount : 0;
                if (!isset($merged[$type][$digitKey])) {
                    $merged[$type][$digitKey] = 0;
                }
                $merged[$type][$digitKey] += $numAmount;
            }
        }
    }

    /* SORT */
    foreach ($merged as $type => $digits) {
        if (is_array($digits)) {
            arsort($digits);
            $merged[$type] = $digits;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Merged Bidding Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
background:#f6f6f6;
}
.card{
border-radius:10px;
}
.nav-tabs .nav-link{
font-size:14px;
padding:8px 12px;
}
.table td,.table th{
font-size:14px;
}
</style>
</head>
<body>

<div class="container-fluid p-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Merged Bidding History</h5>
            <form class="row g-2" method="GET" action="">
                <div class="col-12 col-md-5">
                    <select name="market" class="form-select" required>
                        <option value="">Select Market</option>
                        <?php foreach ($markets as $m): ?>
                            <option value="<?php echo htmlspecialchars($m, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($market === $m) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($m, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <input type="date" name="date" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Fetch</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($merged)): ?>
    <div class="card mt-3 shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs flex-nowrap overflow-auto" role="tablist">
                <?php
                $i = 0;
                foreach ($game_types as $type):
                    $active = ($i === 0) ? 'active' : '';
                    $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
                ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo $active; ?>" data-bs-toggle="tab" data-bs-target="#<?php echo $safeType; ?>" type="button" role="tab"><?php echo $safeType; ?></button>
                    </li>
                <?php
                    $i++;
                endforeach;
                ?>
            </ul>

            <div class="tab-content mt-3">
                <?php
                $i = 0;
                foreach ($game_types as $type):
                    $active = ($i === 0) ? 'show active' : '';
                    $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
                ?>
                    <div class="tab-pane fade <?php echo $active; ?>" id="<?php echo $safeType; ?>" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Digit</th>
                                        <th>Total Bid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $type_total = 0;
                                    if (isset($merged[$type]) && is_array($merged[$type])):
                                        foreach ($merged[$type] as $digit => $amount):
                                            $numAmt = is_numeric($amount) ? (float)$amount : 0;
                                            $type_total += $numAmt;
                                            $overall_total += $numAmt;
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars((string)$digit, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars((string)$amount, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php
                                        endforeach;
                                    ?>
                                        <tr class="table-warning fw-bold">
                                            <td>Total</td>
                                            <td><?php echo htmlspecialchars((string)$type_total, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr><td colspan="2">No Data</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                    $i++;
                endforeach;
                ?>
            </div>

            <div class="mt-3 text-end">
                <h5 class="fw-bold">
                    Overall Total : 
                    <span class="text-primary"><?php echo number_format((float)$overall_total); ?></span>
                </h5>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
