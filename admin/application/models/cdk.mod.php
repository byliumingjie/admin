<?php

/* 
 * 区服管理
 */
class CDK_Model extends Model{
 	private $table = 'tb_platform';
    private $db = null;
    private $cdd = null;
     
    public function __construct($region=NULL) 
    { 
        parent::__construct(); 
        $this->cdd = Mysql::database('',$region);         
    }
   
   /**
 	* 获取最大一个iD
	**/ 
	public static function MaxID($platform)
	{   
		$obj = new CDK_Model( $platform );		 
		$sql ="select max(id)+1 as maxid from tb_gift limit 1";
		
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0){
            $row = $obj->cdd->fetch_row(); 
            return $row;
        }
	} 
	// 录入礼包
	public static function setgift($platform,$data)
	{
		$obj = new CDK_Model( $platform );		
		//$obj->cdd->insert('tb_gift',$data);
		
		if($obj->cdd->insert('tb_gift',$data))
		{
			if($lastId=$obj->cdd->get_insertlastid())
			{
				return $lastId;
			};
			return false; 
		}
		return false;
		
		/* 
		 if($lastId=$this->db->get_insertlastid())
    		{
    			return $lastId; 
    		};
    		return false;
    		 
		 $sql ="insert into tb_gift(name,title,item1,
		item2,item3,item4,datetime) values $data";
		
		__log_message("set gifttt:::".$sql);
		
		if($obj->cdd->query($sql))
		{
			//$obj->cdd->
			return true;
		} */
    }
	// CDK(总)
	public static function Stat_giftTotal($conn,$data='')
	{
		__log_message("stat giftTotal dbbbb:::".$conn['db']);
		$obj = new CDK_Model($conn);
		$dbName = $conn['db'];
		$dat = $sid.' '.$data;
		__log_message("stat giftTotal dbbbb:::");
		if (empty($data))
		{
			$sql = "CALL PROC_STAT_PAGING('gift','total',
			'{$dbName}','','',\"{$dat}\")";
		}else{
			$sql = "CALL PROC_STAT_PAGING('gift','total',
			'{$dbName}','','',\"{$dat}\")";
		} 
		__log_message("giftTotal::::".$sql);
		
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_row();           
		   return $rows;
		} 
	}
	// CDK INFO
	public static function Stat_giftInfo($conn,$data="",$page="",$pagesize="")
	{
		$obj = new CDK_Model($conn);
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
			$sql = "CALL PROC_STAT_PAGING('gift','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL PROC_STAT_PAGING('gift','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		} 
		__log_message("clla::::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
			$rows =$obj->cdd->fetch_all();           
			return $rows;
		}  
	} 	
 
	// 编辑
	public static function giftEdit($conn,$id,$data){
		 	
     	$obj = new CDK_Model($conn);
	 	return $obj->cdd->update2('tb_gift',$data,'id=:id',array('id'=>$id));
	}	
	// 删除
	public static function giftdelete($conn,$id){		 
		$db = new CDK_Model($conn);
        $where = "id=:id";
        $array = array('id' => $id);
        return $db->cdd->delete("tb_gift",$where, $array);
	}
	
		// CDK CODE (总)
	public static function Stat_CdkTotal($conn,$data='')
	{
		$obj = new CDK_Model($conn);
		$dbName = $conn['db'];
		$dat = $sid.' '.$data;
		
		if (empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdkcode','total',
			'{$dbName}','','',\"{$dat}\")";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdkcode','total',
			'{$dbName}','','',\"{$dat}\")";
		} 
		//__log_message("giftTotal::::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_row();           
		   return $rows;
		} 
	}
	// CDK INFO
	public static function Stat_CdkInfo($conn,$data="",$page="",$pagesize="")
	{
		$obj = new CDK_Model($conn);
		
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
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdkcode','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdkcode','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		} 
		__log_message("clla::::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
			$rows =$obj->cdd->fetch_all();           
			return $rows;
		}  
	} 	
	public static function  setcode ($platform,$data=array())
	{
		$obj = new CDK_Model( $platform );	
		
		$fields = 'title,giftid,batch,code,cdk,gift_type,
		gift_rule,starttime,endtime,plafrominfo,serverId,creattime'; 
		 
		if($obj->cdd->insertBatch('tb_cdk',$fields,$data))
		{
			return true;
		}
		return false;
	}
	// 下载/验证
	public  static function getcode($platform,$codeid="",$batch="")
	{
		$obj = new CDK_Model( $platform );		
		
		/* $dat = (!empty($codeid) && !empty($batch))
		?
		" WHERE a.giftid = $codeid and a.batch = '{$batch}'"
		:
		" WHERE a.batch = '{$batch}'"; */
		
		$dat = " WHERE a.batch = '{$batch}'";
		
		$sql ="SELECT gift_type,gift_rule,cdk,
		title,serverId,plafrominfo FROM tb_cdk  as a $dat";
		
		__log_message("dow::".$sql);
			
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_all();           
		   return $rows;
		}
	}
	//  验证
	public static function getVerifyCode($platform)
	{
		$obj = new CDK_Model( $platform );
		
		$sql ="SELECT batch FROM tb_cdk  as a GROUP BY batch";
		
		__log_message("dow::".$sql);
			
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_all();           
		   return $rows;
		}
	}
	
}

