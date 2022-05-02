<?php

if(isset($_POST['user_id'])){
$response = array();
require_once 'core/db_connect.php';
$db = new DB_CONNECT();

//Pass your driver number here
$user_id = $_POST['user_id'];

$result = mysql_query("select * from texirequest where sender_id = '$user_id' order by id DESC");

	if($result){
		$response["ridelist"] = array();
		while ($rowBooking=mysql_fetch_array($result)) {
					$infos=array();
					$infos["id"]=$rowBooking["id"];
					$infos["driver_name"]=$rowBooking["driver_name"];
					$infos["driver_email"]=$rowBooking["driver_email"];
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