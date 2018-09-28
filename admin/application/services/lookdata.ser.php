<?php

class LookData_Service {
    public static function getServer($platform) {
        $db = new AccountDB_Model();
        return $db->getServer($platform);
    }  
    public static function getOneServer($platform,$sid) 
    {
    	//__log_message("getOneServer");
        $db = new AccountDB_Model();       
        return $db->getOneServer($platform,$sid);
    } 
    
    public static function getPlatform() {
        $db = new AccountDB_Model();       
        return $db->GetPlatform();
    }
    public static function getRoleName($uin,$stServer) {
        $db = new LoadData_Model($stServer['roleip'],$stServer['roleuser'],  xxtea_lib::decode($stServer['rolepwd']),$stServer['roledb']);
        return $db->getName($uin);
    }

    public static function getRoleUin($strname,$stServer) {
        $db = new LoadData_Model($stServer['roleip'],$stServer['roleuser'],  xxtea_lib::decode($stServer['rolepwd']),$stServer['roledb']);
        return $db->getUin($strname);
    }
    
    public static function getRoleByRoleid($roleid,$stServer) {
        $db = new LoadData_Model($stServer['roleip'],$stServer['roleuser'],  xxtea_lib::decode($stServer['rolepwd']),$stServer['roledb']);
        return $db->getRolebyID($roleid);
    }
    
    public static function getAccountUin($accountid,$stServer) {
        $db = new LookData_Model($stServer['ip'],$stServer['user'],  xxtea_lib::decode($stServer['pwd']),$stServer['db']);
        return $db->getUin($accountid);
    }
    
    public static function getAccount($uin,$stServer) {
        $db = new LookData_Model($stServer['ip'],$stServer['user'],  xxtea_lib::decode($stServer['pwd']),$stServer['db']);
        return $db->getAccount($uin);
    }
   
}

