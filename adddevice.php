<?php
include "DBCmysql.php";
include "getcode.php";

//oauth2网页授权获取openid
	$code=$_GET['code'];//code 微信接口参数(必须)
	$state=$_GET['state'];//state微信接口参数(不需传参则不用)；若传参可考虑规则： 'act'.'arg1'.'add'.'arg2'
	$APPID='wx8c6780e16e443921';
	$SECRET='b21df0ecd1e8b3543eb233e214f284d3';
	$REDIRECT_URL='http://www.kwstar.com/weixin_wk/adddevice.php';//当前页面地址

	header("content-type:text/html;charset=utf-8");

	$oauth2=new oauth2();
	$oauth2->init($APPID, $SECRET,$REDIRECT_URL);
	if(empty($code)){		
		$oauth2->get_code($state);//获取code，会重定向到当前页。若需传参，使用$state变量传参。
	}
	$openid=$oauth2->get_openid();//获取openid


?>


<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	 <!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览； -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0"/>
    <!-- meta标签是iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览  -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
	<title>添加设备</title>

	 <script type="text/javascript">
        window.onload = function(){
            setTimeout(scrollTo,0,0,0);
        }
    </script>

<style> 
	 	#m{
            display: table;
            width: 100%;
            height: 100%;
        }
        #m > .add{
            display: table-cell;
            text-align: center;
        }

		.btns { width:60px; height:40px; no-repeat left top;background-color:#3758e3;color:#FFF;border-radius:5px 
		} 
		.ys1{font-size: 20px;
		}
</style>
</head>
<body >
	<div id="m">
        <div class="add">
			<form action="http://www.kwstar.com/weixin_wk/DataDeal.php" method="post">

			<p align="left" class="ys1"> 请输入网关号</p>
			 <input type="hidden" name="uesropenid" value="<?php echo $openid ?>"/>
			 <input type="text" name="dvname" style="width:70%; height:40px;border-radius:3px;font-size: 30px;"/>
			 <input type="submit" class="btns" name="insert" onmouseover="this.style.background Position='left -40px'"
			 onmouseout="this.style.background Position='left top'" value="绑定" style="font-size:20px"/>
			</form>
 		</div>
    </div>


</body>
</html>
