<?php
include_once 'include/lib/mysql.lib.php';



/**
 *  当用户触发活动时设置为记录用户信息 
 *  wechat_user // 微信用户
	boot_level  // 级别优先级
	playerId    // 游戏角色Id
	type		// 活动类型 1-礼包码领取
	expiry  	// 到期时长
	createtime	// 活动创建日期 
 * */
function set_wechat_user_activity($wechat_user,
$playerId=NULL,$data=array(),$level=1,$type=1,$step=1)
{
 	$datastr = ( count($data)>0 )? json_encode($data) :array();
	
	$expiryTime = time()+(60*5);
	
	$db = new Mysqldb();
	
	$table = 'game_wechat_push';
	
	$dataInfo = array
	(
	'wechat_user'=>$wechat_user,
	'boot_level'=>$level,
	'playerId'=>$playerId,
	'type'=>$type,
	'expiry'=>$expiryTime,
	'step'=>$step,
	'data'=>$datastr,
	'createtime'=>date('Y-m-d H:i:s',time()),	 
	);
	
	$addItion = " ON DUPLICATE KEY UPDATE
	playerId='{$playerId}',
	boot_level = $level ,
	type =$type,
	createTime = now(),
	step=$step,
	expiry={$expiryTime}";
	
	$ret = $db->insert2($table,$dataInfo,$addItion);
	if($ret)
	{		
		return true ;
	}
	return false;
}
/**
 * 插件校验
 * @param $OpenId 
 * @param $type  1--礼包  2--游戏bug反馈
 * @param $data array(....)
 * **/
function account_tool_verify($OpenId,$type=0,$data=array())
{
	_log("BUG Feedback ::11",'wecatlog-bug-feedback');
	switch ($type){
		case 2:// bug 反馈
			_log("BUG Feedback ::22 type-#".$type,'wecatlog-bug-feedback');
			return account_Feedback_monitor($OpenId,$type,$data['Text']);
			break;  
		 
		default:return false;
		break;		
	}
	return false; 
}
/**
 * bug 反馈监听
 * **/
 function  account_Feedback_monitor($OpenId,$type,$ContentText)
 {
	 _log("BUG Feedback ::22-11",'wecatlog-bug-feedback');
 	$wechatPush = get_activity_info($OpenId);
 	 _log("BUG Feedback ::22-12",'wecatlog-bug-feedback');
 	$table = 'tb_wechat_feedback';
 	 	 _log("BUG Feedback ::22-13 type:::".$type,'wecatlog-bug-feedback');
 	$db = new Mysqldb();
 	 
	 _log("BUG Feedback ::22-13 wechatPush:::".json_encode($wechatPush),'wecatlog-bug-feedback');

 	if(is_array($wechatPush) && $wechatPush['type']==$type)
 	{   
		_log("BUG Feedback ::33",'wecatlog-bug-feedback');
 		switch ($wechatPush['step'])	
 		{
 			case 1:
				_log("BUG Feedback ::44",'wecatlog-bug-feedback');
	 			$feedbackData = [
	 			'desc'=>$ContentText,
	 			'opentId'=>$OpenId,
				'createtime'=>date('Y-m-d H:i:s',time())
	 			];
	 			$ret = $db->insert2($table,$feedbackData);
				_log("BUG Feedback ::55",'wecatlog-bug-feedback');
	 			$Pushret = set_wechat_user_activity($OpenId,null,array(),1,2,0);
				_log("BUG Feedback ::66",'wecatlog-bug-feedback');
	 			// 或清理
	 			if ($ret)
	 			{
					_log("BUG Feedback ::77",'wecatlog-bug-feedback');
	 				return '已为您记下，我会实时为您跟踪结果。谢谢您对我们的支持。';
	 			}  
	 		break;
	 		
	 		default:return false;break;
 		}	 	 
 	} 
 	return false;
 }
 
 /**
  * 清理玩家操作步骤
  * **/
 function close_user_wechat_step($wechat_user)
 {
 	$db = new Mysqldb();
 	$table = 'game_wechat_push';
 	$where = 'wechat_user=:wechat_user';
 	
 	$ret = $db->delete( $table,$where,
 	array('wechat_user'=>$wechat_user) );
 	
 	if($ret)
 	{
 		return true;
 	}
 	return false;  
 }
