<?php 
date_default_timezone_set('UTC');
header('Content-Type: application/json');
$_POST = json_decode(file_get_contents("php://input"));
if(isset($_POST->login))
{
	loginUser();
	die;
}
if(isset($_POST->Home ))
{
	//print_r($_POST);
	userHome();
	die;
}
if(isset($_POST->email_check))
{
	//print_r($_POST);
	email_check();
	die;
}
if(isset($_POST->forgot))
{
	//print_r($_POST);
	forgot_pass();
	die;
}
if(isset($_POST->menuUser))
{
	//print_r($_POST);
	GetUser();
	die;
}
function forgot_pass()
{
	$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');	
	if(!empty($_POST->email))
	{
		$result = $db->query("SELECT * FROM `ang_user` WHERE `email`='".$_POST->email."'");
		if(mysqli_num_rows($result))
		{
			$records=$result->fetch_array();
			$sub="Forgot Password";
			$msg="Hello ".$records['fname'].",<br><br> Here is your password : ".$records['password'];
			if(mail($records['email'], $sub, $msg)){
				$data['msg']="Please check your mail we have sent your password";
			}else{
				$data['msg']="password not sent";
			 }
		}
		else
		{		
			$data['msg']="Please provide right mail";
		}
			
	}
	else
		{		
			$data['msg']="Please provide right mail";
		}	
		print_r(json_encode($data));
}
function email_check()
{
	$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');	
	if(!empty($_POST->email))
	{

		$sql="SELECT * FROM `ang_user` WHERE `email`='".$_POST->email."'";
		if(!empty($_POST->user_id)){
			$sql.=" AND user_id!='".$_POST->user_id."'";
		}
		$result = $db->query($sql);
		if(mysqli_num_rows($result))
		{
			$records=$result->fetch_array();
			
			print_r(json_encode(true)); 
		}
		else
		{		
			print_r(json_encode(false)); 
		}
	}
	else
		{		
			print_r(json_encode(false)); 
		}	
}
function loginUser()
{
	$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');	
	$result = $db->query("SELECT * FROM `ang_user` WHERE `email`='".$_POST->email."' AND `password`='".$_POST->password."' AND `status`='Active'");
	if(mysqli_num_rows($result))
	{
		$records=$result->fetch_array();
		$data['is_login']=true;
		$data['user_id']=$records['user_id'];
		$data['user_name']=$records['fname'];	
		$data['user_pic']=$records['profile_pic_url'];	
		print_r(json_encode($data)); 
	}
	else
	{
		$data['is_login']=false;
		$data['user_id']=0;
		print_r(json_encode($data)); 
	}
}
function userHome()
{
	$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');	
	$result = $db->query("SELECT `fname`, `email`, `dob`,`profile_pic_url` FROM `ang_user` WHERE `user_id` = '".$_POST->user_id."'");
	$records=$result->fetch_assoc();
	/*foreach ($records as $value) 
	{
		$data[]=$value;
	}*/
	print_r(json_encode($records)); 
}
function GetUser()
{
	$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');	
	$result = $db->query("SELECT `fname`, `email`, `dob`,`profile_pic_url` FROM `ang_user` WHERE `user_id` = '".$_POST->user_id."'");
	$records=$result->fetch_assoc();
	/*foreach ($records as $value) 
	{
		$data[]=$value;
	}*/
	print_r(json_encode($records)); 
}

?>