<?php

require "../config.php";

if(!empty($_REQUEST['password']) && !empty($_REQUEST['username'])){

  $username=mysqli_real_escape_string($con,$_REQUEST['username']);
  $password = mysqli_real_escape_string($con,$_REQUEST['password']);
  $password=md5($password);


	$data = "SELECT * from admins where username='".$username."' AND password='".$password."'";
	
	$result = mysqli_query($con,$data);

	if(mysqli_num_rows($result) > 0)
	{
			
		$obj['status'] = true;
		$obj['msg'] = 'Welcome '.$username;
		
	} else {
		
		$obj['status'] = false;
		 
		$obj['msg'] = 'Wrong Username or Password!';
		
	}
	
} else {
	
	$obj['status'] = false;
		
	$obj['msg'] = 'Please fill the respective fields !';
	
} 

echo json_encode($obj);

?>