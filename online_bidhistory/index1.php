<?php
$overall_total = 0;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$market = isset($_GET['market']) ? $_GET['market'] : '';

$sites = [
'https://matkaji.games/api_v2_2021/bidding_history_api_7827.php',	
'https://satorino1.net/api_v2_2021/bidding_history_api_7827.php',
'https://leo777.games/api_ver3/bidding_history_api_7827.php',
'https://jiogames.app/api_v2_2021/bidding_history_api_7827.php',
'https://jodi99.com/api_v2_2021/bidding_history_api_7827.php'
];

$markets = [
'Kalyan Morning Open','Kalyan Morning Close',
'main bazar day open','main bazar day close',
'ratan khatri open','ratan khatri close',
'main mumbai night open','main mumbai night close',
'sridevi morning open','sridevi morning close',
'main mumbai rk open','main mumbai rk close',
];

$game_types = [
'single','jodi','single_patti','double_patti','triple_patti','half_sangam','full_sangam'
];

/* -------- PATTI DATA -------- */

$single_patti=[1=>['128','137','146','236','245','290','380','470','489','560','579','678'],
2=>['129','138','147','156','237','246','345','390','480','570','589','679'],
3=>['120','139','148','157','238','247','256','346','490','580','670','689'],
4=>['130','149','158','167','239','248','257','347','356','590','680','789'],
5=>['140','159','168','230','249','258','267','348','357','456','690','780'],
6=>['123','150','169','178','240','259','268','349','358','367','457','790'],
7=>['124','160','179','250','269','278','340','359','368','458','467','890'],
8=>['125','134','170','189','260','279','350','369','378','459','468','567'],
9=>['126','135','180','234','270','289','360','379','450','469','478','568'],
0=>['127','136','145','190','235','280','370','389','460','479','569','578']];

$double_patti=[1=>['100','119','155','227','335','344','399','588','669'],
2=>['110','200','228','255','336','499','660','688','778'],
3=>['166','229','300','337','355','445','599','779','788'],
4=>['112','220','266','338','400','446','455','699','770'],
5=>['113','122','177','339','366','447','500','799','889'],
6=>['114','277','330','448','466','556','600','880','899'],
7=>['115','133','188','223','377','449','557','566','700'],
8=>['116','224','233','288','440','477','558','800','990'],
9=>['117','144','199','225','388','559','577','667','900'],
0=>['118','226','244','299','334','488','550','668','677']];

$triple_patti=[1=>['777'],2=>['444'],3=>['111'],4=>['888'],5=>['555'],
6=>['222'],7=>['999'],8=>['666'],9=>['333'],0=>['000']];

/* -------- API -------- */

function call_api($url){
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_TIMEOUT,10);
$res = curl_exec($ch);
curl_close($ch);
return json_decode($res,true);
}

/* -------- MERGE -------- */

$merged=[];

if($market!=''){
foreach($sites as $site){

$url=$site."?game=".urlencode($market)."&date=".$date;
$data=call_api($url);

if(!$data || $data['status']!=true) continue;

foreach($data['data'] as $type=>$digits){
foreach($digits as $digit=>$amt){
$merged[$type][$digit]=($merged[$type][$digit]??0)+$amt;
}
}
}

foreach($merged as $t=>$d){
arsort($d);
$merged[$t]=$d;
}
}

/* -------- JANTREE -------- */

$jantree=[];

for($ank=0;$ank<=9;$ank++){

$jantree[$ank]=[];

$all=array_merge($single_patti[$ank],$double_patti[$ank],$triple_patti[$ank]);

foreach($all as $patti){

$amt=0;

$amt+= $merged['single_patti'][$patti] ?? 0;
$amt+= $merged['double_patti'][$patti] ?? 0;
$amt+= $merged['triple_patti'][$patti] ?? 0;

$jantree[$ank][$patti]=$amt;
}
}

/* -------- ANALYTICS -------- */

$patti_analysis=[];
$ank_totals=[];
$lowest_amount=PHP_INT_MAX;
$best_patti='';
$best_ank='';

foreach($jantree as $ank=>$pattis){

$ank_total=0;

foreach($pattis as $p=>$amt){

$ank_total+=$amt;

if($amt < $lowest_amount){
$lowest_amount=$amt;
$best_patti=$p;
$best_ank=$ank;
}

$patti_analysis[]=['patti'=>$p,'amount'=>$amt];
}

$ank_totals[$ank]=$ank_total;
}

usort($patti_analysis,function($a,$b){
return $b['amount']-$a['amount'];
});

$top3=array_slice($patti_analysis,0,3);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pro Jantree Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f6f6f6}
.card{border-radius:10px}
.jantree-box{max-height:320px;overflow:auto}
.patti-row{display:flex;justify-content:space-between;font-size:12px}
</style>
</head>

<body>

<div class="container-fluid p-3">

<!-- FILTER -->
<div class="card">
<div class="card-body">
<form class="row g-2">
<div class="col-md-5">
<select name="market" class="form-select" required>
<option value="">Select Market</option>
<?php foreach($markets as $m){ ?>
<option value="<?= $m ?>" <?=($market==$m?'selected':'')?>>
<?= $m ?>
</option>
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

<?php if(!empty($merged)){ ?>

<!-- BEST RESULT -->
<div class="alert alert-success mt-3 text-center fw-bold">
🎯 BEST RESULT → <?= $best_patti ?> - <?= $best_ank ?> <br>
<small>Lowest Bid: <?= $lowest_amount ?></small>
</div>

<!-- ANK STRENGTH -->
<div class="card mt-2">
<div class="card-body">
<div class="row text-center">
<?php foreach($ank_totals as $ank=>$total){

$class = ($total>15000)?'text-danger':(($total>8000)?'text-warning':'text-success');

echo "<div class='col'><div>$ank</div><div class='$class fw-bold'>$total</div></div>";
} ?>
</div>
</div>
</div>

<!-- JANTREE -->
<div class="card mt-3">
<div class="card-body">

<table class="table table-bordered text-center">

<thead class="table-dark">
<tr>
<?php for($i=1;$i<=9;$i++) echo "<th>$i</th>"; ?>
<th>0</th>
</tr>
</thead>

<tbody>
<tr>

<?php
for($ank=1;$ank<=9;$ank++){

echo "<td class='jantree-box'>";

arsort($jantree[$ank]);
$rank=1;

foreach($jantree[$ank] as $p=>$amt){

if($amt>1000) $color='text-danger fw-bold';
elseif($amt>500) $color='text-warning fw-bold';
else $color='text-success fw-bold';

$badge=($rank<=3)?"<span class='badge bg-dark ms-1'>#$rank</span>":'';
$best=($p==$best_patti)?"<span class='badge bg-success ms-1'>BEST</span>":'';

echo "<div class='patti-row'>
<span>$p $badge $best</span>
<span class='$color'>$amt</span>
</div>";

$rank++;
}

echo "</td>";
}

/* 0 */
echo "<td class='jantree-box'>";
foreach($jantree[0] as $p=>$amt){
echo "<div class='patti-row'><span>$p</span><span>$amt</span></div>";
}
echo "</td>";
?>

</tr>
</tbody>

</table>

</div>
</div>

<?php } ?>

</div>

</body>
</html>