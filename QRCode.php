<?php
include "menu.php";

//获取access_token
$geactoken=new Menu();
$access_token=$geactoken->getWxAccessToken();

$expire_seconds=1800;
$action_name="QR_SCENE";
$scene_id=1;

//调用函数创建二维码ticket
$result=creat_ticket($access_token,$expire_seconds,$action_name,$scene_id);
if(!empty($result)){
	echo show_qcode($result);
}else{
	echo "创建二维码的ticket失败！";
}

//创建二维码ticket
 	function creat_ticket($access_token,$expire_seconds,$action_name,$scene_id){
		//获取tikect票据
		//创建二维码ticket的接口地址
		$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
//{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		if(strtoupper($action_name)=="QR_SCENE")
		{
			//检查临时二维码最长有效时间
			if($expire_seconds>1800)
				$expire_seconds=1800;
			$postArr=array(
				'expire_seconds'=>$expire_seconds,
				'action_name'=>"QR_SCENE",
				'action_info'=>array(
					'scene'=>array(
						'scene_id'=>$scene_id),
					),
				);
		}
		//永久二维码
		elseif (strtoupper($action_name)=="QR_LIMIT_SCENE") {
			$postArr=array(
				'action_name'=>"QR_LIMIT_SCENE",
				'action_info'=>array(
					'scene'=>array(
						'scene_id'=>$scene_id),
					),
				);
		}
		else{
			echo "二维码类型只能为QR_SCENE或QR_LIMIT_SCENE！";
			return "";
		}
			$geactoken=new Menu();
			$postjson=json_encode($postArr);
			$res=$geactoken->http_curl($url,'post','json',$postjson);
			var_dump( $res );
			echo '<hr/>';
			echo  $ticket=$res['ticket'];
			echo '<hr/>';
			if(empty($res->errcode))
			{
				return $res['ticket'];
			}
			else{
				return "";
			}		
	 }

	//使用ticket获取二维码图片
	function show_qcode($ticket){
			echo $ticket=urlencode($ticket);
			echo '<hr/>';
			$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			//直接以图片方式展示
			echo "<img src='".$url."'/>";
		}

?>