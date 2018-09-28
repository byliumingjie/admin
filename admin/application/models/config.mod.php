<?php

/* 
 * 配置管理
 */
class Config_Model extends Model{
 	private $table = 'tb_manager_log';
    private $dbh = null;
 
     
	public function __construct($region='')
	{
		parent::__construct();
		if (!empty($region)){
			$this->dbh = Mysql::database('',$region);
		}
	}
   
    public function getfaceConfig(){
    	
     	$platfrom = Platfrom_Service::getServer(true,'globaldb');
     	
    	$obj = new Config_Model( $platfrom );
    	 
        $sql = 'SELECT id,name FROM tb_face_config'; 
        // __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
        return false;
    }
    public  function getConfig($table){
    	
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    	
    	$obj = new Config_Model( $platfrom );
    	
    	$sql = 'SELECT id,name FROM  '. $table;
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    // tb_equip_config
	 
    public function getequipConfig(){
    	 
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    
    	$obj = new Config_Model( $platfrom );
    
    	$sql = 'SELECT id,name FROM tb_equip_config';
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    //tb_item_config
    
    public function getItemconfig(){
    
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    
    	$obj = new Config_Model( $platfrom );
    
    	$sql = 'SELECT id,name FROM tb_item_config';
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    // tb_skill_config
    public function getSkillconfig(){
    
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    
    	$obj = new Config_Model( $platfrom );
    
    	$sql = 'SELECT id,name FROM tb_skill_config';
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    //tb_dup_config
    public function getdupconfig(){
    
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    
    	$obj = new Config_Model( $platfrom );
    
    	$sql = 'SELECT id,name FROM tb_dup_config';
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    //tb_chap_config
    public function getChapconfig(){
    
    	$platfrom = Platfrom_Service::getServer(true,'globaldb');
    
    	$obj = new Config_Model( $platfrom );
    
    	$sql = 'SELECT id,name FROM tb_chap_config';
    	// __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    
}

