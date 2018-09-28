<?php
include_once '../xxtea.lib.php';
include_once "../include/lib/log.php";
include_once "../include/lib/mysql.lib.php";
include_once "core.account.function.php";

_log("datainfo::: username".$_POST['username']."PlayerId:::".$_POST['PlayerId']."serverId".$_POST['serverId'],'wechat.log');

if(!empty($_POST['username']) && !empty($_POST['PlayerId']) 
&&  !empty($_POST['serverId']))
{
	$type = $_POST['serverId'];
	$playerId = $_POST['PlayerId'];
	$username = $_POST['username'];
	// 首先根据区服ID获取对于的平台db配置
	$outdbKey = get_platform_cfg($type);
	if ($outdbKey)
	{
		// 获取玩家的日志db库地址后，检索看是否能够匹配的玩家如果玩家是注册表示成功继续
		$playerVerify = player_verify($outdbKey,$type,$playerId);		
		if(!$playerVerify)
		{
		 exit('{"errcode": -1,"msg": "角色编号有误,无法识别角色编号,请重试！"}');
		}	
		//$roleInfo = get_player_info($outdbKey,$type,$playerId);
		
	}else{ 
		exit('{"errcode": -1,"msg": "Database execution failed!"}'); 
	}	
	$ciphertext = new xxTea();
	$wechat_name =  $username;
	// 
	$retbind = wechat_account_bind($playerId, $wechat_name); 

	if ($retbind){		
		// 如果玩家已经绑定了账号返回礼包码 ---或者请求一个邮箱领取包发送服务器端
		
	}
	else{ 
		exit('{"errcode": -1,"msg": "您的账号没有绑定请绑定之后再来重试!"}');
		return false;
	}
} 
else{ 
	//_log("账号为空！",'wechat.log');
	ECHO '{"errcode": -1,"msg": "账号为空绑定失败!"}';
	exit();
}
?>