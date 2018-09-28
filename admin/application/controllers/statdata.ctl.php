<?php
set_time_limit(0); 
class Statdata_Controller extends Module_Lib { 
	
	public function index(){
		
		$data = array();		
		$serverId = !empty($_POST['serverId']) ? $_POST['serverId'] : NULL;	
		$platId = !empty($_POST['platId']) ? $_POST['platId'] : NULL;
		
		if (isset($_POST['sut'])){

			$conn = Platfrom_Service::getServer($platId);
			 
			$statdata_Model = new Statdata_Model($conn);
			// online
			$_SESSION['onlineOut'] = $statdata_Model->stat_chart_daily_online(
			$conn,$conn['db'],$serverId);
			// login
			$_SESSION['loginOut'] = $statdata_Model->stat_chart_daily_registered(
			$conn,$conn['db'],$serverId);
			// new pay
			$_SESSION['newpay'] = $statdata_Model->Stat_new_added_pay($conn,
			$conn['db'], $serverId);
			// pay
			$_SESSION['payOut'] = $statdata_Model->Stat_pay_number($platId,$serverId);
		 	// get daily info
		 	$dailydata = $statdata_Model->get_daliy_info($conn, $conn['db']);
		 	
		 	$_SESSION['dailydata'] = (isset($dailydata) && count($dailydata)>0)?$dailydata:NULL;
		 	
			$startday = date('Y-m-d',strtotime("$nowtime -30 day"));
			$datatime = $this->jet_lag_day($startday, date('Y-m-d H:i:s',time()),30,true);
			
			// 当前每5分钟实时在线数据
			$_SESSION['currentlyOnline'] = 
			$statdata_Model->Stat_Currently_Online(
			$conn,$conn['db'],$serverId);
			
			/**
			 * 整理视图详情信息列表
			 * **/
			foreach ($datatime as $intime){
				// DAU	
				$Onlinecont = 0 ;
				$logingcont = 0 ; 
				
				$feecont = $payercont = $frequency =  0;
				
				$NewPayRoleNum = $NewPayMoneyNum = $NewPayFrequency= 0 ; 
				
				foreach ($_SESSION['onlineOut'] as $inonline)
				{
					if ($intime == $inonline['createtime'])	{
						
						$Onlinecont = $inonline['cont'];
					}
				}
				// Register
				foreach ($_SESSION['loginOut'] as $inloging)
				{
					if ($intime == $inloging['createtime'])	{
					
						$logingcont = $inloging['cont'];
					}
				}
				// new pay
				foreach ($_SESSION['newpay'] as $newpayVar){
					//a.newPayFrequency,a.newPaymoney,a.newPayRoleNum
					if ($intime == $newpayVar['datetime'])	
					{
						$NewPayRoleNum = (int)$newpayVar['newPayRoleNum'];
						$NewPayMoneyNum = (int)$newpayVar['newPaymoney'];
						$NewPayFrequency = (int)$newpayVar['newPayFrequency'];						
					}					
				}
				// pay 
				foreach ($_SESSION['payOut'] as $inpay)
				{
					if ($intime == $inpay['datetime'])	
					{							
						$feecont = (int)$inpay['fee']/100;
						$payercont = (int)$inpay['rolecont'];
						$frequency = (int)$inpay['frequency'];
					}
				}
				 
				$Detailsdata[$intime] = 
				[
				'onlineCount'=>$Onlinecont,
				'logingCont'=>$logingcont,
				'feeCont'=>$feecont,
				'payerCont'=>$payercont,
				'frequency'=>$frequency,
				'newPayRole'=>$NewPayRoleNum,
				'newPayMoney'=>$NewPayMoneyNum,
				'newPayFrequency'=>$NewPayFrequency,
				];
				
			} 
			$_SESSION['detailsdata'] = $Detailsdata; 
			//最高在线 
		}
		
		if ($_SESSION['onlineOut'] || $_SESSION['loginOut'] 
		|| $_SESSION['currentlyOnline'])
		{ 
			$data = array(
			 'onlineOut'=>$_SESSION['onlineOut'],		
			 'loginOut'=> $_SESSION['loginOut'],
			 'detailsOut'=>$_SESSION['detailsdata'],
			 'realtimeDauOut'=>$_SESSION['currentlyOnline'],
			 'dailydata'=>$_SESSION['dailydata'],
			 'feeOut'=>$feecont,
			 'platId'=>$platId,
			 'serverId'=>$serverId
			);
		}
		$this->load_view("stat/stat_index",$data);		
	}
	//文件导出
	public function ExportfileIndex()
	{
		$platId = isset($_POST['platId'])?$_POST['platId']:0;
		$time = isset($_POST['time'])?explode(',',trim($_POST['time'])):'';
		$data = isset($_POST['data'])?explode(',',trim($_POST['data'])):0;
		$sid  = isset($_POST['sid'])?$_POST['sid']:0;
		$key  = isset($_POST['key'])?$_POST['key']:'';
			
		$keystr = str_replace(array("\r\n", "\r", "\n"), "",$key);
			
		foreach($data as $var)
		{
			$Data[] = explode("=",$var);
		}
	
		$data = array($keystr=>$Data);
		 
		$fileName = $this->output_file($time,$platId,$data,'',$sid);
		 
		if($fileName)
		{
			$page = Config::get("common.page");
			$acction = $page['host'].'/statfile/';
			header("location:".$acction.$fileName);
			$this->load_view("stat_data",$data);
		}
	
	}
	/**
	 * 全服留存
	 * **/
	public function stat_AllServer_Retained()	
	{  
		session_start();
		$data = array();
		if(!empty($_POST['platId']))
		{		
			$platId =$_POST['platId'];
			
			$datetime = !empty($_POST['datetime'])
			?
			date('Y-m-d',strtotime($_POST['datetime']))
			:'';
			
			$space = !is_numeric($_POST['space'])
			?
			$this->prompt("留存非有效数字或不能为空！")
			:(int)$_POST['space']; 
			  
			if ($space>30)
			{	
				$this->prompt("留存天数已经达到上限！上限30天",false);
			}
			$conn = Platfrom_Service::getServer((int)$platId);
			 
			$dbname = $conn['db'];
		 	  
		 	$timeList = $this->Timelist($datetime,$space,true);
		 	
		 	 
		 	$endtime =  $timeList[$space];  
		 	
		 	IF(isset($_POST['sut']))
		 	{ 
				$list = Statdata_Model::Stat_AllServer_Retained(
				$conn,$dbname,$datetime,$endtime); 
				$_SESSION['allRetainedList'] = $list; 
		 	}	 
			$data = array
			(
				"data"=>$_SESSION['allRetainedList'],
				"time"=>$timeList,
				"platId"=>$platId,
				"space"=>$space,
				"datetime"=>$datetime
			);  
		}
		$this->load_view("stat/stat_allserver_retained",$data);
	} 
	
