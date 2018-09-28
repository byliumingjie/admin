<?php 

	// 获取对于的通讯区服信息
	function getServerInfo($type,$getRoledb)
	{
		$type = (int)$type;

		$sql = 'SELECT  platformId,platformname,type,platformhost,
		platformport  FROM php_manager.tb_platform 
		WHERE type=:type AND platformId !=0 limit 1';
		
		$prepare_array = array('type'=>$type);

		$statement = $getRoledb->query($sql,$prepare_array);	 

		if( $statement && $getRoledb->rowcount($statement)>0 )
		{		
			$getwechatInfo = $getRoledb->fetch_all($statement);		
			if(count($getwechatInfo)>0)
			{		
				return $getwechatInfo[0];	 
			} 
			_log("getServerInfo  false data null ::".$sql,'1sdk-log');
			return false;
		} 
		_log("getServerInfo  false rowcount ::".$sql.'type::'.$type,'1sdk-log');
		return false;
	} 

	function setPayLog($data,$getRoledb)
	{
		$ret = $getRoledb->insert('globaldb.tb_recharge',$data);
		
		if($ret)
		{
			return true;
		}
		return false;
	}
	function editPayLog($status,$tcd,$db,$ErrorInfo=NULL)
	{
		$Insetdata = ['status'=>$status];
		
		$ret = $db->update('globaldb.tb_recharge',
		$Insetdata,'tcd=:tcd',['tcd'=>$tcd]);
		
		if($ret)
		{
			return true;
		}
		_log($ErrorInfo,'1sdk-log');
		return false;
	}
	// CURL数据通讯

	/*
		 * CURL 
		 * */
	function send_request($url,$data,$coding='gbk', $refererUrl = '', $method = 'POST',
			$contentType ='application/json;', $timeout = 30, $proxy = false)
	{
		$ch = null;
		$data = trim(mb_convert_encoding($data,"gbk","utf-8"));
		//$contentType.='charset='.$coding;
		
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
				$real_url = $url. rawurlencode($data);
			} else{
				$real_url = $url. http_build_query($data);
			}
			$urldata = rawurlencode($data); 
			
			_log("send_request url::".$real_url. urldecode($urldata),'1sdk-log');
			$ch = curl_init($real_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:'.$contentType));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			if ($refererUrl) {curl_setopt($ch, CURLOPT_REFERER, $refererUrl);}
		}
		else{
			$args = func_get_args();
			return false;
		}
		if($proxy) {
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		$ret = curl_exec($ch);
		curl_close($ch);
		//return  $ret;
		//var_dump($ret);
		//return $ret;
		return   mb_convert_encoding($ret,"utf-8","gbk");;
	}

	function VerifyToken($value,$manager=NULL,$action,$dbh,$serverId =NULL)
	{ 
		$key = 'a329cf9547facb1cdac1b206f2432c48';
				  
		if ((empty($value) || count($value)<=0) && empty($action))
		{
			return false;
		}   
		if (is_array($value))
		{
			$Inrequestjson = json_encode($value);			 
			$request = rawurlencode($Inrequestjson);
		}  
		
		$datastr = $key.$manager.$action.$request.$key;		
		_log('str info data'.$datastr,'1sdk-log');
		
		$md5data = md5(strtoupper($key.$request.$key));
		
		_log("md5 data::".$md5data,'1sdk-log');
		
		//$serverId = isset($value['ServerId']) ? $value['ServerId'] : $serverId;
		
		//$serverId = $serverId;
		
		if (isset($value['ServerId'])){ 
			$serverId = $value['ServerId'];
		}elseif (!empty($serverId)){
			$serverId = $serverId; 
		}
		$platform =getServerInfo($serverId,$dbh);
		
		if(empty($platform))
		{

			_log("platform info get false".json_encode($platform),'1sdk-log');
			return false;
		}
		$Header['url'] = 'http://'.$platform['platformhost'].
		':'.$platform['platformport'].'/'.$manager.''.$action.'/';
		_log("url data::".$Header['url'],'1sdk-log');

		
		$Header['request'] = $Inrequestjson.'/'.$md5data;
		
		_log("request data::".$Inrequestjson.$md5data,'1sdk-log');
		
		return  $Header;
	}
	function dataVerify($dataOut)
	{
		if (!is_array($dataOut)) 
		{
			_log("error : getdata not array !",'1sdk-log');
			return false;
			//exit('{"status":"-1","result":" error : resquest Not an array !"}');
		}
		if(isset($dataOut) && !empty($dataOut))
		{
			_log('data get'.json_encode($dataOut),'1sdk-log');
		}
		else
		{
			_log('请求数据为空','1sdk-log');
			exit('{"status":"-1","result":"请求数据为空"}');
		}
		if(empty($dataOut['app']))
		{
			_log(' app is null!','1sdk-log');
			exit('{"status":"-1","result":"app为空"}');
		}
		if(empty($dataOut['cbi']))
		{
			_log(' cbi is null!','1sdk-log');
			exit('{"status":"-1","result":"cbi 为空"}');
		}
		if(empty($dataOut['ct']))
		{
			_log(' ct is null!','1sdk-log');
			exit('{"status":"-1","result":"ct 为空"}');
		}
		if(empty($dataOut['fee']))
		{
			_log(' fee is null!','1sdk-log');
			exit('{"status":"-1","result":"fee 为空"}');
		}
		if(empty($dataOut['pt']))
		{
			_log(' pt is null!','1sdk-log');
			exit('{"status":"-1","result":"pt 为空"}');
		}
		if(empty($dataOut['sdk']))
		{
			_log(' sdk is null!','1sdk-log');
			exit('{"status":"-1","result":"sdk 为空"}');
		}
		if(empty($dataOut['ssid']))
		{
			_log(' ssid is null!','1sdk-log');
			exit('{"status":"-1","result":"ssid 为空"}');
		}
		if(empty($dataOut['st']))
		{
			_log(' st is null!','1sdk-log');
			exit('{"status":"-1","result":"st 为空"}');
		}
		if(empty($dataOut['tcd']))
		{
			_log(' tcd is null!','1sdk-log');
			exit('{"status":"-1","result":"tcd 为空"}');
		}
		if(empty($dataOut['uid']))
		{
			_log(' uid is null!','1sdk-log');
			exit('{"status":"-1","result":"uid 为空"}');
		}
		if(empty($dataOut['ver']))
		{
			_log(' ver is null!','1sdk-log');
			exit('{"status":"-1","result":"ver 为空"}');
		}
		if(empty($dataOut['sign']))
		{
			_log(' sign is null!','1sdk-log');
			exit('{"status":"-1","result":"sign 为空"}');
		}
		
		_log(' dataOut:'.json_encode($dataOut),'1sdk-log');

		return $dataOut;
	}
	function fileLoad($fales,$dataOut)
	{
		if($fales)  
		{   
			$filename = $fales['file']['name'];   
			$tmpname = $fales['file']['tmp_name'];   
			
			$imageName = $dataOut['player_id'].'_'.$dataOut['image_id'].'.png';

			// default load 待处理目录		
			/*
			$fileexist = file_exists(dirname(dirname(__FILE__)).'/facefile/'.$dataOut['server_id'].'/';
			
			if(!file_exists($fileexist.'untreated'))
			{
				mkdir($fileexist,0777);
			}
			if(!file_exists($fileexist.'adopt'))
			{
				mkdir($fileexist,0777);
			}
			if(!file_exists($fileexist.'refuse'))
			{
				mkdir($fileexist,0777);
			}
			// 
			*/
			$url=dirname(dirname(__FILE__)).'/facefile/untreated/'.$imageName;  

			_log("files:::".json_encode($fales),'1sdk-log');

			if( move_uploaded_file($tmpname,$url) )
			{   
			return true;
			_log("datainfo::: ok4 ",'1sdk-log'); 
			} 
			else{
			_log("error : file upload failed! ",'1sdk-log');
			return false;
			//exit('{"status":"-1","result":" error : file upload failed!"}');
			}
		}
		_log("datainfo files ::: false",'1sdk-log');
		return false;
	}

	function setFaceLog($db,$dataOut,$Token){ 
		 
		$dataInfo = array
		(
		'PlatformId'=>$dataOut['platform_id'],
		'ServerId'=>$dataOut['server_id'],
		'PlayerId'=>$dataOut['player_id'],
		'NickName'=>urldecode($dataOut['nickname']),
		'ImageId'=>$dataOut['image_id'],
		'ExclusiveKey'=>$dataOut['unique_key'],
		'createTime'=>date('Y-m-d H:i:s',time()),
		);
		 
		$addItion = " ON DUPLICATE KEY UPDATE  
		ExclusiveKey='{$dataInfo['ExclusiveKey']}',
		type = 1 ,
		ServerId ={$dataInfo['ServerId']} ,
		createTime = now(),
		PlatformId={$dataInfo['PlatformId']},
		NickName = '{$dataInfo['NickName']}' ";

		$ret = $db->insert2('role_face_upload',$dataInfo,$addItion);
		 
		if($ret)
		{  
			_log("录入成功".json_encode($dataInfo),'1sdk-log');
			return true;
			
		}else {
			_log("录入失败".json_encode($dataInfo),'1sdk-log');
			return false;
			//exit('{"status":"-1","result":"failure" }');
		}
	}
	function send($dataOut,$db,$logtrueinfo=null,$falseinfo=null,$messageCode=null)
	{
		
		_log("send info dataInfo :".json_encode($dataOut),'1sdk-log');

		$inHeader = VerifyToken($dataOut,NULL,$messageCode,$db,$dataOut['server_id']);
		
		$ret = send_request($inHeader['url'],$inHeader['request'],'gbk'); 
		
		 _log("server return ret status ".$ret,'1sdk-log');

		$retOut = json_decode(trim($ret),true); 
		
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			 _log($logtrueinfo,'1sdk-log'); 	
			 return true;
		}else{
			 _log($falseinfo,'1sdk-log');
			 return false;
		}
	}
