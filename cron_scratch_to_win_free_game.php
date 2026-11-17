<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

$jsonFile = 'txtdb/scratch_to_win_markets.json';

if (!file_exists($jsonFile)) {
    exit('JSON file not found');
}

$jsonData = file_get_contents($jsonFile);
if ($jsonData === false || trim($jsonData) === '') {
    exit('JSON file empty or unreadable');
}

$markets = json_decode($jsonData, true);

if (!is_array($markets)) {
    exit('Invalid JSON format');
}

/* PANNA MAP */

$pannaMap = [
    '0' => ['127','136','145','190','235','280','370','389','460','479','569','578','118','226','244','299','334','488','668','677'],
    '1' => ['128','137','146','236','245','290','380','470','489','560','678','579','119','155','227','335','344','399','588','669'],
    '2' => ['129','138','147','156','237','246','345','390','480','570','679','589','110','228','255','336','499','660','688','778'],
    '3' => ['120','139','148','157','238','247','256','346','490','580','670','689','166','229','337','355','445','599','779','788'],
    '4' => ['130','149','158','167','239','248','257','347','356','590','680','789','112','220','266','338','446','455','699','770'],
    '5' => ['140','159','168','230','249','258','267','348','357','456','690','780','113','122','177','339','366','447','799','889'],
    '6' => ['123','150','169','178','240','259','268','349','358','457','367','790','114','277','330','448','466','556','880','899'],
    '7' => ['124','160','179','250','269','278','340','359','368','458','467','890','115','133','188','223','377','449','557','566'],
    '8' => ['125','134','170','189','260','279','350','369','378','459','567','468','116','224','233','288','440','477','558','990'],
    '9' => ['126','135','180','234','270','289','360','379','450','469','117','478','568','144','199','225','388','559','577','667']
];

/* GENERATE ANK */

function generateAnk(): array
{
    $digits = range(0, 9);
    shuffle($digits);
    $ank = array_slice($digits, 0, 4);
    sort($ank);
    return array_map('strval', $ank);
}

/* GENERATE JODI */

function generateJodi(array $ank): array
{
    $jodi = [];
    if (empty($ank)) {
        return $jodi;
    }

    $maxAttempts = 100;
    $attempts = 0;

    while (count($jodi) < 6 && $attempts < $maxAttempts) {
        $attempts++;
        $firstKey = array_rand($ank);
        $secondKey = array_rand($ank);
        $first  = (string)$ank[$firstKey];
        $second = (string)$ank[$secondKey];

        if ($first === $second) {
            continue;
        }

        $pair = $first . $second;
        if (!in_array($pair, $jodi, true)) {
            $jodi[] = $pair;
        }
    }

    return $jodi;
}

/* GENERATE PANNA */

function generatePanna(array $ank, array $pannaMap): array
{
    $allPanna = [];

    foreach ($ank as $digit) {
        $d = (string)$digit;
        if (isset($pannaMap[$d]) && is_array($pannaMap[$d])) {
            $allPanna = array_merge($allPanna, $pannaMap[$d]);
        }
    }

    $allPanna = array_values(array_unique($allPanna));
    shuffle($allPanna);

    return array_slice($allPanna, 0, 6);
}

/* UPDATE MARKETS */

foreach ($markets as $key => $market) {
    $ank = generateAnk();

    $markets[$key]['ank'] = $ank;
    $markets[$key]['jodi'] = generateJodi($ank);
    $markets[$key]['panna'] = generatePanna($ank, $pannaMap);
    $markets[$key]['updated_at'] = date('Y-m-d');
}

/* SAVE JSON */

$newJson = json_encode(
    $markets,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);

file_put_contents(
    $jsonFile,
    (string)$newJson,
    LOCK_EX
);

echo 'Scratch To Win Markets Updated Successfully';
