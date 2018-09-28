<?php

/* 
 * 区服管理
 */
class  Recharge_Model extends Model{
    private $db = null;
    private $cdd = null;
     
    public function __construct($region) 
    { 
        parent::__construct(); 
        $this->cdd = Mysql::database('',$region);         
    }
   	
	// 订单(总)
	public static function Stat_RechargeTotal($conn,$data='')
	{
		$obj = new Recharge_Model($conn);
		//$dbName = $conn['db'];
		 
		if (empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('recharge','total',
			'globaldb','','',\"{$data}\")";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('recharge','total',
			'globaldb','','',\"{$data}\")";
		} 
		 __log_message("giftTotal::::".$sql,'recharge-log');
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_row();           
		   return $rows;
		} 
	}
	// 订单 INFO
	public static function Stat_RechargeInfo($conn,$data="",
	$page="",$pagesize="")
	{
		$obj = new Recharge_Model($conn);
		$dbName = 'globaldb';
		$limit = '';
		 
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('recharge','TotalPaging',
			'{$dbName}','','{$limit}',\"{$data}\")";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('recharge','TotalPaging',
			'{$dbName}','','{$limit}',\"{$data}\")";
		} 
		 __log_message("giftInfo::::".$sql,'recharge-log');
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
			$rows =$obj->cdd->fetch_all();           
			return $rows;
		}  
	} 	
 
	// 补单变更
	public static function EditOrder($conn,$id,$data)
	{		 	
     	$obj = new Recharge_Model($conn);
     	$dbname = $conn['db'];
	return $obj->cdd->update2($dbname.'.tb_recharge',$data,'id=:id',array('id'=>$id));
	}	
	// 订单类型修改
	public static function EditOrderType($conn,$id,$data)
	{		 	 
     	$obj = new Recharge_Model($conn);
     	$dbname = $dbName = $conn['db'];
	 	return $obj->cdd->update2($dbname.'.tb_querypay',$data,'id=:id',array('id'=>$id));
	}
	 
	
}

