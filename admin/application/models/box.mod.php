<?php

/* 
 * 区服管理
 */
class Box_Model extends Model
{
 	var $table = 'tb_platform';
    private $db = null;
    private $cdd = null;
 
    /* --------------------------------------------------------- */
    public function __construct($region) 
    { 
        parent::__construct(); 
        $this->cdd = Mysql::database('key.conn'.$region);         
    }	 
	
    // GET TB-BOX TOABL
    public static function get_boxTotal($platform,$dbname,$data='')
    {
    	
		$obj = new Box_Model($platform);	
		
		if (!empty($data))
		{
			$sql = "select count(*) as cont from $dbname.tb_box {$data}";
		}else{
			$sql = "select count(*) as cont from $dbname.tb_box";
		} 
		//__log_message("totalsql:::".$sql);
		
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0)
		{	 			 		
		   $rows =$obj->cdd->fetch_row();           
		   return $rows;
		}  
	}
	//get tb_box  //测试版后期增加分页功能
	public static function get_box($platform,$dbname,$data=''
	,$page="",$pagesize="")
	{ 
		$obj = new Box_Model($platform);	
		$limit = '';	
		
		 if(!empty($page) && !empty($pagesize))
        {
        	//__log_message("go page ");
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;
           // __log_message("go page limit".$limit);            
        } 
		if(!empty($data))
		{
			$sql = "select * from $dbname.tb_box {$data} ORDER BY boxid DESC {$limit}";
		}  
		else 
		{
			$sql = "select * from $dbname.tb_box ORDER BY boxid DESC {$limit}";
		} 		 
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0){
            $row = $obj->cdd->fetch_all();           
            return $row;
        }
	} 
	
	public static function delet_box($platform,$dbname)
	{
		$obj = new Box_Model($platform);
		$sql ="delete from $dbname.tb_box";
		//__log_message("sqlll delete box :::".$sql." platform:::".$platform);
		if($obj->cdd->query($sql)){ 
	            return true;
	    } 
	    return false; 
	}
	// 设置录入
	public static function set_box($platform,$dbname,$data,$format = '' ){
		$obj = new Box_Model($platform);
		if($format == true ){				 
			$sql ="INSERT INTO $dbname.tb_box(boxid,thingtotal,thing1type,thing1ID,thing1num,
			thing2type,thing2ID,thing2num,thing3type,thing3ID,thing3num,thing4type,thing4ID,
			thing4num)VALUES".$data; 
			// return $this->db->insert($this->table,$data);
			//__log_message("testBox:::".$sql);
			if($obj->cdd->query($sql)){ 
	            return true;
	        }        
	        return false;
		}
		else{
			//__log_message("testBox:::".$data);
			return $obj->cdd->insert($dbname.'.tb_box',$data);
		}
	}
	// 修改
	public static function update($platform,$boxid,$dbname,$data)
	{
		$obj = new Box_Model($platform);
	 	return $obj->cdd->update2($dbname.'.tb_box',$data,'boxid=:boxid',array('boxid'=>$boxid));
	}
	// 获取字段属性
	public static function get_full($platform,$dbname)
	{
	 	
		$obj = new Box_Model($platform);				 
		$sql ="show full fields from $dbname.tb_box"; 
		//__log_message("testBox:::".$sql);
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0){
            $row = $obj->cdd->fetch_all();           
            return $row;
        } 
	}
	//:~
}

