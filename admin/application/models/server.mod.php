<?php

/* 
 * 区服管理
 */
class Server_Model extends Model{
 /*    private $table_alise = 'tb_server';
    private $db = null;
    public function __construct($host,$user, $pass,$database) {
        parent::__construct();
		$this->db = Mysql::loadMysql($host,$user, $pass,$database);
    } */
	private $table = 'tb_server';
	
	private $dbh = null;
	 
	/* -------------------------------------------------------------------------------------------------------------- */
	public function __construct($region)
	{
		parent::__construct();
		$this->dbh = Mysql::database('',$region);
	}
    /**
     * 添加区服信息
     * @param array $data 区服信息
     * @return boolean 是否添加成功
     */
    public function insert($data){
        
        return $this->db->insert($this->table_alise,$data);
    }
    
    /* 增加白名单每个平台下都有一张白名单表
     * 
     */
    public function insertWhite($data)
    {
        return $this->db->insert("tb_whitelist",$data);
    }
    
    //获得平台白名单
    public function getWhite($page =1 ,$pagesize=15)
    {
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }
        
        $sql = 'SELECT * FROM tb_whitelist ' .$limit ;;
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
        return false;
    }
    
    //获取白名单总数
    public function getWhitetotal()
    {
        $sql = 'SELECT count(1) total FROM tb_whitelist ';
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_row();
            return $rows['total'];
        }
        return false;
    }
    
    //获取帐号基本信息
    public function getAccount($uin) {
        $sql = 'SELECT accountid,accounttype FROM t_qmonster_accountdata where uin =  '.$uin;
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
        return false;
    }
    
    /**封帐号的状态改变
     * $uin帐号唯一主键
     * $state每个封号的状态字段
     */
    public function updateAccountStat($uin,$state) {
        return $this->db->update2("t_qmonster_accountdata",array("state" =>$state),' uin=:uin',array('uin'=>$uin)); 
    }
    
    //删除白名单
    public function delWhite($data){
        return $this->db->delete('tb_whitelist','uin=:iUin',  array('iUin'=>$data['uin']));
    }
    
    /**更新平台信息
     * @param type $data 更新数据
     * @param type $PlatformId 平台唯一标识
     * @param type $sid 区服唯一标识
     * @return type 更新成功返回 success
     */
    public function update($data,$PlatformId,$sid){
        return $this->db->update2($this->table_alise,$data,'platform=:PlatformId and sid=:Sid',array('PlatformId'=>$PlatformId,'Sid'=>$sid));
    }
	
    /**
     * 查询区服列表
     * @param type $page        页码
     * @param type $pagesize    条数
     * @return boolean or array 查询区服列表，成功返回区服列表，失败返回false
     */
    public function getServers($platform='',$page =1 ,$pagesize=15){
    	
    	$obj = new Server_Model( $platform );
    	
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }
        
        $sql = 'SELECT * FROM tb_server  order by sid desc '.$limit ;
        if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
            $rows = $obj->dbh->fetch_all();
            return $rows;
        }
        return false;
    }
    
    public function getAllServers($platform){      
        $sql = 'SELECT * FROM '.$this->table_alise.' where platform=:platform order by sid desc';
        if($this->db->query($sql,array('platform'=>$platform)) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
        return false;
    }
       
    public function getServersTotal($platform){
    	
    	$obj = new Server_Model( $platform );
    	 
        $sql = 'SELECT count(*) as total FROM tb_server '; 
         
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_row();
    		return $rows;
    	}
        return false;
    }
    
    /**
     * 通过平台和区服id查询区服信息
     * @param type $platform  平台ID
     * @param type $sid       区服ID
     * @return boolean      
     */
    public function getServerBySid($platform,$sid){
        
        $sql = 'SELECT * FROM '.$this->table_alise.' WHERE platform=:platform and sid=:sid limit 1';
       
        if($this->db->query($sql,array("platform"=>$platform,'sid'=>$sid)) && ($this->db->rowcount() > 0)){
            $row = $this->db->fetch_row();
            return $row;
        }
        return false;
    }
    

}

