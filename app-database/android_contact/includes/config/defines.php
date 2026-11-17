<?php

	// Define Error Messages
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	
	// Set Timezone
	date_default_timezone_set('Asia/Kolkata');
	
	// Start Session
	session_start();

        // Set Memory Limit
	ini_set("memory_limit", -1); 
	
	// Includes
	include_once("credentials.php");

	// Database Tables
	define("S3_DB_SETTINGS",				"s3_settings");
	define("S3_DB_USER_MANAGE",				"s3_users");
	define("S3_DB_USER_ROLES",				"s3_user_roles");
	define("S3_DB_LOG_USERS",				"s3_log_users");
	
	
	// Email Requirements
	
	
	// Other requirements
	$_user							=		$_SESSION['S3_CLIENT'];
	$_header						=		(isset($_REQUEST['header']))?$_REQUEST['header']=='true':true;
	$_footer						=		(isset($_REQUEST['footer']))?$_REQUEST['footer']=='true':true;
	$_design						=		(isset($_REQUEST['design']))?$_REQUEST['design']=='true':true;
	$_popup							=		(isset($_REQUEST['popup']))?$_REQUEST['popup']=='true':false;
	$_dialog						=		(isset($_REQUEST['dialog']))?$_REQUEST['dialog']=='true':false;
	$_format						=		(isset($_REQUEST['fmt']))?$_REQUEST['design']=='true':'';

	define("S3_SHOW_DESIGN",				(
		(isset($_REQUEST['design'])) ?($_REQUEST['design']=='true')
			:((isset($_REQUEST['fmt'])) ?false :true))
	);
	define("S3_SHOW_HEADER",				($_header && (!$_popup && !$_dialog)));
	define("S3_SHOW_FOOTER",				$_footer);
	define("S3_IS_DIALOG",					$_dialog);
	define("S3_IS_POPUP",					$_popup);
	
	// Variables
	$page_to_site					=		"";
	$web_url						=		(($_SERVER['SERVER_NAME'] != "")
											?(($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http')
												.'://'.$_SERVER['SERVER_NAME']
											:'http://iflinz.com').'/';
	$web_dir						=		$_SERVER['DOCUMENT_ROOT'].((substr($_SERVER['DOCUMENT_ROOT'], -1) == '/')?'':'/');
	$req_url						=		($_SERVER['REQUEST_URI'] != "")?substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI'])):'';
	$script_path					=		($_SERVER['SCRIPT_NAME'] != "")?substr($_SERVER['SCRIPT_NAME'], 1, strlen($_SERVER['SCRIPT_NAME'])):'';
	$redirect_url					=		($_SERVER['REDIRECT_URL'] != "")?substr($_SERVER['REDIRECT_URL'], 1, strlen($_SERVER['REDIRECT_URL'])):$req_url;
	$page_url						=		$web_url.$req_url;
	$page_dir						=		$web_dir.(($script_path)?$script_path:$req_url);
	define("S3_WEBHOME_URL",				$web_url);
	define("S3_WEBHOME_DIR",				$web_dir);
	define("S3_WEBSITE_HOST",				$web_url.$page_to_site);
	//define("S3_WEBSITE_URL",				$web_url.$page_to_site);
	define("S3_WEBSITE_URL",				'https://evijeta.com/admin-panel/');
	define("S3_WEBSITE_DIR",				$web_dir.(($page_to_site != "/")?$page_to_site:''));
	define("S3_PATH_TO_SITE",				$page_to_site);
	define("S3_WEBPAGE_URL",				$page_url);
	define("S3_WEBPAGE_DIR",				$page_dir);
	define("S3_REDIRECT_PATH",				$redirect_url);
	define("S3_REDIRECT_URL",				$web_url.$redirect_url);
	define("S3_REDIRECT_DIR",				$web_dir.$redirect_url); 
	
	// Portal Data
	define("S3_COMP_NAME",					"Recycle Machine");
	define("S3_COMP_NAMES_SHORT",			"Recycle Machine");
	define("S3_COMP_ADDR",					implode("<br />", array("CHENNAI")));
	/* define("S3_COMP_EMAIL",					"admin@gmail.in"); */
	define("S3_COMP_URL",					S3_WEBSITE_URL);
	define("S3_COMP_IMG_PATH",				"assets/img/logo/logo.jpg");
	define("S3_COMP_IMG",					S3_WEBSITE_URL.S3_COMP_IMG_PATH);
	define("S3_COMP_IMG_REL",				S3_WEBSITE_DIR.S3_COMP_IMG_PATH);
	
	// Form Defines
	define("S3_FORM_URL",					htmlspecialchars($_SERVER["PHP_SELF"]));
	define("S3_FORM_ERR_MSG_DEF",			"{field_name} is required . . .");
	define("S3_TABLE_ERR_MSG_DEF",			"<i class='fa fa-warning fa-fw'></i> No data found . . .");
	define("S3_FORM_LOCATION_DATA",			"");
	
	// Balance Defines
	define("S3_ADMIN_BALANCE_VIRTUAL",		true); /* Update this settings once on a fresh install to avoid calculation clash */
	
	define("S3_RECHARGE_TRANS_REFRESH",		5); /* In Minutes*/
	
?>