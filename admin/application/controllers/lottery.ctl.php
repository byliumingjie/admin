<?php

/*
 * 用户信息相关
 */

class Lottery_Controller extends Module_Lib {

	// 入口
    public function index()
    { 
    	$data = array();
    	/* $_SESSION['LoteryStatus'] =null;
    	$_SESSION['Loteryprompt'] =null; */
    	/* if (isset($_COOKIE['LoteryStatus']) && !empty($_COOKIE['Loteryprompt']))
    	{
    		__log_message('LoteryStatus:::'.$_SESSION['LoteryStatus'],'lotery');
    		__log_message('Loteryprompt:::'.$_SESSION['Loteryprompt'],'lotery');
    		$Loteryprompt = $_COOKIE['Loteryprompt'];
    		 
    		$this->prompt2("{$Loteryprompt}","on");
    		unset($_COOKIE['Loteryprompt']);
    		unset($_COOKIE['LoteryStatus']);	    		 
    		unset($Loteryprompt);
    		 
    		//$this->prompt2($_SESSION['Loteryprompt'],'off');
    	} */
    	$type = (int)$_POST['type'];
    	$server = $_POST['server'];
    	/* 
    	if (isset($_GET['indata']) && !empty($_GET['indata']))
    	{
    		$dataOut = json_decode($_GET['indata'],true);
    		
    		$this->prompt2("{$dataOut['Loteryprompt']}","on");
    		
    		$type = (int)$dataOut['LoteryType'];
    		$server = $dataOut['Loteryserver'];
    	}  */
    	
    	/* if (!empty($_COOKIE['LoteryType']) && !empty($_COOKIE['Loteryserver']))
    	{
    		__log_message('LoteryType:::'.$_COOKIE['LoteryType'],'lotery');
    		__log_message('Loteryserver:::'.$_COOKIE['Loteryserver'],'lotery');
    		$type = (int)$_COOKIE['LoteryType'];
    		$server = $_COOKIE['Loteryserver'];    		
    	} */
    	
    	__log_message('监听type:::'.$type);
    	__log_message('监听server:::'.$server);
    	// 通讯获取数据    	
    	if(!empty($type) && !empty($server))
    	{
    		
    		//$type = (int)$_POST['type'];
    		//$server = $_POST['server'];
    		
	    	$dataOut = array
			(
				'type'=>$type,								 
			);			 
			$Headers = $this->RequestHeader(6002,$server,$dataOut);
			$url = $Headers['url'];
			$postdata = $Headers['postdata'];
			 
			$ret = $this->send_request($url, $postdata); //正式测试进行开启改代码格式			 
			$nowtime = time();	
			if($ret)
			{
				// 释放session
				if ($_GET['indata'])
				{
					unset($_GET['indata']);
					//unset($_SESSION['Loteryserver']);					 
				}
				$jsonOut = json_decode($ret,true);				
				  
				if(isset($jsonOut['status']) && $jsonOut['status']==0){
					$data = ["object"=>$jsonOut];
					$data['servers']= $server;  					 
					$this->load_view('dev/lottery', $data);
					exit();
				}else {
					$this->prompt2($this->keystatus($jsonOut,"查询失败!".$jsonOut['status']),"off"); 
				}
			}else{
				$this->prompt2("服务器未响应","off");
			}
    		 
    	}
    	
    	$this->load_view('dev/lottery', $data);
	}
	// 
	public function setLottery()
	{
		$server = (!empty($_POST['server']) && is_numeric($_POST['server']))
		?
		$_POST['server']:$this->prompt2("请输入区服","off");
		 
		$card_id = (!empty($_POST['card_id']) && is_numeric($_POST['card_id']))
		?$_POST['card_id']:
		$this->prompt2("抽卡ID不能为空或必须为有效的整形");
		
		$type = $_POST['type']==0
		?
		$this->prompt2("请输入抽奖类型","off"):$_POST['type'];
		 
		$operate_type = (isset($_POST['operate_type']))
		?
		$_POST['operate_type']:$this->prompt2("请选择开启状态","off");
		
		if(!empty($_POST['timeType']))
		{
			$timeType = $_POST['timeType'];
			
			if($timeType == 1)
			{
				$time = 0;				
			}
			else
			{			
				$startTime = empty($_POST['startTime'])			?
				$this->prompt2("开始日期不能为空","off"):$_POST['startTime'];		
				
				$time = (time()>strtotime($startTime))
				?
				$this->prompt2("开始时间必须大于当前时间!","off"):strtotime($startTime);
			}
		} 
		$data = array
		(
			'type'=>$type,
			'operate_type'=>$operate_type,
			'card_id'=>$card_id,
			'time'=>$time,
		);
		 
		$Headers = $this->RequestHeader(6001,$server,$data);
		$url = $Headers['url'];
		$postdata = $Headers['postdata'];
		
		$ret = $this->send_request($url, $postdata);
		 
		if($ret)
		{
			$jsonOut = json_decode($ret,true); 
			 
			if(isset($jsonOut['status']) && $jsonOut['status']==0){
					
				$this->prompt2("发送成功!","on");
			}else {
				$this->prompt2($this->keystatus($jsonOut,"发送失败!".$jsonOut['status']),"off"); 
			}
		}
		
		$this->load_view('dev/lottery', $data);
	}
	 //活动创建
	 public function createActivity()
	 {
	 	 
	 	 // 区服ID
	 	 $server = 
	 	 ( !empty( $_POST['server'] ) && is_numeric( $_POST['server'] ) )
	 	 ?
	 	 (int)$_POST['server']
	 	 :$this->outputJson(-1,"区服id必须是一个有效的整形并且不能为空!");
	 	 
	 	 // 活动ID
	 	 $cardId = (!empty($_POST['card_id']) && is_numeric($_POST['card_id']))
	 	 ?
	 	 (int)$_POST['card_id']
	 	 :$this->outputJson(-1,"活动Id必须是一个有效的整形并且不能为空!");
	 	
	 	 
	 	 // 抽奖类型  1.限时 2.周活动 3.常驻遗迹 4.常驻王座
	 	 $type = !empty($_POST['type']) ? (int)$_POST['type']
	 	 :$this->outputJson(-1,"抽卡类型为空!");
	 	 
	 	 
	 	 $startTime = 0;
	 	 $endtime = 0;
	 	 $operate_type = 0 ;
	 	 $id = ($type==3 || $type==4)?-1:0;
	 	 
	 	 // 如果创建的是限时活动
	 	 if( $type == 1 )
	 	 {
	 	 	// 1开启 0 关闭
	 	 	/* $operate_type = !empty($_POST['operate_type'])
	 	 	?
	 	 	$_POST['operate_type']
	 	 	:
	 	 	$this->prompt2("请设置是否开启状态!","off"); */	 	 	
	 	 	
	 	 	// 是否立即执行 0 立即执行 2指定时间点koki
	 	 	$timeType = $_POST['timeType']!=0
	 	 	?
	 	 	$_POST['timeType']:$this->outputJson(-1,"是否立即执行不能为空!");
	 	 	
	 	 	$startTime =$timeType ==0 ?0:0;
	 	 	 
	 	 	if($timeType==2)
	 	 	{
	 	 		// 开始时间
	 	 		$startTime = ( 
	 	 		!empty($_POST['startTime']) && strtotime($_POST['startTime'])>time()) 
	 	 		? 
	 	 		strtotime($_POST['startTime']) 
	 	 		: 
	 	 		$this->outputJson(-1,"开始时间必须大于当前时间切不能为空!");
	 	 		// 结束时间
	 	 		$endtime = (
	 	 		!empty($_POST['endtime']) && strtotime($_POST['endtime'])>time()
	 	 		&& strtotime($_POST['endtime']) > $startTime)
	 	 		?
	 	 		strtotime($_POST['endtime'])
	 	 		:
	 	 		$this->outputJson(-1,"结束时间必须大于开始时间切不能为空!");	 	 		
	 	 		
	 	 	} 
	 	 	if($timeType == 1){
	 	 		// 结束时间
	 	 		$endtime = (
 	 				!empty($_POST['endtime']) && strtotime($_POST['endtime'])>time()
 	 				&& strtotime($_POST['endtime']) > $startTime)
 	 				?
 	 				strtotime($_POST['endtime'])
 	 				:
 	 				$this->outputJson(-1,"结束时间必须大于开始时间切不能为空!");
	 	 	}
	 	 }
	 	 if( $type == 2 )
	 	 {
	 	 	// 开始时间
	 	 	$startTime = !empty($_POST['startTime'])
	 	 	?
	 	 	strtotime($_POST['startTime'])
	 	 	:
	 	 	$this->outputJson(-1,"开始时间不能为空!");
	 	 } 
	 	 __log_message("create end time .".$endtime);
	 	 $data = array
	 	 (
	 	 	'id'=>$id,
	 	 	'type'=>$type,
 	 		'operateType'=>$operate_type,
 	 		'cardId'=>$cardId,
 	 		'time'=>$startTime,
 	 		'endTime'=>$endtime,
	 	 );
	 	 
	 	 $jsonOut = $this->RequestHeader(6001, $server,$data);	 	 
	 	 $url = $jsonOut['url'];
	 	 $postdata = $jsonOut['postdata'];
	 	 
	 	  __log_message("activi postData-".$postdata);
	 	  
	 	 $ret = $this->send_request($url, $postdata);
	 	// $_SESSION['LoteryType']=$type;
	 	// $_SESSION['Loteryserver']=$server;
	 	 
	 	 
	 	 if ($ret){
	 	 	$jsonOut = json_decode($ret,true);
	 	 	
	 	 	if(isset($jsonOut['status']) && $jsonOut['status']==0)
	 	 	{
	 	 		$this->outputJson(0,"创建成功");
	 	 	}else{
	 	 		$this->outputJson(-1,"创建成功");	 	 		
	 	 	} 
	 	 }else{
	 	 	$this->outputJson(-1,"服务器未响应!");
	 	 }
		/* $this->responseCheck($ret,
	 	"创建成功!","创建失败",
	 	"header","lottery/index?indata=",
	 	$type,$server);  */
	 } 
	 
