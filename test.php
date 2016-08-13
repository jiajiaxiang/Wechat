<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>My device</title>
<script src="jquery-1.7.2.js" type="text/javascript"></script>
<script>
	//删除功能
	function bdeclick() {
		$r = confirm("是否确认删除");
		if($r == true) {
			$thiss = $(this).parent().parent();
			$deviceid = $thiss.children().eq(0).text();
			$.post("DataDelete.php", {full : ['1000000001']	}, function(msg) {	$thiss.remove();	})			
		}
	};

</script>
<style>
	td {
	width: 100px;
	}
	input, option, select {
	width: 95px;
	}
	.bch, .bde {
	width: 50px;
	}
	.bsave, .bnew {
	width: 100px;
	}
	.add {
	border: 1px red solid
	}
	.display1 {
	display: none
	}
</style>
</head>
	<body>
	<div id="test"></div>
	<table border="1" id="tab">
		<tr>
		<td>设备号</td>
		<td>操作</td>
		</tr>
		<!--数据库遍历-->
		<?php
			include "DBCmysql.php";
			$dbmysql=new DB_mysql();
			$link=$dbmysql->connect();

			$openid=oB4x3s7xI3kOlTlk1Z4kaRz1_rSI;
			$sql = "select * from UserDevice WHERE UserId='$openid'";
			$result = mysql_query ( $sql );
			while ( ! ! $row = mysql_fetch_array ( $result ) ) {
			echo "<tr class='trc'><td>$row[1]</td><td><input type='button' value='删除' onclick='bdeclick()'/></td></tr>";
			}
			mysql_close ( $conn );
		?>
	</table>

	<form action="http://www.kwstar.com/weixin_wk/DataDeal.php" method="post">
		 <input type='button' value='添加' oonClick="window.location.reload('adddevice.php')"/>
	</form>

</body>
</html>