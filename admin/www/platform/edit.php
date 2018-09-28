<?php
	error_reporting(E_ALL);
	include_once "lib/log.php";
	include_once "lib/util.php";
	include_once "lib/mysql.lib.php";

	// 获取客户端数据 
	if (!empty($_POST) && count($_POST)>0 )
	{
		_LOG('edit file post-data'.json_encode($_POST),'platform-cfg-log');
	}
	else
	{
		_LOG('edit file 请求数据为空','platform-cfg-log');
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

	// 获取热更新配置
	function  editplatformInfo($id,$appVersion,$resVersion)
	{	 
	    $updatesql = update('admin.tb_platform_cfg',
		['appVersion'=>$appVersion,
		'resVersion'=>$resVersion],'id=:id',['id'=>$id]); 
	     
	    if( $updatesql)
	    {
			return true;
	    }
		return false;
	} 
	$ret = editplatformInfo($platId,$appVersion,$resVersion);
	
	if($ret)
	{
		_LOG('edit db true','platform-cfg-log');
		exit('{"status":0,"result":"success"}'); 
	} 
	_LOG('edit db false','platform-cfg-log');
	exit('{"status":"-1","result":"failure"}');

?>