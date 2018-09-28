<?php

/**
跑马灯公告
**/
class Notice_Controller extends Module_Lib {
	
	private  $mode = null;
	public function __construct($conn='')
	{
		parent::__construct();
		$conn = Platfrom_Service::getServer(true,'globaldb');
		$this->mode = new  Notice_Model($conn);
	}
	
   /**
	* Index by liumingjie add 2015-08-19 入口函数加载视图
	* 此安全限制一定要非常非常的紧密
	**/
	public function  index()
	{	 	 
		$this->load_view("dev/notice");        
	} 
	
	public function  sendNotice()
	{
		$post= $this->get_contents();
		
		$code = 4001;
		$server = $post['server']; 
		$userId = $post['sendType']!=1?-1:$post['userid'];
		$postData = array(
			"message"=>$post['message'],
			"loopTimes"=>$post['loopTimes'],
			"playerId"=>$userId,
		);
		 
		$inHeader = $this->RequestHeader($code,$server,$postData);
	 
		$url = $inHeader['url'];
		$indata = $inHeader['postdata'];
		// send post data
		$ret = $this->send_request($url, $indata);
		 
		$data =array('status'=>'');
		
		if($ret){
			$jsonOut = json_decode($ret,true);
			$status = (isset($jsonOut['status']) && $jsonOut['status'] == 0)?"发布成功":"发布失败!";
			 
			if(isset($jsonOut['status']) && $jsonOut['status'] == 0){
				unset($jsonOut['status']);
				$data = $jsonOut;
				$this->prompt2("发布成功!","on");
			}else {
				$this->prompt2($this->keystatus($jsonOut,"发布失败!"),"off"); 
			}
		}	
		$this->load_view("dev/notice",$data);
	}
	
	/**
	 * 当前统计信息
	 */
	public function showNotice() 
	{ 
		$page = empty($_GET['p']) ? 1 : $_GET['p'];    	
    	$pagesize = 20;
    	
    	$data = array(); 
    	
    	if (isset($_POST['btn_sub']))
    	{ 
	        $createTime = empty($_POST['createTime'])?"": $_POST['createTime'];
	        $endtime = empty($_POST['endtime'])?"": $_POST['endtime'];        
	        $sender = !empty($_POST['account'])? $_POST['account']:"";
	        
	        $awhere = array(
	        	"createtime"=>$createTime,
	        	"endtime"=>$endtime, 
	        	"sender"=>$sender
	        );
	        $_SESSION['noticeWhere'] = $awhere;        
	        //失效 6
	        $Failuretotal = $this->mode->getNoticeTotal($_SESSION['noticeWhere']);        
	        $_SESSION['Failuretotal'] = $Failuretotal;
	        // 发布中 20 
	        $releasetotal = $this->mode->getNoticeTotal($_SESSION['noticeWhere'],1);
	        $_SESSION['releasetotal'] = $releasetotal;
    	}
    	
    	if (isset($_SESSION['Failuretotal']) && isset($_SESSION['releasetotal']) )
    	{
    		//失效 
	    	$FailureList = $this->mode->getNotice( 
	    	$_SESSION['noticeWhere'],$page,$pagesize);
	    	// 发布中
	    	$releaseList = $this->mode->getNotice( 
	    	$_SESSION['noticeWhere'],$page,$pagesize,1);    
	    	  
	    	// 失效6
	    	$Failurehtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($_SESSION['Failuretotal'], $page, $pagesize));
	    	 
	    	// 发布中20
	    	$releasehtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($_SESSION['releasetotal'], $page, $pagesize));
	    	 
