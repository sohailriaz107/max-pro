<?php

date_default_timezone_set('Asia/Kolkata');

/*

FILE:
create-rashifal-json.php

OUTPUT:
txtdb/rashifal_free_games.json

CRON:
0 0 * * * /usr/local/bin/php /home/username/public_html/create-rashifal-json.php

*/

/* RASHI LIST */

$rashiList = [

    'Mesh',
    'Vrishabh',
    'Mithun',
    'Kark',
    'Singh',
    'Kanya',
    'Tula',
    'Vrishchik',
    'Dhanu',
    'Makar',
    'Kumbh',
    'Meen'

];

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

function generateAnk()
{
    $digits = range(0, 9);

    shuffle($digits);

    $ank = array_slice($digits, 0, 4);

    sort($ank);

    return array_map('strval', $ank);
}

/* GENERATE JODI */

function generateJodi($ank)
{
    $jodi = [];

    while (count($jodi) < 6) {

        $first  = $ank[array_rand($ank)];

        $second = $ank[array_rand($ank)];

        if ($first == $second) {

            continue;
        }

        $pair = $first . $second;

        if (!in_array($pair, $jodi)) {

            $jodi[] = $pair;
        }
    }

    sort($jodi);

    return $jodi;
}

/* GENERATE PANNA */

function generatePanna($ank, $pannaMap)
{
    $allPanna = [];

    foreach ($ank as $digit) {

        if (isset($pannaMap[$digit])) {

            $allPanna = array_merge(
                $allPanna,
                $pannaMap[$digit]
            );
        }
    }

    $allPanna = array_unique($allPanna);

    shuffle($allPanna);

    $final = array_slice($allPanna, 0, 6);

    sort($final);

    return $final;
}

/* FINAL DATA */

$finalData = [];

foreach ($rashiList as $rashi) {

    $ank = generateAnk();

    $finalData[] = [

        'rashi' => $rashi,

        'ank' => $ank,

        'jodi' => generateJodi($ank),

        'panna' => generatePanna($ank, $pannaMap),

        'updated_at' => date('Y-m-d')

    ];
}

/* FINAL JSON */

$jsonOutput = [

    'date' => date('d-m-Y'),

    'updated_at' => date('d-m-Y h:i:s A'),

    'rashifal_data' => $finalData

];

/* SAVE FILE */

file_put_contents(

    'txtdb/rashifal_free_games.json',

    json_encode(
        $jsonOutput,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    ),

    LOCK_EX
);

echo 'Rashifal JSON Generated Successfully';

?>