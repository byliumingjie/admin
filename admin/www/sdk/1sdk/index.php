<?php 
	ini_set('display_errors', '1');
	include_once '../../include/lib/log.php';
	include_once "../../include/lib/mysql.lib.php";
	include_once "core.function.php";
	// 秘钥
	$TokenKey = '3VEIDXI7AGEEQQ7N79ROU9FXU14W9P92';
	
	$dataOut = dataVerify($_GET); 
	
	$signStr =  'app='.$dataOut['app'].'&cbi='.$dataOut['cbi'].'&ct='.$dataOut['ct'].
	'&fee='.$dataOut['fee'].'&pt='.$dataOut['pt'].'&sdk='.$dataOut['sdk'].'&ssid='.$dataOut['ssid'].'&st='.
	$dataOut['st'].'&tcd='.$dataOut['tcd'].'&uid='.$dataOut['uid'].'&ver='.$dataOut['ver'];
	
	$signEncrypt = md5($signStr.$TokenKey); 

	if($signEncrypt == $dataOut['sign'])
	{
		$db = new  Mysqldb();

		 $cbiOut = [];
		   $data = [];
		$Inlogdb = [];

		$cbiOut = explode(',',$dataOut['cbi']); 
		$platId = (int)$cbiOut[0];
		$server_id = (int)$cbiOut[1];
		$RoleIndex = $cbiOut[2];
		$cbi = $cbiOut[3];
		
		$data = [
		'platId'=>$platId,	
		'server_id'=>$server_id,
		'RoleIndex'=>$RoleIndex,
		'cbi'=>$cbi,
		'uid'=>$dataOut['uid'],
		'fee'=>$dataOut['fee'],
		'ssid'=>$dataOut['ssid'],
		'tcd'=>trim($dataOut['tcd']),
		'ssid'=>$dataOut['fee']
		];
		
		_log('data json info:'.json_encode($data),'1sdk-log');
		
		$Inlogdb = $data +  ['sign'=>$signEncrypt,
		'signlist'=>json_encode($dataOut),'status'=>1,'createtime'=>date('Y-m-d H:i:s',time())];
		
		$setLogRet = setPayLog($Inlogdb,$db);
		
		_log('setLogRet1'.($setLogRet),'1sdk-log');

		_log('setLogRet2'.json_encode($setLogRet),'1sdk-log');

		if($setLogRet)
		{  
		    $sendret = send($data,$db,'发送成功!','发送失败','SaveMoneryInform');
			 
			_log(' ret info '.$sendret,'1sdk-log');
	
			if($sendret==true)
			{ 
				// 变更订单状态成功
				editPayLog(2,$data['tcd'],$db,'set pay log Error:line:1!');
				//
				_log(' 通讯成功！返回SUCCESS','1sdk-log');
				exit("SUCCESS");
			}
			// 变更订单状态失败
			editPayLog(3,$data['tcd'],$db,'set pay log Error:line:2');
			_log(' 秘钥验证成功!通讯失败: FAILURE','1sdk-log');
			exit('FAILURE:Error:line:1');
		}
		_log(' log db set false:'.json_encode($Inlogdb),'1sdk-log');
		exit('FAILURE:Error:line:2');
	}	 
	_log(' 秘钥对比失败! FAILURE'.$signEncrypt.' #|# sign'.$dataOut['sign'],'1sdk-log');
	exit('FAILURE:Error:line:1');