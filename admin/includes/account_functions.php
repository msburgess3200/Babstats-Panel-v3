<?php

function god(&$var, $defaultValue = null) {
	return !is_null($var) && !empty($var) ? $var : $defaultValue;
}

// function routineLogin($username, $password, $ip) {
	// /* Variables */
	// $userId = 0;
	// $stmt = null;
	// /* Validation */
	// if (is_null($username)) {
		// throw new Exception('Invalid var: $username');
	// } elseif (is_null($password)) {
		// throw new Exception('Invalid var: $password');
	// } elseif (is_null($ip)) {
		// throw new Exception('Invalid var: $ip');
	// }
	// /* Retrieve user hash */
	
	// return $userId;
// }

function routineLogin() {
	$userId = 0;
	$username = god($_POST['username'], null);
	$password = god($_POST['password'], null);
	$remember = god($_POST['remember'], 0);
	$ip = getRealIpAddr();
	$stmt = null;
	$passwordHash = null;
	
	if (is_null($username)) {
		throw new Exception('Invalid var: $username');
	} elseif (is_null($password)) {
		throw new Exception('Invalid var: $password');
	} elseif (is_null($ip)) {
		throw new Exception('Invalid var: $ip');
	}
	
	// Retrieve from DB
	
	
	//SELECT `ROUTINE_LOGIN`('antony.hixon', 'd14e0437e11e2b6bf33899272e5dd43d', '444444') AS `user.id`
	if ($remember == 1) {
		setCookie('loggedin',1, time() + 604800);
		setCookie('username',$username, time() + 604800);
		setCookie('userid', $userId, time() + 604800);
		setCookie('remember', $remember, time() + 604800);
	}
	
	return $userId;
}

?>