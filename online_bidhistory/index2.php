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

/* -------- PATTI DATA -------- */

$single_patti = [
    1 => ['128','137','146','236','245','290','380','470','489','560','579','678'],
    2 => ['129','138','147','156','237','246','345','390','480','570','589','679'],
    3 => ['120','139','148','157','238','247','256','346','490','580','670','689'],
    4 => ['130','149','158','167','239','248','257','347','356','590','680','789'],
    5 => ['140','159','168','230','249','258','267','348','357','456','690','780'],
    6 => ['123','150','169','178','240','259','268','349','358','367','457','790'],
    7 => ['124','160','179','250','269','278','340','359','368','458','467','890'],
    8 => ['125','134','170','189','260','279','350','369','378','459','468','567'],
    9 => ['126','135','180','234','270','289','360','379','450','469','478','568'],
    0 => ['127','136','145','190','235','280','370','389','460','479','569','578']
];

$double_patti = [
    1 => ['100','119','155','227','335','344','399','588','669'],
    2 => ['110','200','228','255','336','499','660','688','778'],
    3 => ['166','229','300','337','355','445','599','779','788'],
    4 => ['112','220','266','338','400','446','455','699','770'],
    5 => ['113','122','177','339','366','447','500','799','889'],
    6 => ['114','277','330','448','466','556','600','880','899'],
    7 => ['115','133','188','223','377','449','557','566','700'],
    8 => ['116','224','233','288','440','477','558','800','990'],
    9 => ['117','144','199','225','388','559','577','667','900'],
    0 => ['118','226','244','299','334','488','550','668','677']
];

