<?php 

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$timezone = new DateTimeZone("Asia/Kolkata");
class connection
{
		private $hostname = '15.235.214.152';
		private $username = 'dpboss_user';
		private $password = 'AswrdTpudf%t';
		private $dbname = 'dpboss_net';
		private $conn = '';
		private $data = array();
		
		public function __construct()
		{
			$this->conn = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
			
			$this->mysqli = new mysqli($hostname, $username, $password, $dbname);
			
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
		
		
		
		public function now_date() {
			date_default_timezone_set('Asia/Kolkata');
			return date("Y-m-d H:i:s");
		}
		
		public function now_date_with_param($string) {
			date_default_timezone_set('Asia/Kolkata');
			return date($string);
		}
}
?>