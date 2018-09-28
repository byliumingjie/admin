<?php

/*
 * 区服列表管理维护每个平台的区服列表，开关服
 */

class Server_Controller extends Module_Lib {

    /**
     * 获取所有的区服信息
     */
    public function listServers() {
        $gzoneid = Helper_Lib::getCookie('gzoneid');
        
        $platfrom = Platfrom_Service::getServer(true,'admin');
        
        $stServer = Server_Service::getServer();
        var_dump($stServer);
        //exit();
        $page = empty($_GET['p']) ? 1 : $_GET['p'];
        $pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
        
        $total = Server_Service::getServerTotal($platfrom,$stServer);
        
        $pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total, $page, $pagesize));
        
        $allserver = Server_Service::getAllServers($platfrom,$stServer);
        
        $data = array(
            'pagehtml'=>$pagehtml,
            'whitelist'=>Server_Service::getWhite($stServer),
            'allservers'=>$allserver,
            'servers' => Server_Service::getServers($gzoneid,$stServer,$page, $pagesize),
            'platform'=>$stServer,
            'addServer' => $this->checkPermission('server/addServer'),
            'editServer' => $this->checkPermission('server/editServer'),
            'serverState' => $this->checkPermission('server/serverState'),
            'serverStateUpdate' => $this->checkPermission('server/serverStateUpdate'),
            'serverInfo' => $this->checkPermission('server/serverInfo'),
            'getServers' => $this->checkPermission('server/getServers'),
        );
        $this->load_view('server', $data);
    }

    public function addServer() {
        if (empty($_POST['serverid'])) {
            $this->outputJson(-1, '请填写服务器ID！');
        }
        if (empty($_POST['sname'])) {
            $this->outputJson(-1, '请填写区服名称');
        }
	
        if (empty($_POST['showid'])) {
            $this->outputJson(-1, '请填写服务器显示ID');
        }
		
	if (empty($_POST['zoneserverip'])) {
            $this->outputJson(-1, '请填写游戏服IP');
        }
		
	if (empty($_POST['zoneserverport'])) {
            $this->outputJson(-1, '请填写游戏服端口');
        }
			       
       $iplatform = Helper_Lib::getCookie('gzoneid');
       if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        $data = array(
                'platform' =>$iplatform,
                'sid'=>$_POST['serverid'],
                'sname' =>$_POST['sname'],
                'showid'=>$_POST['showid'],
                'zoneserver_ip' => $_POST['zoneserverip'],
                'zoneserver_port' => $_POST['zoneserverport'],
                'zoneid' => 1,
                'serverStatus' =>1,
                'status'=>isset($_POST['State'])?$_POST['State']:0,
        );
                
        $stServer =  Server_Service::getServer();  
        try {
                $ret = Server_Service::addServer($data,$stServer);
                if (!$ret) {
                    $this->outputJson(-2, '数据库执行失败！' );
                }

                $result = Game_Service::addServer($iplatform,$_POST['serverid'],1,$stServer); //1表示添加服务器
                if (!$result) {
                    $this->outputJson(-2,$result. '通知服务器失败！');
                }
           
        
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }

        $this->outputJson(0, '添加成功');
    }

