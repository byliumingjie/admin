<?php

/*
 * 用户信息相关
 */

class Mail_Controller extends Module_Lib {

	public function __construct()
	{
		parent::__construct();
		$this->mode = new  Mail_Model();
	}	
	// 入口
    public function index()
    {	 
    	$this->load_view('mail');
	}
	// get post input data 
	public function get_contents(){
		$indata = '';
		$datOut = array();
		$Inrequest = array();
		
		$indata = file_get_contents("php://input");
		//__log_message("php://input:::".$indata);
		$datOut = explode("&", $indata);
		
		foreach ($datOut  as $var)
		{
			$datOut = explode("=", $var);
			$Inrequest[$datOut[0]] = $datOut[1];
		}
		return $Inrequest;
	}
	public function showmail() 
	{
		$data = array();
		$platformOut = session::get('AllplatformInfo');
		$data['serverInfo'] = $platformOut;
		/*//获取服务器列表 检查 用户是否有目录下得访问的权限 checkPermission
		 $stServer =  Platform_Model::getPlatformByID($_COOKIE['zoneid']);
		$hefserver = Server_Service::gethefuServer($_COOKIE['zoneid'], $stServer);
		$servers = Server_Service::getAllServers($_COOKIE['zoneid'], $stServer);
		$senddata = array(
				'servers'=>$servers,
				'hefuserver'=>$hefserver,
				'addPerMail'=>$this->checkPermission('mail/addPerMail'),
				'addSerMail'=>$this->checkPermission('mail/addSerMail'),
				'addAllMail'=>$this->checkPermission('mail/addAllMail'),
				'addUser'=>$this->checkPermission('mail/addUser'),
				'delMail'=>$this->checkPermission('mail/delMail'), 
		);*/
		$Config = new  Config_Model();
		// 表情包配置
		$faceConfig = $Config->getfaceConfig();
		// 装备盘配置
		$equipConfig =$Config->getequipConfig();
		// 道具配置
		$itemConfig = $Config->getItemconfig();
		// 技能配置
		$skillConfig = $Config->getSkillconfig();
		// 套装配置
		$suitConfig = $Config->getConfig('tb_suit_config');
		$data['configinfo'] = array
		(
			"faceConfig"=>$faceConfig,
			"equipConfig"=>$equipConfig,
			"skillConfig"=>$skillConfig,
			"suitConfig"=>$suitConfig,
			"itemConfig"=>$itemConfig	
		);
		// 关卡 章节
		/* $data['dupConfig']  = $Config->getdupconfig();
		 $data['chapConfig'] = $Config->getChapconfig(); */
		
		$this->load_view('createmail',$data);
	}
	/**
	 * 邮件管理
	 * **/
	public function mail_manage()
	{
		$data = array();
		
		//$platformOut = session::get('AllplatformInfo');
		//$data['serverInfo'] = $platformOut;
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		$pagesize = 20; 
		
		$ReimburseType = '1,2';
		
		if (isset($_POST['btn_sub'])){
			 
			
			$createTime = empty($_POST['createTime'])
			?
			NULL
			:
			' createtime>'."'".$_POST['createTime']."'";
			
			$ServerId = empty($_POST['ServerId'])
			?
			NULL
			:
			' ServerId = '."'".(int)$_POST['ServerId']."'";
			
			$endtime = empty($_POST['endtime'])
			?
			NULL
			:
			'createtime<'."'".$_POST['endtime']."'";
		
			$sender = !empty($_POST['account'])
			?
			" sender = "."'".$_POST['account']."'"
			:
			NULL; 
    		
			$awhere = [$ServerId,$sender,$createTime,$endtime];
		
			foreach ($awhere as $var)
			{
				if(empty($var)){continue;}
		
				if(!$indata){
		
					$indata.= " where ".$var;
				}else{
					$indata .= " and ".$var;
				}
			}
			 
			$_SESSION['mailWhere'] = $indata;
			// 发布中
			$releasetotal = $this->mode->get_MailTotal('minosdb',$_SESSION['mailWhere'],true);
			
			//var_dump($releasetotal);
			
			$_SESSION['mailreleasetotal'] = (int)$releasetotal['cont'];
			// 失效
			$Failuretotal = $this->mode->get_MailTotal('minosdb',$_SESSION['mailWhere']);
			$_SESSION['mailfailuretotal'] = (int)$Failuretotal['cont'];
			
			$_SESSION['reimbursetwhere'] = ['ServerId'=>(int)$_POST['ServerId']];
			 
			// 补偿邮件
			$Reimbursetotal = $this->mode->get_MailTotal('minosdb',
			$_SESSION['reimbursetwhere'],NULL,$ReimburseType);
			
			$_SESSION['maireimbursetotal'] = (int)$Reimbursetotal['cont']; 
			
		}
		  
		
		if (isset($_SESSION['mailreleasetotal']) || isset($_SESSION['mailfailuretotal']) ||
		isset($_SESSION['maireimbursetotal']))
		{

			//echo 'mail where'.($_SESSION['mailWhere'])."<br>";
				
			//echo 'mail burse'.$_SESSION['reimbursetwhere']."<br>";
			
			// 发布中
			$releaselist = $this->mode->get_MailInfo('minosdb',
			$_SESSION['mailWhere'],$page,$pagesize,1);
			
			$mailreleasehtml= htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['mailreleasetotal'], $page, $pagesize));
			// 失效
			$failurelist = $this->mode->get_MailInfo('minosdb',
			$_SESSION['mailWhere'],$page,$pagesize,0);
			
