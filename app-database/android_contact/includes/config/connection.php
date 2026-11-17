<?php 
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
$timezone = new DateTimeZone("Asia/Kolkata");
@include_once("credentials.php");
class connection
{	
		private $hostname = S3_DB_HOST;
		private $username = S3_DB_USER;
		private $password = S3_DB_PASS;
		private $dbname = S3_DB_NAME;
		
		private $conn = '';
		private $data = array();
		
		public function __construct()
		{
			$this->conn = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
			
			$this->conn->set_charset("utf8");
			
			$this->mysqli = new mysqli(S3_DB_HOST, S3_DB_USER, S3_DB_PASS, S3_DB_NAME);
			
			$this->mysqli->set_charset("utf8");
			
			//$this->access_var = 'yes';
			try
			{
				if($this->conn->connect_error)
				{
					throw new Exception('Connection Error !');
					exit;
				}
			}
			
			catch(Exception $ex)
			{
				echo $ex->getmessage();
			}
		}
		
		
		public function insert($data)
		{
			if($this->conn->query($data)==TRUE)
			{
				return  mysqli_insert_id($this->conn);
			}
			
			else
			{
				return 0;
			}
		}
		
		
		public function select($data)
		{
			$result = $this->conn->query($data);
			$data = array();
			if($result==TRUE)
			 {
				while($row = $result->fetch_array()) 
				{
					$data[] = $row;
				}
				return $data;
			 }
		}

		public function count_row($data)
		{
			$result = $this->conn->query($data);
			
			$data = $result->num_rows;
			
			return $data;
		} 
		
		public function select_where($data)
		{
			$result = $this->conn->query($data);
			$data = array();
			if($result==TRUE)
			 {
				while($row = $result->fetch_assoc()) 
				{
					$data[] = $row;
				}
				return $data;
			 }
		}
		
		public function select_where_with_param($table , $where_clause)
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
				if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add keyword
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			
			$qry = "SELECT * FROM $table".$whereSQL;
			
			$result = $this->conn->query($qry);
			
			$data = array();
			
