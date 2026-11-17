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

/* -------- PATTI DATA -------- */

$single_patti=[
1=>['128','137','146','236','245','290','380','470','489','560','579','678'],
2=>['129','138','147','156','237','246','345','390','480','570','589','679'],
3=>['120','139','148','157','238','247','256','346','490','580','670','689'],
4=>['130','149','158','167','239','248','257','347','356','590','680','789'],
5=>['140','159','168','230','249','258','267','348','357','456','690','780'],
6=>['123','150','169','178','240','259','268','349','358','367','457','790'],
7=>['124','160','179','250','269','278','340','359','368','458','467','890'],
8=>['125','134','170','189','260','279','350','369','378','459','468','567'],
9=>['126','135','180','234','270','289','360','379','450','469','478','568'],
0=>['127','136','145','190','235','280','370','389','460','479','569','578']
];

$double_patti=[
1=>['100','119','155','227','335','344','399','588','669'],
2=>['110','200','228','255','336','499','660','688','778'],
3=>['166','229','300','337','355','445','599','779','788'],
4=>['112','220','266','338','400','446','455','699','770'],
5=>['113','122','177','339','366','447','500','799','889'],
6=>['114','277','330','448','466','556','600','880','899'],
7=>['115','133','188','223','377','449','557','566','700'],
8=>['116','224','233','288','440','477','558','800','990'],
9=>['117','144','199','225','388','559','577','667','900'],
0=>['118','226','244','299','334','488','550','668','677']
];

$triple_patti=[
1=>['777'],
2=>['444'],
3=>['111'],
4=>['888'],
5=>['555'],
6=>['222'],
7=>['999'],
8=>['666'],
9=>['333'],
0=>['000']
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

/* -------- JANTREE BUILD -------- */

$jantree = [];

for($ank=0;$ank<=9;$ank++){

$jantree[$ank] = [];

$all = array_merge(
$single_patti[$ank],
$double_patti[$ank],
$triple_patti[$ank]
);

foreach($all as $patti){

$amount = 0;

if(isset($merged['single_patti'][$patti])){
$amount += $merged['single_patti'][$patti];
}

if(isset($merged['double_patti'][$patti])){
$amount += $merged['double_patti'][$patti];
}

if(isset($merged['triple_patti'][$patti])){
$amount += $merged['triple_patti'][$patti];
}

$jantree[$ank][$patti] = $amount;

}

}
?>

<!DOCTYPE html>
<html>
<head>
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

<form class="row g-2">
<div class="col-md-5">
<select name="market" class="form-select" required>
<option value="">Select Market</option>
<?php foreach($markets as $m){ ?>
<option value="<?= $m ?>" <?= ($market==$m)?'selected':'' ?>><?= $m ?></option>
<?php } ?>
</select>
</div>

<div class="col-md-3">
<input type="date" name="date" value="<?= $date ?>" class="form-control">
</div>

<div class="col-md-2 d-grid">
<button class="btn btn-primary">Fetch</button>
</div>

</form>

</div>
</div>

<!-- OLD DATA -->
<?php if(!empty($merged)){ ?>

<div class="card mt-3 shadow-sm">
<div class="card-body">

<ul class="nav nav-tabs flex-nowrap overflow-auto">
<?php $i=0; foreach($game_types as $type){ ?>
<li class="nav-item">
<button class="nav-link <?=($i==0?'active':'')?>" data-bs-toggle="tab" data-bs-target="#<?= $type ?>">
<?= $type ?>
</button>
</li>
<?php $i++; } ?>
</ul>

<div class="tab-content mt-3">

<?php $i=0; foreach($game_types as $type){ ?>

<div class="tab-pane fade <?=($i==0?'show active':'')?>" id="<?= $type ?>">

<table class="table table-bordered table-sm">

<thead class="table-light">
<tr><th>Digit</th><th>Total</th></tr>
</thead>

<tbody>

<?php
$type_total = 0;

if(isset($merged[$type])){
foreach($merged[$type] as $digit=>$amt){
$type_total += $amt;
$overall_total += $amt;
echo "<tr><td>$digit</td><td>$amt</td></tr>";
}

echo "<tr class='table-warning fw-bold'><td>Total</td><td>$type_total</td></tr>";
}else{
echo "<tr><td colspan='2'>No Data</td></tr>";
}
?>

</tbody>
</table>

</div>

<?php $i++; } ?>

</div>

<div class="text-end mt-3">
<h5>Overall Total : <span class="text-primary"><?= number_format($overall_total) ?></span></h5>
</div>

</div>
</div>

<?php } ?>

<!-- JANTREE -->
<?php if(!empty($jantree)){ ?>

<div class="card mt-3 shadow-sm">
<div class="card-body">

<h5>Jantree Dashboard</h5>

<div class="table-responsive">

<table class="table table-bordered text-center">

<thead class="table-dark">
<tr>
<?php for($i=1;$i<=9;$i++){ echo "<th>$i</th>"; } ?>
<th>0</th>
</tr>
</thead>

<tbody>
<tr>

<?php
for($ank=1;$ank<=9;$ank++){

echo "<td class='jantree-box'>";

foreach($jantree[$ank] as $patti=>$amt){

$color = ($amt>1000) ? 'text-danger fw-bold' : (($amt>500)?'text-warning':'text-dark');

echo "<div class='patti-row'>";
echo "<span>$patti</span>";
echo "<span class='$color'>$amt</span>";
echo "</div>";

}

echo "</td>";
}

/* 0 */
echo "<td class='jantree-box'>";
foreach($jantree[0] as $patti=>$amt){
echo "<div class='patti-row'><span>$patti</span><span>$amt</span></div>";
}
echo "</td>";
?>

</tr>
</tbody>

</table>

</div>

</div>
</div>

<?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>