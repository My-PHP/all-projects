<?php 
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json');
$_POST = json_decode(file_get_contents("php://input"));
$db = new mysqli('localhost','root','','angular_db') or die (mysqli_error().': Server not found');
if(isset($_POST->post))
{
	addPost($db);
	die;
}
if(isset($_POST->get_post))
{
	allPost($db);
	die;
}
if(isset($_POST->user_list))
{
	userList($db);
	die;
}
if(isset($_POST->add_friend))
{
	addFriend($db);
	die;
}
if(isset($_POST->view_request))
{
	friendRequest($db);
	die;
}
if(isset($_POST->accept))
{
	updateRequest($db);
	die;
}
if(isset($_POST->friend_list))
{
	friendList($db);
	die;
}
if(isset($_POST->view_user_detail))
{
	viewUserDetails($db);
	die;
}
if(isset($_POST->send_message))
{
	saveMessage($db);
	die;
}if(isset($_POST->view_message))
{
	getMessage($db);
	die;
}
if(isset($_POST->number_msg))
{
	messageCount($db);
	die;
}
function saveMessage($db)
{
	$sender_id=$_POST->user_id;
	$reciever_id=$_POST->to_user_id;
	$msg=$_POST->message;
	$sended_date=Date('Y-m-d H:i:s');
	$status='1';
	$save=$db->query("INSERT INTO `ang_messaging`( `sender_id`, `reciever_id`, `message`, `status`, `sended_date`) VALUES('$sender_id','$reciever_id','$msg','$status','$sended_date')");
	$last_msg_id=mysqli_insert_id($db);
	if($last_msg_id >0)
	{
		echo json_encode(true);
	}
	else
	{
		echo json_encode(false);
	}
}
function messageCount($db)
{
	$user_id=$_POST->user_id;	
	$msg=$db->query("SELECT distinct `sender_id` FROM `ang_messaging` WHERE `status`='1' AND  `reciever_id`='$user_id'AND `is_read`='1'");
	if(@mysqli_num_rows($msg))
		{   
			$count=@mysqli_num_rows($msg);
			$ids=array();
			while($records=$msg->fetch_assoc()){
				$ids[]=$records['sender_id'];
			}
			//echo"<pre>";print_r($ids);die;
			$s_ids=implode(',', array_unique($ids));
			if($s_ids!=''){
					$result = $db->query("SELECT `ang_user`.`fname`,`ang_user`.`profile_pic_url`,`ang_user`.`user_id`,`ang_messaging`.`message` FROM `ang_user` inner join `ang_messaging` on  `ang_user`.`user_id`=`ang_messaging`.`sender_id` WHERE `ang_user`.`status`=1 AND  `ang_user`.`user_id` IN ($s_ids) AND `ang_messaging`.`is_read`='1' group by `ang_user`.`user_id`");

				if(@mysqli_num_rows($result))
				{
					$data['users']=array();
					while($users=$result->fetch_assoc()){			
						$data['users'][]=$users;	
					}
				}
			}



				$data['count']=$count;
				echo json_encode($data);
			}
			else{
				echo json_encode(0);
			}
		}
		function getMessage($db)
		{
			$user_id=$_POST->user_id;	
			$to_user_id=$_POST->to_user_id;	
			$status='1';
			$msg=$db->query("SELECT * FROM `ang_messaging` WHERE `status`='1' AND (`sender_id`='$user_id' OR `reciever_id`='$user_id')AND (`sender_id`='$to_user_id' OR `reciever_id`='$to_user_id') ORDER BY `sended_date` ASC");
			if(@mysqli_num_rows($msg))
			{
				$db->query("UPDATE `ang_messaging` SET `is_read`='2' WHERE `reciever_id`='".$user_id."'AND `sender_id`='".$to_user_id."'");
				$key=0;
				while($records=$msg->fetch_array())
				{	
					$time=strtotime($records['sended_date']);
					$records['time_ago']=get_time_ago($time);		
					$data['message'][]=$records;
					$key++;	
				}
				echo json_encode($data);
			}else{
				echo json_encode(false);
			}
		}






		function updateRequest($db)
		{
			$first_user=$_POST->to_friend;
			$second_user=$_POST->user_id;
			$status=$_POST->status;
			$accepted_date=date('Y-m-d H:i:s');
			if($status=="Unfriend"){
				$sql="DELETE FROM `ang_friends` WHERE `first_user`='".$first_user."' AND `second_user`='".$second_user."'";
			}else{		
				$sql="UPDATE `ang_friends` SET `status`='$status' WHERE `first_user`='".$first_user."' AND `second_user`='".$second_user."'";
			}

			$result = $db->query($sql);
   	//print_r($result);

			if($db->affected_rows > 0)
			{   
				$fIds=loggedUser($first_user,$db);
				$friend1= $fIds.','.$second_user;
				$friend= trim($friend1 ,',');
				$xx=array_unique(array_map('trim',  explode(',', $friend)));
				$FF=implode(',',$xx);
				$add="UPDATE `ang_user` SET `friends_id`='$FF' WHERE `user_id`='".$first_user."'";
				$add_f = $db->query($add);

				$fIds2=loggedUser($second_user,$db);
				$friend12= $fIds2.','.$first_user;
				$friend2= trim($friend12 ,',');
				$xx2=array_unique(array_map('trim',  explode(',', $friend2)));
				$FF2=implode(',',$xx2);
				$add2="UPDATE `ang_user` SET `friends_id`='$FF2' WHERE `user_id`='".$second_user."'";
				$add_f2 = $db->query($add2);
				print_r(json_encode(true)); 
			}
			else
			{
				print_r(json_encode(false)); 
			}
		}
		function addFriend($db)
		{
			$first_user=$_POST->user_id;
			$second_user=$_POST->to_friend;
			$status='Pending';
			$accepted_date=date('Y-m-d H:i:s');
			$sql="INSERT INTO `ang_friends`(`first_user`, `second_user`, `status`, `accepted_date`) VALUES ('$first_user','$second_user','$status','$accepted_date')";

			$result = $db->query($sql);
   	//print_r($result);

			if(mysqli_insert_id($db) > 0)
			{   
		/*$fIds=loggedUser($first_user,$db);
		$friend1= $fIds.','.$second_user;
		$friend= trim($friend1 ,',');
		$xx=array_unique(array_map('trim',  explode(',', $friend)));
		$FF=implode(',',$xx);
		$add="UPDATE `ang_user` SET `friends_id`='$FF' WHERE `user_id`='".$first_user."'";
		$add_f = $db->query($add);*/
		print_r(json_encode(true)); 
	}
	else
	{
		print_r(json_encode(false)); 
	}
}

