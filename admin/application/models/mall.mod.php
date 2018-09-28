<?php

/* 
 * 平台版本更新 model
 */

class Mall_Model extends Model{
    
    private $table = 'game_mall_config';
    private $db = null;
     
    public function __construct($region='')
    {
    	parent::__construct();
    	if (!empty($region)){
    		$this->db = Mysql::database('',$region);
    	}
    }
    /**
     * 商场推荐配置删除
     * **/
    public function delMall($id) 
    {  
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->db->delete($this->table, $where, $array);
    }
    
    
    /**
     * 获取活动配置信息
     * **/
    public function getActivityCfgInfo(){
    
    	$where = NULL;
    	
    	$conn = Platfrom_Service::getServer(true,'globaldb');
    	$obj = new GameActivity_Model($conn);
    	
    	$sql = "SELECT * FROM ".$this->table." ".$where;
    	
    	//__log_message($sql,'activity-log');
    	if($obj->db->query($sql) && $obj->db->rowcount() > 0)
    	{
    		$rows =$obj->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    
    /**
     * 获取活动配置信息
     * **/
    public function getMallInfo(){
    	$conn = Platfrom_Service::getServer(true,'globaldb');
    	$obj = new Mall_Model($conn);
    	 
    	$sql = "SELECT * FROM  ".$this->table." WHERE `status` in(0,2)";
    	//__log_message($sql,'activity-log');
    	if($obj->db->query($sql) && $obj->db->rowcount() > 0)
    	{
    		$rows =$obj->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    /**
     * 
     * 活动变更
     * @param unknown $id
     * @param unknown $data***/
    
    public function MallEdit($id,$status,$data=NULL)
    { 
    	if (empty($data) && count($data)<=0){
	    	$sql = "update ".$this->table." set status ={$status} where id in ({$id})";
	    	__log_message($sql,"db");
	    	if($this->db->query($sql))
	    	{
	    		return true;
	    	}
	    	return false; 
    	}
    	else
    	{ 
    		$where = 'id=:id';
    		
    		$ret = $this->db->update2($this->table,$data,$where,array('id'=>$id));
    		if($ret)
    		{
    			return true;
    		}
    		return false;
    	}
    }
    
    /**
     * 获取活动配置信息
     * **/
    public function getMallList($id = NULL){
    
    	$where='';
    	 
    	if ($id)
    	{
    		$where = ' WHERE id in ('.$id .')  limit 1';
    	}
    	
    	$sql = 'SELECT * FROM game_mall_config '.$where;
    
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
 
    /**
     * +活动分页 +
     * +activity info total+
     * */
    public function mallTotal($data='')
    {
    	$dat = ' '.$data;
    
    	if (empty($data))
    	{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('mallconfig','total',
    		'globaldb','','',\"{$dat}\")";
    	}else{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('mallconfig','total',
    		'globaldb','','',\"{$dat}\")";
    	}
    	__log_message($sql,'db');
    
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_row();
    		return $rows;
    	}
    }
    /**
     * +activity list info+
     * */
    public  function mallInfo($data="",$page="",$pagesize="")
    {
    	$limit = '';
    	$dat = ' '.$data;
    
    	if(!empty($page) && !empty($pagesize))
    	{
    		$offset = ($page-1) * $pagesize;
    
    		$limit  = ' LIMIT '.$offset.','.$pagesize;
    	}
    	if(empty($data))
    	{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('mallconfig','TotalPaging',
    		'globaldb','','{$limit}',\"{$dat}\")";
    	}
    	else
    	{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('mallconfig','TotalPaging',
    		'globaldb','','{$limit}',\"{$dat}\")";
    	}
    	__log_message($sql,'db');
    
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_all();
    		return $rows;
    	}
    }
    /**
     * 记录活动
     * **/
    public function add_mall($data,$setBatch=false){
    	 
    	$fields = 'platId,serverId,startTime,
    	endTime,rules,createtime,title,description,status,type';
    	 
    	$addItion = ' ON DUPLICATE KEY UPDATE platId=values(platId),
    	serverId=values(serverId),title=values(title),description=values(description),
    	description=values(description),endTime=values(endTime),
    	createtime=values(createtime),rules=values(rules),status=values(status),type=values(type);';
    	
    	if($this->db->insertBatch('game_mall_config',$fields,$data,$addItion))
    	{
    		return true;
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
	    		$expiry = 'AND ReimburseType = 0  AND  endtime>'.time();
	    	}else{
	    		$expiry = 'AND ReimburseType = 0  AND  endtime<'.time();
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
    /**
     * 如果邮件发送失败主动清理自增信息
     * **/
    public function closeMail($id) {
    	//$db = Mysql::load('platform');
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->db->delete($this->table, $where, $array);
    }

    public function edit_Mall($id,$data=array()){
    	 
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

