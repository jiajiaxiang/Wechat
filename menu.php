<?php

class Menu{

	/*万能的curl请求
	$url 接口url string
	$type 请求类型string
	$res 返回数据类型string
	$arr  post请求参数string
	*/
	public function http_curl($url,$type='get',$res='json',$arr=''){
		//1.初始化curl
		$ch = curl_init();
		//2.设置curl的参数
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($type=='post'){
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
		}
		//3.采集
		$output = curl_exec($ch);
		//4.关闭
//		curl_close($ch);
		if($res=='json'){
			if(curl_error($ch)){
				return curl_error($ch);
			}
			else{
				return json_decode($output,true);
			}			
		}
	}

	function getWxAccessToken(){


//本地文件缓存，使其长期保存（保存地址，SAE服务器）
		// $tokenFile="saestor://try/access_token.txt";//缓存文件名
		// $data=json_decode(file_get_contents($tokenFile));
		// if($data->expire_time<time() or !$data->expire_time){
		// 	$appid = 'wx8c6780e16e443921';
		// $appsecret =  'b21df0ecd1e8b3543eb233e214f284d3';
		// $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
		// $res=$this->http_curl($url,'get','json');
		// $access_token=$res['access_token'];
		// if($access_token){
		// 	echo $access_token;
		// 	$data1['expire_time']=time()+7000;
		// 	$data1['access_token']=$access_token;
		// 	$fp=fopen($tokenFile,"w");
		// 	fwrite($fp,json_encode($data1));
		// 	fclose($fp);
		// }
		// }
		// else{
		// 	$access_token=$data->access_token;
		// }
		// return $access_token;

		//将access_token 存在session/cookie中
		if($_SESSTION['access_token'] && $_SESSTION['expire_time']>time()){
			//如果access_token在session/cookie没有过期
			echo $_SESSTION['access_token'];
			return $_SESSTION['access_token'];
		}else{

			//1.请求url地址
		$appid = 'wx8c6780e16e443921';
		$appsecret =  'b21df0ecd1e8b3543eb233e214f284d3';
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
		$res=$this->http_curl($url,'get','json');
		$access_token=$res['access_token'];

		$_SESSTION['access_token']=$access_token;
		if(!isset($_SESSTION['expire_time'])||(time()-$_SESSTION['expire_time'])>60){
			$_SESSTION['expire_time']=time()+7000;
		}
	//	echo $access_token;
		return $access_token;
		}
		}

	public function defineditem(){

		header('content-type:text/html;charset=utf-8');
		echo $access_token=$this->getWxAccessToken();

		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
		$postArr=array(
			'button'=>array(
			array(
				'type'=>'view',
				'name'=>urlencode('我的设备'
					),
				'url'=>'http://www.kwstar.com/weixin_wk/mydevice.php',
			),//第一个一级菜单
			array(
				'type'=>'view',
				'name'=>urlencode('购买设备'
					),
				'url'=>'http://www.kwstar.com/weixin_wk/index.html',
			),//第二个一级菜单
			array(
				"name"=>urlencode("个人中心"),
				'sub_button'=>array(
					array(
						'name'=>urlencode('使用帮助'),
						'type'=>'click',
						'key'=>'problem',
						),
					array(
						'name'=>urlencode('添加设备'),
						'type'=>'view',
						'url'=>'http://www.kwstar.com/weixin_wk/test.php'
						),
					array(
						'name'=>urlencode('设备联网'),
						'type'=>'view',
						'url'=>'http://www.kwstar.com/weixin_wk/test.php'
						),
					array(
						'name'=>urlencode('常见问题'),
						'type'=>'view',
						'url'=>'http://www.baidu.com'
						),
				),//第三个一级菜单
			),
			),
			);
		echo '<hr/>';
		var_dump($postArr);
		echo '<hr/>';
		echo $posrJson=urldecode(json_encode($postArr));
		$res=$this->http_curl($url,'post','json',$posrJson);
		echo '<hr/>';
		var_dump( $res );
	}

	public function sendmsgAll(){
		//1	获取access_token

		echo $access_token=$this->getWxAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$access_token;
		//2 组装群发接口数据array
// 		{     
//     "touser":"OPENID",
//     "text":{           
//            "content":"CONTENT"            
//            },     
//     "msgtype":"text"
// }
		$array=array(
			'touser'=>'oB4x3s7xI3kOlTlk1Z4kaRz1_rSI',
			'text'=>array('content'=>'ok'),
			'msgtype'=>'text'
			);
		echo '<hr/>';
		var_dump($array);
		echo '<hr/>';
		//3 将aaray->json
		echo $posrJson=urldecode(json_encode($array));
		//4调用url	
		$res=$this->http_curl($url,'post','json',$posrJson);
		echo '<hr/>';
		var_dump($res);
	}
}

?>