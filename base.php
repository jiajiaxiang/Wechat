<?php

include "indexModel.php";
define ("TOKEN","weixin");

	class Base{
		//检查签名
	public function sourceCheck(){
		if(checkSignature()){
			$echostr = $_GET["echostr"];
			echo $echostr;
			exit();
		}
		else{
			throw new Exception("Error Processing Request", 1);	
			exit();			
		}			
	}
	
	public function responseM(){
	//1.获取到微信推送过来post数据（xml格式）
	$postArr=$GLOBALS['HTTP_RAW_POST_DATA'];
	//2.处理消息，并设置类型和内容
echo '<hr/>';
	 $postObj=simplexml_load_string($postArr);;
	
	 //判断该数据包是否是订阅事件推送
	 if(strtolower($postObj->MsgType)=='event'){
//如果是关注事
	 	if(strtolower($postObj->Event=='subscribe')){
	 		//回复用户消息
	 		//获取OpenID以及原始id
	 	///	$Content="欢迎关注我们的微信公众账号"."微信用户openid:\n".$postObj->FromUserName."\n原始id:\n".$postObj->ToUserName;
	 	

			$content="欢迎关注我们的微信公众账号"."微信用户openid:\n".$postObj->FromUserName."\n原始id:\n".$postObj->ToUserName."\n可回复数字1-3";

			//图文回复
			// $arr = array(//多维数组
			// 		array(
			// 			'title'=>'imooc',
			// 			'description'=>"欢迎关注我们的微信公众账号",
			// 			'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
			// 			'url'=>'http://www.imooc.com',
			// 		)
			// 		);
			$indexmodel= new IndexModel();
			$indexmodel->responseSubscribe($postObj,$content);
	 	}
	 }

	  if(strtolower($postObj->Event)=='click'){
//如果是自定义菜单中点击事件
	 	if(strtolower($postObj->EventKey=='problem')){

	 		$arr = array(//多维数组
					array(
						'title'=>'多联机微控使用说明书',
						'description'=>"多联机微控使用说明书",
						'picUrl'=>'http://www.kwstar.com/pic/weikong.png',
						'url'=>'http://www.hao123.com',
					),
				);
		$indexmodel= new IndexModel();
		$indexmodel->responseNews($postObj,$arr);	
	 	}
	 }

//用户发送关键字，回复单图文+多图文
	if(strtolower($postObj->MsgType) == 'text' && trim($postObj->Content)=='tuwen2'){
				$arr = array(//多维数组
					array(
						'title'=>'hao123',
						'description'=>"hao123 is very cool",
						'picUrl'=>'https://www.baidu.com/img/bdlogo.png',
						'url'=>'http://www.hao123.com',
					),
					array(
						'title'=>'qq',
						'description'=>"qq is very cool",
						'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
						'url'=>'http://www.qq.com',
					),
				);
		//实例化模型
		$indexmodel= new IndexModel();
		$indexmodel->responseNews($postObj,$arr);
}
	else{
			switch(trim($postObj->Content)){
				case 1:
					$content = '您输入的数字是1';
				break;
				case 2:
					$content = '您输入的数字是2';
				break;
				case 3:
					$content = "<a href='http://www.baidu.com'>百度</a>";
				break;
				default:
					$content = "没有找到相关信息！请重新输入！";
				break;
			}	
			$indexmodel= new IndexModel();
			$indexmodel->responseText($postObj,$content);
	}
}


	private function checkSignature(){
		//获取参数值
	$signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];	
    $token=TOKEN;
        		
	//按照字典序排序将三个参数排序
	$tmpArr = array(token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	//先将数组拼接成字符串再sha1加密
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	$echostr = $_GET["echostr"];
	//判断获得的签名是否与本地计算的相同
	if( $tmpStr == $signature && $echostr){
		//第一次接入微信 api的时候
		return true;
	}else{
		return false;
	}
	}	
	
}
?>