	/**
	 * 根据区服的多个配置不同的区服样式共db读取
	 * [0][serverId sql field cfg]$sid_unit,[1][severId info ]$sid,[2]$countsi
	 * @param $sidSpace1 Int ,$sidSpace2 Int//区服两个区间单位
	 * @param $regionid  string// 区服ID 可以以范围一个字符串
	 * @return sid 无论多个还是单个 $data[0] //存在查询符方式 $data[1]排除查询符方式
	 **/
	public function sid_config($sidSpace1=NULL,$sidSpace2=NULL,
	$regionid=NULL,$limit=21)
	{
		$sid_unit = '';
		$data = array();
		# 可以默认(自定义)也可以区间范围生成区服列表编号
		if(!empty($sidSpace1) && !empty($sidSpace2) && empty($regionid))
		{
			$arrayOut = $this->range_number($sidSpace1,$sidSpace2);
		}
		elseif (!empty($regionid) && empty($sidSpace1) && empty($sidSpace2))
		{
			$arrayOut = explode(",",trim($regionid)); # 支持多个区服ID例:1,2解析多个
		}
		elseif (!empty($sidSpace1) && !empty($sidSpace2) && !empty($regionid))
		{
			$this->prompt("非法的区服范围,区服范围重复使用！",false);
		}else{
			$this->prompt("区服不能为空！请选至少一种区服类型",false);
		}
		if ($limit!=0)
		{
			count($arrayOut)>$limit
			?
			$this->prompt("区服查询已达到上限!最大容量为20个区服",false):"";
		}
		$countsid = (Int)count($arrayOut);
	
		foreach($arrayOut as $Insid)
		{
			if(!is_numeric($Insid))
			{
				$i+=1;
				$i===2?
				$this->prompt("存在特殊符号或符号容量查处合法范围!",false)
				:"";
				continue;
			}
			$sid .= $Insid.",";
		}
	
		$sid = substr($sid,0,-1);
	
		$sid_unit = $countsid===1
		?
		" =".$sid:
		" in (".$sid.")";
	
		$data = array($sid_unit,$sid,$countsid);
	
		return $data;
	}
	
