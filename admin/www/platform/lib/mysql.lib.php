<?php
/**
 * 查询数据表,所有的数据表的查询操作，最终都到这里处理
 *
 * @param string $sql
 * @param array $prepare
 * @param type $dbLink pdo connect
 */
 function  connect(){
    $dbconfig['key.gm.db'] = array
    (
     'host'=>'localhost',
     'port'=>3306,
     'user'=>'root',
     'pass'=>'mns!@#2017',
     'dbname'=>'admin'
    );
    
    if (!is_array($dbconfig['key.gm.db'])){
        _LOG("dbconfig is null"); 
    }

    $_dsn  = 'mysql:host=' . $dbconfig['key.gm.db']['host'].';';
    $_dsn .= 'port=' . $dbconfig['key.gm.db']['port'].';';
    $_dsn .= 'dbname=' . $dbconfig['key.gm.db']['dbname'].';';
    $_dsn .= 'charset=utf8';

   try {
	
        $dbLink = new PDO($_dsn, $dbconfig['key.gm.db']['user'],
        $dbconfig['key.gm.db']['pass'],array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));

        $dbLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);		

    } catch (PDOException $e) {
        //var_dump($e->getMessage());
        _LOG('Connection failed: ' . $e->getMessage(),'db');

    }
	return  $dbLink;
}

if(function_exists("query")!=true)
{
	function  query($sql, 
	$prepare = array(),$dbLink=null)
	{
		$dbLink = connect();

		if(empty($sql) || 
		!is_array($prepare) || empty($dbLink))
		{
			_LOG("false execution failed",'db');			
		}	 
		  
		$dbh = $dbLink->prepare($sql);	
		
		$dbh->closeCursor();
		
		$res = $dbh->execute($prepare);
		
		if (!$res)
		{	 
			_LOG("db error : ".
			json_encode($dbh->errorInfo())." sql:" .$sql,'db');
			return false;
		} 		 
		return $dbh;
	}
}

/**
 *  update
 * 
 */
function update($table, $aFields, $where = '',
$prepare_array=array(),$dbLink) {

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
	if(query($sql, $prepare_array,$dbLink)){
		 
		return true;
	}

	return false;
}
/**
 *  自定义 update
 * 
 */
function update2($table, $aFields, $where = array())
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
	_LOG("updata2 sql ".$sql,'db');
	if(mysqlQuery($sql)){                 
	   return true;
	} 
	return false;

}

function mysqlQuery($sql)
{
	$dbLink = connect();
	if(empty($sql) ||  empty($dbLink))
	{
		return false;
		_LOG("false execution failed",'db');			
	}
	return $dbLink->query($sql);
}
/**
 * 返回结果集
 * @$statement PDO
 */
function fetch_all($statement) 
{   
     return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function rowcount($statement) 
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
function insert($table, $array = array(),$dbLink) 
{
	$sql = " INSERT INTO {$table} ";
	$fields = array_keys($array);
	$values = array_values($array);
	$condition = array_fill(1, count($fields), '?');
	
	$sql .= "(`" . implode('`,`', $fields) . "`) 
	VALUES (" . implode(',', $condition) . ")";
	   
	return query($sql, $values,$dbLink);
}
/**
 * 批量插入
 * 字段与数值的对应关系，请调用方处理好
 *
 * @param type $table
 * @param type $field = "uid,name,sex",用逗号隔开
 * @param type $data = 多维数组
 */
function insertBatch($table,$fields,$data=array(),$dbLink)
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
	
	return query(rtrim($sql,','),array(),$dbLink);
}
/**
 * 注入符验证
 *
 * @param type $sql_str 验证字符
 */
function inject_check($sql_str) 
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
 