<?php

/* 
 * 区服管理
 */
class Servers_Model extends Model{
 	private $table = 'tb_platform';
    private $dbh = null;
    private $cdd = null;
     
	public function __construct($region)
	{
		parent::__construct();
		
		if (!empty($region))
		{
			$this->dbh = Mysql::database('',$region);
		}
	}
   
     public function getServersTotal($platform=NULL){
    	 
        $sql = 'SELECT count(*) as total FROM tb_platform '; 
         
    	if($this->dbh->query($sql) && $this->dbh->rowcount() > 0)
    	{
    		$rows =$this->dbh->fetch_row();
    		return $rows;
    	}
        return false;
    }
    // 服务器校验
    public   function serverStatusCheck($conn,$sid)
    { 
    	$obj = new  Servers_Model($conn);
    	
    	$sql = "SELECT sid,status FROM tb_platform WHERE sid=$sid";
     
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
    public function getServers($page =1 ,$pagesize=15){
    	 
        $limit = '';
        if(!empty($page) && !empty($pagesize)){
            $offset = ($page-1) * $pagesize;
            $limit  = ' LIMIT '.$offset.','.$pagesize;
        }
        
        $sql = 'SELECT * FROM tb_platform  order by type desc '.$limit ;
        if($this->dbh->query($sql) && $this->dbh->rowcount()>0){
            $rows = $this->dbh->fetch_all();
            return $rows;
        }
        return false;
    }
    public function getServersList($region){
    
    	$obj = new Servers_Model($region);
    	
    	$sql = 'SELECT * FROM tb_platform  order by type desc ';
    	 
    	if($obj->dbh->query($sql) && $obj->dbh->rowcount()>0){
    		$rows = $obj->dbh->fetch_all();
    		return $rows;
    	}
    	return false;
    }
    
    /* 设置服务器 */
    public  function editServers($id,$data,$where=NULL)
    {
    	if (!empty($where))
    	{
    		$where = $where;
    		$platformId = $data['platformId'];
    		$type = $data['type'];
    		$array = ['platformId'=>$platformId,'type'=>$type];
    	}
    	else{
    		$where = 'id=:id';
    		$array = ['id'=>$id];
    	}
    	
    	$ret = $this->dbh->update2($this->table,$data,$where,$array);
    	if($ret == false)
    	{
    		return false;
    	}
    	return true;
    }
    // 删除区服信息
    public function delServers($id)
    {
    	$where = "id=:id";
    	$array = array('id' => $id);
    	return $this->dbh->delete($this->table, $where, $array);    	
    }
    /**
     * 增加区服
     * **/
    public function addServer($data)
    { 
    	return $this->dbh->insert($this->table,$data);
    }
    // 
    /**
     * 检查重复项 1 统计 配置 2 服务器
     * */
    public function server_Exclude_duplicates($devtype=1,$platformId,$type=NULL)
    {
    	$array = [];
    	if (empty($devtype)) 
    	{
    		return false;
    	}
    	if ($devtype==1)
    	{
    		$array = ['platformId'=>$platformId,'type'=>0]; 
    	}
    	elseif($devtype==2)
    	{
    		$array = ['platformId'=>$platformId,'type'=>$type];
    	}
    	
    	$sql = 'SELECT * FROM '.$this->table.
    	' WHERE platformId=:platformId AND type=:type';
    	
    	if($this->dbh->query($sql,$array) && $this->dbh->rowcount() > 0)
    	{
            $row = $this->dbh->fetch_all();
            return $row;
        } 
    	return false;
    }
}

