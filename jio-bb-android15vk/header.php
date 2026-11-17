<?php
declare(strict_types=1);

$expirtyFile = __DIR__ . '/../txtdb/xzavierJoaquinosia.txt';
$expiryLines = (file_exists($expirtyFile) && is_readable($expirtyFile)) ? (file($expirtyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []) : [];

$userId = (int)($_SESSION['id'] ?? 0);
$expiryPermissions = [];

if ($userId > 0 && isset($con) && $con instanceof mysqli) {
    try {
        $stmt = mysqli_prepare($con, "SELECT permissions FROM admins WHERE id = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($res && ($result = mysqli_fetch_assoc($res))) {
                $expiryPermissions = explode(',', (string)($result['permissions'] ?? ''));
            }
            mysqli_stmt_close($stmt);
        }
    } catch (Throwable $e) {
        error_log("Header permissions query error: " . $e->getMessage());
    }
}

$counter = 0;
foreach ($expiryLines as $line) {
    $parts = explode("|", (string)$line);
    $gName = trim((string)($parts[1] ?? ''));
    if ($gName !== '' && in_array($gName, $expiryPermissions, true)) {
        $counter++;
    }
}

$noti = 0;
$notiNumber = '0';

if ($counter >= 1) {
    foreach ($expiryLines as $line) {
        $parts = explode("|", (string)$line);
        $gName = trim((string)($parts[1] ?? ''));
        $expiryDate = trim((string)($parts[12] ?? $parts[13] ?? ''));

        if ($gName !== '' && in_array($gName, $expiryPermissions, true) && $expiryDate !== '') {
            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 
            
            if ($calcDiff >= 0 && $calcDiff <= 5) {
                $t1 = strtotime($date1);
                $t2 = strtotime($date2);
                if ($t1 !== false && $t2 !== false && $t1 <= $t2) {
                    $noti++;
                }
            }
        }
    }
    $notiNumber = (string)$noti;
}

$currentUsername = (string)($_SESSION['username'] ?? '');
?>	
<div class="col-md-6 col-sm-8 clearfix">
    <ul class="user-info pull-left pull-none-xsm">
        <li class="profile-info dropdown">
            <a href="#">
                Welcome, <b><?php echo htmlspecialchars($currentUsername, ENT_QUOTES, 'UTF-8'); ?></b>
            </a>
        </li>
    </ul>
    
    <ul class="user-info pull-left pull-right-xs pull-none-xsm">
        <li class="notifications dropdown <?php if ($currentUsername !== 'vk' && $noti >= 1) { echo 'open'; } ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <i class="entypo-attention"></i>
                <span class="badge badge-info">
                    <?php echo htmlspecialchars($notiNumber, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </a>

            <ul class="dropdown-menu">
                <li class="top">
                    <p class="small">
                        You have <?php echo ($noti >= 1) ? $noti : 'no'; ?> new notifications.
                    </p>
                </li>
                
                <li>
                    <ul class="dropdown-menu-list scroller">
<?php 
if ($counter >= 1) {
    foreach ($expiryLines as $line) {
        $parts = explode("|", (string)$line);
        $gName = trim((string)($parts[1] ?? ''));
        $expiryDate = trim((string)($parts[12] ?? $parts[13] ?? ''));

        if ($gName !== '' && in_array($gName, $expiryPermissions, true) && $expiryDate !== '') {
            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 
            $t1 = strtotime($date1);
            $t2 = strtotime($date2);

            if ($calcDiff >= 1 && $calcDiff <= 5 && $t1 !== false && $t2 !== false && $t1 < $t2) {
?>
                        <li class="unread notification-info">
                            <a href="#">
                                <i class="entypo-info pull-right"></i>
                                <span class="line">
<?php if ($currentUsername === 'vk') { ?>
                                    <strong><?php echo htmlspecialchars($gName, ENT_QUOTES, 'UTF-8'); ?> is going to expire in <?php echo $calcDiff; ?> days</strong>
<?php } else { ?>
                                    <strong>You have <?php echo $calcDiff; ?> days left to renew <?php echo htmlspecialchars($gName, ENT_QUOTES, 'UTF-8'); ?></strong>
<?php } ?>
                                </span>
                            </a>
                        </li>
<?php
            } elseif ($calcDiff === 0 && $t1 !== false && $t2 !== false) {
?>
                        <li class="unread notification-danger">
                            <a href="#">
                                <i class="entypo-cancel-circled pull-right"></i>
                                <span class="line">
<?php if ($currentUsername === 'vk') { ?>
                                    <strong><?php echo htmlspecialchars($gName, ENT_QUOTES, 'UTF-8'); ?> has expired. Please remind user to renew.</strong>
<?php } else { ?>
                                    <strong>Your plan has expired for <?php echo htmlspecialchars($gName, ENT_QUOTES, 'UTF-8'); ?>. Please Renew it as soon as possible.</strong>
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
