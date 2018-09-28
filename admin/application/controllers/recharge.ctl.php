<?php

class Recharge_Controller extends Module_Lib 
{ 
    /* index */
	public function Index($data=array())
	{    
         $this->load_view("recharge",$data);
	} 
	
    /* 订单信息/补单 */
	public function setpay()
	{
		__log_message(' recharge ... ','pay-log');
		
		//var_dump($_POST);
		
		$data = array();
		
		$page = empty($_GET['p']) ? 1 : $_GET['p']; 
        	
		$pagesize = 50;
		// 平台Id
		$platId = !empty($_POST['platId']) 
		? 
		' platId= ' .(int)$_POST['platId']:NULL;
		
		// 区服Id
		$serverId = !empty($_POST['sid']) 
		? 
		' server_id ='.(int)$_POST['sid']:NULL;
		
		// 开始时间		
		$startTime = !empty($_POST['startTime']) 
		? 
		" DATE(createtime)>=DATE('".$_POST['startTime']."')":"";		
		// 截止时间
		$endtime = !empty($_POST['endTime']) 
		? 
		" DATE(createtime)<=DATE('".$_POST['endTime']."')":"";
		 
		// 联运订单
		$orderid = !empty($_POST['orderid']) 
		? 
		" tcd ='".$_POST['orderid']."'":NULL;
		// UID
		$uid = !empty($_POST['uid'])
		?
		" uid ='".$_POST['uid']."'":NULL;
		
		// 角色ID
		$RoleIndex = !empty($_POST['roleid'])
		?
		" RoleIndex ='".$_POST['roleid']."'":NULL;
		
		$nick_name = $_POST['name'];
		
		if ($nick_name)
		{	
			$AllplatformInfo = session::get('AllplatformInfo');
			
			foreach ($AllplatformInfo as $var)
			{
				if ((int)$var['platformId'] == $platId 
				&& (int)$var['type']==0)
				{
					$platConfig = $var;
				}
			}
			$accountModel = new Account_Model();
			
			$inplayer = $accountModel->getRoleIndex(
			$platConfig,$nick_name);
			$RoleIndex = " RoleIndex  = '".$inplayer."'";
		}
		
		// 订单状态 
		$orderstau = !empty($_POST['orderstau'])
		?
		" status in (".$_POST['orderstau'].")" : NULL;
		 
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		__log_message(' recharge ... 1','pay-log');
		if (isset($_POST['sub_btn']))
		{
			__log_message(' recharge ... 2','pay-log');
			$havingAry = [
			$platId,$serverId,
			$startTime,$endtime,
			$orderid,$uid,
			$RoleIndex,$orderstau];
			
			$indata =NULL;
			
			if( count( $havingAry )>0 )
			{
				foreach ($havingAry as $var)
				{
					if(empty($var)){continue;}
			
					if(!$indata)
					{			
						$indata.= " WHERE ".$var;
					}
					else
					{
						$indata .= " AND ".$var;
					}
				}
			}
			
			//var_dump($havingAry);
			
			$total = 
			Recharge_Model::Stat_RechargeTotal($platfrom,$indata);
			
			$_SESSION['payhaving'] = $indata;
			$_SESSION['paytotal'] = $total['cont'];			
		}
		if ($_SESSION['paytotal'] && $_SESSION['payhaving'])
		{
			$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml(
			$_SESSION['paytotal'],
			$page,$pagesize));
		
			$list = Recharge_Model::Stat_RechargeInfo(
			$platfrom,$_SESSION['payhaving'],$page,$pagesize);
		
			$data = array
			(
				'pagehtml'=>$pagehtml,
				'object'=>$list,
			);
		}
		$this->load_view("recharge",$data);	 
	}	
	
	/* HAVING COMBINATION */
	public function termCombination($havingAry=array(),$field="")
	{	
	  	if(!empty($field))
	  	{
	  		$havingAry[] = "{$field} = 0"; 
	  	}	  	
		foreach($havingAry as $var)
	  		{ 
	  			if(!$var){
	  				continue;
	  			}
	  			 
	  			$having .= isset($having)
	  			?
	  			" AND ".$var
	  			:
	  			" WHERE ".$var; 
	  		}		  		
	  	 
	  		return $having;
	}
	/* termArrange */
	public function termArrange($param,  $field ,$Identifiers=',',
	$Operato='=',$des='',$symbol =false )
	{
	    !empty($des)
		?
		empty($param)
		?
		$this->prompt("{$des} "."不能为空!")
		:"":"";
		 
		$param = (!empty($param)&& $symbol==true)
		?			
		"'{$param}'":$param;
		
		if(strpos($param,",")>1)
		{
			$dataOut = count(explode($Identifiers,trim( $param )));
		
			if($dataOut == 1)
			{
				$datainfo = " {$field} = ".$param;
			}
			elseif ($dataOut>1)
			{
				$datainfo = " {$field} in (".$param .")";
			}
			else
			{
				$datainfo = '';
			}
			return $datainfo;
		}
		
		$datainfo = !empty($param)?" {$field} {$Operato} ".$param:"";
		
		return $datainfo; 		
	}
	
	/* 文件导出  */
	public function ExportfileIndex()
	{
	 
		$type = isset($_POST['type'])?$_POST['type']:0;
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
	 	
	 	$fileName = $this->output_file($time,$type,$data,'',$sid);
	 	
	 	if($fileName)
	 	{	 	
	 		$page = Config::get("common.page");
	 		$acction = $page['host'].'/statfile/';	 
	 		header("location:".$acction.$fileName); 
	 		$this->load_view("recharge",$data);
	 	} 	 
	
	}
	 /* 订单类型变更 */
	public function EditOrderType()
	{
		$id = $_POST['id'];
		$orderType = $_POST['OrderType'];
		$orderDesc = $_POST['orderDesc'];
		$conn = Platfrom_Service::getServer(true,'recharge');
		$data = array('payType'=>$orderType,"orderDesc"=>$orderDesc);
		$ret = Recharge_Model::EditOrderType($conn,$id,$data);
		
		if($ret)
		{
			$this->outputJson(0,'成功!');
		}
		$this->outputJson(-1,'失败!');
	}	
	
	/* 补单 */
	public function EditOrder()
	{
		 // 唯一ID
		 $id = $_POST['id'];
		 // 平台
		 $platId = $_POST['platId']; 
		 // serverid
		 $server_id = $_POST['server_id'];
		 // role index
		 $RoleIndex = $_POST['RoleIndex'];
		 // 档位
		 $cbi = $_POST['cbi'];
		 // uid
		 $uid = $_POST['uid'];
		 // 帮帮糖
		 $fee= $_POST['fee'];
		 // ssid
		 $ssid = $_POST['ssid'];
		 // 联运订单
		 $tcd = $_POST['tcd']; 	 
		 //描述
		 $description = $_POST['description'];
		 
		 $data = [
	 		'platId'=>$platId,
	 		'server_id'=>$server_id,
	 		'RoleIndex'=>$RoleIndex,
	 		'cbi'=>$cbi,
	 		'uid'=>$uid,
	 		'fee'=>$fee,
	 		'ssid'=>$ssid,
	 		'tcd'=>$tcd,	 		
		 ];
		 
		 $code = 'SaveMoneryInform';
		 
		 $inHeader =  $this->VerifyToken($data,NULL,$code,$server_id);
		 
		 $ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk'); 
		 
		 $retOut = json_decode(trim($ret),true);
		 
		 $conn = Platfrom_Service::getServer(true,'globaldb');
		 
		 if ( isset($retOut['status']) && $retOut['status']==0)
		 { 
		 	log_message(20001, $data,$retOut, 0);
		 	 
		 	Recharge_Model::EditOrder(
		 	$conn,$id,['status'=>4,'description'=>$description]);
		 	$this->outputJson(0, '补单成功');
		 } 
		 
		 log_message(-20001, $data,$retOut, 0);
		 
		 Recharge_Model::EditOrder(
		 $conn,$id,['status'=>5,'description'=>$description]);
		 $this->outputJson(-1,'补单失败');
		 
	}
	
}
























