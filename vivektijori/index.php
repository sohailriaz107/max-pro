<?php
declare(strict_types=1);

$rawDate = isset($_GET['date']) ? (string)$_GET['date'] : date('Y-m-d');
$date = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawDate) ? $rawDate : date('Y-m-d');

$websites = array(
    array("Dpboss Play", "https://matkaji.games/api_v2_2021/back_info_imp.php"),
    array("Ratan777", "https://ratan777.app/api_v2_2021/back_info_imp.php"),
    //array("Matka Booking", "https://matkabooking.app/api_v2_2021/back_info_imp.php"),
    //array("PMO", "https://matkaplayonline.games/api_ver3/back_info_imp.php"),
    array("jodi99", "https://jodi99.com/api_v2_2021/back_info_imp.php"),
    array("Kalyan Morning", "https://kalyanmorning.mobi/api_ver3/back_info_imp.php"),
    //array("Ramdev Matka", "https://ramdevmatka.com/api_v2_2021/back_info_imp.php"),
    //array("Leo777", "https://leo777.games/api_ver3/back_info_imp.php"),
    array("Dp777.com", "https://dp777.app/api_v2_2021/back_info_imp.php"),
    array("Jiogames.app", "https://jiogames.app/api_v2_2021/back_info_imp.php"),
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
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center">&#129297; Vivek Ki Tijori &#129297;</h2>
    <!-- Date input box and button -->
    <form class="form-inline mb-3 justify-content-center" method="GET">
      <label for="date">Select Date:</label>
      <input type="date" id="date" name="date" class="form-control mx-2" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="row">
      <?php 
        $total_credit = 0.0;
        $total_debit = 0.0;
        foreach ($websites as $website) : ?>
        <div class="col-md-4">
          <h3><?php echo htmlspecialchars((string)$website[0], ENT_QUOTES, 'UTF-8'); ?></h3>
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
                  $api_url = $website[1] . '?date=' . urlencode($date);
                  $site_info = fetch_remote_json($api_url);
                  $i = 0;
                  if ($site_info !== null) {
                    foreach ($site_info as $attribute => $value) {
                      $i++;
                      $numVal = is_numeric($value) ? (float)$value : 0.0;
                      if ($i === 6) {
                        $total_credit += $numVal;
                      } elseif ($i === 7) {
                        $total_debit += $numVal;
                      }
                      echo '<tr>';
                      echo '<td>' . htmlspecialchars(ucfirst(str_replace('_', ' ', (string)$attribute)), ENT_QUOTES, 'UTF-8') . '</td>';
                      echo '<td>' . (is_numeric($value) ? number_format((float)$value) : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8')) . '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="2" class="text-danger">Error: Unable to fetch or decode data from API</td></tr>';
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
	
    <p>
      Total Credit : <?php echo number_format($total_credit, 2, '.', ','); ?> <br>
      Total Debit : <?php echo number_format($total_debit, 2, '.', ','); ?> <br>
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
