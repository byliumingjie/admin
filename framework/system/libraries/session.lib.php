<?php
/**
 * session 数据库存储类
 * @uses by liumingjie 2016-01-11 add
 * @version v1.2 
 */

class Session {
		 
	    private static $session_id      = 0;
	    private static $session_data    = array();
	    private static $is_update       = FALSE;
	    private static $is_del          = FALSE;
	    private static $is_gc           = FALSE;
	    private static $dbo             = NULL;    //数据库连接句柄
	    private static $gc_max_time     = 2592000;//24*60*60*30 
	    private static $table           = 'tb_sessions';
	    private static $pre_key         = 'weige';//session 密钥
	    //捆绑使用哈
	    private static $gc_rate_de      = 100;//代表分母
	    private static $gc_rate_co      = 20;//代表分子
	     
	    private static $path            = '/';//保存路径
	    private static $domain          = null; //域
	    private static $secure          = false;//默认
	    private static $httponly        = false;//默认
	    var $now;
	    var $time_reference				= 'time()';
	    
    /**
     *  获取数据库句柄  私有
     */
    private static function open() 
    { 
    	if (!self::$dbo) 
        {  
            self::$dbo = Mysql::load('sessions'); 
        }
       
        return TRUE;
    }	 
	 
    /**
     * 设置 
     * */
    public static function set($key, $val=NULL) 
    { 
    	//__log_message("set session11");
        self::open(); 
        $data = self::read();
        if ($data === FALSE)
        {
            $data = array();
        }
        if (!$val && is_array($key))
        {
            $data = $key;
        } 
        else if ($val && is_string($key))
        {
            $data[$key] = $val;
        }
        
        // __log_message("set session");
        self::write($data);
        self::close();
    }
    // 获取是否存在session id 验证 
    
    public  static function  sessionid_verif($type=true)
    { 
    	self::open();
    	
    	$session_id = self::get_session_id();
    	
    	$operator = $_SESSION['account']; 
    	 
    	if (!empty($operator))
    	{ 
	    	$sql = "SELECT * FROM ".self::$table."
    		 WHERE `operator`='{$operator}'
    		 AND session_id !='{$session_id}'
    		 AND status = 1 limit 1";
	    	
    		if($type){
    		$sql = "SELECT * FROM ".self::$table."
    		WHERE `operator`='{$operator}'
    		AND session_id ='{$session_id}' limit 1";
    		}
	    	 
	    	if(self::$dbo->query($sql))
	    	{
	    		$row = self::$dbo->fetch_row();
	    		__log_message("session verif row db ".$row['status'],'session');
	    		return $row;
	    	}
	    	return false;
    	}
    	return false;
    }
 
