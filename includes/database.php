<?php
/*
 * Copyright (c) 2018, Michael Burgess a.k.a SkyWalker
 * All rights reserved.
 *
 * Redistribution and use with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions must retain the above copyright notice.
 * File licence.txt must not be removed from the package.
 *
 * Author        : Michael Burgess a.k.a SkyWalker
 * E-mail        : skywalker@ctsgaming.com
 * 
 * Finalized     : 23rd May 2018
 * Website       : http://www.ctsgaming.com
 */


// compatiablity
$tablepre = $bmt_pre;
$tablepre2 = $bmt_m_pre;
// end compatiablity

/**
 * Escape string with MySQL or MySQLi
 */
function escape_string($str) {
	$escStr = '';
	if (isset($sql_mode)){
		switch($sql_mode) {
			case 1:
				$escStr = mysql_escape_string($str);
				break;
			case 2:
				$escStr = mysqli_escape_string($str);
				break;
		}
		return $escStr;
	}
}
/**
 * Fetch query array with MySQL or MySQLi
 */
function fetch_array($query, $options) {
	$result = null;
	if (isset($sql_mode)){
		switch($sql_mode) {
			case 1:
			if (isset($options) && $options !== NULL){
				$result = mysql_fetch_array($query,$options) or die("An error occurred!<br />\n".mysql_error());
			}else{
				$result = mysql_fetch_array($query) or die("An error occurred!<br />\n".mysql_error());
			}
				break;
			case 2:
			if (isset($options) && $options !== NULL){
				$result = mysqli_fetch_array($query,$options) or die("An error occurred!<br />\n".mysqli_error());
			}else{
				$result = mysqli_fetch_array($query) or die("An error occurred!<br />\n".mysqli_error());
			}
				break;
		}
		return $result;
	}
}
/**
 * Fetch query array with MySQL or MySQLi
 */
function fetch_assoc($query) {
	$result = null;
	if (isset($sql_mode)){
	switch($sql_mode) {
			case 1:
				$result = mysql_fetch_assoc($query);
				break;
			case 2:
				$result = mysqli_fetch_assoc($query);
				break;
		}
		return $result;
	}
}
/**
 * Number of rows with MySQL or MySQLi
 */
function num_rows($query) {
	$num = 0;
	if (isset($sql_mode)){
		switch($sql_mode) {
			case 1:
				$num = mysql_num_rows($query);
				break;
			case 2:
				$num = mysqli_num_rows($query);
				break;
		}
		return $num;
	}
}
/**
 * Query with MySQL or MySQLi
 */
function query($queryString) {
	$results = null;
	if (isset($sql_mode)){
		switch($sql_mode) {
			case 1:
				$results = mysql_query($queryString) or die("An error occurred!<br />\n".mysql_error());
				break;
			case 2:
				$results = mysqli_query($queryString) or die("An error occurred!<br />\n".mysqli_error());
				break;
		}
		return $results;
	}
}
/**
 * Attempt to connect to database.
 */
function connectToDatabase() {
	if (isset($sql_mode)){
		switch($sql_mode) {
			case 1:
				connectToMySQL();
				break;
			case 2:
				connectToMySQLi();
				break;
		}
	}
}
/**
 * Attempt to connect to MySQL database.
 */
function connectToMySQL() {
	mysql_connect($db_host,$db_username,$db_password)or die("An error occurred!\n<br />\n".mysql_error());
	mysql_select_db($db_database)or die("An error occurred!\n<br />\n".mysql_error());
}
/**
 * Attempt to connect to MySQLi database.
 */
function connectToMySQLi() {
	mysqli_connect($db_host,$db_username,$db_password,$db_database)or die("An error occurred!\n<br />\n".mysqli_error());
} 
?>