<?php
    set_time_limit(0);//最大执行时间
    date_default_timezone_set('PRC');//日期区域转换
	function  db_connector(){
    $dbconfig['key.gm.db'] = array
    (
    		'host'=>'localhost',
    		'port'=>3306,
    		'user'=>'root',
    		'pass'=>'mns!@#2017',
    		'dbname'=>'admin'
    );
    
    if (!is_array($dbconfig['key.gm.db'])){
        _LOG("dbconfig is null");
        //exit('{"status":-101,"result":"connection failed"}');
    }

    $_dsn  = 'mysql:host=' . $dbconfig['key.gm.db']['host'].';';
    $_dsn .= 'port=' . $dbconfig['key.gm.db']['port'].';';
    $_dsn .= 'dbname=' . $dbconfig['key.gm.db']['dbname'].';';
    $_dsn .= 'charset=utf8';

   try {

        $dbLink = new PDO($_dsn, $dbconfig['key.gm.db']['user'],
        $dbconfig['key.gm.db']['pass'],array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));

        $dbLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		
    } catch (PDOException $e) {
        //var_dump($e->getMessage());
        _LOG('Connection failed: ' . $e->getMessage(),'db');
	
       // exit('{"status":-102,"result":"connection failed"}');
    }
	return  $dbLink;
}