<?php

class Platfrom_Service { 
    /**
     * 平台配置信息 
     *@一种通过页面js控制设置cookie　设置通过cookie获取平台配置 ,
     *@一种是直接获取全部完整的不依赖于cookie 但也需要一个全局的key值进行匹配
     *@否则 最终返回第一种不需要处理的配置信息
     *@param logdb type 是否日志配置 
     *@param globaldb  全局配置key
     *
     */
    //获取帐号服的基本参数
    public static  function getServer($logdb=NULL,$globaldb=NULL) 
    {
		$stServer = array();
		// 如果后期不要求对后台账号的平台权限设置 
        $gzoneid = Helper_Lib::getCookie('gzoneid');
        
        if (empty($gzoneid) || $gzoneid<0)
        { 
        	$_COOKIE['gzoneid'] = intval(Session::get('platformPermit'));
        	$gzoneid = $_COOKIE['gzoneid'];
        }  
        $platform = $_SESSION['platformInfo'][$gzoneid];
        
        if($logdb === null && $globaldb===null)
		{  	 
			$stServer = array
			(
				"mysqlhost"=>$platform["platformhost"],
				"mysqluse"=>$platform["platformuser"],
				"mysqlpasswprd"=>$platform["platformpwd"],
				"db"=>$platform["platformdb"],
				"reghost"=>$platform["reghost"],
				"regport"=>$platform["regport"],            
			);
		}
	 
		elseif ($globaldb!=null && $logdb!=null){ 
			  
			$AllplatformInfo = Session::get("AllplatformInfo");			
			foreach($AllplatformInfo as $var){
				 
				if((int)$var['type'] == 0 && $var['platformname']==$globaldb)
				{  
					$stServer = $var;
					//$stServer['db'] = $stServer['platformdb'];
					break;
				} 
			}  
		} 
		elseif ($logdb!=null && $globaldb==NULL){
			 
			$AllplatformInfo = Session::get("AllplatformInfo"); 
			 
			foreach($AllplatformInfo as $var){
					
				
				if((int)$var['type'] == 0 && $var['platformId']==$logdb)
				{ 
					$stServer = array
					(
						"mysqlhost"=>$var["mysqlhost"],
						"mysqluse"=>$var["mysqluse"],
						"mysqlpasswprd"=>$var["mysqlpasswprd"],
						"db"=>$var["db"],
						"mysqlport"=>$var["mysqlport"],
						"platformhost"=>$var["platformhost"],
						"platformport"=>$var["platformport"],
					);  
				}
			}
		}
		// 后期如果在需要对应每个区服都或许host不同 则需要抛出type 为 0  另外 logdb 可以是id 也可以是平台名称 
		// 总之要尽量做到各种配置的高扩展性
		// GET User PLATFROM DB 
		else
		{ 
			$stServer = $platform;
		}
        return $stServer;
    } 
	    /**
     * 统计服务器配置
     * **/
 	public static function get_plat_server($platId=NULL)
 	{ 
 		$Serverinfo = session::get("AllplatformInfo");
 		
 		$serverList = NULL;
 		$ksortServerOut = NULL;
 		
 		foreach ($Serverinfo as $var)
 		{
 			if ((int)$var['type']==0)
 			{
 				continue;
 			}
 			if (!empty($var['type']) && !empty($var['platformId']))
 			{
 				if ($platId!=NULL)
 				{
 					__log_message('平台获取'.$var['type'],"platfrom-log");
 					if(((int)$var['platformId']==$platId))
 					{
 						$serverList [(int)$var['type']] = (int)$var['type'];
 					}
 				} 
 				elseif (empty($platId)) // 获取所以区服信息
 				{
 					__log_message('完整获取'.$var['type'],"platfrom-log");
 					//$serverList [(int)$var['type']] = $var;
 					$serverListOut[] = $var;
 					 
 				}
 			}
 		} 
 		if (empty($platId)){
 			
 			$InsortServer = self::getServerSort($serverListOut);
 			
 			foreach ($InsortServer as $var)
 			{
 				$serverList[] = $var;
 			}
 			return $serverList;
 		}
 		$ksortServerOut = $serverList;
 		 
 		__log_message('获取'.json_encode($ksortServerOut),"platfrom-log");
 		return $ksortServerOut;
 	} 	
 	// 通过区服ID 匹配平台ID
 	public static function server_match_Plat($serverIdList)
 	{
 		if (empty($serverIdList)){ return false; }
 		
 		$Serverinfo = session::get("AllplatformInfo");
 		
 		$serverData =array(); 
 		
 		if ($serverIdList)
 		{ 
 			$serverIdListOut = explode(',', trim($serverIdList));
 			 
 			foreach ($Serverinfo as $inplatInfo)
 			{ 
 				if ((int)$inplatInfo['type']==0){continue;}
 				
 				
 				if (!empty($inplatInfo['type']) && !empty($inplatInfo['platformId']))
 				{ 
 					
		 			foreach ($serverIdListOut as $Inserver){
		 				 
		 				//var_dump($serverIdListOut);
		 				if ((int)$inplatInfo['type']==(int)$Inserver)
		 				{ 
		 					$serverData[(int)$Inserver] = (int)$inplatInfo['platformId'];
		 				}
		 			} 
 				}
 			}
 			return $serverData;
 		}
 	}
 	// 
 	public static  function  getStatDBconfig($platId=NULL)
 	{
 		$platfrom = Platfrom_Service::getServer(true,'admin');
 		
 		$Server_Model = new Servers_Model($platfrom);
 		
 		$dataout = $Server_Model->getServersList($platfrom);
 		  
 		$statDBOut = [] ; 
 		$platdata = [] ; 
 		foreach ($dataout as $var)
 		{
 			if (!empty($var['platformId']) && (int)$var['type'] == 0)
 			{
 				$statDBOut [(int)$var['platformId']] = $var; 
 			} 			
 		}
 		// 根据平台ID获取全局配置
 		if (!empty($platId))
 		{	
 			return  $statDBOut[$platId];
 		}
 		$platOut  = System_Service::getplatformInfo();
 		
 		foreach ($platOut  as $inplat)
 		{
 			$platId = (int)$inplat['id'];
 			
 			if (isset($statDBOut[$platId]))
 			{ 
 				continue;
 			} 
 			$platdata[] = $inplat;
 		}
 		
 		if (!empty($platdata) && count($platdata)>0)
 		{
 			return $platdata;
 		}
 		return  false ; 		 
 	}
 	// 区服排序
 	public static function getServerSort($platOut) 
 	{ 	   
 		$total = count($platOut);
 		$pageSize = $total;
 		$page = 0;
 		// 第一次分割排序
 		$array = self::page_array($total,$page,$platOut,'platformId',$type='asc');
 		
 		return $array;
 	}
 	
