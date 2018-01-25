<?php 
date_default_timezone_set('UTC');
header('Content-Type: application/json');
//$_FILES = json_decode(file_get_contents("php://input"));
$data['fileName']='';
if(isset($_FILES['file']['name']) && $_FILES['file']['name']!==''){
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	$fileName=uniqid().'_profile_pic.'.$ext;
	$destination='../uploads/profile_pic/'.$fileName;
	if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
		$data['fileName']=$fileName;
		echo json_encode($data);
	}else{
		echo json_encode($data);
	}
}else{
		echo json_encode($data);
	}


 ?>