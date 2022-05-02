<?php



if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['number']) && isset($_POST['password']) ){

	$name=$_POST['name'];
	$number=$_POST['number'];
	$email = $_POST['email'];
	$password=$_POST['password'];


	$response = array();

	require_once 'core/db_connect.php';

	$db = new DB_CONNECT();

		 		$result = mysql_query("UPDATE users SET user_name='$name', user_num='$number' , password='$password' WHERE user_email='$email'");
	
				if (!$result) {

			   		$response["success"] = 0;
					$response["message"] = "Cannot edit data. Please try again";
					
					echo json_encode($response);
				}else{
					$response["success"] = 1;
					$response["message"] = "Info Update Successfully.";
					echo json_encode($response);
				}
		 }else{

	$response["success"] = 0;
	$response["message"] = "Required field(s) is missing";
	// echoing JSON response
	echo json_encode($response);

}

?>