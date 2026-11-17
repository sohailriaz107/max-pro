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
				<li class="<?php if ($pageName=='update-results') {echo 'active';} ?>"><a href="update-results.php"><i class="entypo-cw"></i><span class="title">Update Result</span></a>
				</li>
				<li class="<?php if ($pageName=='update-2day-market-results') {echo 'active';} ?>"><a href="update-2day-market-results.php"><i class="entypo-cw"></i><span class="title">Update 2day market results</span></a>
						</li>
				<li class="<?php if ($pageName=='panel-charts') {echo 'active';} ?>"><a href="panel-charts.php"><i class="entypo-cw"></i><span class="title">Edit Charts</span></a>
				</li>
<?php
$sql_query="SELECT * FROM admins WHERE id=".$_SESSION['id'];
$result_set=mysqli_query($con,$sql_query);
$fetched_row=mysqli_fetch_array($result_set,MYSQLI_ASSOC);
if (!empty($fetched_row['permissions'])) { ?>
				<li class="<?php if ($pageName=='free-game') {echo 'active';} ?>"><a href="free-game.php"><i class="entypo-cw"></i><span class="title">Free Game</span></a>
				</li>
<?php } ?>
				

				<li>
					<a href="logout.php">
						<i class="entypo-logout"></i>
						<span class="title">Logout</span>
					</a>
				</li>
			</ul>
			
		</div>

	</div>