<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RoleConfig_Service{
    public static  function getRoleServerbyID($platformid,$sid) {
        $db = new RoleConfig_Model();
        return $db->getRoleConfigbyId($platformid, $sid);
    }
    
    public static function getRoleServer($platformid) {
        $db = new RoleConfig_Model();
        return $db->getRoleConfig($platformid);
    }
    
    public static function getRoleNumber($stServer) {
        $db = new RoleQuery_Model($stServer['roledbhost'],$stServer['roledbuser'],xxtea_lib::decode($stServer['roledbpwd']),$stServer['roledbname']);
        return $db->getRole();
    }
}