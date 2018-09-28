<?php

class Accountreplace_Service {

    /**
     * @param type $ip       区服所在服务器
     * @param type $sname    区服名称
     * @return type 是否插入成功
     */
/*     //获取帐号服的基本参数
    public static  function getServer($logdb=NULL,$globaldb=NULL) 
    {
		$stServer = array();
        $gzoneid = Helper_Lib::getCookie('gzoneid'); 
        $platform = $_SESSION['platformInfo'][$zoneid];
       
        if($logdb === null && $globaldb===null)
		{ 		
			 
			$stServer = array
			(
				"mysqlhost"=>$platform["platformhost"],
				"mysqluse"=>$platform["platformuser"],
				"mysqlpasswprd"=>$platform["platformpwd"],
				"db"=>$platform["platformdb"],
				"reghost"=>$platform["reghost"],
				"regport"=>$platform["regport"],            
			);
		}
		// GET GLOBAL(customize) DB 
		elseif ($globaldb!=null && $logdb!=null){ 
			 
			$AllplatformInfo = Session::get("AllplatformInfo");			
			foreach($AllplatformInfo as $var){
				if((int)$var['type'] === 0 && $var['platformname']===$globaldb)
				{
					 
					$stServer = $var;
					$stServer['db'] = $stServer['platformdb'];
					break;
				} 
			}  
		}
		// GET User PLATFROM DB 
		else
		{
			 
			$stServer = $platform;
		}
        return $stServer;
    }  */
     
}
