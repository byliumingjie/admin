<?php
ini_set('display_errors', '1');
include_once '../../include/lib/log.php';
include_once "../../include/lib/mysql.lib.php";
	
if(isset($_GET) && !empty($_GET))
{
	_log('login data get'.json_encode($_GET),'1sdk-log');
}
else
{
	_log('请求数据为空','1sdk-log');
	exit('{"status":"-1","result":"请求数据为空"}');
}
if(empty($_GET['sdk']))
{
	_log('  login sdk is null!','1sdk-log');
	exit('{"status":"-1","result":"sdk为空"}');
}
if(empty($_GET['app']))
{
	_log('  login app is null!','1sdk-log');
	exit('{"status":"-1","result":"app为空"}');
}
if(empty($_GET['uin']))
{
	_log(' login uin is null!','1sdk-log');
	exit('{"status":"-1","result":"uin为空"}');
}
if(empty($_GET['sess']))
{
	_log(' login sess is null!','1sdk-log');
	exit('{"status":"-1","result":"sess为空"}');
}

$url = 'http://sync.1sdk.cn/login/check.html?sdk='.$_GET['sdk'].'&app='.$_GET['app'].'&uin='.$_GET['uin'].'&sess='.$_GET['sess'];

$ret=file_get_contents($url);

if($ret==0)
{
	_log(' get 1sdk login sucess !','1sdk-log');

	exit('SUCCESS');
}
else{
	_log(' get 1sdk login failure !'.$ret,'1sdk-log');
	exit(-1);
}