			if($result==TRUE)
			 {
				while($row = $result->fetch_assoc()) 
				{
					$data[] = $row;
				}
				return $data;
			 }
		}
		
		public function select_where_with_param_order($table , $where_clause, $order)
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
				if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add keyword
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			
			$qry = "SELECT * FROM $table".$whereSQL.' '.$order;
			
			$result = $this->conn->query($qry);
			
			$data = array();
			
			if($result==TRUE)
			 {
				while($row = $result->fetch_assoc()) 
				{
					$data[] = $row;
				}
				return $data;
			 }
		}
		
		public function select_where_with_fields($table , $where_clause , $fields)
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
				if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add keyword
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			
			$qry = "SELECT $fields FROM $table".$whereSQL;
			
			$result = $this->conn->query($qry);
			
			$data = array();
			
			if($result==TRUE)
			 {
				while($row = $result->fetch_assoc()) 
				{
					$data[] = $row;
				}
				return $data;
			 }
		}
		
		public function update_where($data)
		{
			$result = $this->conn->query($data);
			if($result==TRUE)
			{
				return 1;
			}
			else
			{
				 return 0;
			}
		} 
		
		public function delete($data)
		{   
			$result = $this->conn->query($data);
			if($result==TRUE)
			 {
				return 1;
			 }
			 
			 else
			 {
				 return 0;
			 }
		}
		
		public function getExcerpt($str, $startPos, $maxLength) 
		{
			if(strlen($str) > $maxLength) 
			{
				$excerpt   = substr($str, $startPos, $maxLength-3);
				$lastSpace = strrpos($excerpt, ' ');
				$excerpt   = substr($excerpt, 0, $lastSpace);
				$excerpt  .= '...';
			}
			
			else 
			{
				$excerpt = $str;
			}
			
			return $excerpt;
		}
		
		public function InsertData($table_name, $form_data)
		{  
			// retrieve the keys of the array (column titles)
			$fields = array_keys($form_data);

			// build the query
			$sql = "INSERT INTO ".$table_name."
			(`".implode('`,`', $fields)."`)
			VALUES('".implode("','", $form_data)."')";

			// run and return the query result resource
				//echo $sql; exit;
			
			if($this->conn->query($sql)==TRUE)
			{
				return  mysqli_insert_id($this->conn);
			}
			
			else
			{
				return 0;
			}
				
		}
		
		public function ReturnInsertData($table_name, $form_data)
		{
			
			// retrieve the keys of the array (column titles)
			$fields = array_keys($form_data);

			// build the query
			$sql = "INSERT INTO ".$table_name."
			(`".implode('`,`', $fields)."`)
			VALUES('".implode("','", $form_data)."')";
			
			return $sql;
			
		}
		
		public function DeleteData($table_name, $where_clause='')
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
				if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add keyword
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			// build the query
			$sql = "DELETE FROM ".$table_name.$whereSQL;

			// run and return the query result resource
			
			$result = $this->conn->query($sql);
			if($result==TRUE)
			{
				return 1;
			}
			else
			{
				 return 0;
			}
			
		}
		
		public function UpdateData($table_name, $form_data, $where_clause='')
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
			if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add key word
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			// start the actual SQL statement
			$sql = "UPDATE ".$table_name." SET ";

			// loop and build the column /
			$sets = array();
			foreach($form_data as $column => $value)
			{
				 $sets[] = "`".$column."` = '".$value."'";
			}
			$sql .= implode(', ', $sets);

			// append the where statement
			$sql .= $whereSQL;

			// run and return the query result
			$result = $this->conn->query($sql);
			if($result==TRUE)
			{
				return 1;
			}
			else
			{
				 return 0;
			}
		}
		
		public function ReturnUpdateData($table_name, $form_data, $where_clause='')
		{
			// check for optional where clause
			$whereSQL = '';
			if(!empty($where_clause))
			{
				// check to see if the 'where' keyword exists
			if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
				{
					// not found, add key word
					$whereSQL = " WHERE ".$where_clause;
				} else
				{
					$whereSQL = " ".trim($where_clause);
				}
			}
			// start the actual SQL statement
			$sql = "UPDATE ".$table_name." SET ";

			// loop and build the column /
			$sets = array();
			foreach($form_data as $column => $value)
			{
				 $sets[] = "`".$column."` = '".$value."'";
			}
			$sql .= implode(', ', $sets);

			// append the where statement
			$sql .= $whereSQL;
			
			return $sql;
		
		}
		
		public function randomPassword() {
			$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$pass = array(); //remember to declare $pass as an array
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 6; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
			return implode($pass); //turn the array into a string
		}
		
		public function get_user_balance_by_id($user_id) {
			
			$bal_qry = "SELECT `t1`.`remained_balance` FROM `transaction` AS t1 WHERE  `t1`.`user_id` = ".$user_id." order by id desc limit 1";
			$bal = $this->select_where($bal_qry);
			
			return $bal[0]['remained_balance'];
		}
		
		/* public function get_client_id() {
			
			$query = 'select user_id from '.S3_DB_USER_ROLES.' user_role="client" order by sno desc limit 1';
			
			$data = $this->select_where($query);
			
			if(empty($data[0]['user_id'])){
				
				return "01";
				
			} else {
				
				$Bottle_ID = $data[0]['Bottle_ID'];
				
				$Bottle_ID_ex = explode('BT_WI_BARCODE_',$Bottle_ID);
				
				$new_Bottle_ID = $Bottle_ID_ex[1]+1;
				
				if($new_Bottle_ID < 10){
					
					$new_Bottle_ID = '0'.$new_Bottle_ID;
				}
				
				return $new_Bottle_ID; 
			}
			
		} */
}
?>