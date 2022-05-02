<?php
// array for JSON response

// check for required fields

if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['number']) && isset($_POST['category'])){

	$response = array();

	$name = $_POST['name'];
	$number = $_POST['number'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$category=$_POST['category'];


/*	if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
 		 
 		 $response["success"] = 0;
		$response["message"] = "Email format is not valid";
		echo json_encode($response);
		exit();
	}*/

	// include db connect class
	require_once 'core/db_connect.php';
	$db = new DB_CONNECT();
	$sql="INSERT INTO users (user_name, password, user_email,user_num,category) VALUES ('$name', '$password', '$email','$number','$category')";
	$result = mysql_query($sql);
	
	if ($result) {
		$response["success"] = 1;
		$response["message"] = "Registration is successfull";
		echo json_encode($response);
	} else {

			if (strpos(mysql_error(),'Duplicate') !== false) {
		    
		 	   $response["success"] = 0;
				$response["message"] = "Email already used. Please try another email";
				echo json_encode($response);

			}else{

		$response["success"] = 0;
		$response["message"] = "Oops! An error occurred.";

		echo json_encode($response);
		}
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