<?php

/* 
 * 区服管理
 */
class AdminCache_Model extends Model{
 	private $table = 'tb_cache';
    private $dbh = null;
 
     
	public function __construct($region='')
	{
		parent::__construct();
		if (!empty($region)){
			$this->dbh = Mysql::database('',$region);
		}
	}
   
     public function getlogTotal($platform,$having=''){
    	
    	$obj = new AdminCache_Model( $platform );
    	 
        $sql = 'SELECT count(*) as total FROM tb_cache '.$having; 
         __log_message("tatal".$sql);
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
    	{
    		$rows =$obj->dbh->fetch_row();
    		return $rows;
    	}
        return false;
    }
	   /**
     * 查询区服列表
     * @param type $page        页码
     * @param type $pagesize    条数
     * @return boolean or array 查询区服列表，成功返回区服列表，失败返回false
     */
    public function getlogList($platform='',$page =1 ,$pagesize=15,$having=''){
    	
    	$obj = new AdminCache_Model( $platform );
    	
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }        
        $sql = 'SELECT * FROM tb_cache '.$having.' order by id desc '.$limit ;
        __log_message("info".$sql);
        if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
            $rows = $obj->dbh->fetch_all();
            return $rows;
        }
        return false;
    } 
    // 删除指定缓存
    public function delecache($platform,$id)
    {    	
    	$obj = new AdminCache_Model( $platform );
    	$sql = "DELETE FROM  tb_cache WHERE id =$id";    	 
    	__log_message("dbsql".$sql);
    	if($obj->dbh->query($sql)){
    	 return true;
    	}
    	return false;
    }
}

