<?php

$db_host 	 = "localhost";
$db_username = "bhd_dev";
$db_password = "Doggie14";
$db_database = "bhd_dev";

$conn = new mysqli($db_host,$db_username,$db_password,$db_database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

//$conn = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_username, $db_password);

echo 'Connected!';

try {
	echo 'Created: ';
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}

/**
 * Insert new message for 'messages' table.
 * 
 * @param {String} $key
 * @param {String} $value
 * @return {Number} $rowId
 */
function insertSetting($fromId, $toId, $ccId, $bccId, $body, $status, $options) {
	// 1. Variables
	global $conn;
	$rowId = 0;
	$query = "INSERT INTO `settings` (`id`, `key`, `value`) VALUES (NULL, ?, ?);";
	$stmt = NULL;
	// 2. Validate
	if (is_null($key) || empty($key) || !is_string($key)) {
		throw new Exception("Invalid parameter: '$key'");
	} elseif (is_null($value) || empty($value) || !is_string($value)) {
		throw new Exception("Invalid parameter: '$value'");
	}
	// 3. Sanitize
	// 4. Execution
	if ($stmt = $conn->prepare($query)) {
		$stmt->bind_param("ss", $key, $value);
		if ($stmt->execute() && $stmt->affected_rows > 0) {
			$rowId = $stmt->insert_id;
		}
		$stmt->close();
	}
	// 5. End
	return $rowId;
}

/**
 * Insert new setting for 'settings' table.
 * 
 * @param {String} $key
 * @param {String} $value
 * @return {Number} $rowId
 */
function insertSetting($key, $value) {
	// 1. Variables
	global $conn;
	$rowId = 0;
	$query = "INSERT INTO `settings` (`id`, `key`, `value`) VALUES (NULL, ?, ?);";
	$stmt = NULL;
	// 2. Validate
	if (is_null($key) || empty($key) || !is_string($key)) {
		throw new Exception("Invalid parameter: '$key'");
	} elseif (is_null($value) || empty($value) || !is_string($value)) {
		throw new Exception("Invalid parameter: '$value'");
	}
	// 3. Sanitize
	// 4. Execution
	if ($stmt = $conn->prepare($query)) {
		$stmt->bind_param("ss", $key, $value);
		if ($stmt->execute() && $stmt->affected_rows > 0) {
			$rowId = $stmt->insert_id;
		}
		$stmt->close();
	}
	// 5. End
	return $rowId;
}

/**
 * Insert new user for 'users' table.
 * 
 * @param {String} $username
 * @param {String} $password
 * @param {Boolean|Number} $enable
 * @param {String} $permissions
 * @param {String} $ip
 * @return {Number} $rowId
 */
function insertUser($username, $password, $enable, $permissions, $ip) {
	// 1. Variables
	global $conn;
	$rowId = 0;
	$query = "INSERT INTO `users` (`id`, `username`, `password`, `enable`, `permissions`, `ip`) VALUES (NULL, ?, ?, ?, ?, ?);";
	$stmt = NULL;
	// 2. Validate
	if (is_null($username) || empty($username) || !is_string($username)) {
		throw new Exception("Invalid parameter: '$username'");
	} elseif (is_null($password) || empty($password) || !is_string($password)) {
		throw new Exception("Invalid parameter: '$password'");
	} elseif (is_null($enable) || !isset($enable) || !(is_numeric($enable) || is_bool($enable))) {
		throw new Exception("Invalid parameter: '$enable'");
	} elseif (is_null($permissions) || empty($permissions) || !is_string($permissions)) {
		throw new Exception("Invalid parameter: '$permissions'");
	} elseif (is_null($ip) || empty($ip) || !is_string($ip)) {
		throw new Exception("Invalid parameter: '$ip'");
	}
	// 3. Sanitize
	if (is_bool($enable)) {
		$enable = ($enable ? 1 : 0);
	}
	$password = password_hash($password, PASSWORD_DEFAULT);
	// 4. Execution
	if ($stmt = $conn->prepare($query)) {
		$stmt->bind_param("ssiss", $username, $password, $enable, $permissions, $ip);
		if ($stmt->execute() && $stmt->affected_rows > 0) {
			$rowId = $stmt->insert_id;
		}
		$stmt->close();
	}
	// 5. End
	return $rowId;
}
?>