public function editServer() {
		
        if (empty($_POST['serverid'])) {
            $this->outputJson(-1, '请填写服务器ID！');
        }
        if (empty($_POST['sname'])) {
            $this->outputJson(-1, '请填写区服名称');
        }
	
        if (empty($_POST['showid'])) {
            $this->outputJson(-1, '请填写服务器显示ID');
        }
		
	if (empty($_POST['zoneserverip'])) {
            $this->outputJson(-1, '请填写游戏服IP');
        }
		
	if (empty($_POST['zoneserverport'])) {
            $this->outputJson(-1, '请填写游戏服端口');
        }
        
        if ($_POST['State']> 4|| $_POST['State'] <0) {
            $this->outputJson(-1, '请填写客户端状态');
        }
        
        $data = array(
            'sid'=>$_POST['serverid'],
            'sname' =>$_POST['sname'],
            'showid'=>$_POST['showid'],
            'zoneserver_ip' => $_POST['zoneserverip'],
            'zoneserver_port' => $_POST['zoneserverport'],
            'status'=>$_POST['State'],
        );
 
        
        $iplatform = Helper_Lib::getCookie("gzoneid");
        if($iplatform == 0)
        {
           $this->outputJson(-1, '平台验证出错！' ); 
        }
        
        $stServer =  Server_Service::getServer();
        try {
                $ret = Server_Service::editServer($data,$iplatform,$_POST['serverid'],$stServer);
                if (!$ret) {
                    $this->outputJson(-2, '数据库执行失败！');
                }

                $result = Game_Service::addServer($iplatform,$_POST['serverid'],2,$stServer); //1表示添加服务器
                if (!$result) {
                    $this->outputJson(-2,$result. '通知服务器失败！');
                }
   
            
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }
        
        $this->outputJson(0, '修改成功');
    }
	
	
    public function serverState() {
        if (empty($_POST['showid'])) {
            $this->outputJson(-1, '请填写服务器显示ID');
        }
	if (empty($_POST['serverStatus'])) {
            $this->outputJson(-1, '请选中服务器状态');
        }
	if (empty($_POST['sname'])) {
            $this->outputJson(-1, '请填写服务器名称');
        }

        if ($_POST['State']> 4|| $_POST['State'] <0) {
            $this->outputJson(-1, '请填写客户端状态');
        }
        
        $data = array(
        'showid'=>$_POST['showid'],
        'serverStatus' => $_POST['serverStatus'],
        'server_info' => $_POST['desc'],
        'sname' =>$_POST['sname'],
        'status'=>$_POST['State'],
        );
        
        $iplatform = Helper_Lib::getCookie("gzoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        $stServer =  Server_Service::getServer();
        try {
            
            $ret = Server_Service::serverState($data,$iplatform,intval($_POST['serverid']),$stServer);
            if (!$ret) {
                $this->outputJson(-2, '数据库执行失败！');
            }
             
            $result = Game_Service::addServer($iplatform,$_POST['serverid'],2,$stServer);//2表示修改服务器信息
            if (!$result) {
                $this->outputJson(-2,$result. '通知服务器失败！');
            }
    
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }  
        
        $this->outputJson(0, '修改成功');
    }

    public function serverStateUpdate() {		        
        $iplatform = Helper_Lib::getCookie("gzoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        if ($_POST['serverStatus'] == 3 && empty( $_POST['desc']) ) {
            
           $this->outputJson(-1, '请填写关服说明');
        }   
        if (empty($_POST['serverStatus'])) {
            $this->outputJson(-1, '请服务器状态');
        }
        if ($_POST['State']> 4|| $_POST['State'] < 0) {
            $this->outputJson(-1, '请填写客户端状态');
        }
        if (empty($_POST['serverlist'])) {
            $this->outputJson(-1, '请勾选服务器');
        }
        $stServer =  Server_Service::getServer();
        $data = array(
        'serverStatus' =>$_POST['serverStatus'],
        'server_info' => $_POST['desc'],
        'status'=>$_POST['State'],
        );
        
        $sidarray = "";
        $arrlist = explode(",",$_POST['serverlist']);
        for($i = 0; $i < count($arrlist);++$i)
        {
            $sidarray .= $arrlist[$i] . "|";
            $ret = Server_Service::serverState($data,$iplatform,$arrlist[$i],$stServer);
            if (!$ret) {
                $this->outputJson(-2, '数据库执行失败！');
            }
        }    
              
        $result = Game_Service::addServer($iplatform,1,3,$stServer);//3表示加载所有服务器信息
        if (!$result) {
            $this->outputJson(-2,$result. '通知服务器失败！');
        }

        $this->outputJson(0, '操作成功');
    }
    
public function serverInfo() {
    
        if (empty($_POST['serverid'])) {
        $this->outputJson(-1, '请填写服务器ID');
        }

        if (empty($_POST['sname'])) {
        $this->outputJson(-1, '请填写服务器名称');
        }
        $data = array(
        'server_info' => $_POST['desc'], 
        'sname' =>$_POST['sname'],
        );
         
        $iplatform = Helper_Lib::getCookie("gzoneid");
        if($iplatform == 0)
        {
           $this->outputJson(0, '平台验证出错！' ); 
        }
        
        $stServer =  Server_Service::getServer();
        try {
                $ret = Server_Service::serverInfo($data,$iplatform,intval($_POST['serverid']),$stServer );
                if (!$ret) {
                    $this->outputJson(-2, '数据库执行失败！');
                }

                $result = Game_Service::addServer($iplatform,$_POST['serverid'],2,$stServer);//2表示修改服务器信息
                if (!$result) {
                    $this->outputJson(-2,$result. '通知服务器失败！');
                }
  
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(), $e->getMessage());
        }
        $this->outputJson(0, '修改成功');
    
    }
}