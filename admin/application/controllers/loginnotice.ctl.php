<?php

/*
 * 登录公告
 */

class LoginNotice_Controller extends Module_Lib 
{ 
	private  $mode = null;
	public function __construct($conn='')
	{
		parent::__construct(); 
		$conn = Platfrom_Service::getServer(true,'globaldb');
		$this->mode = new  LoginNotice_Model($conn);
	}
	
    /**
     * 当前统计信息
     */
    public function showNotice() {
    	$data = array();
     
        /* $createTime = empty($_GET['createTime'])?"": $_GET['createTime'];
        $endtime = empty($_GET['endtime'])?"": $_GET['endtime'];
        $account = empty($_GET['account'])?"": $_GET['account'];
        $platform = Helper_Lib::getCookie("zoneid");
        $stServer =  Platform_Model::getPlatformByID($platform);  //暂时平台id为设置
        if(empty($stServer))
       {
          header("Location:/gameuser/loadserverinfo");
          exit();       
       }
       $awhere = array(
           "createtime"=>  strtotime($createTime),
           "endtime"=>strtotime($endtime),
           "account"=>$account,
        ); */
        //$page = empty($_GET['p']) ? 1 : $_GET['p'];
        //$pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
       // $total = LoginNotice_Service::getNoticeTotal($stServer);
       // $pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total, $page, $pagesize));
        /* $data = array(
            'servers'=>  Server_Service::getAllServers($platform, $stServer),
            //'pagehtml'=>$pagehtml,
            'data' => LoginNotice_Service::getNotice($awhere,$stServer,0, 0),
            'addServer' => $this->checkPermission('loginnotice/addServer'),
            'addLoginNotice' => $this->checkPermission('loginnotice/addLoginNotice'),
            'alterLoginNotice' => $this->checkPermission('loginnotice/alterLoginNotice'),
            'sumbitNotice' => $this->checkPermission('loginnotice/sumbitNotice'),  
        );   */
        $this->load_view('dev/login_notice',$data);
    }
    public function passLoginNotice() 
    { 
    	$page = empty($_GET['p']) ? 1 : $_GET['p'];    	
    	$pagesize = 20;
    	
    	$data = array();
    	
    	if (isset($_POST['btn_sub'])){
    		
        $createTime = empty($_POST['createTime'])
        ?
        NULL
        :
        ' createtime>'."'".$_POST['createTime']."'";
        
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
        
        $awhere = [$sender,$createTime,$endtime];
        
        foreach ($awhere as $var)
        {
        	if(empty($var)){continue;}
        	 
        	if(!$indata){
        
        		$indata.= " where ".$var;
        	}else{
        		$indata .= " and ".$var;
        	}
        }
         
        $_SESSION['noticeWhere'] = $indata;  
        // 发布中
        $releasetotal = $this->mode->getNoticeTotal($_SESSION['noticeWhere'],true);
        $_SESSION['noticereleasetotal'] = $releasetotal;
        // 失效
        $Failuretotal = $this->mode->getNoticeTotal($_SESSION['noticeWhere']);
        $_SESSION['noticefailuretotal'] = $Failuretotal;
    	}
    	 
    	if (isset($_SESSION['noticereleasetotal']) || isset($_SESSION['noticefailuretotal']) )
    	{
    		// 发布中
	    	$releaselist = $this->mode->getNotice( 
	    	$_SESSION['noticeWhere'],$page,$pagesize,true);
	    	// 失效
	    	$failurelist = $this->mode->getNotice( 
	    	$_SESSION['noticeWhere'],$page,$pagesize);    
	    	
	    	$noticereleasehtml= htmlspecialchars(
	    	Helper_Lib::getPageHtml($_SESSION['noticereleasetotal'], $page, $pagesize));
	    	// 失效
	    	$noticefailurehtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($_SESSION['noticefailuretotal'], $page, $pagesize));
	    	 
	    	$data = array
	    	(
	    		'noticefailurelist'=>$failurelist,
	    		'noticefailurehtml'=>$noticefailurehtml,
	    		'noticereleaselist'=>$releaselist,
	    		'noticereleasehtml'=>$noticereleasehtml,	    		
	    	);
    	}
        $this->load_view('dev/login_noticepass',$data);
    }
    
    public function sumbitNotice() {
        
        if(empty($_POST['sid']))
        {
            $this->outputJson(-1, '区服为空，请填写区服');
        }
        
        $iplatform = Helper_Lib::getCookie("zoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }		
        $data = array(
                'statu'=>0,
        );
        $stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置      
        try {
            $ret = LoginNotice_Service::addData($_POST['tr_id'],$data,$stServer);
            if (!$ret) {
                $this->outputJson(-2, '数据库执行失败！' );
            }  
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }

        $this->outputJson(0, '提交成功');
    }
    public function returnLoginNotice() {
        
        if (empty($_POST['tr_id'])) {
            $this->outputJson(-1, '选择目标错误,请刷新');
        }
        if (empty($_POST['reason'])) {
            $this->outputJson(-1, '请填写撤回理由');
        }
        $stList = explode(',',$_POST['sid']);
        $flag = 0;

        $Data = array(
                    'reason'=>$_POST['reason'],
                    'starttime' =>0,
                    'endtime' => 0,
                    'statu'=>3,
                );

        $iplatform = Helper_Lib::getCookie("zoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        
            
        //撤回放到失效的页面
        $stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置 
        $ret = LoginNotice_Service::addData($_POST['tr_id'],$Data,$stServer);
            if (!$ret) {
                $this->outputJson(-2, '数据库执行失败！' );
            }     
        
        $sendData = array(
                    'id'=>$_POST['tr_id'],
                    'starttime' =>0,
                    'endtime' => 0,
                    'statu'=>-1,
                    'account'=>$_SESSION['account'],
                );

        $logdata = array(
                'f_platform'=>$iplatform,
                'f_account'=>$_SESSION['account'],
                'f_addtime'=>date("Y-m-d H:i:s", time()),
                'f_ip'=>$stServer['sid_ip'],
            );
          
            $logtype = 'revokelogin';
            $logParams = array('id'=>$_POST['tr_id']);
            ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
            
        for($i =0; $i< count($stList); ++$i)
        {

           $Server = Server_Service::getServerByPtAndId($iplatform,$stList[$i],$stServer);
//           if($Server['serverStatus'] !=2 )//只对开服的服务器发送公告
//           {
//               continue;
//           } 
           $result = LoginNotice_Service::SendNoitce($sendData,$Server); 
           if (!$result) {
              $flag = 1;
            } 
        }    
        if($flag != 1)
        {
           
            $this->outputJson(0, '撤回成功');
            
        }  else {
            $this->outputJson(-2, '撤回失败');
        }   
    }
    /**
     * 添加登录公告
     * **/
    public function addLoginNotice()
    {
    	
        if (empty($_POST['starttime'])) {
            $this->outputJson(-1, '请填写生效时间');
        }
		if (empty($_POST['endtime'])) {
            $this->outputJson(-1, '请填写失效时间');
        }
	
        if(strtotime($_POST['endtime']) <= time(0))
        {
            $this->outputJson(-1, '失效时间不能小于当前时间');
        }
		if (empty($_POST['title'])) {
            $this->outputJson(-1, '请填写公告标题');
        }
        if (empty($_POST['titleColor'])) {
        	$this->outputJson(-1, '请填写标题颜色');
        }
        
		if (empty($_POST['context'])) {
            $this->outputJson(-1, '请填写公告内容');
        }
        if (empty($_POST['platformid'])){
        
        	$this->outputJson(-1, '请填平台信息');
        }
        
        $serverInfo = $_POST['platformid'];
        
        foreach ($serverInfo as $Inplatformid)
        {
        	if(empty($Inplatformid)){
        		continue;
        	}
        	
        	$Inplatformid = (int) $Inplatformid;
        	 
	        $data []= array(		
			'platformId'=>$Inplatformid,
			'title'=>"'".$_POST['title']."'",// 发送者
			'titlecolor'=>"'".$_POST['titleColor']."'",
	        'context'=>"'".$_POST['context']."'",
			'starttime'=>"'".$_POST['starttime']."'",
			'endtime'=>"'".$_POST['endtime']."'",
	        'sender'=>"'".$_SESSION['account']."'",	 
	        'createtime'=>"'".date('Y-m-d h:i:s',time())."'",
	        );
        }
        try {
        	//$ret = LoginNotice_Service::addLoginNotice($data,$stServer);
        	$ret = $this->mode->insert($data);
        	// 后期这里或者另外开启函数去执行与服务端进行通讯\
        	// 如果服务器要读取本配置只需要有一个配置表就可以了 -- 制定协议格式
        	// 如果是发送给服务端可以循环区服进行遍历但是要记录日志比如录入到自己本地的db库里面
        	if (!$ret) {
        		$this->outputJson(-2, '数据库执行失败！' );
        	}
        
        	if(!log_message('490', $data, 0,0))
        	{
        		$this->outputJson(-1,'日志录入失败');
        	}
        
        } catch (Exception_Lib $e) {
        	$this->outputJson($e->getCode(), $e->getMessage());
        }
        
        $this->outputJson(0, '添加成功!');
        // 获取可根据
        
        
        // $loginnotice = new  LoginNotice_Controller($conn);
        
        
        
        
        /* $isbtn = 1;
        if(intval($_POST['linktype']) == 0 )
        {
            $isbtn = 0;
            if(! empty($_POST['linktext']) || !empty($_POST['btntext']))
            {
                $this->outputJson(-1, '按钮文字和超链接不用填写内容');
            }
        }else if(empty($_POST['linktype'])) {
            
            $this->outputJson(-1, '请填选择跳转的类型');
        }
        
        if (!empty($_POST['linktype'])&&empty($_POST['btntext'])) {

            $this->outputJson(-1, '请填写按钮上的文字');
        }  

        if(intval($_POST['linktype']) === 7)
        {
            if (empty($_POST['linktext'])) {
            $this->outputJson(-1, '请填写跳转链接地址');
            }
        } */

        
		/* $iplatform = Helper_Lib::getCookie("zoneid");
        if($iplatform == 0)
        {
        }		
        $data = array(
                'createtime'=>time(0),
                'context' =>$_POST['context'],
                'creator'=>$_SESSION['account'],
                'title'=>$_POST['title'],
                'statu'=>-2,
                'lable' =>$_POST['statu'],
                'author' => "无",
                'authtime' =>0,
                'linktype'=> $_POST['linktype'],
                 'linktext'=>isset($_POST['linktext'])?$_POST['linktext']:"",
                 'btntext'=>isset($_POST['btntext'])?$_POST['btntext']:"",
                 'isbutton'=>$isbtn,
                'starttime' => strtotime($_POST['starttime']),
                'endtime' => strtotime($_POST['endtime']),
        );
        $stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置    
        $logdata = array(
                'f_platform'=>$iplatform,
                'f_account'=>$_SESSION['account'],
                'f_addtime'=>date("Y-m-d H:i:s", time()),
                'f_ip'=>$stServer['sid_ip'],

            );
          
            $logtype = 'addlogin';
            $logParams = array('id'=>$_POST['tr_id']);
            ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams); */
          
       
    }
    public function editLoginNotice()
    {       
    	 
    	if(empty($_POST['id']))
    	{
    		$this->outputJson(-1, 'id为空');
    	}
    	if(empty($_POST['platformId']))
    	{
    		$this->outputJson(-1, '平台id为空');
    	}
    	if (empty($_POST['starttime'])) {
    		$this->outputJson(-1, '开始时间为空');
    	}
    	if (empty($_POST['endtime'])) {
    		$this->outputJson(-1, '结束时间为空');
    	}
    	if (empty($_POST['title'])) {
    		$this->outputJson(-1, '请选择审核结果');
    	}
    	if (empty($_POST['context'])) {
    		$this->outputJson(-1, '请填写公告内容');
    	}
    		
    	 
    	
    	$data = array(
    			'platformId'=>$_POST['platformId'],
    			'title' =>$_POST['title'],
    			'context' => $_POST['context'],
    			'starttime' =>$_POST['starttime'],
    			'endtime' => $_POST['endtime'],
    			'sender' => $_POST['sender'],
    	);
    	
    	if($this->mode->updateloginNotice($data,$_POST['id']))
    	{
    		//$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
    	
    		//$retOut = json_decode(trim($ret),true);
    	
    		/* if (!$ret || !isset($retOut) || $retOut['status']!=0)
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
    		 } */
    		log_message(9001, $intoken,$retOut, 0);
    		$retSucce[$_POST['platformId']]=$ret;
    	}else
    	{
    		$result = '平台为'.$_POST['platformId'].'db执行失败';
    		$inretinfo = array(
    				'status'=>-1,
    				'result'=>$result
    		);
    		$retError [$_POST['platformId']]= $result;
    		//$retSucce[$serverid]=json_encode($inretinfo);
    		log_message(9001, $intoken,$inretinfo, -9001);
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
    
    public function alterLoginNotice()
    {       
        if (empty($_POST['starttime'])) {
            $this->outputJson(-1, '请填写生效时间');
        }
	if (empty($_POST['endtime'])) {
            $this->outputJson(-1, '请填写失效时间');
      
        }
		
        
	if (empty($_POST['title'])) {
            $this->outputJson(-1, '请填写公告标题');
        }
			
	if (empty($_POST['context'])) {
            $this->outputJson(-1, '请填写公告内容');
        }
        
        if(intval($_POST['statu']) < 0 || intval($_POST['statu']) >3)
        {
           $this->outputJson(-1, '请选择公告状态');
        }
        
        if(intval($_POST['linktype']) != 7 && !empty($_POST['linktext']) )
        {
            $this->outputJson(-1, '请填选择跳转的类型为超链接');
        }  
        if(!empty($_POST['btntext'])&& intval($_POST['linktype']) == 0)
        {
            $this->outputJson(-1, '请填选择跳转的类型');
        }
        
        $flag = 1;
        if($_POST['linktype'] == 0 )
        { 
            $flag = 0;              	
        }else if(empty($_POST['linktype'])) {
            $this->outputJson(-1, '请填选择跳转的类型');

            if (empty($_POST['btntext'])) {
                $this->outputJson(-1, '请填写按钮上的文字');
            }

            if($_POST['linktype'] == 7)
            {
                if (empty($_POST['linktext'])) {
                $this->outputJson(-1, '请填写跳转链接地址');
                }
            }
        }
        $iplatform = Helper_Lib::getCookie("zoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        
        $data = array(
                'createtime'=>time(0),
                'context' =>$_POST['context'],
                'creator'=>$_SESSION['account'],
                'title'=>$_POST['title'],
                'statu'=>-2,
                'lable' =>$_POST['statu'],
                'author' => "无",
                'authtime' =>0,
                'linktype'=> $_POST['linktype'],
                'linktext'=>isset($_POST['linktext'])?$_POST['linktext']:"",
                'btntext'=>isset($_POST['btntext'])?$_POST['btntext']:"",
                'isbutton'=>$flag,
                'starttime' => strtotime($_POST['starttime']),
                'endtime' => strtotime($_POST['endtime']),
        );
        $stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置 
        $logdata = array(
                'f_platform'=>$iplatform,
                'f_account'=>$_SESSION['account'],
                'f_addtime'=>date("Y-m-d H:i:s", time()),
                'f_ip'=>$stServer['sid_ip'],
            );
          
            $logtype = 'alterlogin';
            $logParams = array('id'=>$_POST['tr_id']);
            ManagerLog_Service::insertManagerLog($logdata, $logtype, $logParams);
            
             
        try {
                $ret = LoginNotice_Service::addData($_POST['tr_id'],$data,$stServer);
                if (!$ret) {
                    $this->outputJson(-2, '数据库执行失败！' );
                }
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }
          $this->outputJson(0, '修改成功');

    }
    
    public function delLoginNotice() 
    {
        $iplatform = Helper_Lib::getCookie("zoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        $stServer =  Platform_Model::getPlatformByID($iplatform);  //暂时平台id为设置 
        $logdata = array
        (
             'f_platform'=>$iplatform,
             'f_account'=>$_SESSION['account'],
             'f_addtime'=>date("Y-m-d H:i:s", time()),
             'f_ip'=>$stServer['sid_ip'],
        );          
        $logtype = 'dellogin';
        $logParams = array('id'=>$_POST['tr_id']);
        ManagerLog_Service::insertManagerLog($logdata,$logtype,$logParams);
          
        try {
            $ret = LoginNotice_Service::delNotice($_POST['tr_id'],$stServer);
            if (!$ret) 
            {
                $this->outputJson(-2, '数据库执行失败！' );
            }                 
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }

        $this->outputJson(0, '删除成功');
    }
    
   /**************
    * 添加发送的服务器     *
    **************/
    public function addServer() {
        
        if ( empty($_POST['id']) ) 
        {
          $this->outputJson(-1, '没有对应id可以选择！');
        } 
        $iplatform = Helper_Lib::getCookie("zoneid");
        if( $iplatform == 0 )
        {
           $this->outputJson(0, '平台验证出错！' ); 
        } 
        $stServer =  Platform_Model::getPlatformByID($iplatform);  // 平台id为设置      
        try {
            $data = array
            (
                'sid'=>$_POST['serverlist']
            ); 
            $ret = LoginNotice_Service::addData($_POST['id'],$data,$stServer);
            if ( !$ret ) 
            {
                $this->outputJson(-2, '数据库执行失败！' );
            }   
        } catch ( Exception_Lib $e ) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }

        $this->outputJson(0, '添加服务器成功');
    }  
}
