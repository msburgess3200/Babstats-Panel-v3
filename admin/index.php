<?php
/* Imports */
//require('stats.bhd.ctsgaming.com/dev/admin/includes/functions.php');
//require('stats.bhd.ctsgaming.com/dev/admin/includes/config.php');

require '../includes/config.php';
require 'includes/functions.php';
require 'includes/account_functions.php';

/* Variables */
// $action = '';
$username = '';
$password = '';
$remember = '';

/* Execution */
session_start();
connectToDatabase();

$action = $_GET['action'];

if (isset($action)) {
	if ($action == 'login') {
		doLogin();
	} elseif ($action == 'logout') {
		doLogout();
	}
}

function doLogin() {
	$uid = 0;
	$rows = null;
	/* Grab user data */
	$username = $_POST['username'];
	$password = $_POST['password'];
	$remember = $_POST['remember'];
	/* Validate */
	if (isset($username) && isset($password) && isset($remember)) {
		/* Sanitize */
		$username = escape_string($username);
		$password = md5($password);
		$password = escape_string($password);
		$remember = escape_string($remember);
		/* Exec */
		$rows = query("SELECT `id` FROM `users` WHERE `username` = '".$username."' AND `password` = '".$password."';");
		if (isset($rows) && num_rows($rows) == 1) {
			$uid = fetch_array($rows)['id'];
			/* Update user IP */
			query("UPDATE `users` SET `ip` = '".getRealIpAddr()."' WHERE `id` = ".$uid.";");
			if ($remember == 1) {
				setCookie("loggedin",1,time()+604800);
				setCookie("username",$username,time()+604800);
				setCookie("userid",$uid,time()+604800);
				setCookie("remember",$remember,time()+604800);
			}
		}
	}
	return $uid;
}

function doLogout() {
	unSet($_COOKIE['loggedin']);
	unSet($_COOKIE['username']);
	unSet($_COOKIE['userid']);
	unSet($_COOKIE['remember']);
	setCookie("loggedin",0,time()-604800);
	setCookie("username",0,time()-604800);
	setCookie("userid",0,time()-604800);
	setCookie("remember",0,time()-604800);
	header("Location: index.php?err=logout");
}


