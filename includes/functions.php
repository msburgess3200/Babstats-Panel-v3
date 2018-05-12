<?php
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$date = mktime(0, 0, 0, date("m")  , date("d"), date("Y")); // Do not edit - todays date.
$today = date("Y-m-d", $date) ; // Do not edit - formatted date.
$date2 = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")); // Do not edit - yesterdays date.
$yesterday = date("Y-m-d", $date2) ; // Do not edit - formatted date.
$dow = date("l"); // Do not edit - current day.
$cur_mon = date("m"); // Do not edit - current month.
$cur_year = date("Y"); // Do not edit - current year.
?>