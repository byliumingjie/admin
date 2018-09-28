<?php
#by liumj 2016-06-29 add 
class  Socket{

	private static $_instance = null;
	private static $handler = null;
	
	public function __destruct() {
	}
	 
	public static function getInstance()
	{	
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function InitSocket($Ip,$port)
	{
		if(empty($Ip)||empty($port))
		{
			return false;
		}
		self::$handler = new swoole_client(SWOOLE_TCP);
		$ret = self::$handler->connect($Ip, $port,5,0);
		if(!$ret)
		{
			return false;
		}
		return  true;
	}
	
	public function SerializeMsg($stMsg)
	{
		$msg = $stMsg->SerializeToString();
		$string = "";
		$string = pack("C1",intval((strlen($msg)+2+20)/256));
		$string.= pack("C1",(strlen($msg)+2+20)%256);
		$string.= ShaCreateToken($msg);
		$string.= $msg;
		return $string;
	}
	public function integerToBytes($val) {
		$byt = array();
		$byt[0] = ($val & 0xff);
		$byt[1] = ($val >> 8 & 0xff);
		$byt[2] = ($val >> 16 & 0xff);
		$byt[3] = ($val >> 24 & 0xff);
		return $byt;
	}
	
	public function ShaCreateToken($msg) {
		$shaString =  sha1($msg, true);
		$TotalLen = (strlen($msg)+20)*547;
		$bValue = integerToBytes($TotalLen);
		for ( $i = 0 ; $i <  20 ; ++$i )
		{
			$shaString[$i] = chr(ord($shaString[$i])^$bValue[$i%4]);
		}
		return $shaString;
	}
	public function ShaVerifyToken($msg) {
		$strSub = substr($msg, 22,  strlen($msg)-22);
		$subString = substr($msg, 2,  20);
		$shaString =  sha1($strSub, true);
		$TotalLen = (strlen($msg)-2)*547;
		$bValue = integerToBytes($TotalLen);
		for ( $i = 0 ; $i < 20 ; ++$i )
		{
			$value = chr(ord($shaString[$i])^$bValue[$i%4]);
			if($value !=$subString[$i])
			{
				return false;
			}
		}
		return true;
	} 
	
	public function SendMsg($stMsg)
	{
		$result = self::$handler->send($stMsg);
		if($result)
		{
			return true;
		}
	
	}
	
	public function RecvMsg()
	{
		try{
			$retbuff = self::$handler->recv();
		}catch (Exception $e)
		{
			unset(self::$handler);
			return false;
		}
		if(strlen($retbuff)==0)
		{
			return false;
		}
		$len = unpack('C2',$retbuff);
	
		$recvlen = $len[1]*256+$len[2];
		if(strlen($retbuff) == $recvlen)
		{
			if(!$this->ShaVerifyToken($retbuff)) //验证加密
			{
				return false;
			}
			return $retbuff;
		}
		while(true )
		{
			$Lastbuff .= $retbuff;
			if( $recvlen == strlen($Lastbuff) )
			{
				break;
			}
			try {
				$retbuff = self::$handler->recv();
				if(strlen($retbuff) == 0)
				{
					break;
				}
			} catch (Exception $exc) {
				unset(self::$handler);
				return false;
			}
	
		}
		if(!$this->ShaVerifyToken($Lastbuff))//验证加密
		{
			return false;
		}
		return $Lastbuff;
	}
}