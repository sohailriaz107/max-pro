<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$expirtyFile = '../txtdb/xzavierJoaquinosia.txt';
$expiryLines = file_exists($expirtyFile) ? (file($expirtyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
$expiryPermissions = [];

if ($userId > 0 && isset($con) && $con instanceof mysqli) {
    $stmt = mysqli_prepare($con, "SELECT id, permissions FROM admins WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res && $row = mysqli_fetch_assoc($res)) {
            $expiryPermissions = array_filter(array_map('trim', explode(',', (string)($row['permissions'] ?? ''))));
        }
        mysqli_stmt_close($stmt);
    }
}

$counter = 0;
foreach ($expiryLines as $line) {
    $parts = array_pad(explode("|", trim((string)$line)), 13, '');
    $gameName = $parts[1];
    if (in_array($gameName, $expiryPermissions, true)) {
        $counter++;
    }
}

$noti = 0;
if ($counter >= 1) {
    $date1 = str_replace('/', '-', date('Y-m-d'));
    foreach ($expiryLines as $line) {
        $parts = array_pad(explode("|", trim((string)$line)), 13, '');
        $gameName = $parts[1];
        $expiryDate = $parts[12];
        if (in_array($gameName, $expiryPermissions, true)) {
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2);
            if ($calcDiff >= 0 && $calcDiff <= 5) {
                if (strtotime($date1) <= strtotime($date2)) {
                    $noti++;
                }
            }
        }
    }
    $notiNumber = (string)$noti;
} else {
    $notiNumber = '0';
}
$username = (string)($_SESSION['username'] ?? '');
?>
<div class="col-md-6 col-sm-8 clearfix">
    <ul class="user-info pull-left pull-none-xsm">
        <li class="profile-info dropdown">
            <a href="#">
                Welcome, <b><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></b>
            </a>
        </li>
    </ul>
    
    <ul class="user-info pull-left pull-right-xs pull-none-xsm">
        <li class="notifications dropdown <?php if ($username !== 'vk' && (int)$notiNumber >= 1) { echo 'open'; } ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <i class="entypo-attention"></i>
                <span class="badge badge-info"><?php echo htmlspecialchars($notiNumber, ENT_QUOTES, 'UTF-8'); ?></span>
            </a>

            <ul class="dropdown-menu">
                <li class="top">
                    <p class="small">
                        You have <?php echo ($noti >= 1) ? htmlspecialchars((string)$noti, ENT_QUOTES, 'UTF-8') : 'no'; ?> new notifications.
                    </p>
                </li>
                
                <li>
                    <ul class="dropdown-menu-list scroller">
<?php 
if ($counter >= 1) {
    $date1 = str_replace('/', '-', date('Y-m-d'));
    foreach ($expiryLines as $line) {
        $parts = array_pad(explode("|", trim((string)$line)), 13, '');
        $gameName = $parts[1];
        $expiryDate = $parts[12];
        if (in_array($gameName, $expiryPermissions, true)) {
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2);
            $safeGame = htmlspecialchars($gameName, ENT_QUOTES, 'UTF-8');
            $safeDiff = htmlspecialchars((string)$calcDiff, ENT_QUOTES, 'UTF-8');
            if ($calcDiff >= 1 && $calcDiff <= 5) {
                if (strtotime($date1) < strtotime($date2)) {
?>
                    <li class="unread notification-info">
                        <a href="#">
                            <i class="entypo-info pull-right"></i>
                            <span class="line">
<?php if ($username === 'vk') { ?>
                                <strong><?php echo $safeGame; ?> is going to expire in <?php echo $safeDiff; ?> days</strong>
<?php } else { ?>
                                <strong>You have <?php echo $safeDiff; ?> days left to renew <?php echo $safeGame; ?></strong>
<?php } ?>
                            </span>
                        </a>
                    </li>
<?php
                }
            } elseif ($calcDiff === 0) {
?>
                    <li class="unread notification-danger">
                        <a href="#">
                            <i class="entypo-cancel-circled pull-right"></i>
                            <span class="line">
<?php if ($username === 'vk') { ?>
                                <strong><?php echo $safeGame; ?> has expired. Please remind user to renew.</strong>
<?php } else { ?>
                                <strong>Your plan has expired for <?php echo $safeGame; ?>. Please Renew it as soon as possible.</strong>
<?php } ?>
                            </span>
                        </a>
                    </li>
<?php
            }
        }
    }
}
?>									
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- Raw Links -->
<div class="col-md-6 col-sm-4 clearfix hidden-xs">
    <ul class="list-inline links-list pull-right">
        <li>
            <a href="logout.php">
                Log Out <i class="entypo-logout right"></i>
            </a>
        </li>
    </ul>
</div>
