<div class="sidebar-menu">

		<div class="sidebar-menu-inner">
			
			<header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="update-results.php">
						<p style="color: #ffc3ab; font-size: 26px; font-weight: bold; text-shadow: 2px 2px 6px #000; margin: 0px;">AMUL</p>
					</a>
				</div>

				<!-- logo collapse icon -->
				<div class="sidebar-collapse">
					<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
						<i class="entypo-menu"></i>
					</a>
				</div>

								
				<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
				<div class="sidebar-mobile-menu visible-xs">
					<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
						<i class="entypo-menu"></i>
					</a>
				</div>

			</header>
			
									
			<ul id="main-menu" class="main-menu">
				<!-- add class "multiple-expanded" to allow multiple submenus to open -->
				<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
				<li>
					<a href="../index.php">
						<i class="entypo-home"></i>
						<span class="title">Back To Home Page</span>
					</a>
				</li>
				<!--<li>
					<a href="#">
						<i class="entypo-gauge"></i>
						<span class="title">Dashboard</span>
					</a>
				</li>-->
				<li class="has-sub <?php if ($pageName=='add-market'||$pageName=='edit-results'||$pageName=='update-results'||$pageName=='update-main-market-results') {echo 'opened';} ?>">
					<a href="#">
						<i class="entypo-layout"></i>
						<span class="title">Results</span>
					</a>
					<ul>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
						<li class="<?php if ($pageName=='add-market') {echo 'active';} ?>"><a href="add-market.php"><span class="title">Add Market</span></a></li>
						<li class="<?php if ($pageName=='edit-results') {echo 'active';} ?>"><a href="edit-results.php"><span class="title">Edit Result</span></a></li>
						<li class="<?php if ($pageName=='update-main-market-results') {echo 'active';} ?>"><a href="update-main-market-results.php"><span class="title">Update Main Market Result</span></a>
						</li>
						<li class="<?php if ($pageName=='update-2day-market-results') {echo 'active';} ?>"><a href="update-2day-market-results.php"><span class="title">Update 2day market results</span></a>
						</li>
<?php } } ?>


						<li class="<?php if ($pageName=='update-results') {echo 'active';} ?>"><a href="update-results.php"><span class="title">Update Result</span></a>
						</li>
					</ul>
				</li>

<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk'||$_SESSION['username']=='rohanji') {
?>
				<li class="<?php if ($pageName=='update-free-game') {echo 'active';} ?>">
					<a href="update-free-game.php">
						<i class="entypo-gauge"></i>
						<span class="title">Update Free Game</span>
					</a>
				</li>
				
				<li class="<?php if ($pageName=='update-free-game-sp') {echo 'active';} ?>">
					<a href="update-free-game-sp.php">
						<i class="entypo-gauge"></i>
						<span class="title">Update Free Game SP</span>
					</a>
				</li>
				
				
				<li class="<?php if ($pageName=='update-trick') {echo 'active';} ?>">
					<a href="update-trick.php">
						<i class="entypo-gauge"></i>
						<span class="title">Update Trick</span>
					</a>
				</li>
				
				<li class="<?php if ($pageName=='update-notice') {echo 'active';} ?>">
					<a href="update-notice.php">
						<i class="entypo-newspaper"></i>
						<span class="title">Update Notice</span>
					</a>
				</li>

				<li class="<?php if ($pageName=='x-golden-ank') {echo 'active';} ?>">
					<a href="x-golden-ank.php">
						<i class="entypo-cw"></i>
						<span class="title">Update X Golden Ank</span>
					</a>
				</li> 

<?php } } ?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
				<li class="<?php if ($pageName=='update-weekly') {echo 'active';} ?>">
					<a href="update-weekly.php">
						<i class="entypo-calendar"></i>
						<span class="title">Update Weekly</span>
					</a>
				</li>
				
				<li class="<?php if ($pageName=='update-weekly-sp') {echo 'active';} ?>">
					<a href="update-weekly-sp.php">
						<i class="entypo-calendar"></i>
						<span class="title">Update Weekly SP</span>
					</a>
				</li>
				
				