function allPost($db)
{
	$fIds=loggedUser($_POST->user_id,$db);
	$result = $db->query("SELECT `ang_post`.*,`ang_user`.`fname` FROM `ang_post` inner join `ang_user` on `ang_post`.`posted_by`=`ang_user`.`user_id` WHERE `ang_post`.`post_status`='1' AND `ang_user`.`user_id`='$_POST->user_id' ORDER BY `ang_post`.`posted_date` DESC limit 10" );
	$data['posts']=array();
	if(@mysqli_num_rows($result))
	{
		while($records=$result->fetch_array()){			
			$data['posts'][]=$records;	
		}
	} 
	
	$other = $db->query("SELECT `ang_post`.*,`ang_user`.`fname` FROM `ang_post` inner join `ang_user` on `ang_post`.`posted_by`=`ang_user`.`user_id` WHERE `ang_post`.`post_status`=1 AND `ang_post`.`posted_by` IN ($fIds) ORDER BY `ang_post`.`posted_date` DESC");
	if(@mysqli_num_rows($other))
	{ 
		while($oth=$other->fetch_array())
		{			
			$data['posts'][]=$oth;	
		}
	}

	print_r(json_encode($data)); 
	
	
}
function userList($db)
{	
	$abc=0;
	$get_fr_id=$db->query("SELECT CONCAT_WS(',', first_user, second_user) as `user_id` FROM `ang_friends` WHERE (`second_user`='$_POST->user_id' OR  `first_user`='$_POST->user_id') AND (`status`='Accepted' OR `status`='Pending' OR `status`='Blocked')");
	
	if(@mysqli_num_rows($get_fr_id))
	{
		$rt_arr=[];
		while ($records1=$get_fr_id->fetch_array()){
			$rt_arr[]=$records1['user_id'];
		}
		$YYYY = implode(',' , array_unique(array_map('trim',$rt_arr)));
		$y = explode(',', $YYYY);
		$abc=implode(',' , array_unique(array_map('trim',$y)));
	}

	$result = $db->query("SELECT * FROM `ang_user` WHERE `status`=1 AND `user_id`!='$_POST->user_id' AND `user_id` NOT IN ($abc)");
	//echo "SELECT * FROM `ang_user` WHERE `status`=1 AND `user_id`!='$_POST->user_id' AND `user_id` NOT IN ($abc)";
	if(@mysqli_num_rows($result))
	{
		$data['users']=array();
		while($records=$result->fetch_array()){			
			$data['users'][]=$records;	
		}
		print_r(json_encode($data)); 
	}
	else
	{
		print_r(json_encode('0')); 
	}
}

