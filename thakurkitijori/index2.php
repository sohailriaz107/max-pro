<?php
date_default_timezone_set('Asia/Kolkata');
$date = isset($_GET['date']) ? filter_var($_GET['date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : date('Y-m-d');
$websites = array(
    array("Matka booking", "https://matkabooking.app/api_v2_2021/back_info_imp2.php"),
	array("Paytm Matka", "https://paytmmatka.com/api_v2_2021/back_info_imp2.php"),
    array("Dpbossplay", "https://dpbossfix.net/api_v2_2021/back_info_imp2.php"),
    array("jodi99", "https://jodi99.com/api_v2_2021/back_info_imp2.php")
    
    // Add more websites as needed
);
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
	
	<?php if(0){?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
	<?php }?>
	
</head>
<body>

<div class="container">
<br><br><br>
    <h2 class="text-center">🤑 Daily Withdraw Report 🤑</h2>
    <!-- Date input box and button -->
    <form class="form-inline mb-3 justify-content-center" method="GET">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" class="form-control mx-2" value="<?php echo $date; ?>">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="row">
        <?php 
        $total_credit = 0;
        foreach ($websites as $website) : 
		$website_total = 0;
		?>
        <div class="col-md-12">
            <div style="display: block;"><h3 style="display: inline-block;"><?php echo $website[0]; ?></h3> 
			<a class="btn btn-primary" id="exportbtn-<?php echo str_replace(' ', '-', $website[0]); ?>" href="#" style="float:right;">Export</a></div>
            <div class="table-responsive">
                <table id="table-<?php echo str_replace(' ', '-', $website[0]); ?>" class="display table table-bordered">
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
                        $api_url = $website[1] . '?date=' . $date;
                        $response = file_get_contents($api_url);
                        if ($response !== false) {
                            $site_info = json_decode($response, true);
                            if ($site_info !== null) {
                                foreach ($site_info as $attribute => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['account_number'] . '</td>';
                                    echo '<td>' . $value['ifsc'] . '</td>';
                                    echo '<td>' . $value['account_name'] . '</td>';
                                    echo '<td>' . $value['amount'] . '</td>';
                                    echo '<td>' . $value['payout_order_id'] . '</td>';
                                    echo '</tr>';
                                    $total_credit += $value['amount']; // Adjust as needed
                                    $website_total += $value['amount']; // Adjust as needed
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-danger">Error: Unable to decode JSON response</td></tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-danger">Error: Unable to fetch data from API</td></tr>';
                        }
                        ?>
						<tr><td colspan="3" class="text-primary"><b>Total Amount</b> </td> <td colspan="2"> <b><?php echo $website_total;?></b></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <h3>
        Total Withdraw: <?php echo number_format($total_credit, 2, '.', ',');?> <br>
    </h3>
</div>


  <script src="tableHTMLExport.js"></script>
<?php $temp_date_time = date('d-m-Y_h_i_s');?>

<?php foreach ($websites as $website) : ?>

<script>

$('#exportbtn-<?php echo str_replace(' ', '-', $website[0]); ?>').on('click',function(){
    $("#table-<?php echo str_replace(' ', '-', $website[0]); ?>").tableHTMLExport({type:'csv',filename:'withdrawal_<?php echo str_replace(' ', '-', $website[0]); ?><?php echo $temp_date_time;?>.csv'});
  })
  
  
</script>
<?php endforeach; ?>

<?php if(0){?>
<script>
$(document).ready(function() {
    <?php foreach ($websites as $website) : ?>
        $('#table-<?php echo str_replace(' ', '-', $website[0]); ?>').DataTable({
            "responsive": "true",
			"bPaginate": false,
			"paging":   false,
			dom: 'Bfrtip',
			 
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
			language: {
				paginate: {
				  next: '&#8594;', // or '→'
				  previous: '&#8592;' // or '←' 
				}
			  }
        });
    <?php endforeach; ?>
});
</script>

<?php } ?>

</body>
</html>
