<?php

class System_Service {

    /**
     * @param type $ip       区服所在服务器
     * @param type $sname    区服名称
     * @return type 是否插入成功
     */
    
    //获取帐号服的基本参数
    public static  function getplatformInfo($arrange=FALSE) 
    {
    	$db = new System_Model();
    	
    	$platOut =  $db->getplatformInfo();
    	
    	if ($platOut)
    	{
	    	if ($arrange==FALSE)
	    	{    	
	    		return  $platOut;
	    	}	    	
    		$Indata = array();
    		
    		foreach ($platOut as $var){
    			
    			$Indata[$var['id']] = $var; 
    		}
    		return $Indata;	    	
    	}
    	__log_message('getplatformInfo boole fasel'.$platOut,'db');
    	return false; 
    } 
     
}