function addPost($db)
{
	$user_id=$_POST->user_id;
	$post_msg=$_POST->message;	
	$posted_date=date("Y-m-d H:i:s");
	if(isset($_POST->post_id)&& $_POST->post_id !=='')
	{
		$sql="UPDATE `ang_post` SET `post_msg`='$post_msg' WHERE `post_id`='".$post_id."'";
	}
	else
	{
		$sql="INSERT INTO `ang_post`(`post_msg`, `posted_by`, `posted_date`, `post_status`) VALUES ('$post_msg','$user_id','$posted_date','1')";
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

function loggedUser($user_id,$db){
	$result=$db->query("SELECT * FROM `ang_user` WHERE `user_id`='$user_id'");
	if(@mysqli_num_rows($result))
	{
		$records=$result->fetch_array();
		return $records['friends_id'];
	}
}
function friendRequest($db){
	$result=$db->query("SELECT * FROM `ang_user` WHERE `user_id` IN (SELECT `first_user` FROM `ang_friends` WHERE `second_user`='$_POST->user_id' AND `status`='Pending')");
	if(@mysqli_num_rows($result))
	{
		$data['requests']=array();
		while($records=$result->fetch_assoc()){			
			$data['requests'][]=$records;	
		}
		print_r(json_encode($data)); 
	}else{
		print_r(json_encode(false));
	}
}
function friendList($db){
	$abc=0;
	$get_fr_id=$db->query("SELECT CONCAT_WS(',', first_user, second_user) as `user_id` FROM `ang_friends` WHERE (`second_user`='$_POST->user_id' OR  `first_user`='$_POST->user_id') AND `status`='Accepted'");
	if(@mysqli_num_rows($get_fr_id))
	{
		$rt_arr=[];
		while ($records1=$get_fr_id->fetch_array()){
			$rt_arr[]=$records1['user_id'];
		}
		$YYYY = implode(',' , array_unique(array_map('trim',$rt_arr)));
		$y = explode(',', $YYYY);
		$abc=implode(',' , array_unique(array_map('trim',$y)));
	}
	$result=$db->query("SELECT * FROM `ang_user` WHERE `user_id` IN ($abc) AND `user_id`!='$_POST->user_id'");
	if(@mysqli_num_rows($result))
	{
		$data['friends']=array();
		while($records=$result->fetch_array()){			
			$data['friends'][]=$records;	
		}
		print_r(json_encode($data)); 
	}else{
		print_r(json_encode(false));
	}
}

function viewUserDetails($db){
	$result=$db->query("SELECT * FROM `ang_user` WHERE `user_id`='$_POST->to_friend'");
	if(@mysqli_num_rows($result))
	{
		$records=$result->fetch_array();
		print_r(json_encode($records));
	}
	else
	{
		print_r(json_encode(false));
	}
}
function getMessageSender($db,$ids){
	$result=$db->query("SELECT * FROM `ang_user` WHERE `user_id` IN ($ids)");
	if(@mysqli_num_rows($result))
	{
		$records=$result->fetch_array();
		
	}
	else
	{
		return'0';
	}
}


function get_time_ago( $time )
{
	$time_difference = time() - $time;

	if( $time_difference < 1 ) { return 'less than 1 second ago'; }
	$condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
		30 * 24 * 60 * 60       =>  'month',
		24 * 60 * 60            =>  'day',
		60 * 60                 =>  'hour',
		60                      =>  'minute',
		1                       =>  'second'
	);

	foreach( $condition as $secs => $str )
	{
		$d = $time_difference / $secs;

		if( $d >= 1 )
		{
			$t = round( $d );
			return  $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
		}
	}
}


?>