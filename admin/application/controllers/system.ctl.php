<?php

/*
 * 用户信息相关
 */

class System_Controller extends Module_Lib {
 
	 
	private  $mode = null;
	 
	public function __construct()
	{
		parent::__construct();
 		$this->mode = new  System_Model();
	}
	
    public function index()
    { 
    	if ($_POST['selectdata']){    		
    		$_SESSION['selectdata'] = $_POST['selectdata'];
    		session::set("selectdata",$_POST['selectdata']);
    	} 
		$data = array();
		//var_dump($_POST['account']);
		$systemUpdaInfo = $this->mode->getplatformInfo();
		
		if (!empty($systemUpdaInfo)){
			
			$data['systemCfgInfo'] = $systemUpdaInfo;
		}
		//$platformOut = session::get('AllplatformInfo');
		 
		
		$this->load_view('system_update', $data);
	}
	/**
	 * 区服平台解析配置配置
	 * **/
    public function ServerPlatSolveCfg()
    {
    	$indata = file_get_contents("php://input");
    	
    	$platformId = $_POST['platformId'];
    	
    	$serverType = (isset($_POST['serverType'])) 
    	? 
    	$_POST['serverType']:0;
    	
    	$AllplatformOut= User_Service::getuser_platform(0,true);
    	
    	$been_SelectedListOut = array();
    	$been_SelectedList=NULL;
    	$wait_SelectListOut = array();
    	$wait_SelectList=null;
    	// 获取批量数组平台
    	if (!empty($serverType))
    	{
    		if (!empty($platformId))
    		{
    			$platOut = explode(',', $platformId);
    			__log_message('platOut::'.json_encode($platOut),'user-log');
    			$platInfo = System_Service::getplatformInfo();
    			
    			foreach ($platInfo as $inplat)
    			{ 
    				$platId = (int)$inplat['id'];
    				
    				if (in_array($platId, $platOut))
    				{
    					$been_SelectedListOut[] = [(int)$inplat['id'],$inplat['name']];
    				}
    				else 
    				{
    					// 获取还没有被选中的
    					$wait_SelectListOut[] = [(int)$inplat['id'],$inplat['name']];
    				}
    			}    			
    			$rs['errcode'] =0;
    			$rs['msg'] = '区服信息获取成功!';
    			$rs['beenselect'] = $been_SelectedListOut;
    			$rs['waitselect'] = $wait_SelectListOut;    
    			
    			__log_message(json_encode($rs),'user-log');
    			echo json_encode($rs);
    			exit;
    		}	
    		else
    		{
    			$this->outputJson(-1,'无效的平台信息');
    		}
    	}
    	// 获取所有的区服列表信息 
    	if ( !empty($platformId) && $platformId>0 && empty($serverType))
    	{ 
    		$platformId = (int)$platformId;
    		
    		foreach ($AllplatformOut as $var)
    		{
    			if ($var['type'] == 0){ 
    				continue;
    			}
    			if ((int)$var['platformId'] == $platformId){
    				// 获取已经选中的
    				$been_SelectedListOut[] = (int)$var['type'];
    			}
    			else { 
    				// 获取还没有被选中的
    				$wait_SelectListOut[] = (int)$var['type'];
    			} 
    		}
    	
    		$rs['errcode'] =0;
    		$rs['msg'] = '区服信息获取成功!';
    		$rs['beenselect'] = $been_SelectedListOut;
    		$rs['waitselect'] = $wait_SelectListOut;
    	
    		echo json_encode($rs);    		
    		exit;
    	}
    	$this->outputJson(-1,'区服列表获取失败!');
    }
    /**
     * 获取平台区服信息
     * *
    public function getPlatServerList(){
    	
    	$platformId = (int)$_POST['platformId'];
    	$AllplatformOut= User_Service::getuser_platform(0,true);
    	
    	foreach ($AllplatformOut as $var)
    	{
    		if ($var['type'] == 0){
    			continue;
    		}
    		if ((int)$var['platformId'] == $platformId){
    			// 获取已经选中的
    			$been_SelectedListOut[] = (int)$var['type'];
    		}
    		else {
    			// 获取还没有被选中的
    			$wait_SelectListOut[] = (int)$var['type'];
    		}
    	}
    }*/
    public function addPlatformCfg()
    {
     	// __log_message("addplatform");
    	// 之所以把变量名称更改为type是因为在type是区服ID的时候同时也关乎其他配置如果自定的配置则为0
    	// 后期优化点可以进行优化，自定义默认为0需要手动通过db填写一般会结合服务器名称或者后期增加字段也可以
    	//$type = $_POST['type'];// 服务器id
    	$name = $_POST['name'];	
    	$appVersion = $_POST['appVersion']; 
    	$resVersion  = $_POST['resVersion'];
    	$downloadAppURL  = $_POST['downloadAppURL'];
    	$downloadResURL = $_POST['downloadResURL'];
    	 
    	$data = array
    	( 
    	    "name"=>$name,
	    	'appVersion'=>$appVersion,
    		'resVersion'=>$resVersion,
            'downloadAppURL'=>$downloadAppURL,
	    	'downloadResURL'=>$downloadResURL, 
        );     
    	//$ret = User_Service::addPlatform($data);
    	$ret = $this->mode->addPlatformCfg($data);
    	if (!$ret) {
    		log_message(6001, $data, $ret,-6001);
            $this->outputJson(-3, '添加平台配置失败！网络原因，请稍后重试！');            
        }
        log_message(6001, $data, $ret,0);
        $this->outputJson(0, '添加平台配置成功！');	 
    }
    // 平台删除
    public function delplatfomrCfg()
    { 
     	try{
            $uid = Helper_Lib::getPost('id', 'string', '', false);
            
            $ret = User_Service::delPlatform($uid);
            if (!$ret) {
            	
                $this->outputJson(-3, '删除平台失败！网络原因，请稍后重试！');
            }
           
            $this->outputJson(0, '删除平台成功！');
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(),$e->getMessage());
        }
        
    }
    /**
     * 编辑平台
     */
    public function editPlatformCfg()
    { 
    	$id = $_POST['id'];
   
    	if (empty(trim($_POST['id']))){
    		$this->outputJson(-1, '平台Id不能为空！');
    	}
    	if (empty(trim($_POST['name']))) {
    		$this->outputJson(-1, '平台名称不能为空！');
    	}
    	if (empty(trim($_POST['appVersion']))) {
    		$this->outputJson(-1, '安装包版本号不能为空！');
    	}  
    	if (empty(trim($_POST['resVersion']))) {
    		$this->outputJson(-1, '资源版本号不能为空！');
    	}
    	if (empty(trim($_POST['downloadAppURL']))) {
    		$this->outputJson(-1, '安装包下载地址不能为空！');
    	}
    	if (empty(trim($_POST['downloadResURL']))) {
    		$this->outputJson(-1, '资源下载地址不能为空！');
    	}
    	$ifUpserver = isset($_POST['ifUpserver'])?$_POST['ifUpserver']:0; 
    	//$this->outputJson(-1, $_POST['serverId']);
    	 //explode($delimiter, $string);
    	$data = array
    	(
    		'name' => $_POST['name'],    			
    		'appVersion' => $_POST['appVersion'],
    		'resVersion' => $_POST['resVersion'],
    		'downloadAppURL' => $_POST['downloadAppURL'],
    		'downloadResURL' => $_POST['downloadResURL'], 
    	); 
    	// 如果需要变更区信息时
    	if ($ifUpserver>0)
    	{
    		__log_message("ifUpserverType::".
    		$ifUpserver.'serverId'.implode(',',$_POST['serverId']),'system-ctl');
    		
    		if (count($_POST['serverId'])<=0 || !is_array($_POST['serverId'])) 
    		{
    			$this->outputJson(-1, '更新区服信息不能为空！');
    		}
    		// 更新平台区服信息 
    		$serverIdStr = trim(implode(',',$_POST['serverId']));
    		$serverIdOut = $_POST['serverId'];
	    	$UpserverRet = $this->mode->uploadServerPlat($serverIdStr,$id);
	    	
	    	if(!$UpserverRet)
	    	{
	    		$this->outputJson(-2,'更新区服失败!');
	    	} 
	    	// 对于在表单去掉的区服在一次进行对比第一次是确保不遗漏添加的
	    	// 第二次提出选区中去掉的
	    	$AllplatformOut = User_Service::getuser_platform(0,true);
	    	
	    	foreach ($AllplatformOut as $var)
	    	{
	    		if ($var['type'] == 0){
	    			continue;
	    		}
	    		if ((int)$var['platformId'] == $id){
	    			// 获取已经选中的
	    			$been_SelectedListOut[] = (int)$var['type'];
	    		} 
	    	} 
	    	if( count($been_SelectedListOut)>0 )
	    	{  
		    	foreach($been_SelectedListOut as $var){
		    		if (!in_array($var, $serverIdOut))
		    		{
		    			$RemovedSid[] = $var;
		    		} 
		    	} 
	    	}
	    	__log_message("RemovedSid" . $RemovedSid,'ststemUp');
	    	
	    	if (count($RemovedSid)>0)
	    	{
		    	$UpserverRet2 = $this->mode->uploadServerPlat(trim(implode(',',$RemovedSid)),0);
		    	
		    	if(!$UpserverRet2)
		    	{
		    		$this->outputJson(-2,'在变更替换区时出现异常!区信息为:'.implode(',',$RemovedSid));
		    	}  
	    	}
    	}
    	$ret = $this->mode->editPlatformCfg($id, $data);
    	// 通讯游戏服状态 
    	if(!$ret){
    		log_message(6002,$data,'false',-6002);
    		$this->outputJson(-2,'更新失败!');
    	}
    	log_message(6002,$data,$ret,0);
    	$this->outputJson(0,'修改成功');
    }
    public function plat_server()
    {
    	__log_message("system :::0000#".$_POST['platid'],'system-log');
    	$platId = $_POST['platid'];
    	
    	if (empty($platId))
    	{
    		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
    		&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
    		{
    			$this->outputJson(-1,'平台信息有误,请确认平台信息是否为空!');
    		}
    		return false;
    	}
    	__log_message("system :::00",'system-log');
    	$serverListInfo = Platfrom_Service::get_plat_server($platId);
    	__log_message("system :::11",'system-log');
    	 
    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    	$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') 
    	{
    		__log_message("system--:::".json_encode($serverListInfo),'system-log');
    		if ($serverListInfo)
    		{
    			$rs['serverlist'] = $serverListInfo;
    			$rs['errcode'] =0;
    			$rs['msg'] = '区服信息获取成功!';
    			__log_message("system :::22",'system-log');
    			//echo json_encode($rs);
    			echo json_encode($rs);
    			exit;
    		}
    		else{
    			__log_message("system :::33",'system-log');
    			$this->outputJson(-1,'获取区服信息失败!');
    		}
    		__log_message("system :::44",'system-log');
    	} else {
    		__log_message("system :::55",'system-log');
    		return $serverListInfo;
    	}
    }
}
