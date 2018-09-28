<?php
	include_once "include/lib/log.php";
	include_once "include/lib/mysql.lib.php";

	$db = new  Mysqldb();
	$sql = null;
	$sqlOut = array();
	$j = 0 ;
	for($i=0;$i<1000000;$i++)
	{ 
		$j ++ ;
		if($sql==null){
			$sql.="(".$i.",'".md5($i.'jack')."')";		
		}
		$sql.=",(".$i.",'".md5($i.'jack')."')";

		if($i==5000)
		{
			$j = 0;
			$sqlOut[] = $sql;
			$sql = null;
		}
	}
	if(!empty($sql))
	{
		$sqlOut[] = $sql;
	}
	foreach($sqlOut as $var)
	{
		$sqlstr = 'insert into test.tb_verif_sdata(uin,`name`) VALUES ' .$var;
		$ret  = $db->query($sqlstr);
		
	}
	 
	echo $ret;
	 
?>