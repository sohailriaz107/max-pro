<?php

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	// Includes
	@include_once("mysql.php");
	@include_once("connection.php");
	
	
	$db_obj = new connection;
	
/*	==================================================================
	   General Functions required for portal
	================================================================== */

	// Function for printing array/object elements
	function _echo($arr){
		echo "\n<pre>";
		print_r($arr);
		echo "</pre>\n";
	}
	
	function _output_json($data){
		header("Content-Type:application/json");
		echo json_encode($data);
	}
	
	function _var(){
		$args	=	func_get_args();
		if(isset($args) && is_array($args)){
			foreach($args as $a){
				if(isset($a) && ($a != "" || (is_array($a) && count($a) > 0)))
				return $a;
			}
		}
	}
	
	function _safe_vars(){
		$db		=	$GLOBALS['db'];
		$args	=	func_get_args();
		$args	=	(is_array($args) && count($args) == 1)?$args[0]:$args;
		if(isset($args) && is_array($args)){
			foreach($args as $k=>$d){
				$args[$k]	=	is_array($d)?_safe_vars($d):$db->quote($d);
			}
		}
		return $args;
	}
	
	function _remove_single_quotes(){
		$args	=	func_get_args();
		$args	=	(is_array($args) && count($args) == 1)?$args[0]:$args;
		if(isset($args) && is_array($args)) foreach($args as $k=>$d)
		$args[$k]	=	is_array($d)?_remove_single_quotes($d):trim($d, "'");
		return $args;
	}
	
	function _now(){
		$timezone = new DateTimeZone("Asia/Kolkata");
		return date("Y-m-d H:i:s");
	}
	
	function _time_diff($date, $fmt){
		$date_1		=	date_create($date);
		$date_2		=	date_create(date("Y-m-d H:i:s"));
		$format		=	($fmt)?$fmt:'%i';
		$interval	=	date_diff($date_1, $date_2);
		return $interval->format($fmt);
	}
	
	function _time_passed($date){
		$format		=	'{"Yrs":%y, "Mth":%m, "Day":%d, "Hrs":%h, "Min":%i, "Sec":%s}';
		$dt_out		=	@json_decode(_time_diff($date, $format), true);
		$output		=	'';
		foreach($dt_out as $t=>$v) if($v > 0) $output .= "$v $t ";
		return $output;
	}
	
	function is_date($dt){
		return strtotime(date("Y-m-d H:i:s", strtotime($dt))) > 0;
	}
	
	function _time_diff_between($date1, $date2, $fmt){
		$date_1		=	date_create($date1);
		$date_2		=	date_create($date2);
		$format		=	($fmt)?$fmt:'%i';
		$interval	=	date_diff($date_1, $date_2);
		return $interval->format($fmt);
	}
	
	function _time_passed_between($date1, $date2){
		$date1		=	date("Y-m-d H:i:s", strtotime($date1));
		$date2		=	date("Y-m-d H:i:s", strtotime($date2));
		$format		=	'{"Year":%y, "Month":%m, "Day":%d, "Hours":%h, "Minutes":%i, "Seconds":%s}';
		$dt_out		=	@json_decode(_time_diff_between($date1, $date2, $format), true);
		$output		=	'';
		
		foreach($dt_out as $t=>$v){
			$t		.=	($v > 1)?'s':'';
			if($v > 0) $output .= "$v $t ";
		}
		$output		=	($output == '')?'0 Seconds':$output;
		
		return $output;
	}
	
	function _minute_passed($date){
		$now		=	strtotime("now");
		$date		=	strtotime($date);
		$a_min		=	60;
		$diff		=	round(($now - $date)/$a_min);
		return $diff;
	}
	
	function _hour_passed($date){
		$now		=	strtotime("now");
		$date		=	strtotime($date);
		$an_hour	=	60*60;
		$diff		=	round(($now - $date)/$an_hour);
		return $diff;
	}
	
	function _day_passed($date){
		$now		=	strtotime("now");
		$date		=	strtotime($date);
		$a_day		=	60*60*24;
		$diff		=	round(($now - $date)/$a_day);
		return $diff;
	}
	
	function _day_passed_between($date1, $date2){
		$now		=	strtotime($date2);
		$date		=	strtotime($date1);
		$a_day		=	60*60*24;
		$diff		=	round(($now - $date)/$a_day);
		return $diff;
	}
	
	function array_contains($str, array $arr){
		foreach($arr as $k=>$a) {
			if (stripos($a,$str) !== false) return $k;
		}
		return false;
	}
	
	function array_is_associative(array $arr){
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
	
	function toTel($ph){
		if($ph != ""){
			$ph_split	=	explode("|", $ph);
			$ph			=	"( ".$ph_split[0]." ) - ".$ph_split[1]." - ".$ph_split[2];
		}
		return $ph;
	}
	
	function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		
		return $val;
	}
	
	function is_local_addr(){
		$is_local	=	(
							// 1) Step One - Check for Local IP
							($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ||
							preg_match('/192.168.^/', $_SERVER['REMOTE_ADDR']) ||
							preg_match('/:\//', $_SERVER['DOCUMENT_ROOT']) ||
							preg_match('/^@localhost/', $_SERVER['SERVER_ADMIN'])
						);
		
		return $is_local;
	}
	
	function _is_hacker($txt){
		return preg_match("/[\'\"=+-]/", $txt);
	}
	
	function _valid_username($usr){
		return preg_match('/^[a-zA-Z0-9]+$/', $usr);
	}

	function file_uploader($file, $path, $rename_file=""){
		$is_uploaded	=	false;
		$chk_req4upload	=	true;
		$target_path	=	'';
		$error_msg		=	"Invalid File / No File Found . . .";
		
		if(isset($file) && is_array($file) && $file['name'] != "" && $path != ""){
			// Upload to Directory
			$file_name		=	basename( $file['name']);
			$target_path	=	$path.(($rename_file != "")?$rename_file:$file_name);
			$file_ext		=	@end(explode(".", $file["name"]));
			
			// Check if the directory available
			if(file_exists($path)){
				if(!is_writable($path)){
					if(!chmod($path, 777)){
						$chk_req4upload	=	false;
						$error_msg		=	"Cannot change permission for upload folder . . .";
					}
				}
			}
			else if(!mkdir($path, 0777)){
				$chk_req4upload	=	false;
				$error_msg		=	"Cannot create directory in upload folder . . .";
			}
			
			// Check for Upload Errors
			if($chk_req4upload){
				if($file['error'] <= 0){
					if(@move_uploaded_file($file['tmp_name'], $target_path)){
						$is_uploaded	=	true;
						$error_msg		=	"File Uploaded Successfully . . .";
					}
					else{
						$is_uploaded	=	false;
						$error_msg		=	"Error Uploading File '$file_name' . . .";
					}
				}
				else{
					$is_uploaded	=	false;
					$error_msg		=	"Upload Error for File '$file_name': ".$file['error']." . . .";
				}
			}
			else{
				$is_uploaded	=	$chk_req4upload;
			}
		}
		
		return array($is_uploaded, $error_msg, $target_path);
	}
	
	function client_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
	
	function _log_user($log_txt, $log_user="", $log_no=""){
		global $user, $_user;
		
		$username	=	_var($log_user, $user['username'], $_user['username'], $_SESSION['S3_CLIENT']['username']);
		$user_id	=	_var($log_no, $user['sno'], $_user['sno'], $_SESSION['S3_CLIENT']['sno']);
		
		if($username != "" && $log_txt != ""){
			$user_ip		=	client_ip();
			$user_path		=	$_SERVER['REQUEST_URI'];
			$user_browser	=	$_SERVER['HTTP_USER_AGENT'];
			
			$log_sql		=	_qg("insert", S3_DB_LOG_USERS, array(
				'user_id'		=>	$user_id,
				'log_txt'		=>	$log_txt,
				'log_link'		=>	$user_path,
				'log_ip'		=>	$user_ip,
				'log_browser'	=>	$user_browser,
				'entry_on'		=>	_now()
			));
			$log_query		=	_query($log_sql);
			
			return $log_query['status'] == 'success';
		}
		
		return false;
	}
	
	function _log_txt($arr, $ins=true, $ext=''){
		$cont	=	"\n";
		
		if(is_array($arr) && count($arr) > 0){
			foreach($arr as $ak=>$av){
				$cont	.=	$ext."$ak => ".(is_array($av)?_log_txt($av, false, $ext."\t"):$av)."\n";
			}
		}
		else $cont = $arr;
		
		if($ins == true){
			$continue	=	true;
			$page_cont	=	"Log requested on ".date("dS M Y, h:i:s A")."\n".
							"=================================================================\n\n".
							$cont."\n";
			$page_cont	.=	"=================================================================\n\n\n";
			$folder		=	S3_WEBSITE_DIR."logs/";
			
			// Check for folder availability
			if(!is_dir($folder)) $continue =  false;
			
			// Add Year as folder
			if($continue){
				$folder	.=	date("Y")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Add Month as folder
			if($continue){
				$folder	.=	date("m")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Add Date as folder
			if($continue){
				$folder	.=	date("d")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Create File with today date and add the contents
			if($continue){
				$file	=	$folder."logs.txt";
				$f		=	@fopen($file, "a");
				@fwrite($f, $page_cont);
				@fclose($f);
			}
		}
		else return $cont;
	}

	function _log_req($arr, $ins=true, $ext=''){
		$cont	=	"\n";
		
		if(is_array($arr) && count($arr) > 0){
			foreach($arr as $ak=>$av){
				$cont	.=	$ext."$ak => ".(is_array($av)?_log_req($av, false, $ext."\t"):$av)."\n";
			}
		}
		else $cont = $arr;
		
		if($ins == true){
			$continue	=	true;
			$page_cont	=	"Log requested on ".date("dS M Y, h:i:s A")."\n".
							"=================================================================\n".
							$cont."\n";
			$page_cont	.=	"=================================================================\n\n\n";
			$folder		=	S3_WEBSITE_DIR."logs/";
			
			// Check for folder availability
			if(!is_dir($folder)) $continue =  false;
			
			// Add Year as folder
			if($continue){
				$folder	.=	date("Y")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Add Month as folder
			if($continue){
				$folder	.=	date("m")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Add Date as folder
			if($continue){
				$folder	.=	date("d")."/";
				if(!is_dir($folder)) $continue = mkdir($folder, 0777);
			}
			
			// Create File with today date and add the contents
			if($continue){
				$file	=	$folder."req.txt";
				$f		=	@fopen($file, "a");
				@fwrite($f, $page_cont);
				@fclose($f);
			}
		}
		else return $cont;
	}
	
	function brower_info($br){ 
		$u_agent = _var($br, $_SERVER['HTTP_USER_AGENT']);
		$bname = 'Unknown';
		$platform = 'Unknown';
		$interface = 'Desktop';
		$device = 'Desktop';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent) && !preg_match('/android/i', $u_agent)) {
			$platform = 'Linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'Windows';
		}
		elseif (preg_match('/android/i', $u_agent)) {
			$platform = 'Android';
			$device = 'Tablet';
			
			// Get version of android
			$pattern	=	'#(?<browser>Version|Android|other)[ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			preg_match_all($pattern, $u_agent, $vmatches);
			
			if(isset($vmatches['version']) && is_array($vmatches['version']))
			$platform	.=	' '.$vmatches['version'][0];
		}
		
		// Get the Interface
		if(preg_match('/ipad|tablet|nexus/i', $u_agent)){
			$interface	=	'Tablet';
		}
		elseif(preg_match('/windows phone|iemobile|mobi/i', $u_agent)){
			$interface	=	'Mobile';
		}
		$device		=	$interface;
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE|IEMobile/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE|IEMobile"; 
			
			if(preg_match('/xbox one/i',$u_agent))
			$device = 'Xbox One';
			elseif(preg_match('/xbox/i',$u_agent))
			$device = 'Xbox 360';
			elseif(preg_match('/windows phone|iemobile/i',$u_agent))
			$device = 'Windows Phone';
		} 
		elseif(preg_match('/bingbot/',$u_agent)) 
		{ 
			$bname = 'Bing Bot';
			$ub = "bingbot"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Opera|OPR/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera|OPR"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari";
			
			if(preg_match('/iphone/i',$u_agent))
			$device = 'iPhone';

			elseif(preg_match('/ipad/i',$u_agent))
			$device = 'iPad';
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		}
		elseif(preg_match('/Googlebot-Image/i',$u_agent)) 
		{ 
			$bname = 'Google Spider - Image'; 
			$ub = "Googlebot-Image"; 
		}
		elseif(preg_match('/Googlebot/i',$u_agent)) 
		{ 
			$bname = 'Google Robot';
			$ub = "Googlebot"; 
		}
		elseif(preg_match('/Bot|bot/i',$u_agent)) 
		{ 
			$bname = 'Search Robot/Spider';
			$ub = "Bot|bot"; 
		}
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") > strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null) $version="";
		
		return array(
			'user_agent'	=>	$u_agent,
			'name'			=>	$bname,
			'version'		=>	$version,
			'platform'		=>	$platform,
			'interface'		=>	$interface,
			'device'		=>	$device,
			'pattern'		=>	$pattern
		);
	}
	
	
	function isValidXml($content){
		$content = trim($content);
		if (empty($content)) return false;
		
		//html go to hell!
		if (stripos($content, '<!DOCTYPE html>') !== false) return false;
		
		libxml_use_internal_errors(true);
		simplexml_load_string($content);
		$errors = libxml_get_errors();
		libxml_clear_errors();
		
		return empty($errors);
	}
	
	function XML2Array($xml){
		$arr		=	array();
		$arr_keys	=	array();
		
		// Get the attributes and store it
		$attr	=	(array) $xml->attributes();
		if(count($attr) > 0)
		$arr	=	array_merge($arr, $attr);
		
		// Get the childrens of that node
		$childs	=	$xml->children();
		if(count($childs) > 0){
			// Analyze Keys
			foreach($childs as $key=>$data){
				if(array_key_exists($key, $arr_keys))
					$arr_keys[$key]	+= 1;
				else
					$arr_keys[$key]	= 1;
			}
			
			// Get the value
			foreach($childs as $key=>$data){
				if($arr_keys[$key] > 1)
				$arr[$key][]	=	is_object($data)?xml2array($data):$data;
				else
				$arr[$key]		=	is_object($data)?xml2array($data):$data;
			}
		}
		else{
			$val			=	(string)$xml;
			if($val != ""){
				if(count($arr) > 0)
					$arr['@value']	=	$val;
				else
					$arr			=	$val;
			}
		}
		
		return $arr;
	}
	
	function getArray ( $xml ) {
		if($xml != ""){
			$obj		=	simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
			$obj->registerXPathNamespace("soap", "http://www.w3.org/2003/05/soap-envelope");
			//$array		=	json_decode(json_encode($obj), true); // Convert to array
			$array		=	XML2Array($obj);
			return $array;
		}
	}

	function _datatable($tbl_data, $tbl_init_out=array()){
	
		if(is_array($tbl_data) && count($tbl_data) > 0){
			
			
			// Table Columns
			$columns		=	$tbl_data['columns'];
			$tbl_class		=	$tbl_data['class'];
			$tbl_attr		=	$tbl_data['attr'];
			$tbl_has_foot	=	(isset($tbl_data['footer']) && $tbl_data['footer'] === true);
			$col_no			=	0;
			$class_arr		=	array();
			$js_vars		=	array();
			
			if(is_array($columns) && count($columns) > 0){
				// Prepare initial data
				$is_init	=	(is_array($tbl_init_out) && count($tbl_init_out) > 0); 
				$total_rec	=	$is_init?$tbl_init_out['recordsTotal']:0;
				$tbl_data	=	$is_init?$tbl_init_out['data']:array();
				
				// Prepare the header
				$th_data	=	_dt_header($columns);
				$th_head	=	$th_data['head'];
				$th_len		=	$th_data['colno'];
				$th_shead	=	$th_data['sub_head'];
				$th_out		=	'<tr>'.$th_head.'</tr>'.(($th_shead != "")?'<tr>'.$th_shead.'</tr>':'');
				$class_arr	=	$th_data['class'];
				$attr_arr	=	$th_data['attr'];
				$js_vars	=	$th_data['js'];
				

				
				// Prepare the body
				$tb_out		=	'';
				if($is_init && is_array($tbl_data) && count($tbl_data) > 0)
				foreach($tbl_data as $tbody){
					if(is_array($tbody) && count($tbody) > 0){
						$tb_out	.=	"<tr>";
						foreach($tbody as $tdno=>$td)
						
						$tb_out	.=	"<td class=\"$class_arr[$tdno]\" $attr_arr[$tdno]>$td</td>";
						$tb_out	.=	"</tr>";
					}
				}
				
				// Prepare the footer
				/* if($tbl_has_foot && $th_len > 0) $th_foot = str_repeat("<th></th>", $th_len); */
				
				// Table Attributes
				if($total_rec > 0) $tbl_attr .= " totalrecords=\"$total_rec\" ";
				
				// Output Data
				echo "
					<table class=\"table table-striped table-hover $tbl_class\" $tbl_attr>
						<thead>$th_out</thead>
						<tbody>$tb_out</tbody>
						<tfoot>$th_foot</tfoot>
					</table>
					<script language=\"javascript\">var dtColDefList=".@json_encode($js_vars).";var dtColDefNew=".@json_encode($js_vars)."</script>
				";
			}
		}
	}
	
	function _dt_header($array, $col_no=0, $is_sub=false){
		$head		=	'';
		$sub_head	=	'';
		$class		=	array();
		$attr		=	array();
		$js_vars	=	array();
		
		if(is_array($array) && count($array) > 0){
			// Check for rowspan
			$hasSub		=	0;
			foreach($array as $colnme=>$colopt) if(isset($colopt['sub']) && is_array($colopt['sub'])) $hasSub++;
			$rowspan	=	($hasSub>0)?2:0;
			
			foreach($array as $colnme=>$colopt){
				$has_sub	=	isset($colopt['sub']) && is_array($colopt['sub']) && count($colopt['sub']) > 0;
				
				// Data for sub head
				if($has_sub){
					$sub_cols	=	count($colopt['sub']);
					$sub_data	=	_dt_header($colopt['sub'], $col_no, true);
					$sub_head	.=	$sub_data['head'];
					$sub_class	=	$sub_data['class'];
					$sub_js		=	$sub_data['js'];
					$sub_cno	=	$sub_data['colno'];
				}
				
				// Data for main head
				$head		.=	"<th ".(!$has_sub?"rowspan='$rowspan'":"colspan='$sub_cols'")." class=\"$colopt[class]\">$colnme</th>";
				
				// Add class if it is not sub list
				if(!$has_sub){
					$class[$col_no]		=	$colopt['class'];
					$attr[$col_no]		=	$colopt['attr'];
					
					// Prepare the column behavior
					$orderable	=	isset($colopt['orderable'])?($colopt['orderable'] === true)
										:(strpos($colopt['class'], "td-options") === false);
					array_push($js_vars, array('targets'=>$col_no, 'className'=>'td-'.($col_no+1).' '.$colopt['class'], 'orderable'=>$orderable));
					$col_no++;
				}
				else{
					$class		=	array_merge($class, $sub_class);
					$js_vars	=	array_merge($js_vars, $sub_js);
					$col_no		=	($sub_cno > 0)?$sub_cno:$col_no;
				}
			}
		}
		
		return array(
			'head'		=>	$head,
			'sub_head'	=>	$sub_head,
			'class'		=>	$class,
			'attr'		=>	$attr,
			'js'		=>	$js_vars,
			'colno'		=>	$col_no
		);
	}
	
 
	
	// User Functions
	function is_user_logged_in(){
		return is_array($_SESSION['S3_CLIENT']) && count($_SESSION['S3_CLIENT'] > 0);
	}
	
	function is_web_user_logged_in(){
		return is_array($_SESSION['C3_USER']) && count($_SESSION['C3_USER'] > 0);
	}
	
	function is_admin(){
		return (get_curr_user_role() == 'admin');
	}
	
	function check_user_logged_in(){ 

		if(!is_user_logged_in() && S3_WEBSITE_URL != S3_WEBPAGE_URL) header("Location:".S3_WEBSITE_URL);
		return is_user_logged_in();
	}
	
	function check_user_session(){
		$sess_id		=	's3_user_session';
		$sess_timeout	=	90;
		$logout_url		=	S3_WEBSITE_URL."logout.php";
		$return			=	true;
		$_v				=	array_merge($_POST, $_GET);
		
		// Check if user logged in
		if(is_user_logged_in){
			// Check if session is timed out
			if(isset($_SESSION[$sess_id]) && is_date($_SESSION[$sess_id])){
				$time_passed	=	_minute_passed($_SESSION[$sess_id]);
				if($time_passed > $sess_timeout){
					$return		=	false;
					
					// Logout User
					logout_user();
					
					// Message to be shown in Login Page
					$_SESSION['login_msg']	=	"Session Expired Relogin again ...
												 <input type=\"hidden\" value=\"$time_passed\" />";
					
					// Send JSON output if action variable is found
					if(is_array($_v) && count($_v) > 0 && $_v['action'] != ""){
						die(json_encode(array(
							'status'	=>	false,
							'param'		=>	$_v,
							'display'	=>	'<i class="fa fa-times-circle fa-fw fa-lg"></i> Session Expired. Please Login ...',
						)));
					}
					else{
						// Send it to login page
						if(S3_WEBSITE_URL != S3_WEBPAGE_URL)
						header("Location:".S3_WEBSITE_URL);
					}
				}
			}
			
			// Update new session time
			$_SESSION[$sess_id]		=	_now();
		}
		
		return $return;
	}
	
	function goto_landing_page(){
		if(check_user_logged_in()){
			$link_active	=	S3_WEBSITE_URL."dashboard.php";
			
			if($link_active != "")
			header("Location:$link_active");
		}
	}
	
	function web_user_goto_landing_page(){
		if(is_web_user_logged_in()){
			$link_active	=	C3_WEBSITE_URL."user-dashboard.php";
			
			if($link_active != "")
			header("Location:$link_active");
		}
	}
	
	function update_curr_user_data(){
		global $_user;
		$u				=	$_SESSION['S3_CLIENT'];
		$user_query		=	null;
		$user_name		=	$u['username'];
		
		// Check if user data is found
		if(!(is_array($u) && count($u) > 0)){
			// Check for autologin information from cookie
			$is_alog	=	$_COOKIE['S3_AUTO_LOGIN'] == true;
			$al_uname	=	$_COOKIE['S3_REMEMBER_UNAME'];
			if($is_alog && $al_uname != "") $user_name = $al_uname;
		}
		
		if($user_name != ""){
			$user_sql		=	"select t1.*, t2.user_type as user_role_txt from ".S3_DB_USER_MANAGE." t1 
								 left join ".S3_DB_USER_ROLES." t2 on t1.user_role=t2.user_slug
								 where t1.username='$user_name' and t1.status>0";
			$user_query		=	_query($user_sql);
			
			if($user_query['status'] == 'success' && $user_query['count'] > 0){
				$u			=	$user_query['data'][0];
				
				// Remove important fields for security concerns
				unset($u['password']);
				
				// Update the output
				$_SESSION['S3_CLIENT']	=	$u;
				$_user					=	$u;
				
				return true;
			}
		}
		return false;
	}
	
	function get_curr_user_data(){
		global $_user;
		$user_data		=	_var($_user, $_SESSION['S3_CLIENT']);
		return $user_data;
	}
	
	function get_curr_web_user_data(){
		
		global $_user;
		//$user_data		=	_var($_user, $_SESSION['C3_USER']);
		$user_data		=	$_SESSION['C3_USER'];
		return $user_data;
	}
	
	function get_curr_user_role(){
		$user_data		=	get_curr_user_data();
		return (is_array($user_data) && count($user_data) > 0)?$user_data['user_role']:'';
	}
	
	function get_chained_users($arr){
		global $_user;
		$users_list		=	array();
		
		if(is_array($arr) && count($arr) > 0){
			$user			=	$arr['user'];
			$this_users		=	$user;
			$add_me			=	isset($arr['add_me']) && $arr['add_me'] == true;
			$multi_array	=	isset($arr['multi_array']) && $arr['multi_array'] == true;
			$user_no		=	isset($arr['user_no']) && $arr['user_no'] == true;
			$user_key		=	($user_no)?'sno':'username';
			
			// Get Username to provide for user_under
			if($user_no === true){
				$user_sql		=	"select username from ".S3_DB_USER_MANAGE." where sno='$user' and status>0";
				$user_query		=	_query($user_sql);
				$this_user		=	$user_query['data'][0]['username'];
			}
			
			// Get Users list under given username
			$username		=	_var($this_user, $_user['username'], "admin");
			if($username != ""){
				$users_sql		=	"select sno, username from ".S3_DB_USER_MANAGE." where user_under='$username' and status>0";
				$users_query	=	_query($users_sql);
				$this_users		=	_query_var($users_query['data'], $user_key);
				
				if($multi_array !== true)
				$users_list		=	$this_users;
				
				if(count($this_users) > 0){
					foreach($this_users as $tu){
						$new_users	=	get_chained_users(array(
							'user'			=>	$tu,
							'user_no'		=>	$user_no,
							'multi_array'	=>	$multi_array,
						));
						
						if($multi_array)
							$users_list[$tu]	=	$new_users;
						else
							$users_list			=	array_merge($users_list, $new_users);
					}
				}
			}
			
			// If Multi array and array is empty make it normal array
			if($multi_array === true && count($users_list) > 0){
				$array_no	=	0;
				foreach($users_list as $ul) if(count($ul) > 0) $array_no++;
				if($array_no == 0) $users_list = array_keys($users_list);
			}
			
			// Add Me if asked
			if($add_me === true && $multi_array === false)
			$users_list		=	array_merge(array($user), $users_list);
		}
		
		return $users_list;
	}

	
	
	function logout_user(){
		// Set Log for logout
		_log_user("User Signed Out");
		// Signout the user
		unset($_SESSION['S3_CLIENT'], $_SESSION['s3_user_session']);
		setcookie("S3_REMEMBER_UNAME", "", time() - 3600);
		setcookie("S3_AUTO_LOGIN", "", time() - 3600);
	}
	
	function web_user_logout_user(){
		// Set Log for logout
		_log_user("User Signed Out");
		// Signout the user
		unset($_SESSION['C3_USER'], $_SESSION['s3_user_session']);
		setcookie("C3_REMEMBER_UNAME", "", time() - 3600);
		setcookie("C3_AUTO_LOGIN", "", time() - 3600);
	}
	
	

	
	
	function _is_duplicate($arr){
		$chk_1	=	_is_duplicate_arr($arr);
		$chk_2	=	_is_duplicate_db($arr);
		return ($chk_1 || $chk_2);
	}
	
	
	function genID($length) {
		$chars = "0123456789"; 
		//only allowed chars in the blowfish salt.
		$size = strlen($chars); $str = "";
		for ($i = 0; $i < $length; $i++)
			$str .= $chars[rand(0, $size - 1)]; // strings can be used as char arrays
			// Yes, I am aware this salt isn't generated using the OS source.
			// use mycrypt_create_iv or /dev/urandom/
		return $str;
	}

	function newID($table_name,$field_name){
		
		$db_obj = new connection;
		
		$continue = true;

		while ($continue) {
			$newid = genID(6);
			//$query = mysql_query("SELECT * FROM poolzy_event WHERE random_number='".$newid."' LIMIT 1");
			$ph_where = "SELECT * FROM $table_name WHERE $field_name='".$newid."' LIMIT 1";
			$dup_check = $db_obj->count_row($ph_where);
			if ($dup_check != 1)
			$continue = false;
			return $newid;
		}
	}
	
	function array_multi_unique($multiArray){

	  $uniqueArray = array();

	  foreach($multiArray as $subArray){

		if(!in_array($subArray, $uniqueArray)){
		  $uniqueArray[] = $subArray;
		}
	  }
	  return $uniqueArray;
	}
	

?>