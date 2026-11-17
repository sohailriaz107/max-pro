<?php
$overall_total = 0;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$market = isset($_GET['market']) ? $_GET['market'] : '';

/* -------- SITES -------- */

$sites = [
'https://matkaji.games/api_v2_2021/bidding_history_api_7827.php',	
'https://dp777.app/api_v2_2021/bidding_history_api_7827.php',
'https://leo777.games/api_ver3/bidding_history_api_7827.php',
'https://jiogames.app/api_v2_2021/bidding_history_api_7827.php',
'https://jodi99.com/api_v2_2021/bidding_history_api_7827.php'
];

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

function call_api($url){

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_TIMEOUT,10);

$response = curl_exec($ch);

curl_close($ch);

return json_decode($response,true);
}

/* -------- FETCH DATA -------- */

$merged = [];

if($market != ''){

foreach($sites as $site){

$url = $site."?game=".urlencode($market)."&date=".$date;

$data = call_api($url);

if(!$data || $data['status'] != true){
continue;
}

foreach($data['data'] as $type=>$digits){

foreach($digits as $digit=>$amount){

if(!isset($merged[$type][$digit])){
$merged[$type][$digit] = 0;
}

$merged[$type][$digit] += $amount;

}

}

}

/* SORT */

foreach($merged as $type=>$digits){
arsort($digits);
$merged[$type] = $digits;
}

}

?>

<!DOCTYPE html>
<html>
<head>

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

<form class="row g-2">

<div class="col-12 col-md-5">

<select name="market" class="form-select" required>

<option value="">Select Market</option>

<?php foreach($markets as $m){ ?>

<option value="<?php echo $m ?>" <?php if($market==$m) echo 'selected'; ?>>

<?php echo $m ?>

</option>

<?php } ?>

</select>

</div>


<div class="col-6 col-md-3">

<input type="date" name="date" value="<?php echo $date ?>" class="form-control">

</div>


<div class="col-6 col-md-2 d-grid">

<button class="btn btn-primary">Fetch</button>

</div>

</form>

</div>

</div>


<?php if(!empty($merged)){ ?>

<div class="card mt-3 shadow-sm">

<div class="card-body">

<ul class="nav nav-tabs flex-nowrap overflow-auto">

<?php

$i=0;

foreach($game_types as $type){

$active=($i==0)?'active':'';

echo "<li class='nav-item'>";
echo "<button class='nav-link $active' data-bs-toggle='tab' data-bs-target='#$type'>$type</button>";
echo "</li>";

$i++;

}

?>

</ul>


<div class="tab-content mt-3">

<?php

$i=0;

foreach($game_types as $type){

$active=($i==0)?'show active':'';

echo "<div class='tab-pane fade $active' id='$type'>";

echo "<div class='table-responsive'>";

echo "<table class='table table-bordered table-sm'>";

echo "<thead class='table-light'>";
echo "<tr>";
echo "<th>Digit</th>";
echo "<th>Total Bid</th>";
echo "</tr>";
echo "</thead>";

echo "<tbody>";

$type_total = 0;

if(isset($merged[$type])){

foreach($merged[$type] as $digit=>$amount){

$type_total += $amount;
$overall_total += $amount;

echo "<tr>";
echo "<td>".$digit."</td>";
echo "<td>".$amount."</td>";
echo "</tr>";

}

/* GAME TYPE TOTAL */

echo "<tr class='table-warning fw-bold'>";
echo "<td>Total</td>";
echo "<td>".$type_total."</td>";
echo "</tr>";

}else{

echo "<tr><td colspan='2'>No Data</td></tr>";

}

echo "</tbody>";
echo "</table>";

echo "</div>";

echo "</div>";

$i++;

}

?>

</div>

<div class="mt-3 text-end">

<h5 class="fw-bold">
Overall Total : 
<span class="text-primary"><?php echo number_format($overall_total); ?></span>
</h5>

</div>

</div>

</div>

<?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>