<?php
/***************************************
 *    2015-05-28 by liumingjie add 日志文件
 *    call _LOG function generates log
 ***************************************/
// -----------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Shanghai');//日期区域转换

//define("LOGDIR", __DIR__ . "/../logs/");
 
define("LOGDIR", __DIR__ . "\logs\\");
if (function_exists("_log") != true) {
    function _log($form_data = "", $log_name = "", $url = '')
    {
    	//echo 'log info ddddddd';
        $data = date("Y-m-d", time());

        $url = LOGDIR;

        $log_name = !empty($log_name)
            ?
            $log_name . '_' . $data . '.txt'
            :
            "common_" . date("Y-m-d", time()) . '.txt';

        $myfile = fopen("$url" . "$log_name", "a+");

        $log = "[" . date('Y-m-d h:i:s', time()) . "]" . $form_data . "\r\n";

        fwrite($myfile, $log);
        fclose ( $myfile );
        //echo $log;
    }
}

if (function_exists("_json_error") != true) 
	{
    
function _json_error($string)
   
{
			
	json_decode($string,true);

			
	$status = null;
			
	switch (json_last_error()) 
	{
			
		case JSON_ERROR_NONE:
				
		$status = 0;
			
		break;
			
		case JSON_ERROR_DEPTH:
				
		$status =  ' - Maximum stack depth exceeded';
			
		break;
			
		case JSON_ERROR_STATE_MISMATCH:
				
		$status =  ' - Underflow or the modes mismatch';
			
		break;
			
		case JSON_ERROR_CTRL_CHAR:
				
		$status =  ' - Unexpected control character found';
			
		break;
			
		case JSON_ERROR_SYNTAX:
				
		$status =  ' - Syntax error, malformed JSON';
			
		break;
			
		case JSON_ERROR_UTF8:
				
		$status =  ' - Malformed UTF-8 characters, possibly incorrectly encoded';
			
		break;
			
		default:
				
		$status =  ' - Unknown error';
			
		break;
		
	}
		
	return $status;
    
	}

}
// -----------------------------------------------------------------------------------------
/**
 * 游戏统计日志扩展
 * */
class  LOG
{
	public $dbh=null;
	public $redis = null;
	public $datetime =null;
	
