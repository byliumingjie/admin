<?php 
	error_reporting(E_ALL);
	include_once "lib/log.php";
	include_once "lib/util.php";
	include_once "lib/mysql.lib.php";
	
	// 获取客户端数据 
	if (!empty($_POST) && count($_POST)>0 )
	{
		_LOG('post-data'.json_encode($_POST));
	}
	else
	{
		_LOG('post 请求数据为空');
		exit('{"status":"-1","result":"请求数据为空"}');
	}	
	$id = (isset($_POST['id']) && !empty( trim($_POST['id']) ))
	?
	$_POST['id'] : exit('{"status":"-1","result":"平台id为空"}');
	
	if (!is_numeric($id))
	{
		exit('{"status":"-1","result":"不存在的平台Id"}');
	}	
	$appVersion = (isset($_POST['appVersion']) && !empty( trim($_POST['appVersion']) ))
	?
	$_POST['appVersion']:exit('{"status":"-1","result":"安装包版本号为空"}');
	
	$resVersion = ( isset($_POST['resVersion']) && !empty( trim($_POST['resVersion']) ))
	?
	$_POST['resVersion']:exit('{"status":"-1","result":"资源版本号为空"}');
	
	//$id = 1;
	//$appVersion = '1.0.0.1';
	//$resVersion = '1.0.0.5';
	
	
	//_LOG('post-data11','platform-log');
	
	// 获取版本更新配置信息 后期可读取redis
	function  getplatformInfo($id)
	{	 
	    $sql = "SELECT id,`name`,appVersion,resVersion,
	    downloadAppURL,downloadResURL FROM tb_platform_cfg 
	    where id = :id limit 1"; 
	    
		//_LOG('post-data11'. $sql,'db');
		
	    $outid = array('id'=>$id);
	    
	    $statement = query($sql,$outid);
	
	    if( $statement && rowcount($statement)>0 )
	    {
			//_LOG('post-data11'. $sql,'db');
	    	$rows = fetch_all($statement);
	    	return $rows[0];
	    }
	} 
	$Inplatform = getplatformInfo($id);
	
	//$dataOut = array();
	
	if (!empty($Inplatform) && count($Inplatform)>0)
	{ 
		$t_id = $Inplatform['id'];
		$t_name =  $Inplatform['name'];
		$t_appVer = $Inplatform['appVersion'];
		$t_resVer = $Inplatform['resVersion'];
		$t_appURL = $Inplatform['downloadAppURL'];
		$t_resURL = $Inplatform['downloadResURL'];
		// 默认情况下，在第一个版本低于第二个时，version_compare() 
		// 返回 -1；如果两者相等，返回 0；第二个版本更低时则返回 1
		$appverif = version_compare($appVersion, $t_appVer);
		$retverif= version_compare($resVersion, $t_resVer);
		// 如果客户端的版本低于目前线上版本则直接强制更新安装包 -- 返回安装包的下载地址
		// 如果客户端的APP版本号比数据库里面的版本号低 （强制更新）
		
		$dataOut = array
		(
			'status'=>0,
			'result'=>array()
		);
		
		if ($appverif==-1)
		{
			$dataOut = array(
			'status'=>1,
			'result'=>array
			(		
			'id'=>$t_id,
			'name'=>$t_name,
			'appVersion'=>$t_appVer,
			'downloadAppURL'=>$t_appURL) 
			); 
			_LOG("1111",'platfrom.up.log');
			//echo  json_encode($dataOut);
			exit(json_encode($dataOut));	  
			
		}	 
		// 如果客户端的资源版本号 与数据库的 ret(资源版本号) 相等  & 如果是客户端的资源版本号大于数据库资源版本号
		if($retverif!=0) 
		{  
			$dataOut = array(
			'status'=>2,
			'result'=>array
			(
				'id'=>$t_id,
				'name'=>$t_name,
				'resVersion'=>$t_resVer,
				'downloadResURL'=>$t_resURL)
			);
			_LOG("3333",'platfrom.up.log');
		}	
		
	
		 
		echo   json_encode($dataOut);  
	}else{  
		echo '{"status":"-1","result":"平台信息为空!或网络中断"}';
	}
?>