if (isset($_GET['action']) && $_GET['action'] == "login"){
	require("../includes/config.php");
	if (isset($_POST['username']) && $_POST['username'] !== "" && $_POST['username'] !== NULL){
		$username = $_POST['username'];
	}
	if (isset($_POST['password']) && $_POST['password'] !== "" && $_POST['password'] !== NULL){
		$password = md5($_POST['password']);
	}
	if (isset($_POST['remember']) && $_POST['remember'] !== "" && $_POST['remember'] !== NULL){
		$remember = $_POST['remember'];
	}
	// if there aren't any blank fields.. proceed.
	// for security reasons let's check the data for SQL injections...
	
	if (isset($sql_mode) && $sql_mode == 1){
		$username = mysql_escape_string($username);
		$password = mysql_escape_string($password);
		$remember = mysql_escape_string($remember);
	}elseif (isset($sql_mode) && $sql_mode == 2){
		$username = mysqli_escape_string($username);
		$password = mysqli_escape_string($password);
		$remember = mysqli_escape_string($remember);
	}
	// open connection to sql server:
	if (isset($sql_mode) && $sql_mode == 1){
		mysql_connect ($db_host,$db_username,$db_password)or die("An error occurred!\n<br />\n".mysql_error());
		mysql_select_db ($db_database)or die("An error occurred!\n<br />\n".mysql_error());
	}elseif (isset($sql_mode) && $sql_mode == 2){
		mysqli_connect($db_host,$db_username,$db_password,$db_database)or die("An error occurred!\n<br />\n".mysqli_error());
	}
	// data check
	if (isset($sql_mode) && $sql_mode == 1){
		$data = mysql_query("SELECT * FROM `users` WHERE `username` = '". $username ."' AND `password` = '". $password ."';");
	}elseif (isset($sql_mode) && $sql_mode == 2){
		$data = mysqli_query("SELECT * FROM `users` WHERE `username` = '". $username ."' AND `password` = '". $password ."';");
	}
	if (isset($sql_mode) && $sql_mode == 1){
		$num = mysql_num_rows($data);
		$user= mysql_fetch_array($data);
	}elseif (isset($sql_mode) && $sql_mode == 2){
		$num = mysqli_num_rows($data);
		$user= mysqli_fetch_array($data);
	}
	$uid = $user['id']; // get userid from database
	if (isset($num) && $num == 1 && $remember == 1){
		if (isset($sql_mode) && $sql_mode == 1){
			mysql_query("UPDATE `users` SET `ip` = '". getRealIpAddr() ."' WHERE `id` = ". $uid ."; ");
		}elseif (isset($sql_mode) && $sql_mode == 2){
			mysqli_query("UPDATE `users` SET `ip` = '". getRealIpAddr() ."' WHERE `id` = ". $uid ."; ");
		}
		setCookie("loggedin",1,time()+604800);
		setCookie("username",$username,time()+604800);
		setCookie("userid",$uid,time()+604800);
		setCookie("remember",$remember,time()+604800);
		echo "Login with rememberme";
		header("Location: index.php?page=home");
		exit;
	}elseif (isset($num) && $num == 1 && $remember !== 1){
		setCookie("loggedin",1);
		setCookie("username",$username);
		setCookie("userid",$uid);
		setCookie("remember",$remember);
		echo "Login without rememberme";
		header("Location: index.php?page=home");
		exit;
	}
}elseif (isset($_GET['action']) && $_GET['action'] == "logout"){
	unSet($_COOKIE['loggedin']);
	unSet($_COOKIE['username']);
	unSet($_COOKIE['userid']);
	unSet($_COOKIE['remember']);
	setCookie("loggedin",0,time()-604800);
	setCookie("username",0,time()-604800);
	setCookie("userid",0,time()-604800);
	setCookie("remember",0,time()-604800);
	header("Location: index.php?err=logout");
	exit;
}
require("../includes/config.php");
// open connection to sql server:
	if (isset($sql_mode) && $sql_mode == 1){
		mysql_connect ($db_host,$db_username,$db_password) or die("An error occurred!\n<br />\n".mysql_error());
		mysql_select_db ($db_database)or die("An error occurred!\n<br />\n".mysql_error());
	}elseif (isset($sql_mode) && $sql_mode == 2){
		mysqli_connect($db_host,$db_username,$db_password,$db_database)or die("An error occurred!\n<br />\n".mysqli_error());
	}
