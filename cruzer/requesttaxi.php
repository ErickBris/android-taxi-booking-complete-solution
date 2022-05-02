<?php
// array for JSON response

// check for required fields

if (isset($_POST['driver_id']) && isset($_POST['driver_name'])  && isset($_POST['driver_email']) && isset($_POST['sender_id']) && isset($_POST['name']) && isset($_POST['phone'])
	&& isset($_POST['location']) && isset($_POST['droplocation']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['timedate']) ){

	$response = array();

	$driver_id = $_POST['driver_id'];
	$driver_name = $_POST['driver_name'];
	$driver_email = $_POST['driver_email'];
	$sender_id = $_POST['sender_id'];
	$name = $_POST['name'];
	$phone=$_POST['phone'];
	$location=$_POST['location'];
	$droplocation=$_POST['droplocation'];
	$latitude=$_POST['latitude'];
	$longitude=$_POST['longitude'];
	$timedate=$_POST['timedate'];
         $rate=$_POST['rate'];


	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	$sql="INSERT INTO texirequest (driver_id, driver_name, driver_email,sender_id,name,phone,location,droplocation,latitude,longitude,timedate,rate)".
	"VALUES ('$driver_id', '$driver_name','$driver_email','$sender_id','$name','$phone','$location','$droplocation','$latitude','$longitude','$timedate','$rate')";
	$result = mysql_query($sql);
	
	if ($result) {
		$response["success"] = 1;
		$response["message"] = "Taxi request is succcessful";
		echo json_encode($response);
	}else{

		$response["success"] = 0;
		$response["message"] = "Oops! An error occurred.".mysql_error();

		echo json_encode($response);
		}
}
	else {
// required field is missing
	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";
	// echoing JSON response
	echo json_encode($response);
}

?>