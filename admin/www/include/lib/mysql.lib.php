<?php

/**
 * 数据库类
 *
 */
class Mysqldb {
    private $dbh; 
    private $statement;
    private $dbconfig; 
    
    function __construct() 
    {
    	include_once 'log.php';
    	
    	/* $this->dbconfig = array
    	(
    		'host'=>'121.40.110.104',
    		'port'=>3306,
    		'user'=>'minosphp',
    		'pass'=>'123456',
    		'dbname'=>'minosdb'
    	); */
    	$this->dbconfig = array
    	(
    			'host'=>'127.0.0.1',
    			'port'=>3306,
    			'user'=>'root',
    			'pass'=>'mns!@#2017',
    			'dbname'=>'minosdb'
    	);
    	//$this->dbconfig = $dbconfig;
    	$dbh = $this->connect();
    	
    	
    	if ($this->connect())
    	{
    		$this->dbh = $this->connect();
    	}
    	
    }
    
    private function  connect()
    { 
    	$_dsn  = 'mysql:host=' . $this->dbconfig['host'].';';
    	$_dsn .= 'port=' . $this->dbconfig['port'].';';
    	$_dsn .= 'dbname=' . $this->dbconfig['dbname'].';';
    	$_dsn .= 'charset=utf8';
    
    	try { 
    		$dbLink = new PDO($_dsn, $this->dbconfig['user'],
    		$this->dbconfig['pass'],array(
    		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';")); 
    		$dbLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    		
    	} catch (PDOException $e) { 
    		_log('Connection failed: ' . $e->getMessage(),'db'); 
    		return false;
    	} 
    	return  $dbLink;
    }
    
    public function close() 
    {
    	if (is_object($this->dbh)) {
    		$this->dbh = null;
    	}
    }
    public function  query($sql,$prepare = array())
    {
    		//$dbLink = connect();
    		//var_dump($this->dbh);
    		if(empty($sql) || !is_array($prepare) || empty($this->dbh))
    		{	//echo  "lod false prepare 1";
    			_log("false execution failed",'db');
    		}
    		//echo  "lod false prepare ??";
    		$dbh = $this->dbh->prepare($sql);
    		//echo  "lod false prepare ??";
    		$dbh->closeCursor();
    
    		$res = $dbh->execute($prepare);
    		//echo  "lod false prepare ??";
    		if (!$res)
    		{
    			_log("db error : ".
    					json_encode($dbh->errorInfo())." sql:" .$sql,'db');
    			return false;
    		}
    		return $dbh;
    }
    /**
     *  update
     *
     */
    public function update($table, $aFields, $where = '',
    		$prepare_array=array()) {
    
    	if (!$where)
    		return false;
    
    	if(!is_array($aFields) || count($aFields)<1)
    		return false;
    
    	$aSet = array();
    	foreach ($aFields as $key => $v) {
    		$aSet[] = "`{$key}`=:{$key}";
    		$prepare_array[$key] = $v;
    	}
    	$aSet && $set = implode(',', $aSet);
    	if (empty($set))
    		return false;
    
    	$sql = " UPDATE {$table} SET {$set} WHERE {$where}";
    	if(query($sql, $prepare_array,$this->dbh)){
    			
    		return true;
    	}
    
    	return false;
    }
    /**
     *  自定义 update
     *
     */
    public function update2($table, $aFields, $where = array())
    {
    	if (!$where)
    		return false;
    
    	if(!is_array($aFields) || count($aFields)<1)
    		return false;
    
    	$aSet = array();
    	foreach ($aFields as $key => $v) {
    		$aSet[] = "`{$key}`={$v}";
    	}
    
    	foreach($where as $fileKey=>$fileVar)
    	{
    		if(isset($whereOut))
    		{
    			$whereOut[$fileKey] = "AND  {$fileKey}={$fileVar}";
    		}else{
    			$whereOut[$fileKey] = "WHERE {$fileKey}={$fileVar}";
    		}
    	}
    	$aSet && $set = implode(',', $aSet);
    
    	$wheres = implode(' ', $whereOut);
    
    	$sql = " UPDATE {$table} SET {$set}  {$wheres}";
    	_log("updata2 sql ".$sql,'db');
    	if(mysqlQuery($sql)){
    		return true;
    	}
    	return false;
    
    }
    
    public function mysqlQuery($sql)
    { 
    	if(empty($sql) || empty($this->dbh))
    	{
    		return false;
    		_log("false execution failed",'db');
    	}
    	return $this->dbh->query($sql);
    }
    /**
     * 返回结果集
     * @$statement PDO
     */
    public function fetch_all($statement)
    {
    	return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function rowcount($statement)
    {
    	return $statement->rowCount();
    }
    
    /**
     * 单个录入
     *
     * @param type $table  tables
     * @param type $array =array(fields=>value)
     * @param type $dbLink pdo connect
     */
    public function insert($table, $array = array())
    {
    	$sql = " INSERT INTO {$table} ";
    	$fields = array_keys($array);
    	$values = array_values($array);
    	$condition = array_fill(1, count($fields), '?');
    
    	$sql .= "(`" . implode('`,`', $fields) . "`)
		VALUES (" . implode(',', $condition) . ")";
    
    	return $this->query($sql, $values);
    }
    public function insert2($table, $array = array(),$addition =null)
    {
    	$sql = " INSERT INTO {$table} ";
    	$fields = array_keys($array);
    	$values = array_values($array);
    	$condition = array_fill(1, count($fields), '?');
    
    	$sql .= "(`" . implode('`,`', $fields) . "`)
		VALUES (" . implode(',', $condition) . ") ".$addition;
    	//json_decode($json,tur)
    	return $this->query($sql, $values);
    }
    /**
     * 批量插入
     * 字段与数值的对应关系，请调用方处理好
     *
     * @param type $table
     * @param type $field = "uid,name,sex",用逗号隔开
     * @param type $data = 多维数组
     */
    public function insertBatch($table,$fields,$data=array())
    {
    	if(empty($fields))
    		return false;
    
    	if(!is_array($data) || count($data)<1){
    		return false;
    	}
    	$sql = " INSERT INTO {$table} ($fields) VALUES  ";
    
    	foreach($data as $v){
    		$sql.="(".implode(',', $v)."),";
    	}
    
    	return $this->query(rtrim($sql,','),array());
    }
    /**
     * 注入符验证
     *
     * @param type $sql_str 验证字符
     */
    private function inject_check($sql_str)
    {
    	$injectSign = 'select|insert|update|delete|\'|\/\*|\*|';
    
    	$injectSign .= '\.\.\/|\.\/|union|into|load_file|outfile';
    
    	$check= eregi($injectSign, $sql_str);
    
    	if($check)
    	{
    		//exit('{"status":"false","result":"Invaild symbol"}');
    	}else
    	{
    		return $sql_str;
    	}
    }
 
}
?>