<?php
 
class CacheLog_Controller extends Module_Lib 
{ 
  
	 // api 接口日志
	public function index()
	{ 
		$account = !empty($_POST['account']) 
		? 
		" account ='".$_POST['account']."'":'';
		
		$startTime = $_POST['startTime'] 
		? 
		' createtime>="'.$_POST['startTime'].'"':'';
		
		$endtime = $_POST['endtime'] 
		? 
		' createtime<="'.$_POST['endtime'].'"':'';
		
		$server = !empty($_POST['server']) 
		? 
		'server = '.$_POST['server'] :"";
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
			
		$pagesize = empty($_GET['pagesize']) 
		? 
		10 : $_GET['pagesize'];
			
		$platfrom = Platfrom_Service::getServer(true,'admin');
		
		$adminCachemode = new AdminCache_Model($platfrom);
		
		if(isset($_POST['sut']))
		{
			$havingAry = array
			( $server,
			  $account,
			  $startTime,
			  $endtime );		
							
			if(!empty($channelId) || !empty($account) ||
			!empty($startTime) || !empty($endtime))
			{
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
			}
			$_SESSION['loghaving'] = $indata;
				
			$total = $adminCachemode->getlogTotal(
			$platfrom,$_SESSION['loghaving']);
			$_SESSION['logTotal'] = $total['total'];
		}
		
		if(!empty($_SESSION['logTotal']))
		{ 
			$Loglist = $adminCachemode->getlogList(
			$platfrom,$page, $pagesize,$_SESSION['loghaving']);
				
			$pagehtml = htmlspecialchars(
			Helper_Lib::getPageHtml(
			$_SESSION['logTotal'], $page, $pagesize));
			
			$data = array
			(
				'pagehtml'=>$pagehtml,
				'Logdata'=>$Loglist,
			);
		}
		$this->load_view('admin_cache_log', $data);
	}
	public function delcache()
	{
		$putdata = file_get_contents("php://input");
		$data = json_decode($putdata,true);
		$id = $data['id'];
		
		$platfrom = Platfrom_Service::getServer(true,'admin');
		
		$adminCachemode = new AdminCache_Model($platfrom);
		__log_message("id".$id);      
		if($adminCachemode->delecache($platfrom, $id)){			
			$this->outputJson(0,'删除成功');
		}else{
			$this->outputJson(-1,'删除失败');
		}
	}
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
























