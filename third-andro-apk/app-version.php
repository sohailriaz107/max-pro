<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require "../config.php";

// get posted data
$data = json_decode(file_get_contents("php://input"));
// get user values
$version =  mysqli_real_escape_string($con,$data->version);
$package_name =  mysqli_real_escape_string($con,$data->package_name);

if(1){
             
                $app=array(
                    "version" => '2.0.0',
					"apk" => 'https://dpboss.com/apk/third-party-admin.apk',
                    "date" => '2023-04-20',
					"onehourlogout" => 1
                );
            
            echo json_encode($app);
            mysqli_close($con);
}
  
?>