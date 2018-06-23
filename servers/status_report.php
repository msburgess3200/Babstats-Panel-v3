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


// if($_GET["version"]) {
	// echo 'status_report.php 1.0.0';
	// die();
// }

error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE);

global $client;
$client = "uploader";

require("../includes/config.php");
require("weapons.php");
require("gametypes.php");
require("rating.php");
require("../includes/database.php");
require("functions.php");

if($_POST)   foreach($_POST   as $Key=>$Value) $$Key = $Value;
if($_GET)    foreach($_GET    as $Key=>$Value) $$Key = $Value;

DBConnect();
MakeTableNames();

if(!isset($data) || !isset($serverid)) {
  echo "No data sent";
  exit;
}

$data =	base64_decode(str_replace(" ", "+", $data));

echo StatusReport($data, $serverid);

?>