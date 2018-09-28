<?php 
	set_time_limit(0);	 
	/**
	 * 获得配置信息
	 * **/ 
	function get_cfg($key,$code,$config)
	{	 
		if(isset($config[$key][$code]))
		{  
			return $config[$key][$code];
		} 
		_LOG("Protocol error code");
		exit('{"status":"false","result":"Protocol error code"}');
	} 
	
	/**
	 * response 响应
	 * @param type $data 服务器响应的信息 
	 * @param type $status 响应状态  成功返回 0 
	 * @param type $dbLink connect
	 * @param type array() $logdata 日志记录数据对应 db 字段配置
	 * @param type $protocolCode 请求码
	 * */ 
	function  responseStatus($data,$status,$dbLink, 
	$logdata= array(),$protocolCode)
	{   
		  
		if( isset($data['status']) && isset($data['result']) 
		&& is_array($data) && !empty($data) )
		{
			unset($data['status']);
		}	 
		
		if ($status == 0 )
		{   
			if(count($data)>0 && !isset($data['status']))
			{ 
				if(isset($data['result']))
				{
					$data['status'] =$status; 
					$indata = $data;
				}else {	 
					$indata = array
					(
						'status'=>$status,
						'result'=>$data,
					);
				}
				
			}
			else
			{	
				$indata = array
				(
					'status'=>$status, 
				);
			} 
			 
		}else {
			$indata = $data;
		} 		 
		 
		// 如果系统获取不需要记录日志
		if($protocolCode!=108 && $protocolCode!=109)
		{  
			 
			if(!empty($logdata['server']) && $protocolCode!=106)
			{	
				insertmanagerLog($status,
				$logdata['source'],$logdata['server'],$logdata['operator']
				,json_encode($logdata),json_encode($indata),$dbLink,$protocolCode );
			}else{
				insertmanagerLog($status,
						$logdata['source'],'',$logdata['operator']
						,json_encode($logdata),json_encode($indata),$dbLink,$protocolCode );
			}
			
		} 
		unset($data);		 
		unset($logdata);
		unset($status);
		return  json_encode($indata);
	}
	
	function  responseStatus2($data,$status,$dbLink,
			$logdata= array(),$protocolCode)
	{
	
		if( isset($data['status']) && isset($data['result'])
				&& is_array($data) && !empty($data) )
		{
			if ($protocolCode!=106 && $protocolCode!=107 && $protocolCode!=108 && $protocolCode!=109){
				unset($data['status']);
			}
		}
	
		if ($status == 0 )
		{
			if(count($data)>0 && !isset($data['status']))
			{
				if ($protocolCode!=106 && $protocolCode!=107 && $protocolCode!=108 && $protocolCode!=109){
					$indata = array
					(
							'status'=>$status,
							'result'=>$data,
					);
				}else {
					$indata = $data;	
				}
			}
			else
			{
				$indata = array
				(
						'status'=>$status,
				);
			}
	
		}else {
			$indata = $data;
		}
			
		// 如果系统获取不需要记录日志
		if($protocolCode!=108 && $protocolCode!=109)
		{
	
			if(!empty($logdata['server']) && $protocolCode!=106)
			{
				insertmanagerLog($status,
						$logdata['source'],$logdata['server'],$logdata['operator']
						,json_encode($logdata),json_encode($indata),$dbLink,$protocolCode );
			}else{
				insertmanagerLog($status,
						$logdata['source'],'',$logdata['operator']
						,json_encode($logdata),json_encode($indata),$dbLink,$protocolCode );
			}
				
		}
		unset($data);
		unset($logdata);
		unset($status);
		return  json_encode($indata);
	}
	
	/**
	 * request 请求处理 
	 * */
	function  requestProcess( $data ){		 
		
		if( isset($data) )
		{ 
			$jsondata = json_decode($data,true); 
			 
			if(isset($jsondata) && 
			is_array($jsondata) && !empty($jsondata))
			{	 
				return $jsondata;
			}
			_LOG("core.function.php function 
			requestProcess line 66 jsondata Is not set ");
			exit('{"status":"false","result":"request was malformed"}');
		}		
		_LOG("core.function.php function 
				requestProcess line 69 data Is not set ");
		
		exit('{"status":"false","result":"data Is not"}');
		
	}  
	/**
	 * @version Curl 2 
	 * 发送HTTP请求	 *
	 * @param string $url 请求地址
	 * @param string $method 请求方式 GET/POST
	 * @param string $refererUrl 请求来源地址
	 * @param array $data 发送数据
	 * @param string $contentType
	 * @param string $timeout
	 * @param string $proxy
	 * @return boolean
	 */
	 // 
	function send_request($url,$data, $refererUrl = '', $method = 'GET',
	$contentType ='application/json;charset=utf-8', $timeout = 30, $proxy = false) 
	{ 
		$ch = null;			
		if('POST' === strtoupper($method)) 
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_HEADER,0 ); 
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
			$info = curl_getinfo($ch); 
			if ($refererUrl) {
				curl_setopt($ch, CURLOPT_REFERER, $refererUrl);  
			}
			$contentType = '';
			if($contentType) {
				curl_setopt($ch, CURLOPT_HTTPHEADER,$contentType);					
			}
			if(is_string($data)){				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
				
			} else {			
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}
		} 
		else if('GET' === strtoupper($method)) 
		{			
			if(is_string($data)) {
				$real_url = $url. (strpos($url, '?') === false ? '' : ''). rawurlencode($data);					
			} else{
				$real_url = $url. (strpos($url,'') === false ? '' : ''). http_build_query($data);
			}
			$ch = curl_init($real_url);				
			curl_setopt($ch, CURLOPT_HEADER, 0);				 
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:'.$contentType));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);				
			if ($refererUrl) {curl_setopt($ch, CURLOPT_REFERER, $refererUrl);}
		} else{
			$args = func_get_args();
			return false;
		}		
		if($proxy) {			  
		    curl_setopt($ch, CURLOPT_PROXY, $proxy); 
		}		  	
		$ret = curl_exec($ch);  
		curl_close($ch); 
		return $ret;
	}
	/****************************************************************
	 *PHP CURL Multithreading  GET/POST
	 *curl(array('url?get=data','url'),array('','post_data'));						 
	 ****************************************************************/
	function curl($urls,$protocolCode) 
	{
		$queue = curl_multi_init();
		$map = array();
		foreach ($urls as $key => $var) 
		{
			$url = "http://".$var['zoneserver_ip'].":".$var['zoneserver_port'].'/';
			$url .=$protocolCode."&".$var['requestData'];
			 
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);//
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);//
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//
			curl_setopt($ch, CURLOPT_POSTFIELDS, '');//$post[$key]
			curl_setopt($ch, CURLOPT_HEADER, 0);//
			curl_setopt($ch, CURLOPT_NOSIGNAL, true);
			curl_multi_add_handle($queue, $ch);
			$map[(string) $ch] = $key;
			 
		}
		$responses = array();
		do {
			while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;
			if ($code != CURLM_OK) { break; }
			while ($done = curl_multi_info_read($queue)) {
				$error = curl_error($done['handle']);
				_LOG("all curl key ::".$key);
				$result = curl_multi_getcontent($done['handle']);
				
				if (!empty($error))
				{
					$responses[$map[(string) $done['handle']]] = compact('error', 'result');
				}else
				{
					$responses[$map[(string) $done['handle']]] = compact('result');
				}
				 
				curl_multi_remove_handle($queue, $done['handle']);
				curl_close($done['handle']);
			}
			if ($active > 0) {
				curl_multi_select($queue, 0.5);
			}
		} while ($active);
		curl_multi_close($queue);
		return $responses;
	}
	
	/**
	 * insert server info 
	 * **/
	function addServer($value,$protocolCode,$dbLink)
	{	 
		if(empty($dbLink) || empty($value) || empty($protocolCode))
		{ 
			_LOG("error:Undefined ",'select-log');			
			exit('{"status":"error","result":"empty variables"}');			
		}
		$datainfo = '';			
		// 此 $value 为 json 字符
		$outArray = json_decode($value,true);		 
	 
		foreach ($outArray as $var)
		{
			if(is_array($var) && count($var[0])>0)
			{ 
				(count($var)>30)
				?
				exit('{"status":"false","result":"beyond the limit"}')
				:"";
		
				foreach ($var as $Invalue){					
					$datainfo[] = array(
						$Invalue['sid'],
						"'".$Invalue['sname']."'",
						$Invalue['status'],
						time(),
						"'".$Invalue['zoneserver_ip']."'",
						$Invalue['zoneserver_port'],
						"'".$Invalue['server_desc']."'",
					);
				}
			}
		} 	  
		
		$ret = insertBatch('tb_server', 'sid,sname,status,createTime,
		zoneserver_ip,zoneserver_port,server_desc',$datainfo, $dbLink);
		 
		if($ret == true){			
			$responseinfo = responseStatus('true',0,$dbLink,$outArray,$protocolCode);
			
		}else {
			$responseinfo = responseStatus('false',-106,$dbLink,$outArray,$protocolCode);			
		}		
		return $responseinfo;
	}
		/**
		 * get server
		 * 如果系统本身要获取服务器配置要,本身不需要录入日志后期可以优化或者凡是经过就要进行统计了
		 * @ 但是这样一来就分不清到底是系统还是所以可以在进行定义两个系统指令码
		 * */
		function getServer($request,$protocolCode,$dbLink)
		{	 
			  
			if(empty($request) || empty($dbLink) ||
			empty($protocolCode) || !isset($request['server']))
			{
				_LOG("error:Undefined ",'select-log');
				exit('{"status":"error","result":"error:Undefined "}'); 
			} 
			if($sid = $request['server'])
			{		
				
				if($protocolCode == 107 || $protocolCode ==108)
				{					 
					$outSid = explode(',', $sid);
					  
					$placeholders = str_repeat ('?, ',  count ($outSid) - 1) . '?';
					 
					$sql = "select sid,sname,status,createTime,zoneserver_ip,
					zoneserver_port from  globaldb.tb_server where sid in($placeholders)";	
				 
				}
				if($protocolCode == 105 || $protocolCode ==109 )
				{
					$sid = (int)$sid;
					$sql = "select sid,sname,status,createTime,zoneserver_ip,
					zoneserver_port from globaldb.tb_server where sid = :sid";
					
					$outSid = array('sid'=>$sid);
				}				
				 
				if( $statement = query( $sql,$outSid,$dbLink ) )
				{	 
					$Indata = fetch_all($statement); 
					
					$responseinfo = responseStatus($Indata,0,$dbLink,$request,$protocolCode);				
					return $responseinfo;  
				} 				
				_LOG("mysql error:query false ".$sql,'db');
				$responseinfo = responseStatus('false',-107,$dbLink,$request,$protocolCode);
				
				return $responseinfo;				
			}
			else
			{  _LOG('{"status":"error","result":"server is null"}','db');
				exit('{"status":"error","result":"server is null"}');				
			} 
		}
 		/**
 		 * 区服修改
 		 * **/
 		function editServer($request,$protocolCode,$dbLink)
 		{
 			$data = array();
 			
 			$ineditServer = json_decode($request,true); 
 			 
 			if(is_array($ineditServer) && !empty($ineditServer))
 			{
 				foreach ($ineditServer as $key=>$var)
 				{ 				
 					if( $key=='sid' ||  $key=='sname' || $key=='status' || $key=='zoneserver_ip' 
 					|| $key=='zoneserver_port' || $key=='server_desc' )
 					{ 		 
 						$data[$key] = $var;
 					}
 				}	 
 			} 
 			 
 			$ret = update('tb_server',$data,'sid=:sid',array('sid'=>$data['sid']),$dbLink);
 			
 			if($ret == true){
 				$responseinfo = responseStatus(null,0,$dbLink,$ineditServer,$protocolCode); 
 				
 			}else{
 				
 				$responseinfo = responseStatus('edit false',-110,$dbLink,$request,$protocolCode);
 			}
 			return $responseinfo;
 		}
		/**
		 * @param $data
		 * @param $data['source'] 请求来源 详情查看GM api文档 1-网易 2-萌宫坊后台
		 * @param $data['protocolCode'] 请求协议码
		 * @param $data['sname'] 区服信息
		 * @param $data['playerId'] 用户ID
		 * @param $data['account'] 账号
		 * @param $data['requestData'] 请求数据
		 * @param $data['ResponseData'] 响应数据
		 * **/ 
		function managerLog($data,$dbLink,$protocolCode)
		{
			//$info = '';
			
			if(empty($data) ||  empty($dbLink) || empty($protocolCode) ){
				_LOG("error:Undefined 276",'select-log');
				exit('{"status":"false","result":"undefined variable lin 276"}'); 
			}
			if(!is_array($data) && count($data)<0)
			{
				_LOG("Empty array lin 282",'select-log');
				exit('{"status":"false","result":"empty array lin 282"}'); 		
			}
			 
			if(!$dbLink)
			{
				_LOG("error:The MySQLi connection is invalid 289",'select-log');
				exit('{"status":"false","result":"connection is invalid 289"}');
			}else { 			 
				if(insert('tb_manager_log',$data,$dbLink)==false){
					exit('{"status":"false","result":"connection is invalid 289"}'); 
				} 
			} 
		}
 
		/**
		 *查询指定用户
		 *@param $requestData 请求的数据的参数
		 *@param $protocolCode 请求的协议码
		 ***/
		function request( $requestData,$protocolCode,$dbLink )
		{
			if(empty($requestData) || empty($protocolCode)
			|| empty($dbLink)){
				_LOG("error:Undefined lin 307");
				exit('{"status":"false","result":"undefined variable line 307"}');				 
			}
			// 请求处理对json字符转换为数组格式
			$jsonArray= requestProcess( $requestData );
			 
			$inserver = json_decode( getServer( $jsonArray,108,$dbLink ),true) ;
		
			if( !empty($inserver['result'][0]['zoneserver_ip']) 
			 && !empty($inserver['result'][0]['zoneserver_port']) )
			{ 
				 
				$url = "http://".$inserver['result'][0]['zoneserver_ip'].":".
				$inserver['result'][0]['zoneserver_port']."/";
				
				$requestJson = $protocolCode.'&'.json_encode($jsonArray);				 
				
				$serverdata = send_request($url,$requestJson,'','GET');				 
				
				unset($inserver);
				 
				$jsonOut = json_decode($serverdata,true);
				
				$responseinfo = responseStatus($jsonOut,$jsonOut['status'],
				$dbLink,$jsonArray,$protocolCode);
				
				return $responseinfo; 
			 
			}
			_LOG("error:Empty service address");
			exit('{"status":"error","result":"empty service address"}'); 
		}
		/**
		 * 日志记录
		 * **/
		function  insertmanagerLog($status,$source,
		$sname,$account,$requestData,$ResponseData,$dbLink,$protocolCode)
		{			
			 
			$logOut = array
			(
				'source'=>$source,
				'protocolCode'=>$protocolCode,
				'sname'=>$sname,
				'account'=>$account,					
				'RequestData'=>$requestData,
				'ResponseData'=>$ResponseData,
				'ExecutionState'=>$status,
				'create_time'=>time(),
				'RequestIp'=>get_real_ip()
			);
			managerLog($logOut,$dbLink,$protocolCode);			
		}  
		/**
		 * 后期多服接口
		 * **/
		function allrequest( $requestData, $protocolCode, $dbLink )
		{
			$requestOut = json_decode( $requestData,true ); 
		 
			foreach ( $requestOut as $var )
			{ 
				$source = $requestOut['source'];
				$operator = $requestOut['operator'];
				$code = $requestOut['code'];
				
				if( is_array( $var ) )
				{
					foreach ($var as $invalue){
						 
						if( !empty( $invalue['server'] ) ){						
							$Indata[$invalue['server']] = json_encode( $invalue );
						} 						
					}				
				}  
			}
			 
		 	$sid['server'] = implode(',',array_keys($Indata));
			   
			$serverinfo  = json_decode(getServer( $sid, 108, $dbLink ),true);
			
			foreach ($serverinfo['result'] as $var){
				
				foreach ($var as $value){
					foreach ($Indata as $key=>$invar){
							
						if($key == $value['sid']){
					
							$dataOut[] = [
							'zoneserver_ip'=>$value['zoneserver_ip'],
							'zoneserver_port'=>$value['zoneserver_port'],
							'sid'=>$value['sid'],
							'requestData'=>$invar,
							'protocolCode'=>$protocolCode,
							];							 
						}
					}					
				}  
			} 
			 //<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			return curl($dataOut,$protocolCode);			
		}
		// get request ip 
		function get_real_ip()
		{
			$ip=false;
			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
				if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
				for ($i=0; $i < count($ips); $i++){
					if(!eregi ('^(10│172.16│192.168).', $ips[$i])){
						$ip=$ips[$i];
						break;
					}
				}
			}
			return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
		} 