	/**
	 * 统计玩家货币付费信息
	 * **/
	public function stat_money_info()
	{
		session_start();
	
		$data = array();
		$page = empty($_GET['p']) ? 1 : $_GET['p'];#page number
		$pagesize = 200;
		if(isset($_POST['sut'])){
			  
			// 角色ID
			if (!empty($_POST['roleid']))
			{
				$Roleid = explode(",",trim($_POST['roleid']));
				$countrole = count($Roleid);
				foreach ($Roleid as $Inroleid)
				{
					if(!$Inroleid){
						continue;
					}
					$roleid_str .='"'.$Inroleid.'"'.",";
				}
				$roleid =  $countrole>=2
				?
				'a.play_id in('.substr($roleid_str,0,-1).')'
						:
						'a.play_id = '.substr($roleid_str,0,-1).' ';
			}
	
			#角色名
			$Name = !empty($_POST['name'])
			?
			"a.nick_name like \"%".trim($_POST['name'])."%\""
					:"";
					// 消耗类型 
					$remainType = !empty($_POST['remainType'])
					?
					$_POST['remainType']
					:"";
	
					$status = !empty($_POST['status'])?(int)$_POST['status']:"";
					$time = array($_POST['startTime'],$_POST['endTime']);
					# 开始日期
					$startTime = !empty($_POST['startTime'])
					?
					'DATE(a.time) >= DATE("'.$_POST['startTime'].'")':"";
					# 截止日期
					$endTime = !empty($_POST['endTime'])
					?
					'DATE(a.time) <= DATE("'.$_POST['endTime'].'")':"";
					# Exctype(流水类型)
					$exctype  = (!empty($_POST['exctype']) && $_POST['exctype']>0)
					?
					"a.module =".trim($_POST['exctype']):"a.module BETWEEN  1 AND  100";
					# 货币消耗类型
					if (!empty($remainType)){
						
						$loginType ='a.currency='.$remainType;
					}
					else
					{
						$loginType =NULL;
					}
					# 获取货币消耗类型配置
					/* $remainData = $this->remain_type_config($remainType);					
					$OutputType =$remainData[0];
					$ConsumeType =$remainData[1];
					$strType =$remainData[2];
					
					if(!empty($status) && $status===1 && !empty($remainType))
					{
						$loginType ='a.logtype='.$ConsumeType;
					}
					else if(!empty($status) && $status===2 && !empty($remainType))
					{
						$loginType ='a.logtype='.$OutputType;
					}
					elseif(
							!empty($status) && $status===3 && !empty($remainType)
							|| $remainType!=0)
					{
						 
						$loginType = "a.logtype in({$OutputType},{$ConsumeType})";
					}
					else
					{
						$loginType ='';
					} */
					# 区服编号
					$getConfigSid= $this->sid_config('','',$_POST['regionid']);
					$regionid_unit = " a.server_id".$getConfigSid[0];
					$regionid = $getConfigSid[1];
					$sidNum = $getConfigSid[2];
					# 货币流水类型设索引，原则上是所有查询都要经过设置货币流水 也就是不能为空的 ，这里增加了一些特性即
					# 单服不需要经过流水类型后期再进行优化调整
					if ($sidNum>1 && empty($_POST['exctype']))
					{
						$this->prompt("不合法的查询列,只有单区服查看查看所有的流水类型！",false);
					}
					/*# ……*1-支出（-消耗） 2-收入 */
					$havingAry = array($loginType,$exctype,$regionid_unit,
							$roleid,$startTime,$endTime,$Name);
					 
					foreach ($havingAry as $var)
					{
						if(empty($var))
						{
							continue;
							$having .= $var;
						}
						if(isset($having)){
							$having .= " AND ".$var;
						}else{
							$having .= "WHERE ".$var;
						}
					}
					 
					$platId = !empty($_POST['platId'])?$_POST['platId']:"";
	
					# 第一次session存放拼接的sql条件 区服ID 以及平台类型
					$_SESSION['exctypestr'] = $_POST['exctype'];
					$_SESSION['regionid'] = $regionid;
					$_SESSION['platId'] = $platId;
					$_SESSION['time'] = $time;
					$_SESSION['having'] = $having;
					$page = 1;
		}
		 
		if (!empty($_SESSION['platId']))
		{
			$conn = Platfrom_Service::getServer((int)$_SESSION['platId']);
			$dbname = $conn['db'];
			 
			# 第二次和第一次同时进行但是区服的信息必须要在第一次执行之后才可以进行运行
			if(isset($_POST['sut']))
			{
				$total = Statdata_Model::Stat_moneyFlowTotal($conn,$_SESSION['having'],
						$dbname,$_SESSION['regionid']);
				$_SESSION['total'] = $total['cont'];
			}
			$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($_SESSION['total'],
					$page,$pagesize));
	
			$list = Statdata_Model::Stat_moneyFlow($conn,$_SESSION['having'],
					$page,$pagesize,$dbname,$_SESSION['regionid']);
	
			$data = array
			(
				'pagehtml'=>$pagehtml,
				'data'=>$list,
				'platId'=>$_SESSION['platId'],
				'sid' =>$_SESSION['regionid'],
				'time'=>$_SESSION['time'],
				'exctypestr'=>$_SESSION['exctypestr']
			);
			 
		}
		$this->load_view('stat/stat_money_info',$data);
	}
	/**
	 *等级分布
	 ***/
	public function stat_level_info(){
		
		$data = array();
		$startTime = " DATE(createtime)>=DATE('".$_POST['startTime']."')";
		$endtime = " DATE(createtime)<= DATE('".$_POST['endTime']."')";
		$platId = $_POST['platId'];
		$serverId = " server_id = ".$_POST['serverId'];
		  
		if(isset($_POST['sut']))
		{  
			$_SESSION['levelinfoserverid'] = $_POST['serverId'];
			
			$havingAry = array($serverId,$startTime,$endtime);
			 
			foreach ($havingAry as $var)
			{
				if(empty($var))
				{
					continue;
					$having .= $var;
				}
				if(isset($having)){
					$having .= " AND ".$var;
				}else{
					$having .= "WHERE ".$var;
				}
			}
			$conn = Platfrom_Service::getServer($platId);
			$dbname = $conn['db'];
			$_SESSION['leveldata'] = Statdata_Model::Stat_level_info($conn,$dbname,$having);
			$_SESSION['platId'] = $platId;
		}
		if ($_SESSION['leveldata']){
			
			$data= array(
				'leveldata'=>$_SESSION['leveldata'],
				'platId'=>$_SESSION['platId'],
				'serverid'=>$_SESSION['levelinfoserverid']
			);
		}
 		$this->load_view('stat/stat_level_info',$data); 
	}
	/**
	 * @global 当前在线信息
	 * @method realTimeRole 实时在线玩家
	 **/
	public function realTimeRole()
	{ 
		$data = array();
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
	
		$pagesize = 10;
	
		$platId = !empty($_POST['platId'])
		?
		(int)$_POST['platId']:NULL;
		
		$code = 'AskOnlineNum';
		
		if(isset($_POST['sut']))
		{
			$serverList = Platfrom_Service::get_plat_server($platId);
			
			foreach ($serverList as $sid)
			{
				
				$ret = $this->send(['ServerId'=>(int)$sid], $code,(int)$sid,true);
				
				if (isset($ret['intData']))
				{
					$onlineData[$sid] = $ret['intData'];
				}
			}
			//$conn = Platfrom_Service::getServer($platId);			
			//$dbname = $conn['db']; 
			
			//$total = Statdata_Model::Stat_Currently_online_total($conn,$dbname);
			//$_SESSION['total'] = $total['cont'];
			 
			$_SESSION['onlinePlatData'] = $onlineData;
			$_SESSION['platId'] = $platId;
		}
		if ($_SESSION['onlinePlatData'])
		{
			//$total = $_SESSION['total'];
			
			$conn = Platfrom_Service::getServer($_SESSION['platId']);
			$dbname = $conn['db'];	
			
			$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total,
			$page,$pagesize));
			
			$CurrentlyonlineOut = Statdata_Model::Stat_Currently_online_info2($conn,$dbname);
			  
			// get 日志
			$data = array
			(  
				'pagehtml'=>NULL,
				'onlineData'=>$_SESSION['onlinePlatData'],
				'total'=>NULL,
				'platId'=>$_SESSION['platId'],
			);
		} 
		$this->load_view("stat/stat_realtime_role",$data);
	}
	/**
	 * 最高在线
	 *
	 **/
	public function  stat_highest_online(){
	
		$data = array();
		$platId = $_POST['platId'];
 		$startTime = $_POST['startTime'];
		$endtime = $_POST['endTime'];
		
		if (isset($_POST['sut']))
		{ 
			$conn = Platfrom_Service::getServer($platId);
			$dbname = $conn['db'];
			$InhighestDatOut = Statdata_Model::Stat_highest_online(
			$conn, $dbname, $startTime, $endtime);			
			$_SESSION['highestout'] = $InhighestDatOut; 
		}
		if ($_SESSION['highestout']){ 
			
			$data =array( 
			'object'=>$_SESSION['highestout'], 
			'platId'=>$_SESSION['platId'],
			);
		}
		$this->load_view("stat/stat_highest_online",$data);
	}
	/**
	 * 
	 * 最高在线时长
	 * 
	 **/
	public function onlineTimeLength()
	{ 	
		$data = array();
		$serverId =$_POST['serverId'];
		$startTime = $_POST['startTime'];
		$endTime =$_POST['endTime'];
		$platId = $_POST['platId'];
		$onlineTimeNum = $_POST['num'];
		$onlineTimeNum2 = $_POST['num1'];
		$onlinetype = $_POST['type'];
		// 1-日  2-时  3-分 4-秒
		switch ($onlinetype){
			
			case 1:
				$onlineTimeNum *= 24*60*60;
				$onlineTimeNum2*= 24*60*60;
				$onlinetypeNam = '日';
				break;
			case 2:
				$onlineTimeNum *= 60*60;
				$onlineTimeNum2*= 60*60;
				$onlinetypeNam = '时';
				break;
			case 3:
				
				$onlineTimeNum *= 60;
				$onlineTimeNum2*= 60;
				$onlinetypeNam = '分';
				break;
			case 4: 
				$onlinetypeNam = '秒';
				// 初始秒无需转换
				break;
			default:break;
		}
		if (isset($_POST['sut']))
		{
		  $conn = Platfrom_Service::getServer($platId);
		  $dbName = $conn['db'];
		  $_SESSION['onlinetimedata'] = Statdata_Model::Stat_role_Online_Time($conn, $dbName, 
		  $serverId, $onlineTimeNum,$onlineTimeNum2, $startTime, $endTime);
		  $_SESSION['onlinetimedataplat'] =$platId;
		  
		  $_SESSION['onlinetimenum1'] =$_POST['num'];
		  $_SESSION['onlinetimeNam'] =$onlinetypeNam;
		  $_SESSION['onlinetimenum2'] =$_POST['num1'];
		}
		if ( $_SESSION['onlinetimedata'] ){
			
			$data = array
			(
			'onlinetimedata'=>$_SESSION['onlinetimedata'],
			'platId'=> $_SESSION['onlinetimedataplat'],
			'onlinetimenum1'=> $_SESSION['onlinetimenum1'],
			'onlinetname'=> $_SESSION['onlinetimeNam'],
			'onlinetimenum2'=> $_SESSION['onlinetimenum2'],
			);
		}
		$this->load_view("stat/stat_online_timelength",$data);
	}
	
	/**
	 * @global 分页
	 * @method 分页数组
	 * @param int $pagesize is pagesize
	 * @param int $page is page
	 * @param array $array total data info is array
	 * @param $order 
	 * @param array $array
	 * @param string orderkey array key 
	 * @param $type asc or desc 
	 * **/
	public function page_array($pagesize,$page,$array,$orderkey,$type='asc')
	{  
	    global $countpage; #定全局变量  
	    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面   
	       $start=($page-1)*$pagesize; #计算每次分页的开始位置  
	    if(!empty($orderkey) && !empty($type)){  
	    	$array=$this->array_sort($array,"{$orderkey}","{$type}");
	    }     
	    $totals=count($array);    
	    $countpage=ceil($totals/$pagesize); #计算总页面数  
	    $pagedata=array();  
	    $pagedata=array_slice($array,$start,$pagesize);  
	    return $pagedata;  #返回查询数据  
	}
	/***
	玩家邮件领取记录统计
	**/  
	public function stat_user_mail_annal()
	{
		$data = array();
		
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		
		$playerid = $_POST['RoleIndex'];
		
		$nikeName = $_POST['nikeName'];
		
		$serverId = $_POST['serverId'];
		
		if (!empty($nikeName))
		{
			$AllplatformInfo = session::get('AllplatformInfo');
			
			$platIdOut = Platfrom_Service::server_match_Plat($_POST['serverId']);
			
			 
			foreach ($AllplatformInfo as $var)
			{
				if ((int)$var['platformId']==(int)$platIdOut[$serverId] && (int)$var['type']==0)
				{
					$platConfig = $var;
				}
			} 
			$accountModel = new Account_Model();
			
			$inplayer = $accountModel->getRoleIndex($platConfig,$nikeName);
			  
			$playerid = $inplayer['player_id'];
		}
		
		if (isset($_POST['sut']))
		{
			$startTime = (!empty($_POST['startTime'])) 
			? 
			" AND   time>=".strtotime($_POST['startTime']):"";
			
			$endTime = (!empty($_POST['endTime'])) 
			? 
			" AND  time<=".strtotime($_POST['endTime']):"";
			
			$serverId = " AND server_id = ".$_POST['serverId'];
			
			$RoleIndex = " AND player_id = '".$playerid."'";
			
			
			$having = array($startTime,$endTime,$serverId,$RoleIndex);
			
			foreach ( $having as $var){
				
				$haingstr .= " ".$var;
			} 
			
			$data['UserMailList'] = 
			Statdata_Model::Stat_User_Mail_Annal($platfrom,$haingstr);
		}
		$this->load_view("stat/stat_mail_log",$data); 
	}
	/**
	 * 玩家实时在线数据
	 * **/
	public function Stat_real_time()
	{   
		$data = [];
		
		if (isset($_POST['sut']))
		{
			$_SESSION['RealTimeInfo'] = 
			$this->Stat_real_time_list();
		}		
		
		if ($_SESSION['RealTimeInfo'])
		{
			$data = 
			[
			  'realtimeinfo'=>$_SESSION['RealTimeInfo']		
			];
		}
		$this->load_view("stat/stat_real_time",$data);
	}
	
	public function Stat_real_time_list()
	{
		$platId = $_POST['platId'];
		
		$conn = Platfrom_Service::getServer($platId);
		
		$serverId = $_POST['serverId'];
		
		// 得到一个时间点
		$startTime =  empty($_POST['startTime']) 
		? 
		date('Y-m-d H:i:s',time()) 
		: 
		$_POST['startTime'];
		 
		if (strtotime($startTime) > time())
		{
			if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
			&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') 
			{
				$this->outputJson(-1, '起始时间点不能大于当前时间');
			} 
			else 
			{			
				return false;
			} 	 	
		}
		 
		// 得到一个时间点如果不存在默认为当前时间
		$endtime = empty($startTime)
		?
		date("Y-m-d",strtotime("-1 day"))
		:
		date("Y-m-d",strtotime($startTime) -24*60*60 );
		 
		$statModel = new Statdata_Model($conn);
		// 登录人数 登录次数  评价登录时长
		$InRealTimeLogin = 
		$statModel->Stat_real_time_Inlogin($conn,$serverId,$endtime,$startTime);
		 
		__log_message(json_encode($InRealTimeLogin),'stat-log');
		foreach ($InRealTimeLogin as $Invar){
			 
			$datatime = $Invar['createtime'];
			 
			 
			// 登录获取当前数据（输入的时间点数据）
			if ( $datatime == date('Y-m-d',strtotime($startTime)))
			{
				$CurrentLoginOut = 
				[
				'server_id'=>$Invar['server_id'],
				'createtime'=>$Invar['createtime'],
				'RoleNum'=>$Invar['RoleNum'],
				'loginNum'=>$Invar['loginNum'],
				'AvglogingTime'=>$Invar['AvglogingTime'],
				];
			}
			// 次日登录完整数据（输入的时间点的昨日后退一天的数据）
			$TotalLoginOut[] = 
			[
			'server_id'=>$Invar['server_id'],
			'createtime'=>$Invar['createtime'],
			'RoleNum'=>$Invar['RoleNum'],
			'loginNum'=>$Invar['loginNum'],
			'AvglogingTime'=>$Invar['AvglogingTime'],
			];
		}
		 
		// 付费次数 付费人数 付费金额
		$InrealTimePay = $statModel->Stat_real_time_payinfo($serverId,$endtime);
		 
		$TotalPay = $statModel->Stat_real_time_pay($serverId);
		 
		$fee = $frequency = $rolenum = 0 ;
		 
		foreach ($InrealTimePay as $Invar)
		{
			$datatime = $Invar['createtime'];
			  
			// 当前数据
			if ( $datatime == date('Y-m-d',strtotime($startTime)) )
			{
				$fee += (int)$Invar['fee'];
				$frequency += (int)$Invar['frequency'];
				$rolenum += (int)$Invar['rolenum'];
				
				$CurrentPayOut = 
				[
				'server_id'=>$Invar['server_id'],
				'fee'=>$fee,
				'frequency'=>$frequency,
				'rolenum'=>$rolenum,
				'datetime'=>date('Y-m-d',time()),
				];
			}
			// 付费完整数据
			$TotalPayOut[] = 
			[
			'server_id'=>$Invar['server_id'],
			'fee'=>$Invar['fee'],
			'frequency'=>$Invar['frequency'],
			'rolenum'=>$Invar['rolenum'],
			'datetime'=>$Invar['datetime'],
			];
		}
		 
		// 对每一个小时分割的额注册人数时间段 还有老玩家人数对应时间段(老玩家为次日)
		$InRealTimeRole = $statModel->Stat_real_time_registered($conn,$serverId,$endtime,$startTime);
		
		$Inonlinedays = $statModel->Stat_real_onlinedays($conn,$serverId,$endtime);
		
		$cont = $onlinedays = 0; 
		
		foreach ($InRealTimeRole as $Invar)
		{
			$datatime = $Invar['createtime'];
			// 当前数据
			if ( $datatime == date('Y-m-d',strtotime($startTime)) )
			{
				$cont +=(int)$Invar['cont'];
				//$onlinedays +=(int)$Invar['onlinedays'];
				
				$CurrentRegOut = 
				[
				'server_id'=>$Invar['server_id'],
				'cont'=>$cont,
				'createtime'=>$datatime,
				'onlinedays'=>$Inonlinedays['onlineDays'],
				];
			}
			$TotalRegOut[] = 
			[
			'server_id'=>$Invar['server_id'],
			'cont'=>$Invar['cont'],
			'createtime'=>$Invar['createtime'],			
			'datetime'=>$Invar['datetime'],
			'onlinedays'=>$Invar['onlinedays'],
			];
		}
		// 新增付费
		// new pay
		$newpay = $statModel->Stat_new_added_pay($conn,
		$conn['db'], $serverId);
		 
		foreach ($newpay as $Invar)
		{ 
			$datatime = $Invar['datetime'];
			 
			$newPaymoney = (int)$Invar['newPaymoney'];
			$newPayFrequency = (int)$Invar['newPayFrequency'];
			$newPayRoleNum = (int)$Invar['newPayRoleNum'];
			
			// 当前数据
			if ( $datatime == date('Y-m-d',strtotime($startTime)))
			{  	
				$CurrentNewPayOut =
				[
					'server_id'=>$Invar['serverId'],
					'newPaymoney'=>$newPaymoney,
					'createtime'=>$datatime,
					'newPayFrequency'=>$newPayFrequency,
					'newPayRoleNum'=>$newPayRoleNum
				];
				break;
			}  
		}
		
		$Real_time_Online = $statModel->Stat_Currently_Online(
		$conn,$conn['db'],$serverId,$endtime,TRUE);
		
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
		&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') 
		{  
			$rs = 
			[
			'errcode'=> 0,
			'msg'=> 'ok',
			'loginRole'=> empty($CurrentLoginOut['RoleNum'])?0:$CurrentLoginOut['RoleNum'], // 登录人数
			'loginFrequency'=> empty($CurrentLoginOut['loginNum'])?0:$CurrentLoginOut['loginNum'], //登录次数
			'AvglogingTime'=> ceil((int)$CurrentLoginOut['AvglogingTime']/60).'分', // 平均登录时长
			'payfee'=>((int)$CurrentPayOut['fee']/100), // 今日收入
			'payfrequency'=> $CurrentPayOut['frequency'], // 付费次数
			'Payrolenum'=> $CurrentPayOut['rolenum'], // 付费玩家
			'PayTotal'=>((int)$TotalPay['Totalfee']/100),	// 累计付费
			'onlineRole'=> $CurrentRegOut['cont'],//新增人数
			'onlinedays'=> $CurrentRegOut['onlinedays'],// 老玩家
			'newpay'=>$CurrentNewPayOut, // 新增付费
			];
			echo json_encode($rs);
			exit;
		} 
		else 
		{		 
			return
			[
			'serverId'=>$serverId,
			'PlatId'=>$platId,
			'current_pay' => $CurrentPayOut,
			'total_pay' => $InrealTimePay,
			'total_monery' =>(int)$TotalPay['Totalfee'],
			'current_login' => $CurrentLoginOut,
			'total_login' => $TotalLoginOut,
			'current_online' => $CurrentRegOut,
			'total_online' => $TotalRegOut,
			'real_time_online' => $Real_time_Online,
			'current_time'=>date('Y-m-d',strtotime($startTime)),
			'Yesterday_time'=>date('Y-m-d',strtotime($endtime)),
			'newpay'=>$CurrentNewPayOut 
			];
		} 	 
	} 
	
	// 钻石排行
	public function  Stat_diamond_rank()
	{
		$data = [] ;
		
		$platId = $_POST['platId'];		
		
		$range = $_POST['range'];
		
		$serverId = empty($_POST['serverId']) 
		? NULL : $_POST['serverId'];
		
		$conn = Platfrom_Service::getServer($platId);
		
		if (isset($_POST['sut']))
		{		
			// 
			$rangeOut = Statdata_Model::Stat_real_time_rank_range(
			$conn,(int)$range,$serverId);
			
		 	$_SESSION['rankout'] = Statdata_Model::Stat_real_time_rank(
		 	$conn,$rangeOut['mindiamond'],$rangeOut['maxdiamond'],$serverId);
		}
		
		if ($_SESSION['rankout'])
		{			
			$rankOrder = [];
			$datin = [];
			$i = 1 ;
			
			foreach ($_SESSION['rankout'] as $var)
			{
				$datin = NULL;
				$datinfo = 0 ;
				if (isset($rankOrder[(int)$var['diamond']]))
				{
					$rankOrder[(int)$var['diamond'].'-'.$i] = $var;
						
					$rankOrder[(int)$var['diamond'].'-'.$i]['rank']
					= $rankOrder[(int)$var['diamond']]['rank'];
						
				}
				else
				{
					$rankData = [];
					$rank = $i ;
						
					if (isset($rankOrder) && count($rankOrder)>0)
					{
						foreach ($rankOrder as $Inval)
						{
							$rankData[] = $Inval['rank'];
						}
					}
					$rankOrder[(int)$var['diamond']]  = $var;
			
					if (isset($rankData) && count($rankData)>0)
					{
						$rank =  $rankData[count($rankData)-1] + 1 ;
					}
					$rankOrder[(int)$var['diamond']]['rank'] = $rank;
				}
				$i++;
			}
			// 
			$data = [
			'platId'=>$platId,
			'serverId'=>(isset($serverId))?$serverId:NULL,
			'RankOut'=>$rankOrder,
			];
		}
		
		$this->load_view('stat/stat_diamond_rank',$data);
	}
   /** 
	* @global 在线信息
	* @method onlineRole 在线玩家当前 
	**/