	 // 活动编辑
	 public function editActivity()
	 { 
	 	 
	 	$server = (!empty($_POST['server']) && is_numeric($_POST['server']))
	 	?
	 	(int)$_POST['server']:$this->outputJson(-1,"请输入区服");
	 		
	 	$card_id = (!empty($_POST['card_id']) && is_numeric($_POST['card_id']))
	 	?(int)$_POST['card_id']:
	 	$this->outputJson(-1,"抽卡ID不能为空,且必须为有效的整形");
	 	
	 	$type = $_POST['type']==0
	 	?
	 	$this->outputJson(-1,"请输入抽奖类型"):(int)$_POST['type'];
	 	
	 	$id=(!empty($_POST['id']) && is_numeric($_POST['id']))
	 	?(int)$_POST['id']:
	 	$this->outputJson(-1,"流水号不能为空,且必须为有效的整形");
	 	 
	 	$status=(!empty($_POST['status']) && is_numeric($_POST['status']))
	 	?(int)$_POST['status']:
	 	$this->outputJson(-1,"状态不能为空,且必须为有效的整形");
	 	
	 	$typeStr = $_POST['typeStr'];
	 	$statusStr = $_POST['statusStr'];
	 	
	 	$startTime = 0 ;	 	
	 	$endtime = 0 ;
	 	
	 	// 如果是限时更改
	 	if( $type == 1 )
	 	{ 
	 		if( $status == 1 )
	 		{	
	 			// 是否立即执行 0 立即执行 2指定时间点koki
	 			$timeType = $_POST['uptimetype']!=0
	 			?
	 			(int)$_POST['uptimetype']:$this->outputJson(-1,"是否立即执行不能为空!");
	 			// 开启限时活动	 			
		 	 	if ( $timeType==2 )
		 	 	{
		 	 	// 开始时间
		 	 	$startTime = (
	 	 			!empty($_POST['startTime']) && strtotime($_POST['startTime'])>time())
	 	 			?
	 	 			strtotime($_POST['startTime'])
	 	 			:
	 	 			$this->outputJson(-1,"开始时间必须大于当前时间切不能为空!");
		 	 	// 结束时间
		 	 	$endtime = (
	 	 			!empty($_POST['endtime']) && strtotime($_POST['endtime'])>time()
	 	 			&& strtotime($_POST['endtime']) > $startTime)
	 	 			?
	 	 			strtotime($_POST['endtime'])
	 	 			:
	 	 			$this->outputJson(-1,"结束时间必须大于开始时间切不能为空!");			 	 	
	 			}
	 			
	 			if($timeType == 1){
	 				// 结束时间
	 				$endtime = (
	 						!empty($_POST['endtime']) && strtotime($_POST['endtime'])>time()
	 						&& strtotime($_POST['endtime']) > $startTime)
	 						?
	 						strtotime($_POST['endtime'])
	 						:
	 						$this->outputJson(-1,"结束时间必须大于开始时间切不能为空!");
	 			}
	 			
	 		}
	 		if( $status == 0)
	 		{
	 			$startTime = time();//
	 		}
	 		//<<<<<<<<<<<<<<<<<<<<<<<
	 	}
	 	if($type == 2){	 		
	 		// 开始时间
	 		$startTime = (
 				!empty($_POST['startTime']) &&  strtotime($_POST['startTime'])>time())
 				?
 				strtotime($_POST['startTime'])
 				:
 				$this->outputJson(-1,"开始时间必须大于当前时间切不能为空!");
	 	}
	 	 $data = array
	 	 (
	 	 	'id'=>$id,
	 	 	'type'=>$type,
 	 		'operateType'=>$status,
 	 		'cardId'=>$card_id,
 	 		'startTime'=>$startTime,
 	 		'endTime'=>$endtime,
	 	 );
	 	  
	 	 $jsonOut = $this->RequestHeader(6001, $server,$data);
	 	 $url = $jsonOut['url'];
	 	 $postdata = $jsonOut['postdata']; 
	 	 
	 	 __log_message("activi postData-".$postdata);
	 	 	
	 	 $ret = $this->send_request($url, $postdata);
	 	 
	 	 switch ($type){
	 	 	case 1:$typeStr = '限时活动';break;
	 	 	case 2:$typeStr = '每周活动';break;
	 	 	case 3:$typeStr = '常驻遗迹';break;
	 	 	case 4:$typeStr = '常驻王座';break;
	 	 	default:$typeStr= '未知的类型';break;	 	 	
	 	 }
	 	 switch ($status){
	 	 	case 0:$statusStr = '创建';break;
	 	 	case 1:$statusStr = '开启';break;	 	 	
	 	 	case 2:$statusStr = '关闭';break;
	 	 	case 3:$statusStr = '编辑';break; 
	 	 	default:$statusStr= '未知操作';break;
	 	 }	 	 
	 	 $_SESSION['LoteryType']=$type;
	 	 $_SESSION['Loteryserver']=$server;
	 	 /*$_SESSION['LoteryStatus'] = 0;
				$_SESSION['Loteryprompt'] = $success;*/
	 	 $data = array(
	 	 	'LoteryType'=>	$type,
	 	 	'Loteryserver'=>$server,
	 	 	'LoteryStatus'=>$_SESSION['LoteryStatus'],
	 	 	'Loteryprompt'=>$_SESSION['Loteryprompt'],
	 	 );
	 	 $jsonOutstr = json_encode($data);
	 	 
	 	 if($ret){
	 	 	$jsonOut = json_decode($ret,true);
	 	 	
	 	 	if(isset($jsonOut['status']) && $jsonOut['status']==0)
			{
				$this->outputJson(0,"编辑成功!");
			}
			else
			{
				$keystatus  = $this->keystatus($jsonOut,"编辑失败");				 
				$this->outputJson(-1,"{$keystatus}");				
			} 
	 	 }else{
	 	 	$this->outputJson(-1,"服务器未响应!");
	 	 }
	 	
	 	 
	 	/*  $this->responseCheck(
 	 		$ret,$typeStr.$statusStr."成功!",
 	 		"设置".$typeStr.$statusStr."失败",
 	 		"header",
 	 		"lottery/index?indata=",$type,$server ); */
	 	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	 }
}

















