<?php
declare(strict_types=1);

require_once '../config.php';

date_default_timezone_set('Asia/Kolkata');

$TABLE = "mumbai_standard_single_khech";
$cache_dir = '../cache';
$cache_file = $cache_dir . '/mumbai-standard-single-khech.html';
$cache_time = 60;

if (file_exists($cache_file) && (time() - (int)filemtime($cache_file)) < $cache_time) {
    readfile($cache_file);
    exit;
}

ob_start();

function generateTimeSlots(string $start = '11:00', string $end = '19:00', int $interval = 5): array
{
    $slots = [];
    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current !== false && $endTime !== false && $current < $endTime) {
        $slots[] = date('h:i A', $current);
        $current = strtotime("+{$interval} minutes", $current);
    }

    return $slots;
}

$time_labels = generateTimeSlots();
$TOTAL_SLOTS = count($time_labels);

$query = mysqli_query($con, "SELECT * FROM mumbai_standard_single_khech ORDER BY game_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mumbai Standard Single Khech Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#f4f4f4;
            font-family:Arial,sans-serif;
        }
        .main-box{
            background:#fff;
            border-radius:10px;
            overflow:hidden;
            margin-bottom:25px;
            box-shadow:0 2px 12px rgba(0,0,0,0.08);
        }
        .date-header{
            background:#dc3545;
            color:#fff;
            text-align:center;
            padding:12px;
            font-size:18px;
            font-weight:bold;
        }
        .result-grid{
            display:flex;
            flex-wrap:wrap;
            padding:10px;
        }
        .result-item{
            width:16.66%;
            padding:6px;
        }
        .result-inner{
            border:1px solid #ddd;
            border-radius:8px;
            padding:10px 5px;
            text-align:center;
            background:#fafafa;
        }
        .time-label{
            font-size:11px;
            font-weight:bold;
            color:#666;
            margin-bottom:5px;
        }
        .result-value{
            font-size:22px;
            font-weight:bold;
            color:#dc3545;
        }
        @media(max-width:768px){
            .result-item{
                width:25%;
            }
            .result-value{
                font-size:18px;
            }
        }
        @media(max-width:480px){
            .result-item{
                width:33.33%;
            }
            .time-label{
                font-size:10px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid py-3">
    <h2 class="text-center mb-4">
        Mumbai Standard Single Khech Results
    </h2>

    <?php if ($query): ?>
        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <?php
            $raw_results = json_decode((string)($row['results'] ?? ''), true);
            $results = is_array($raw_results) ? $raw_results : [];
            $results = array_pad($results, $TOTAL_SLOTS, '');
            $ts = strtotime((string)($row['game_date'] ?? ''));
            $date_str = $ts !== false ? date('d M Y', $ts) : (string)($row['game_date'] ?? '');
            ?>
            <div class="main-box">
                <div class="date-header">
                    <?= htmlspecialchars($date_str, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="result-grid">
                    <?php foreach($time_labels as $index => $time): ?>
                        <div class="result-item">
                            <div class="result-inner">
                                <div class="time-label">
                                    <?= htmlspecialchars((string)$time, ENT_QUOTES, 'UTF-8') ?>
                                </div>
                                <div class="result-value">
                                    <?= ($results[$index] ?? '') !== ''
                                        ? htmlspecialchars((string)$results[$index], ENT_QUOTES, 'UTF-8')
                                        : '*'
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endwhile; ?>
        <?php mysqli_free_result($query); ?>
    <?php endif; ?>
</div>

</body>
</html>
<?php
$html = (string)ob_get_contents();
ob_end_flush();

if (!is_dir($cache_dir)) {
    @mkdir($cache_dir, 0755, true);
}
@file_put_contents($cache_file, $html, LOCK_EX);
