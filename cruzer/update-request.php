<?php

if(isset($_POST['id']) && isset($_POST['accept'])){
$response = array();
require_once 'core/db_connect.php';
$db = new DB_CONNECT();

//Pass your driver number here
$id = $_POST['id'];
$accept=$_POST['accept'];

//Get user id of the driver
//As the conceptAssignments have the driverNo instead of the user id, we need to get the user id
$result = mysql_query("UPDATE texirequest SET accept ='$accept' where id = '$id'");

	if($result){
			$response["success"] = 1;
			$response["message"] = "Data Update successful.";
			echo json_encode($response);
		}else{
			$response["success"] = 0;
			$response["message"] = "Could not load data".mysql_error();
			echo json_encode($response);
		}
}else{

	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";
	// echoing JSON response
	echo json_encode($response);

}
?>