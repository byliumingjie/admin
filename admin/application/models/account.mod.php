<?php

/* 
 * 区服管理
 */
class Account_Model extends Model{
 	 
    private $db = null;
    private $cdd= null;
     
    public function __construct($region=NULL) 
    { 
        parent::__construct(); 
        if (!empty($region))
        {
        	$this->cdd = Mysql::database('',$region);
        }
    }
    public function getRoleIndex($conn,$keyname,$type='nick_name')
    {
    	__log_message('getRoleIndex','account-log');
    	$obj = new Account_Model($conn);
    	__log_message('getRoleIndex1','account-log');
    	$sql =NULL;
    	$where=[];
    	__log_message('getRoleIndex2','account-log');
	    if ($type=='nick_name')
	    {
	    	__log_message('getRoleIndex3'.$keyname,'account-log');
	    	$nick_name = $keyname;
	    	
	    	$sql = "SELECT player_id,nick_name FROM game_data_role 
			WHERE nick_name =:nick_name limit 1";    	
	    	$where = ['nick_name'=>$nick_name];
    	}
    	else
    	{
    		__log_message('getRoleIndex4','account-log');
    		$player_id = $keyname;
    		
    		$sql = "SELECT player_id,nick_name FROM game_data_role
			WHERE player_id =:player_id limit 1";
    		$where = ['player_id'=>$player_id]; 
    	}
    	__log_message('getRoleIndex5' . $sql ,'account-log');
    	if($obj->cdd->query($sql,$where) 
    	&& $obj->cdd->rowcount() > 0)
    	{
    		__log_message('getRoleIndex22','account-log');
    		$row = $obj->cdd->fetch_row();
    		return $row;
    	}
    	__log_message('getRoleIndex33','account-log');
    	return false;
    }
}

