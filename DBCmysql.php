<?php
define ('DB_HOST','127.0.0.1');
define ('DB_USER','root');
define ('DB_PSW','12345678');
define ('DB_CHAREST','UTF8');
define ('DB_DBNAME','test_db');

class DB_mysql{

public function connect (){

	$link=mysql_connect(DB_HOST,DB_USER,DB_PSW); //连接Mysql服务器
	if (!$link)
	{
		  die('Could not connect: ' . mysql_error());
	}//连接错误
//设置字符集
	mysql_set_charset(DB_CHAREST);

	$db_selected=mysql_select_db(DB_DBNAME,$link);
	if (!$db_selected)
	{//选择数据库test_db
		die("Can't use test_db:".mysql_error());//选择错误
    }
	return $link;
}
//查询所有
function fetchAll($sql){
$result=mysql_query($sql);//执行查询，并保存在变量result中
	echo '<hr/>';
	if($result && mysql_num_rows($result)>0){
	while ($row=mysql_fetch_row($result)){
		// for($i=0;$i<count($row);$i++){
		// 	echo $row[$i];
		// 	echo "\n\n";
		// }
		$rows[]=$row;		
		}
		return $rows;
	}
	else{
		return false;
	}
}
/**
 * 查询满足条件的记录
 * @param string $sql
 * @param string $result_type
 * @return boolean
 */
function fetchData($sql,$link){
    $result=mysql_query($sql,$link);
    while($row=mysql_fetch_row($result))
	{
	  echo "UserId：".$row[0];
	  echo "DeviceID：".$row[1];
	  echo "</br>";
	}
}


function insert($userId,$dvId){

	$sql="insert into UserDevice(UserId,DeviceId) values('$userId','$dvId')";
	$result = mysql_query($sql)or die("数据库连接错误");
}

function delete($userId,$dvId){
	$sql = "delete from serDevice(UserId,DeviceId) values('$userId','$dvId')";
	mysql_query($sql)or die("数据库连接错误");
 
	$mark  = mysql_affected_rows();//返回影响行数
	 
	if($mark>0){
	 echo "删除成功";
	}else{
	 echo  "删除失败";
	}
}

function closemysql ($link=null){
	return mysql_close($link);//关闭数据库
}

}

?>