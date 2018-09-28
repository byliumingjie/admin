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
	
	
	 

	/**$appVersion = (isset($_POST['appVersion']) && !empty( trim($_POST['appVersion']) ))
	?
	$_POST['appVersion']:exit('{"status":"-1","result":"安装包版本号为空"}');
	
	$resVersion = ( isset($_POST['resVersion']) && !empty( trim($_POST['resVersion']) ))
	?
	$_POST['resVersion']:exit('{"status":"-1","result":"资源版本号为空"}');*/
	
	//$id = 1;
	//$appVersion = '1.0.0.1';
	//$resVersion = '1.0.0.5';
	
	
	//_LOG('post-data11','platform-log');
	
	// 获取版本更新配置信息 后期可读取redis
 
	function  getloginNoticInfo($id)
	{	 
		$endtime = time();
		$sql = "SELECT id,platformId,title,titlecolor,
	    context,startime,endtime FROM globaldb.tb_login_notice 
	    where platformId = $id  AND endtime>now()" . '  limit 1'; 
	    
		_LOG('post-data11'. $sql,'db');
		
	    //$outid = array('id'=>$id,'endtime'=>$endtime);
	    
	    $statement = query($sql);
	
	    if( $statement && rowcount($statement)>0 )
	    {
			//_LOG('post-data11'. $sql,'db');
	    	$rows = fetch_all($statement);
	    	return $rows[0];
	    }
	} 
	$Inplatform = getloginNoticInfo($id);
	
	//$dataOut = array();
	$dataOut = array
	(
		'status'=>0,
		'result'=>array()
	);

	if (!empty($Inplatform) && count($Inplatform)>0)
	{ 
		$t_id = $Inplatform['id'];
		$t_title =  $Inplatform['title'];
		$t_titlecolor = $Inplatform['titlecolor'];
		$t_platformId = $Inplatform['platformId'];		
		$t_context = $Inplatform['context'];
		$t_startime = $Inplatform['starttime'];
		$t_endtime = $Inplatform['endtime'];
 
		$dataOut = array
		(
			'status'=>0,
			'result'=>array(
				'platformId'=>$t_platformId,
				'title'=>['text'=>$t_title,'color'=>$t_titlecolor],
				'context'=>$t_context,
			)
		); 
		 
		echo   json_encode($dataOut);  
	}else{  
		echo '{"status":"-1","result":"查找的登录公告为不存在！"}';
	}
?>