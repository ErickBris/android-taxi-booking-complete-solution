<?php
// array for JSON response

// check for required fields


	$response = array();


	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	
	$sql="SELECT * FROM locations WHERE online=1";
	$result = mysql_query($sql) or die(mysql_error());
	
	if (mysql_num_rows($result)>0) {

		$response["location"] = array();
			while ($row=mysql_fetch_array($result)) {
				$files=array();
				$files["id"]=$row["id"];
				$files["name"]=$row["name"];
				$files["email"]=$row["email"];
				$files["number"]=$row["number"];
				$files["latitude"]=$row["latitude"];
				$files["longitude"]=$row["longitude"];
				$files["info"]=$row["vehicleinfo"];
				$files["cost"]=$row["costpkm"];
$files["bearing"]=$row["bearing"];
				array_push($response["location"], $files);

		}

		$response["success"]=1;
		echo json_encode($response);
	} else {
		$response["success"]=0;
		$response["message"]="No Product found";

		echo json_encode($response);
	}

?>