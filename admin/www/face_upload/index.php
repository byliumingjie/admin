<?php 

// 获取对于的通讯区服信息
function getServerInfo($type,$getRoledb)
{
	$sql = 'SELECT  platformId,platformname,type,platformhost,
	platformport  FROM admin.tb_platform 
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
		return false;
	} 
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
		
		_log("send_request url::".$real_url. urldecode($urldata),'mail');
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
		_log('str info data'.$datastr);
		
		$md5data = md5(strtoupper($key.$request.$key));
		
		_log("md5 data::".$md5data,'VerifyToken');
		
		//$serverId = isset($value['ServerId']) ? $value['ServerId'] : $serverId;
		
		//$serverId = $serverId;
		
		if (isset($value['ServerId'])){ 
			$serverId = $value['ServerId'];
		}elseif (!empty($serverId)){
			$serverId = $serverId; 
		}
		$platform =  getServerInfo($serverId,$dbh);
		
		if(empty($platform))
		{
			_log("platform info get false",'VerifyToken');
			return false;
		}
		
		 
		$Header['url'] = 'http://'.$platform['platformhost'].
		':'.$platform['platformport'].'/'.$manager.''.$action.'/';
		_log("url data::".$Header['url'],'VerifyToken');

		
		$Header['request'] = $Inrequestjson.'/'.$md5data;
		
		_log("request data::".$Inrequestjson.$md5data,'VerifyToken');
		
		return  $Header;
	}
	function dataVerify($dataOut)
	{
		if (!is_array($dataOut)) 
		{
			_log("error : getdata not array !",'face-upload-log');
			return false;
			//exit('{"status":"-1","result":" error : resquest Not an array !"}');
		}
		if (!isset($dataOut['server_id']) || $dataOut['server_id']<0)
		{ 	
			_log("error : illegal of server_id!",'face-upload-log');
			return false;
			//exit('{"status":"-1","result":" error : illegal of server_id!"}');
		}
		if (!isset($dataOut['image_id']) || empty($dataOut['image_id'])){
			_log("error : illegal of image_id!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of image_id!"}');
			return false;
		}
		if (!isset($dataOut['nickname']) || empty($dataOut['nickname'])){
			_log("error : illegal of nickname!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of nickname!"}');
			return false;
		}
		if (!isset($dataOut['platform_id']) || empty($dataOut['platform_id']))
		{ 
			_log("error : illegal of platform_id!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of platform_id!"}');
			return false;
		}
		if (!isset($dataOut['player_id']) || empty($dataOut['player_id']) ){
			_log("error : illegal of player_id!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of player_id!"}');
			return false;
		} 
		if (!isset($dataOut['unique_key']) || empty($dataOut['unique_key']) ){
			_log("error : illegal of unique_key!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of unique_key!"}');
			return false;
		} 
		if (!isset($_FILES) || empty($_FILES) ){
			_log("error : illegal of file!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of file!"}');
			return false;
		}  
		if (!isset($_FILES['file']['name']) || !isset($_FILES['file']['tmp_name']) ){
			_log("error : illegal of file name or file tmp_name!",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of file!"}');
			return false;
		} 
		if (empty($_FILES['file']['name']) || empty($_FILES['file']['tmp_name']) ){
			_log("error : illegal of file name or file tmp_name is null",'face-upload-log');
			//exit('{"status":"-1","result":" error : illegal of file is null!"}');
			return false;
		}
		return true;
	}
function fileLoad($_FILES,$dataOut)
{
	if($_FILES)  
	{  
		_log("datainfo files :::",'face-log');
		$filename = $_FILES['file']['name'];   
		$tmpname = $_FILES['file']['tmp_name'];   
		
		$imageName = $dataOut['player_id'].'_'.$dataOut['image_id'].'.png';
		// default load 待处理目录
		$url=dirname(dirname(__FILE__)).'/facefile/untreated/'.$imageName;  

		_log("files:::".json_encode($_FILES),'face-upload-log');

		if( move_uploaded_file($tmpname,$url) )
		{   
		return true;
		_log("datainfo::: ok4 ",'face-upload-log'); 
		} 
		else{
		_log("error : file upload failed! ",'face-upload-log');
		return false;
		//exit('{"status":"-1","result":" error : file upload failed!"}');
		}
	}
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
		_log("录入成功".json_encode($dataInfo),'face-upload-log');
		return true;
		
	}else {
		_log("录入失败".json_encode($dataInfo),'face-upload-log');
		return false;
		//exit('{"status":"-1","result":"failure" }');
	}
}
function send($dataOut,$db,$logtrueinfo=null,$falseinfo=null)
	{
		$inHeader = VerifyToken($dataOut,NULL,'UpRoleImageInform',$db,$dataOut['server_id']);
		
		$ret = send_request($inHeader['url'],$inHeader['request'],'gbk'); 

		$retOut = json_decode(trim($ret),true); 
		
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			 _log($logtrueinfo,'face-upload-log'); 	
			 return true;
		}else{
			 _log($falseinfo,'face-upload-log');
			 return false;
		}
	}
