<?php
include "DBCmysql.php";


function Deal(){
		$openid=$_POST["uesropenid"];
		$dvId=$_POST["dvname"];
		//1000000000--4294967295
		if($dvId<4294967295&&$dvId>1000000000){
			$dbmysql=new DB_mysql();
			$link=$dbmysql->connect();
			$dbmysql->insert($openid,$dvId);
			echo "<script>alert('添加成功，在我的设备中查看！');location.href='http://www.kwstar.com/weixin_wk/adddevice.php'</script>"; 
			}
			else{
				echo "<script>alert('不合法的设备！请重新输入');location.href='http://www.kwstar.com/weixin_wk/adddevice.php'</script>"; 
			}
		}

	if(!empty($_POST)){
		if(empty($_POST["dvname"])){
			echo "<script>alert('设备号不能为空');location.href='http://www.kwstar.com/weixin_wk/adddevice.php'</script>"; 
		}
		else{
			Deal();
		}

	}else{
		echo "<script>alert('信息为空');location.href='http://www.kwstar.com/weixin_wk/adddevice.php'</script>"; 
		}

	
?>

