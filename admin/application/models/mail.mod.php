<?php

/* 
 * 平台版本更新 model
 */

class Mail_Model extends Model{
    
    private  $table = 'server_email';
    private  $db = null;
    
    function __construct() {
        parent::__construct();
        $this->db = Mysql::load($this->table);
    } 
    
    /**
     * 记录邮件
     * **/
    public function add_mail($data){
    	
    	if($this->db->insert($this->table,$data)){
    		
    		if($lastId=$this->db->get_insertlastid())
    		{
    			return $lastId; 
    		};
    		return false;
    	}
    	return false;
    }	
    
    /* get mail insert id*/
    public function get_insert_lastid($id,$data)
    {     	
    	$where = 'id=:id'; 
    	$ret = $this->db->update2('tb_platform_cfg',$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    public  function get_MailTotal($dbname,$data,
    $expiry=NULL,$ifReimburse=NULL)
    { 
    	$dbName = $dbname;
    	
    	if (empty($ifReimburse))
    	{
	    	if(!empty($expiry))
	    	{
	    		$expiry = ' AND ReimburseType = 0  AND  endtime>'.time();
	    	}else{
	    		$expiry = ' AND ReimburseType = 0  AND  endtime<'.time();
	    	}
	    	$data .= $expiry;
    	}
    	else {
    		// 如果邮件补偿查询
    		if (is_array($data))
    		{
	    		if (count($data)<=0)
	    		{
	    			$data =" WHERE ReimburseType in ({$ifReimburse})";
	    		}else{
	    			
	    			$data =" WHERE ServerId = {$data['ServerId']}
	    			AND ReimburseType in ({$ifReimburse})";
	    		}	
    		}
    		 
    	}
    	
    	$sql = "CALL globaldb.PROC_STAT_PAGING('MailManage','total',
    	'{$dbName}','','',\"{$data}\")";
    	
    	__log_message(" get_MailTotal : ::::".$sql,'mail-log');
    	
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_row();
    		return $rows;
    	}
    }
   
    // 
    public  function get_MailInfo($dbname,$data,
    $page="",$pagesize="",$expiry=NULL,$ifReimburse=NULL)
    {    
    	$dbName = $dbname;
    	
    	$limit = NULL;   
    	
   		if (empty($ifReimburse))
    	{
	    	if(!empty($expiry))
	    	{
	    		$expiry = ' AND ReimburseType = 0 AND  endtime>'.time();
	    	}else{
	    		$expiry = ' AND ReimburseType = 0 AND  endtime<'.time();
	    	}
	    	$data .= $expiry;
    	}
    	else {
    		if (is_array($data))
    		{
	    		if (count($data)<=0)
	    		{
	    			$data =" WHERE ReimburseType in ({$ifReimburse})";
	    		}else{
	    			
	    			$data =" WHERE ServerId = {$data['ServerId']}
	    			AND ReimburseType in ({$ifReimburse})";
	    		}
    		}	
    	}
    	
    	if(!empty($page) && !empty($pagesize))
    	{
    		$offset = ($page-1) * $pagesize;
    
    		$limit  = ' LIMIT '.$offset.','.$pagesize;
    	} 
    	$sql = "CALL globaldb.PROC_STAT_PAGING('MailManage','TotalPaging',
    		'{$dbName}','','{$limit}',\"{$data}\")";
    	
    	__log_message(" mail info ::::".$sql.'-expiry-'.$expiry,'mail-log');
    	
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows = $this->db->fetch_all();
    		return $rows;
    	}
    }
    /*
     * 获取邮件单个信息
     * **/
    /**
     * 如果邮件发送失败主动清理自增信息
     * **/
    public function closeMail($id) {
    	//$db = Mysql::load('platform');
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->db->delete($this->table, $where, $array);
    }
    
 	
    
    //删除游戏平台
    /* public function delPlatformCfg($id) {
    	//$db = Mysql::load('platform');
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $db->delete("tb_platform_cfg", $where, $array);
    } */
    // 通过平台的变更 关联区服的配置相应进行变更
    /* public function uploadServerPlat($idList,$platId)
    {
    	//$this->table = 'platform';
    	$sql = "update tb_platform set platformId = ".$platId ."  where type in (".$idList.")";
    	__log_message($sql,"db");
    	if($this->db->query($sql))
    	{
    		return true;
    	}
    	return false;
    } */  
    public function edit_Mail($id,$data=array()){
    	 
    	$where = 'id=:id';
    	$ret = $this->db->update2($this->table,$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    } 
    /***
     * 补偿更改 后期如果需要调取此接口来源于sql添加不加索引更改，
     * 如果需要唯一性的一条数据可进行先查看存在之后再进行修改
     * @param unknown $data
     * @param string $ServerId
     * @param number $ReimburseType
     * @param string $id
     * @return boolean***/
    public function reimburse_mail_up($data,$ServerId=NULL,
    $ReimburseType=1,$id=NULL){
    
    	$where = ' ServerId=:ServerId AND ReimburseType=:ReimburseType';
    	
    	$prepare_array = array
    	( 
    	 'ReimburseType'=>$ReimburseType,
    	 'ServerId'=>$ServerId    			
    	);
    	
    	if (!empty($id))
    	{
    	 $where.=' AND id=:id ';
    	 $prepare_array['id']=$id;
    	}
    	     	
    	$ret = $this->db->update2($this->table,$data,$where,$prepare_array);
    	
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    
}

