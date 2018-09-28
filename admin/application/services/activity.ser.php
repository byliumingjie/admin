<?php

class Activity_Service {

    /**
     * @param type $ip       区服所在服务器
     * @param type $sname    区服名称
     * @return type 是否插入成功
     */
    
    //获取帐号服的基本参数
    public static  function getActivityCfgInfo() {
    	$db = new GameActivity_Model();
    	return  $db->getActivityCfgInfo();
    }
    // 获取待发布活动
    public static function getActivityInfo()
    {
    	
    	$db = new GameActivity_Model();
    	return  $db->getActivityInfo();
    }
    // get global activity 
    public static function getGlobalActivityInfo(){
    	  
    	$db = new GlobalActivity_Model();
    	return  $db->getGlobalActivityInfo(); 
    }
    
    public static function getMallInfo()
    {
    	$db = new Mall_Model();
    	return  $db->getMallInfo();
    }
     
}
