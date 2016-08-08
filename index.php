<?php
header('content-type:text/html;charset=utf-8');
include "base.php";
include "menu.php";
include "DBCmysql.php";
include "getcode.php";

	$baseObj=new Base();
	$menu=new Menu();
	$dbmysql=new DB_mysql();

	//自定义菜单
	$menu->defineditem();
	//群发消息
	//$menu->sendmsgAll();

	if(isset($_GET['echostr'])){

		$baseObj->sourceCheck();
	}
	else{
		$baseObj->responseM();
	}

	$link=$dbmysql->connect();
	$sql="SELECT * FROM UserDevice";//查询数据
	$rows=$dbmysql->fetchAll($sql);
	var_dump($rows);
	echo '<hr/>';	
	


?>