// extra security...
if (isset($sql_mode) && $sql_mode == 1 && isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
	$q = mysql_query("SELECT `id`, `username`, `permissions`, `ip` FROM `users` WHERE `id` = ". $_COOKIE['userid'] ." AND `username` = '". $_COOKIE['username'] ."' LIMIT 1;")or die("An error occurred!\n<br />\n".mysql_error());
	$ans = mysql_fetch_array($q)or die("An error occurred!\n<br />\n".mysql_error());
}elseif (isset($sql_mode) && $sql_mode == 2 && isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
	$q = mysqli_query("SELECT `id`, `username`, `permissions`, `ip` FROM `users` WHERE `id` = ". $_COOKIE['userid'] ." AND `username` = '". $_COOKIE['username'] ."' LIMIT 1;");
	$ans = mysqli_fetch_array($q);
}
if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
	$ip=getRealIpAddr();
	if ($ans['ip'] !== $ip){
		unSet($_COOKIE['loggedin']);
		unSet($_COOKIE['username']);
		unSet($_COOKIE['userid']);
		unSet($_COOKIE['remember']);
		setCookie("loggedin",0,time()-604800);
		setCookie("username",0,time()-604800);
		setCookie("userid",0,time()-604800);
		setCookie("remember",0,time()-604800);
		header("Location: index.php?err=6");
		exit;
	}	
}	
// get permissions...
if (isset($sql_mode) && $sql_mode == 1 && isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
	$q = mysql_query("SELECT `id`, `username`, `permissions` FROM `users` WHERE `id` = ". $_COOKIE['userid'] ." AND `username` = '". $_COOKIE['username'] ."' LIMIT 1;")or die("An error occurred!\n<br />\n".mysql_error());
	$ans = mysql_fetch_array($q)or die("An error occurred!\n<br />\n".mysql_error());
	$pep = array(); // need to make this an array for incoming data...
	$pep = json_decode($ans['permissions'],true);
	$perm = $pep;
}elseif (isset($sql_mode) && $sql_mode == 2 && isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
	$q = mysqli_query("SELECT `id`, `username`, `permissions` FROM `users` WHERE `id` = ". $_COOKIE['userid'] ." AND `username` = '". $_COOKIE['username'] ."' LIMIT 1;");
	$ans = mysqli_fetch_array($q);
	$pep = array(); // need to make this an array for incoming data...
	$pep = json_decode($ans['permissions'],true);
	$perm = $pep;
}
// pages
if (isset($_GET['page'])){
		switch($_GET['page']){
			case "home":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (in_array("a",$perm) || in_array("z",$perm)){
					include("pages/home.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{ 
				header("Location: index.php?err=1");
			}exit;break;
			case "users":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("b",$perm) || array_key_exists("z",$perm)){
					include("pages/users.php");
				}else{
					header("Location: index.php?err=1");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "servers":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("c",$perm) || array_key_exists("z",$perm)){
					include("pages/servers.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "players":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("d",$perm) || array_key_exists("z",$perm)){
					include("pages/players.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "squads":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("e",$perm) || array_key_exists("z",$perm)){
					include("pages/squads.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "weapons":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("f",$perm) || array_key_exists("z",$perm)){
					include("pages/weapons.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "maps":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("g",$perm) || array_key_exists("z",$perm)){
					include("pages/maps.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "awards":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("h",$perm) || array_key_exists("z",$perm)){
					include("pages/awards.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "awards-weapons":
			if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
				if (array_key_exists("i",$perm) || array_key_exists("z",$perm)){
					include("pages/awards-weapons.php");
				}else{
					header("Location: index.php?err=4");
				}
			}else{
				header("Location: index.php?err=1");
			}exit;break;
			case "messages":
				if (isset($_COOKIE['loggedin']) && $_COOKIE['loggedin'] == 1){
					if (array_key_exists("k",$perm) || in_array("z",$perm)){
						if (isset($_GET['view']) && $_GET['view'] == "inbox" && in_array("a",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/inbox.php");
							exit;
						}elseif (isset($_GET['view']) && $_GET['view'] == "compose" && in_array("b",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/compose.php");
							exit;
						}elseif (isset($_GET['view']) && $_GET['view'] == "reply" && in_array("c",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/reply.php");
							exit;
						}elseif(isset($_GET['view']) && $_GET['view'] == "sent" && in_array("d",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/sent.php");
							exit;
						}elseif (isset($_GET['view']) && $_GET['view'] == "trash" && in_array("e",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/trash.php");
							exit;
						}elseif (isset($_GET['view']) && $_GET['view'] == "viewmsg" && in_array("f",$perm['k']) || in_array("z",$perm['k']) || in_array("z",$perm)){
							include("pages/messages/viewmeg.php");
							exit;
						}
					}
				}else{
					header("Location: index.php?err=1");
				}exit;break;
			case "login":
				include("pages/login.php");
			exit;break;
			case "error":
				include("pages/error.php");
			exit;break;
			default:
				include("pages/error.php");
				exit;
			exit;break;
		}
	}
	// this prevents blank pages at INDEX.
	// May 12th, 2018 - Patch: 3.1
	if (!isset($_GET['err']) && !isset($_GET['action']) && !isset($_GET['page'])){
		header("Location: index.php?page=home");
	}
	// this redirects to login page after being logged out..
	// May 12th, 2018 - Patch 3.2
	if (!isset($_COOKIE['loggedin'])){
		header("Location: index.php?page=login");
		exit;
	}
	
	// error pages
	if (isset($_GET['err'])){
		$error = $_GET['err'];
		include("pages/error.php");
		exit;
	}
	
	
?>