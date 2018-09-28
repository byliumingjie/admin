<?php

/* 
 * 区服管理
 */
class Statdata_Model extends Model{
 	private $table = 'tb_platform'; 
    private $dbh = null;
  
    /* ---------------------------------------------*/
    public function __construct($region) 
    { 
        parent::__construct(); 
        $this->dbh = Mysql::database('',$region);         
    }
   /**
	* get_region info
	* $platform = 平台ID
	* $sid = 区服ID	* 
	**/ 
    /* ---------------------------------------------*/
	public static function get_region($platform,$sid)
	{   
		$obj = new Statdata_Model( $platform );		 
		$sql ="select * from tb_admin_region_info 
			   where PLATID = $platform  
			   and   ZONEID = $sid";
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0){
            $row = $obj->dbh->fetch_row();
           
            return $row;
        }
	}
	/* ---------------------------------------------*/
	# 统计两个时间戳的相关数据
	public static function  time_intervalData($platform,$sid,$start_time,$end_time)
	{
		 $obj = new Statdata_Model($platform); 
	}
	/* ---------------------------------------------*/
	# 统计  日 周 月 的先关数据  
	public static function countdownData()
	{
		 $obj = new Statdata_Model($platform);	
	} 
	/* ---------------------------------------------*/
	# getProcDailyData
	/*
	 * In  inmode varchar(20),In type varchar(20),In startTime varchar(50),
	 * In  endTime varchar(50),In dbTable varchar(500),In sid int(5),In dbTable2 varchar(500)
	 *  $stmt1->execute($row);
        $rs1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        $stmt1->closeCursor();
        syslog(LOG_INFO,'$rs1: '.print_r($rs1[0],1).' '.rand());
        syslog(LOG_INFO,'$rs2: '.print_r($rs2[0],1).' '.rand());
	 * */
	public  static function getProcDailyData($platform,$inmode,
	$Type,$startTime,$endTime,$dbTable,$sid,$dbTable2)
	{
		$t1 = microtime(true);
		$rows = null;	 
		$resul =null;
		
		$obj = new Statdata_Model($platform);	
		__log_message("CALL sqllhost:::".$platform['mysqlhost']); 		 
		$sql ="CALL globaldb.PROC_STAT_DAILY_DATA('{$inmode}','{$Type}'
		,'{$startTime}','{$endTime}','{$dbTable}',{$sid},'{$dbTable2}')";
			
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
	 	{	  
            $rows =$obj->dbh->fetch_all();           
            return $rows;
        }
        
	} 
	/* ---------------------------------------------*/
	# 校验在选择区服的的时候通过查询配置表的相关配置文件查询
	# 是否包含先关的数据库表(此外还要包含数据库的名字)
	public static function dbTableVerification($platform,$database,$tables)
	{	
		
		$obj = new Statdata_Model($platform);		 
		if(!empty($database) && !empty($tables)){
			$sql ="SHOW TABLES FROM ".$database." like '%{$tables}%'";	
			__log_message('dbTableVerification'.$sql);	 
			if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 	{	  
	            $rows =$obj->dbh->fetch_all();           
	            return $rows;
	        }	
		} 
	}
	
	/* -----------------单利获取充值数据项-----------------*/
	# getpayinfo
	# 单利获取充值数据项（区服每一个角色充值记录信息）
	public static function getpayinfo($platform,$tables,
	$statime,$endtime,$usersid,$payconn='')
	{
		$obj = new Statdata_Model($platform);
		if ($payconn === 0)
		{
			$sql = "
			SELECT rechargetime as order_time,onerecharge as order_money,
			roleid as order_roleid,worldid  as user_sid
			FROM {$tables} 
			WHERE DATE(rechargetime) >= DATE('{$statime}')
			AND DATE(rechargetime)<=DATE('{$endtime}')
			AND worldid =$usersid
			";
		}
		else
		{
			$sql ="SELECT FROM_UNIXTIME(order_time) as order_time,
			order_money,order_roleid,user_sid FROM ".$tables." as a 
			WHERE DATE(FROM_UNIXTIME(order_time))>=DATE('{$statime}') 
			AND DATE(FROM_UNIXTIME(order_time))<=DATE('{$endtime}')
			AND order_state =2 AND user_sid = $usersid";
		}		
		__log_message("getpayinfo".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	            $rows =$obj->dbh->fetch_all();           
	            return $rows;
	     }	    
	}
	/* ---------------------------------------------*/
	# 支付金额
	public static function payroleinfo($platform,$tables,
	$statime,$endtime,$usersid,$payconn=''){
		$obj = new Statdata_Model($platform);
		if ($payconn === 0)
		{
			$sql ="SELECT DATE(rechargetime) as order_time,
			SUM(onerecharge) as money,
			COUNT(DISTINCT roleid)as rolenum,worldid  as user_sid
			FROM {$tables} 
			WHERE DATE(rechargetime)>=DATE('{$statime}') 
			AND DATE(rechargetime)<=DATE('{$endtime}')
			AND worldid = $usersid
			GROUP BY DATE(rechargetime)";
		}
		else{
		$sql ="
		SELECT DATE(FROM_UNIXTIME(order_time)) as order_time,SUM(order_money) 
		as money,COUNT(DISTINCT order_roleid) 
		as rolenum,user_sid FROM ".$tables." 
		WHERE order_time BETWEEN UNIX_TIMESTAMP('{$statime}') AND UNIX_TIMESTAMP('{$endtime}')
		AND order_state = 2 AND user_sid = ".$usersid."
		GROUP BY DATE(FROM_UNIXTIME(order_time));";
		}	
		__log_message("payroleinfo".$sql);		 
		if( $obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
	            $rows =$obj->dbh->fetch_all();           
	            return $rows;
	    } 
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	// 角色信息
	public static function getroleinfo($platform,$dbname,$regionid,$statime,$endtime='',$type,$RetainedStatTime="",$RetainedEndTime="")
	{
		$obj = new Statdata_Model($platform);
		$sql ="CALL globaldb.PROC_STAT_ADMIN_USERINFO('{$type}','{$dbname}','{$regionid}',
		'{$statime}','{$endtime}','{$RetainedStatTime}','{$RetainedEndTime}')";
		__log_message("caaallll:::".$sql);
	 	if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
	 	{	 			 		
            $rows =$obj->dbh->fetch_all();           
            return $rows;
        } 
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	# 累计充值 
	public static function pay_grand_totalInfo($platform,$tables,
	$statime,$endtime,$usersid,$payconn='')
	{
		$obj = new Statdata_Model($platform);
		if ($payconn===0)
		{
			$sql ="SELECT SUM(onerecharge)as money,
			COUNT(DISTINCT roleid) as controle,
			worldid as user_sid
			FROM $tables 
			where DATE(rechargetime)>=DATE('{$statime}')
			AND DATE(rechargetime) <=DATE('{$endtime}')
			AND worldid = $usersid";
		}else{
			$sql ="SELECT SUM(order_money) as money,COUNT( DISTINCT order_roleid) 
			as controle,user_sid FROM  ".$tables."
			WHERE order_time BETWEEN UNIX_TIMESTAMP('{$statime}') AND UNIX_TIMESTAMP('{$endtime}')
			AND order_state = 2 AND user_sid = $usersid";
		}
		__log_message("pay get payroleinfopayroleinfopayroleinfo".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	            $rows =$obj->dbh->fetch_all();           
	            return $rows;
	     }		
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	# 单充详情  :混/专服	服务器	角色名	角色ID	UIN	等级	单笔充值（≥648rmb）	上次下线时间
	public static function PayDetailInfo($platform,$dbName,$tables,
	$statime,$endtime,$usersid,$MaxInterval,$payconn='')
	{	
		$obj = new Statdata_Model($platform);
		$interval = "单笔充值大于等于".$MaxInterval."rmb";
		if ($payconn===0)
		{
			$sql = "
			SELECT a.roleid as order_roleid,a.onerecharge as order_money,
			a.rechargetime as order_time,a.uin,a.ilevel as level,
			a.NickName as strName,a.date as updatetime,
			'{$interval}' as entry,a.worldid as worldID
			FROM(
				SELECT a.roleid,a.onerecharge,a.rechargetime,
				b.uin,b.ilevel,b.NickName,b.date,b.worldid 
				FROM {$dbName}.tb_recharge as a ,{$dbName}.login_table  as  b 
				WHERE a.worldid = $usersid
				AND b.worldid =$usersid
				AND a.onerecharge >=$MaxInterval
				AND DATE(a.rechargetime) >= DATE('{$statime}') 
				AND DATE(a.rechargetime)<=DATE('{$endtime}')
				AND a.roleid = b.roleid
				ORDER BY b.date DESC
			) as a  GROUP BY a.roleid
			";
		}
		else
		{
		$sql ="SELECT a.order_roleid,a.order_money,
		FROM_UNIXTIME(a.order_time) as order_time,a.uin,a.`level`,a.strName,
		FROM_UNIXTIME(a.updatetime)as updatetime,'{$interval}' as entry,a.worldID  FROM 
		(
			SELECT  a.order_roleid,a.order_money,FROM_UNIXTIME(a.order_time),
			a.order_time,b.uin,b.`level`,b.strName,
			b.updatetime,b.worldID FROM  ".$tables." as a
			STRAIGHT_JOIN ".$dbName.".t_qmonster_userdata as b on a.order_roleid = b.roleID 
			WHERE a.order_time BETWEEN UNIX_TIMESTAMP('{$statime}') AND UNIX_TIMESTAMP('{$endtime}')
			AND a.order_state = 2 
			AND a.user_sid = $usersid
			AND b.worldID = $usersid
			AND a.order_money >=$MaxInterval
			ORDER BY b.updatetime ASC
		)as a GROUP BY a.order_roleid ";
		} 
		__log_message("充值详情数据".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	            $rows =$obj->dbh->fetch_all();           
	            return $rows;
	     }	
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	# 累计充值 :混/专服	服务器	角色名	角色ID	UIN	等级	单笔充值（≥648rmb）	上次下线时间
	public static function Totalpayinfo($platform,$dbName,
	$tables,$statime,$endtime,$usersid,$grandTotal,$payconn='')
	{
		$obj = new Statdata_Model($platform);
		$interval = "累计充值大于等于{$grandTotal}Rmb";
		if ($payconn === 0){
		$sql ="SELECT * FROM( 
		SELECT a.roleid as order_roleid ,max(a.sumrecharge)/10 as order_money,
		a.rechargetime as order_time ,a.uin,a.ilevel as level,a.NickName as strName,
		a.date as updatetime,'{$interval}' as entry,a.worldid as worldID FROM(
				SELECT a.roleid,a.sumrecharge,a.rechargetime,
				b.uin,b.ilevel,b.NickName,b.date,b.worldid 
				FROM {$dbName}.tb_recharge as a ,{$dbName}.login_table  as  b 
				WHERE a.worldid = $usersid
				AND b.worldid = $usersid
				AND DATE(a.rechargetime)>= DATE('{$statime}') 
				AND DATE(a.rechargetime)<=DATE('{$endtime}')
				AND a.roleid = b.roleid
				ORDER BY b.date DESC
		) as a GROUP BY a.roleid
		) as b WHERE b.order_money >={$grandTotal}";
		}else{		
		$sql ="SELECT * FROM (	
		SELECT a.order_roleid,SUM(a.order_money) as order_money,
		FROM_UNIXTIME(a.order_time) as order_time,a.uin,a.`level`,a.strName,
			FROM_UNIXTIME(a.updatetime)as updatetime,'{$interval}' as entry,a.worldID  FROM 
			(
				SELECT  a.order_roleid,a.order_money,
				FROM_UNIXTIME(a.order_time) ,a.order_time,b.uin,b.`level`,b.strName,
				b.updatetime,b.worldID FROM  ".$tables." as a
				STRAIGHT_JOIN ".$dbName.".t_qmonster_userdata as b on a.order_roleid = b.roleID 
				WHERE a.order_time BETWEEN UNIX_TIMESTAMP('{$statime}') AND UNIX_TIMESTAMP('{$endtime}')
				AND a.order_state = 2 
				AND a.user_sid = $usersid
				AND b.worldID = $usersid 
				ORDER BY b.updatetime ASC
			)as a  GROUP BY a.order_roleid
		) as b  WHERE b.order_money >={$grandTotal}"; 
		}
		__log_message("累计充值详情数据".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	            $rows =$obj->dbh->fetch_all(); 
	                    
	            return $rows;
	     }	
		 
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	# 充值区间分布
	# SELECT COUNT(DISTINCT a.order_roleid) FROM roledb_ios_s1.t_order_form_s1 as a
	# WHERE a.order_time BETWEEN UNIX_TIMESTAMP('2015-01-20') AND UNIX_TIMESTAMP('2015-09-25')
	# AND order_state = 2 AND user_sid = 1
	# AND order_money BETWEEN 1 AND 100
	//$conn,$dbTable,$startTime,$endTime,$regionID,$First,$second,$payconn,$dbname
	public static function pay_Interval_Info($platform,$tables,$statime,$endtime,
	$usersid,$First,$second,$MaxInterval="",$Geometric="",$payconn="",$dbname="")
	{
		$interval = "";
		$obj = new Statdata_Model($platform);
		if($MaxInterval)
		{ 
			$interval = $MaxInterval."以上";
			if ($payconn === 0)
			{
				$sql ="
				SELECT SUM(onerecharge)as money,
				COUNT(DISTINCT roleid) as controle,
				'{$interval}' as entry,worldid as user_sid
				FROM {$dbname}.tb_recharge 
				where DATE(rechargetime)>=DATE('{$statime}')
				AND DATE(rechargetime) <=DATE('{$endtime}')
				AND worldid = $usersid
				and onerecharge >$MaxInterval";
			}else{
				$sql ="SELECT SUM(order_money) as money,
				COUNT( DISTINCT order_roleid) as controle,
				'{$interval}' as entry,user_sid FROM  ".$tables."
				WHERE order_time BETWEEN UNIX_TIMESTAMP('{$statime}') 
				AND UNIX_TIMESTAMP('{$endtime}')
				AND order_state = 2 AND user_sid = $usersid  
				AND order_money > $MaxInterval";
			} 
			__log_message("pay get max interval".$sql);		 
			if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
			 {	 			 		
		            $rows =$obj->dbh->fetch_all();           
		            return $rows;
		     }		
		}
		if($Geometric)
		{
			$interval = "单笔".$Geometric;
			if ($payconn === 0)
			{
				$sql ="
				SELECT SUM(onerecharge)as money,
				COUNT(DISTINCT roleid) as controle,
				'{$interval}' as entry,worldid as user_sid
				FROM {$dbname}.tb_recharge 
				where DATE(rechargetime)>=DATE('{$statime}')
				AND DATE(rechargetime) <=DATE('{$endtime}')
				AND worldid = $usersid
				and onerecharge = $Geometric";
			}else{
				$sql ="SELECT SUM(order_money) as money,
				COUNT( DISTINCT order_roleid) as controle,
				'{$interval}' as entry,user_sid FROM  ".$tables."
				WHERE order_time BETWEEN UNIX_TIMESTAMP('{$statime}') 
				AND UNIX_TIMESTAMP('{$endtime}')
				AND order_state = 2 AND user_sid = $usersid  
				AND order_money = $Geometric";
			} 
			__log_message("pay get Geometric interval".$sql);		 
			if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
			 {	 			 		
		            $rows =$obj->dbh->fetch_all();           
		            return $rows;
		     }	
		}
		//----------------------------------------------------------------------------------------------------------
		if(empty($MaxInterval) && empty($Geometric))
		{
			$interval = $First.'至'.$second;
			if ($payconn === 0)
			{
				$sql ="
				SELECT SUM(onerecharge)as money,
				COUNT(DISTINCT roleid) as controle,
				'{$interval}' as entry,worldid as user_sid
				FROM {$dbname}.tb_recharge 
				where DATE(rechargetime)>=DATE('{$statime}')
				AND DATE(rechargetime) <=DATE('{$endtime}')
				AND worldid = $usersid
				AND onerecharge >= $First AND onerecharge<=$second
				";
			}else{
				$sql ="SELECT SUM(order_money) as money,
				COUNT( DISTINCT order_roleid) as controle,
				'{$interval}' as entry,user_sid FROM  ".$tables."
				WHERE order_time BETWEEN UNIX_TIMESTAMP('{$statime}') 
				AND UNIX_TIMESTAMP('{$endtime}')
				AND order_state = 2 AND user_sid = $usersid  
				AND order_money >=  $First  AND order_money <=$second";
			}		
			__log_message("pay get payroleinfopayroleinfopayroleinfo".$sql);		 
			if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
			 {	 			 		
		            $rows =$obj->dbh->fetch_all();           
		            return $rows;
		     }
		}		
	}
	/* -------------------------------------------------------------------------------------------------------------- */
	// 充值排行
	public static function recharge_ranking($platform,$dbName,$statime,$endtime,$sid)
	{
		$obj = new Statdata_Model($platform);
		$sql ="SELECT MAX(b.sumrecharge/10) as sumrecharge,b.onerecharge,COUNT(b.roleid) as cont,
		b.rechargetime,b.roleid,b.rechargeplatform,b.worldid FROM (
		SELECT a.sumrecharge,a.onerecharge,a.rechargetime,a.roleid,a.rechargeplatform,a.worldid 
		FROM {$dbName}.tb_recharge as a  
		WHERE a.worldid = $sid
		AND  DATE(a.rechargetime)>=DATE('{$statime}') AND DATE(a.rechargetime)<=DATE('{$endtime}')
		ORDER BY a.rechargetime desc
		)as b GROUP BY b.roleid ORDER BY b.sumrecharge desc";
		__log_message("recharge_ranking".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	          $rows =$obj->dbh->fetch_all();           
	          return $rows;
	     }		
	}
	// (最后一次登录时间)角色信息
	public static function online_info($platform,$dbName,$statime,$endtime,$sid)
	{
		$obj = new Statdata_Model($platform);
		$sql ="SELECT * FROM (
		SELECT b.worldid,b.roleid,b.date, b.coin,b.cashs,b.NickName,b.vip,b.ilevel 
		FROM {$dbName}.login_table as b 
		WHERE b.worldid = $sid
		AND  DATE(b.date)>=DATE('{$statime}') AND DATE(b.date)<=DATE('{$endtime}')
		ORDER BY b.date  desc 
		)as a GROUP BY a.roleid";
		//__log_message("online_info".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	          $rows =$obj->dbh->fetch_all();           
	          return $rows;
	     }		
	}
	//总充值
	public static function total_recharge($platform,$dbName,$sid)
	{
		$obj = new Statdata_Model($platform);
		$sql ="SELECT SUM(onerecharge) as money,worldid FROM ".$dbName."
		WHERE worldid = $sid";
		//__log_message("total_recharge".$sql);			 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	          $rows =$obj->dbh->fetch_row();           
	          return $rows;
	     }		
	} 
	// ===============================================================================
	// 等级滞留
	public static function Stat_grade_remain($platform,$dbName,$sid,$startTime,$endTime,
	$statTime,$daynumber="")
	{	
		$sql = "";		
		$obj = new Statdata_Model($platform);
		if(empty($startTime) || empty($endTime))
		{	// ALL SERVER
			$Where = "
			WHERE  a.iworld = {$sid}
			AND TO_DAYS(NOW()) - TO_DAYS(a.datetime)!=0";	
			$loginData ="AND TO_DAYS(NOW())- TO_DAYS(b.date)!=0";
			$statTime = date('Y-m-d h:i:s',time());
			
		}else{ 
			$Where = "
			WHERE a.datetime>='{$startTime}' 
			AND a.datetime<='{$endTime}'
			AND a.iworld = {$sid}";
			
			$loginData = "
			AND b.date >= '{$startTime}' 
			AND b.date <= '{$endTime}'
			";
		}		
			/*AND b.date >= '{$startTime}' 
					AND b.date <= '{$endTime}'*/
		if(!empty($daynumber))
		{		
			$sql ="SELECT  a.worldid,a.daynumber,a.ilevel,COUNT(*) as cont FROM(
				SELECT a.worldid,a.roleid,to_days('{$statTime}')-to_days(a.date) as daynumber,
				a.ilevel FROM
				( 
					SELECT b.worldid,b.roleid,b.date,b.ilevel 
					FROM {$dbName}.login_table as b 
					WHERE b.worldid ={$sid}  
					AND roleid in 
					(
						SELECT a.role FROM {$dbName}.role_table as a
						{$Where}
					)
					{$loginData}
					ORDER BY b.date desc
				) as a GROUP BY a.roleid HAVING daynumber >={$daynumber}
				) as a GROUP BY a.ilevel";
			//__log_message("Stat_grade_remain111111".$sql);
		}
		else
		{
			$sql ="SELECT  a.worldid,a.daynumber,a.ilevel,COUNT(*) as cont FROM(
				SELECT a.worldid,a.roleid,to_days('{$statTime}')-to_days(a.date) as daynumber,
				a.ilevel FROM
				( 
					SELECT b.worldid,b.roleid,b.date,b.ilevel 
					FROM {$dbName}.login_table as b 
					WHERE b.worldid ={$sid}  
					AND roleid in 
					(
						SELECT a.role FROM {$dbName}.role_table as a
						{$Where}
					)
					{$loginData}
					ORDER BY b.date desc
				) as a GROUP BY a.roleid 
				) as a GROUP BY a.ilevel";
			//__log_message("Stat_grade_remain222222".$sql);
		}	 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {	 			 		
	          $rows =$obj->dbh->fetch_all();           
	          return $rows;
	     }		
	}	
	/**
	 * 货币滞留率
	 * */
	# 活跃详情 对日期进行获取单个的信息 HAVING creatime = '{$statTime}'";
	public static function Stat_custom_online($conn,$dbName,
	$sid,$startTime,$endTime,$remainType)
	{	
		$obj = new Statdata_Model($conn);
		
		$sql ="
		SELECT roleid ,DATE(date) as creatime 
		FROM {$dbName}.login_table  
		WHERE  worldid = $sid
		AND DATE(date)  
		BETWEEN DATE('{$startTime}') 
		AND  DATE('{$endTime}')
		GROUP BY  DATE(date),roleid ORDER BY NULL 
		"; 
		//HAVING creatime = '{$remainType}'
		//__log_message("Stat_custom_online".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	# 货币滞留 根据不同类型获取产出及消耗
	public static function Stat_GameCurrencyInfo($conn,$dbName,
	$sid,$logtype,$strRoleID,$remainType)
	{
		$obj = new Statdata_Model($conn);
		$sql ="SELECT SUM(b.count) as cont,'{$remainType}' as creatime
		FROM {$dbName}.cach_table as b 
		WHERE b.logtype = $logtype
		AND b.worldid =$sid
		AND DATE(b.date) = DATE('{$remainType}')
		AND b.roleid IN 
		({$strRoleID})";
		__log_message("Stat_GameCurrencyInfo".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		}	
	}
		
	#货币滞留率 
	public static function Stat_monery_remain($conn,$dbName,
	$sid,$logtype,$strRoleID,$remainType){
		$obj = new Statdata_Model($conn);
		$sql ="
		SELECT SUM(b.totalnum)as cont,'{$remainType}' as creatime FROM 
		(
			SELECT a.totalnum FROM 
			(
			SELECT  b.totalnum,b.roleid FROM {$dbName}.cach_table as b 
			WHERE b.logtype = $logtype
			AND b.worldid =$sid
			AND DATE(b.date) = DATE('{$remainType}')
			AND b.roleid IN ({$strRoleID})
			ORDER BY DATE(date)
			) as a GROUP BY a.roleid
		)as b ";
		//__log_message("Stat_monery_remain".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		}	
	}
	/*
	 * 分 时注册统计（实施数据统计展示图标）
	 * */
	public static function Stat_graph_registe($conn,$dbName,$sid){
		$obj = new Statdata_Model($conn);
		$sql ="
		 SELECT DATE_FORMAT(a.datetime,'%Y-%m-%d') as creatime,a.iworld,COUNT(*) as cont 
		 FROM {$dbName}.role_table as a 
		 WHERE a.iworld = $sid
		 GROUP BY DATE_FORMAT(a.datetime,'%Y-%m-%d')";
		// __log_message("Stat_graph_registe".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
 
 
 
	// dau 合计
	public static function Stat_DauTotal($conn,$dbName,$sid,$TimeInterval = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT DATE_FORMAT(a.creatime,'%Y-%m')as creatime,
		a.role/a.rolecont as avgRolecount,contTime  FROM
		(
			SELECT a.roleid ,DATE(a.date) creatime,COUNT(a.roleid) as role,
			COUNT(DISTINCT a.roleid) as rolecont,a.date,SUM(a.iOnLineTime) as contTime 
			FROM(
			SELECT a.roleid,a.date,a.iOnLineTime  
			FROM {$dbName}.login_table as a 
			WHERE a.worldid = $sid
			{$TimeInterval}
			ORDER BY a.date desc 
			) as a  
		) as a";
		//__log_message("Stat_DauTotal".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	// 新玩家合计
	public static function Stat_NewPlayersTotal($conn,$dbName,$sid,
	$TimeInterval = "",$RolrTime = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT (RoleNum/rolecont) as avgrole,(maxOline/rolecont) as avgonlie FROM
		(
			SELECT SUM(a.usernumber) as RoleNum,SUM(roleCont) as rolecont
			,SUM(a.MaxolineTime) as maxOline FROM (
			SELECT COUNT(*) as usernumber,COUNT(DISTINCT a.roleid) as roleCont,
			a.date,MAX(a.iOnLineTime ) as MaxolineTime
				FROM {$dbName}.login_table as a 
				WHERE a.worldid = $sid
				AND  a.roleid in 
				(
					SELECT a.role  FROM {$dbName}.role_table as a 
					WHERE a.iworld = $sid 
					{$RolrTime}
				)
				{$TimeInterval}
				GROUP BY a.roleid
			)as a 
		)as b ";
		//__log_message("stat_overall_login".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	// 老玩家合计
	public static function Stat_OldPlayersTotal($conn,$dbName,
	$sid,$TimeInterval = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT (RoleNum/rolecont) as avgrole,(maxOline/rolecont) as avgonlie FROM
		(
			SELECT SUM(a.usernumber) as RoleNum,SUM(roleCont) as rolecont,
			SUM(a.MaxolineTime) as maxOline FROM (
			SELECT COUNT(*) as usernumber,COUNT(DISTINCT a.roleid) as roleCont,
			a.date,MAX(a.iOnLineTime ) as MaxolineTime
				FROM {$dbName}.login_table as a 
				WHERE a.worldid = $sid
				AND  a.roleid in 
				(
					SELECT n.roleid  FROM(
					  SELECT b.roleid,COUNT(*) rolecont FROM
					  (
						SELECT a.roleid ,a.create_time  FROM
						(
							SELECT DATE(a.date) as create_time, a.roleid
							FROM {$dbName}.login_table  as a   
							WHERE a.worldid = $sid
							{$TimeInterval}
							GROUP BY roleid,DATE(a.date)
						)as a  
					  ) as b GROUP BY b.roleid HAVING rolecont >1
					)as n
				)
				{$TimeInterval}
				GROUP BY a.roleid
			)as a 
		)	 as b ";
		//__log_message("stat_overall_login".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	// R 合计
	public static function Stat_R_total($conn,$dbName,$sid,
	$TimeInterval= "",$Whether="")
	{
		
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT a.create_time,COUNT(DISTINCT a.roleid) as cont FROM
		(
			SELECT a.roleid,DATE(a.date) as create_time 
			FROM {$dbName}.login_table as a 
			WHERE a.worldid = $sid
			{$TimeInterval}
			AND a.roleid {$Whether} in 
			(
				SELECT b.roleid FROM {$dbName}.tb_recharge as b 
				WHERE b.worldid = $sid 
			)
		) as a  ";
		//__log_message("stat_overall_login".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	} 
	// 总体在线数据统计 pcu数据（时间区间、周、日）
	public static  function StatdataOverallOnlinePcu($conn,$dbName,
	$sid,$TimeInterval = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT MAX(a.OnlineRoleNum) as RoleNum,DATE(FROM_UNIXTIME(MAX(a.RecordTime))) as RecordTime
		FROM {$dbName}.t_qmonster_worldonline as a 
		WHERE a.WorldID = $sid
		AND a.OnlineRoleNum > 0
		{$TimeInterval} 
		GROUP BY DATE(FROM_UNIXTIME(a.RecordTime))";
		//__log_message("StatdataOverallOnlinePcuMAX".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	
	//Total
	public static  function StatdataOverallOnlinecont($conn,$dbName,
	$sid,$TimeInterval = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT sum(a.OnlineRoleNum) as RoleNum,DATE(FROM_UNIXTIME(MAX(a.RecordTime))) as RecordTime
		FROM {$dbName}.t_qmonster_worldonline as a 
		WHERE a.WorldID = $sid
		AND a.OnlineRoleNum > 0
		{$TimeInterval} 
		GROUP BY DATE(FROM_UNIXTIME(a.RecordTime))";
		//__log_message("StatdataOverallOnlinePcu".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		}	
	}
	/*
	 * PCU ACU Total
	 * */
	public static  function StatdataOnlineTotal($conn,$dbName,
	$sid,$TimeInterval = "")
	{
		$obj = new Statdata_Model($conn);		 
		$sql ="
		SELECT a.maxOnline,a.sumonline/a.maxOnline as avgonline,
		a.RecordTime FROM(
			SELECT  MAX(a.OnlineRoleNum) as maxOnline,
			DATE(FROM_UNIXTIME(a.RecordTime)) as RecordTime,
		  SUM(a.OnlineRoleNum) as sumonline
			FROM {$dbName}.t_qmonster_worldonline as a 
			WHERE a.WorldID = $sid
			AND a.OnlineRoleNum > 0
			{$TimeInterval} 
			GROUP BY DATE(FROM_UNIXTIME(a.RecordTime))  
		) as a ORDER BY a.maxOnline DESC LIMIT 1";	
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		}	
	}
	// 测试分页总量
	public static function test_pagingTotal($conn,$data){
		/*$obj = new Statdata_Model($conn);
		if (empty($data)){
			$sql = "select count(*) as cont from globaldb.app_stroe as a ";
		}else{
			$sql = "select count(*) as cont from globaldb.app_stroe as a 
			where a.money  like '{$data}'";
		}
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		}	*/
	}
	// 测试分页
	public static function getChat($conn,$data,$page,$pagesize)
	{
        /*$obj = new Statdata_Model($conn);
        
        $limit = '';
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;
            
        } 
		if(empty($data))
		{
			$sql = 'SELECT * FROM globaldb.app_stroe order by id desc ' . $limit;
		}  else {
			$sql = 'SELECT * FROM globaldb.app_stroe'. " where money  like '%".$data."%'  
			order by id desc " .$limit;
		} 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}    */
	}
	//通过uin  获得角色ID 和区服ID
	public static function  get_uin_info($conn,$dbName,
	$uin){
		$obj = new Statdata_Model($conn);
		$sql = "SELECT  a.role,a.iworld  FROM {$dbName}.role_table as a 
		WHERE a.uin  = $uin ";
		//__log_message("get uin Info".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_all();           
		   return $rows;
		} 
	}
	# 玩家搜索
	public static function user_serachTotal($conn,$data,$dbName,
	$sid)
	{
		$obj = new Statdata_Model($conn);
		
		if (empty($data)){
			$sql = "CALL globaldb.PROC_STAT_PAGING('userSerach','total',
		'kingnet_ios','{$sid}','','')";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('userSerach','total',
			'kingnet_ios','{$sid}','','{$data}')";
		}
		__log_message("sqluser_serachTotal::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	# 玩家搜索
	public static function user_serach($conn,$data,$page,$pagesize,$dbName,
	$sid)
	{
		$obj = new Statdata_Model($conn);
		$limit = '';
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('userSerach','TotalPaging',
			'{$dbName}','{$sid}','{$limit}','')";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('userSerach','TotalPaging',
			'{$dbName}','{$sid}','{$limit}','{$data}')";
		} 
		__log_message("clla user_serach::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	public static function Stat_sumrecharge($conn,$dbName,
	$sid)
	{	 
		$obj = new Statdata_Model($conn);
		$sql = "
		SELECT d.roleid,d.sumrecharge FROM ( 
			SELECT a.worldid,a.roleid,a.sumrecharge
			FROM {$dbName}.tb_recharge as a 
			{$sid} 
			ORDER BY a.rechargetime DESC 
		) as d GROUP BY  d.roleid 
		";
		//__log_message("clla Stat_sumrecharge::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	# 货币流水
	public static function Stat_moneyFlowTotal($conn,$data,$dbName,
	$sid)
	{
		$obj = new Statdata_Model($conn);
		
		if (empty($data)){
			$sql = "CALL {$dbName}.PROC_STAT_PAGING('moneyflow','total',
		'{$dbName}','','','')";
		}else{
			$sql = "CALL {$dbName}.PROC_STAT_PAGING('moneyflow','total',
			'{$dbName}','','','{$data}')";
		} 
		//__log_message("call total ::::".$sql,'db');
		 if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	public static function Stat_moneyFlow($conn,$data,$page,$pagesize,$dbName,
	$sid)
	{	
		$obj = new Statdata_Model($conn);
		$limit = '';
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL {$dbName}.PROC_STAT_PAGING('moneyflow','TotalPaging',
			'{$dbName}','','{$limit}','')";
		}  
		else 
		{
			$sql = "CALL {$dbName}.PROC_STAT_PAGING('moneyflow','TotalPaging',
			'{$dbName}','','{$limit}','{$data}')";
		} 
		//__log_message("call total paging::::".$sql,'db');
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		} 
	}
	
	// 登录流水 
	public  static   function Stat_loginInfoTotal($conn,$data,$dbName,
	$sid){ 
		$obj = new Statdata_Model($conn);
		
		if (empty($data)){
			$sql = "CALL globaldb.PROC_STAT_PAGING('loginInfo','total',
		'{$dbName}',$sid,'','')";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('loginInfo','total',
			'{$dbName}',$sid,'','{$data}')";
		}
		//__log_message("sqllltotal::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	public  static  function Stat_loginInfo($conn,$data,$page,$pagesize,$dbName,
	$sid)
	{	
		$obj = new Statdata_Model($conn);
		$limit = '';
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('loginInfo','TotalPaging',
			'{$dbName}',$sid,'{$limit}','')";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('loginInfo','TotalPaging',
			'{$dbName}',$sid,'{$limit}','{$data}')";
		} 
		//__log_message("clla::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
 
	// CDK(总)
	public static function Stat_cdkcodeTotal($conn,$data,$dbName,$sid)
	{
		$obj = new Statdata_Model($conn);
		
		$dat = $sid.' '.$data;
		
		if (empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdk','total',
			'{$dbName}','','',\"{$dat}\")";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdk','total',
			'kingnet_ios','','',\"{$dat}\")";
		} 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	// CDK INFO
	public static function Stat_cdkcodeInfo($conn,$data,$dbName,
	$sid,$page="",$pagesize="")
	{
		$obj = new Statdata_Model($conn);
		$limit = '';
        
		$dat = $sid.' '.$data;
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdk','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cdk','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		} 
		__log_message("clla::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	
	// 货币消耗
	public static function Stat_money_data($conn,$data,$dbName,
	$sid)
	{
		//$regionStr = !empty($sid)?"and worldid = {$sid}":"";
		$obj = new Statdata_Model($conn);
		$sql ="SELECT worldid,SUM(a.count) as number,COUNT(a.roleid) as cont,
		DATE(a.date) as date 
		FROM {$dbName}.cach_table as a 
		where a.worldid =  $sid 
		{$data}";
		//__log_message("money_data:::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	} 

	// 账号 
	public function Stat_Account_Create($conn,$dbName,
	$startTime,$endTime,$type)
	{
		$obj = new Statdata_Model($conn);
		$sql ="
		SELECT COUNT(a.uin) as rolecont,DATE(a.datetime) as datetime 
		FROM globaldb.g_user_table as a 
		WHERE a.type = $type
		AND DATE(a.datetime) >=DATE('{$startTime}')
		AND DATE(a.datetime) <=DATE('{$endTime}')
		GROUP BY DATE(a.datetime)";
		//__log_message("Stat_Account_Create".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	
	} 
		// 账号 DAU
	public static  function Stat_Account_DAU($conn,$dbName,
	$startTime,$endTime)
	{
		$obj = new Statdata_Model($conn);
		$startTime=date("Y-m-d",strtotime($startTime));
		$endTime=date("Y-m-d",strtotime($endTime));
		$sql ="
		SELECT COUNT(DISTINCT a.uin) as cont,
		DATE(a.datetime) as datetime
		FROM {$dbName}.user_table as a 
		WHERE  DATE(datetime)>='{$startTime}'
		AND DATE(datetime)<='{$endTime}'
		GROUP BY DATE(a.datetime)";
		//__log_message("dauconnnttt".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	
	} 
	//账号留存 
	public function  Stat_Account_Retained($conn,$dbName,
	$startTime,$nextTime,$type,$status)
	{
		$obj = new Statdata_Model($conn);
		if($status === 1)
		{
		 
		$sql = "
		SELECT SUM(b.order_money)+0 as cont,date('{$startTime}') as datetime 
		FROM globaldb.g_user_table as a ,globaldb.rechargedata as b 
		WHERE a.type = 4
		AND b.order_state = 2 
		AND a.uin = b.uin
		AND TO_DAYS(a.datetime) = TO_DAYS('{$startTime}')
		AND TO_DAYS(FROM_UNIXTIME(b.order_time)) =  TO_DAYS('{$startTime}')+$nextTime
		";
		}else{
		$sql =" 
		SELECT COUNT(uin)+0 as cont,DATE('{$startTime}') as datetime 
		FROM globaldb.g_user_table as a 
		WHERE a.type = $type
		AND TO_DAYS(a.datetime) = TO_DAYS('{$startTime}')
		AND a.uin in 
		( 
		  SELECT DISTINCT uin FROM {$dbName}.user_table
		  WHERE  uin>0
		  AND TO_DAYS(datetime) = TO_DAYS('{$startTime}')+$nextTime
		)";
		}
		__log_message("Stat_Account_Retained".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	// 区服留存
		/*SELECT a.worldid,COUNT(DISTINCT a.roleid),DATE(a.date) FROM kingnet_ios.login_table as a 
	WHERE   DATE(a.date) >= '2015-10-11'
	AND a.roleid in 
	(
			SELECT b.role FROM kingnet_ios.role_table as b
		  WHERE  DATE(b.datetime) = '2015-10-11'
	)GROUP BY a.worldid,DATE(a.date);*/
	public static function Stat_AllServer_Retained($conn,$dbName,
	$datetime,$endtime)
	{
		$obj = new Statdata_Model($conn);
		$sql = "SELECT a.server_id,COUNT(DISTINCT a.play_id)+0 as roleCont,
		DATE(a.login_in_time) as  datetime FROM {$dbName}.game_data_role_online as a 
		WHERE DATE(a.login_in_time) >= '{$datetime}'
		AND DATE(a.login_in_time) <= '{$endtime}'
		AND a.play_id in (
			SELECT b.player_id FROM {$dbName}.game_data_role as b
			WHERE  DATE(b.createtime) = '{$datetime}'
		)GROUP BY a.server_id,DATE(a.login_in_time)";
		
		__log_message("all retained sql ". $sql ,'db');
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	
	
	// 角色创建人数
	public function Stat_Role_Create($conn,$dbName,$sid,$startTime,$endTime)
	{
		$obj = new Statdata_Model($conn);
		
		$Judge =!empty($sid)
		?
		"WHERE iworld = $sid  
		AND DATE(a.datetime) >= DATE('{$startTime}')
		AND DATE(a.datetime) <= DATE('{$endTime}')"
		:
		"WHERE iworld >=1 
		AND DATE(a.datetime) >= DATE('{$startTime}')
		AND DATE(a.datetime) <= DATE('{$endTime}')
		";
		
		$sql ="
		SELECT DATE(a.datetime) as datetime,
		COUNT(a.role) as rolecont FROM {$dbName}.role_table as a 
		{$Judge}
		GROUP BY DATE(a.datetime)";
		 
		//__log_message("Stat_Role_Create".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	
	}
	//角色留存人数
	public function  Stat_Role_Retained($conn,$dbName,$sid,$startTime,$nextTime)
	{
		$obj = new Statdata_Model($conn);
		
		$Judge =!empty($sid)
		?"WHERE iworld = $sid  AND DATE(a.datetime) = DATE('{$startTime}')"
		:"WHERE iworld >=1 AND DATE(a.datetime) = DATE('{$startTime}')";
		
		$nextJudge = !empty($sid)
		?
		 "WHERE worldid = $sid
		  AND  TO_DAYS('$startTime')+$nextTime = TO_DAYS(a.date)"
		:"WHERE worldid>=1 AND TO_DAYS('$startTime')+$nextTime = TO_DAYS(a.date)";
		
		$sql ="
		SELECT COUNT(1)+0 as cont,DATE('{$startTime}') as datetime  
		FROM {$dbName}.role_table as a 
		{$Judge}
		AND a.role in (
		SELECT a.roleid from {$dbName}.login_table as a 
		{$nextJudge})";
		//__log_message("Stat_Role_Retained".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}

	public static function Stat_accountInfo($conn,$type,$dbName,$tims)
	{
		$obj = new Statdata_Model($conn); 
		$sql ="
		SELECT COUNT(*)+0 as cnt,DATE('{$tims}')  as datetime FROM globaldb.g_user_table as a  
		WHERE a.type = $type
		AND  TO_DAYS(a.datetime) = TO_DAYS('{$tims}')
		AND a.uin not in ( 
		SELECT uin FROM $dbName.role_table   AS a 
		WHERE TO_DAYS(a.datetime) = TO_DAYS('{$tims}')
		)";
		__log_message("account:::cont".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  	
	}
	/*
	 * 铭刻信息分布
	 * 
	public static function Stat_posy_annal($conn,$type,$dbName,$sid,
	$startTime,$endTime,$exctype)
	{
		$obj = new Statdata_Model($conn); 
		$sql ="SELECT a.exctype,a.date,a.worldid,COUNT(*) as frequency,
		SUM(count) as monetary,
		COUNT( DISTINCT a.roleid) as role_cont FROM {$dbName}.cach_table as a 
		WHERE a.exctype in ({$exctype})
		AND a.worldid in ({$sid})
		AND DATE(a.date)>=DATE('{$startTime}')
		AND   DATE(a.date)<=DATE('{$endTime}')
		GROUP BY a.exctype,a.worldid,DATE(a.date);
		";
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}*/
	// cach(总)
	public static function Stat_cachTotal($conn,$data,$dbName,$sid)
	{
		$obj = new Statdata_Model($conn);
		
		$dat =  $data;
		
		if (empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cach','total',
			'{$dbName}','','',\"{$dat}\")";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cach','total',
			'{$dbName}','','',\"{$dat}\")";
		} 
		//__log_message("sqlll::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	// cach INFO
	public static function Stat_cachInfo($conn,$data,$dbName,
	$sid,$page="",$pagesize="")
	{
		$obj = new Statdata_Model($conn);
		$limit = '';
        
		$dat =  $data;
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cach','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('cach','TotalPaging',
			'{$dbName}','','{$limit}',\"{$dat}\")";
		}  
		__log_message("clla::::".$sql);
		__log_message("LIMIT::::".$limit);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	/**
	 * 邮箱记录总数
	 **/
	public static  function Stat_mail_recording_Total($conn,$tables,$Term)
	{
	
		$obj = new Statdata_Model($conn);	 
		$sql ="select count(1)  as cont FROM {$tables} as a 
		{$Term}";		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_row();           
			return $rows;
		}   
	}
	/**
	 * 邮箱记录
	 **/
	public static  function Stat_mail_recording($conn,$tables,$Term,
	$page="",$pagesize="")
	{
		$obj = new Statdata_Model($conn);
		$limit = '';
        
		$dat =  $data;
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        }  
		$sql ="
		select recvername,senddatetime,readtag,readdatetime,title,content 
		FROM {$tables} as a  {$Term}   {$limit}";
		__log_message("account:::cont".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}   
	}
	/**
	 * 公会信息
	 **/
	public static  function Stat_guild_Info($conn,$tables,$Term)
	{
		$obj = new Statdata_Model($conn);
		 
		$sql ="
		select id,strName,guildmember,strPresidentName,iPresidentRoleId,
		guildmemberamt,iActiveVal,strNotice,strDeclare 
		FROM {$tables} {$Term}";
		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}   
	}
	/**
	 * LTV值	 
	 **/
	public static function Stat_ltv($conn,$dbName,$sid,$datetime,$endtime)
	{
		$obj = new Statdata_Model($conn);
		 
		$sql ="
		 
		SELECT a.worldid,a.onerecharge,
		DATE(a.rechargetime) as datetime FROM (
			SELECT a.worldid,SUM(a.onerecharge) as onerecharge,a.rechargetime 
			FROM {$dbName}.tb_recharge as a 
			WHERE a.worldid = $sid
			AND DATE(a.rechargetime) >= DATE('{$datetime}')
			AND DATE(a.rechargetime) <= DATE('{$endtime}')
			AND a.roleid in 
			(
			  SELECT b.role FROM {$dbName}.role_table as  b 
			  WHERE b.iworld = $sid
			  AND DATE(b.datetime) =DATE('{$datetime}')
			)GROUP BY DATE(a.rechargetime)
		)as a ";
		__log_message("LTV".$sql);		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}   
	} 
	//实时数据
	public static function Stat_real_time($conn,$platfrom)
	{
		$obj = new Statdata_Model($conn);
		 
		$sql ="SELECT * FROM globaldb.g_real_time as a WHERE a.platfrom ={$platfrom}";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}   
	
	}
	// data 搜索@2 is  loginglog and two usesearch total
	public  static   function Stat_user_searchTotal($inmode,$conn,$data,$dbName,$sid = ''){ 
		$obj = new Statdata_Model($conn);		
		if (empty($data)){
			$sql = "CALL globaldb.PROC_STAT_PAGING('{$inmode}','total',
		'{$dbName}','{$sid}','','')";
		}else{
			$sql = "CALL globaldb.PROC_STAT_PAGING('{$inmode}','total',
			'{$dbName}','{$sid}','','{$data}')";
		} 
		__log_message(" sql log ::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
		   $rows =$obj->dbh->fetch_row();           
		   return $rows;
		} 
	}
	//  data 搜索@2 loginglog and two usesearch info 
	public  static  function Stat_user_searchInfo($inmode,$conn,$data,$page,
	$pagesize,$dbName,$sid = '')
	{	
		
		$obj = new Statdata_Model($conn);
		
		$limit = '';
        
        if(!empty($page) && !empty($pagesize))
        {
            $offset = ($page-1) * $pagesize;
            
            $limit  = ' LIMIT '.$offset.','.$pagesize;            
        } 
		if(empty($data))
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('{$inmode}','TotalPaging',
			'{$dbName}','{$sid}','{$limit}','')";
		}  
		else 
		{
			$sql = "CALL globaldb.PROC_STAT_PAGING('{$inmode}','TotalPaging',
			'{$dbName}','{$sid}','{$limit}','{$data}')";
		}  
		__log_message(" sql info ::::".$sql);
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{	 			 		
			$rows =$obj->dbh->fetch_all();           
			return $rows;
		}  
	}
	
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
	public  static function Stat_fruit_chart_daily($conn)
	{		
		$obj = new Statdata_Model($conn);
		 
		$sql = "SELECT date,COUNT( user_id) as cont FROM user_order 
		WHERE `status` = 1
		AND date >=20160914 AND date<=20160921
		GROUP BY date";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	/**
	 * ----------------------------------------------------------
	 * ----------------------------------------------------------
	 */
	public static function stat_chart_daily_online($conn,$dbname,$sid = null){
		
		//$sid = empty($sid)?1:$sid;
		
		$obj = new Statdata_Model($conn);
		// DAY 日  MONTH 月	
		$sql = "SELECT COUNT(DISTINCT play_id) as cont,
		DATE(login_in_time) as createtime 
		FROM {$dbname}.game_data_role_online 
		WHERE server_id = $sid
		AND  DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(login_in_time)
		GROUP BY  DATE(login_in_time)";
		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
 	/**
 	 * 日常数据
 	 * **/
	public static function stat_chart_daily_registered($conn,$dbname,$sid = null ){
		$sid = empty($sid)?1:$sid;
		$obj = new Statdata_Model($conn);
		// DAY 日  MONTH 月
		$sql = "SELECT date(createtime) as createtime,COUNT(player_id) as cont 
		FROM {$dbname}.game_data_role 
		WHERE server_id = $sid
		AND  DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(createtime)
		GROUP BY  DATE(createtime)";
			
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	// new pay
	public static function Stat_new_added_pay($conn,$dbname,$sid){
	
		$sid = empty($sid)?1:$sid;
		
		$obj = new Statdata_Model($conn);
		
		$sql ="SELECT DATE(a.createtime) as datetime,a.serverId,
		a.newPayFrequency,a.newPaymoney,a.newPayRoleNum
		FROM {$dbname}.game_new_added_pay as a
		WHERE a.serverId = $sid
		AND  DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date(a.createtime)
		GROUP BY DATE(createtime)";
		__log_message('Stat_new_added_pay::'.$sql,'stat-log');
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
		return false;
	}
	public static function Stat_level_info($conn,$dbname,$data)
	{
		$obj = new Statdata_Model($conn);
		$sql = "SELECT DATE(a.createtime) as createtime,a.`level`,
		a.server_id,COUNT(a.player_id) as cont FROM {$dbname}.game_data_role as a 
		 {$data}
		GROUP BY a.server_id,a.`level`"; 
		 if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		 {
		 	$rows =$obj->dbh->fetch_all();
		 	return $rows;
		 }
	}
	/**
	 * 当前在线总量
	 ***/
	public static function Stat_Currently_online_total($conn,$dbname,$data=NULL)
	{
		$data = 'WHERE a.onlinetype = 1
		GROUP BY a.server_id';
		$sid = null;
		$obj = new Statdata_Model($conn);
		  
		$sql = "CALL {$dbname}.PROC_STAT_PAGING('currently_online','total',
		'{$dbname}','{$sid}','','{$data}')";
		//__log_message("stat online total ".$sql,'db');
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_row();
			return $rows;
		}
		
	}
	/**
	 * 当前在线信息
	 ***/
	public static function Stat_Currently_online_info($conn,$page,
	$pagesize,$dbname,$sid =NULL)
	{
		$data = 'WHERE a.onlinetype = 1
		GROUP BY a.server_id';
		$sid = null;
		$obj = new Statdata_Model($conn);
		$limit = '';
		
		if(!empty($page) && !empty($pagesize))
		{
			$offset = ($page-1) * $pagesize;
		
			$limit  = ' LIMIT '.$offset.','.$pagesize;
		} 
		
		$sql = "CALL {$dbname}.PROC_STAT_PAGING('currently_online','TotalPaging',
		'{$dbname}','{$sid}','{$limit}','{$data}')";
		//__log_message("stat online info ".$sql,'db');
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		} 
	}
	/**
	 * 当前在线信息
	 ***/
	public static function Stat_Currently_online_info2($conn,$dbname,$sid =NULL)
	{
		$data = 'WHERE a.onlinetype = 1
		GROUP BY a.server_id';
		$sid = null;
		$obj = new Statdata_Model($conn);
		$limit = NULL;
	  
		$sql = "CALL {$dbname}.PROC_STAT_PAGING('currently_online','TotalPaging',
		'{$dbname}','{$sid}','{$limit}','{$data}')";
		//__log_message("stat online info ".$sql,'db');
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		} 
	}
	/**
	最高在线 stat_highest_online
	**/
	public static function Stat_highest_online(
	$conn,$dbName,$startTime,$endTime){
		
		$obj = new Statdata_Model($conn);
		
		$sql = "SELECT a.server_id,a.date,a.time,a.num 
		FROM {$dbName}.game_data_top_online_num  as a 
		WHERE a.date >= DATE('{$startTime}')
		AND a.date<=DATE('{$endTime}')
		GROUP BY a.server_id,a.date";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}		
	}
	
	/**
	 *最高在线时长 
	 ***/
	public static function Stat_role_Online_Time($conn,$dbName,
	$serverId =NULL,$onlineTimeNum,$onlineTimeNum2,$startTime,$endTime)
	{
		$obj = new Statdata_Model($conn);
		
		$serverId = empty($serverId)?NULL:" a.server_id =$serverId";
		
		$sql = "SELECT a.server_id,COUNT(1) as cont  
		FROM {$dbName}.game_data_role as a 
		WHERE  
		{$serverId}
		AND DATE(a.createtime)>='{$startTime}'
		AND DATE(a.createtime)<='{$endTime}'
		AND a.onlinetime >=$onlineTimeNum 
		AND a.onlinetime <=$onlineTimeNum2
		GROUP BY a.server_id";
		
		//__log_message("Stat_role_Online_Time".$sql,'Stat_role_Online_Time');
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		} 
	}	
	///
	public static function Stat_User_Mail_Annal($conn,$data =NULL)
	{
		$obj = new Statdata_Model($conn);
		
		$sql = "SELECT * FROM globaldb.tb_role_mail_log as a ,
		minosdb.game_server_email as b
		WHERE a.mail_id_php = b.id   ".$data ;
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	
	// 当前实时在线每5分钟数据	
	public static function Stat_Currently_Online($conn,$dbname,
	$serverId =NULL,$starttime=NULL,$Status=FALSE)
	{
		$obj = new Statdata_Model($conn);
		
		$sql ="SELECT online_nums,createtime FROM {$dbname}.game_data_realtime as a
		WHERE  server_id =$serverId
		AND TO_DAYS(NOW()) - TO_DAYS(createtime)=0";
		
		if ($Status==TRUE)
		{
			$sql ="SELECT online_nums,createtime FROM {$dbname}.game_data_realtime as a 
			WHERE  server_id =$serverId
			AND DATE(createtime)>=DATE('{$starttime}')";			
		}
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	// 充值
	public static function Stat_pay_number($platId,$sid)
	{
		$conn = Platfrom_Service::getServer(true,'globaldb');
		
		$obj = new Statdata_Model($conn);
		
		$sql ="
		SELECT SUM(fee) as fee,COUNT(DISTINCT RoleIndex) 
		as rolecont,DATE(createtime) as datetime,count(*) as frequency
		FROM globaldb.tb_recharge
		WHERE platId = {$platId} AND  server_id = $sid 
		AND `status` in(2,4)
		AND  DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date(createtime)
		GROUP BY DATE(createtime)";
		 
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}		
	}
	
	/*
	 实时统计	设备激活	实时统计当日激活的设备数量，如果已安装的游戏激活标识被移除的话，则设备激活不会被去重。
	//实时统计	新增玩家	实时统计当日新增玩家账号的数量。
	实时统计	老玩家	实时统计当日有登陆过游戏的老玩家的账号数量。
	//实时统计	总活跃	实时统计当日所有有进行过登陆行为的玩家账号数量。
	//实时统计	付费玩家	实时统计当日有进行成功付费的玩家账号数量。
	//实时统计	今日收入	实时统计当日游戏总收入金额。
	//实时统计	付费次数	实时统计当日玩家有成功进行付费的总次数。
	//实时统计	累积收入	实时统计截至当时为止游戏的总收入金额。
	//小时统计	游戏次数	实时统计当日玩家所进行的游戏总次数。
	//小时统计	平均每次游戏时长	实时统计当日玩家平均每次进行游戏的时间。 当前总游戏时长  / 当前总登录人数	
	 */
	// 对每一个小时分割的额注册人数时间段 还有老玩家人数对应时间段
	public function Stat_real_time_registered($conn,$serverId,$endtime,$stratime)
	{
		$obj = new Statdata_Model($conn);
		 
		$having = empty($serverId) 
		? 
		NULL : ' HAVING a.server_id = '.$serverId;
		$sql ="
		SELECT b.server_id,b.cont,
		CONCAT(b.datetime,':00:00') as datetime,
		DATE(b.datetime) as createtime,b.onlinedays FROM 
		(
		SELECT a.server_id,COUNT(a.player_id) as cont ,
		DATE_FORMAT(a.createtime,'%Y-%m-%d %H') as datetime,
		SUM((CASE WHEN a.onlinedays>='2' THEN '1'  ELSE '0' END))as onlinedays
		FROM game_data_role as a 
		WHERE a.server_id = $serverId
		AND DATE(a.createtime)>=DATE('{$endtime}')
		AND DATE(a.createtime)<=DATE('{$stratime}')
		GROUP BY a.server_id ,DATE_FORMAT(a.createtime,'%Y-%m-%d %H')		 
		) as b ";
		__log_message('pay-log:'.$sql,"pay-log");
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	public function Stat_real_onlinedays($conn,$serverId,$stratime)
	{
		$obj = new Statdata_Model($conn);
		 
		$sql ="
		SELECT count(*) as onlineDays FROM game_data_role as a 
		WHERE server_id=$serverId
		AND DATE(createtime) = DATE('{$stratime}')
		AND onlinedays>=2;";
		
		//__log_message('pay-log:'.$sql,"pay-log");
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_row();
			return $rows;
		}
	}
	//  付费次数 付费人数 付费金额
	public function Stat_real_time_pay($serverId)
	{   
		$conn = Platfrom_Service::getServer(true,'globaldb');
		
		$obj = new Statdata_Model($conn);
		
		$sql = " SELECT SUM(fee) as Totalfee  
		FROM globaldb.tb_recharge
		WHERE `status` = 2 AND server_id ={$serverId}";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows = $obj->dbh->fetch_row();
			 
			return $rows;
		}
	}
	public function Stat_real_time_payinfo($serverId,$strattime)
	{
		$having = empty($serverId)
		?
		NULL : ' HAVING a.server_id = '.$serverId;
		
		$conn = Platfrom_Service::getServer(true,'globaldb');
		 
		$obj = new Statdata_Model($conn);
		
		$sql ="
		SELECT b.server_id,b.fee,b.frequency,b.rolenum,
		CONCAT(b.datetime,':00:00') as datetime,
		DATE(b.datetime) as createtime FROM 
		(
		SELECT a.server_id,SUM(a.fee) as fee ,COUNT(*) as frequency ,
		COUNT(DISTINCT RoleIndex) as rolenum,
		DATE_FORMAT(a.createtime,'%Y-%m-%d %H') as datetime
		FROM globaldb.tb_recharge  as a
		WHERE a.`status` = 2
		AND DATE(a.createtime)>=DATE('{$strattime}')
		GROUP BY a.server_id,DATE_FORMAT(a.createtime,'%Y-%m-%d %H')
		{$having}
		) as b";
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount()> 0)
		{
			$rows = $obj->dbh->fetch_all();
		
			return $rows;
		}
		
	}
	// 登录人数 登录次数  评价登录时长
	public function  Stat_real_time_Inlogin($conn,$serverId,$endtime,$stratime)
	{ 
		$having = empty($serverId) 
		? 
		NULL 
		: 
		' HAVING a.server_id = '.$serverId;
		  
		$obj = new Statdata_Model($conn);
		
		$sql ="
		SELECT a.server_id,DATE(a.login_in_time) as createtime,
		COUNT(DISTINCT a.play_id) as RoleNum,COUNT(*) as loginNum,
		ROUND( SUM(
		(UNIX_TIMESTAMP(a.login_out_time) - UNIX_TIMESTAMP(a.login_in_time))) / COUNT(DISTINCT a.play_id) ) as AvglogingTime
		FROM game_data_role_online as a 
		WHERE  a.server_id = $serverId
		AND DATE(a.login_in_time)>= DATE('{$endtime}') 
		AND DATE(a.login_in_time)<= DATE('{$stratime}')
		GROUP BY a.server_id,DATE(a.login_in_time)
		 ";
		//__log_message('pay-log:'.$sql,"pay-log");
		if($obj->dbh->query($sql) && $obj->dbh->rowcount() > 0)
		{
			$rows =$obj->dbh->fetch_all();
			return $rows;
		}
	}
	///////////////实时在线
	/*
	 5分钟间隔	实时在线	显示玩家今日和昨日的在线数据，单位为每5分钟显示一个
	一小时间隔	设备激活	显示玩家今日和昨日的在线数据，单位为每1小时显示1次
	一小时间隔	注册玩家	显示玩家今日和昨日的在线数据，单位为每1小时显示1次
	一小时间隔	收入金额	显示玩家今日和昨日的在线数据，单位为每1小时显示1次	 
	*/
	// rank
	public static function Stat_real_time_rank($conn,
	$mindiamond=NULL,$maxdiamond=NULL,$serverId = NULL)
	{
		$serverId = !empty($serverId) 
		? 
		" WHERE server_id = $serverId ":NULL;
		
		$obj = new Statdata_Model($conn);
		 
		$sql = "SELECT server_id,account,player_id,nick_name,
		vip_level,diamond,`level`,createtime FROM  game_data_role as  a 
		{$serverId} AND a.diamond BETWEEN {$mindiamond}  AND {$maxdiamond}  
		ORDER BY a.diamond DESC";	
		
		/* if (!empty($diamond))
		{
			$sql = "SELECT server_id,account,player_id,nick_name,
			vip_level,diamond,`level`,createtime FROM game_data_role
			WHERE diamond = $diamond
			{$serverId}  limit {$limit}";
		} */
		__log_message('rank-log: '.$sql,"rank-log");
		
		if($obj->dbh->query($sql) && $obj->dbh->rowcount()> 0)
		{
			$rows = $obj->dbh->fetch_all();
		
			return $rows;
		}		
	}
	// 获取最大排行范围
	public static function Stat_real_time_rank_range(
	$conn,$limit=NULL,$serverId=NULL)
	{
		$serverId = !empty($serverId)
		?
		" WHERE server_id = $serverId ":NULL;
		
		$obj = new Statdata_Model($conn);
			
		$sql = " SELECT MAX(B.diamond) as maxdiamond,
			MIN(B.diamond) as mindiamond FROM (
			SELECT COUNT(*),a.diamond FROM game_data_role as a 
			{$serverId}
			GROUP BY a.diamond  ORDER BY a.diamond DESC  LIMIT {$limit}
		) as B;	";
		
		__log_message('rank-log: '.$sql,"rank-log");
			
		if($obj->dbh->query($sql) && $obj->dbh->rowcount()> 0)
		{
			$rows = $obj->dbh->fetch_row();
		
			return $rows;
		}
		
	}
	// get daliy data
	public static function get_daliy_info($conn,$dbname){
		 
		$obj = new Statdata_Model($conn);
			
		$sql = " SELECT * FROM {$dbname}.game_daily_back 
		WHERE TO_DAYS(NOW()) - TO_DAYS(createtime) = 1";
		 
		if($obj->dbh->query($sql) && $obj->dbh->rowcount()> 0)
		{
			$rows = $obj->dbh->fetch_all();
		
			return $rows;
		}
	}
}

