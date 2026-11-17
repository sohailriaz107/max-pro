<?php
declare(strict_types=1);

$pageName = $pageName ?? '';
$permissionsExist = false;

if (isset($_SESSION['id']) && is_numeric($_SESSION['id']) && isset($con) && $con instanceof mysqli) {
    $stmtMenu = mysqli_prepare($con, "SELECT permissions FROM admins WHERE id = ?");
    if ($stmtMenu) {
        $uId = (int)$_SESSION['id'];
        mysqli_stmt_bind_param($stmtMenu, "i", $uId);
        mysqli_stmt_execute($stmtMenu);
        $resMenu = mysqli_stmt_get_result($stmtMenu);
        if ($resMenu && $rowMenu = mysqli_fetch_assoc($resMenu)) {
            $permissionsExist = !empty($rowMenu['permissions']);
        }
        mysqli_stmt_close($stmtMenu);
    }
}
?>
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
				<a href="#" class="sidebar-collapse-icon">
					<i class="entypo-menu"></i>
				</a>
			</div>
							
			<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
			<div class="sidebar-mobile-menu visible-xs">
				<a href="#" class="with-animation">
					<i class="entypo-menu"></i>
				</a>
			</div>
		</header>
								
		<ul id="main-menu" class="main-menu">
			<li>
				<a href="../index.php">
					<i class="entypo-home"></i>
					<span class="title">Back To Home Page</span>
				</a>
			</li>
			<li class="<?php if ($pageName === 'update-results') { echo 'active'; } ?>">
				<a href="update-results.php"><i class="entypo-cw"></i><span class="title">Update Result</span></a>
			</li>
			<li class="<?php if ($pageName === 'update-2day-market-results') { echo 'active'; } ?>">
				<a href="update-2day-market-results.php"><i class="entypo-cw"></i><span class="title">Update 2day market results</span></a>
			</li>
			<li class="<?php if ($pageName === 'panel-charts') { echo 'active'; } ?>">
				<a href="panel-charts.php"><i class="entypo-cw"></i><span class="title">Edit Charts</span></a>
			</li>
<?php if ($permissionsExist) { ?>
			<li class="<?php if ($pageName === 'free-game') { echo 'active'; } ?>">
				<a href="free-game.php"><i class="entypo-cw"></i><span class="title">Free Game</span></a>
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
