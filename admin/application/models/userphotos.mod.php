<?php

/* 
 * 平台版本更新 model
 */

class UserPhotos_Model extends Model{
    
    private  $table = 'minosdb';
    private  $cdd = null;
    private  $dbName ='minosdb';
    
	public function __construct($region='')
	{
		parent::__construct();
		if (!empty($region)){
			$this->cdd = Mysql::database('',$region);
		}
	}
    /**
     * role info total
     * */ 
	public function rolefaceTotal($data='')
	{ 
		$dat = ' '.$data;
		
		if (empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('roleface','total',
			'{$this->table}','','',\"{$dat}\")";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('roleface','total',
			'{$this->table}','','',\"{$dat}\")";
		}  
		__log_message($sql,'db');
		
		if($this->cdd->query($sql) && $this->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$this->cdd->fetch_row();           
		   return $rows;
		} 
	}
	/**
     * role list info  
     * */
	public  function rolefaceInfo($data="",$page="",$pagesize="")
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
			$sql = "CALL globaldb.PROC_STAT_PAGING('roleface','TotalPaging',
			'{$this->table}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('roleface','TotalPaging',
			'{$this->table}','','{$limit}',\"{$dat}\")";
		} 
		__log_message($sql,'db');
		
		if($this->cdd->query($sql) && $this->cdd->rowcount() > 0)
		{	 			 		
			$rows =$this->cdd->fetch_all();           
			return $rows;
		}  
	} 	
    /* 审核通过 */
    public function roleAdopt($data)
    { 
    	$placeholders = str_repeat ('?, ',  count ($data) - 1) . '?';
    	$where = ' id in('.$placeholders.')'; 
    	
    	$sql = "update minosdb.role_face_upload  set type=2 where " .$where;
    	
    	$ret = $this->cdd->query($sql,$data);
    	
    	//$ret = $this->db->query("{$this->table}",$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    // 拒绝审核
    public function roleRefuse($data){
    	
    	$placeholders = str_repeat ('?, ',  count ($data) - 1) . '?';
    	$where = ' id in('.$placeholders.')';
    	 
    	$sql = "update minosdb.role_face_upload  set type=3 where " .$where;
    	 
    	$ret = $this->cdd->query($sql,$data);
    	 
    	//$ret = $this->db->query("{$this->table}",$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    /**
     * 审核信息获取 //------在与服务通讯中处理失败的情况 进行重置服务的原始目录
     * **/
    public function RoleAvatarFileInfo($data)
    {
    	__log_message("RoleAvatarFileInfo1",'userphotos-log');
    	
    	$placeholders = str_repeat ('?, ',  count ($data) - 1) . '?';
    	__log_message("RoleAvatarFileInfo2",'userphotos-log');
    	$where = ' id in('.$placeholders.')';
    	__log_message("RoleAvatarFileInfo3",'userphotos-log');
    	$sql = "select id,PlayerId,ImageId from  minosdb.role_face_upload  where " .$where;
    
    	if($this->cdd->query($sql,$data) && $this->cdd->rowcount() > 0)
    	{
    		$rows =$this->cdd->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    /**
     * 审核信息设置
     * **/
    public function RoleAvatarReset($data,$status=1)
    {	
    	$placeholders = str_repeat ('?, ',  count ($data) - 1) . '?';
    	$where = ' id in('.$placeholders.')';
    	
    	$sql = "update minosdb.role_face_upload  set type=$status where " .$where;
    	
    	$ret = $this->cdd->query($sql,$data);
    	
    	//$ret = $this->db->query("{$this->table}",$data,$where,array('id'=>$id));
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    
    
}