$triple_patti = [
    1 => ['777'], 2 => ['444'], 3 => ['111'], 4 => ['888'], 5 => ['555'],
    6 => ['222'], 7 => ['999'], 8 => ['666'], 9 => ['333'], 0 => ['000']
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

/* -------- JANTREE BUILD -------- */

$jantree = [];

for ($ank = 0; $ank <= 9; $ank++) {
    $jantree[$ank] = [];
    $all = array_merge($single_patti[$ank], $double_patti[$ank], $triple_patti[$ank]);

    foreach ($all as $patti) {
        $amount = 0;
        if (isset($merged['single_patti'][$patti]) && is_numeric($merged['single_patti'][$patti])) {
            $amount += (float)$merged['single_patti'][$patti];
        }
        if (isset($merged['double_patti'][$patti]) && is_numeric($merged['double_patti'][$patti])) {
            $amount += (float)$merged['double_patti'][$patti];
        }
        if (isset($merged['triple_patti'][$patti]) && is_numeric($merged['triple_patti'][$patti])) {
            $amount += (float)$merged['triple_patti'][$patti];
        }
        $jantree[$ank][$patti] = $amount;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Merged Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f6f6f6;}
.card{border-radius:10px;}
.table td,.table th{font-size:13px;}
.jantree-box{max-height:320px;overflow:auto;}
.patti-row{display:flex;justify-content:space-between;font-size:12px;gap: 10px;}
</style>
</head>
<body>

<div class="container-fluid p-3">

<!-- FILTER -->
<div class="card shadow-sm">
<div class="card-body">
<h5>Merged Bidding History</h5>

<form class="row g-2" method="GET" action="">
<div class="col-md-5">
<select name="market" class="form-select" required>
<option value="">Select Market</option>
<?php foreach ($markets as $m): ?>
<option value="<?php echo htmlspecialchars($m, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($market === $m) echo 'selected'; ?>>
    <?php echo htmlspecialchars($m, ENT_QUOTES, 'UTF-8'); ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-3">
<input type="date" name="date" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
</div>

<div class="col-md-2 d-grid">
<button type="submit" class="btn btn-primary">Fetch</button>
</div>
</form>

</div>
</div>

<!-- OLD DATA -->
<?php if (!empty($merged)): ?>

<div class="card mt-3 shadow-sm">
<div class="card-body">

<ul class="nav nav-tabs flex-nowrap overflow-auto" role="tablist">
<?php
$i = 0;
foreach ($game_types as $type):
    $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
    $active = ($i === 0 ? 'active' : '');
?>
<li class="nav-item" role="presentation">
<button class="nav-link <?php echo $active; ?>" data-bs-toggle="tab" data-bs-target="#<?php echo $safeType; ?>" type="button" role="tab">
<?php echo $safeType; ?>
</button>
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
    $safeType = htmlspecialchars($type, ENT_QUOTES, 'UTF-8');
    $active = ($i === 0 ? 'show active' : '');
?>

<div class="tab-pane fade <?php echo $active; ?>" id="<?php echo $safeType; ?>" role="tabpanel">

<table class="table table-bordered table-sm">
<thead class="table-light">
<tr><th>Digit</th><th>Total</th></tr>
</thead>
<tbody>

<?php
$type_total = 0;
if (isset($merged[$type]) && is_array($merged[$type])) {
    foreach ($merged[$type] as $digit => $amt) {
        $numAmt = is_numeric($amt) ? (float)$amt : 0;
        $type_total += $numAmt;
        $overall_total += $numAmt;
        echo "<tr><td>" . htmlspecialchars((string)$digit, ENT_QUOTES, 'UTF-8') . "</td><td>" . htmlspecialchars((string)$amt, ENT_QUOTES, 'UTF-8') . "</td></tr>";
    }
    echo "<tr class='table-warning fw-bold'><td>Total</td><td>" . htmlspecialchars((string)$type_total, ENT_QUOTES, 'UTF-8') . "</td></tr>";
} else {
    echo "<tr><td colspan='2'>No Data</td></tr>";
}
?>

</tbody>
</table>

</div>

<?php
    $i++;
endforeach;
?>

</div>

<div class="text-end mt-3">
<h5>Overall Total : <span class="text-primary"><?php echo number_format((float)$overall_total); ?></span></h5>
</div>

</div>
</div>

<?php endif; ?>

<!-- JANTREE -->
<?php if (!empty($jantree)): ?>

<div class="card mt-3 shadow-sm">
<div class="card-body">

<h5>Jantree Dashboard</h5>

<div class="table-responsive">
<table class="table table-bordered text-center">
<thead class="table-dark">
<tr>
<?php for ($i = 1; $i <= 9; $i++) { echo "<th>$i</th>"; } ?>
<th>0</th>
</tr>
</thead>
<tbody>
<tr>

<?php
for ($ank = 1; $ank <= 9; $ank++) {
    echo "<td class='jantree-box'>";
    if (isset($jantree[$ank]) && is_array($jantree[$ank])) {
        foreach ($jantree[$ank] as $patti => $amt) {
            $numAmt = is_numeric($amt) ? (float)$amt : 0;
            $color = ($numAmt > 1000) ? 'text-danger fw-bold' : (($numAmt > 500) ? 'text-warning' : 'text-dark');
            $safePatti = htmlspecialchars((string)$patti, ENT_QUOTES, 'UTF-8');
            $safeAmt = htmlspecialchars((string)$amt, ENT_QUOTES, 'UTF-8');

            echo "<div class='patti-row'>";
            echo "<span>{$safePatti}</span>";
            echo "<span class='{$color}'>{$safeAmt}</span>";
            echo "</div>";
        }
    }
    echo "</td>";
}

/* 0 */
echo "<td class='jantree-box'>";
if (isset($jantree[0]) && is_array($jantree[0])) {
    foreach ($jantree[0] as $patti => $amt) {
        $safePatti = htmlspecialchars((string)$patti, ENT_QUOTES, 'UTF-8');
        $safeAmt = htmlspecialchars((string)$amt, ENT_QUOTES, 'UTF-8');
        echo "<div class='patti-row'><span>{$safePatti}</span><span>{$safeAmt}</span></div>";
    }
}
echo "</td>";
?>

</tr>
</tbody>
</table>
</div>

</div>
</div>

<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
