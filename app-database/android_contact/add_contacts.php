<?php
//require "../connection.php";
//$con=mysqli_connect('51.89.97.89','dpbossapi','TfyrEt%768U','dpboss_net_api');
require "functions.php";
if(isset($_REQUEST['phone_list']) && !empty($_REQUEST['user_id'])){
		
		$phone_list = json_decode($_REQUEST['phone_list'],true);
	
		$user_id = $_REQUEST['user_id']; 

		foreach($phone_list as $phone){
			
			$name = $phone['name'];
			
			$contact = $phone['contact'];
			
			$data = "INSERT INTO android_contact_list(contact,name,user_id) VALUES('".$contact."','".$name."','".$user_id."')";
			
			$result = $db_obj->insert($data);
		}
		
		$obj["msg"] = "Contacts Added Successfully !";
		
			
			$obj['status'] = true;
			
			echo json_encode($obj);die();
		
	} else {
		
		$obj['status'] = false;
		$obj['msg'] = 'Fill all required fields !';
	}
echo json_encode($obj);