			$mailfailurehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['mailfailuretotal'], $page, $pagesize));
			// 邮件补偿
			$Reimbursetlist = $this->mode->get_MailInfo('minosdb',
			$_SESSION['reimbursetwhere'],$page,$pagesize,null,$ReimburseType);
			
			$Reimbursethtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['maireimbursetotal'], $page, $pagesize));
			
			$data = array
			(
			'mailfailurelist'=>$failurelist,
			'mailfailurehtml'=>$mailfailurehtml,
			'mailreleaselist'=>$releaselist,
			'mailreleasehtml'=>$mailreleasehtml,
			'reimbursetlist'=>$Reimbursetlist,
			'reimbursethtml'=>$Reimbursethtml,
			);
		}  
		
		$this->load_view('mail_manage',$data);
	}
	/***
	 * 邮件撤回
	 * **/
	public function mail_retract()
	{			
		 $mialid = (int)$_POST['mailId'];
		 $ServerId =(int)$_POST['ServerId'];
		
		 if(empty($mialid)){			
			$this->outputJson(-1,'邮件ID不能为空!');
		 }
		 if(empty($ServerId)){				
			$this->outputJson(-1,'区服ID不能为空!');
		 }
		 $code = 'RevokeServerMail'; 
		 
		 $data = array(
		 'MailIndex'=>$mialid,
		 'ServerId'=>$ServerId,
		 );
		 __log_message("server mail json:".json_encode($data),"mail-log");
		 
		 $retractOut = ['Endtime'=>0,'MailTitle'=>'已撤回','MailText'=>'已撤回'];
		 
		 if($this->mode->edit_Mail($mialid,$retractOut))
		 { 
		 
			 $inHeader =  $this->VerifyToken($data,null,'RevokeServerMail');
			 $ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
			 	
			 $retOut = json_decode(trim($ret),true);
			 	
			 if (!$ret || !isset($retOut) || $retOut['status']!=0)
			 {	// 多个请求
			 $retError [$ServerId]= '区服为'.$ServerId.'执行失败'.$ret;
			 log_message(9001, $data,$retOut, -9001);
			 //一次性请求
			 //$this->outputJson(0, '保持失败！' );
			 }
			 else
			 { 
			 			 	
			 	log_message(9001, $data,$retOut, 0);
			 	$retSucce[$ServerId]=$ret;
			 }
		 }
		 else{
		 	
		 	$this->outputJson(0,'撤回失败！');
		 }
		
		if (count($retError)<=0){
		
			//log_message(5003,$requestDat,$retOut, 0);
		
			$this->outputJson(0,'发送成功  !完成个数'.count($retSucce));
		}
		else{
			foreach ($retError as $key=>$var)
			{
				$error .= $var.'---';
			}
		
			$this->outputJson(-1,'发送失败  !完成个数'.count($retSucce).' error'.$error);
		
		}
		
	}
	/**
	 * 创建邮件发送
	 * **/
	public function createMail()
	{ 
		$Inpropid = '';
		$checkboxOut = $_POST['checkbox'];
		if(!empty($checkboxOut))
		{			 
		  for ($i=0;$i<count($checkboxOut);$i++)
		  {   
			$propid = 'propid'.($i+1);
			$propnum = 'propnum'.($i+1);
				
			if ($checkboxOut[$i]==$propid)
			{ 
				if( empty($_POST[$propid]) || !is_numeric($_POST[$propid]) )
                {
                   $this->outputJson(-1, '请填写道具ID'.($i+1).'有效数额!');
                } 		
                if( empty($_POST[$propnum]) || !is_numeric($_POST[$propnum]) )
                {
                   $this->outputJson(-1, '请填写道具数量'.($i+1).'有效数额!');
                }  
			}				
			$Inpropid .=
			(!empty($_POST[$propid]) && !empty($_POST[$propnum]))
			?
			$_POST[$propid].'-'.$_POST[$propnum].',':'';						
		  } 
		}		
		$Inpropid = rtrim($Inpropid,',');
		
		$server = $_POST['server'];
		$userId = $_POST['roleid'];
		$title  =$_POST['mailtitle'];
		$context = $_POST['context'];
		
		$delayday =		
		(!empty($_POST['delayDeleteDay']) 
		&& is_numeric($_POST['delayDeleteDay']) )
		?
		$_POST['delayDeleteDay']:"";
		
		$data = array
		(
			'mailTitle'=>$title,
			'mailContent'=>$context,
			'attachments'=>$Inpropid,
			'delayDeleteDay'=>$delayday,
		);
		if($delayday=='' || $delayday==7)
		{
			unset($data['delayDeleteDay']);
		}
		$Headers = $this->RequestHeader(5001,$server,$data);
		$url = $Headers['url'];
		
		$postdata = $Headers['postdata'];
		
	 
		$ret = $this->send_request($url, $postdata);
		 
		 if($ret)
		{
			$jsonOut = json_decode($ret,true);
			if(isset($jsonOut['status']) && $jsonOut['status']==0){
				 
				$this->outputJson(0,"发送成功!");
			}else {
					
				$this->outputJson(-1,$this->keystatus($jsonOut,"发送失败!".$jsonOut['status']));
			}			
		}		  
	}

	/**
	 *UP 验证数值类型
	 *@param $key type string 通过表单click属性($key)
	 *@param $val type int 匹配输入的数值
	 *@param $prompt type string 提示信息
	 **/
	public function checkVerify($key,$val,$prompt)
	{
		if(empty($val)||!is_numeric($val))
		{
			$this->outputJson(-1, '请填写有效附件'.$prompt.'数额');
		}
		/* 
		// 后期如果要对道具进行上限审核$key 可以是id 或 name Key value 
		 if(! Mail_Service::CheckValue($key,$val))
		{
			$this->outputJson(-1, '附件'.$prompt.'数量超过设置上限');
		}  */  
		return  $val;
	}
	/**
	 * 遍历check 数据
	 * @param $checkbox type array
	 * @param $_POST type array 
	 * **/
	public function  attachmentConfig($checkbox)
	{ 
		if (count($_POST)<0 || empty($_POST) || !isset($_POST)){
			
			$this->outputJson(-1,'无效的post类型');
		}
		$indata = array();
		
		if(!empty($checkbox))
		{
			// case 后面值为表单的checkbox的值(checkbox[$i])，
			// 其通过表单click属性($key)匹配输入的值（$var）是否有效
			for($i = 0; $i < count($checkbox);++$i)
			{
				switch ($checkbox[$i])
				{						
				case 'coin':	// 金币					
					 $indata['coin'] = array(
					 'ItemId'=>1,
					 'ItemNumber'=>$this->checkVerify('coin',(int)$_POST['coin'],'金币')
					 ) 
					 ;break;	
				case 'pill':	// 药丸
					 $indata['pill'] = 
					 array(
					 'ItemId'=>2,
					 'ItemNumber'=>$this->checkVerify('pill',(int)$_POST['pill'], '药丸')
					 )
					 ;break;
				case 'sugar':	// 棒棒糖 
					 $indata['sugar'] =array(
					 'ItemId'=>3,
					 'ItemNumber'=>$this->checkVerify('sugar', (int)$_POST['sugar'], '棒棒糖')
					 ) 
					 ;break;
				case 'sword':	// 石中剑次数
					 $indata['sword'] = array(
					 'ItemId'=>8,
					 'ItemNumber'=>$this->checkVerify('sword', (int)$_POST['sword'], '石中剑') 
					 ) 
					 ;break;
				case 'sports':  // 竞技场次数
					 $indata['sports'] = array(
					 'ItemId'=>7,
					 'ItemNumber'=>$this->checkVerify('sports', (int)$_POST['sports'], '竞技点')
					 ) 
					 ;break;
				case 'exp':  // 经验
					 $indata['exp'] = array(
					 'ItemId'=>4,
					 'ItemNumber'=>$this->checkVerify('exp', (int)$_POST['exp'], '经验')
					 );
					 break;	
				//sportsIntegral
				case 'sportsIntegral':  //竞技场积分
				 	$indata['sportsIntegral'] = array(
				 	'ItemId'=>6,
				 	'ItemNumber'=>$this->checkVerify('sportsIntegral',
				 	(int)$_POST['sportsIntegral'], '竞技场积分')
				 	);
				 	break;
				case 'suitId':  
					 // 套装Id 套装数量
					 $indata['suit'] = array(
					 'ItemId'=>$this->checkVerify('suitId', (int)$_POST['suitId'], '套装Id'),
					 'ItemNumber'=>$this->checkVerify('suitnum', (int)$_POST['suitnum'], '套装数量')
					 ); 
					 break;	
				case 'equipid': 
					 // 装备Id 装备数量
					 $indata['equip'] = array(
					 'ItemId'=>$this->checkVerify('equipid', (int)$_POST['equipid'], '装备Id'),
					 'ItemNumber'=>$this->checkVerify('equipnum', (int)$_POST['equipnum'], '装备数量')
					 );  
					 break;	
					 //faceid
				 case 'faceid':
				 	// 表情Id 表情数量
				 	$indata['face'] = array(
				 	'ItemId'=>$this->checkVerify('faceid', (int)$_POST['faceid'], '表情Id'),
				 	'ItemNumber'=>$this->checkVerify('facenum', (int)$_POST['facenum'], '表情数量')
				 	);
				 	break;
				case 'propid1': 
					 // 道具1id
					$indata['propid1'] =array(
				 	'ItemId'=>$this->checkVerify('propid1', (int)$_POST['propid1'], '道具1Id'),
				 	'ItemNumber'=>$this->checkVerify('propnum1', (int)$_POST['propnum1'], '道具1数量')
				 	);
					break;				 
				case 'propid2': 
					$indata['propid2'] =array(
					'ItemId'=>$this->checkVerify('propid2', (int)$_POST['propid2'], '道具2Id'),
					'ItemNumber'=>$this->checkVerify('propnum2', (int)$_POST['propnum2'], '道具2数量')
					); 
					break; 
				case 'propid3':					
					$indata['propid3'] =array(
					'ItemId'=>$this->checkVerify('propid3', (int)$_POST['propid3'], '道具3Id'),
					'ItemNumber'=>$this->checkVerify('propnum3', (int)$_POST['propnum3'], '道具3数量')
					); 
					break; 
				case 'propid4':
					$indata['propid4'] =array(
					'ItemId'=>$this->checkVerify('propid4', (int)$_POST['propid4'], '道具4Id'),
					'ItemNumber'=>$this->checkVerify('propnum4', (int)$_POST['propnum4'], '道具4数量')
					); 
					break;
				case 'propid5':
					$indata['propid5'] =array(
					'ItemId'=>$this->checkVerify('propid5', (int)$_POST['propid5'], '道具5Id'),
					'ItemNumber'=>$this->checkVerify('propnum5', (int)$_POST['propnum5'], '道具5数量')
					);
					break;
				case 'propid6':
					// 道具1id
					$indata['propid6'] =array(
					'ItemId'=>$this->checkVerify('propid6', (int)$_POST['propid6'], '道具6Id'),
					'ItemNumber'=>$this->checkVerify('propnum6', (int)$_POST['propnum6'], '道具6数量')
					);
					break;
				case 'propid7':
					$indata['propid7'] =array(
					'ItemId'=>$this->checkVerify('propid7', (int)$_POST['propid7'], '道具7Id'),
					'ItemNumber'=>$this->checkVerify('propnum7', (int)$_POST['propnum7'], '道具7数量')
					);
					break;
				case 'propid8':
					$indata['propid8'] =array(
					'ItemId'=>$this->checkVerify('propid8', (int)$_POST['propid8'], '道具8Id'),
					'ItemNumber'=>$this->checkVerify('propnum8', (int)$_POST['propnum8'], '道具8数量')
					);
					break;
				case 'propid9':
					$indata['propid9'] =array(
					'ItemId'=>$this->checkVerify('propid9', (int)$_POST['propid9'], '道具9Id'),
					'ItemNumber'=>$this->checkVerify('propnum9', (int)$_POST['propnum9'], '道具9数量')
					);
					break;
				case 'propid10':
					$indata['propid10'] =array(
					'ItemId'=>$this->checkVerify('propid10', (int)$_POST['propid10'], '道具10Id'),
					'ItemNumber'=>$this->checkVerify('propnum10', (int)$_POST['propnum10'], '道具10数量')
					);
					break; 
				default: 
					 $this->outputJson(-1, '无效附件');
					 break;
				}				
			}
		}
		return $indata;
	} 
	/**
	 * 个人邮件
	 * **/ 
	public function addPerMail() {
	
		if(empty($_POST['sid']))
		{
			$this->outputJson(-1, '区服为空，请填写区服');
		}	
		if(empty($_POST['roleid']))
		{
			$this->outputJson(-1, '请检查是角色ID，或者为空');
		}
		/* if(empty($_POST['sendtime']))
		{
			$this->outputJson(-1, '请选择发送时间');
		}	 */	 
		$itemArrayList = array();
		if(empty($_POST['mailtitle']))
		{
			$this->outputJson(-1, '邮件标题不能为空');
		}
		if(empty($_POST['context']))
		{
			$this->outputJson(-1, '请填写邮件内容');
		}	
		$checkbox = $_POST['checkbox'];
		if(count($checkbox) >4 )
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		}		
		
		$indata = file_get_contents("php://input");
		 
		__log_message("maill :::".$indata,'mail');
		
		// all attachment type array
		if(empty($_POST['annex']))
		{
			$indataOut = $this->attachmentConfig($checkbox); 
			
			foreach ($indataOut as $var){
				
				$indataInfo[] = $var; 
			}
		}else{
			__log_message("annex ::".$_POST['annex'],"mail-log");
			
			$annex = explode("&",trim($_POST['annex']));
			
			foreach ($annex as $var )
			{
				$invarOut = explode(",",$var);
				
				if (!empty($invarOut[0]) && !empty($invarOut[1]))
				{
					$itemArrayList[] = ["ItemId"=>(int)$invarOut[0],"ItemNumber"=>(int)$invarOut[1]];
				}
				else
				{
					$this->outputJson(-1,'批量附件格式有误,请检查道具Id及数量为空');
				}
			}			
			 
		}
		if (count($itemArrayList)>0){
			
			$indataInfo = $itemArrayList;
		}
		// 协议码
		//$code = 111;		
		__log_message("maill :::".json_encode($indataInfo),'mail');
 
		$data = array(
		'MailTitle'=>$_POST['mailtitle'],
		'MailText'=>$_POST['context'],
		'RoleIndex'=>$_POST['roleid'],
		'ServerId'=>(int)$_POST['sid'],
		'ItemList'=>$indataInfo
		);
		 
		$inHeader =  $this->VerifyToken($data,null,'AskSendMail');
		
		$code = 'AskSendMail';
		
		//__log_message("maill :::".json_encode($data),'mail');
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk'); 
		
		//$err = $this->is_json(trim($ret));
		__log_message("server :::".$ret,'mail');
		$retOut = json_decode(trim($ret),true); 
		
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			if (!log_message(5001, $data,$retOut, 0)){
				$this->outputJson(-1,'日志记录失败');	
			}
			$this->outputJson(0, '保存成功');			
		}
		log_message(5001, $data,$retOut, -5001);
		$this->outputJson(0, '保存失败');
		// $headerOut = $this->RequestHeader($code, $_POST['sid'],$indataOut);		
				  
		/* 
		// 日志录入 
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台iplatform为设置
		$logdata = array(
				'f_platform'=>$iplatform,
				'f_account'=>$_SESSION['account'],
				'f_addtime'=>date("Y-m-d H:i:s", time()),
				'f_sid'=>$_POST['sid'],
				'f_ip'=>$stServer['sid_ip'],
		);
	
		$logtype = 'addPermail';
		$logParams = array('roleid'=>$_POST['roleid'],'title'=>$_POST['mailtitle']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
	 	*/
		 
		/* 
		 #### Send
		 try {
			//$ret = Mail_Service::addMail($data,$stServer);
			$ret = $this->send_request($headerOut['url'], $headerOut['postdata']);
			if (!$ret) {
				$this->outputJson(-2, '执行失败！');
			}
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		} */
		//log_message($code, $request, $response);
		
	} 
	/**
	 * 文件上传处理
	 * **/ 
	public function addUser() {
		if( $_FILES['myFile']['size']>2*1024*1024)
		{
			$this->outputJson(-1,"上传文件过大");
		}
	
		$pathname = $_FILES['myfile'];
		$filename = time(0)."_".$pathname['name']; 
		 
		$filepath = PROJECT_ROOT."www//tmp//".$filename;
		
		if(is_uploaded_file($pathname['tmp_name']))
		{
			if(!move_uploaded_file($pathname['tmp_name'],$filepath))//上传文件，成功返回true
			{
				$this->outputJson(-1,"上传失败");
			}
		}else{
			$this->outputJson(-1,"非法上传文件");
		}
	
		setcookie('filepath', $filepath,time()+60, '/');
	
		$this->outputJson(0,"上传成功");
	}
	/**
	 * 批量用户邮件
	 * **/
	public function addSerMail() {
	
		if(empty($_POST['sendtime']))
		{
			$this->outputJson(-1, '请选择发送时间');
		}
		if(empty($_POST['mailtitle']))
		{
			$this->outputJson(-1, '邮件标题不能为空');
		}
		if(empty($_POST['context']))
		{
			$this->outputJson(-1, '请填写邮件内容');
		} 
		$checkbox = $_POST['checkbox'];
		if(count($checkbox) >4 )
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		}
		// 获取用户信息
		$rolelist = $this->ReadXml();
			
		if(count($rolelist) ==0)
		{
			$this->outputJson(0, '导入用户失效，请重新导入！' );
		}
		
		// 获取附件表单数据
		$indataOut = $this->attachmentConfig($checkbox);
		// 建立请求数据
		foreach ($indataOut as $var){		
			$indataInfo[] = $var;		
		} 
		
		$requestDat= array(
			'MailTitle'=>$_POST['mailtitle'],
			'MailText'=>$_POST['context'], 
			'ItemList'=>$indataInfo
		); 
		
		$arrsid = array();
		$arrrole = array(); 
		
		$code = 'AskSendMail';
		$retError = array();
		$retSucce = array();
		
		for($i = 0;$i<count($rolelist);$i++)
		{
			$requestDat['ServerId'] = $rolelist[$i]['sid'];
			$requestDat['RoleIndex'] = $rolelist[$i]['roleid'];
			
			if ( !isset($requestDat['ServerId']) && !isset($requestDat['RoleIndex']) )
			{
				continue;
			}
			if ( empty($requestDat['ServerId']) && empty($requestDat['RoleIndex']) ){			 	
			 	continue;
			}  
			$inHeader = array();
			$ret=null;
			$retOut = array();
			
			$inHeader =  $this->VerifyToken($requestDat,'PJSERVERMANAGER','AskSendMail');
			//__log_message("maill :::".json_encode($data),'mail');
			$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');				
			$retOut = json_decode(trim($ret),true); 
			 
			if (!$ret || !isset($retOut) || $retOut['status']!=0)
			{	// 多个请求
				$retError [$i.$ret]= '区服为'.$rolelist[$i]['sid'].
				'角色Id'.$rolelist[$i]['roleid'].'失败';
				log_message(5002, $requestDat+$retError,$retOut, -5002);
			//一次性请求
			//$this->outputJson(0, '保持失败！' );
			}
			else 
			{
				$retSucce[$i]=$ret;
			}  
		}   
		
		
		if (count($retError)<=0){
			
			
			log_message(5002,$requestDat,$retOut, 0);
			
			$this->outputJson(0,'发送成功  !完成个数'.count($retSucce));
		}
		else{
			foreach ($retError as $key=>$var)
			{
				$error .= $var.'---';
			}
			
			$this->outputJson(-1,'发送失败  !完成个数'.count($retSucce).' error'.$error);
			
		}
		// 协议码
		/* $code = 111;
		$headerOut = $this->RequestHeader($code, $_POST['sid'],$indataOut);
		$retError = array();
		$ret = $this->send_request($headerOut['url'], $headerOut['postdata']);
		if (!$ret)
		{	// 多个请求
			$retError[] = '区服为'.$arrsid.'角色Id'.$arrrole.'失败'.''.$ret;
			//一次性请求
			//$this->outputJson(0, '保持失败！' );
		} */
		
		/* $logdata = array(
				'f_platform'=>$iplatform,
				'f_account'=>$_SESSION['account'],
				'f_addtime'=>date("Y-m-d H:i:s", time()),
				'f_sid'=>$sid,
				'f_ip'=>$stServer['sid_ip'],
		);
	
		$logtype = 'addSermail';
		$logParams = array('sid'=>implode(",", $arrsid),'title'=>$_POST['mailtitle']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams); */
	
		
		
		/* try {
			// $ret = Mail_Service::addMail($data,$stServer);
			// 
			$ret = $this->send_request($headerOut['url'], $headerOut['postdata']);
			if (!$ret) 
			{
				$retError[] = $ret;
				//$this->outputJson(0, '保持失败！' );
			}
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		} */
	
		//$this->outputJson(0, '保存成功');
	}
	
	/**
	 * 全服邮件
	 * **/
	public function addAllMail() {
	
		if(empty($_POST['sid']))
		{
			$this->outputJson(-1, '区服为空，请填写区服');
		}
		$ServerOut = $_POST['sid'];
		
		$checkbox = $_POST['checkbox'];
	
		/* if(!$this->CheckPropNum($checkbox))
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		} */
		$minlevel = empty($_POST['minlevel'])?0:$_POST['minlevel'];
		$maxlevel =empty($_POST['maxlevel'])?999:$_POST['maxlevel'];
		
		if (!empty($_POST['minlevel']) && !empty($_POST['maxlevel']))
		{
			if (!is_numeric($minlevel))
			{
				$this->outputJson(-1, '最小等级类型不是有效的整形,请重试！');
			}
			if (!is_numeric($maxlevel))
			{
				$this->outputJson(-1, '最大等级类型不是有效的整形,请重试！');
			}
		} 
		
		if(empty($_POST['starttime']))
		{
			$this->outputJson(-1, '请选择发送时间');
		} 
		if(empty($_POST['endtime']))
		{
			$this->outputJson(-1, '请选择结束时间');
		} 
		if(strtotime($_POST['endtime']) <= time(0))
		{
			$this->outputJson(-1, '结束时间不能小于当前时间');
		}
		if(empty($_POST['mailtitle']))
		{
			$this->outputJson(-1, '邮件标题不能为空');
		}
		if(empty($_POST['context']))
		{
			$this->outputJson(-1, '请填写邮件内容');
		} 
	 
		$checkbox = $_POST['checkbox'];
		
		if(count($checkbox) >4 )
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		}		
		$indataOut = $this->attachmentConfig($checkbox);
		
		foreach ($indataOut as $var){
				
			$indataInfo[] = $var;
				
		}
		if(count($ServerOut)>10)
		{
			$this->outputJson(-1, '区服超出上限最多保留10个');
			
		}
		/* __log_message("maile sid".json_encode($_POST['sid']).
				' indatainfo'.json_encode($indataInfo),'maile-log'); */
		$lastId = 0;
		foreach ($ServerOut as $inserver)
		{
			if(empty($inserver))
			{
				continue;
			}
			// 执行servers
			$inserver = (int)$inserver;
			$data[$inserver] = array
			(
				'Rules'=>array(
				'minLevel'=>$minlevel,
				'maxLevel'=>$maxlevel,
				'startTime'=>strtotime($_POST['starttime']),
				'endTime'=>strtotime($_POST['endtime']),
				),					
				'MailTitle'=>$_POST['mailtitle'],
				'MailText'=>$_POST['context'],				
				'ServerId'=>$inserver,
				'ItemList'=>$indataInfo
			);
			__log_message("mail data".json_encode($data),'mail-log');
			$setmaildata = array
			(
			'Rules'=>json_encode($data[$inserver]['Rules']),	
			'MailTitle'=>$data[$inserver]['MailTitle'],
			'MailText'=>$data[$inserver]['MailText'],
			'ServerId'=>$inserver,
			'ItemList'=>json_encode($indataInfo),
			'Createtime'=>date('Y-m-d H:i:s',time()),
			'Starttime'=>$data[$inserver]['Rules']['startTime'],
			'Endtime'=>$data[$inserver]['Rules']['endTime'],
			'Sender'=>$_SESSION['account']
			);
			__log_message("mail setmaildata".json_encode($setmaildata),'mail-log');
			// add_mail
			$lastId = $this->mode->add_mail($setmaildata);
			 
			if(!$lastId)
			{
				unset($data[$inserver]);
			}
			else
			{
				$data[$inserver]['MailIndex'] = $lastId;
			}
		}
		__log_message("mail data".json_encode($data),'mail-log');
		if(count($data)<=0)
		{
			$this->outputJson(-1,'发送失败，无效的数据类型请检查邮件索引获取是否异常！'.count($retSucce));
		}
		// 邮件发送
		$code = 'ServerMail';
		foreach ($data as $serverid=>$intoken){
			$instr = json_encode($intoken);
			__log_message("server mail json:".$instr,"mail-log");
			$inHeader =  $this->VerifyToken($intoken,null,'ServerMail');
			$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
			
			$retOut = json_decode(trim($ret),true);
			
			if (!$ret || !isset($retOut) || $retOut['status']!=0)
			{	// 多个请求
			$this->mode->closeMail($intoken['MailIndex']);
			$retError [$serverid]= '区服为'.$serverid.'执行失败'.$ret;
			log_message(5003, $intoken,$retOut, -5003);
			//一次性请求
			//$this->outputJson(0, '保持失败！' );
			}
			else
			{
				log_message(5003, $intoken,$retOut, 0);
				$retSucce[$serverid]=$ret;
			} 
		}
		
		 if (count($retError)<=0){ 
				
			//log_message(5003,$requestDat,$retOut, 0);
				
			$this->outputJson(0,'发送成功  !完成个数'.count($retSucce));
		}
		else{
			foreach ($retError as $key=>$var)
			{
				$error .= $var.'---';
			}
				
			$this->outputJson(-1,'发送失败  !完成个数'.count($retSucce).' error'.$error);
				
		}
	}
	/**
	 * 补偿邮件
	 * **/
	public function addReimburseMail() {
	
		if(empty($_POST['sid']))
		{
			$this->outputJson(-1, '区服为空，请填写区服');
		}
		$ServerOut = $_POST['sid'];
	
		$checkbox = $_POST['checkbox'];	
		 
		/* if(empty($_POST['starttime']))
		{
			$this->outputJson(-1, '请选择发送时间');
		}
		if(empty($_POST['endtime']))
		{
			$this->outputJson(-1, '请选择结束时间');
		}
		if(strtotime($_POST['endtime']) <= time(0))
		{
			$this->outputJson(-1, '结束时间不能小于当前时间');
		} */
		$starttime = time();
		$endtime = 2128657149;
		
		if(empty($_POST['mailtitle']))
		{
			$this->outputJson(-1, '邮件标题不能为空');
		}
		if(empty($_POST['context']))
		{
			$this->outputJson(-1, '请填写邮件内容');
		}
		 
		// 补偿类型HeadreviewType
		if(empty($_POST['HeadreviewType']))
		{
			$this->outputJson(-1, '请填写补偿类型内容');
		}
		$ReimburseType = $_POST['HeadreviewType'];
		
		$checkbox = $_POST['checkbox'];
	
		if(count($checkbox) >4 )
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		} 
		
		$indataOut = $this->attachmentConfig($checkbox);
	
		foreach ($indataOut as $var){
	
			$indataInfo[] = $var;
	
		}
		if(count($ServerOut)>10)
		{
			$this->outputJson(-1, '区服超出上限最多保留10个');
				
		} 
		$dbname = 'minosdb';
		
		$lastId = 0;
		
		foreach ($ServerOut as $inserver)
		{
			if(empty($inserver))
			{
				continue;
			}		
			
			$inserver = (int)$inserver;
			$data[$inserver] = array
			(
				'MailTitle'=>$_POST['mailtitle'],
				'MailText'=>$_POST['context'],
				'ServerId'=>$inserver,
				'ItemList'=>$indataInfo,
				'ReimburseType'=>$ReimburseType
			);
			 
			$setmaildata = array
			(
				'Rules'=>NULL,
				'MailTitle'=>$data[$inserver]['MailTitle'],
				'MailText'=>$data[$inserver]['MailText'],
				'ServerId'=>$inserver,
				'ItemList'=>json_encode($indataInfo),
				'Createtime'=>date('Y-m-d H:i:s',time()),
				'Starttime'=>$starttime,
				'Endtime'=>$endtime,//
				'ReimburseType'=>$ReimburseType,
				'Sender'=>$_SESSION['account']
			);
			  
			// 邮件添加之前验证是否存在
			$Where = " WHERE ReimburseType={$ReimburseType} AND ServerId={$inserver}";
			
			$isReimburseTotal = $this->mode->get_MailTotal($dbname,$Where,NULL,TRUE);
			 
			__log_message('REIMBURSE TOTAL'.$isReimburseTotal['cont'].' where'.$Where.'
			server' . $inserver,'mail-log2');
			 
			if ((int)$isReimburseTotal['cont']<=0)
			{  
				$lastId = $this->mode->add_mail($setmaildata);
		
				if(!$lastId)
				{
					unset($data[$inserver]);
				}
				else
				{					
					$data[$inserver]['MailIndex'] = $lastId;
				} 
			}
			else
			{
				 $remimburseret = $this->remimburse_up($setmaildata);
			 	 
			 	 if (!$remimburseret)
			 	 {	
			 	 	//$Remimburseret[$inserver] = $inserver;
			 	 	unset($data[$inserver]);
			 	 	$retError[$inserver]=' 添加的区已存在，区服为'.$inserver.'
			 	 	进行更新时失败！';
			 	 } 
			}			
		}		 
		if(count($data)<=0)
		{
			$this->outputJson(-1,'发送失败，无效的数据类型请检查邮件索引获取是否异常！'
			.count($retSucce));
		}
		// 邮件发送 
		$code = 'ServerTempleteMail';
		
		foreach ($data as $serverid=>$intoken){
			$instr = json_encode($intoken);
			 
			$inHeader =  $this->VerifyToken($intoken,null,'ServerTempleteMail');
			$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
				
			$retOut = json_decode(trim($ret),true);
				
			if (!$ret || !isset($retOut) || $retOut['status']!=0)
			{	// 多个请求
				$this->mode->closeMail($intoken['MailIndex']);
				$retError [$serverid]= '区服为'.$serverid.'执行失败'.$ret;
				log_message(5010, $intoken,$retOut, -5010); 
			}
			else
			{ 
				log_message(5010, $intoken,$retOut, 0);
				$retSucce[$serverid]=$ret;
			}
		}
	
		if (count($retError)<=0){
			 
			$this->outputJson(0,'发送成功  !完成个数'.count($retSucce));
		}
		else
		{
			foreach ($retError as $key=>$var)
			{
				$error .= $var.'---';
			}			 
			$this->outputJson(-1,'发送失败  !完成个数'.count($retSucce).' error'.$error);	
		}
	}
	public function remimburse_up($data){
		
		$setmaildata = array
		(
			'MailTitle'=>$data['MailTitle'],
			'MailText'=>$data['MailText'],			
			'ItemList'=>$data['ItemList'],
			'Createtime'=>$data['Createtime'],
			'Starttime'=>$data['Starttime'],
			'Endtime'=>$data['Endtime'],//2128657149			
			'Sender'=>$data['Sender']
		); 
		 
		$ret = $this->mode->reimburse_mail_up($setmaildata,$data['ServerId'],
		$data['ReimburseType']);
		
		if(!$ret)
		{
			return false;
		}
		return true; 
	}
	/**
	 * 导入文本格式
	 * **/
	public function ReadXml() { 
		$filepath = Helper_Lib::getCookie('filepath');
		$ext = pathinfo($filepath, PATHINFO_EXTENSION | PATHINFO_FILENAME);
		// __log_message("file 格式：：：".$ext);
		if(trim($ext)!="txt" && trim($ext)!= "xml")
		{
			$this->outputJson(-1,"文件格式错误");
		} 
		if(trim($ext) == "txt" )
		{
			$file= file_get_contents($filepath);
			$filecontent = explode("\n", trim($file));
			$j = 0;
			for($i = 0 ;$i < count($filecontent); ++$i)
			{
				$tmp = explode(" ", trim($filecontent[$i]));
				if(count($tmp) == 2)
				{
					$data[$j]["id"]= $j;
					$data[$j]["sid"]=$tmp[0];
					$data[$j]["roleid"]=$tmp[1];
					$j++;
				} 
			}
		}
		if(trim($ext) == "xml")
		{
			$data = array(array());
			try {
				$lists = simplexml_load_file($filepath);  //载入xml文件 $lists和xml文件的根节点是一样的
			} catch (Exception $ex) {
				$this->outputJson(-1,"加载配置失败");
			}
	
			if(empty($lists))
			{
				$this->outputJson(-1,"读取配置失败");
			}
	
			$i = 0;
			foreach($lists->RoleConfig as $table){     //有多个user，取得的是数组，循环输出
				$data[$i]["id"]="$table->id";
				$data[$i]["sid"]="$table->sid";
				$data[$i]["roleid"]="$table->roleid";
				$i++;
			}
		}
		//删除文件
		@unlink($filepath);
		return $data;
	}	 
}

















