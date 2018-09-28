<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

	include_once '../xxtea.lib.php';
	include_once "../include/lib/log.php";
	include_once "core.account.function.php";
	
	_log("datainfo::: username".$_POST['username']."PlayerId:::"
	.$_POST['PlayerId']."serverId".$_POST['serverId'],'wechat.log');
	_log('00','wechat');
	if(!empty($_POST['username']) && !empty($_POST['PlayerId']) 
	&&  !empty($_POST['serverId']))
	{	 
		$ciphertext = new xxTea();
		_log('01','wechat');
		//$ciphertext->decrypt($_POST['username']),
		$Indata = [
		'activityType'=>1,
		'server_id'=>$_POST['serverId'],
		'player_id'=>$_POST['PlayerId'],
		'wechat_name'=>$_POST['username'],
		];	
		$activityOut = RulesVerifes('GetActivityInfo',$Indata);
		 
		//var_dump($activityOut);

		if (!empty($activityOut) && count($activityOut)>0){
			
			$jsonOut = 
			[
				'errcode'=>-1,
				'msg'=>'非常抱歉您成功已经领取过了,不能重复重复领取！上次领取码为'.$activityOut[0]['desc']
			];
			//echo json_encode($jsonOut);
			exit(json_encode($jsonOut));			
			//return false;
		}
		_log('22','wechat');
		// 根据typ 这里指区服id编号 对应于统计库的地址活得db连接配置
		$outdbKey = get_platform_cfg($Indata['server_id']);
		
		if ($outdbKey)
		{
			_log('33','wechat');
			// 检验玩家用户是否注册的正常用户 --- 其实原本在绑定时候已经进行了校验，只是这次是为了获取注册用户
			// 身上的信息作为最终的礼包发放		 
			$Indata['dbKey'] = $outdbKey;
			
			$playerVerify = RulesVerifes('RoleInfo',$Indata);
			_log('44','wechat');
			if(!$playerVerify)
			{
			 exit('{"errcode": -1,"msg": "角色编号有误,无法识别角色编号,请重试！"}');
			}		
		}else{ 
			exit('{"errcode": -1,"msg": "Database execution failed!"}'); 
		}	 
		_log('55','wechat');
		//$wechat_name =  $username;
		// 检验玩家是否绑定游戏角色 arrcountBInd GameServerCfg
		$accountRet = RulesVerifes('arrcountBInd',$Indata); 
		_log('66','wechat');
		//
		if ($accountRet)
		{		
			_log('77','wechat');
			// 后期加上此验证信息
			// $gameServerCfg = RulesVerifes('GameServerCfg',$Indata);			
			// if ($gameServerCfg)
			// {
				// 验证规则匹配活动  onlayDays,giftid
				$Indata['onlayDays'] =(int)$playerVerify[0]['onlinedays'];

				//var_dump($Indata);

				$cdkInfo = RulesVerifes('cdkInfo',$Indata);

				//echo "cdk info :"."<br>";
				//var_dump($cdkInfo);

				_log('88','wechat');
				$code = $cdkInfo[0]['giftid'].$cdkInfo[0]['batch'].$cdkInfo[0]['code'];
				_log('99','wechat');

				//echo 'code :::'.$code."<br>";
				// 设置活动类型
				//$Indata['activityType'] = 1;		
				$Indata['activityDesc'] =  $code;
				$setactiviLog = RulesVerifes('ActivityLog',$Indata);
				_log('1010','wechat'); 

				if ($cdkInfo && $setactiviLog)
				{		
					_log('1111','wechat');
					$Indata['cdkId'] = $cdkInfo[0]['id'];

					//var_dump($Indata);
					$upcdkStatus = RulesVerifes('cdkUp',$Indata);
					_log('1212','wechat');
					/*activityType,player_id,wechat_name*/
					//ActivityRulesVerify();
					// 更改状态
					if ($upcdkStatus)					
					{	 
						_log('1313','wechat');
						$jsonOut = 
						[
							'errcode'=>0,
							'msg'=>'恭喜您！得到一个礼包码,请您在游戏中进行兑换，礼包码为:'.$code
						];
						//echo json_encode($jsonOut);
						exit(json_encode($jsonOut));	

						//exit("{'errcode': 0,'msg': '恭喜您！得到一个礼包码,请您在游戏中进行兑换，礼包码为:{$code}'}");
						 
					}
					exit('{"errcode": -1,"msg": "礼包领取状态失败"}');										
				}
				exit('{"errcode": -1,"msg": "礼包获取失败"}');
				 
			}	
			return false ;
		}
		//exit('{"errcode": -1,"msg": "账号没有绑定请绑定之后再来重试"}');
	//} 
	else{ 
		ECHO '{"errcode": -1,"msg": "信息不能为空,请您填写必要的角色信息!"}';
		exit();
	}
?>