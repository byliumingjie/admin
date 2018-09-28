<?php

class Servers_Controller extends Module_Lib 
{ 
	private  $mode = null;
	private  $webdbcfg = null;
	
	public function __construct()
	{
		parent::__construct();
		$platfrom = Platfrom_Service::getServer(true,'admin');
		
		$this->webdbcfg = $platfrom;
		
		$this->mode = new  Servers_Model($platfrom);
	}
    public function listServers() 
    { 
    	 
    	$page = empty($_GET['p']) ? 1 : $_GET['p'];
    	
    	$pagesize = empty($_GET['pagesize']) ? 100 : $_GET['pagesize'];
    	 
		$total = $this->mode->getServersTotal();	 	
	   
		$server = $this->mode->getServers($page, $pagesize); 
		 
		$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total['total'], $page, $pagesize));
		
		$data = array
		(
			'pagehtml'=>$pagehtml,				 
			'servers'=>$server,		 				 
		); 
		
		$this->load_view('gm/servers', $data);
  	} 
  	// 下载config
  	public function downloadSidConfig()
  	{
  		__log_message('1','load-sid.txt');
  		$id = (int)$_GET['id']; 
  		
  		$Serverinfo = User_Service::getuser_platform(0,true);
  		
  		foreach ($Serverinfo as $Invar)
  		{
  			if (!empty($Invar['platformId']) && !empty($Invar['type']))
  			{
	  			if ($id == $Invar['id'])
	  			{
	  				$serverData = $Invar;
	  			}
  			}
  		}
  		__log_message('2'.json_encode($serverData),'load-sid.txt');
  		if (!empty($serverData) && count($serverData)>0)
  		{ 
  			$iNcdk = Platfrom_Service::getStatDBconfig(
  			(int)$serverData['platformId']);
  			 
  			$data = 
  			[  			
  			 'gameNodePort'=>(int)$serverData['gameNodePort'],
  			 'gamedbHost'=>$serverData['gamesdbHost'],
  			 'gamedbName'=>$serverData['platformdb'],
  			 'gamedbUser'=>$serverData['platformuser'],
  			 'gamedbPassword'=>$serverData['platformpwd'],
  			 'webSocketPort'=>$serverData['platformport'],
  			 'serverId'=>(int)$serverData['type'],
  			 'platId'=>(int)$serverData['platformId'],
  			 'gameInternalHost'=>$serverData['gameInternalHost'], 
  			 'centerServerWorker'=>(int)$serverData['centerServerWorker'],
  			 'arenaServerWorker'=>(int)$serverData['arenaServerWorker'],
  			 'mapServerWorker'=>(int)$serverData['mapServerWorker'],
  			 'gatewayServerWorker'=>(int)$serverData['gatewayServerWorker'],
  			 'cdkServerHost'=>$iNcdk['cdkServerHost'],
  			 'cdkServerWorker'=>(int)$iNcdk['cdkServerWorker'],
  			 'statdbHost'=>$iNcdk['mysqlhost'],
  			 'statdbName'=>$iNcdk['db'],
  			 'statdbUser'=>$iNcdk['mysqluse'],
  			 'statdbPassword'=>$iNcdk['mysqlpasswprd'],
  			];
  			__log_message('3'.json_encode($data),'load-sid.txt');
  			header('Content-type: text/xml');
  			header("Content-Disposition:attachement;filename=server-".$data['serverId'].".xml");  			
  			echo $this->SET_XML($data);
  			exit;
  			 
  		}
  		$this->outputJson(-1,'无效区服无法导出配置信息');
  	}
  	
  	public function SET_XML($data=array())
  	{
  		__log_message('4 SET_XML ','load-sid.txt');
  		 
  		$gameInternalHost = 
  		!empty($data['gameInternalHost']) 
  		? 
  		$data['gameInternalHost']:'127.0.0.1';
  		 
  		// root->node  property
  		$rootNode= [
  		'center'=>['tick'=>50,'addr'=>'127.0.0.1','count'=>$data['centerServerWorker']],
  		'redeem'=>['tick'=>50,'addr'=>$data['cdkServerHost'],'count'=>$data['cdkServerWorker']],
  		'arena'=>['tick'=>50,'addr'=>'127.0.0.1','count'=>$data['arenaServerWorker']],
  		];
  		$Noteattribute_array =
  		[
  		'logic'=>['tick'=>50,'addr'=>$gameInternalHost,'count'=>$data['mapServerWorker']],
  		'login_in'=>['tick'=>50,'addr'=>'127.0.0.1','count'=>$data['gatewayServerWorker']],
  		];
  		// root->server property  node
  		$serverlogicOut = [
  		'logic'=>['tick'=>30,'count'=>$data['mapServerWorker']],
  		'login_in'=>['tick'=>30,'count'=>$data['mapServerWorker']],
  		];
  		// xml end
  		$xmlRestOut = 
  		[
  			'db'=>
  			[
  			 'db_addr'=>$data['gamedbHost'],
  			 'db_user'=>$data['gamedbUser'],
  			 'db_password'=>$data['gamedbPassword'],
  			 'db_name'=>$data['gamedbName'],
  			 'db_charset'=>'gbk'  					
  			], 				
  			'censusdb'=>
  			[
  			 'db_addr'=>$data['statdbHost'],
  			 'db_user'=>$data['statdbUser'],
  			 'db_password'=>$data['statdbPassword'],
  			 'db_name'=>$data['statdbName'],
  			 'db_charset'=>'gbk'  				
  			],  				
  			'gameaccountdb'=>
  			[
  			 'db_addr'=>$this->webdbcfg['mysqlhost'],
  			 'db_user'=>$this->webdbcfg['mysqluse'],
  			 'db_password'=>$this->webdbcfg['mysqlpasswprd'],
  			 'db_name'=>$this->webdbcfg['db'],
  			 'db_charset'=>'gbk'  				
  			],  				
  			'globaldb'=>
  			[
  			 'db_addr'=>$this->webdbcfg['mysqlhost'],
  			 'db_user'=>$this->webdbcfg['mysqluse'],
  			 'db_password'=>$this->webdbcfg['mysqlpasswprd'],
  			 'db_name'=>'globaldb',
  			 'db_charset'=>'gbk'  				
  			],  				
  			'manager'=>
  			[
  			 'addr'=>'+',
  			 'port'=>$data['webSocketPort'],
  			 'signkey'=>'123'
  			],
  		];
  		// node tab
  		for ($n = 0 ;$n<$data['totalNode']; $n++)
  		{
  		  	$loginNOte[] = ['node'=>null];
  		}
  		$centerNote_array =[];
  		
  		// body title
  		$string ="<?xml version='1.0' encoding='utf-8'?>
  		<root  platform_id='{$data['platId']}' 
  		server_id='{$data['serverId']}' maxnum='600'>
  		<system heartbeat='300' closeclientpacketsize='50'/>
  		</root>";
  		$xml = simplexml_load_string($string);
  		
  		$res = $xml->addChild('res');
  		$res->addAttribute('configrespath',"res");
  		foreach ($rootNode as $key=>$var){
  		
  			$root_node = $xml->addChild($key);  		
  			$root_node->addAttribute('tick',$var['tick']);
  			$root_node->addAttribute('addr',$var['addr']);
  			$root_node->addAttribute('count',$var['count']);
  		}  	
  		
  		$server = $xml->addChild('server');
  		
  		foreach ($Noteattribute_array as $key=>$var)
  		{  		
  			$login = $server->addChild($key);  		
  			$login->addAttribute('tick',$var['tick']);
  			$login->addAttribute('addr',$var['addr']);
  			$login->addAttribute('count',$var['count']);
  		} 
  		// 
  		foreach ($xmlRestOut as $key=>$var)
  		{
  			$xmlres =null;
  					 
  			$xmlres = $xml->addChild($key);
  			
  			foreach ($var as $keys=>$Invar)
  			{
  				$xmlres->addAttribute($keys,$Invar);
  			}  			
  		}
  		$xmlName = 'server-'.$data['serverId'].'.xml';
  		__log_message('5 SET_XML ','load-sid.txt');
  		
  		if($xml->asXML($xmlName))
  		{
  			__log_message('6 SET_XML ','load-sid.txt');  			
  			__log_message('7 SET_XML ','load-sid.txt');
  			return   file_get_contents($xmlName);  			
  		}
  		else
  		{
  			__log_message('8 SET_XML ','load-sid.txt');
  			$this->outputJson(-1,'生成xml失败');
  			//echo json_encode(['status'=>-1,'message'=>'生成xml失败']);
  		}
  	}
	public function addServer()
	{
		// --------------------------------------------
		// 判断是否录的数据统计配置 还是区服配置 1-区服 列表添加 2-平台数据统计db配置	
		// 3 服务器列表变更 4 平台数据统计 db配置变更
		$devType = (int)$_POST['devType'];
		__log_message(json_encode($_POST),'servers-log');
		// --------------------------------------------
		if (empty($devType))
		{
			$this->outputJson(-1,'服务器功能类型有误不能为空!');
		}
		// --------------------------------------------
		
		// 服务器id
		$type = (int)$_POST['type'];
		// 服务器名称
		$name = $_POST['platformName'];	
		// 平台ID
		$platId = (int)$_POST['platformId'];
		// 后台通讯地址
		$gamehost  = $_POST['platformhost'];
		// 后台通讯端口
		$gameport  = (int)$_POST['platformPort'];	
		// 客户端通讯外网地址
		$gameNodeHost = $_POST['gameNodeHost'];
		// 客户端通讯内网地址
		$gameInternalHost = $_POST['gameInternalHost'];
		// 中心服进程数	
		$centerServerWorker =(int)$_POST['centerServerWorker'];
		// 竞技场进程数
		$arenaServerWorker = (int)$_POST['arenaServerWorker'];
		// 地图进程数
		$mapServerWorker = (int)$_POST['mapServerWorker'];
		// 网关进程数
		$gatewayServerWorker = (int)$_POST['gatewayServerWorker'];
		// 服务器起始节点
		$gameNodePort = (int)$_POST['gameNodePort'];
		// 区服状态 0-未设置 1-开服  2-关服
		$serverStatus = (int)$_POST['serverStatus'];
		// 游戏服数据库DB 地址
		$gamesdbHost = $_POST['gamesdbHost'];		
		// 游戏服数据库DB 库名
		$platformdb = $_POST['platformdb'];
		// 游戏服数据库DB 用户
		$platformuser = $_POST['platformuser'];
		// 游戏服数据库DB 密码
		$platformpwd = $_POST['platformpwd'];
		// 描述
		$description = $_POST['desc'];
		// --------------------------------------------
		// 数据统计配置数据		
		$mysqlhost = $_POST['mysqlhost']; 
		$mysqlport = $_POST['mysqlport'];
		$db = $_POST['db'];
		$mysqlpasswprd = $_POST['mysqlpasswprd'];
		$mysqluse = $_POST['mysqluse'];
		$cdkServerHost = $_POST['cdkServerHost'];
		$cdkServerWorker =(int)$_POST['cdkServerWorker'];
		// --------------------------------------------
		$data = [];
		// --------------------------------------------	
		 
		if ($devType==1) // 区服 列表添加 
		{
			$data =[
			"type"=>$type,
			"platformName"=>$name,
			"platformId"=>$platId,
			"platformhost"=>$gamehost,
			"platformport"=>$gameport,
			"gameInternalHost"=>$gameInternalHost,
			"gameNodeHost"=>$gameNodeHost,				
			"centerServerWorker"=>$centerServerWorker,
			"arenaServerWorker"=>$arenaServerWorker,
			"mapServerWorker"=>$mapServerWorker,
			"gatewayServerWorker"=>$gatewayServerWorker,			 	
			"serverStatus"=>0,
			"gamesdbHost"=>$gamesdbHost,
			"platformdb" =>$platformdb,
			"platformuser"=>$platformuser,
			"platformpwd" =>$platformpwd,						
			"desc"=>$description,
			"createtime"=>date('Y-m-d H:i:s',time())
			]; 
		} 
		elseif($devType==2) //平台数据统计db配置
		{			
			$serverOut = $this->mode->server_Exclude_duplicates(1,$platId,0);
			
			__log_message(json_encode($serverOut),'servers-log');
			
			if (!empty($serverOut) && count($serverOut)>=1)
			{
				$this->outputJson(-1,'该平台的数据库配置已添加!');	
			}			 
			$data =[
			'type'=>0,
			'platformName'=>$name,
			'platformId'=>$platId,
			'mysqlhost'=>$mysqlhost,
			'mysqlport'=>$mysqlport,
			"mysqlpasswprd"=>$mysqlpasswprd,
			'db'=>$db,
			'mysqluse'=>$mysqluse,
			'cdkServerHost'=>$cdkServerHost,
			'cdkServerWorker'=>$cdkServerWorker,
			'desc'=>$description,
			"createtime"=>date('Y-m-d H:i:s',time())
			];
		}
		// --------------------------------------------
		// --------------------------------------------
		$ret = $this->mode->addServer($data);
		 
		if (!$ret)
		{
			log_message(6003, $data, '',-6003);
			$this->outputJson(-3, '添加服务器失败！网络原因，请稍后重试！');
		}
		log_message(6003, $data, '',0);
		 
		$this->outputJson(0, '服务器增加成功！');
	}
	// 删除区服列表
	public function deleServer(){
		 
		$id = $_POST['id'];
		
		$ret = $this->mode->delServers($id);
		
		if (!$ret) 
		{
			log_message(6005, $id, '',-6005);
			
			$this->outputJson(-3, '删除区服失败！网络原因，请稍后重试！');
		}		
		log_message(6005, $id, '',0);
		
		$this->outputJson(0, '删除区服成功！');		 
	}
	/*
	 * 
	 * EDIT SERVER 
	 * */
	public  function editServer()
	{
		$serverdesc = '';
		
		$id = $_POST['id'];
		// --------------------------------------
		$devType = (int)$_POST['devType'];
		// --------------------------------------
		if (empty($devType))
		{
			$this->outputJson(-1,'服务器增加类型有误不能为空!');
		}
		// --------------------------------------
		if ($devType == 3)
		{
		$type = (int)$_POST['type'];
		if (empty($type)) {
			$this->outputJson(-1, '请填写服务器ID！');
		}
		// --------------------------------------
		// --------------------------------------
		$platformhost = $_POST['platformhost'];
		
		if (empty($platformhost)) {
			$this->outputJson(-1, '后台通讯IP不能为空');
		}
		// --------------------------------------
		$platformport = (int)$_POST['platformport'];
		if (empty($platformport))
		{
			$this->outputJson(-1, '后台通讯端口不能为空');
		}		 
		// --------------------------------------
        $centerServerWorker = !empty($_POST['centerServerWorker']) 
        ? 
        (int)$_POST['centerServerWorker'] 
        : 
        $this->outputJson(-1,'总心服进程数不能为空');
        // --------------------------------------
        $arenaServerWorker = !empty($_POST['arenaServerWorker']) 
        ? 
        (int)$_POST['arenaServerWorker'] 
        : 
        $this->outputJson(-1,'竞技场进程数不能为空');
        
        // --------------------------------------
        $mapServerWorker = !empty($_POST['mapServerWorker'])
        ?
        (int)$_POST['mapServerWorker']
        :
        $this->outputJson(-1,'地图进程数不能为空');
        // --------------------------------------
        $gatewayServerWorker = !empty($_POST['gatewayServerWorker'])
        ?
        (int)$_POST['gatewayServerWorker']
        :
        $this->outputJson(-1,'网关进程数不能为空');
        // --------------------------------------
        $gameNodeHost = !empty($_POST['gameNodeHost']) 
        ? 
        $_POST['gameNodeHost'] 
        : 
        $this->outputJson(-1,'客户端外网通讯地址不能为空');
        // --------------------------------------
        $gameInternalHost = !empty($_POST['gameInternalHost']) 
        ? 
        $_POST['gameInternalHost'] 
        : 
        $this->outputJson(-1,'客户端内网通讯地址不能为空');
        // .---------------------------------------
        $gamesdbHost = !empty($_POST['gamesdbHost'])
        ?
        $_POST['gamesdbHost']
        :
        $this->outputJson(-1,'游戏服数据库地址不能为空');
        // --------------------------------------
        $platformdb = !empty($_POST['platformdb'])
        ?
        $_POST['platformdb']
        :
        $this->outputJson(-1,'游戏服数据库库名不能为空');
        // --------------------------------------
        $platformuser = !empty($_POST['platformuser'])
        ?
        $_POST['platformuser']
        :
        $this->outputJson(-1,'游戏服数据库用户不能为空');
        // --------------------------------------
        $platformpwd = !empty($_POST['platformpwd'])
        ?
        $_POST['platformpwd']
        :
        $this->outputJson(-1,'游戏服数据库密码不能为空');
        // --------------------------------------
        $description = $_POST['desc'];
	}
	// --------------------------------------
        $platformname = $_POST['platformname'];
        if (empty($platformname)) {
            $this->outputJson(-1, '请填写区服名称');
        } 
        // --------------------------------------
        $platformId = !empty($_POST['platformId']) 
        ? 
        (int)$_POST['platformId'] 
        : 
        $this->outputJson(-1,'平台不能为空'); 
        // --------------------------------------        
        __log_message($_POST['sidstatus'],'server-log');
        
        $serverStatus = (empty($_POST['serverStatus'])) 
        ? 
        (int)$_POST['sidstatus'] : (int)$_POST['serverStatus'];
        
        // --------------------------------------
        if ($devType == 3)
        {
       		$data =[
			"type"=>$type,
			"platformName"=>$platformname,
			"platformId"=>$platformId,
			"platformhost"=>$platformhost,
			"platformport"=>$platformport,
			"gameInternalHost"=>$gameInternalHost,
			"gameNodeHost"=>$gameNodeHost,
       		"centerServerWorker"=>$centerServerWorker,
       		"arenaServerWorker"=>$arenaServerWorker,
       		"mapServerWorker"=>$mapServerWorker,
       		"gatewayServerWorker"=>$gatewayServerWorker,
			"serverStatus"=>$serverStatus,
       		"gamesdbHost"=>$gamesdbHost,
       		"platformdb" =>$platformdb,
       		"platformuser"=>$platformuser,
       		"platformpwd" =>$platformpwd,
			"desc"=>$description,
			"createtime"=>date('Y-m-d H:i:s',time())
			]; 
        }
        elseif($devType==4) //平台数据统计db配置
        {
        	// --------------------------------------
        	$mysqlhost = !empty($_POST['mysqlhost']) 
        	? 
        	$_POST['mysqlhost'] 
        	: 
        	$this->outputJson(-1,'db地址不能为空');
        	// --------------------------------------
        	$mysqlport = !empty($_POST['mysqlport']) 
        	? 
        	$_POST['mysqlport'] 
        	: 
        	$this->outputJson(-1,'db端口不能为空');
        	// --------------------------------------
        	$db = !empty($_POST['db']) 
        	? 
        	$_POST['db'] 
        	: 
        	$this->outputJson(-1,'db库名不能为空');
        	// --------------------------------------
        	$mysqluse = !empty($_POST['mysqluse']) 
        	? 
        	$_POST['mysqluse'] 
        	: 
        	$this->outputJson(-1,'db用户不能为空');
        	//---------------------------------------
        	$mysqlpasswprd = !empty($_POST['mysqlpasswprd']) 
        	? 
        	$_POST['mysqlpasswprd'] 
        	: 
        	$this->outputJson(-1,'db密码不能为空');
        	// --------------------------------------
        	$cdkServerHost = !empty($_POST['cdkServerHost'])
        	?
        	$_POST['cdkServerHost']
        	:
        	$this->outputJson(-1,'cdk 服务器地址不能为空');
        	// --------------------------------------
        	$cdkServerWorker = !empty($_POST['cdkServerWorker'])
        	?
        	$_POST['cdkServerWorker']
        	:
        	$this->outputJson(-1,'cdk 进程数不能为空');
        	// desc        	
        	$description = $_POST['desc'];
        	// --------------------------------------
        	$data =[
        	'type'=>0,
        	'platformName'=>$platformname,
        	'platformId'=>$platformId,
        	'mysqlhost'=>$mysqlhost,
        	'mysqlport'=>$mysqlport,
        	'mysqlpasswprd'=>$mysqlpasswprd,
        	'db'=>$db,
        	'mysqluse'=>$mysqluse,
        	'cdkServerHost'=>$cdkServerHost,
        	'cdkServerWorker'=>$cdkServerWorker,
        	'desc'=>$description,
        	"createtime"=>date('Y-m-d H:i:s',time())
        	];
        	
        	// --------------------------------------
        }
        $ret = $this->mode->editServers($id, $data);
        if ($devType==3)
        {
        	$Reminder = '区服';
        }
        else{
        	$Reminder = '统计数据库配置';
        }
        if($ret){       
        	$this->outputJson(0,$Reminder.'变更成功!');
        }else{
        	$this->outputJson(-1,$Reminder.'区服变更失败!');
        }
        
	} 
	// 统计服验证不可重复平台
	public function DB_config_plat_Verify()
	{
		$id = (int)$_POST['id'];
		__log_message('id='.$id,'servers-log');
		$page = Config::get("common.page");
		$systemurl  = $page['host'].'/System/index';
		
		$AllplatOut = Platfrom_Service::getStatDBconfig();
		
		$platOut  = System_Service::getplatformInfo();
		
		$addplatOut = [];
		
		foreach ($platOut as $var)
		{
			$platId = $var['id'];
			
			if ($id == $platId){
				
				$addplatOut[] = $var;
			}
		}
		foreach ($AllplatOut as $Inallvar)
		{
			$addplatOut[]=$Inallvar;
		}
		__log_message('add plat out ='.json_encode($addplatOut),'servers-log');
		
		if (!empty($addplatOut) && count($addplatOut)>0)
		{
			//turn  $addplatOut;
			$option = "<select name='platformId' id='editDBcfgplatFormId'>";
			foreach ($addplatOut as $key=>$Invar)
			{
				$option.="<option value='".$Invar['id']."'>".$Invar['name']."</option>";
			}
			$option.="</select><a href='{$systemurl}'>去添加</a>";
			
			
			$rs['errcode'] =0;
			$rs['msg'] = '获取成功!';
			$rs['option'] = $option;
			__log_message($rs,'servers-log');
			echo json_encode($rs);    		
    		exit;
		}
		$this->outputJson(-1,'隶属平台获取信息失败!');
		
	}
	// 开服
	public function OpenSever(){
		
		// 开1 关 2
		$status = (int)$_POST['status'];	
		$serverId = (int)$_POST['serverId'];
		$ret = $statname = NULL;
		
		$code = ($status==1)?'StartServer':'StopServer';		
		
		$codeNum = ($status==1)?50001:50002;		
		
		if ($status == 1)
		{
			$statname = '开服';
			$ret = $this->send(['ServerId'=>$serverId], $code,$serverId,NULL,90);
		}
		else{
			$ret = $this->send(['ServerId'=>$serverId], $code,$serverId,NULL,380);
			$statname = '关服';
		}		
				
		if(log_message($codeNum, ['ServerId'=>$serverId], $ret))
		{
			if($ret){			
				$this->outputJson(0,$statname.'成功!');
			}
			else{			
				$this->outputJson(0,$statname.'失败!');
			}
		}
		$this->outputJson(-1,'日志记录失败');
	}
	
}
























