<?php 
	ini_set('display_errors', '1');
	 
	include_once '../../include/lib/log.php';
	include_once "../../include/lib/mysql.lib.php";
	include_once "core.function.php";
	// 秘钥
	$TokenKey = 'E0369064274BD63E4264534B5AB89B9B';
	
	$dataOut = dataVerify($_POST); 
	
	$platformID = $dataOut['platformID'];

	$serverID = $dataOut['serverID'];

	$roleIndex = $dataOut['roleIndex'];

	$account = $dataOut['account'];

	$fee = $dataOut['fee']; // 费用
	
	$cbi = $dataOut['cbi']; // 档位

	$transID = $dataOut['transID'];

	$transReceipt = str_replace(' ','+',$dataOut['transReceipt']);	

	$sign = $dataOut['sign'];

	$signStr = $platformID.$serverID.$roleIndex.$account.$fee.$cbi.$transID.$transReceipt; 

	$signEncrypt = md5($signStr.$TokenKey); 

	if($signEncrypt == $sign)
	{		
		_log('验证通过','ios-sdk');

		$url = 'https://sandbox.itunes.apple.com/verifyReceipt';

		$jsonTransReceipt = ['receipt-data'=>$transReceipt];

		$iosRet = send_request($url,json_encode($jsonTransReceipt),'utf-8');
		
		$InPayData = json_decode($iosRet,true); 

		if((int)$InPayData['status']==0)
		{
			$db = new  Mysqldb();
			
			$cbi = $InPayData['in_app']['product_id']; // 档位 对应价格
			$ssid =  $InPayData['in_app']['transaction_id']; // 流水号
			$tcd = $InPayData['in_app']['original_transaction_id']; // 订单号
			//verifycbi();
			// 档位验证
			$data = [
			'platId'=>$platformID,	
			'server_id'=>$serverID,
			'RoleIndex'=>$roleIndex,
			'cbi'=>$cbi,
			'uid'=>$account,
			'fee'=>$dataOut['fee'],
			'ssid'=>$ssid,
			'tcd'=>trim($tcd),
			'sign'=>$dataOut['sign'],
			'signlist'=>json_encode($dataOut),
			'status'=>1,
			'createtime'=>date('Y-m-d H:i:s',time())
			];

			$setLogRet = setPayLog($Inlogdb,$db);

			$cbiRet = verifycbi((int)$data['cbi'],(int)$data['fee']);
			
			if ($cbiRet)
			{
				if($setLogRet)
				{ 
					$sendret = send($data,$db,'发送成功!','发送失败','SaveMoneryInform');
					
					_log(' ret info '.$sendret,'1sdk-log');
					
					if($sendret==true)
					{
						// 变更订单状态成功
						editPayLog(2,$data['tcd'],$db,'set pay log Error:line:1!');						 
						exit('{"status":0,"result":"支付成功!"}');
					}
					// 变更订单状态失败
					editPayLog(3,$data['tcd'],$db,'set pay log Error:line:2');					 
					exit('{"status":1201,"result":"秘钥验证成功!与服务端通讯失败!"}');					 
				} 
				_log('iosRet::1202'.$iosRet,'ios-sdk');
				exit('{"status":1202,"result":"日志记录失败!"}');
			} 
			_log('iosRet::1203'.$iosRet,'ios-sdk');
			exit('{"status":1203,"result":"档位验证失败!"}');
		}  
		_log('iosRet::'.$iosRet,'ios-sdk');
		exit($iosRet);
	}
	exit('{"status":1205,"result":"sign验证失败!"}');
