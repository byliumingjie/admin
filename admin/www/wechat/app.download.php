<?php
	// app download 下载
	//include 'core.function.php';
	include_once 'include/lib/log.php';

	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	_log(" server user  agent$$$$$##@@".$agent,'wecat-log-core1');
	$type = 'http://www.minosgame.com';
	//分别进行判断
	if(strpos($agent, 'iphone') || strpos($agent, 'ipad'))
	{
	 $type = 'https://fir.im/983b';
	}  
	if(strpos($agent, 'android'))
	{
	 $type = 'https://fir.im/pqsd';
	}

	//$url = get_device_type();
	$url = $type;
	_log(" server user  data$$$$$##@@".$url,'wecat-log-core1');
	Header("Location: $url"); 
	exit;