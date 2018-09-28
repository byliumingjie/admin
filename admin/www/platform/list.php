<?php
	error_reporting(E_ALL);
	include_once "lib/log.php";
	include_once "lib/util.php";
	include_once "lib/mysql.lib.php";

	// $platId = $_POST['platId'];
	// 获取客户端数据 
	if (!empty($_POST) && count($_POST)>0 )
	{
		_LOG('post-data'.json_encode($_POST),'platform-cfg-log');
	}
	else
	{
		_LOG('post 请求数据为空','platform-cfg-log');
		exit('{"status":"-1","result":"请求数据为空"}');
	}	
	$id = (isset($_POST['id']) && !empty( trim($_POST['id']) ))
	?
	$_POST['id'] : exit('{"status":"-1","result":"平台id为空"}');
	
	if (!is_numeric($id))
	{
		exit('{"status":"-1","result":"不存在的平台Id"}');
	}

	// 获取热更新配置
	function  getplatformInfo($id)
	{	 
	    $sql = "SELECT id,`name`,appVersion,resVersion,
	    downloadAppURL,downloadResURL FROM tb_platform_cfg 
	    where id = :id limit 1"; 
	     
	    $outid = array('id'=>$id);
	    
	    $statement = query($sql,$outid);
	
	    if( $statement && rowcount($statement)>0 )
	    {
			//_LOG('post-data11'. $sql,'db');
	    	$rows = fetch_all($statement);
	    	return $rows[0];
	    }
	} 
	$Inplatform = getplatformInfo($platId);
	
	if(count($Inplatform)>0)
	{
		_LOG('post 请求数据为空'.json_encode($Inplatform),'platform-cfg-log');
		exit(json_encode($Inplatform));
	}
	_LOG('failure get platformInfo is null!','platform-cfg-log');
	exit('failure get platformInfo is null!');
?>