<?php
/*
 * 用户信息相关
 */
class Account_Controller extends Module_Lib {

	// 入口
    public function index()
    {
     	$data = array();    	
	    $sid = $_POST['sid'];
	 	$userid = $_POST['userid'];	
	 	$nikeName = $_POST['name'];
	 	$platId = (int)$_POST['platId'];
	 	$roleinfo = array();
	 	$platConfig =array();
	 	//echo $nikeName;
	 	if (isset($_POST['sut']))
	 	{
	 		// 如果昵称不为空
	 		if (!empty($nikeName))
	 		{ 
	 			$AllplatformInfo = session::get('AllplatformInfo');
	 			 
	 			foreach ($AllplatformInfo as $var)
	 			{
	 				if ((int)$var['platformId']==$platId && (int)$var['type']==0)	
	 				{
	 					$platConfig = $var; 
	 				}
	 			}
	 			$accountModel = new Account_Model();	 			
	 			$inplayer = $accountModel->getRoleIndex($platConfig,$nikeName);
	 			$userid = $inplayer['player_id'];	 			
	 		}
	 		  
		 	$request = array('ServerId'=>$sid,'RoleIndex'=>$userid);
		 	// PJSERVERMANAGER
		 	$inHeader = $this->VerifyToken( $request,NULL,'AskRoleInfo');
		 	
		 	$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
		 	 
		 	$roleinfo = json_decode(trim($ret),true);
		 	//var_dump($roleinfo);
		 	// 获取配置文件
		 	$Config = new  Config_Model();
		 	 
		 	if ( isset($roleinfo['status']) && $roleinfo['status']==0)
		 	{
		 		$data['status'] = 0;
		 		$data['roleInfo'] = $roleinfo;
		 		$data['faceConfig'] = $Config->getfaceConfig();
		 		$data['equipConfig'] =$Config->getequipConfig();
		 		$data['itemConfig'] = $Config->getItemconfig();
		 		$data['skillConfig'] = $Config->getSkillconfig();
		 		 
		 		$dupConfig = $Config->getdupconfig();
		 		$dupOut = array();
		 		foreach ($dupConfig as $var){
		 			if (empty($var)){continue;}
		 			$dupOut[$var['id']] = $var['name']; 
		 		}
		 		$data['dupConfig'] = $dupOut;
		 		
		 		//$data['chapConfig'] = $Config->getChapconfig();
		 		$chapConfig = $Config->getChapconfig();
		 		$chapOut = array();
		 		foreach ($chapConfig as $var){
		 			if (empty($var)){continue;}
		 			$chapOut[$var['id']] = $var['name'];
		 		}
		 		$data['chapConfig'] = $chapOut;
		 		$status = 0;
		 		$data['response'] = '';
		 	}  
		 	else{
		 		$data['status'] = isset($roleinfo['status'])?$roleinfo['status']:-1;  
		 		$data['response'] = $roleinfo;
		 	}
		 	$data['server'] = $sid;
		 	$_SESSION['data'] = $data;
		 	log_message(1001, $request, $data['response'],$data['status']);
	 	}
	 	if (isset($_SESSION['data']))
	 	{
	 		$data = $_SESSION['data'];
	 	}
    	$this->load_view('gm/account', $data);
	}
	// 关闭引导
	public function roleCoseGuide(){
		
		$code = 'CloseForceGuide';
		
		$ServerId = $_POST['ServerId'];
		$PlayerId = $_POST['PlayerId'];
		
		if (!empty($ServerId) && !empty($PlayerId))
		{
			$ReqdataOut =
			[
				'ServerId'=>(int)$ServerId,
				'PlayerId'=>(string)$PlayerId
			];
			$roleCoseGuide = $this->send($ReqdataOut,$code,$ReqdataOut['ServerId']);
				
			if ($roleCoseGuide==true)
			{
				 $this->outputJson(-1,'引导关闭成功'); 
			}
			$this->outputJson(-1,'引导关闭失败');
		} 
	}
	function is_json($string) {  
		//json_decode($string);
		return is_null(json_decode($string));
		//return (json_last_error() == JSON_ERROR_NONE);
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
	
	/**
	 * 货币更新
	 * @param type 1-金币 2-彩糖 3-药丸 4-vip等级
	 * @param Server
	 * **/
	public function editmoney ()
	{
		
		$prepare = array();
		$indata = '';
		$datOut = array();
		$jsonOut = array();
		
		$indata = file_get_contents("php://input");
		
		$datOut = explode("&", $indata);
	
		foreach ($datOut  as $var)
		{
			$datOut = explode("=", $var);
			$Inrequest[$datOut[0]] = $datOut[1];
		} 
		$server = $Inrequest['server'];
		//重新更新数据
		$inHeader = $this->RequestHeader(1001, 
		$server,array("playerId"=>$Inrequest['userid']));
		
		$inret['roleInfo'] = $this->send_request( 
		$inHeader['url'], $inHeader['postdata'] );
		$retOut = json_decode($inret['role'],true);
		
		//$data = 
		// 钻石
		if($Inrequest['status'] == 1)
		{   
			$userid = $Inrequest['userid'];
			$dimond = $Inrequest['diamond'];
			
			$code = $Inrequest['diamondType'];
			
			$prepare = array(
				"playerId"=>$Inrequest['userid'],
				"moneyNum"=>$Inrequest['diamond'],					
			);
			 
			$inHeader = $this->RequestHeader($code, $server,$prepare);
			
			$url = $inHeader['url'];
			$postdata = $inHeader['postdata'];
			// 发送服务器请求更新数据
			$ret = $this->send_request($url, $postdata);
			if($ret){ 
				
				$jsonOut = json_decode($ret,true);
				if(isset($jsonOut['status']) && $jsonOut['status'] == 0){
					 unset($jsonOut['status']);
					 $data = $jsonOut;
					 
					 $userdata = $this->repeatgetData( 
					 array("playerId"=>$Inrequest['userid']),$server );
					 
					 if (!empty($userdata))
					 {
					 	unset($_SESSION['userData']['roleInfo']);
					 	$_SESSION['userData']['roleInfo']= $userdata;
					 }
					   
					 $this->outputJson(0,"更新成功!");
				}else {
					
					$this->outputJson(-1,$this->keystatus($jsonOut,"更新失败!"));
				}
			} 
			
		}
		//金币
		if($Inrequest['status'] == 2)
		{
			$type = $Inrequest['goldType'];
			
			$prepare = array(
				"playerId"=>$Inrequest['userid'],
				"moneyNum"=>$Inrequest['gold'],					
			); 
			
			$inHeader = $this->RequestHeader($type, $server,$prepare);
			
			$url = $inHeader['url'];
			$postdata = $inHeader['postdata'];
			
			$ret = $this->send_request($url, $postdata);
			if($ret){
				
				$jsonOut = json_decode($ret,true);
				if(isset($jsonOut['status']) && $jsonOut['status'] == 0){
					 unset($jsonOut['status']);
					 $data = $jsonOut;
					 
					 $userdata = $this->repeatgetData( 
					 array("playerId"=>$Inrequest['userid']),$server);
					 
					 if (!empty($userdata))
					 {
					 	unset($_SESSION['userData']['roleInfo']);
					 	$_SESSION['userData']['roleInfo']= $userdata;
					 }
					 
					 $this->outputJson(0,"更新成功!");
				}else {
					
					$this->outputJson(-1,$this->keystatus($jsonOut,"更新失败!"));
				}
			} 
				
		} 	
	} 
	// 复述更新
	public function repeatgetData($data,$server,$code=1001)
	{		
		//重新更新数据
		$inHeader = $this->RequestHeader($code, $server,$data);
		$ret = $this->send_request( $inHeader['url'], $inHeader['postdata'] );
		 
		$retOut = json_decode($ret,true);
		
		$datainfo = '';
		
		if (isset($retOut['status']) && $retOut['status']==0 )
		{
			 unset($retOut['status']);
			  $datainfo = $retOut;			
		} 
		return $datainfo;
	}
	// 道具更改
	public function editProps(){
		
		$post= $this->get_contents();
		
		$code = $post['propsType'];
		$server = $post['server'];
		 
		$postData = array(
			"itemId"=>$post['propsid'],
			"playerId"=>$post['userid'],
			"itemNum"=>$post['propsNum'],					
		);		
		$inHeader = $this->RequestHeader($code, $server,$postData);
		
		$url = $inHeader['url'];
		$indata = $inHeader['postdata'];
		// send post data 
		$ret = $this->send_request($url, $indata);
		
		if($ret){		
			$jsonOut = json_decode($ret,true);
			if(isset($jsonOut['status']) && $jsonOut['status'] == 0){
				unset($jsonOut['status']);
				$data = $jsonOut;
				$this->outputJson(0,"更新成功!");
			}else {
					
				$this->outputJson(-1,$this->keystatus($jsonOut,"更新失败!"));
			}
		}
	}
	 
	public function roleOnlineStatusVerif()
	{
		$code = 'AskRoleInfo';
		
		$ServerId = $_POST['ServerId'];
		$PlayerId = $_POST['PlayerId'];
		
		if (!empty($ServerId) && !empty($PlayerId))
		{
			$ReqdataOut =
			[
			'ServerId'=>$ServerId,
			'RoleIndex'=>$PlayerId				
			];
			$roleList = $this->send($ReqdataOut,$code,$ReqdataOut['ServerId'],true);
			
			if (count($roleList)>0 && !empty($roleList))
			{
				$ifOnline = isset($roleList['data']['memory_info']['is_on_enter_game'])?1:0;
				
				if ($ifOnline==0)
				{
					$rs['errcode'] = 0;
			    	$rs['msg'] = '';
			    	$rs['ifonline'] =$ifOnline;  
			    	$rs['playerName']=$roleList['data']['db_card_info']['show_info']['nick_name'];
			    	echo json_encode($rs);
			    	exit;
				}
				if ($ifOnline==1)
				{
					$rs['errcode'] = -1;
			    	$rs['msg'] = '玩家在线不可进行更改,请离线重试!';
			    	$rs['ifonline'] =$ifOnline;
			    	$rs['playerName']=$roleList['data']['db_card_info']['show_info']['nick_name'];
			    	echo json_encode($rs);
			    	exit;
				}
			}
		}
		$rs['errcode'] =-1;
		$rs['msg'] = '角色在线状态验证失败!';
		 	
	}
	// 玩家信息变更
	public function editRoleInfo(){
	
		$post= $this->get_contents();
		
		$ServerId = empty($_POST['ServerId'])?NULL:$_POST['ServerId'];
		 
		if(empty($_POST['RoleAccount']))
		{
			$this->outputJson(-1, '账号不能为空！');
		}
		if(empty($_POST['PlayerId']))
		{
			$this->outputJson(-1, '角色Id不能为空！');
		}
		if (empty($ServerId)){
			$this->outputJson(-1,'区服Id不能为空!');			
		}
		 
		// 等级
		$uplevel = empty($_POST['uplevel'])?-1:$_POST['uplevel'];		
		// 体力
		$Strength = empty($_POST['strength'])?-1:$_POST['strength'];
		// 金币
		$gold = empty($_POST['gold'])?-1:$_POST['gold'];		
		// 彩糖
		$sugar = empty($_POST['sugar'])?-1:$_POST['sugar'];
		
		$postData = array
		(
			"ServerId"=>$ServerId,
			"RoleAccount"=>$_POST['RoleAccount'],
			"PlayerId"=>$_POST['PlayerId'],
			"level"=>$uplevel,
			"Gold"=>$gold,
			"Strength"=>$Strength,
			"Sugar"=>$sugar,
		);
		
		//__log_message("server :::".json_encode($postData),'account-editLevel');
		
		$inHeader =  $this->VerifyToken($postData,null,'UpdateRoleInfo');
		
		$code = 'UpdateRoleInfo';
		 
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk'); 
		
		//__log_message("server :::".$ret,'account');
		$retOut = json_decode(trim($ret),true); 
		
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			log_message(20001, $postData,$retOut, 0);
			$this->outputJson(0, '变更成功');			
		}
		log_message(20001, $postData,$retOut, -20001);
		$this->outputJson(0, '变更失败');
		 
	}
	//体力变更
	public function editBodyStrength(){
	
		$post= $this->get_contents();
	
		 
		if(empty($_POST['RoleAccount']))
		{
			$this->outputJson(-1, '账号不能为空！');
		}
		if(empty($_POST['PlayerId']))
		{
			$this->outputJson(-1, '角色Id不能为空！');
		}
		if(empty($_POST['strength']))
		{
			$this->outputJson(-1, '体力不能为空！');
		}
	
		$postData = array(
			"ServerId"=>1,
			"RoleAccount"=>$_POST['RoleAccount'],
			"PlayerId"=>$_POST['PlayerId'],
			"Strength"=>$_POST['strength'],
			"level"=>-1
		);
	 
		$inHeader =  $this->VerifyToken($postData,null,'UpdateRoleInfo');
	
		$code = 'UpdateRoleInfo';
			
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
	 
		$retOut = json_decode(trim($ret),true);
	
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			log_message(20001, $postData,$retOut, 0);
			$this->outputJson(0, '变更成功');
		}
		log_message(20001, $postData,$retOut, -20001);
		$this->outputJson(0, '变更失败'); 
	} 
	
	// 提出下线
	public function kickedOut(){
		
		$postData = file_get_contents("php://input"); 
		 
		__log_message($postData);
		$dataOut = json_decode($postData,true);
		
		$code = 1002;
		$server = $dataOut['server'];
		$userId = $dataOut['userid'];
		
		$inHeader = $this->RequestHeader( $code, $server, array('playerId' => $userId) );
		$url = $inHeader['url'];
		$indata = $inHeader['postdata'];
			
		$ret = $this->send_request($url, $indata);
		
		if($ret)
		{
			$jsonOut = json_decode($ret,true);
			if(isset($jsonOut['status']) && $jsonOut['status'] == 0){
				unset($jsonOut['status']);
				$data = $jsonOut;
				$userdata = $this->repeatgetData(array("playerId"=>$Inrequest['userid']),$server);
				if (!empty($userdata))
				{
					unset($_SESSION['userData']['roleInfo']);
					$_SESSION['userData']['roleInfo']= $userdata;
				}				
				$this->outputJson(0,"成功踢出下线!");
			}else {
					
				$this->outputJson(-1,$this->keystatus($jsonOut,"踢出下线失败!"));
			}
		}
		
	}
}

