<?php } } ?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
				<li class="has-sub <?php if ($pageName=='panel-charts'||$pageName=='select-charts'||$pageName=='add-charts') {echo 'opened';} ?>">
					<a href="#">
						<i class="entypo-book-open"></i>
						<span class="title">All Charts</span>
					</a>
					<ul>
						<li class="<?php if ($pageName=='panel-charts') {echo 'active';} ?>"><a href="panel-charts.php"><span class="title">Edit Charts</span></a>
						</li>
						<li class="<?php if ($pageName=='select-charts'||$pageName=='add-charts') {echo 'active';} ?>"><a href="select-charts.php"><span class="title">Add Charts (Manually)</span></a>
						</li>
						</li>
					</ul>
				</li>
<?php } } ?>

<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
				<li class="has-sub <?php if ($pageName=='all-game-owners'||$pageName=='add-game-owners'||$pageName=='permissions') {echo 'opened';} ?>">
					<a href="#">
						<i class="entypo-users"></i>
						<span class="title">Game Owners</span>
					</a>
					<ul>
						<li class="<?php if ($pageName=='all-game-owners') {echo 'active';} ?>">
							<a href="all-game-owners.php">
								<span class="title">All Game Owners</span>
							</a>
						</li>
						<li class="<?php if ($pageName=='add-game-owners') {echo 'active';} ?>">
							<a href="add-game-owners.php">
								<span class="title">Add Game Owners</span>
							</a>
						</li>
					</ul>
				</li>
<?php } } ?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>

				<li class="<?php if ($pageName=='send-notification') {echo 'active';} ?>">
					<a href="send-notification.php">
						<i class="entypo-mail"></i>
						<span class="title">Send Notification</span>
					</a>
				</li>
<?php } } ?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk') {
?>
		<li class="has-sub <?php if ($pageName=='update-ad'||$pageName=='update-image') {echo 'opened';} ?>">
					<a href="#">
						<i class="entypo-cw"></i>
						<span class="title">Update</span>
					</a>
				<ul>
				<li class="<?php if ($pageName=='update-ad') {echo 'active';} ?>">
					<a href="update-ad.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Ad</span>
					</a>
				</li>
				
				<li class="<?php if ($pageName=='update-image') {echo 'active';} ?>">
					<a href="update-image.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Image</span>
					</a>
				</li>
				<li class="<?php if ($pageName=='update-date') {echo 'active';} ?>">
					<a href="update-date.php">
						<i class="entypo-back-in-time"></i>
						<span class="title">Update Date</span>
					</a>
				</li>
			</ul>
		</li>

<?php } } ?>
<?php 
if (isset($_SESSION['username'])) {
  if ($_SESSION['username']=='vk'||$_SESSION['username']=='rohanji') {
?>
				
				<!--<li class="<?php if ($pageName=='update-ad-fix-game') {echo 'active';} ?>">
					<a href="update-ad-fix-game.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Ad Fix Game</span>
					</a>
				</li>
				<li class="<?php if ($pageName=='update-cycle-motor-patti') {echo 'active';} ?>">
					<a href="update-cycle-motor-patti.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Cycle Motor Patti</span>
					</a>
				</li>
				<li class="<?php if ($pageName=='update-random-4-ank') {echo 'active';} ?>">
					<a href="update-random-4-ank.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Random 4 Ank</span>
					</a>
				</li> -->
				
				<li class="<?php if ($pageName=='update-live-result-ad') {echo 'active';} ?>">
					<a href="update-live-result-ad.php">
						<i class="entypo-cw"></i>
						<span class="title">Update Live Result Ad</span>
					</a>
				</li>
				
				
				<li class="<?php if ($pageName=='settings') {echo 'active';} ?>">
					<a href="settings.php">
						<i class="entypo-cw"></i>
						<span class="title">Settings</span>
					</a>
				</li>

				<li class="<?php if ($pageName=='expiry-setting') {echo 'active';} ?>">
					<a href="expiry-setting.php">
						<i class="entypo-cw"></i>
						<span class="title">Starline Expiry</span>
					</a>
				</li>

<?php } } ?>
				<li>
					<a href="logout.php">
						<i class="entypo-logout"></i>
						<span class="title">Logout</span>
					</a>
				</li>
			</ul>
			
		</div>

	</div>