<?php
// array for JSON response

// check for required fields

if (isset($_POST['email']) ){

	$response = array();

	$email = $_POST['email'];

	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	$sql="select  count(rate) as rides, sum(rate) as earnings from texirequest where driver_email = '$email' and accept='2'";
	$result = mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {

$response["rides"] = $row["rides"];
$response["earnings"] = $row["earnings"];
		
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