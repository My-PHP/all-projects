<?php 
date_default_timezone_set('UTC');
header('Content-Type: application/json');
$_POST = json_decode(file_get_contents("php://input"));
if(isset($_POST->signup))
{
	signUpUser();
	die;
}
if(isset($_POST->profile))
{
	updateUser();
	die;
}

function updateUser()
{
 $db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');

 $user_id=$_POST->user_id;
 $fname=$_POST->fname;	
 $email=$_POST->email;	
 $dob=date("Y-m-d", strtotime($_POST->dob));
 if($_POST->profile_pic!==''){ 	
 $pic=$_POST->profile_pic;	
 $pic_url='./uploads/profile_pic/'.$pic;
 $res = $db->query("SELECT `profile_pic` FROM `ang_user` WHERE `user_id` = '".$user_id."'");
 $records=$res->fetch_assoc();
 if($records){

  unlink('../uploads/profile_pic/'.$records['profile_pic']);
 }


  $sql="UPDATE `ang_user` SET `fname`='$fname', `email`='$email', `dob`='$dob', `profile_pic`='$pic',`profile_pic_url`= '$pic_url' WHERE `user_id`='".$user_id."'";
 }else{
 $sql="UPDATE `ang_user` SET `fname`='$fname', `email`='$email', `dob`='$dob' WHERE `user_id`='".$user_id."'";

 }

 $result = $db->query($sql);
   	//print_r($result);
 
 if($db->affected_rows > 0)
 {
   print_r(json_encode(true)); 
 }
 else
 {
   print_r(json_encode(false)); 
 }
 

} 


 function signUpUser()
{
 $db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');

 $fname=$_POST->fname;	
 $email=$_POST->email;	
 $password=$_POST->password;	
 $pic=$_POST->profile_pic;	
 $pic_url='./uploads/profile_pic/'.$pic;	
 $dob=date("Y-m-d", strtotime($_POST->dob));

 $sql="INSERT INTO `ang_user`( `fname`, `email`, `dob`, `password`,`profile_pic`,`profile_pic_url`) VALUES ('$fname','$email','$dob','$password','$pic','$pic_url')";

 $result = $db->query($sql);
   	//print_r($result);
 $data['user_id']=mysqli_insert_id($db);
 if($data['user_id'] > 0)
 {
   print_r(json_encode($data)); 
 }
 else
 {
   print_r(json_encode($data)); 
 }
 

}  
?>