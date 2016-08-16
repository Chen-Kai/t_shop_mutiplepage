<?php
//ob_start();
//session_start();

$host = "192.168.1.235";
$user = "root";
$pwd = "2xdialxl";

$link = mysql_connect($host,$user,$pwd);
mysql_query("SET NAMES 'utf8'");
mysql_select_db("shop");

?>
