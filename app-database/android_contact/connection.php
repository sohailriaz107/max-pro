<?php 
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
$timezone = new DateTimeZone("Asia/Kolkata");
@include_once("credentials.php");
//echo S3_DB_NAME;die('yes1');
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
			
			$this->mysqli = new mysqli(S3_DB_HOST, S3_DB_USER, S3_DB_PASS, S3_DB_NAME);
			
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
		
		public function select_where_single($data)
		{
			$result = $this->conn->query($data);
			$data = array();
			if($result==TRUE)
			 {
				while($row = $result->fetch_assoc()) 
				{
					$data = $row;
				}
				return $data;
			 }
		}
		
		
}
?>