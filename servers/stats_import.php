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



// security feature for..whatever reason..
global $client;
$client = "uploader";

require("config.php");
require("functions.php");
require("common.php");

// check for BMT... (not much of a check though..)
if (isset($_POST['bmt'] && $_POST['bmt'] == 1)){
	$bmt = $_POST['bmt'];
}else{
	die("What? Where's Waldo?");
}
// check for incoming data...
if (isset($_POST['data']) && $_POST['data'] !== "" && $_POST['data'] !== NULL){
	$data = $_POST['data'];
}
// check for a serverid...
if (isset($_POST['serverid']) && $_POST['serverid'] !== NULL && $_POST['serverid'] !== ""){
	$serverid = $_POST['serverid'];
}

DBConnect();
MakeTableNames();

$data =	base64_decode(str_replace(" ", "+", $data));

$query = DBQuery("SELECT * FROM `". $servers_table ."` WHERE serverid='". $serverid ."';");
if(DBNumRows($query) == 0) {
  echo "Invalid server id code (". $serverid .")\n";
  exit; 
}
$question = DBQuery("INSERT INTO `queue` (`serverid`, `data`, `type`) VALUES ('". $serverid ."', '". $data ."', 1);");
echo "Stats uploaded for processing!";
?>