<?php 
include "DBCmysql.php";
include "getcode.php";
include "menu.php";


//oauth2网页授权获取openid
	$code=$_GET['code'];//code 微信接口参数(必须)
	$state=$_GET['state'];//state微信接口参数(不需传参则不用)；若传参可考虑规则： 'act'.'arg1'.'add'.'arg2'
	$APPID='wx8c6780e16e443921';
	$SECRET='b21df0ecd1e8b3543eb233e214f284d3';
	$REDIRECT_URL='http://www.kwstar.com/weixin_wk/mydevice.php';//当前页面地址

	header("content-type:text/html;charset=utf-8");

	$oauth2=new oauth2();
	$oauth2->init($APPID, $SECRET,$REDIRECT_URL);
	if(empty($code)){		
		$oauth2->get_code($state);//获取code，会重定向到当前页。若需传参，使用$state变量传参。
	}
	$openid=$oauth2->get_openid();//获取openid


	//var_dump($openid);
	$dbmysql=new DB_mysql();
	$link=$dbmysql->connect();
	//echo '<hr/>';
	$sql="SELECT * FROM UserDevice where UserId='$openid'";//查询数据
	//获取数据
	$result=mysql_query($sql,$link);
	//统计记录
	if(mysql_query($sql,$link))                                                //判断$sql语句是否执行
	 {
	  $num=mysql_num_rows($result);
	//  echo "当前用户下的设备统计结果为".$num."条";
	}
	//echo '<hr/>';
	//组合信息
	$DateStr='{"Id":[';	
    while($row=mysql_fetch_row($result))
	{	 
	    $deviceId = $row[1];
	    //查询命令
		$url = "http://115.29.43.226:7300/search?id=".$deviceId;
		$Mhttp=new Menu();
		$res=$Mhttp->http_curl($url,'get','json');
		$rres=json_encode($res);
		//判断json数据状态是否正常
		if(strlen($rres)>50){
		//substr($str,int start[,int length]):从$str中strat位置开始提取[length长度的字符串]。
			// echo "当前设备".$deviceId."为合法设备！";
			// echo '<hr/>';
			$d1=substr($rres,1);
			//echo $mnum;
			//判断当前是否为最后一个设备
			$mnum=--$num;
			if($mnum!=0){
				//字符串连接方式
				$DateStr.='{"deviceId":'.$deviceId.','.$d1.',';
			}
			else{
				$DateStr.='{"deviceId":'.$deviceId.','.$d1;
			}
		}
		else{
			// echo "当前设备".$deviceId."为不合法设备！";
			// echo '<hr/>';
		}
	}
	$DateStr.=']}';
	//echo $DateStr;


	//1.请求url地址
	// $deviceID = 1000000001;
	// //查询命令
	// $url = "http://115.29.43.226:7300/search?id=".$deviceID;
	// $Mhttp=new Menu();
	// $res=$Mhttp->http_curl($url,'get','json');


	// //提取指定数值
	// echo '<hr/>';
	// echo $rres=json_encode($res);
	// //substr($str,int start[,int length]):从$str中strat位置开始提取[length长度的字符串]。
	// echo '<hr/>';
	// echo $d1=substr($rres,1);

	// echo '<hr/>';
	// echo $d2='{"deviceId":'.$deviceID.','.$d1;

	// echo '<hr/>';

