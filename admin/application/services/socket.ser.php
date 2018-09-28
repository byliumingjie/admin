<?php
/**
 * Socket Service
 * @author Administrator
 *****/
class Socket_Service extends Controller
{	
	public static  $_instance = null;
    public static $handler = null;
    
    //-------------------------------------------------
    // 初始化socket链接
    //-------------------------------------------------
	public  static  function InitSocket($Ip,$port)
	{   
        if(empty($Ip)||empty($port))
        {
        	__log_message('InitSocket connect ip port false ','socket-log');
           return false;
        }
        self::$handler = new swoole_client(SWOOLE_TCP);            
		$ret = self::$handler->connect($Ip, $port,5,0);	
		if(!$ret)
		{
			__log_message('InitSocket connect false','socket-log');
           return false;	
		}
		__log_message('InitSocket connect ok','socket-log');
		return true;
	}
	//-------------------------------------------------
	// 发送数据包
	//-------------------------------------------------
	public  static function SendMsg($value,$manager=NULL,
	$action,$serverId =NULL)
	{
		__log_message('InitSocket sendMsg 1','socket-log');
		//self::VerifyToken($value, $action)
		$VerifyData = self::VerifyToken(
		$value,$action,$manager,$action,$serverId);
		 //self::InitSocket($Ip, $port)
		__log_message('InitSocket sendMsg 2'.json_encode($VerifyData),'socket-log');
		$client = self::InitSocket(
		$VerifyData['host'],$VerifyData['port']);
		
		if (!empty($VerifyData['host']) 
		&& !empty($VerifyData['port']) && $client)
		{
			if (!empty($VerifyData['data']))
			{ 		
				$pack_data = pack('a32a*a20',
				$action,$VerifyData['request']);
				__log_message('InitSocket sendMsg 3','socket-log');
				$result = 
				self::$handler->send(
				$VerifyData['sign'],
				$VerifyData['data'],$action);
				__log_message('InitSocket sendMsg 4'.$result,'socket-log');
				if($result)
				{
					__log_message('data SendMsg 11'
					,'socket-server-log');
					return true;
				}
				return false;
			} 
			return false;
		} 
		return false;
	}
	//-------------------------------------------------
	// 接收数据包
	//-------------------------------------------------
	public  static function RecvMsg()
	{
		__log_message('InitSocket RecvMsg 1','socket-log');
       	try
       	{
           $retbuff = self::$handler->recv();
        }
        catch (Exception $e)
        {   __log_message('InitSocket RecvMsg false ','socket-log');
            unset(self::$handler);
            return false;
        }
        if(strlen($retbuff)==0)
        {    
        	__log_message('InitSocket null RecvMsg  ','socket-log');
            return false;
        }
        __log_message('InitSocket 22  ','socket-log');
        $dataOut = unpack('a*',$retbuff);
        __log_message('data-out'.
        json_encode($retbuff),'socket-server-log');  
        __log_message('InitSocket 33  '.json_encode($dataOut),'socket-log');
	    return $dataOut;
	}
	//-------------------------------------------------
	// CURL
	//-------------------------------------------------
    public function request_curl($remote_server,
    $post_string)
    {
       	$ch = curl_init();  
		curl_setopt($ch,CURLOPT_URL, $remote_server);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);  
		curl_close($ch);
		return $data;  
    } 
}
