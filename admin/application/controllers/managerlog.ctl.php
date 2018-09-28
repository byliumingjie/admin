<?php

/*
 * 
 * 管理员操作日志
 */

class ManagerLog_Controller extends Module_Lib {
	//
	public function index()
	{
		__log_message(" 日志导入：：");
		$data = array();
		$createTime = !empty($_POST['createTime'])
		?
		" createTime>='".$_POST['createTime']."'":"";
		
		$endtime = !empty($_POST['endtime'])
		?
		" createTime<='".$_POST['endtime']."'":"";
		 
		$account = !empty($_POST['account']) 
		? 
		" operator='".$_POST['account']."'":"";
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		$pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
		$platfrom = Platfrom_Service::getServer(true,'admin');
		$userLogmode = new Apilog_Model($platfrom);
		
		if (!empty($createTime) && !empty($endtime) && !empty($account))
		{ 
			$havingAry = array
		    ( $account,
			  $createTime,
			  $endtime );
			
			$indata = '';
			
			foreach ($havingAry as $var)
			{
				if(empty($var)){continue;}
			
				if(!$indata){
					$indata.= " where ".$var;
				}else{
					$indata .= " and ".$var;
				}
			}
			
			$_SESSION['accounthaving'] = $indata;
			
			$total = $userLogmode->getAccountlogTotal(
					$platfrom,$_SESSION['accounthaving']);
			
			$_SESSION['AccountlogTotal'] = $total['total'];
			
			//$userLogmode->getAccountlogTotal($platform,$indata);
		}
		if(!empty($_SESSION['AccountlogTotal']))
		{
			 	
			$Loglist = $userLogmode->getAccountlogList(
			$platfrom,$page, $pagesize,$_SESSION['accounthaving']);
		
			$pagehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['AccountlogTotal'], $page, $pagesize));
				
			$data = array
			(
				'pagehtml'=>$pagehtml,
				'Logdata'=>$Loglist,
			);
		}
		
		$this->load_view('managerLog', $data);		 
	}
	
    public function show() {
        $creatTime = Helper_Lib::getGet('createTime', 'int');
        $endtime = Helper_Lib::getGet('endtime','int');
        $account = Helper_Lib::getGet('account');
        $page = Helper_Lib::getGet('p', 'int', 1);
        $pagesize = Helper_Lib::getGet('pagesize', 'int', 15);
        $aWhere = array();

        $creatTime&$aWhere['createTime'] = $creatTime;
        $endtime&$aWhere['endtime'] = $endtime;
        $account && $aWhere['account'] = $account;
        
        $managerLogs = ManagerLog_Service::getManagerLog($aWhere,$page,$pagesize);
        $total = ManagerLog_Service::getTotal($aWhere);
        $pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($total, $page, $pagesize));
        
        $data = array(
            'pagehtml' => $pagehtml,
            'account'=> $account,
            'logs'  => $managerLogs,
        );
        $this->load_view('managerLog', $data);		
    }
   
}
