<?php
// array for JSON response

// check for required fields

if (isset($_POST['email']) ){

	$response = array();

	$email = $_POST['email'];

	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	$sql="Select online from locations WHERE email='$email' limit 1 ";
	$result = mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {

$response["status"] = $row["online"];
		
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