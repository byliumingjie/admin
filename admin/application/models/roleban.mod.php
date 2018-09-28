<?php

/* 
 * 区服管理
 */
class Roleban_Model extends Model
{
 	private $table = 'tb_platform';
    private $db = null;
    private $cdd = null;
    
     
  	public function __construct($region) 
    { 
        parent::__construct(); 
        $this->cdd = Mysql::database('',$region);         
    } 
	// 录入
	
	public static function setRoleBan($conn,$data,$logtab=false)
	{
		$obj = new Roleban_Model($conn);
		
		$addItion = " ON DUPLICATE KEY UPDATE
		description='{$data['description']}',
		createtime='{$data['createtime']}',
		lockType={$data['lockType']},
		lockTimeType={$data['lockTimeType']},
		lockEndtime={$data['lockEndtime']},
		executor='{$data['executor']}',
		lockStatus = {$data['lockStatus']}
		"; 
		$roleBantable = 'tb_role_ban_log';
		
		if ($logtab==false)
		{
			$roleBantable = 'game_role_ban';
		}
		
        return $obj->cdd->insert($roleBantable,$data,$addItion);
    }
    // set log 
    public static function setRoleBanLog($conn,$data)
    { 
    	$obj = new Roleban_Model($conn);
    	return $obj->cdd->insert('game_role_ban_log',$data,$addItion);
    }
    
    //
    public static function getRoleBanInfo($conn,$data)
    {
    	__log_message('lock role db1','rock-log');
    	$obj = new Roleban_Model($conn);
    	__log_message('lock role db2','rock-log');
    	$sql = 'select * from game_role_ban 
    	WHERE platId=:platId AND serverId=:serverId AND player_id=:player_id limit 1';
    	__log_message('lock role db3' .$sql ,'rock-log');
    	if($obj->cdd->query($sql,$data) && $obj->cdd->rowcount()>0)
    	{
    		__log_message('lock role db4' .$sql ,'rock-log');
    		$rows = $obj->cdd->fetch_row();
    		return $rows;
    	}
    	__log_message('lock role db5' .$sql ,'rock-log');
    	return false;
    }
	// 修改
	public static function updaterole($platform,$worleid,$roleid,$data)
	{
		//__log_message("roleidd::".$roleid."--worleid::".$worleid);
		$obj = new Roleban_Model($platform);
	 	return $obj->cdd->update2('globaldb.tb_role',$data,'roleid=:roleid and worleid=:worleid',
	 	array('roleid'=>$roleid,'worleid'=>$worleid));
	}
    // 录入日志 
    public static function setlog($platform,$data)
    {
    	$obj = new Roleban_Model($platform);
 		
        $sql = "insert into tb_role_ban_log(CREATRTIME,ROLESID,ROLEID,
        DEVICE,TYPE,STATUS,BEGINTIME,ENDTIME,REASON,OPERATOR,DETAIL) values $data";
       // __log_message($sql);
 		return $obj->cdd->query($sql);
    }
	//  
	public static function Stat_rolebanTotal($conn,$data='',$callKey)
	{
		$obj = new Roleban_Model($conn);
		$dbName = $conn['db'];
		$dat = $data;
		
		if (empty($data))
		{
			$sql = "CALL PROC_STAT_PAGING('{$callKey}','total',
			'{$dbName}','','',\"{$dat}\")";
		}else{
			$sql = "CALL PROC_STAT_PAGING('{$callKey}','total',
			'{$dbName}','','',\"{$dat}\")";
		} 
		__log_message("clla::::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_row();           
		   return $rows;
		} 
	}
	//  
	public static function Stat_rolebanInfo($conn,$data="",$page="",$pagesize="",$callKey)
	{
		$obj = new Roleban_Model($conn);
		$dbName = $conn['db'];
		$limit = '';
        
		$dat = $sid.' '.$data;
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL PROC_STAT_PAGING('{$callKey}','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL PROC_STAT_PAGING('{$callKey}','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		} 
		__log_message("clla::::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
			$rows =$obj->cdd->fetch_all();           
			return $rows;
		}  
	}
	
}

