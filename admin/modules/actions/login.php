<?php
if (isset($_POST['username']) && $_POST['username'] !== "" && $_POST['username'] !== NULL){
	$username = $_POST['username'];
}
if (isset($_POST['password']) && $_POST['password'] !== "" && $_POST['password'] !== NULL){
	$password = md5($_POST['password']);
}
// if there aren't any blank fields.. proceed.
// for security reasons let's check the data for SQL injections...
$username = mysql_escape_string($username);
$password = mysql_escape_string($password);

// open connection to sql server:
DBConnect();

// data check
$data = DBQuery("SELECT * FROM `users` WHERE `username` = '". $username ."' AND `password` = '". $password ."';");

$num = DBNumRows($data);
echo $num;
?>