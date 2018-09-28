<?php

class ApiLog_Controller extends Module_Lib 
{ 
  
	public function Index($data=array())
	{   
		 
         $this->load_view("api_log",$data);
	} 
	 // api 接口日志
	public function messageLog()
	{
		$channelId = !empty($_POST['channelId'])?' source='.$_POST['channelId']:'';
		$account = !empty($_POST['account'])?" account ='".$_POST['account']."'":'';
		$startTime = $_POST['startTime']?' create_time>='.strtotime($_POST['startTime']):'';
		$endtime = $_POST['endtime']?' create_time<='.strtotime($_POST['endtime']):'';
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
			
		$pagesize = empty($_GET['pagesize']) ? 10 : $_GET['pagesize'];
			
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		
		$apiLogmode = new Apilog_Model($platfrom);
		
		if(isset($_POST['sut']))
		{ 
			$havingAry = array
			( $channelId,
			  $account,
			  $startTime,
			  $endtime					
			);		
							
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
				
			$total = $apiLogmode->getlogTotal(
			$platfrom,$_SESSION['loghaving']);
			$_SESSION['logTotal'] = $total['total'];
		}
		
		if(!empty($_SESSION['logTotal']))
		{
			$total = $apiLogmode->getlogTotal($platfrom);
			
			$Loglist = $apiLogmode->getlogList(
			$platfrom,$page, $pagesize,$_SESSION['loghaving']);
				
			$pagehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['logTotal'], $page, $pagesize));
			
			$data = array
			(
				'pagehtml'=>$pagehtml,
				'Logdata'=>$Loglist,
			);
		}
		$this->load_view('api_log', $data);
	}
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}
