/* 	public function onlineRole($data = array())
	{    
			 
			$page = empty($_GET['p']) ? 1 : $_GET['p'];	
		 
			$pagesize = 10; 	
		 
			$server = !empty($_POST['server']) 
			?
			$_POST['server']:'';
			
			$sequence = $_POST['sequence'];
			
			if(isset($_POST['sut']))
			{ 
			// <<<<<<<<<<<<<<<<<<<<<<<<<<<<
			$dataOut=array(
			["id"=>1 ,"account"=>'jamse1 ',"name"=>"尼龙1 ","level"=>121 ,"paynumber"=>21 ,"diamond"=>21,"gold"=>50001 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.68'  ,"totalTime"=>"25544"],
			["id"=>2 ,"account"=>'jamse2 ',"name"=>"尼龙2 ","level"=>122 ,"paynumber"=>22 ,"diamond"=>22,"gold"=>50002 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.1 '  ,"totalTime"=>"25544"],
			["id"=>3 ,"account"=>'jamse3 ',"name"=>"尼龙3 ","level"=>123 ,"paynumber"=>23 ,"diamond"=>23,"gold"=>50003 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.2 '  ,"totalTime"=>"25544"],
			["id"=>4 ,"account"=>'jamse4 ',"name"=>"尼龙4 ","level"=>124 ,"paynumber"=>24 ,"diamond"=>24,"gold"=>50004 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.3 '  ,"totalTime"=>"25544"],
			["id"=>5 ,"account"=>'jamse5 ',"name"=>"尼龙5 ","level"=>125 ,"paynumber"=>25 ,"diamond"=>25,"gold"=>50005 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.4 '  ,"totalTime"=>"25544"],
			["id"=>6 ,"account"=>'jamse6 ',"name"=>"尼龙6 ","level"=>126 ,"paynumber"=>26 ,"diamond"=>26,"gold"=>50006 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.5 '  ,"totalTime"=>"25544"],
			["id"=>7 ,"account"=>'jamse7 ',"name"=>"尼龙7 ","level"=>127 ,"paynumber"=>27 ,"diamond"=>27,"gold"=>50007 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.6 '  ,"totalTime"=>"25544"],
			["id"=>8 ,"account"=>'jamse8 ',"name"=>"尼龙8 ","level"=>128 ,"paynumber"=>28 ,"diamond"=>28,"gold"=>50008 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.7 '  ,"totalTime"=>"25544"],
			["id"=>9 ,"account"=>'jamse9 ',"name"=>"尼龙9 ","level"=>129 ,"paynumber"=>29 ,"diamond"=>29,"gold"=>50009 ,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.8 '  ,"totalTime"=>"25544"],
			["id"=>10,"account"=>'jamse10',"name"=>"尼龙10","level"=>1210,"paynumber"=>210,"diamond"=>2100,"gold"=>500010,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.9 ',"totalTime"=>"25544"],
			["id"=>11,"account"=>'jamse11',"name"=>"尼龙11","level"=>1211,"paynumber"=>211,"diamond"=>2110,"gold"=>500011,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.10',"totalTime"=>"25544"],
			["id"=>12,"account"=>'jamse12',"name"=>"尼龙12","level"=>1212,"paynumber"=>212,"diamond"=>2120,"gold"=>1,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.11',"totalTime"=>"25544"],
			["id"=>13,"account"=>'jamse13',"name"=>"尼龙13","level"=>1213,"paynumber"=>213,"diamond"=>556,"gold"=>11,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.12',"totalTime"=>"25544"],
			["id"=>14,"account"=>'jamse14',"name"=>"尼龙14","level"=>1214,"paynumber"=>214,"diamond"=>44,"gold"=>21,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.13',"totalTime"=>"2"],
			["id"=>15,"account"=>'jamse15',"name"=>"尼龙15","level"=>1215,"paynumber"=>215,"diamond"=>4,"gold"=>222,"loginTime"=>"2016-07-18","onlinetime"=>'180','1080',"loginIp"=>'192.0.1.14',"totalTime"=>"1"]
			);   
			 
			$_SESSION['onlineroleData'] = $dataOut;
			}
			if ($_SESSION['onlineroleData'])
			{
				$total = count($_SESSION['onlineroleData']);
				 
				$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total,
			    $page,$pagesize));
				
				$info = $this->page_array($pagesize,$page,
				$_SESSION['onlineroleData'],"{$sequence}","desc");
				// get 日志
				$data = array
				(
					'request'=>true,
					'pagehtml'=>$pagehtml,
					'object'=>$info,
					'total'=>$total
				); 
			}
		
		$this->load_view("stat_online_role",$data);
	} */
	
	/** 
	* @global 在线信息
	* @method currentFighting 当前战斗
	**/
	/* public function currentFighting()
	{   
		$data = array(
			"id"       =>1,
			"startTime"=>4545454,
			"mode"     =>4454,
			"userName1"=>123,
			"userNum1" =>123,
			"userName2"=>123,
			"userNum2" =>123,
			"userName3"=>123,
			"userNum3" =>123,
			"allname"  =>123			
		);
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
			
		$pagesize = 10;
			
		$server = !empty($_POST['server'])
		?
		$_POST['server']:'';
			
		$sequence = $_POST['sequence'];
			
		if(isset($_POST['sut']))
		{
			// <<<<<<<<<<<<<<<<<<<<<<<<<<
			$dataOut=array(
				["id"=>1,"startTime"=> '2016/5/6',"mode"=>"尼龙1 ","userName1"=>'asdasd' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>2,"startTime"=> '2016/5/6',"mode"=>"尼龙2 ","userName1"=>'dfgh' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>3,"startTime"=> '2016/5/6',"mode"=>"尼龙3 ","userName1"=>'asda' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>4,"startTime"=> '2016/5/6',"mode"=>"尼龙4 ","userName1"=>'gggh' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>5,"startTime"=> '2016/5/6',"mode"=>"尼龙5 ","userName1"=>'qwdeqa' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>6,"startTime"=> '2016/5/6',"mode"=>"尼龙6 ","userName1"=>'gbhnf' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>7,"startTime"=> '2016/5/6',"mode"=>"尼龙7 ","userName1"=>'asda' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>8,"startTime"=> '2016/5/6',"mode"=>"尼龙8 ","userName1"=>'gdfshf' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>9,"startTime"=> '2016/5/6',"mode"=>"尼龙9 ","userName1"=>'asdg' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>10,"startTime"=>'2016/5/6',"mode"=>"尼龙10 ","userName1"=>'asg' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>11,"startTime"=>'2016/5/6',"mode"=>"尼龙11 ","userName1"=>'ggg' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
				["id"=>12,"startTime"=>'2016/5/6',"mode"=>"尼龙12 ","userName1"=>'asd' ,"userNum1"=>21 ,"userName2"=>21,"userNum2"=>50001 ,"userName3"=>"2016-07-18","userNum3"=>'180',"allname"=>'asdasd'],
			);
			$_SESSION['onlineFightingData'] = $dataOut;
		}
		if ($_SESSION['onlineFightingData'])
		{
			$total = count($_SESSION['onlineFightingData']);
				
			$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total,
					$page,$pagesize));
		
			$info = $this->page_array($pagesize,
			$page,$_SESSION['onlineFightingData'],"startTime","desc");
			// get 日志
			$data = array
			(
				'request'=>true,
				'pagehtml'=>$pagehtml,
				'object'=>$info,
				'total'=>$total
			);
		}
		
		$this->load_view("stat_current_fighting",$data);
	} 
		 */ 
	
	/**
	 * @global  综合数据统计 日常数据
	 * @method dailyOnlineRole 综合数据统计
	 **/
