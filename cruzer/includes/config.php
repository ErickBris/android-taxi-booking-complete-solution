<?php

$host= "localhost";
$db_name="jetbench_taxi";
$db_username="jetbench_admin";
$db_password="Asdf!234";
try
{
$pdo= new PDO('mysql:host='. $host .';dbname='.$db_name, $db_username, $db_password);
}
catch(PDOException $e)
{
exit("Error Connectiong to database");
}
?>