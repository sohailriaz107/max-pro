<?php

	// MySQL
	function connectDB(){
		$status			=	false;
		$err_code		=	null;
		$err_msg		=	null;
		
		switch(S3_DB_CONN){
			case "pdo":
				try {
					$db		=	new PDO(
						S3_DB_TYPE.':host='.
							S3_DB_HOST.';dbname='.
							S3_DB_NAME,
						S3_DB_USER, S3_DB_PASS,
						array(PDO::ATTR_PERSISTENT => true)
					);
					$GLOBALS['db']	=	$db;
					$status			=	true;
				}
				catch(PDOException $e){
					$status			=	false;
					$err_data		=	_error_pdo($e->getMessage());
					$err_code		=	$err_data['code'];
					$err_no			=	$err_data['no'];
					$err_msg		=	$err_data['msg'];
					$err_dmsg		=	($err_code == 'err1')?"Error connecting to server ..."
										:($err_code == 'err2')?"Error connecting to server ..."
										:"Error connecting to server ...";
				}
			break;
			default:
				if($db = @mysql_connect(S3_DB_HOST, S3_DB_USER, S3_DB_PASS)){
					if($db_conn = @mysql_select_db(S3_DB_NAME, $db)){
						$status			=	true;
					}
					else{
						$err_code	=	'err2';
						$err_dmsg	=	"Error connecting to server ...";
						$err_no		=	@mysql_errno($db);
						$err_msg	=	@mysql_error($db);
					}
				}
				else{
					$err_code	=	'err1';
					$err_dmsg	=	"Error connecting to server ...";
					$err_no		=	@mysql_errno();
					$err_msg	=	@mysql_error();
				}
			break;
		}
		
		define("S3_DB_STATUS",			$status);
		define("S3_DB_ERR_CODE",		$err_code);
		define("S3_DB_ERR_NO",			$err_no);
		define("S3_DB_ERR_MSG",			$err_msg);
		define("S3_DB_ERR_DISP_MSG",	$err_dmsg);
		
		$GLOBALS['db']	=	S3_DB_STATUS?$db:null;
	}
	
	function _query($sql, $type='', $startNode='', $nodeCase=''){
		$db_func	=	(S3_DB_CONN == 'pdo')?'_query_pdo':'_query_regular';
		return $db_func($sql, $type, $startNode, $nodeCase);
	}
	
	function _q($sql, $type='', $startNode='', $nodeCase=''){
		return _query($sql, $type, $startNode, $nodeCase);
	}
	
	function _query_pdo($sql, $type='', $startNode='', $nodeCase=''){
		$db				=	$GLOBALS['db'];
		$SN				=	($startNode)?$startNode:"mysql";
		$xml_output		=	"<$SN>";
		$is_select		=	false;
		$is_insert		=	false;
		$is_update		=	false;
		$is_delete		=	false;
		
		if($sql){
			$db_output['sql']	=	$sql;
			$xml_output			.=	"<sql>".$sql."</sql>";
			
			// Split the query by words
			$spl_sql			=	preg_split("/[ \n\r\t]/", $sql, -1, PREG_SPLIT_NO_EMPTY);
			if(count($spl_sql) > 0){
				switch(strtolower($spl_sql[0])){
					case "select": $is_select = true; break;
					case "insert": $is_insert = true; break;
					case "update": $is_update = true; break;
					case "delete": $is_delete = true; break;
				}
			}
			
			if(S3_DB_STATUS){
				$query	=	$db->prepare($sql);
				$query->execute();
				if($query->errorCode() == 0){
					$db_output['status']	=	'success';
					$arr_data				=	array();
					$xml_data				=	'';
					while(($row = $query->fetch(PDO::FETCH_ASSOC)) != false) {
						$data			=	$row;
						$keys			=	array_keys($data);
						$ndata			=	array();
						if(count($keys) > 0){
							for($k=0; $k<count($keys); $k++){
								if(preg_match("/\(/", $keys[$k])){
									$data_key	=	split("\(", $keys[$k]);
									$data_key	=	split("\)", $data_key[count($data_key)-1]);
									$data_key	=	$data_key[0];
									
									if(preg_match("/\./", $data_key)){
										$data_key	=	split("\.", $data_key);
										$data_key	=	$data_key[count($data_key)-1];
									}
								}
								else{
									$data_key	=	$keys[$k];
								}
								
								switch(strtolower($nodeCase)){
									case "lower":
									case "lowercase":
										$data_key	=	@strtolower($data_key);
									break;
									case "upper":
									case "uppercase":
										$data_key	=	@strtoupper($data_key);
									break;
								}
								
								$ndata[$data_key]	=	$data[$keys[$k]];
							}
							$data		=	$ndata;
							$keys		=	array_keys($data);
						}
						
						// Array Data
						array_push($arr_data, $data);
						
						// Xml Data
						$xml_data			.=	"<row>";
						for($k=0; $k<count($keys); $k++){
							$xml_data		.=	"<".$keys[$k].">".$data[$keys[$k]]."</".$keys[$k].">";
						}
						$xml_data			.=	"</row>";
					}
					$db_output['count']		=	count($arr_data);
					$db_output['data']		=	$arr_data;
					$xml_output				.=	"<count>".$db_output['count']."</count>";
					$xml_output				.=	"<data>$xml_data</data>";
				}
				else{
					$error					=	$query->errorInfo();
					$db_output['status']	=	'err3';
					$db_output['errno']		=	$error[1];
					$db_output['errtxt']	=	$error[2];
				}
			}
			else{
				$db_output['status']	=	S3_DB_ERR_CODE;
				$db_output['errno']		=	S3_DB_ERR_NO;
				$db_output['errtxt']	=	S3_DB_ERR_MSG;
			}
			
			$xml_output				.=	"<status>".$db_output['status']."</status>";
			$xml_output				.=	"<errno>".$db_output['errno']."</errno>";
			$xml_output				.=	"<errtxt>".urlencode($db_output['errtxt'])."</errtxt>";
			
			if($db_output['status'] == 'success'){
			$db_output['affected_rows']	=	$query->rowCount();
			$xml_output				.=	"<affected_rows>".urlencode($db_output['affected_rows'])."</affected_rows>";
			}
			
			if($is_insert){
			$db_output['insert_id']	=	$db->lastInsertId();
			$xml_output				.=	"<insert_id>".urlencode($db_output['insert_id'])."</insert_id>";
			}
			
			$xml_output				.=	"</$SN>
			";
			return ($type == "xml")?$xml_output:(($type=="both")?array('arr'=>$db_output, 'xml'=>$xml_output):$db_output);
		}
		
	}
	
	function _error_pdo($msg){
		$err	=	array(
			'code'	=>	null,
			'no'	=>	null,
			'msg'	=>	null
		);
		
		if($msg != ""){
			$spl_msg		=	preg_split("/[\[\]]/", $msg, -1, PREG_SPLIT_NO_EMPTY);
			$err_no			=	trim(ltrim(rtrim($spl_msg[count($spl_msg)-2])));
			$err_msg		=	trim(ltrim(rtrim($spl_msg[count($spl_msg)-1])));
			
			$err['no']		=	$err_no;
			$err['msg']		=	$err_msg;
			
			if(preg_match('/Unknown MySQL server host/', $err_msg) || preg_match('/Access denied for/', $err_msg))
				$err['code']	=	'err1';
			else if(preg_match('/Unknown database/', $err_msg))
				$err['code']	=	'err2';
			else
				$err['code']	=	'err3';
		}
		
		return $err;
	}
	
	function _query_regular($sql, $type='', $startNode='', $nodeCase=''){
		$db				=	$GLOBALS['db'];
		$db_output		=	array();
		$SN				=	($startNode)?$startNode:"mysql";
		$xml_output		=	"<$SN>";
		$is_select		=	false;
		$is_insert		=	false;
		$is_update		=	false;
		$is_delete		=	false;
		
		if($sql){
			$db_output['sql']	=	$sql;
			$xml_output			.=	"<sql>".$sql."</sql>";
			
			// Split the query by words
			$spl_sql			=	preg_split("/[ \n\t\r]/", $sql, -1, PREG_SPLIT_NO_EMPTY);
			if(count($spl_sql) > 0){
				switch(strtolower($spl_sql[0])){
					case "select": $is_select = true; break;
					case "insert": $is_insert = true; break;
					case "update": $is_update = true; break;
					case "delete": $is_delete = true; break;
				}
			}
			
			if(S3_DB_STATUS){
				if($query = @mysql_query($sql, $db)){
					if(@mysql_num_rows($query) > 0){
						$db_output['count']		=	@mysql_num_rows($query);
						$db_output['data']		=	array();
						$xml_output				.=	"<count>".$db_output['count']."</count>";
						$xml_output				.=	"<data>";
						while($data = @mysql_fetch_array($query, MYSQL_ASSOC)){
							$keys			=	array_keys($data);
							$ndata			=	array();
							if(count($keys) > 0){
								for($k=0; $k<count($keys); $k++){
									if(preg_match("/\(/", $keys[$k])){
										$data_key	=	split("\(", $keys[$k]);
										$data_key	=	split("\)", $data_key[count($data_key)-1]);
										$data_key	=	$data_key[0];
										
										if(preg_match("/\./", $data_key)){
											$data_key	=	split("\.", $data_key);
											$data_key	=	$data_key[count($data_key)-1];
										}
									}
									else{
										$data_key	=	$keys[$k];
									}
									
									switch(strtolower($nodeCase)){
										case "lower":
										case "lowercase":
											$data_key	=	@strtolower($data_key);
										break;
										case "upper":
										case "uppercase":
											$data_key	=	@strtoupper($data_key);
										break;
									}
									
									$ndata[$data_key]	=	$data[$keys[$k]];
								}
								$data		=	$ndata;
								$keys		=	array_keys($data);
							}
							
							// Array Data
							array_push($db_output['data'], $data);
							
							// Xml Data
							$xml_output			.=	"<row>";
							for($k=0; $k<count($keys); $k++){
								$xml_output		.=	"<".$keys[$k].">".$data[$keys[$k]]."</".$keys[$k].">";
							}
							$xml_output			.=	"</row>";
						};
						$xml_output				.=	"</data>";
					}
					$db_output['status']	=	"success";
				}
				else{
					$db_output['status']	=	"err3";
				}
			}
			else{
				$db_output['status']	=	S3_DB_ERR_CODE;
			}
			
			$db_output['errno']		=	(S3_DB_ERR_NO)?S3_DB_ERR_NO:@mysql_errno($db);
			$db_output['errtxt']	=	(S3_DB_ERR_MSG)?S3_DB_ERR_MSG:@mysql_error($db);
			$xml_output				.=	"<status>".$db_output['status']."</status>";
			$xml_output				.=	"<errno>".$db_output['errno']."</errno>";
			$xml_output				.=	"<errtxt>".urlencode($db_output['errtxt'])."</errtxt>";
			
			if($db_output['status'] == 'success'){
			$db_output['affected_rows']	=	@mysql_affected_rows($db);
			$xml_output				.=	"<affected_rows>".urlencode($db_output['affected_rows'])."</affected_rows>";
			}
			
			if($is_insert){
			$db_output['insert_id']	=	@mysql_insert_id($db);
			$xml_output				.=	"<insert_id>".urlencode($db_output['insert_id'])."</insert_id>";
			}
			
			$xml_output				.=	"</$SN>
			";
			return ($type == "xml")?$xml_output:(($type=="both")?array('arr'=>$db_output, 'xml'=>$xml_output):$db_output);
		}
	}
	
	function _query_var($arr, $var, $key=''){
		$output		=	array();
		if(is_array($arr) && $var != ""){
			foreach($arr as $a){
				if($key != "")	$output[$a[$key]]	=	$a[$var];
				else			array_push($output, $a[$var]);
			}
		}
		return $output;
	}
	
	function _qg($clause, $table, $vars, $where=""){
		return _query_generator($clause, $table, $vars, $where);
	}
	
	function _query_generator($clause, $table, $vars, $where=""){
		$sql		=	'';
		$is_insert	=	false;
		$is_update	=	false;
		$is_delete	=	false;
		
		if($clause != "" && $table != "" && is_array($vars) && count($vars) > 0){
			// Clause define
			$clause		=	strtolower(preg_replace("/[ ]/", "", $clause));
			switch($clause){
				case "insert":
					$sql		=	"insert into $table ";
					$is_insert	=	true;
				break;
				case "update":
					$sql		=	"update $table ";
					$is_update	=	true;
				break;
				case "delete":
					$sql		=	"delete from $table ";
					$is_delete	=	true;
				break;
			}
			
			if(is_array($vars) && count($vars) > 0){
				// Add connector
				switch($clause){
					case "update":
						$sql	.=	"set ";
					break;
				}
				
				// Convert array to sql data variables
				if($is_insert && !array_is_associative($vars)){
					$ins_multi_sql	=	'';
					foreach($vars as $nvar){
						$t_sql		=	_q_var_gen($nvar, $is_insert);
						$t_sql_spl	=	preg_split("/ values /", $t_sql, -1, PREG_SPLIT_NO_EMPTY);
						
						$ins_multi_sql	.=	($ins_multi_sql == '')?$t_sql:', '.$t_sql_spl[1];
					}
					$sql		.=	$ins_multi_sql;
				}
				else{
					$sql		.=	_q_var_gen($vars, $is_insert);
				}
			}
			
			if($where != "")
			$sql		.=	$where;
		}
		return $sql;
	}
	
	function _q_var_gen(array $vars, $is_insert = false){
		$var_keys	=	array();
		$var_vals	=	array();
		$sql_vars	=	'';
		
		// Safe check vars
		$vars		=	_safe_vars($vars);
		
		foreach($vars as $k=>$v){
			// Make the key ready
			$k		=	"`$k`";
			
			// Make the value ready
			if(is_array($v)){
				switch($v['type']){
					case "mysql": case "'mysql'":
						$vl		=	substr($v['val'], 1, strlen($v['val'])-2);
					break;
				}
			}
			else{
				$vl		=	"$v";
			}
			
			if(!$is_insert){
				$k		=	"$k=$vl";
				$vl		=	"";
			}
			
			if($k != "")	array_push($var_keys, $k);
			if($vl != "")	array_push($var_vals, $vl);
		}
		
		// Combine the keys
		$var_keys	=	join(", ", $var_keys);
		$sql_vars	.=	($is_insert?'(':'').$var_keys.($is_insert?')':'').' ';
		
		// Combine the values if avail
		if(count($var_vals) > 0){
			$var_vals	=	join(", ", $var_vals);
			$sql_vars	.=	($is_insert?'values (':'').$var_vals.($is_insert?')':'').' ';
		}
		
		return $sql_vars;
	}
	
	function _q_gen_others($table, $vars, $where){
		$sql	=	'';
		if($table != "" && is_array($vars) && count($vars) > 0){
			$var_keys	=	array();
			$var_vals	=	array();
			foreach($vars as $k=>$v){
				if(is_array($v)){
					switch($v['type']){
						case "mysql":
							$vl		=	$v['val'];
						break;
					}
				}
				else{
					$vl		=	"\"$v\"";
				}
				array_push($var_keys, "`$k`");
				array_push($var_vals, $vl);
			}
			$var_keys	=	join(", ", $var_keys);
			$var_vals	=	join(", ", $var_vals);
			$sql		=	"insert into $table($var_keys) values($var_vals)";
		}
		return $sql;
	}
	
	function db2SelOpt($sql, $txt, $val, $def=""){
		$opt	=	'';
		$val	=	(!is_null($val) && $val != "")?$val:$txt;
		if(!is_null($sql) && $sql != "" && !is_null($txt) && $txt != ""){
			$arr	=	_query($sql);
			$opt	=	array2SelOptDB($arr['data'], $txt, $val, $def);
		}
		
		return $opt;
	}
	
	function db2GroupSelOpt($sql, $gtxt, $txt, $val, $def=""){
		
		/* _echo($sql);
		_echo($gtxt);
		_echo($txt);
		_echo($val);
		_echo($def); */
		
		//die();
		$opt	=	'';
		$val	=	(!is_null($val) && $val != "")?$val:$txt;
		if(!is_null($sql) && $sql != "" && !is_null($gtxt) && $gtxt != "" && !is_null($txt) && $txt != ""){
			$arr	=	_query($sql);
			$garr	=	array();
			if($arr['count'] > 0){
				foreach($arr['data'] as $dt){
					if(!isset($garr[$dt[$gtxt]])) $garr[$dt[$gtxt]] = array();
					array_push($garr[$dt[$gtxt]], $dt);
				}
				foreach($garr as $k=>$varr){
					$k		=	strtoupper($k);
					$opt	.=	"<optgroup label='$k'>";
					$opt	.=	array2SelOptDB($varr, $txt, $val, $def);
					$opt	.=	"</optgroup>";
				}
			}
		}
		
		return $opt;
	}
	
	function array2SelOptDB($arr, $txt, $val, $def=""){
		$opt	=	'';
		$val	=	(!is_null($val) && $val != "")?$val:$txt;
		if(is_array($arr) && count($arr) > 0 && !is_null($txt) && $txt != ""){
			foreach($arr as $obj){
				$opt	.=	'<option value="'.$obj[$val].'"'.(($def != "" && $def == $obj[$val])?' selected="selected"':'').'>'.$obj[$txt].'</option>';
			}
		}
		
		return $opt;
	}
	
	function array2SelOpt($arr, $txtAsVal=false, $def=""){
		$opt	=	'';
		if(is_array($arr) && count($arr) > 0){
			foreach($arr as $val=>$txt)
			$opt	.=	'<option value="'.(($txtAsVal)?$txt:$val).'"'.(($def != "" && ($def == $val || $def == $txt))?' selected="selected"':'').'>'.$txt.'</option>';
		}
		
		return $opt;
	}

	// Connect to database
	connectDB();

?>