<?php

/* 
 * 区服管理
 */
class Apilog_Model extends Model{
 	private $table = 'tb_manager_log';
    private $dbh = null;
 
     
	public function __construct($region='')
	{
		parent::__construct();
		if (!empty($region)){
			$this->dbh = Mysql::database('',$region);
		}
	}
   
     public function getlogTotal($platform,$having=''){
    	
    	$obj = new Apilog_Model( $platform );
    	 
        $sql = 'SELECT count(*) as total FROM tb_manager_log '.$having; 
         __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_row();
    		return $rows;
    	}
        return false;
    }
	   /**
     * 查询区服列表
     * @param type $page        页码
     * @param type $pagesize    条数
     * @return boolean or array 查询区服列表，成功返回区服列表，失败返回false
     */
    public function getlogList($platform='',$page =1 ,$pagesize=15,$having=''){
    	
    	$obj = new Apilog_Model( $platform );
    	
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }        
        $sql = 'SELECT * FROM tb_manager_log '.$having.' order by id desc '.$limit ;
        __log_message("info".$sql);
        if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
            $rows = $obj->dbh->fetch_all();
            return $rows;
        }
        return false;
    }
    // account
    public function getAccountlogTotal($platform,$having=''){
    	 
    	$obj = new Apilog_Model( $platform );
    
    	$sql = 'SELECT count(*) as total FROM tb_sessions '.$having;
    	__log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_row();
    		return $rows;
    	}
    	return false;
    }
    /**
     * 查询区服列表
     * @param type $page        页码
     * @param type $pagesize    条数
     * @return boolean or array 查询区服列表，成功返回区服列表，失败返回false
     */
    public function getAccountlogList($platform='',$page =1 ,$pagesize=15,$having=''){
    	 
    	$obj = new Apilog_Model( $platform );
    	 
    	$limit = '';
    	if(!empty($page) && !empty($pagesize)){
    		$offset = ($page-1) * $pagesize;
    		$limit  = ' LIMIT '.$offset.','.$pagesize;
    	}
    	$sql = 'SELECT * FROM tb_sessions '.$having.' order by createTime desc '.$limit ;
    	__log_message("info".$sql,'db');
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
    		$rows = $obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    // log set save
    public static function setlog($data)
    { 
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    	 
    	$obj = new Apilog_Model( $platfrom ); 
    	return $obj->dbh->insert('tb_manager_log',$data); 
    }
    // 录入系统缓存
    public static function  insertAdminCache( $data )
    {
    	$platfrom = Platfrom_Service::getServer(true,'admin');
    	
    	$obj = new Apilog_Model( $platfrom );
    	
    	return $obj->dbh->insert('tb_cache',$data);  
    }
    // 封禁缓存日志tb_banned_log
    
    public static function insertBanlog( $data )
    {
    	$platfrom = Platfrom_Service::getServer(true,'admin');
    	 
    	$obj = new Apilog_Model( $platfrom );
    	 
    	return $obj->dbh->insert('tb_banned_log',$data); 
    }
    // 检索指定日志数据
	public static function RetrievalLog($source,$code,$server,$starttime,$endtime)
	{
		$platfrom = Platfrom_Service::getServer(true,'admin');
		
		$obj = new Apilog_Model( $platfrom );
		
		$sql = "select protocolCode,RequestData,id,source,
		account,sname,ResponseData,create_time FROM tb_manager_log 
		where protocolCode =:code 
		and sname=:server
		and create_time>=:starttime 
		and create_time<=:endtime
		and ExecutionState=0 order by create_time desc"; 
		// 	"source"=>$source,
		$getdata = array(		
			"code"=>$code,	
			"server"=>$server,
			"starttime"=>$starttime,
			"endtime"=>$endtime	 
		); 
		if($obj->dbh->query($sql,$getdata) && $obj->dbh->rowcount()>0){
			$rows = $obj->dbh->fetch_all();
			return $rows;
		}
		return false; 
	}
	public static function RetrievalLog2($source,$code,$server)
	{
		$platfrom = Platfrom_Service::getServer(true,'admin');
	
		$obj = new Apilog_Model( $platfrom );
	
		$sql = "select protocolCode,RequestData,id,source,account,
		sname,ResponseData,create_time FROM tb_manager_log
		where protocolCode =:code
		and sname=:server		
		and ExecutionState=0 order by create_time desc";
		//"source"=>$source,
		$getdata = array(				
				"code"=>$code,
				"server"=>$server,
		);
		if($obj->dbh->query($sql,$getdata) && $obj->dbh->rowcount()>0){
			$rows = $obj->dbh->fetch_all();
			return $rows;
		}
		return false;
	}
	// 缓存异常提示
	public static function cacheError($code,$server){
		
		$platfrom = Platfrom_Service::getServer(true,'admin');
		
		$obj = new Apilog_Model( $platfrom );
		
		$sql="SELECT account,`server`,protocolCode,`server`,createtime,
		`status`,requestData,responseData,lasttime FROM tb_cache as a 
		WHERE a.`server` =$server 
		and  protocolCode in($code)
		AND  a.`status`!=0";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
			$rows = $obj->dbh->fetch_all();
			return $rows;
		}
		return false;
	}
}

