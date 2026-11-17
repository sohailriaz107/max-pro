<?php
$expirtyFile='../txtdb/xzavierJoaquinosia.txt';

$expiryLines= file($expirtyFile);
$ExpiryQuery=mysqli_query($con,"SELECT id, permissions FROM admins WHERE id=".$_SESSION['id']);
while($result=mysqli_fetch_array($ExpiryQuery))
{
  $expiryPermissions = explode(',',$result['permissions']);
}
$counter = 0;
foreach ($expiryLines as $line_num => $line) {

  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status)=explode("|", $line);
  if (in_array($gameName, $expiryPermissions)) {
	$counter++;
}
}
?>
<!-- test start -->
<?php 
if ($counter>=1) {
$noti = 0;
	foreach ($expiryLines as $line_num => $line) {

  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate)=explode("|", $line);
  if (in_array($gameName, $expiryPermissions)) {
		// start date difference logic

            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 
  		//end date difference logic
        if ($calcDiff>=0 && $calcDiff<=5) {
        	if (strtotime($date1)<=strtotime($date2)) {
        		$noti++;
?>
<?php
}
}

}
}
$notiNumber = $noti;
}
else{
$notiNumber = '0';
}
?>	
<!-- test end -->
<div class="col-md-6 col-sm-8 clearfix">
		
				<ul class="user-info pull-left pull-none-xsm">
		
					<!-- Profile Info -->
					<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
		
						<a href="#">
							Welcome, <b><?php echo $_SESSION['username']; ?></b>
						</a>
					</li>
		
				</ul>
				
				<ul class="user-info pull-left pull-right-xs pull-none-xsm">
		
					<!-- Raw Notifications -->
					<li class="notifications dropdown <?php if ($_SESSION['username']!='vk') { if($notiNumber>=1){echo 'open';}} ?>">
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							<i class="entypo-attention"></i>
							<span class="badge badge-info">
<?php echo $notiNumber; ?>
							</span>
						</a>
		
						<ul class="dropdown-menu">
							<li class="top">
								<p class="small">
									<?php if ($_SESSION['username']=='vk') {?>
										<!-- <a href="#" class="pull-right">Mark all Read</a> -->
									<?php } ?>
									You have <?php if ($noti>=1) {echo $noti;}else {echo 'no';}?> new notifications.
								</p>
							</li>
							
							<li>
								<ul class="dropdown-menu-list scroller">
<?php 
if ($counter>=1) {
	foreach ($expiryLines as $line_num => $line) {

  list($order, $gameName, $gameResult, $timeOpen, $timeClose, $gameBgcolor, $gameFontcolor, $frequency, $liveTimeBefore, $liveTimeAfter, $updateDate, $status, $expiryDate)=explode("|", $line);
  if (in_array($gameName, $expiryPermissions)) {
		// start date difference logic

            $date1 = str_replace('/', '-', date('Y-m-d')); 
            $date2 = str_replace('/', '-', $expiryDate);
            $calcDiff = dateDiffInDays($date1, $date2); 
  		//end date difference logic
        if ($calcDiff>=1 && $calcDiff<=5) {
        	if (strtotime($date1)<strtotime($date2)) {
?>
									<li class="unread notification-info">
										<a href="#">
											<i class="entypo-info pull-right"></i>

											<span class="line">
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<strong><?php echo $gameName ?> is going to expire in <?php echo $calcDiff ?> days</strong>
<?php }else{ ?>

												<strong>You have <?php echo $calcDiff ?> days left to renew <?php echo $gameName ?></strong>
<?php }} ?>
											</span>
										</a>
									</li>
<?php
}
}
elseif ($calcDiff==0) {
?>
<li class="unread notification-danger">
										<a href="#">
											<i class="entypo-cancel-circled pull-right"></i>

											<span class="line">
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
<strong><?php echo $gameName ?> has expired. Please remind user to renew.</strong>
<?php }else{ ?>

												<strong>Your plan has expired for <?php echo $gameName ?>. Please Renew it as soon as possible.</strong>
<?php }} ?>
											</span>
										</a>
									</li>
<?php
}// end else if differenece is zero
else{

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