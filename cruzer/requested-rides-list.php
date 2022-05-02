<?php

if(isset($_POST['user_email'])){
$response = array();
require_once 'core/db_connect.php';
$db = new DB_CONNECT();

//Pass your driver number here
$driver_email = $_POST['user_email'];

//Get user id of the driver
//As the conceptAssignments have the driverNo instead of the user id, we need to get the user id
$result = mysql_query("select * from texirequest where driver_email = '$driver_email' order by id DESC");

	if($result){
		$response["ridelist"] = array();
		while ($rowBooking=mysql_fetch_array($result)) {
					$infos=array();
					$infos["id"]=$rowBooking["id"];
					$infos["driver_id"]=$rowBooking["driver_id"];
					$infos["sender_id"]=$rowBooking["sender_id"];
					$infos["name"]=$rowBooking["name"];
					$infos["phone"]=$rowBooking["phone"];
					$infos["droplocation"]=$rowBooking["droplocation"];
					$infos["location"]=$rowBooking["location"];
					$infos["latitude"]=$rowBooking["latitude"];
					$infos["longitude"]=$rowBooking["longitude"];
					$infos["timedate"]=$rowBooking["timedate"];
$infos["accept"]=$rowBooking["accept"];


					array_push($response["ridelist"], $infos);
				}
			$response["success"] = 1;
			$response["message"] = "Loading Data successful.";
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