/* 	public function complexDistributed()
	{		
		$data = array();
		
		$server =$_POST['platfrominfo'];		
		$startTime = $_POST['startTime'];		
		$endTime =$_POST['endTime'];
		
		if (!empty($server) && 
		!empty($startTime) && !empty($endTime))
		{
			 $datetime  = $this->jet_lag_day($startTime,$endTime,30);
			
			 $data['datetim'] = $datetime;			 
			 $serverOut = explode(",", $server); 
			 
			 $errorinfo = ''; 
			 $indata = array();
			 
			 foreach ($serverOut as $inserver)
			 {
			 	 $jsonOut = array();
				 //参数组装
				 $jsonOut = $this->RequestHeader(7788, $server,
				 array('time'=>$startTime,'endTime'=>$endTime) );
				 
				 $url = $jsonOut['url'];
				 $postdata = $jsonOut['postdata'];
				 //$ret[] = $this->send_request($url, $postdata);
				 $ret = '';
				 
				 if ( !empty($ret) )
				 {
				 	foreach ($ret as $va)
				 	{
				 		if (isset($va['status']) && $va['status'] ==0){				 			 
				 			$indata[] = $va;
				 			break;
				 		}else 
				 		{
				 			$keystatus  = $this->keystatus( 
				 			$jsonOut,$inserver."服获取失败");
				 			
				 			$inerror[$inserver] = $keystatus;
				 			break;
				 		}
				 	}
				 }
				 else
				 {
				 	$errorinfo .= $inserver."服未响应|";
				 }				 
			 }
			 $data['object'] = !empty($indata)?$indata:'';
			  
			 if ( !empty($inerror) && count($inerror)>0 ){
			 	
			 	foreach ( $inerror as $key=>$error ){
			 		
			 		$errorinfo .=$error.",";
			 		
			 		__log_message("complexDistributed:".$errorinfo ); 
			 	} 
			 }
			 if ( !empty($errorinfo) ){
			 	__log_message("complexDistributed:". $errorinfo );
			 	$this->prompt2( $errorinfo,"off" );
			 }
		} 
		
		$this->load_view("stat_complex",$data);
	}  */
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	// 水果机日常数据统计 index	
	/*public function fruitDaily(){
		
		$data = array();
		$starttime = $_POST['startTime'];
		$endtime = $_POST['endTime'];
		
		$checkbox = $_POST['channel'];
		/*****
		*统计时间 OR 渠道*
		*注册人数*
		*loading页*
		*新增登录大厅*
		*新增操作人数*
		*新增进场人数*
		*新增游戏人数*
		*新增重复游戏*
		*新增退出人数*
		*进场人数（初级）*
		*进场人数（中级）*
		*进场人数（高级）*
		*游戏人数*
		*重复游戏*
		*退出人数*
		*总游戏局数*
		*****/
			
		//$this->load_view("stat_fruit_daily",$data);
	//}	
	// 水果机注册登陆留存
	/* public function fruitOnlineRetained(){		
		$data = array();
		// 留存率计算公式例次日 第一天新增用户中，
		// 在往后的第3天还有登录的用户数）/第一天新增总用户数,以此类推
		 
		$this->load_view("stat_fruit_online_retained",$data);		
	} */
	
	//   水果机基础日报
	/* public function fruitBasisDaily(){
		
		$data = array();
		 
		$this->load_view("stat_fruit_basis_daily",$data);
	} */
	/*
	 * 水果机日常报表
	 *  
	public function fruitDailyReport(){
		
		$data = array();
		
		$startTime = $_POST['startTime'];
		$endTime = $_POST['endTime'];
		
		$this->load_view("fruit/stat_fruit_daily_report",$data);
	}
	
	// 水果机 基础统计basis
	public function  fruitBasis(){
		
		$data = array();
		
		$this->load_view("fruit/stat_fruit_basis",$data);
	}
	// 水果机 新增用户ltv
	public function fruitNewUserltv(){
		
		$data = array();
		
		$this->load_view("fruit/stat_fruit_newuser_ltv",$data);
	}
	// 水果机游戏-玩家列表
	public function fruitGamUserlist(){
		
		$data = array();
		
		$this->load_view("fruit/stat_fruit_GamUserlist",$data);
	}	
	// 水果机游戏-红包列表
	public function fruitGamRed(){
		
		$data = array();
		
		$this->load_view("fruit/stat_fruit_Gamred",$data);		
	}
	// 水果机游戏-充值排行
	 public function fruitPayRanking(){
		$data = array();
		
		$this->load_view("fruit/stat_fruit_payranking",$data);		
	}
	 
	// 日常图表
	 public  function fruitChartDaily(){
	
		$data = array();
	
		$conn = Platfrom_Service::getServer(true,'admin');
			
		$statdata_Model = new Statdata_Model($conn);
	
		$data['object'] = $statdata_Model->Stat_fruit_chart_daily($conn);
	
		$this->load_view("stat_fruit_chart_daily",$data);
	}
	// 图表-活跃用户
	public function fruitChartOnline(){
		
		$this->load_view("stat_fruit_chart_daily",$data);
	}
	// 图表-用户分布
	// 图表-用户场次分布
	// 水果机奖品兑换
	public function fruitPrizeExchange(){
		$data = array();
		
		$this->load_view("stat_fruit_prize_exchange",$data);
	}
	
	//public function 
	//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	public function niuNiuIndex(){
		$data = array();
		
		$conn = Platfrom_Service::getServer(true,'admin');
			
		$statdata_Model = new Statdata_Model($conn);
		
		$data['object'] = $statdata_Model->Stat_fruit_chart_daily($conn);
		
		$this->load_view("niuniu/stat_index",$data);
	}*/
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	/**
	 *留存统计
	 * 
	 ***/
}





