<?php
include_once "wechat.class.php";
include_once 'include/lib/log.php';
include_once 'xxtea.lib.php';
//include 'core.function.php';
include "wechat.push.php";
/* $options = array(
		'token'=>'tokenaccesskey', //填写你设定的key
        'encodingaeskey'=>'encodingaeskey' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
	); */
$options = array(
		'token'=>'minosweixintoken', //填写你设定的key
		'encodingaeskey'=>'cqGF8EpSgatd3feHMiKOtmqsgHuB6BQvWNpZITRY39y', //填写加密用的EncodingAESKey
		'appid'=>'wxede1e5a9fb3ec78b', //填写高级调用功能的app id
		'appsecret'=>'43081edafeb5da5a5786e0c21eef227a' //填写高级调用功能的密钥
);
$postStr = file_get_contents("php://input");
_log("post data".$postStr,'wecatlog');

//$menudata = create_menu_list();
 
$weObj = new Wechat($options);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败

/*$ret = $weObj->createMenu($menudata);
if($ret){
	
	_log("create menu success".$ret,'wecatlog');
}
else{
	_log("create menu false".$ret,'wecatlog');
}
*/
$type = $weObj->getRev()->getRevType();
$ciphertext = new   xxTea();
// OpentId
$OpenId = $weObj->getRev()->getRevFrom(); 

switch($type) {
	case Wechat::MSGTYPE_TEXT:	

			// 获取信息内容
			$RevContent = $weObj->getRev()->getRevContent();			 	 
			// 问题反馈
			$pushRet = account_tool_verify($OpenId,2,array('Text'=>$RevContent));
			
			if($pushRet)
			{				
				$weObj->text("$pushRet")->reply();
				break;
			}
			$weObj->text("欢迎来到米喏斯(上海)网络有限公司")->reply();
			break;
	case Wechat::MSGTYPE_EVENT: 
			$eventOut = $weObj->getRev()->getRevEvent();
			if($eventOut['event']=='CLICK')
			{	  
				// 账号绑定
				if( $eventOut['key']=='rselfmenu_0_2' )
				{	
					// username || is OpenId
					$userName = $weObj->getRev()->getRevFrom();
					 
					if($userName)
					{
						$username = rawurlencode(xxTea::encrypt($userName));
					}
					$a = "<a href='http://m.minosgame.com/wechat/account/index.php?userName={$username}'>【点我绑定】</a>";
					$weObj->text("绑定之后才能领取活动奖励资格哦~ ".$a)->reply();
					break;
				}
				
				// 最新活动
				if( $eventOut['key']=='rselfmenu_2_0' )
				{	
					$NewActivity = set_wechat_new_material('NewActivity');
					$weObj->news($NewActivity)->reply(); 
					break;
					break;
				}
				// 活动礼包
				if( $eventOut['key']=='rselfmenu_2_1' )
				{
					// username || is OpenId
					$userName = $weObj->getRev()->getRevFrom();
				
					/*if($userName)
					{
						$username = rawurlencode(xxTea::encrypt($userName));
					}
					$a = "<a href='http://m.minosgame.com/wechat/account/award.php?userName={$username}'>【点我去领取礼包】</a>";*/
					$cdk = set_wechat_new_material('ActivityGift');
					$weObj->text("恭喜您获得礼包码一个,请速速拿去兑换吧~ :".$cdk)->reply();
					break;
				}
				// 宣传视频
				if( $eventOut['key']=='rselfmenu_2_2' )
				{ 
					$PromoteVideo = set_wechat_new_material('PromoteVideo');
					$weObj->news($PromoteVideo)->reply(); 
					break;
				}
				// 表情包
				if( $eventOut['key']=='rselfmenu_3_0' )
				{ 
					$Emoji = set_wechat_new_material('Emoji');
					$weObj->news($Emoji)->reply(); 
					break;
				}
				// 问题反馈 
				if( $eventOut['key']=='rselfmenu_3_3' )
				{	
					if(set_wechat_user_activity($OpenId,NULL,array(),1,2))
					{
						$weObj->text("请问您有什么需要反馈的呢？")->reply();
					}
					break;
				}				
				// 宣传视频
				if( $eventOut['key']=='rselfmenu_3_2' )
				{ 
					$PromoteVideo = set_wechat_new_material('QQgroup');
					$weObj->news($PromoteVideo)->reply(); 
					break;
				}
				$weObj->text("敬请期待")->reply();
				break; 
			}  
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;	 
	default:
			$weObj->text("help info")->reply();
}