	public function __construct()
	{ 		 
		$this->redis= new  Redis();		
		$this->datetime = date('Y-m-d H:i:s',time());
		$this->dates = date('Y-m-d',time());
	}
	/**
	 * 登录日志记录
	 * @param  array $value 
	 * **/
	public  function log_online($value,$status=false) 
	{		
		$data = array();

		$this->verify_data($value,'log_online');
		$uid = $this->verify_field($value['uid'],true);
 
		//_LOG("log_approach db cont" . "sql ::" .$sql,'dbselect');
		$cont = array();
		$datetime = $this->datetime;	
		// 如何数据不存在就录入数据库否则更新数据 
		$data = array
		(
			"uid"=>$uid, // uid
			"gold"=>$this->verify_field($value['gold']),	// 金币
			"date"=>$datetime,	// 登录时间
			"phone"=>$this->verify_field($value['phone']),   // 手机
			"roomId"=>$this->verify_field($value['roomId']), // 房间号
			"wechat"=>$this->verify_field($value['wechat']), // 微信
			"roleid"=>$this->verify_field($value['id']),     // 用户ID
			"device"=>$this->verify_field($value['device']), // 设备
			"channel"=>$this->verify_field($value['channel']), // 渠道
			"address"=>$this->verify_field($value['address']), // 地址
			"nickname"=>$this->verify_field($value['nickname']), // 账号昵称
			"realname"=>$this->verify_field($value['realname']), // 玩家名
			"total_bet"=>$this->verify_field($value['total_bet']), // 总投注
			"total_win"=>$this->verify_field($value['total_win']),	//总赢取
			"core_version"=>$this->verify_field($value['core_version']),// 核心版本
			"version"=>$this->verify_field($value['version']),	// 版本
			"ticket"=>$this->verify_field($value['ticket']),	//点券
			"last_time"=>null
		);  
		if($status==false)
		{ 
			$this->verify_dbset('t_login_log', $data);	
			unset($data);
		}else{ 
			// 如果推出则增加推出时间
		
			/* select  uid max id */
			$sql ="SELECT MAX(id) as id  FROM t_login_log 
			WHERE uid =$uid LIMIT 1";
			$cont = $this->db_select($sql); 

			// update uid last time

			$where = ['id'=>$cont[0]['id'],'uid'=>$uid,'DATE(date)'=>"DATE('".$this->datetime."')"];
			// 后期获取NewRedis
			$data = ['last_time'=>"'{$this->datetime}'"];
			// 更新数据
			$this->db_update('t_login_log', $data,$where);
			
		}
	}
	/**
	 * 注册日志记录
	 * @param  array $value
	 * **/
	public  function log_registered($value,$status=null)
	{
		//_LOG("in  log_registered:".json_encode($value), "game-stat-log");
		$this->verify_data($value,'log_registered');
		 $data = array(				
			"roleid"=>$this->verify_field($value['id']),  		  // 用户ID
			"uid"=>$this->verify_field(trim($value['uid']),true),// uid
			"channel"=>$this->verify_field($value['channel']),  // 渠道
			"device"=>$this->verify_field($value['device']),   // 设备
			"version"=>$this->verify_field($value['version']),// 版本
			"core_version"=>$this->verify_field($value['core_version']),// 核心版本			
			"datetime"=>$this->datetime,	// 注册时间
			"total_bet"=>$this->verify_field($value['total_bet']),// 累计投注
			"total_win"=>$this->verify_field($value['total_win']),// 累计赢取
			"persis_round"=>$this->verify_field($value['persis_round']),//累计局数
			"nickname"=>$value['nickname'],//角色昵称
			"gold"=>0, //金币
			"status"=>1, //是否在线
			"last_time"=>null, // 最后在线时间
			"ip"=>null// ip
		);
		// 注册
		if ($status==null)
		{
			$this->verify_dbset('t_role', $data);
		}
		else 
		{
			$staus = ($status ==='login')?1:0;
			$last_time = ($status ==='login')?$value['last_time']:$this->datetime;
			$updata = array(
			'gold'=>($this->verify_field($value['gold'])==false)?0:$value['gold'],
			'total_bet'=>($this->verify_field($value['total_bet'])==false)?0:$value['total_bet'],
			'total_win'=>($this->verify_field($value['total_win'])==false)?0:$value['total_win'],
			'last_time'=>'"'.$last_time.'"',
			'persis_round'=>($this->verify_field($value['persis_round'])==false)?0:$value['persis_round'],
			'status'=>$staus,
			'nickname'=>($this->verify_field($value['nickname'])==false)?"":'"'.$value['nickname'].'"',
			); 
			$where = ['uid'=>$value['uid']];
			_LOG("log_registered userinfo".json_encode($updata),"user-log");
			// 更新数据
			$this->db_update('t_role', $updata,$where);
		}		 
	}
	/**
	 * 充值订单日志记录
	 * ***/
	public function log_pay($value)
	{
		$this->verify_data($value,'log_pay');
		$data = array(
			"appid"=>$this->verify_field($value["appid"]),
			"amount"=>$this->verify_field($value["amount"]),
			"payload"=>$this->verify_field($value["payload"]),
			"orderid"=>$this->verify_field($value["orderid"]),
			"time"=>$this->verify_field($value["time"]),
			"queryid"=>$this->verify_field($value["queryid"]),
			"product_id"=>$this->verify_field($value["product_id"]),		
			"uid"=>$this->verify_field($value["user_id"],true),
		);
		$this->verify_dbset('t_pay_log', $data);
	}
	/**
	 * 进场日志记录
	 * **/
	public function log_approach($value=array(),$userinfo=array())
	{		
		$data = array();
		// 集合录入数据
		if( is_array($value) && count($value)>0 && 
		is_array($userinfo) && count($userinfo)>0 )
		{
			//_LOG("log_approach value userinfo",'db');
			$this->verify_data($value,'log_approach');
			$this->verify_data($userinfo,'log_approach userinfo'); 

			$data = array(
				"uid"=>$this->verify_field($userinfo['uid'],true),
				"type"=>$this->verify_field($userinfo["roomId"]),
				"bet"=>$this->verify_field($value["bet"]),
				"rType"=>$this->verify_field($value["rType"]),
				"adType"=>$this->verify_field($value["adType"]),
				"ticket"=>$this->verify_field($value["ticket"]),
				"date"=>$this->dates,
				"round"=>0,
				"frequency"=>1,
			);
		}
		//_LOG("log_approach userinfo".json_encode($userinfo),"approach-log");
		// 检验数据是否存在
		$uid = $userinfo['uid'];
		$type = $userinfo["roomId"];
		$sql ="SELECT COUNT(1) as cont,id FROM t_approach_log 
		WHERE uid = $uid AND type=$type AND date='{$this->dates}' limit 1";

		//_LOG("log_approach db cont" . "sql ::" .$sql,'dbselect');
		$cont = array();
		$cont = $this->db_select($sql);
		//如何数据不存在就录入数据库否则更新数据
		if($cont[0]['cont']<=0)
		{
			//_LOG("log_approach db cont".$cont[0]['cont'] . "sql ::" .$sql,'db');
			$this->verify_dbset('t_approach_log', $data);	
		}
		else
		{	
			//_LOG("log_approach update".json_encode($userinfo),"db");
			
			$uid = $userinfo['uid'];
			$round = $userinfo['round'];
			$where = ['id'=>$cont[0]['id'],'uid'=>$uid,'DATE(date)'=>"DATE('".$this->datetime."')"];
			
			$data = [
			'round'=>'round+1',
			'total_cost'=>'total_cost+'.$userinfo['RoundCost'],
			'total_win'=>'total_win+'.$userinfo['RoundWin'],
			];
			// 如果记录进场的次数而不是局数只需判断进场局数获取数据是否为空
			if(count($value)>0)
			{
				$data = ['frequency'=>'frequency+1'];
			}
			// 更新数据
			$this->db_update('t_approach_log', $data,$where);
		}
		
	}
	/**
	验证uid 是否存在
	**/
	public function verify_uid($userinfo)
	{ 
		$this->verify_data($userinfo,'verify_uid userinfo');
		$uid = $this->verify_field($userinfo['uid'],true);
		

	}
	/**
	 * 验证字段是否设置为空
	 * @param $value whether is set 
	 * @param $empty false or true if true value!=null
	 * **/
	public  function  verify_field($value,$empty=false)
	{
		if ( $empty==true )
		{
			if ($value!=null)
			{
				return  $value;
			}
			return false;			
		}
		if ( isset($value) )
		{			
			return $value;
		}
		_LOG(" verify field: null","field-verify");
		return null;		
	}
	/**
	 * 结果集检验
	 * @param $data Result set judgment or verify
	 ***/
	public function verify_data( $data ,$function=null)
	{
									
		if (!is_array($data) && count($data)<=0 )
		{	
			_LOG("in data_verify failure : " . 
			json_encode($data)." function : 
			".$function , "game-stat-log");
			return false;
		}		
	}
	/**
	 * 数据检验录入
	 * @param $table name
	 * @param $data  array()
	 ***/
	public function verify_dbset($table,$data)
	{ 
		if(!insert($table, $data, $this->dbh ))
		{
			_LOG($table."in  error db setfailure 22:",
			"game-stat-log");
			return false;
		}
		unset($data);		
	}
	/**
	 * 数据更新
	 * @param $table name
	 * @param $data  array()
	 ***/
	public function db_update($table,$data,$where){
	
		if(update2($table,$data,$where)){
			return true;
		}
		_LOG("db update false:".$table,"db-log");
		return false;
	}
	/**
	 * 数据获取
	 * @param $table name
	 * @param $data  array()
	 ***/
	public function db_select($sql)
	{ 
		if($statement = mysqlQuery($sql))
		{	 
			$Indata = fetch_all($statement); 
			return $Indata;
		}
		_LOG("db select false:".$sql,"db-log");
		return false;
	}
	 
}