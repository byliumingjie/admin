<?php
	error_reporting(E_ALL);
	
	ini_set('display_errors', '1');
	include_once '../include/lib/log.php';
	include_once "../include/lib/mysql.lib.php";
	
	$platformId = 1;
	
	$getRoledb = new Mysqldb(); 
	
	function getActivityInfo($platformId,$getRoledb)
	{ 
		$prepare_array =['platformId'=>$platformId];
		
		$sql = 'SELECT id,platformId,title,content,activityType,starttime,
		endtime,stoptime,ResetType,ResetTime,rules FROM globaldb.game_activity 
		WHERE platformId=:platformId';
		
		$statement = $getRoledb->query($sql,$prepare_array);
	
		if( $statement && $getRoledb->rowcount($statement)>0 )
		{
			$getwechatInfo = $getRoledb->fetch_all($statement);
			
			if(count($getwechatInfo)>0)
			{
				return $getwechatInfo;
			}
			return false;
		}
		return false;
	}
	
	$activityOut = getActivityInfo($platformId,$getRoledb);
	 
	foreach ($activityOut as $inactivity)
	{	
		$activityId = (int)$inactivity['id'];
		$title = $inactivity['title'];
		$content =  $inactivity['content'];
		$inrulesOut = json_decode($inactivity['rules'],true);
		$sub_activity =array();
		foreach ($inrulesOut as $Inrules)
		{
			$NodeBewrite = $Inrules['NodeBewrite'];
			$itemList = $Inrules['itemList'];
			
			//echo $activityId.'--'.$title.'--'.$content.'--'.$NodeBewrite."<br>";
			
			 
			$sub_activity[] = array('title'=>$NodeBewrite);
		} 
		
		$data[] = array
		(
			'id'=>$activityId,
			'title'=>$title,
			'content'=>$content,
			'sub_activity'=>$sub_activity
		);
		
	}
	
	echo json_encode($data);
	
	
	
	