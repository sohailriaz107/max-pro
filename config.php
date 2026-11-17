<?php 
$con = mysqli_connect("157.230.44.215","dpboss_user","LdgDDG6hFxAXJmk","dpboss_net_new");
// if (mysqli_connect_errno()) {
// 	$con = mysqli_connect("51.195.4.25","dpboss_user","LdgDDG6hFxAXJmk","dpboss_net");
// 	if (mysqli_connect_errno()) {
// 		$con = mysqli_connect("51.195.4.25","dpboss_user","LdgDDG6hFxAXJmk","dpboss_net");
// 	}
// 	if (mysqli_connect_errno()) {
// 		$con = mysqli_connect("51.195.4.25","dpboss_user","LdgDDG6hFxAXJmk","dpboss_net");
// 	}
// 	if (mysqli_connect_errno()) {
// 		$con = mysqli_connect("51.195.4.25","dpboss_user","LdgDDG6hFxAXJmk","dpboss_net");
// 	}
// }
date_default_timezone_set('Asia/Kolkata');


$service_json = realpath('/home/websites/maxpro.dpboss.town/dpboss-admin-third-party-firebase-adminsdk-flael-1b6231fe51.json'); // Update this path with service json file
define('SERVICE_ACCOUNT_KEY_FCM', $service_json);
?>
