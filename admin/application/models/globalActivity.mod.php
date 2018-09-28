<?php

/* 
 * 平台版本更新 model
 */
class GlobalActivity_Model extends Model{
    
    private $table = 'game_global_activity';
    private $db = null;
     
    public function __construct($region='')
    {
    	parent::__construct();
    	if (!empty($region)){
    		$this->db = Mysql::database('',$region);
    	}
    }  
    /**
     * 活动删除
     * **/
    public function delActivity($id)
    {
    	//$db = Mysql::load('platform');
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->db->delete($this->table, $where, $array);
    }
    /**
     * +活动分页 +
     * +activity info total+
     * */
    public function activityTotal($data='')
    {
    	$dat = ' '.$data;
    
    	if (empty($data))
    	{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('GlobalActivity','total',
    		'globaldb','','',\"{$dat}\")";
    	}else{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('GlobalActivity','total',
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
     * 活动变更
     * @param unknown $id
     * @param unknown $data
     **/
    public function activityEdit($id,$status,$data=NULL)
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
    public function getActivityList($id = NULL){
    
    	$where='';
    
    	if ($id)
    	{
    		$where = ' WHERE id = '.$id .'  limit 1';
    	}
    	 
    	$sql = 'SELECT * FROM '.$this->table .' '.$where;
    
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    /**
     * 获取活动配置信息
     * **/
    public function getActivityCfgInfo(){
    
    	$conn = Platfrom_Service::getServer(true,'globaldb');
    	
    	$obj = new GlobalActivity_Model($conn);
    	 
    	//  1 --服务器活动 2-全局服务器活动
    	$where = " WHERE status = 1";
    	 
    	$sql = 'SELECT * FROM game_activity_config  ';
    	__log_message($sql,'activity-log');
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
    public function getGlobalActivityInfo(){
    	$conn = Platfrom_Service::getServer(true,'globaldb');
    	$obj = new GlobalActivity_Model($conn);
    
    	$sql = 'SELECT * FROM '.$this->table.'  WHERE `status` in(0,2)';
    	if($obj->db->query($sql) && $obj->db->rowcount() > 0)
    	{
    		$rows =$obj->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    /**
     * 记录活动
     * **/
    public function add_global_activity($data)
    { 
    	if($this->db->insert($this->table,$data))
    	{
    		return true;
    	}
    	return false;
    }
    /**
     * 活动变更
     * @param unknown $id
     * @param unknown $data
     **/
    public function GlobalActivityEdit($id,$status,$data=NULL)
    {
    	if (empty($data) && count($data)<=0){
    		$sql = "update game_global_activity set status ={$status} where id in ({$id})";
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
    		$ret = $this->db->update2('game_global_activity',$data,$where,array('id'=>$id));
    		if($ret)
    		{
    			return true;
    		}
    		return false;
    	}
    }
    /**
     * +activity list info+
     * */
    public  function activityInfo($data="",$page="",$pagesize="")
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
    		$sql = "CALL globaldb.PROC_STAT_PAGING('GlobalActivity','TotalPaging',
    		'globaldb','','{$limit}',\"{$dat}\")";
    	}
    	else
    	{
    		$sql = "CALL globaldb.PROC_STAT_PAGING('GlobalActivity','TotalPaging',
    		'globaldb','','{$limit}',\"{$dat}\")";
    	}
    	__log_message($sql,'db');
    
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_all();
    		return $rows;
    	}
    }
    // 获取区服的活动有效期时间    
    public function getActivityServerLog()
    { 	
    	return ;
    }    
    
}