/**
 * 查看账户是否绑定
 * */
 function account_ifbind_verify($wechat_name){
 	_log("account cdk :::2",'wechat.db.log');
 	$db = new Mysqldb();
 	$table = 'game_wechat_account_bind';
 	 
 	$sql = 'SELECT  count(*) as cont
	FROM minosdb.'.$table.' 
	WHERE wechat_name =:wechat_name  limit 1';
 	
 	$outid = array('wechat_name'=>$wechat_name);
 	 
 	$statement = $getRoledb->query($sql,$outid);
 	
 	if( $statement && $getRoledb->rowcount($statement)>0 )
 	{  
 		$getwechatRow = $getRoledb->fetch_row($statement); 
 		if($getwechatRow['cont']>0)
 		{
 			return true;
 		}
 		return false ;
 	}
 	return false ;
 }
 
 function get_account_activity_cdk($openId,$batch)
 {
 	_log("account cdk :::1",'wechat.db.log');
 	$ifbind = account_ifbind_verify($openId);
 	_log("account cdk :::3",'wechat.db.log');
 	$ifgetgift = account_cdk_log($openId);
 	_log("account cdk :::4",'wechat.db.log');
 	if($ifbind)
 	{
 		_log("account cdk :::5",'wechat.db.log');
 		if ($ifgetgift==false)
 		{  _log("account cdk :::6",'wechat.db.log');
 			$cdk = get_cdk_code($batch);
 			_log("account cdk :::7",'wechat.db.log');
 			return $cdk;
 		}
 		return '礼包只能领取一次呃,不能重复领取礼包~';
 	}
 	return '很抱歉！请您绑定账号再来领取礼包奖励吧~';
 	
 }
 function account_cdk_log($wechat_account)
 { 	
 	$db = new Mysqldb();
 	$table = 'game_wechat_cdk_log';
 		
 	$sql = 'SELECT  count(*) as cont
	FROM minosdb.'.$table.' 
	WHERE wechat_account =:wechat_account  limit 1';
 	
 	$outid = array('wechat_account'=>$wechat_account);
 		
 	$statement = $getRoledb->query($sql,$outid);
 	
 	if( $statement && $getRoledb->rowcount($statement)>0 )
 	{
 		$getwechatRow = $getRoledb->fetch_row($statement);
 		if($getwechatRow['cont']>0)
 		{
 			return true;
 		}
 		return false ;
 	}
 	return false ;
 }
 function set_account_cdk_log($data=array())
 {
 	$db = new Mysqldb();
 	$table = 'game_wechat_cdk_log';
 	$ret = $db->insert2($table,$dataInfo,$data);
 	if($ret){
 	 return true;
 	}
 	return false ;
 }
 function get_cdk_code($openId,$batch)
 {
 	$db = new Mysqldb();
 	$table = 'tb_cdk';
 		
 	$sql = 'SELECT  id,cdk
	FROM globaldb.'.$table.'
	WHERE batch=:batch AND status =0  limit 1';
 	
 	$outid = array('batch'=>$batch);
 		
 	$statement = $getRoledb->query($sql,$outid);
 	
 	if( $statement && $getRoledb->rowcount($statement)>0 )
 	{
 		$getwechatRow = $getRoledb->fetch_row($statement);
 		
 		$upcdeRet = set_cdk_code($getwechatRow['id']);
 		
 		$setlog = set_account_cdk_log($openId);
 		
 		if($getwechatRow['cdk'] && $upcdeRet && $setlog)
 		{
 			return $getwechatRow['cdk'];
 		}
 		return '领取失败!请您稍后再试~' ;
 	}
 	return '领取失败服务未响应!请您稍后再试' ;
 }
 function set_cdk_code($id)
 {
 	$db = new Mysqldb();
 		
 	$sql = " UPDATE globaldb.tb_cdk  set status =1 where id = $id";
 		
 	$statement = $db->query($sql);
 		
 	if($statement)
 	{
 		return true;
 	}
 	return false;
 
 }
  