// {"Id":[
// {"deviceId":100000001,"State":1,
//              "Node":[{"NUM":0,"TYPE":1,"S1":2,"S2":20,"S3":80,"S4":23},
//                      {"NUM":1,"TYPE":1,"S1":2,"S2":20,"S3":80,"S4":23}]
// }    ,
// {"deviceId":100000002,"State":1,
//              "Node":[{"NUM":0,"TYPE":1,"S1":2,"S2":20,"S3":80,"S4":23}]
// }
// ]
// }
 ?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>我的设备</title>
    <!-- <meta name="keywords" content="首页关键字"/>
    <meta name="description" content="首页描述"/> -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览； -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0"/>
    <!-- meta标签是iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览  -->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!--<link rel="apple-touch-icon-precomposed" sizes="48×48" href="/images/48×48.png"/>-->
    <!--<link rel="apple-touch-icon-precomposed" sizes="72×72" href="/images/72×72.png"/>-->
    <!--<link rel="apple-touch-icon-precomposed" sizes="114×114" href="images/114×114.png"/>-->

    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var ss = '<?php echo $DateStr;?>';
            var json = eval('('+ss+')');
            var str = "";
            for (var i = 0; i <= json['Id'].length - 1; i++) {
                
                str+="<div class='item'><button onclick='btnClick(this)' value='0'>"+json['Id'][i]['deviceId']+"<span><b>⟩</b></span></button><div class='ml'>";
                for(var j = 0; j<=json['Id'][i]['Node'].length-1; j++){
                    str += "<div><a href=''>"+json['Id'][i]['Node'][j]['NUM']+"</a>"
                }
                str += "</div></div></div>";
            }
            // $('#mm').hide('fast');
            $('#mm').append(str);//追加内容
            $('.item').children('.ml').slideUp();//选择子元素（下拉菜单）
            // $('#mm').show('fast');
        });
        function btnClick(Obj){
            $(Obj).parent().children('.ml').slideToggle("fast");
            var state = $(Obj).attr("value");
            if(state=="1"){
                $(Obj).attr("value","0");
                $(Obj).children('span').css("animation","rotation180 0.2s forwards");
                $(Obj).children('span').css("-ms-animation","rotation180 0.2s forwards");
                $(Obj).children('span').css("-moz-animation","rotation180 0.2s forwards");
                $(Obj).children('span').css("-webkit-animation","rotation180 0.2s forwards");
                $(Obj).children('span').css("-o-animation","rotation180 0.2s forwards");
            }else{
                $(Obj).attr("value","1");
                $(Obj).children('span').css("animation","rotation 0.2s forwards");
                $(Obj).children('span').css("-ms-animation","rotation 0.2s forwards");
                $(Obj).children('span').css("-moz-animation","rotation 0.2s forwards");
                $(Obj).children('span').css("-webkit-animation","rotation 0.2s forwards");
                $(Obj).children('span').css("-o-animation","rotation 0.2s forwards");
            }
        }
    </script>

    <style type="text/css">
        body{
            background-image: url("/images/main.png");
            background-repeat: no-repeat;
            background-size: 100%;
        }
        #mm{
            display: table;
            width: 100%;
            height: auto;
        }
        #mm .item .ml{
            display: table;
            margin-left: 5%;
            width: 90%;
        }
        #mm .item button{
            margin-left: 5%;
            width: 90%;
            height: 50px;
            font-size: 15px;
        }
        #mm .item .ml div{
            background-color: rgb(190, 190, 190);
            text-align: center;
            line-height: 50px;
        }
        #mm .item > button > span{
            display: inline-block;
            float: right;
            width: 10px;
            margin-top: 3px;
            transform:rotate(90deg);
        }

        @keyframes rotation180{
            0%   {transform:rotate(90deg);}
            100% {transform:rotate(270deg);}
        }
        @-ms-keyframes rotation180{  Firefox 
            0%   {transform:rotate(90deg);}
            100% {transform:rotate(270deg);}
        }
        @-moz-keyframes rotation180{
            0%   {transform:rotate(90deg);}
            100% {transform:rotate(270deg);}
        }
        @-webkit-keyframes rotation180{
            0%   {transform:rotate(90deg);}
            100% {transform:rotate(270deg);}
        }
        @-o-keyframes rotation180{
            0%   {transform:rotate(90deg);}
            100% {transform:rotate(270deg);}
        }
        /* 回来的动画*/
        @keyframes rotation{
            0%   {transform:rotate(270deg);}
            100% {transform:rotate(90deg);}
        }
        @-ms-keyframes rotation{  Firefox 
            0%   {transform:rotate(270deg);}
            100% {transform:rotate(90deg);}
        }
        @-moz-keyframes rotation{
            0%   {transform:rotate(270deg);}
            100% {transform:rotate(90deg);}
        }
        @-webkit-keyframes rotation{
            0%   {transform:rotate(270deg);}
            100% {transform:rotate(90deg);}
        }
        @-o-keyframes rotation{
            0%   {transform:rotate(270deg);}
            100% {transform:rotate(90deg);}
        }
    </style>
    
</head>
<body>
    <div id="mm">

    </div>
</body>
</html>