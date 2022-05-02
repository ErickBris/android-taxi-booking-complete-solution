<?php
// array for JSON response

// check for required fields

if (isset($_POST['email']) && isset($_POST['latitude']) && isset($_POST['longitude'])){

	$response = array();

	$email = $_POST['email'];
	$longitude = $_POST['longitude'];
	$latitude = $_POST['latitude'];
$bearing= $_POST['bearing'];

	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	$sql="UPDATE locations SET latitude='$latitude', longitude='$longitude',bearing='$bearing' WHERE email='$email'";
	$result = mysql_query($sql);
	
	if ($result) {
		$response["success"] = 1;
		$response["message"] = "Location Update is successful";
	} else {
		$response["success"] = 0;
		$response["message"] = "Location Update is failed";
	}
	echo json_encode($response);
}
	else {
// required field is missing
	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";
	// echoing JSON response
	echo json_encode($response);
}

?>