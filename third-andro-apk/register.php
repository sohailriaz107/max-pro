<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require "../config.php";

// get posted data
$data = json_decode(file_get_contents("php://input"));

    $first_name =  mysqli_real_escape_string($con, $data->first_name);
	$last_name =  mysqli_real_escape_string($con, $data->last_name);
    $username =  mysqli_real_escape_string($con, $data->username);
    $mobile =  mysqli_real_escape_string($con, $data->mobile);
    $password =  mysqli_real_escape_string($con, $data->password);
	$password = md5($password);
    $device_id =  mysqli_real_escape_string($con, $data->device_id);
    $device_token =  mysqli_real_escape_string($con, $data->device_token);
    $device_info =  mysqli_real_escape_string($con, $data->device_info);
	$date_created = date('Y-m-d');
	
if($first_name !='' && $username !='' && $mobile !='' && $password !='')  
{
    $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($con, $sql);
            $username_check = mysqli_num_rows($result);
            
            $sql = "SELECT * FROM users WHERE mobile='$mobile'";
            $result = mysqli_query($con, $sql);
            $phone_check = mysqli_num_rows($result);

            
            if($username_check >0)
            {
                $err_msg .= 'Username Already Exist. ';
                
            }else{$username_check =0; }
			
			if($phone_check >0)
            {
                $err_msg .= 'Mobile No Already Exist. ';
                
            }else{$phone_check =0; }
            

            if($username_check ==0 && $phone_check==0){
                
                $sql = "INSERT INTO admins(firstname,lastname,username,mobile,password,status,date_created,device_id,device_token,device_info)
                VALUES('$first_name','$last_name','$username','$mobile','$password','0','$date_created','$device_id','$device_token','$device_info')";
                $res = mysqli_query($con, $sql);
                //echo mysqli_error($con);
                $last_id = mysqli_insert_id($con);
                if($last_id > 1){
					   $output=array(
						"msg" => 'Account Created',
						"status" => 200
						);
						echo json_encode($output);
				}else{
						$output=array(
						"msg" => 'error-'.mysqli_error($con),
						"status" => 401
					);
					echo json_encode($output);
				}
            
            
            }else
            {
                $output=array(
                    "msg" => $err_msg,
                    "status" => 401
                );
				echo json_encode($output);
            }
            
}else{
                    $output=array(
                    "msg" => 'Please Fill All Details. ',
                    "status" => 401
                );
                
                echo json_encode($output);
            }       
 mysqli_close($con);   



