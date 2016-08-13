<?php

	include 'DBCmysql.php';

	$arr = $_POST ['full'];
	
	$dbmysql=new DB_mysql();
	$link=$dbmysql->connect();
	$sql = "delete from UserDevice where DeviceId=$arr[0]";
	$result = mysql_query ( $sql );
	break;
	
?>

