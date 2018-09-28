<?php

/* 
 * 登录公告
 */
class LoginNotice_Model extends Model{
    //private $table_alise = 'tb_loginnotice';
    private $table = 'tb_login_notice';
    private $db = null;
    /* public function __construct($host,$user, $pass,$database) {
        parent::__construct();
        $this->db = Mysql::loadMysql($host,$user, $pass,$database);
    } */
    public function __construct($region='')
    {
    	parent::__construct();
    	if (!empty($region)){
    		$this->db = Mysql::database('',$region);
    	}
    } 
    
    /**
     * 添加登录公告信息
     * @param array $data 区服信息
     * @return boolean 是否添加成功
     */
    public  function insert($data){
         
    	$fields = 'platformId,title,titlecolor,context,
    	starttime,endtime,sender,createtime';
    	
    	$addItion = ' ON DUPLICATE KEY UPDATE platformId=VALUES(platformId),
    	title=VALUES(title),titlecolor=VALUES(titlecolor),context=VALUES(context),
    	starttime=VALUES(starttime),endtime=VALUES(endtime),
    	sender=VALUES(sender),createtime=VALUES(createtime);';
    	
    	if($this->db->insertBatch($this->table,$fields,$data,$addItion))
    	{   
    		return true;
    	}
    	return false; 
    }
    
    public function getPageNotice($awhere,$page =1 ,$pagesize=15){
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }        
        $where  = " where  ";
        
        if(!empty($awhere['account']))
        {
            $where .= ' creator like "'. $awhere['account'].'%"';
        }
        if(!empty($awhere['createtime']) && !empty($awhere['account']))
        {
            $where .= ' and createtime >= '. $awhere['createtime'];
        }
        if(!empty($awhere['createtime']) && empty($awhere['account']))
        {
            $where .= ' createtime >= '. $awhere['createtime'];
        }
        if(empty($awhere['account']) && empty($awhere['createtime']))
        {
            $where = " ";
        }
        $sql = 'SELECT * FROM '.$this->table_alise.$where." order by createtime desc".$limit ;
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
       
        return false;
    }
   /**
     *
     * 获取登录公告数据 LIST 
     * @param unknown $awhere
     * @param string $expiry 如果不为空 未失效   ---- 如果null 代表失效
     * @return Ambigous <>|boolean**/
    public function getNotice($awhere,$page =1 ,$pagesize=20,$expiry=NULL){
    	$status = null;
    	$limit = '';
        if(!empty($page) && !empty($pagesize)){
           $offset = ($page-1) * $pagesize;
           $limit  = ' LIMIT '.$offset.','.$pagesize;
        }
    	if(!empty($expiry))
    	{
    		$expiry = ' UNIX_TIMESTAMP(endtime)>'.time();
    	}else{
    		$expiry = ' UNIX_TIMESTAMP(endtime)<'.time();
    	}
        
        $sql = 'SELECT * FROM '.$this->table . 
        'where '.$expiry . $statu.' order by createtime desc' .$limit ;
        
       // __log_message("sql list 1",'db');
        if(!empty($awhere))
        {
       		$where = $awhere .' AND '.$expiry;
       		
       		$sql = 'SELECT * FROM '.$this->table . $where."  ".
       		" order by createtime desc" .$limit ;
        } 
        __log_message("sql list " . $sql,'db');
        
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }       
        return false;
    }
     /**
     *
     * 获取登录公告数据 总 
     * @param unknown $awhere
     * @param string $expiry 如果不为空 未失效   ---- 如果null 代表失效
     * @return Ambigous <>|boolean**/
     public function getNoticeTotal($awhere=NULL,$expiry=NULL){
     	
     	$status = null;
     	
    	if(!empty($expiry))
    	{
    		$expiry = ' UNIX_TIMESTAMP(endtime)>'.time();
    	}else{
    		$expiry = ' UNIX_TIMESTAMP(endtime)<'.time();
    	}
     	
     	$sql = 'SELECT count(*)as total FROM '.$this->table . ' where '.$expiry;
     	
     	if( !empty($awhere) )
        {
        	$where = $awhere .' AND ' . $expiry;
        	
        	$sql = 'SELECT count(*)as total FROM '.
        	$this->table . $where;
        }
        __log_message("sql total" . $sql,'db');
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_row();
            return $rows['total'];
        }
        return false;
    }
    public function updateloginNotice($data,$id) {
    	return $this->db->update2($this->table,$data,'id=:id ',array('id'=>$id));
    }
    
    public function update($data,$id) {
        return $this->db->update2($this->table_alise,$data,'id=:id ',array('id'=>$id));
    }
    public function deldata($id) {      
        return $this->db->delete($this->table_alise,'id=:id',  array('id'=>$id));
    }
}

