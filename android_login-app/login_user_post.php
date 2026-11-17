<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require "../config.php";
function sendPushNotification($to,$title,$description){
            $data = array(
                "to" => $to,  
                "priority"=> "high",
                
                "data" => array(
                    "title" => $title, 
                    "description" => $description
                    )
                ); 
    
            $data_string = json_encode($data); 
            $headers = array ('Authorization: key=AAAAl_fFjAM:APA91bH85JXEKmBcH1JxQ7wadtiOLPP1uhhyhc5Kg9mWWlz2TiUIvKCfBCzmLH_Aun0cWfLs2Z2Y8rqMsEDR61ZEVg9N3TwcZzZCBAhwQYkNgy4zd8IOPDWsOn-w-VHC3AYMngY0i6oU', 'Content-Type: application/json' ); 
            $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
            curl_setopt( $ch,CURLOPT_POST, true ); 
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
            curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string); 
            $result = curl_exec($ch); 
			//print_r($result);
            curl_close ($ch); 
}

// get posted data
$data = json_decode(file_get_contents("php://input"));

if(isset($data->username) && $data->username !='' && isset($data->device_id) && $data->device_id !='' && isset($data->device_token) && $data->device_token !='')
{
	$username =  mysqli_real_escape_string($con, $data->username);
    $password =  mysqli_real_escape_string($con, $data->password);
    $device_token =  mysqli_real_escape_string($con, $data->device_token);
    $device_id =  mysqli_real_escape_string($con, $data->device_id);
    $device_info =  mysqli_real_escape_string($con, $data->device_info);
    $app_version =  mysqli_real_escape_string($con, $data->app_version);

    if($app_version == '2.0.8'){
        $output=array(
                        "msg" => 'Invalid Login details',
                        "status" => 401
                    );
        echo json_encode($output);
        exit;
    }

	$result = mysqli_query($con, "SELECT * FROM admins WHERE username = '" . $username. "' and password = '" . md5($password) . "'");
	if($row = mysqli_fetch_array($result)){
		
		$stored_device_token = $row['device_token'];
		$stored_device_id = $row['device_id'];
		$user_id = $row['id'];
		
		if($stored_device_id != $device_id){
			
			$sql = "UPDATE admins set device_token='$device_token', device_id='$device_id', device_info='$device_info' where id='$user_id'";
            $res = mysqli_query($con, $sql);
			//remove session from old device
			sendPushNotification($stored_device_token,'remove_session','Looks like your Device Changed.');
			
			$output=array(
                    "msg" => 'Welcome '.$username,
                    "status" => 200
                );
				echo json_encode($output);
			
		}elseif($stored_device_token != $device_token){
			$sql = "UPDATE admins set device_token='$device_token', device_id='$device_id', device_info='$device_info' where id='$user_id'";
            $res = mysqli_query($con, $sql);
			//remove session from old device
			sendPushNotification($stored_device_token,'remove_session','Looks like your Device Changed.');
			
			$output=array(
                    "msg" => 'Welcome '.$username,
                    "status" => 200
                );
				echo json_encode($output);
			
		}else{
			$output=array(
                    "msg" => 'Welcome '.$username,
                    "status" => 200
                );
				echo json_encode($output);
		}
                
		
	}else{
                $output=array(
                    "msg" => 'Invalid Login details',
                    "status" => 401
                );
                
                echo json_encode($output);
    }
	
}else{
	$output=array(
                    "msg" => 'Please Fill All Details.',
                    "status" => 401
                );
                
    echo json_encode($output);
}

?>