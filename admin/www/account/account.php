<?php
 
include_once '../xxtea.lib.php';
include_once "../include/lib/log.php";
include_once "../include/lib/mysql.lib.php";
include_once "core.account.function.php";

_log("datainfo::: username".$_POST['username']."PlayerId:::".$_POST['PlayerId']."serverId".$_POST['serverId'],'wechat.log');

if(!empty($_POST['username']) && !empty($_POST['PlayerId']) 
&&  !empty($_POST['serverId']))
{
	$ciphertext = new xxTea();	

	$type = $_POST['serverId'];
	$playerId = $_POST['PlayerId'];
	$username = $ciphertext->decrypt($_POST['username']);
	
	_log("datainfo:::00000",'wechat.log');

	$outdbKey = get_platform_cfg($type);
	_log("datainfo:::111111",'wechat.log');
	if ($outdbKey)
	{
		_log("datainfo:::22222".json_encode($outdbKey),'wechat.log');
		$playerVerify = player_verify($outdbKey,$type,$playerId);
		
		if(!$playerVerify)
		{
		_log("datainfo:::33333333",'wechat.log');
		 exit('{"errcode": -1,"msg": "角色编号有误,无法识别角色编号,请重试！"}');
		}
		
	}else{ 
		_log("datainfo:::44444",'wechat.log');
		exit('{"errcode": -1,"msg": "Database execution failed!"}'); 
	}
	
	_log("datainfo:::55555",'wechat.log');

	_log("datainfo:::66666",'wechat.log');
	//$wechat_name = xxTea::decrypt($username); 
	$wechat_name =  $username;
	//echo $username."<br>";
	_log("datainfo:::77777",'wechat.log');
	//echo 'wechat_name'.$wechat_name;
	$retbind = wechat_account_bind($playerId, $wechat_name); 
	_log("datainfo:::88888",'wechat.log');
	echo $retbind;
	} 
else{ 
	//_log("账号为空！",'wechat.log');
	ECHO '{"errcode": -1,"msg": "账号为空绑定失败!"}';
	exit();
}
?>