	    	$data = array
	    	(
	    		'failurehtml'=>$Failurehtml,
	    		'failurelist'=>$FailureList,
	    		'releasehtml'=>$releasehtml,
	    		'releaseList' => $releaseList,
	    	);
    	} 
        $this->load_view('dev/notice_pass',$data);
	}
	 
	public function sumbitNotice() 
	{
		if(empty($_POST['sid']))
		{
			$this->outputJson(-1, '区服为空，请填写区服');
		}	
		$iplatform = Helper_Lib::getCookie("zoneid");
		
		if($iplatform == 0)
		{
			$this->outputJson(0, '平台验证出错！' );
		} 
		$data = array('tr_statu'=>"审核中",);
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置
	
		$ret = Notice_Service::UpdateNotice($data,$_POST['tr_id'],$stServer);
		if (!$ret) {
			$this->outputJson(-2, '数据库执行失败！' );
		}
	
		$this->outputJson(0, '提交成功');
	} 
	
	
	public function passNotice() {
		$createTime = empty($_GET['createTime'])?"": $_GET['createTime'];
		$endtime = empty($_GET['endtime'])?"": $_GET['endtime'];
		$account = empty($_GET['account'])?"": $_GET['account'];
		$platform = Helper_Lib::getCookie("zoneid");
		/* $stServer =  Platform_Model::getPlatformByID($platform);  //暂时平台id为设置
		if(empty($stServer))
		{
			echo '<script>alert("没有选择服务器！");</script>';
			header("Location:/gameuser/loadserverinfo");
			exit();
		}
		$awhere = array(
				"createtime"=>  strtotime($createTime),
				"endtime"=>strtotime($endtime),
				"account"=>$account,
		); */
		//        $page = empty($_GET['p']) ? 1 : $_GET['p'];
		//        $pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
		//        $total = Notice_Service::getNoticeTotal($platform,$stServer);
		//        $pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total, $page, $pagesize));
		/* $data = array(
				'servers'=>  Server_Service::getAllServers($platform, $stServer),
				//         'pagehtml'=>$pagehtml,
				'data' => Notice_Service::getNotice($platform,$awhere,$stServer,0, 0),
				'editNotice' => $this->checkPermission('notice/editNotice'),
				'delNotice' => $this->checkPermission('notice/delNotice'),
				'alterNotice' => $this->checkPermission('notice/alterNotice'),
				'lookNotice' => $this->checkPermission('notice/lookNotice'),
				'addServer' => $this->checkPermission('notice/addServer'),
	
		); */
		$this->load_view('dev/notice_pass',$data);
	}
	/**
	 * 添加走马灯
	 * **/
	public function addNotice()
	{
		/* if ($_POST['level'] != 0 && $_POST['level'] != -1 ) {
			$this->outputJson(-1, '选择播放等级');
		}
	 	*/
		if(strtotime($_POST['endtime']) <= time(0))
		{
			$this->outputJson(-1, '播放结束时间不能小于当前时间');
		} 
		if (empty($_POST['starttime'])) {
			$this->outputJson(-1, '请填写播放开始时间');
		}
		if (empty($_POST['endtime'])) {
			$this->outputJson(-1, '请填写播放结束时间');
		}
	
		/* if (empty($_POST['playnum'])) {
			$this->outputJson(-1, '请填写播放次数');
		}
	
		if ($_POST['looptime'] > 0 && $_POST['looptime'] < 10) {
			$this->outputJson(-1, '循环间隔小于10秒，请重新填写');
		} 
		if ($_POST['Intervaltime']>0 && $_POST['Intervaltime'] < 1) {
			$this->outputJson(-1, '循环间隔小于1秒，请重新填写');
		}
		*/
		if (empty($_POST['context'])) {
			$this->outputJson(-1, '请填写走马灯内容');
		}
		 
		
		if (empty($_POST['platformid']) || count($_POST['platformid'])<0){
			
			$this->outputJson(-1, '请填写区服');
		}	
		$ServerOut = $_POST['platformid'];
		
		/* if(count($ServerOut)>20)
		{
			$this->outputJson(-1, '区服超出上限最多保留20个');
				
		} */
		foreach ($ServerOut as $inserver)
		{
			if(empty($inserver))
			{
				continue;
			}
			$inserver = (int)$inserver;
			
			$data[$inserver] = array( 
			'starttime'=>strtotime($_POST['starttime']),
			'endtime'=>strtotime($_POST['endtime']), 
			'context'=>$_POST['context'],
			'sender'=>$_SESSION['account'],
			'Intervaltime'=>$_POST['Intervaltime'],
			'createtime'=>date('Y-m-d H:i:s',time()), 
			'ServerId'=>$inserver
			);
		}
		/*  for($i=0;$i<=100;$i++)
		 {
		 	$Indata= array(
	 			'startime'=>strtotime($_POST['starttime']),
	 			'endtime'=>strtotime($_POST['endtime']),
	 			'context'=>$_POST['context'],
	 			'sender'=>$_SESSION['account'],
	 			'createtime'=>date('Y-m-d H:i:s',time()),
	 			'Serverid'=>1
		 	);
		 	if($this->mode->insert($Indata))
		 	{
		 		if($i==100)
		 		{
		 			$this->outputJson(0, '完成'.$i);	
		 			break;
		 		}		 		
		 	}
		 }	 */
		//echo json_encode($data); 
		
		$code = 'ServerHorseLight';
		foreach ($data as $serverid=>$intoken)
		{ 
			$instr = json_encode($intoken);
			 
			$lastId = $this->mode->insert($intoken);
			if($lastId)
			{
				$intoken['MarqueeIndex']=$lastId;
				$inHeader =  $this->VerifyToken($intoken,null,'ServerHorseLight');
				
				$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
					
				$retOut = json_decode(trim($ret),true);
					
				if (!$ret || !isset($retOut) || $retOut['status']!=0)
				{	// 多个请求
					$retError [$serverid]= '区服为'.$serverid.'执行失败'.$ret;
					log_message(8001, $intoken,$retOut, -5003);
					//一次性请求
					//$this->outputJson(0, '保持失败！' );
				}
				else
				{
					log_message(8001, $intoken,$retOut, 0);
					$retSucce[$serverid]=$ret;
				} 
			}else 
			{
				$result = '区服为'.$serverid.'db执行失败';
				$inretinfo = array(
					'status'=>-1,
					'result'=>$result
				);
				$retError [$serverid]= $result;
				//$retSucce[$serverid]=json_encode($inretinfo);
				log_message(8001, $intoken,$inretinfo, -8001);
			}
		}
		
		if (count($retError)<=0){
		
			//log_message(5003,$requestDat,$retOut, 0);
		
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
		
		//$sid = implode(',', $_POST['platformid']);
		 
		/* $ret = $this->mode->insert($data);
		if (!$ret) {
			$this->oututJson(-2, '数据库执行失败！' );
		} */
		/* $logtype = 'addnotic';
		$logParams = array('context'=>$_POST['context']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
	
	
		try {
			$ret = Notice_Service::addNotice($data,$stServer);
			if (!$ret) {
				$this->outputJson(-2, '数据库执行失败！' );
			}
	
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		} */
	
		//$this->outputJson(0, '添加成功 ');
	}
	/**
	 * 走马灯撤销
	 * **/
	public  function notic_retract(){
		
		$Id = (int)$_POST['Id'];
		$ServerId =(int)$_POST['ServerId'];
		
		if(empty($Id)){
			$this->outputJson(-1,'走马灯ID不能为空!');
		}
		if(empty($ServerId)){
			$this->outputJson(-1,'区服ID不能为空!');
		}
		$code = 'RevokeServerHorseLight';
		$data = array(
			'MarqueeIndex'=>$Id,
			'ServerId'=>$ServerId,
		);
		__log_message("server mail json:".json_encode($data),"notic-log");
			
		$retractOut = ['endtime'=>0,'context'=>'已撤回'];
			
		if($this->mode->edit_notic($Id,$retractOut))
		{
				
			$inHeader =  $this->VerifyToken($data,null,'RevokeServerHorseLight');
			$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
				
			$retOut = json_decode(trim($ret),true);
				
			if (!$ret || !isset($retOut) || $retOut['status']!=0)
			{	// 多个请求
				$retError [$ServerId]= '区服为'.$ServerId.'执行失败'.$ret;
				log_message(10001, $data,$retOut, -10001);
				//一次性请求
				//$this->outputJson(0, '保持失败！' );
			}
			else
			{
					
				log_message(10001, $data,$retOut, 0);
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
	public function editNotice()
	{
		if(empty($_POST['sid']))
		{
			$this->outputJson(-1, '区服为空，请填写区服');
		}
	
		if (empty($_POST['starttime'])) {
			$this->outputJson(-1, '请重新选择时间');
		}
		if (empty($_POST['endtime'])) {
			$this->outputJson(-1, '请填写播放结束时间');
		}
	
		if (empty($_POST['playnum'])) {
			$this->outputJson(-1, '请填写播放次数');
		}
	
		if ($_POST['looptime'] > 0 && $_POST['looptime']< 10) {
			$this->outputJson(-1, '循环间隔小于10秒，请重新填写');
		}
	
		if (empty($_POST['context'])) {
			$this->outputJson(-1, '请填写公告内容');
		}
		if (empty($_POST['result'])) {
			$this->outputJson(-1, '请选择审核结果');
		}
		$iplatform = Helper_Lib::getCookie("zoneid");
		if($iplatform == 0)
		{
			$this->outputJson(0, '平台验证出错！' );
		}
	
		$data = array(
				'tr_platform'=>$iplatform,
				'tr_context' =>$_POST['context'],
				'tr_statu'=>($_POST['result']>1)?"未通过":"发布中",
				'tr_auther' => $_SESSION['account'],
				'tr_authtime' =>time(0),
				'tr_startime' => strtotime($_POST['starttime']),
				'tr_endtime' => strtotime($_POST['endtime']),
				'tr_trigtime' =>$_POST['playnum'],
				'tr_looptime' =>$_POST['looptime'],
				'tr_level'=>($_POST['level'] =='普通')? 0:-1,
		);
	
	
		 
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置
		$logdata = array(
				'f_platform'=>$iplatform,
				'f_account'=>$_SESSION['account'],
				'f_addtime'=>date("Y-m-d H:i:s", time()),
				'f_ip'=>$stServer['sid_ip'],
				'f_sid'=>$_POST['sid'],
		);
		$logtype = 'alternotic';
		$logParams = array('context'=>$_POST['context']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
	
		try {
	
			$ret = Notice_Service::UpdateNotice($data,$_POST['tr_id'],$stServer);
			if (!$ret) {
				$this->outputJson(-2, '数据库执行失败！' );
			}
	
			if($_POST['result'] == 1)
			{
				$sendData = array(
						'level'=>$data['tr_level'],
						'looptime'=>$data['tr_looptime'],
						'starttime'=>$data['tr_startime'],
						'endtime'=>$data['tr_endtime'],
						'trigtime'=>$data['tr_trigtime'],
						'context'=>$data['tr_context'],
						'account'=>$_SESSION['account'],
				);
				$stList = explode(',',$_POST['sid']);
				 
				for($i =0; $i< count($stList); ++$i)
				{
					$Server = Server_Service::getServerByPtAndId($iplatform,$stList[$i],$stServer);
					//                   if($Server['serverStatus'] != 2)//只对开服的服务器发送走马灯
					//                   {
					//                       continue;
					//                   }
	
					$result = Notice_Service::SendNoitce($sendData,$Server);
					if (!$result) {
						 
						$flag[$i] = $stList[$i];
					}
				}
			}
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		}
		if(count($flag) == 0 &&$_POST['result'] == 1)
		{
			$this->outputJson(0, '发布成功');
	
		}  else if($_POST['result'] != 1 && count($flag) == 0){
			$this->outputJson(0, '更新成功，未通过审核');
		}else {
			$this->outputJson(-2, '发布失败的服务器id'.  implode(',', $flag));
		}
	}
	
	public function alterNotice()
	{
		if ($_POST['level'] != 0 && $_POST['level'] != -1 ) {
			$this->outputJson(-1, '选择播放等级');
		}
	
		if (empty($_POST['starttime'])) {
			$this->outputJson(-1, '请重新选择开始时间');
		}
		if (empty($_POST['endtime'])) {
			$this->outputJson(-1, '请填写播放结束时间');
		}
		if( strtotime($_POST['endtime']) < time(0))
		{
			$this->outputJson(-1, '播放结束时间不能小于当前时间');
		}
		if (empty($_POST['playnum'])) {
			$this->outputJson(-1, '请填写播放次数');
		}
	
		if (empty($_POST['context'])) {
			$this->outputJson(-1, '请填写公告内容');
		}
	
		if ($_POST['looptime'] > 0 && $_POST['looptime']< 10) {
			$this->outputJson(-1, '循环间隔小于10秒，请重新填写');
		}
	
		$iplatform = Helper_Lib::getCookie("zoneid");
		if($iplatform == 0)
		{
			$this->outputJson(0, '平台验证出错！' );
		}
		$data = array(
				'tr_createtime'=>time(0),
				'tr_platform'=>$iplatform,
				'tr_context' =>$_POST['context'],
				'tr_statu'=>"未审核",
				'tr_auther' => "无",
				'tr_authtime' =>0,
				'tr_startime' => strtotime($_POST['starttime']),
				'tr_endtime' => strtotime($_POST['endtime']),
				'tr_trigtime' =>$_POST['playnum'],
				'tr_looptime' =>$_POST['looptime'],
				'tr_level'=>($_POST['level'] =='普通')? 0:-1,
		);
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置
	
		$logdata = array(
				'f_platform'=>$iplatform,
				'f_account'=>$_SESSION['account'],
				'f_addtime'=>date("Y-m-d H:i:s", time()),
				'f_ip'=>$stServer['sid_ip'],
		);
		$logtype = 'alternotic';
		$logParams = array('context'=>$_POST['context']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
		 
		 
		try {
			$ret = Notice_Service::UpdateNotice($data,$_POST['tr_id'],$stServer);
			if (!$ret) {
				$this->outputJson(-2, '数据库执行失败！' );
			}
	
	
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		}
	
		$this->outputJson(0, '修改成功');
	
	}
	
	public function delNotice() {
		$iplatform = Helper_Lib::getCookie("zoneid");
		if($iplatform == 0)
		{
			$this->outputJson(0, '平台验证出错！' );
		}
		 
	
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置
		$logdata = array(
				'f_platform'=>$iplatform,
				'f_account'=>$_SESSION['account'],
				'f_addtime'=>date("Y-m-d H:i:s", time()),
				'f_ip'=>$stServer['sid_ip'],
		);
		$logtype = 'delnotic';
		$logParams = array('id'=>$_POST['tr_id']);
		ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
	
		try {
			$ret = Notice_Service::delNotice($_POST['tr_id'],$stServer);
			if (!$ret) {
				$this->outputJson(-2, '数据库执行失败！' );
			}
			 
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		}
	
		$this->outputJson(0, '删除成功');
	}
	
	//添加发送的服务器
	public function addServer() {
		if (empty($_POST['id'])) {
			$this->outputJson(-1, '没有对应id可以选择！');
		}
		$iplatform = Helper_Lib::getCookie("zoneid");
		if($iplatform == 0)
		{
			$this->outputJson(0, '平台验证出错！' );
		}
		$stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置
		try {
			$data = array(
					'tr_sid'=>$_POST['serverlist']
			);
			$ret = Notice_Service::addserver($_POST['id'],$data,$stServer);
			if (!$ret) {
				$this->outputJson(-2, '数据库执行失败！' );
			}
			 
		} catch (Exception_Lib $e) {
			$this->outputJson($e->getCode(), $e->getMessage());
		}
	
		$this->outputJson(0, '添加服务器成功');
	}
	 
}
