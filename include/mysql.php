<?php 

$mysql_host = "localhost";
$mysql_database = "fanfiction";
$mysql_user = "fanfiction";
$mysql_password = "############";

mysql_connect ("$mysql_host", "$mysql_user", "$mysql_password") or 
	die ("Nie można połączyć się z MySQL");
mysql_select_db ("$mysql_database") or 
	die ("Nie można połączyć się z bazą danych");
mysql_query("SET NAMES 'utf8'");
?> 