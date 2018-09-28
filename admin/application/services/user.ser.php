<?php

/* 
 * user service
 */

class User_Service{
    
    public static function checkLogin()
    {
        if(!empty($_SESSION['account']))
        {
            return true;
        }
        else if(!empty ($_COOKIE['accountCookie']))
        {
            $_SESSION['account'] = $_COOKIE['accountCookie'];
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function login($account,$pass){
        
    }
    
    public static function register($account,$pass){
        $data = array(
            't_uid'       => Helper_Lib::createUUID(),
            't_account'   => $account,
            't_password'  => Helper_Lib::encodePass($pass),
            't_regtime'   => date("Y-m-d H:i:s",time()),
            't_roleid'    => 2,//默认分组
        );
        
        $db = new User_Model();
        $ret = $db->insert($data);
        if($ret){
            $_SESSION['account'] = $account;
        }
        return $ret;
    }
    
    /** 
     * 获取用户平台
     ***/
    public  static function getuser_platform($platformid,$status=false){
    	 
    	$db = new User_Model();
    	return  $db->getUserPlatform($platformid,$status);	 
    }
    /**
     * add 平台
     * **/
    public static function addPlatform($data){
    	$db = new User_Model();
    	return  $db->addPlatform($data);
    }
    /**
     * 删除游戏平台
     * **/
 	public static function delPlatform($uid) {
        $db = new User_Model;
        $ret = $db->delPlatform($uid);
        return $ret;
    }
   // 编辑平台
   public static function editPlatform($id,$data)
   {
   	$db = new User_Model;
   	$ret = $db->editPlatform($id,$data);
   	return $ret;
   }
    /**
     * 添加用户
     * @param type $data
     * @return boolean
     */
    public static function addUser($data) {
        if(empty($data['t_account'])){
            return false;
        }
        $data['t_uid'] = Helper_Lib::createUUID();
        empty($data['t_roleid']) && $data['t_roleid'] = 2;//默认分组ID=2,
        // DEFAULT_PASS: is default password and 123456
        $data = array(
            't_uid'       => Helper_Lib::createUUID(),
            't_account'   => $data['t_account'],
            't_password'  => Helper_Lib::encodePass($data['t_password']),
            't_regtime'   => date("Y-m-d H:i:s",time()),
            't_roleid'    => $data['t_roleid'],
        	't_usepermission'=>$data['t_usepermission']
        );
        
        $db = new User_Model();
        $ret = $db->insert($data);
        return $ret;
    }
     
    
    public static function updateUser($account,$data) 
    {
        unset($data['t_account']);
        $db = new User_Model();
        $ret = $db->update($account,$data);
        return $ret;
    }
    public static function delUser($uid) {
        $db = new User_Model;
        $ret = $db->delUserByUid($uid);
        return $ret;
    }
    /**
     * 通过帐号查询到的用户信息
     * @param type $account 帐号
     * @return type 返回用户信息
     */
    public static function getUserByAccount($account){
        $db = new User_Model();
        return $db->getUserByAccount($account);
    }
    
    public static function getUserByUid($uid) {
        $db = new User_Model();
        return $db->getUserByUid($uid);
    }
    /**
     * 获得用户信息
     * @param type $page 页数
     * @param type $pagesize 每页显示数
     * @return type
     */
    public static function getUsers($page = 1, $pagesize = 15) {
        $db = new User_Model();
        $rows = $db->getUsers($page, $pagesize);
        //self::formatUserinfo($rows);
        return $rows;
    }
    
    
    
}