/***
 * 步骤监听
 * **/
 function account_step_monitor($data,$type)
 {
 	
 	switch ($wechatActivity['step']){
 		case 1:
 			
 			set_wechat_user_activity($userName,null,array(),1,1,2);
 			$weObj->text("请输入区服地址")->reply();
 			break;
 		case 2:
 			// json 转换数组
 			if(!$RevContent || !is_numeric($RevContent))
 			{
 				$weObj->text("请输入有效角色编号")->reply();
 				break;
 			}
 			$dbkey = get_platform_cfg(get_platform_cfg);
 			if(empty($dbkey)){
 				$weObj->text("服务器中断！")->reply();
 				break;
 			}
 			$dataOut= json_decode($wechatActivity['data'],true)+
 			array('serverid'=>$RevContent,'dbkey'=>$dbkey);
 			set_wechat_user_activity($userName,null,$dataOut,1,1,3);
 			$weObj->text("请输入角色编号")->reply();
 			break;
 		case 3:
 	
 			if(empty($RevContent))
 			{
 				$weObj->text("请输入角色编号")->reply();
 				break;
 			}
 			$dataOut= json_decode($wechatActivity['data'],true);
 			if(player_verify($dataOut['dbkey'],$dataOut['serverid'],$RevContent)){
 					
 				$code = get_gift_code();
 				$weObj->text($code['code'])->reply();
 				set_wechat_user_activity($userName,null,array('codeId'=>$code),1,1,4);
 				break;
 			}
 			else{
 					
 				$weObj->text("角色验证失败请重新点击领取码")->reply();
 			}
 		case 4:
 			$dataOut= json_decode($wechatActivity['data'],true);
 			if(update_gift_code($dataOut['codeId']))
 			{
 				set_wechat_user_activity($userName,null,array(),1,1,0);
 			}
 	
 		default:
 	
 			break;
 	} 
 	
 }
/**
 * 后期如果存在多个活动出现在菜单的配置解决方案根基级别优先级操作如果有一个级别优先级
 * 或可以考虑追加多个索引目前只是会一个微信号记录一条记录
 * 只要微信玩家每次获取信息同时只获取优先级最好即可，这样就可以通用与在微信上可配置多个回复文字信息的活动接口,
 * 但是如果每次需要根据启动等级获取还存在一种问题就是如果同一个时间段内点了两个活动但是两个每次都是1就不行了
 * $ 解家函数在点击菜单进行初始化就好了
 * 目前就一条
 **/
