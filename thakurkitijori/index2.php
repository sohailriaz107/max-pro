<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

$rawDate = isset($_GET['date']) ? (string)$_GET['date'] : date('Y-m-d');
$date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate) ? $rawDate : date('Y-m-d');

$websites = array(
    array("Matka booking", "https://matkabooking.app/api_v2_2021/back_info_imp2.php"),
    array("Paytm Matka", "https://paytmmatka.com/api_v2_2021/back_info_imp2.php"),
    array("Dpbossplay", "https://dpbossfix.net/api_v2_2021/back_info_imp2.php"),
    array("jodi99", "https://jodi99.com/api_v2_2021/back_info_imp2.php")
    // Add more websites as needed
);

function fetch_remote_json(string $url): ?array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!is_string($response)) {
        return null;
    }
    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawals Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <br><br><br>
    <h2 class="text-center">&#129297; Daily Withdraw Report &#129297;</h2>
    <!-- Date input box and button -->
    <form class="form-inline mb-3 justify-content-center" method="GET" action="">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" class="form-control mx-2" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="row">
        <?php 
        $total_credit = 0.0;
        foreach ($websites as $website): 
            $website_total = 0.0;
            $safeSlug = preg_replace('/[^a-zA-Z0-9_\-]/', '-', (string)$website[0]);
        ?>
        <div class="col-md-12">
            <div style="display: block;">
                <h3 style="display: inline-block;"><?php echo htmlspecialchars((string)$website[0], ENT_QUOTES, 'UTF-8'); ?></h3> 
                <a class="btn btn-primary" id="exportbtn-<?php echo htmlspecialchars((string)$safeSlug, ENT_QUOTES, 'UTF-8'); ?>" href="#" style="float:right;">Export</a>
            </div>
            <div class="table-responsive">
                <table id="table-<?php echo htmlspecialchars((string)$safeSlug, ENT_QUOTES, 'UTF-8'); ?>" class="display table table-bordered">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>IFSC</th>
                            <th>Account Name</th>
                            <th>Amount</th>
                            <th>Order ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $api_url = $website[1] . '?date=' . urlencode($date);
                        $site_info = fetch_remote_json($api_url);
                        if ($site_info !== null) {
                            foreach ($site_info as $attribute => $value) {
                                if (!is_array($value)) {
                                    continue;
                                }
                                $accNum = (string)($value['account_number'] ?? '');
                                $ifsc = (string)($value['ifsc'] ?? '');
                                $accName = (string)($value['account_name'] ?? '');
                                $rawAmt = $value['amount'] ?? 0;
                                $amountNum = is_numeric($rawAmt) ? (float)$rawAmt : 0.0;
                                $orderId = (string)($value['payout_order_id'] ?? '');

                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($accNum, ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td>' . htmlspecialchars($ifsc, ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td>' . htmlspecialchars($accName, ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td>' . htmlspecialchars((string)$rawAmt, ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '<td>' . htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '</tr>';
                                $total_credit += $amountNum;
                                $website_total += $amountNum;
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-danger">Error: Unable to fetch data from API</td></tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-primary"><b>Total Amount</b></td>
                            <td colspan="2"><b><?php echo number_format($website_total, 2, '.', ','); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <h3>
        Total Withdraw: <?php echo number_format($total_credit, 2, '.', ','); ?> <br>
    </h3>
</div>

<script src="tableHTMLExport.js"></script>
<?php $temp_date_time = date('d-m-Y_h_i_s'); ?>

<?php foreach ($websites as $website): 
    $safeSlug = preg_replace('/[^a-zA-Z0-9_\-]/', '-', (string)$website[0]);
?>
<script>
$('#exportbtn-<?php echo htmlspecialchars((string)$safeSlug, ENT_QUOTES, 'UTF-8'); ?>').on('click', function(e) {
    e.preventDefault();
    $("#table-<?php echo htmlspecialchars((string)$safeSlug, ENT_QUOTES, 'UTF-8'); ?>").tableHTMLExport({
        type: 'csv',
        filename: 'withdrawal_<?php echo htmlspecialchars((string)$safeSlug, ENT_QUOTES, 'UTF-8'); ?>_<?php echo $temp_date_time; ?>.csv'
    });
});
</script>
<?php endforeach; ?>

</body>
</html>