 	public static function page_array($count,$page,$array,$orderkey,$type='asc')
 	{
 		global $countpage; #定全局变量
 		$page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
 		$start=($page-1)*$count; #计算每次分页的开始位置
 		if(!empty($orderkey) && !empty($type)){ 
 			$array=self::array_sort($array,"platformId","{$type}");
 		}
 		$totals=count($array);
 		$countpage=ceil($totals/$count); #计算总页面数
 		$pagedata=array();
 		$pagedata=array_slice($array,$start,$count);
 		return $pagedata;  #返回查询数据
 	}
 	public static function array_sort($array,$keys,$type='asc')
 	{
 		if(!is_array($array)||empty($array)||!in_array(strtolower($type),array('asc','desc'))) return '';
 		$keysvalue=array();
 		foreach($array as $key=>$val){
 			$val[$keys]=str_replace('-','',$val[$keys]);
 			$val[$keys]=str_replace(' ','',$val[$keys]);
 			$val[$keys]=str_replace(':','',$val[$keys]);
 			$keysvalue[] =$val[$keys];
 		}
 		asort($keysvalue);//key值排序
 		reset($keysvalue);//指针重新指向数组第一个
 		foreach($keysvalue as $key=>$vals){
 			$keysort[]=$key;
 		}
 		$keysvalue=array();
 		$count=count($keysort);
 		if(strtolower($type)!='asc'){
 			for($i=$count-1;$i>=0;$i--){
 				$keysvalue[]=$array[$keysort[$i]];
 			}
 		}else{
 			for($i=0;$i<$count;$i++){
 				$keysvalue[]=$array[$keysort[$i]];
 			}
 		}
 		return $keysvalue;
 	}
}