    public static function session_clear_up($user =true){
    	//__log_message("session_clear_up :: ".self::get_session_id(),'session');
    	self::open();
    	 
    	$session_id = self::get_session_id();
    	$account = $_SESSION['account'];
    	$ip = self::getIp();
    	//__log_message("session_clear_up :: ".$session_id);
    	//$account = $_SESSION['account']
    	if (!empty($session_id) && !empty($account))
    	{
    		$sql ="UPDATE tb_sessions SET `status` = 2
    		WHERE  session_id !='{$session_id}'
    		AND operator = '{$account}'";
    		
    		if ($user){
    		$sql = "UPDATE tb_sessions SET `status` = 1
			WHERE  session_id ='{$session_id}' 
    		AND operator = '{$account}'"; 
    		}
    		
    		if(self::$dbo->query($sql)){
    			//__log_message("session_clear_up true :: ".$session_id,'session');
    			return true;
    		}
    		return false;
    	}
    	return false;
    }
    public static function session_exit(){
    	self::open();
    	$createTime = date('Y-m-d H:i:s',time());
    	
    	if (!empty($session_id))
    	{
    		$sql = "UPDATE tb_sessions SET lastUpTime = '{$createTime}',
    		status = 0    		
    		WHERE  session_id ='{$session_id}'";
    		if(self::$dbo->query($sql)){
    			return true;
    		}
    		return false;
    	}
    	return false;
    	
    }
    public static function  session_last_time_up(){
    	
    	self::open();
    	
    	$session_id = self::get_session_id();
    	$createTime = date('Y-m-d H:i:s',time());
    	 
    	if (!empty($session_id))
    	{
    		$sql = "UPDATE tb_sessions SET lastUpTime = '{$createTime}'
    		WHERE  session_id ='{$session_id}'";
    		if(self::$dbo->query($sql)){
    			return true;
    		}
    		return false;
    	}
    	return false;
    }
    /*DELETE FROM tb_sessions 
	WHERE  operator ='liumj' 
	AND ip!='172.168.100.179'
	AND session_id !='b42e10947a2c86c19f306f7ae13e5a2e'*/
    /**
     *获取值 
     * 
     */
    public static function get($key=NULL) 
    {
       	self::open();
        self::$session_data = self::read();
        $ret = '';
        if (!$key) {
            $ret = self::$session_data;
           // __log_message("sessiondata".$session_data);
        } 
        else if(is_array(self::$session_data) 
        && isset(self::$session_data[$key])) 
        {
            $ret = self::$session_data[$key];
        }
        self::update(); 
        self::close();
        
        return $ret;
    }
    /**
     * 删除或者重置
     * */
    public static function del($key)
    {
        if (!self::$is_del) 
        {
        	/*__log_message("is_del".$is_del);
        	__log_message("is_key".$key);*/
        	
            self::open();
             
            $val = self::read();
             
            if (isset($val[$key])) 
            {
                unset($val[$key]);
            }
            
            $session_id     = self::$session_id;
            $session_data   = serialize($val);
            $session_expire = time() + self::get_gc_maxtime();
           
            
            self::$dbo->query("update ".self::$table." set value='$session_data',
             expiry='$session_expire' where session_id='$session_id'");
            self::close();
        }
        self::$is_del = TRUE;
    }
    
    /**
     *____ 
     *
     *|销毁|     
     *____
     **/
    public static function destroy() 
    {
        $session_id = self::get_session_id();
        $_COOKIE['WBSID'] = '';         
        self::open();
        self::$dbo->query("delete from 
        ".self::$table." where session_id='$session_id'");
        self::close();
    }
    //后期添加销毁用户（针对于用户）
     
    /**
     * 读取  私有
     * */
    private static function read()
    {
    	self::open();
    	
        $session_id = self::$session_id;
        
        if (!$session_id) {
            $session_id = self::get_session_id();
        }
       // __log_message("readsessid22".$session_id); 
        if (!$session_id) return array();
        // self::getIp();1492584480 - 2592000
        $user_agent = isset($_SERVER['HTTP_USER_AGENT'])
        ?
        	md5($_SERVER['HTTP_USER_AGENT']) 
        : '';
        //6-31 5-31
        $client_ip  = self::getIp();
        // 当前日期 减去 最大的设置session的30天有效期 的时间戳（秒）
        $session_expire = time() - self::get_gc_maxtime();
         
        $sql ="select * from ".self::$table." 
        where session_id='$session_id' and expiry>'$session_expire'";
        
       // $rs = self::$dbo->fetch_row($sql);
        
   		if(self::$dbo->query($sql) && self::$dbo->rowcount() > 0){
            
   		 	$rows = self::$dbo->fetch_row(); 
           //__log_message("last::::rs==".$rows['session_id']);
        }else{ 
        	//__log_message(mysql_errno());
        }
        
        
       /* __log_message("rowsAgent=".$rows['agent']);
        __log_message("user_agent=".$user_agent);
        
        __log_message("rowsip=".$rows['ip']);
        __log_message("client_ip=".$client_ip);*/
        
        if (!$rows || $rows['agent'] != $user_agent || $rows['ip'] != $client_ip) 
        {
        	//__log_message("session====false ");
            return FALSE;
        } else{
        	//__log_message("session====true ");
        }
         
        self::$session_id = $rows['session_id'];
        
        return unserialize($rows['value']);
    }
    /**
     * session 写入   私有
     * sesion_data 该数据保留
     * */
    private static function write(array $session_data) 
    {
        $session_id = self::$session_id;
        //__log_message("第一次write session id".self::$session_id);
        //echo "sessionIIIIIIIIDDDDD".$session_id."<br>";
        
        if (!$session_id)
        { 
        	//__log_message("第一次 sessionid_write GETTTT".self::get_session_id());
            $session_id = self::get_session_id();
            self::$session_id = $session_id;
        }
        // __log_message("time()".time());
        $session_expire = time() + self::get_gc_maxtime();
        
        //__log_message("GC_maxTime".self::get_gc_maxtime());
         
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) 
        ? 
       	md5($_SERVER['HTTP_USER_AGENT'])
        : '';
        
        $client_ip  = self::getIp();
         
        $session_data = serialize($session_data);
        
        $createTime = date('Y-m-d H:i:s',time());
        $account = $_SESSION['account'];
       
        //__log_message("***********self::session_id" . self::$session_id);
        //__log_message("***********session_id" .$session_id);
        //后期可增加账户相关信息
        if (self::$session_id && self::$session_id === $session_id) 
        { 
        	//var_dump($account);
            /* self::$dbo->query("update ".self::$table." 
            set value='$session_data', expiry='$session_expire', 
            agent='$user_agent', ip='$client_ip',operator='{$account}' where session_id='$session_id'");
             */
            self::$dbo->query("
            insert into ".self::$table."(session_id, value, expiry, agent, ip,createTime,operator)
            values('$session_id', '$session_data',
            '$session_expire', '$user_agent', '$client_ip','$createTime','$account')
            ON DUPLICATE KEY UPDATE
            session_id='{$session_id}',
            value = '{$session_data}',
            agent = '$user_agent'
            ");
            
        } 
        else
        {
        	 
        	
        	// 如果session id 查找不到 浏览器缓存被清理 则需要重新获取数据
        	$session_id = self::create_session_id();            
        	//__log_message("创建的session idd".$session_id);
        	__log_message("!session id ".$session_id,'session');
        	self::$session_id = $session_id ;
        	
        	 // = self::create_session_id();
            
            // __log_message("三个复制的sessionID".self::$session_id); 
            
            // $sesid = self::$session_id;
            
            //__log_message("最后一个复制的sessionID".self::$session_id);
            
            self::$dbo->query("
            insert into ".self::$table."(session_id, value, expiry, agent, ip,createTime,operator) 
            values('$session_id', '$session_data', 
            '$session_expire', '$user_agent', '$client_ip','$createTime','$account')
            ON DUPLICATE KEY UPDATE 
            session_id='{$session_id}',
            value = '{$session_data}',
            expiry ='{$session_expire}',
            agent = '$user_agent'             
            ");
        }
        return true;
    }
    /**
     * session 更新   私有
     * */
    private static function update() 
    {
        if (!self::$is_update) 
        {
            $session_id = self::$session_id;
            $session_expire = time() + self::get_gc_maxtime();
            self::$dbo->query("update ".self::$table." 
            set expiry='$session_expire' where session_id='$session_id'");
        }
        self::$is_update = TRUE;
    }
     
    private static function close() 
    {
        if (!self::$is_gc && mt_rand(1, self::$gc_rate_de)%self::$gc_rate_co == 0) 
        {
            self::gc();
        }
        self::$is_gc = TRUE;
    }
    /**
     * 过期session 清除  随机触发
     * */
    private static function gc() 
    {
         
         $session_expire = time() - self::get_gc_maxtime();
         self::$dbo->query("delete from ".self::$table." 
         where expiry<'$session_expire'");
    }
     
    private static function get_session_id() 
    { 
		//session_start();
    	//__log_message("get session cookie session id :::".$_COOKIE['WBSID']);
		
        if (isset($_COOKIE['WBSID']) && strlen($_COOKIE['WBSID'])==32) 
        {
        	//__log_message("strlen wbssid".strlen($_COOKIE['WBSID']));
    		//__log_message("_COOKIE wbssid".strlen($_COOKIE['WBSID']));
            $sid = $_COOKIE['WBSID'];
             
            setcookie('WBSID', $sid, time() + self::get_gc_maxtime(),
            self::$path, self::$domain, self::$secure, self::$httponly);
            return $sid;
        } 
        return null;
    }
     
    private static function create_session_id() 
    {
    	$sid = self::get_session_id();
        if (!$sid) 
        {
        	// return liunx   时 函数返回当前 Unix 时间戳和微秒数 +        	
        	// return mt_rand 返回两个数值之间的整数（随机）+
        	// return substr  函数返回字符串的一部分
        	//__log_message("create sessid".$sid);
            $sid = self::getIp() . time() . microtime(TRUE) . 
            mt_rand(mt_rand(0, 100), mt_rand(100000, 90000000));
            //__log_message("MAX time()".time() + self::get_gc_maxtime());
            $sid = md5(self::$pre_key . $sid);
            
            $data = substr($sid, 0, 32);
            
			//设置一个wbsid 持续写那个sessionID 存放cookie方便下次读取设置有效生命周期
            setcookie('WBSID', $data, time() + self::get_gc_maxtime(),
            self::$path, self::$domain, self::$secure, self::$httponly);
            
           
            //setcookie("557",$data,time() + self::get_gc_maxtime());
           // setcookie()
           //__log_message("substr".substr($sid, 0, 32));
          //  __log_message("create sessid".$sid);
           // __log_message("557==".$_COOKIE['WBSID']);
        }
        return $sid;
    }
     
    public static function get_gc_maxtime()
    {
        return  self::$gc_max_time;
    }
    //
    public static function getIp()
    {

    	global $ip;
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip; 
    }
    
}