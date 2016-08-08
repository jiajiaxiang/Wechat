<?php

Class oauth2{

	public $REDIRECT_URL="";
 	public $APPID="";
 	public $SECRET="";
 	
 	public $Code="";
 	public $State="";
 	public $Access_token="";
 	
 	public $Openid="";
 	
 	function __construct(){		
 		//默认使用的appid
 		$this->APPID='';
 		$this->SECRET='';		
 	} 	
    
 	/**
 	 * 初始化参数。(包括微信接口参数$code、$state)
 	 * @param string $APPID
 	 * @param string $SECRET
 	 * @param string $REDIRECT_URL
 	 */
 	function init($APPID,$SECRET,$REDIRECT_URL){
 		$this->REDIRECT_URL=$REDIRECT_URL;
 		$this->APPID=$APPID;
 		$this->SECRET=$SECRET;
 		
 		$this->Code=$_GET['code'];//code
 		$this->State=$_GET['state'];//state参数
 	}
 	/**
 	 * 获取Code
 	 * (传递state参数)
 	 */
	function get_code($state='1'){

		$APPID=$this->APPID;
 		$redirect_uri=$this->REDIRECT_URL;
		//1.获取code
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$APPID."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
		header("Location: $url");//重定向请求微信用户信息
	
	}
	/**
 	 * 获取用户openid
 	 * @param string $redirect_uri
 	 * @param string $state 传参
 	 */
	function get_openid(){
		$APPID=$this->APPID;
 		$SECRET=$this->SECRET;
 		$code=$this->Code;

		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$APPID."&secret=".$SECRET."&code=".$code."&grant_type=authorization_code";//通过code换取网页授权access_token
		$str=file_get_contents($url);
		$arr=json_decode($str,true);
		$this->Openid=$zrr['openid'];
		return $arr['openid'];
	}
}


?>