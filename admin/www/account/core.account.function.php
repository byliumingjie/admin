<?php 
	include_once "../include/lib/mysql.lib.php";

	/**
	 * 获取区服配置
	 * **/
	function  get_platform_cfg($type)
	{ 
		_log("datainfo:::sql 111",'wechat.db.log');
		$db = new Mysqldb();
		_log("datainfo:::sql 22",'wechat.db.log');
		
		$sql = "SELECT mysqlhost,db,mysqluse,mysqlpasswprd 
		FROM admin.tb_platform as a 
		WHERE  platformId in 
		(
		SELECT platformId FROM admin.tb_platform
		WHERE type =:type AND platformId !=0
		) AND type = 0 ;";

		_log("datainfo:::sql 33",'wechat.db.log');
		$typeid = array('type'=>$type);
		_log("datainfo:::sql 44 sql".$sql,'wechat.db.log');	
		$statement = $db->query($sql,$typeid);	
		
		_log("datainfo:::sql 55",'wechat.db.log');	
		if($statement && $db->rowcount($statement)>0)
		{	
			_log("datainfo:::sql 66",'wechat.db.log');	
			$getwechatRow =  $db->fetch_row($statement);
			_log("datainfo:::sql 77 data".json_encode($getwechatRow),'wechat.db.log');	
			if(count($getwechatRow)>0)
			{
				_log("datainfo:::sq l88",'wechat.db.log');	
				$dbKey = array
				(
				 'host'=>$getwechatRow['mysqlhost'],
				 'port'=>3306,
				 'user'=>$getwechatRow['mysqluse'],
				 'pass'=>$getwechatRow['mysqlpasswprd'],
				 'dbname'=>$getwechatRow['db']
				);
				return  $dbKey;
			}
			return false;
		}
		return false;
	}
	/**
	 * 角色验证
	 * **/
	function  player_verify($dbKey,$server_id,$player_id){
		
		$getRoledb = new Mysqldb($dbKey);
		
		$sql = 'SELECT  *  
		FROM game_data_role
		where server_id =:server_id AND player_id=:player_id limit 1';	
		$outid = array('server_id'=>$server_id,'player_id'=>$player_id);

		$statement = $getRoledb->query($sql,$outid);	 
		
		if( $statement && $getRoledb->rowcount($statement)>0 )
		{		
			$getwechatInfo = $getRoledb->fetch_all($statement);		
			if(count($getwechatInfo)>0)
			{		
				return $getwechatInfo;	 
			} 
			return false;
		} 
		return false; 
	}
	/**
	 * 获取角色信息
	 * **/
	function  get_player_info($dbKey,$server_id,$player_id){

		$getRoledb = new Mysqldb($dbKey);

		$sql = 'SELECT  * FROM game_data_role
		where server_id =:server_id AND player_id=:player_id limit 1';

		$outid = array('server_id'=>$server_id,'player_id'=>$player_id);

		//_log("datainfo::: player sql 111",'wechat.db.log');
		$statement = $getRoledb->query($sql,$outid);

		//_log("datainfo::: player sql 222",'wechat.db.log');
		if( $statement && $getRoledb->rowcount($statement)>0 )
		{
			//_log("datainfo::: player sql 333",'wechat.db.log');
			$getwechatlist = $getRoledb->fetch_all($statement);
			//_log("datainfo::: player sql 444",'wechat.db.log');
			if(count($getwechatlist)>0)
			{
				return $getwechatlist;
			}
			return false;
		}
		return false;
	}
	/**
	 * 绑定 
	 ***/
	 function wechat_account_bind($playerId,$wechat_name){

		// var_dump($wechat_name);
		
		$db = new Mysqldb();
		
		$sql = "SELECT  count(*) as cont  FROM minosdb.game_wechat_account_bind
		where playerId =:playerId and wechat_name=:wechat_name ";
		
		$outid = array('playerId'=>$playerId,'wechat_name'=>$wechat_name);
		
		$statement = $db->query($sql,$outid);
		
		if( $statement && $db->rowcount($statement)>0)
		{
			$getwechatRow =  $db->fetch_row($statement);
				
			if($getwechatRow['cont']==0)
			{ 
				$createTime = date('Y-m-d H:i:s',time()); 
				$ret = $db->insert2('minosdb.game_wechat_account_bind',
				array('playerId'=>$playerId,'wechat_name'=>$wechat_name,'createtime'=>$createTime));
				// var_dump($ret);
				if($ret)
				{
				return '{"errcode": 0,"msg": "绑定成功!"}';
				}
				return '{"errcode": -1,"msg": "绑定失败!"}'; 			 
			}
			if($getwechatRow['cont']>0)
			{
				return '{"errcode": -1,"msg": "您的账号已被绑定!"}'; 
			}
		} 
		return '{"errcode": -1,"msg": "网络连接失败!"}';
	 }

	 function  account_award_verify($playerId,$wechat_name,$serverId)
	 {
		$db = new Mysqldb();
		
		$sql = "SELECT  count(*) as cont  FROM minosdb.game_wechat_account_bind
		where playerId =:playerId and wechat_name=:wechat_name ";
		
		$outid = array('playerId'=>$playerId,'wechat_name'=>$wechat_name);
		
		$statement = $db->query($sql,$outid);
		
		if( $statement && $db->rowcount($statement)>0)
		{
			$getwechatRow =  $db->fetch_row($statement);
		 
			if($getwechatRow['cont']>0)
			{
				return true;
			}
		}
		return false;  
	 }
	 
	 function VerifyToken($value,$manager=NULL,$action,$serverId =NULL)
	 {
		$key = 'a329cf9547facb1cdac1b206f2432c48';
			
		$value.$md5data  = array();
		if ((empty($value) || count($value)<=0) && empty($action))
		{
			return false;
		}
		if (is_array($value))
		{
			$Inrequestjson = json_encode($value);
			 
			$request = rawurlencode($Inrequestjson);
		}
		// 打印
		$datastr = $key.$manager.$action.$request.$key;
		 
		$md5data = md5(strtoupper($key.$request.$key));

		if (isset($value['ServerId'])){
			$serverId = $value['ServerId'];
		}elseif (!empty($serverId)){
			$serverId = $serverId;
		}
		
		$platform = $_SESSION['platformInfo'][$serverId]; 	
		$Header['url'] = 'http://'.$platform['platformhost'].
		':'.$platform['platformport'].'/'.$manager.''.$action.'/';
		
		$Header['request'] = $Inrequestjson.'/'.$md5data;
	  
		return  $Header;
	 }
	 /**
	  * 获取平台配置信息
	  * **/
	 function getGameServerCfg($type)
	 {
		$db = new Mysqldb();
		
		$sql = "SELECT platformId,platformhost,platformport,type 
		FROM admin.tb_platform  WHERE type =:type";
		
		if($db->query($sql,array('type'=>$type)) && $db->rowcount() > 0)
		{	  
			$row = $db->fetch_all();
			
			if(count($row)>0)
			{ 		
				return $row;
			}
			else{ 			
				return false;
			}
		} 
		return false;
	 }

	 /**
	  * CURL
	  ***/
	function send_request($url,$data,$coding='gbk', $refererUrl = '', 
	$method = 'POST',$contentType ='application/json;',$timeout = 30, $proxy = false)
	{
		$ch = null;
		$data = trim(mb_convert_encoding($data,"gbk","utf-8"));
		 
		if('POST' === strtoupper($method))
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER,0 );
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			$info = curl_getinfo($ch);
			if ($refererUrl) {
				curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
			}
			$contentType = '';
	 
			if($contentType) {
				curl_setopt($ch, CURLOPT_HTTPHEADER,$contentType);
			}
			if(is_string($data)){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	 
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}
		} 
		else if('GET' === strtoupper($method))
		{
				
			if(is_string($data)) {
				$real_url = $url. rawurlencode($data);
			} else{
				$real_url = $url. http_build_query($data);
			}
			$urldata = rawurlencode($data);
				 
			$ch = curl_init($real_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:'.$contentType));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			if ($refererUrl) {curl_setopt($ch, CURLOPT_REFERER, $refererUrl);}
		}
		else{
			$args = func_get_args();
			return false;
		}
		if($proxy) {
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		$ret = curl_exec($ch);
		curl_close($ch);
		 
		return   mb_convert_encoding($ret,"utf-8","gbk");;
	 }
	 /**
	  * 活动规则验证
	  * @param type data['rules'] 
	  * @param LogingGift [onlayDays,type,ServerId] = 登录礼包
	  * @param CDKCODE [onlayDays,giftid] = 获取兑换码 CDK 
	  * @param CDKCODEUP [cdkId, or {cdkstatus default[0]} ] 
	  * @param ActivitylOG[activityType,player_id,wechat_name]
	  * 兑换码状态更新 CDK UPDATE STATUS ->1(待领取) 0-未领取 2  default set :1
	  * */
	 function ActivityRulesVerify($data =array())
	 {
		$db = new Mysqldb();
		
		$prepareOut =array();
		$sql = NULL;
		
		switch ($data['Rules'])
		{
			// 连续登陆奖励邮件发送获取配置信息
			case 'LogingGift' :  			  
				if ($data['onlayDays']>=3 && $data['onlayDays']<7)
				{ 				
					$type = 1;
				}
				if ($data['onlayDays']>=7)
				{ 					
					$type = 2;
				} 			
				$prepareOut =[
				'type'=>$type,
				'ServerId'=>$data['server_id']		 		
				]; 			
				$sql = " SELECT * FROM game_role_gift
				WHERE type =:type ServerId=:ServerId limit 1;";
				$ret = setSql($sql,$prepareOut);
				if ($ret){return true;} 
				return false;
			break; 
			 
			case 'CDKCODE':
				
				$giftid = 1;

				if ($data['onlayDays']>=3 && $data['onlayDays']<7)
				{
					$giftid = 1;
				}
				if ($data['onlayDays']>=7)
				{
					$giftid = 2;
				}
				_log('cdkinfo 33','db');
				$prepareOut =array('giftid'=>$giftid);
				$sql = " SELECT * FROM admin.tb_cdk
				WHERE `status` =0 AND giftid=:giftid limit 1;";
				_log('cdkinfo 34','db');
				$ret = setSql($sql,$prepareOut);
				_log('cdkinfo end 1','db');
				if ($ret){
					//var_dump($ret);
				_log('cdkinfo end 2','db');	
					return $ret;} 
					_log('cdkinfo end 3','db');	
				return false;
				break; 			
			case 'CDKCODEUP':
				$cdkset = array('status'=>1);
				$id = $data['cdkId'];
				$ret = $db->update('admin.tb_cdk',$cdkset,'id=:id',array('id'=>$id));  			
				if ($ret){return true;} 
				return false;  			
				break; 
			case 'ActivitylOG':
				$ret = $db->insert2('game_wechat_activity_award',
				[
				'activityType'=>$data['activityType'],
				'player_id'=>$data['player_id'],
				'wechat_name'=>$data['wechat_name'],
				'server_id'=>$data['server_id'],
				'desc'=>$data['activityDesc'],
				'createtime'=>date('Y-m-d H:i:s',time()),
				]);
				if ($ret){return true;} 
				return false;
				break;
			case 'GetActivityInfo':
				_log(" GetActivityInfo 22".json_encode($data),'wechat');
				$prepareOut = [
				'activityType'=>$data['activityType'],
				'wechat_name'=>$data['wechat_name'],
				'player_id'=>$data['player_id'],
				];
				
				$sql = " SELECT *  FROM game_wechat_activity_award
				WHERE activityType =:activityType 
				AND wechat_name=:wechat_name  
				AND player_id=:player_id limit 1;"; 
				$ret = setSql($sql,$prepareOut);
				if ($ret){return $ret;} 
				return false;
				break;
			default:return false;break; 
		}
		
	 }

	function setSql($sql,$prepareOut,$dbkey=null)
	{
	_log('cdkinfo setSql  1','db');	
		$db = new Mysqldb();

		if (!empty($sql))
		{ 
			_log('cdkinfo setSql  2','db');	
			 
			$statement = $db->query($sql,$prepareOut);
			//echo "sql set0 "."<br>";
			//var_dump($statement);
			//echo "sql set1 "."<br>";
			//echo  $db->rowcount($statement)."<br>";
			_log('cdkinfo setSql  3','db');	
			if( $statement && $db->rowcount($statement) > 0)
			{	  
					_log('cdkinfo setSql 4','db');
				 
				unset($sql);unset($prepareOut);unset($data);
					_log('cdkinfo setSql 5','db');
				$row = $db->fetch_all($statement);
				_log('cdkinfo setSql 6','db');
				if(count($row)>0)
				{ 
					_log('cdkinfo setSql 7','db');
					return $row;
				}
				else{ 
					_log('cdkinfo setSql 8','db'); 
					return $row;
				}
				_log('cdkinfo setSql 9','db');
			}
			_log('cdkinfo setSql 10','db');
			return false;
		}
		_log('cdkinfo setSql 11','db');
		return false;
	}
	// 
	/**
	 * RulesVerifes  验证   
	 * switch   each case  param status funcion follows...
	 * @method  RoleInfo  @param $data[dbKey,server_id,player_id]..
	 * @method  arrcountBInd @param $data[player_id,wchat_name,server_id]..
	 * @method  GameServerCfg @param $data[server_id]..
	 * @method  giftInfo @param  $data[rules,onlayDays,type,ServerId]..
	 * @method  cdkInfo @param $data[rules,onlayDays,giftid]..
	 * @method  cdkUp @param $data[rules,cdkId , cdkstatus or NULL = default 1 ]..
	 * @method  Token @param $data[value,server_id,action]..
	 * @method  SendMessage @param $data[url,data]..
	 * @method  ActivityLog @param $data[activityType,player_id,wechat_name]
	 * **/
	function  RulesVerifes($status,$data=array())
	{ 
		$ret = NULL; 
		switch ($status){
			// 角色信息
			case 'RoleInfo' : 
				$ret = get_player_info($data['dbKey'],
				$data['server_id'],$data['player_id']);
				
				if ($ret){return $ret;}			
				return false;			
				break;
			// 账号是否绑定
			case 'arrcountBInd' :				
				$ret = account_award_verify($data['player_id'],
				$data['wechat_name'],$data['server_id']);
					
				if ($ret){return $ret;}
				return false;
				break;
			// 游戏服监听地址
			case 'GameServerCfg' :			
				$ret = getGameServerCfg($data['server_id']);
			
				if ($ret){return $ret;}
				return false;
				break;	
			// 礼包信息
			case 'giftInfo' : 
				$ret = ActivityRulesVerify($data); 
				if ($ret){return $ret;}
				return false;
				break;
			// 兑换码信息
			case 'cdkInfo' :
				$data['Rules']= 'CDKCODE';
				$ret = ActivityRulesVerify($data);
				_log('cdkinfo ::1','db');
				if ($ret){ 
					_log('cdkinfo ::1-1','db');
					return $ret;}
				 
				 _log('cdkinfo ::2','db');
				return false;
				break;
			// 兑换码更新
			case 'cdkUp' :
				$data['Rules']= 'CDKCODEUP';
				$ret = ActivityRulesVerify($data);
				if ($ret){return $ret;}
				return false;
				break;
			// Token 加密验证
			case 'Token' :
				$ret = VerifyToken($data['value'],
				NULL,$data['action'],$data['server_id']);
				if ($ret){return $ret;}
				return false;
				break;
			// 消息发送
			case 'SendMessage' :
				$ret = send_request($data['url'],
				$data['data']);
				if ($ret){return $ret;}		
				break;
			case 'ActivityLog' :
				$data['Rules']= 'ActivitylOG';
				$ret = ActivityRulesVerify($data);
				if ($ret){return $ret;}
				return false;
				break;
				//GetActivityInfo
			case 'GetActivityInfo':
				
				$data['Rules']= 'GetActivityInfo';
				_log(" GetActivityInfo 0".json_encode($data),'wechat');
				$ret = ActivityRulesVerify($data);
				_log(" GetActivityInfo 1",'wechat');
				if ($ret){return $ret;}
				return false;
				break;	
			default:return $ret;break;
			
		}
		
	}

