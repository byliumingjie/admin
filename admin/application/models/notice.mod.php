<?php

/* 
 * 走马灯
 */
class Notice_Model extends Model{
    //private $table_alise = 'tb_notice';
    private $table = 'tb_marquee';
    private $db = null;
     /*public function __construct($host,$user, $pass,$database) {
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
     * 添加走马灯
     * @param array $data 走马灯信息
     * @return boolean 是否添加成功
     */
    public function insert($data){
        
    	if($this->db->insert($this->table,$data)){
    	
    		if($lastId=$this->db->get_insertlastid())
    		{
    			return $lastId;
    		};
    		return false;
    	}
    	return false;
    	
        //return $this->db->insert($this->table,$data);
    }
    
    public function getPageNotice($platform,$awhere,$page =1 ,$pagesize=10){
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }
        
        $where  = "";
        if(!empty($awhere['account']))
        {
            $where .= ' and tr_sender like"'. $awhere['account'].'%"';
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        }
        if(!empty($awhere['createtime'])&&!empty($awhere['endtime']))
        {
            $where .= ' and tr_createtime >= '. $awhere['createtime'].' and tr_createtime <='.$awhere['endtime'];
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        }
        if(empty($awhere['account'])&& empty($awhere['createtime'])&&empty($awhere['endtime']))
        {
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        }
        if($this->db->query($sql,array('platform'=>$platform)) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
       
        return false;
    }
    
    /**
     *
     * 获取跑马灯数据 LIST 
     * @param unknown $awhere
     * @param string $expiry 如果不为空 未失效   ---- 如果null 代表失效
     * @return Ambigous <>|boolean**/
    public function getNotice($awhere,$page =1 ,$pagesize=10,$expiry=NULL)
    { 
    	$limit = '';
    	$status = NULL;
    	if (!empty($expiry)){
    		$expiry = '  endtime>'.time();
    	}else{
    		$expiry = '  endtime<'.time();
    	}
        if(!empty($page) && !empty($pagesize))
        {
           $offset = ($page-1) * $pagesize;
           $limit  = ' LIMIT '.$offset.','.$pagesize;
        } 
        if(!empty($awhere['status']))
        {
        	$status = 'AND  status in (4,5)';
        }
        
		$sql = 'SELECT * FROM '.$this->table.' 	WHERE '.$expiry 
        ." order by createtime desc " .$limit ;
        if(!empty($awhere['createtime']) && !empty($awhere['endtime']))
        {
        	$sql = 'SELECT * FROM '.$this->table.
        	'  WHERE createtime>="'.$awhere['createtime'].
        	'" AND createtime<="'.$awhere['endtime'].
        	'" AND sender ="'.$awhere['sender'].'"'.
        	'  AND  '.$expiry .$status
        	." order by createtime desc " .$limit ; 
        }
        __log_message("notice sql :::".$sql,'db');
        /* $where  = "";
        if(!empty($awhere['account']))
        {
            $where .= ' and tr_sender like"'. $awhere['account'].'%"';
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        }
        if(!empty($awhere['createtime'])&&!empty($awhere['endtime']))
        {
            $where .= ' and tr_createtime >= '. $awhere['createtime'].' and tr_createtime <='.$awhere['endtime'];
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        }
        if(empty($awhere['account'])&& empty($awhere['createtime'])&&empty($awhere['endtime']))
        {
            $sql = 'SELECT * FROM '.$this->table_alise.' where  tr_platform=:platform '.$where." order by tr_createtime desc" ;
        } */
        
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_all();
            return $rows;
        }
       
        return false;
    }
    /**
     * 
     * 获取跑马灯数据总
     * @param unknown $awhere
     * @param string $expiry 如果不为空 未失效   ---- 如果null 代表失效
     * @return Ambigous <>|boolean**/
     public function getNoticeTotal($awhere,$expiry=NULL)
     {
     	$status = NULL;
     	
     	if(!empty($awhere['status']))
     	{
     		$status = ' AND  status in (4,5)'; 
     	}
     	if (!empty($expiry)){
     		$expiry = '  endtime>'.time();
     	}else{
     		$expiry = '  endtime<'.time();
     	}
        $sql = 'SELECT count(*)as total FROM '.$this->table.' where ' . $expiry ;
        if (!empty($awhere['createtime']) && !empty($awhere['endtime'])){
        	
        	$sql= 'SELECT count(*)as total FROM '.$this->table .
        	' WHERE createtime>="'.$awhere['createtime']. 
        	'" AND createtime<="'.$awhere['endtime'].
        	'" AND sender ="'.$awhere['sender'].'"'.
        	' AND '.$expiry .$status;
        }
        __log_message("notice sql :::".$sql,'db');
        if($this->db->query($sql) && $this->db->rowcount()>0){
            $rows = $this->db->fetch_row();
            return $rows['total'];
        }
        return false;
    }
    public function edit_notic($id,$data=array()){
    
    	$where = 'id=:id';
    	$ret = $this->db->update2($this->table,$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    
    public function update($data,$id) {
        return $this->db->update2($this->table_alise,$data,'id=:id ',array('id'=>$id));
    }
    public function deldata($id) {      
        return $this->db->delete($this->table_alise,'id=:id',  array('id'=>$id));
    }
}

