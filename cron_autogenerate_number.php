
<?php
date_default_timezone_set('Asia/Kolkata');

/*
|--------------------------------------------------------------------------
| GOLDEN ANK COMBINATIONS
|--------------------------------------------------------------------------
| Existing logic preserved.
*/

$golden_ank_array = array(
    "0-5-1-6", "0-5-2-7", "0-5-3-8", "0-5-4-9",
    "1-6-0-5", "1-6-2-7", "1-6-3-8", "1-6-4-9",
    "2-7-0-5", "2-7-1-6", "2-7-3-8", "2-7-4-9",
    "3-8-0-5", "3-8-1-6", "3-8-2-7", "3-8-4-9",
    "4-9-0-5", "4-9-1-6", "4-9-2-7", "4-9-3-8"
);

shuffle($golden_ank_array);

/*
|--------------------------------------------------------------------------
| FILE 1 - EXISTING
|--------------------------------------------------------------------------
*/

$data = $golden_ank_array[0];
$myFile = "txtdb/golden-ank.txt";

$fh = fopen($myFile, 'w') or die("Can't open Golden ank file");
$write = fwrite($fh, $data);
fclose($fh);

/*
|--------------------------------------------------------------------------
| FILE 2 - EXISTING
|--------------------------------------------------------------------------
*/

$data1 = $golden_ank_array[1];
$myFile1 = "txtdb/golden-ank1.txt";

$fh1 = fopen($myFile1, 'w') or die("Can't open Golden ank1 file");
$write1 = fwrite($fh1, $data1);
fclose($fh1);

/*
|--------------------------------------------------------------------------
| PANNA MAP
|--------------------------------------------------------------------------
*/

$pannaMap = array(
    '0' => array('127','136','145','190','235','280','370','389','460','479','569','578','118','226','244','299','334','488','668','677'),
    '1' => array('128','137','146','236','245','290','380','470','489','560','678','579','119','155','227','335','344','399','588','669'),
    '2' => array('129','138','147','156','237','246','345','390','480','570','679','589','110','228','255','336','499','660','688','778'),
    '3' => array('120','139','148','157','238','247','256','346','490','580','670','689','166','229','337','355','445','599','779','788'),
    '4' => array('130','149','158','167','239','248','257','347','356','590','680','789','112','220','266','338','446','455','699','770'),
    '5' => array('140','159','168','230','249','258','267','348','357','456','690','780','113','122','177','339','366','447','799','889'),
    '6' => array('123','150','169','178','240','259','268','349','358','457','367','790','114','277','330','448','466','556','880','899'),
    '7' => array('124','160','179','250','269','278','340','359','368','458','467','890','115','133','188','223','377','449','557','566'),
    '8' => array('125','134','170','189','260','279','350','369','378','459','567','468','116','224','233','288','440','477','558','990'),
    '9' => array('126','135','180','234','270','289','360','379','450','469','117','478','568','144','199','225','388','559','577','667')
);

/*
|--------------------------------------------------------------------------
| THIRD FILE - X GOLDEN ANK
|--------------------------------------------------------------------------
| Line 1: Four selected ank
| Line 2: Three unique jodi
| Line 3: Three unique patti
|--------------------------------------------------------------------------
*/

// Use the same four ank as the first existing file.
$ankDigits = explode('-', $data);

/*
|--------------------------------------------------------------------------
| Generate related jodi combinations
|--------------------------------------------------------------------------
| Each jodi uses two digits from the selected four anks.
| The jodi mapping/rules can be adjusted if you have a specific mapping.
*/

$jodiPool = array();

foreach ($ankDigits as $digit1) {
    foreach ($ankDigits as $digit2) {
        if ($digit1 !== $digit2) {
            $jodiPool[] = $digit1 . $digit2;
        }
    }
}

$jodiPool = array_values(array_unique($jodiPool));
shuffle($jodiPool);

$selectedJodis = array_slice($jodiPool, 0, 4);

/*
|--------------------------------------------------------------------------
| Generate related patti
|--------------------------------------------------------------------------
| Each selected ank must have exactly one patti.
| Total: 4 unique pattis for 4 selected anks.
|--------------------------------------------------------------------------
*/

$pattiPool = array();

foreach ($ankDigits as $ank) {
    if (isset($pannaMap[$ank]) && !empty($pannaMap[$ank])) {
        $ankPattis = $pannaMap[$ank];

        // Randomly select exactly one patti for this ank
        $randomIndex = array_rand($ankPattis);

        $pattiPool[] = $ankPattis[$randomIndex];
    }
}

$selectedPattis = $pattiPool;

/*
|--------------------------------------------------------------------------
| Save third file in the requested three-line format
|--------------------------------------------------------------------------
*/

$thirdFileData =
    implode('-', $ankDigits) . "\n" .
    implode('-', $selectedJodis) . "\n" .
    implode('-', $selectedPattis);

$thirdFile = "txtdb/x-golden-ank.txt";

$fh3 = fopen($thirdFile, 'w') or die("Can't open X Golden ank file");
$write3 = fwrite($fh3, $thirdFileData);
fclose($fh3);

/*
|--------------------------------------------------------------------------
| CRON LOG - EXISTING
|--------------------------------------------------------------------------
*/

if ($write !== false && $write1 !== false && $write3 !== false) {
    echo 'Golden Ank Generated..';
} else {
    echo 'Something Wrong..!';
}

$log = 'Cron job run happened at ' . date('d-m-y h:i:s A') . '|';
$fp = fopen('cron_job_log.txt', 'a');

if ($fp) {
    fwrite($fp, json_encode($log) . "\n");
    fclose($fp);
}
?>
