<?php

/* 
 * 平台版本更新 model
 */
class GameConfig_Model extends Model{
    
    private $table = 'game_pay_config';
    private $db = null;
     
    public function __construct($region='')
    {
    	parent::__construct();
    	if (!empty($region)){
    		$this->db = Mysql::database('',$region);
    	}
    }
    // 付费 配置
    public function getPayConfig()
    {
    	$sql = 'SELECT * FROM '.$this->table;
    	
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows = $this->db->fetch_all();
    		return $rows;
    	}
    	return false;    	
    }
    // 付费配置更新
    public function  editPayconfig($id,$data) 
    {
    	$where = 'id=:id'; 
    	$ret = $this->db->update2($this->table,$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    
    // 付费配置更新 删除
    public function delPlatConfig($id) {
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->db->delete($this->table, $where, $array);
    }
   	// 付费配置数据清空
   	public function  clostPayConfig(){
   		$sql = "delete from ".$this->table;
   		
   		if($this->db->query($sql)){
   			return true;
   		}
   		return false;
   	}
   	/**
   	 * 付费配置增加
   	 * **/
   	public function addPayConfig($data)
   	{ 
   		if($this->db->insert($this->table,$data)){
   			return true;
   		}
   		return false ;
   	}
    /**
     * 付费配置增加
     * **/
    public function addAllPayConfig($data)
    {
    	
    	$fields = 'fee,diamond,extra_diamond,if_special_get,special_diamond,product_id';
    	__log_message(json_encode($data),'pay-log');
    	 if($this->db->insertBatch($this->table,$fields,$data)){
    	 	return true;
    	 }
    	 return false ;
    }
}

