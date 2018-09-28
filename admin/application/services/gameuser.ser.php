<?php

/*
 *  游戏用户管理
 */

class Gameuser_Service {
 
    /**
     * 更新游戏用户信息
     * @param type $uid
     * @param type $data
     * @return type
     */
    public static function updateRole($Server, $data) {
        if (!is_array($data) || empty($data)||!is_array($Server) || empty($Server)) {
            return false;
        }
        
        $handler = Socket_Model::getInstance();
	
        $socket = $handler->InitSocket($Server['zoneserver_ip'],$Server['zoneserver_port']);
        if( !$socket )
        {
            return false;	
        }
        
        $sendMsg = $handler->UpdateRoleBase($data);
        $ret = $handler->SendMsg($sendMsg);
        $result = false;
        if( !$ret )
        {
            return false;	
        }
        try
        {
            $RequestServer = new PbProtocolCSMsg();
            $RequestServer->reset();
            $recvResult = $handler->ProcMsg($RequestServer);
            if($recvResult)
            {
                $result = $handler->ProCommondRet($RequestServer);
            }
            unset($RequestServer);
        }catch (Exception $e)
        {	
                unset($handler);
                echo $e->getMessage();	
        }	
        unset($handler);
        return $result;
    }
        //日志记录
        //$logParams = array(
//            'sth' => '用户游戏信息',
//            'resourceId' => $uid,
//            'field' => implode(' | ', $keys),
//            'attr' => implode(' | ', $attr),
//            'attr2' => implode(' | ', array_values($data))
//        );
//        ManagerLog_Service::insertManagerLog($logData, 'edit', $logParams);

     public static function ForbidRole($Server, $data) {
     	 
        if (!is_array($data) || empty($data)||!is_array($Server) || empty($Server)) {
        	 
            return false;
        }
        
        $handler = Socket_Model::getInstance();        
        
        $socket = $handler->InitSocket($Server['zoneserver_ip'],$Server['zoneserver_port']);
        if( !$socket )
        { 
            return false;	
        }
        
        $sendMsg = $handler->ForbidRole($data);
        $ret = $handler->SendMsg($sendMsg);
        $result = false;
        if( !$ret )
        {  
            return false;	
        }
        try
        {
            $RequestServer = new PbProtocolCSMsg();
            $RequestServer->reset();
            $recvResult = $handler->ProcMsg($RequestServer);
            if($recvResult)
            {
                $result = $handler->ProCommondRet($RequestServer);
            }
            unset($RequestServer);
        }catch (Exception $e)
        {	  
                echo $e->getMessage();	
        }	
        unset($handler);
        return $result;
    }
    // 根据角色昵称获取用户Id
    public static function getUserId($platId,$nikeName){
    	
    	$AllplatformInfo = session::get('AllplatformInfo');
    	
    	foreach ($AllplatformInfo as $var)
    	{
    		if ((int)$var['platformId']==$platId && (int)$var['type']==0)
    		{
    			$platConfig = $var;
    		}
    	}
    	$accountModel = new Account_Model();
    	$inplayer = $accountModel->getRoleIndex($platConfig,$nikeName);
    	
    	if (!empty($inplayer))
    	{
    		return 	$inplayer;
    	}
    	return false;
    }
}
