<?php
$date = isset($_GET['date']) ? filter_var($_GET['date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : date('Y-m-d');
$websites = array(
    array("Onlinematkaplay.com", "https://onlinematkaplay.com/api_v2_2021/back_info_imp.php"),
    array("Matkaofice.mobi", "https://matkaoffice.mobi/api/back_info_imp.php"),
	array("matkaonline.net", "https://matkaonline.net/api_v2_2021/back_info_imp.php"),
    array("Matkabank.com", "https://matkabank.com/api_v4/back_info_imp.php"),
	array("Matkabank.mobi", "https://matkabank.mobi/api/back_info_imp.php"),
	array("matkaplay.site", "https://matkaplay.site/api_v2_2021/back_info_imp.php"),
	array("dhanjito.net", "https://dhanjito.net/api_v2_2021/back_info_imp.php"),
    array("Sachin99", "https://sachin99.com/api_v4/back_info_imp.php"),
    // Add more websites as needed
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Private Data</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container {
      margin-top: 50px;
    }
	.table td, .table th {
  padding: 2px;
  margin: 2px;
  font-size: 12px;
}
h3{
	font-size: 14px;
}
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center">🤑 Dipak Ki Tijori 🤑</h2>
    <!-- Date input box and button -->
    <form class="form-inline mb-3 justify-content-center" method="GET">
      <label for="date">Select Date:</label>
      <input type="date" id="date" name="date" class="form-control mx-2" value="<?php echo $date; ?>">
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="row">
      <?php 
		$total_credit =0;
		$total_debit =0;
	  foreach ($websites as $website) : ?>
        <div class="col-md-4">
          <h3><?php echo $website[0]; ?></h3>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Attribute</th>
                  <th>Value</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $api_url = $website[1] . '?date=' . $date;
                  $response = file_get_contents($api_url);
				  $i=0;
                  if ($response !== false) {
                    $site_info = json_decode($response, true);
                    if ($site_info !== null) {
                      foreach ($site_info as $attribute => $value) {
						  $i++;
						  if($i==6){
						  $total_credit = $total_credit + $value;
						  }elseif($i == 7){
						  $total_debit = $total_debit + $value;
						  }
                        echo '<tr>';
                        echo '<td>' . ucfirst(str_replace('_', ' ', $attribute)) . '</td>';
                        echo '<td>' . $value . '</td>';
                        echo '</tr>';
                      }
                    } else {
                      echo '<tr><td colspan="2" class="text-danger">Error: Unable to decode JSON response</td></tr>';
                    }
                  } else {
                    echo '<tr><td colspan="2" class="text-danger">Error: Unable to fetch data from API</td></tr>';
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
	
<p>
    Total Credit : <?php echo number_format($total_credit, 2, '.', ',');?> <br>
    Total Debit : <?php echo number_format($total_debit, 2, '.', ',');?> <br>
    Profit/Loss : 
    <?php 
        $profit_loss = $total_credit - $total_debit;
        if ($profit_loss >= 0) {
            echo '<span style="color: green;">+';
        } else {
            echo '<span style="color: red;">-';
        }
        echo number_format(abs($profit_loss), 2, '.', ',');
        echo '</span>';
    ?>
</p>


  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