function get_activity_info($wechat_user)
{
	$db = new Mysqldb();
	
	$new_expire = time() - (60*5);	
	$dbname = 'minosdb';
	$sql = "select * from {$dbname}.game_wechat_push 
	WHERE wechat_user ='{$wechat_user}' 
	AND boot_level=1
	AND expiry>$new_expire
	LIMIT 1";
	
	$statement = $db->query($sql); 
	
	_log("datainfo::: player sql 222".$sql,'wechat.db.log');
	if( $statement && $db->rowcount($statement)>0 )
	{
		_log("datainfo::: player sql 333",'wechat.db.log');
		$getwechatlist = $db->fetch_row($statement);
		_log("datainfo::: player sql 444",'wechat.db.log');
		if(count($getwechatlist)>0)
		{
			_log("datainfo::: player sql 555",'wechat.db.log');
			return $getwechatlist;
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

	$sql = 'SELECT  count(*) as cont
	FROM minosdb.game_data_role
	where server_id =:server_id AND player_id=:player_id limit 1';

	$outid = array('server_id'=>$server_id,'player_id'=>$player_id); 

	//_log("datainfo::: player sql 111",'wechat.db.log');
	$statement = $getRoledb->query($sql,$outid);


	//_log("datainfo::: player sql 222",'wechat.db.log');
	if( $statement && $getRoledb->rowcount($statement)>0 )
	{
		//_log("datainfo::: player sql 333",'wechat.db.log');
		$getwechatRow = $getRoledb->fetch_row($statement);
		//_log("datainfo::: player sql 444",'wechat.db.log');
		if($getwechatRow['cont']>0)
		{
			//_log("datainfo::: player sql 555",'wechat.db.log');
			return true;
		}
		return false;
	}
	return false;
}
/**
 * 获取区服配置
 * **/
function  get_platform_cfg($type)
{
	//_log("datainfo:::sql 111",'wechat.db.log');
	$db = new Mysqldb();
	//_log("datainfo:::sql 22",'wechat.db.log');
	$sql = "SELECT  mysqlhost,db,mysqluse,
	mysqlpasswprd FROM admin.tb_platform
	where type =:type limit 1";
	//_log("datainfo:::sql 33",'wechat.db.log');
	$typeid = array('type'=>$type);
	//_log("datainfo:::sql 44",'wechat.db.log');
	$statement = $db->query($sql,$typeid);

	//_log("datainfo:::sql 55",'wechat.db.log');
	if($statement && $db->rowcount($statement)>0)
	{
		//_log("datainfo:::sql 66",'wechat.db.log');
		$getwechatRow =  $db->fetch_row($statement);
		//_log("datainfo:::sql 77",'wechat.db.log');
		if(count($getwechatRow)>0)
		{

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
 * 活动码领取
 * **/
 function get_gift_code()
 {
 	 
 	$db = new Mysqldb();
 	 
 	$sql = "SELECT id,code FROM admin.tb_cdk
	where status =0 limit 1";
 	 
 	$statement = $db->query($sql,$typeid);
 	
 	//_log("datainfo:::sql 55",'wechat.db.log');
 	if($statement && $db->rowcount($statement)>0)
 	{
 		//_log("datainfo:::sql 66",'wechat.db.log');
 		$getwechatRow =  $db->fetch_row($statement);
 		//_log("datainfo:::sql 77",'wechat.db.log');
 		if(count($getwechatRow)>0)
 		{ 
 			return  $getwechatRow;
 		}
 		return false;
 	}
 	return false;	
 	
 }
 /**
  * 活动码清理或状态更改表示已经有账户拥有了
  * 等待玩家领取之后可以清理码 
  **/
 function update_gift_code($id)
 {
 	$db = new Mysqldb();
 		
 	$sql = " UPDATE admin.tb_cdk  set status =1 where id = $id";
 		
 	$statement = $db->query($sql); 
 	 
 	if($statement)
 	{
 		return true;
 	}
 	return false;
 	
 } 

/**
 * 图文 视频  图文
 * **/
function set_wechat_new_material($status,$account=NULL)
{
	$NewUrl = NULL;
	
	switch ($status){	
		// 发布视频图文（官方视频介绍）
		case 'PromoteVideo':
			$NewUrl = 'https://mp.weixin.qq.com/s/bOS_8CFjSKea3kH25jm16A';			
			$newsOut= array(
				"0"=>array(
					'Title'=>'宣传视频',
					'Description'=>'官方宣传视频',
					'PicUrl'=>'http://m.minosgame.com/wechat/image/2-2.jpg',
					'Url'=>"{$NewUrl}"
				), 		
			);
			return $newsOut;
			break;
		case 'QQgroup':
			$NewUrl = 'https://mp.weixin.qq.com/s/7dw5jb8eLNm3HOPCSIugkA';			
			$newsOut= array(
				"0"=>array(
					'Title'=>'官方群【419317285 】',
					'Description'=>'你为啥这么厉害官方群',
					'PicUrl'=>'http://m.minosgame.com/wechat/image/3-2.jpg',
					'Url'=>"{$NewUrl}"
				), 		
			);
			return $newsOut;
			break;
		// https://mp.weixin.qq.com/s/PVW8fn6c576yuq5Or1GseQ
		case 'NewActivity':
			$NewUrl = 'https://mp.weixin.qq.com/s/PVW8fn6c576yuq5Or1GseQ';			
			$newsOut= array(
				"0"=>array(
					'Title'=>'最新活动',
					'Description'=>'连续登录奖励：在正式公测后，都会对应赠送价值200~500元的新手大礼包',
					'PicUrl'=>'http://m.minosgame.com/wechat/image/2-0.jpg',
					'Url'=>"{$NewUrl}"
				), 		
			);
			return $newsOut;
			break;
		// 
		case 'Emoji':
			$NewUrl = 'https://mp.weixin.qq.com/s/zoOeaCesHdUDazb8stSHNw';			
			$newsOut= array(
				"0"=>array(
					'Title'=>'表情',
					'Description'=>'一大碗汤圆来尝鲜吧~',
					'PicUrl'=>'http://m.minosgame.com/wechat/image/3-0.jpg',
					'Url'=>"{$NewUrl}"
				), 		
			);
			return $newsOut;
			break;
		case 'ActivityGift':
			// 
			$batch = 'Q755';
			$cdk = get_account_activity_cdk($account,$batch);
			return $cdk;
			break;
		default: return false;
			break;		
	} 
}





