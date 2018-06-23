<?php
require("includes/config.php");
// require("includes/functions.php");
/* Validation */
if (is_null($sql_mode) || empty($sql_mode)) {
	die('\'$sql_mode\' is invalid!');
} else if (is_null($bmt_pre) || empty($bmt_pre)) {
	die('\'$bmt_pre\' is invalid!');
}
//connectToDatabase();
// mysql_connect($db_host,$db_username,$db_password) or die("Nope!".mysql_error());
// mysql_select_db($db_database) or die ("Nope!".mysql_error());
// connectToMySQL();
//$query = query("SELECT * FROM `".$bmt_pre."servers` LIMIT 1;");
//$row  = fetch_array($query, null);
//print_r($row);


?>
<!--
/*
    _            ____               ____        _____    _   _    ____   _  __      __   __   U  ___ u   _   _        ____   _   _      _      _   _     ____  U _____ u 
U  /"\  u     U | __")u    ___   U /"___|u     |" ___|U |"|u| |U /"___| |"|/ /      \ \ / /    \/"_ \/U |"|u| |    U /"___| |'| |'| U  /"\  u | \ |"| U /"___|u\| ___"|/ 
 \/ _ \/       \|  _ \/   |_"_|  \| |  _ /    U| |_  u \| |\| |\| | u   | ' /  U  u  \ V /     | | | | \| |\| |    \| | u  /| |_| |\ \/ _ \/ <|  \| |>\| |  _ / |  _|"   
 / ___ \        | |_) |    | |    | |_| |     \|  _|/   | |_| | | |/__U/| . \\u/___\U_|"|_u.-,_| |_| |  | |_| |     | |/__ U|  _  |u / ___ \ U| |\  |u | |_| |  | |___   
/_/   \_\       |____/   U/| |\u   \____|      |_|     <<\___/   \____| |_|\_\|__"__| |_|   \_)-\___/  <<\___/       \____| |_| |_| /_/   \_\ |_| \_|   \____|  |_____|  
 \\    >>      _|| \\_.-,_|___|_,-._)(|_       )(\\,- (__) )(   _// \\,-,>> \\,-. .-,//|(_       \\   (__) )(       _// \\  //   \\  \\    >> ||   \\,-._)(|_   <<   >>  
(__)  (__)    (__) (__)\_)-' '-(_/(__)__)     (__)(_/     (__) (__)(__)\.)   (_/   \_) (__)     (__)      (__)     (__)(__)(_") ("_)(__)  (__)(_")  (_/(__)__) (__) (__) 
*/

-->