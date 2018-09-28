<?php

class Server_Service {

    /**
     * @param type $ip       区服所在服务器
     * @param type $sname    区服名称
     * @return type 是否插入成功
     */
    
    //获取帐号服的基本参数
    public static  function getServer() {
        $gzoneid = Helper_Lib::getCookie('gzoneid');       
        $redata =  User_Service::getuser_platform($gzoneid);
        
        $stServer = array(
            "platformname"=>$redata[0]["platformname"],
            "type"=>$redata[0]["type"],
            "platformhost"=>$redata[0]["platformhost"],
            "platformuser"=>$redata[0]["platformuser"],
            "platformdb"=>$redata[0]["platformdb"],
            "platformpwd"=>$redata[0]["platformpwd"],
            "reghost"=>$redata[0]["reghost"],
            "regport"=>$redata[0]["regport"]
        );
        return $stServer;
    }
    
    
    public static function addServer($data,$stServer) {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->insert($data);
    }
    
    //添加白名�?
    public static function addWhite($data,$stServer) {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->insertWhite($data);
    }
    //删除
    public static function delWhite($data,$stServer ){
    $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->delWhite($data);
    }
    /**
     * @param type $PlatformId      平台
     * @param type $sid    区服
     * @return type 是否更新成功
     */
	public static function editServer($data,$PlatformId,$sid,$stServer) {       
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->update($data,$PlatformId,$sid);
    }

    /**
     * 获取白名单
     * @param type $stServer
     * @return type
     */
    public static function getWhite($stServer,$page =1 ,$pagesize=15)
    {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getWhite($page ,$pagesize);
    }

    public static function getWhiteTotal($stServer)
    {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getWhitetotal();
    }
    
    public static function getAccount($uin,$stServer)
    {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getAccount($uin);
    }
    
    public static function UpdateAccountState($uin,$state,$stServer)
    {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->updateAccountStat($uin,$state);
    }
    /**
     * @param type $PlatformId      平台
     * @param type $sid    区服
     * @return type 是否更新成功
     */
	public static function serverState($data,$PlatformId,$sid,$stServer) {    
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->update($data,$PlatformId,$sid);
    }
	
    /**
     * @param type $PlatformId      平台
     * @param type $sid    区服
     * @return type 是否更新成功
     */
	public static function serverInfo($data,$PlatformId,$sid,$stServer) {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->update($data,$PlatformId,$sid);
    }
		
    /**
     * 获取某平台分页后的区服信
     * @param type $page
     * @param type $pagesize
     * @return boolean
     */
    public static function getServers($platform,$stServer,$page = 1, $pagesize = 15) {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getServers($platform,$page, $pagesize);
    }
    
    /**
     * 获得某平台区服信
     * @return boolean
     */
    public static function getAllServers($platform,$stServer) {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getAllServers($platform);
        
    }

    public static function getServerTotal($platform='',$stServer='')
    {
        $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        
        return $server->getServersTotal($platform);
    }

    /**
     * @param type $platform 平台id
     * @param type $sid      区服id
     */
    	public static function getServerByPtAndId($platform, $sid,$stServer) {            
         $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        return $server->getServerBySid($platform, $sid);
    }
    
    public static function gethefuServer($platform, $stServer) {
         $server = new Server_Model($stServer['platformhost'],$stServer['platformuser'],xxtea_lib::decode($stServer['platformpwd']),$stServer['platformdb']);
        $sidarray = $server->getAllServers($platform);
        if(empty($sidarray))
        {
            return -1;
        }
        
        $newArray = array();
        $k = 0;
        for ($i =count($sidarray); $i > 0 ;--$i)
        {            
            for($j = $i-1;$j>0 ;--$j)
            {
                if($sidarray[$j]["zoneserver_ip"] == $sidarray[$i]["zoneserver_ip"] && $sidarray[$j]["zoneserver_port"] == $sidarray[$i]["zoneserver_port"] )
                {
                   $newArray[$k++] =  $sidarray[$j]["sid"];
                }
            }
        }
        
        $hefusid = array();
        $l =0;
        $unArray = array_unique($newArray);
        for( $i = 0; $i < count($sidarray); ++$i)
        {
            if(in_array($sidarray[$i]["sid"], $unArray) )
            {
                continue;
            }
            $hefusid[$l++] = $sidarray[$i];
        }
        
        return $hefusid;
    }

}
