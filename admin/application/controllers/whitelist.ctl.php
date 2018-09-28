<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Whitelist_Controller extends Module_Lib{
    //展示加载
    public function index() {       
        $stServer = Server_Service::getServer();
        $page = empty($_GET['p']) ? 1 : $_GET['p'];
        $pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
        if(!is_array($stServer))
        {
            $this->load_view('white', "");
        }
        $total = Server_Service::getWhiteTotal($stServer);       

        $pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total, $page, $pagesize));
        
        $data = array(
            'pagehtml'=>$pagehtml,
            'whitelist'=>Server_Service::getWhite($stServer,$page,$pagesize),
            'addWhiteAccount' => $this->checkPermission('whitelist/addWhiteAccount'),
            'deleteWhiteAccount' => $this->checkPermission('whitelist/deleteWhiteAccount'),
        );
        $this->load_view('white', $data);
    }
    
    public function addWhiteAccount() {
        $uin = $_POST["uin"];
        if(empty($uin)|| is_numeric($uin) ==false)
        {
            $this->outputJson(-1,"uin必须为数字");
        }
        
        $stServer = Server_Service::getServer();
        if(!is_array($stServer))
        {
            $this->load_view(-1, "服务器加载失败");
        }
        $account = Server_Service::getAccount($uin, $stServer);
        if(count($account) > 1 )
        {
            $this->outputJson(-1,"添加为白名单，当前uin有相同帐号的大于1");
        }
        
        //1表示白名单帐号
        $ret = Server_Service::UpdateAccountState($uin, 1, $stServer);
        if( !$ret)
        {
             $this->outputJson(-1,"更新失败，数据库错误");
        }
        $data = array(
            "uin"=>$uin,
            "accountid"=>$account[0]["accountid"],
            "time"=>  date("Y-m-d h:i:s", time(0)),
            "desc"=>$_POST["desc"]
        );
        $ret = Server_Service::addWhite($data, $stServer);
        if( !$ret)
        {
             $this->outputJson(-1,"插入失败，数据库错误");
        }
        $this->outputJson(0,"添加成功");
    }
    
    public function deleteWhiteAccount() {
        $uin = $_POST["uin"];
        $stServer = Server_Service::getServer();
        if(!is_array($stServer))
        {
            $this->load_view(-1, "服务器加载失败");
        }
        $data = array(
            "uin"=>$uin
        );
        $account = Server_Service::getAccount($uin, $stServer);
        if(count($account) != 1 )
        {
            $this->outputJson(-1,"没有对应的帐号。");
        }
        
        //0表示修改为普通帐号
        $ret = Server_Service::UpdateAccountState($uin, 0, $stServer);
        if(!$ret)
        {
             $this->outputJson(-1,"更新失败，数据库错误");
        }
        
       $ret =  Server_Service::delWhite($data, $stServer);
       if(!$ret)
       {
             $this->outputJson(-1,"删除失败");
        }
        $this->outputJson(0,"删除成功");
    }
}