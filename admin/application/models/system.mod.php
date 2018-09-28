<?php

/* 
 * 平台版本更新 model
 */

class System_Model extends Model{
    
    private  $table = 'platform_cfg';
    private  $db = null;
    
    function __construct() {
        parent::__construct();
        $this->db = Mysql::load($this->table);
    } 
    
    public function getplatformInfo(){ 
    	 
    	$sql = 'SELECT * FROM tb_platform_cfg';
     
    	if($this->db->query($sql) && $this->db->rowcount() > 0)
    	{
    		$rows =$this->db->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    
    /**
     * 增加平台
     * **/
    public function addPlatformCfg($data){
    	
    	return $this->db->insert("tb_platform_cfg",$data);
    }	
    //删除游戏平台
  	public function delPlatformCfg($id) {
        //$db = Mysql::load('platform'); 
        $where = "id=:id";
        $array = array('id' => $id);
        return $db->delete("tb_platform_cfg", $where, $array);
    }
    /* 编辑平台 */
    public function editPlatformCfg($id,$data)
    { 
    	
    	$where = 'id=:id'; 
    	$ret = $this->db->update2('tb_platform_cfg',$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    // 通过平台的变更 关联区服的配置相应进行变更
    public function uploadServerPlat($idList,$platId)
    {
    	//$this->table = 'platform';
    	$sql = "update tb_platform set platformId = ".$platId ."  where type in (".$idList.")";
    	__log_message($sql,"db");
    	if($this->db->query($sql))
    	{
    		return true;
    	}
    	return false;
    } 
    // 根据平台获取区服列表信息
 	public function getServerInfo($platformId){
        
 		if(empty($platId))
        {
            return false;
        }
        $sql = 'SELECT id,type as serverId,platformId FROM tb_platform  WHERE platformId=:platformId';
        __log_message($sql);
        if($this->db->query($sql,array('platformId'=>$platformId)) && $this->db->rowcount() > 0){
            $row = $this->db->fetch_all();
            return $row;
        }
        return false;
    }
}

