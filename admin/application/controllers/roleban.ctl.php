<?php
class roleban_Controller extends Module_Lib {
	
   /**
	* Index by liumingjie add 2015-08-12 入口函数加载视图
	**/
	public function index()
	{   			 
		$listtype = (int)$_POST['listtype'];
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		
		$pagesize = 10;
		
		$data = array();
		
		$channelId = NULL;
		
		$call ='roleban';
		
		$player_id = !empty($_POST['player_id'])
		?
		' player_id='.$_POST['player_id']
		:
		NULL;
		// 封禁类型 1 禁言 2 禁止登陆
		$lockStatus = !empty($_POST['lockStatus'])
		?
		' lockType='.$_POST['lockStatus']
		:
		NULL;
		// 平台Id
		$platId = !empty($_POST['platId'])
		?
		' platId = '.$_POST['platId']
		:
		NULL;
		// 区服Id
		$server = !empty($_POST['server'])
		?
		' serverId = '.$_POST['server']
		:
		NULL;
		// 创建开始日期
		$startTime = !empty($_POST['startTime'])
		?
		' createtime>='."'".$_POST['startTime']."'"
		:
		NULL;
			
		// 创建截止日期
		$endtime = !empty($_POST['endtime'])
		?
		' createtime<='."'".$_POST['endtime']."'"
		:
		NULL;
		// 日期验证
		$this->jet_lag_day($_POST['startTime'],
		$_POST['endtime'],60,'',true);
		
		$dbconfig = Platfrom_Service::getServer(true,'globaldb');
		
		if (isset($_POST['sub_btn']))
		{ 	
			$havingAry = [
			$server,$platId,$player_id,
			$lockStatus,$startTime,$endtime];
				
			if(count($havingAry)>0)
			{
				$indata = null;
			
				foreach ($havingAry as $var)
				{
					if(empty($var)){continue;}
			
					if(!$indata){
						$indata.= " where ".$var;
					}else{
						$indata .= " and ".$var;
					}
				}
			}
				
			$_SESSION['loghaving'] = $indata;
				 
			$RoleBanTotal=
			Roleban_Model::Stat_rolebanTotal(
			$dbconfig,$_SESSION['loghaving'],$call);
				
			$_SESSION['RoleBanTotal'] = $RoleBanTotal['cont'];
		
		}
		if ($_SESSION['RoleBanTotal']){
				
			$RoleBanList = Roleban_Model::Stat_rolebanInfo($dbconfig,
			$_SESSION['loghaving'],$page, $pagesize,$call);
				
			$pagehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['RoleBanTotal'], $page, $pagesize));
				
			$data =
			[
				'roleBanInfo'=>$RoleBanList,
				'pagehtml'=>$pagehtml,
			];			
		}		
		 
		$this->load_view('gm/role_ban',$data);
		 
	}  
	 
	public function roleBanlogIndex()
	{
		
		$listtype = (int)$_POST['listtype'];
		
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		
		$pagesize = 10;
		
		$data = array();
		
		$channelId = NULL;
		
		$call ='rolebanlog';
		
		$player_id = !empty($_POST['player_id'])
		?
		' player_id='."'".$_POST['player_id']."'"
		:
		NULL;
		// 封禁类型 1 禁言 2 禁止登陆
		$lockStatus = !empty($_POST['lockStatus'])
		?
		' lockType='.$_POST['lockStatus']
		:
		NULL;
		// 平台Id
		$platId = !empty($_POST['platId'])
		?
		' platId = '.$_POST['platId']
		:
		NULL;
		// 区服Id
		$server = !empty($_POST['server'])
		?
		' serverId = '.$_POST['server']
		:
		NULL;
		// 创建开始日期
		$startTime = !empty($_POST['startTime'])
		?
		' createtime>='."'".$_POST['startTime']."'"
		:
		NULL;
			
		// 创建截止日期
		$endtime = !empty($_POST['endTime'])
		?
		' createtime<='."'".$_POST['endTime']."'"
		:
		NULL;
		 
		// 日期验证
		$this->jet_lag_day($_POST['startTime'],
		$_POST['endTime'],60,'',true);
		
		$dbconfig = Platfrom_Service::getServer(true,'globaldb');
		
		$nickName = $_POST['nick_name'];
		
		if (!empty($nickName))
		{
			 $IngameUserInfo = Gameuser_Service::getUserId(
			 (int)$_POST['platId'], $nickName);
			 
			 $player_id = !empty($IngameUserInfo['player_id'])
			 ?
			 ' player_id='."'".$IngameUserInfo['player_id']."'"
			 :
			 NULL;
		} 
		if (isset($_POST['sub_btn']))
		{
			$havingAry = [
			$server,$platId,$player_id,
			$lockStatus,$startTime,$endtime];
		
			if(count($havingAry)>0)
			{
				$indata = null;
					
				foreach ($havingAry as $var)
				{
					if(empty($var)){continue;}
						
					if(!$indata){
						$indata.= " where ".$var;
					}else{
						$indata .= " and ".$var;
					}
				}
			}
				
			$_SESSION['rolebanloghaving'] = $indata;
				 
			$RoleBanTotal=
			Roleban_Model::Stat_rolebanTotal(
			$dbconfig,$_SESSION['rolebanloghaving'],$call);
				
			$_SESSION['RoleBanlogTotal'] = $RoleBanTotal['cont'];
		
		}
		if ($_SESSION['RoleBanlogTotal']){
				
			$RoleBanList = Roleban_Model::Stat_rolebanInfo($dbconfig,
			$_SESSION['rolebanloghaving'],$page, $pagesize,$call);
				
			$pagehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($_SESSION['RoleBanlogTotal'], $page, $pagesize));
				
			$data =
			[
				'roleBanInfo'=>$RoleBanList,
				'pagehtml'=>$pagehtml,
			];			
		}		
		
		$this->load_view('gm/roleban_log',$data);
	}
	/**
	 * 文件上传解析 获取文件内容 此函数通过js去submit
	 * 文件路径为www/static/js/usersupervise.js 
	 **/
    public function uploadfile() 
    {
        $pathname = $_FILES['myfile'];
        $filename = time(0)."_".$pathname['name'];
        #$filepath = '/tmp/'.$filename;// linux tmp
        $filepath = $_SERVER['DOCUMENT_ROOT'].'/'.$filename;
        $data = null;
        
        if(is_uploaded_file($pathname['tmp_name']))
        {
        	//上传文件，成功返回true
          if(! move_uploaded_file($pathname['tmp_name'],$filepath))   
          {
              $this->outputJson(-1,"上传失败");
          }
        }else
        {
            $this->outputJson(-1,"非法上传文件");
        } 
        $ext = pathinfo($pathname['name'], PATHINFO_EXTENSION | PATHINFO_FILENAME);  
        if(trim($ext) != "txt")
        {
            $this->outputJson(-1,"文件格式错误");
        }
        if(trim($ext) == "txt" )
        {
            $file= file_get_contents($filepath);
            $filecontent = explode("\n", trim($file));
            $j = 0;
            for($i = 0 ;$i < count($filecontent); ++$i)
            {
                $tmp = explode(" ", trim($filecontent[$i]));
                //__log_message("TXT:::".$tmp[0]);
                if(count($tmp) == 2)
                {
                    $data[$j]["id"]  =  $j;
                    $data[$j]["sid"] =  $tmp[0];
                    $data[$j]["roleid"] = $tmp[1];  
                    $j++;
                } 
            }
        }
        if(trim($ext) == "xml")
        {
        	$i = 0;
            $data = array(array());
            try {
            	# 载入xml文件 $lists和xml文件的根节点是一样的
           	 	$lists = simplexml_load_file($filepath);  
            } 
            catch (Exception $ex) {
            	// __log_message("get::".$ex->getMessage());
            	$this->outputJson(-1,"加载配置失败");
            }
            
			empty($lists)?$this->outputJson(-1,"读取配置失败"):"";
            
            # 有多个user，取得的是数组，循环输出
            foreach($lists->RoleConfig as $table)
            {    
	            $data[$i]["id"]="$table->id";
	            $data[$i]["sid"]="$table->sid";
	            $data[$i]["roleid"]="$table->roleid";
	            $i++;
            }
        }  
        //删除所上传的文件
        @unlink ($filepath); 
        
        $this->outputJson(0,$data);
    } 
    
    public function save()
    {   
    	$outdata = '';
    	$type = 0;
    	// 4011禁止玩家发送聊天 4012取消禁止玩家发送聊天
        $ban_status = $_POST['ban_status'];
        // status              
    	$selected = $_POST['selected'];
    	// start time     	
    	$starttime = $_POST['starttime'];
    	// end tiem 
    	$endtime = $_POST['endtime']; 
    	// timetype
    	$timetype = $_POST['timetype']; 
    	//sid,roleid
    	$listrole = explode(',',$_POST['listroleid']);
    	// STATUS    	
    	$status = $ban_status==1
    	?
    	'locking_status'
    	:
    	'gag_status';  
    	  	    	
    	$now = date("Y-m-d H:i:s",time());
    	// reason
    	$reason = $_POST['reason'];
    	// log
    	$device= $this->getOS();
    	$detail = $_SERVER['HTTP_USER_AGENT'];
    	$Account= $_SESSION['account'];
    	$logtype = 1;
    	
    	switch ($ban_status)
    	{
    	 case 1:
    		$type=$selected==2?3:4;
    		break;
    	 case 2:
    		$type=$selected==1?5:6;
    		break;    			
    	 default: 
    		$this->outputJson(-1,"错误的类型");
    		break;
    	}
       	$type = (int)$type;
        $data = array
        (
        'begintime'=>$starttime,
        'endtime'=>$endtime,
        'datetime'=>$now,
         $status=>$selected,      
        ); 
         
        $errcode = '';
        
        $platform = Platfrom_Service::getServer(true,'globaldb');       
        $j = 0;
        for($i = 0; $i < count($listrole);$i+=2)
        {  
        	$sid = trim($listrole[$i]);
        	$roleid = trim($listrole[$i+1]);
        	if($roleid=='' or $sid==''){
        		continue;
        	}
        	$ret = Roleban_Model::updaterole($platform,$sid,$roleid,$data);
        	
        	if($ret==false)
        	{
        		$errcode.="未成功的有区服ID：".$sid."角色ID".$roleid;
        	}else{
        		$outdata[$j] = array
        		(
        			"id"=>$j,
	        		"sid"=>$sid,
	        		"roleid"=>$roleid,        				        		
        		);
        		 
        		if(!isset($resultlog)){
        			$resultlog .="('".$now."',".$sid.",".$roleid.",\"".
        			$device."\",".$logtype.",".$type.",'".$starttime.
        			"','".$endtime."','".$reason."','".$Account."',\"{$detail}\")";
        		}else{
        			$resultlog .=",('".$now."',".$sid.",".$roleid.",\"".
        			$device."\",".$logtype.",".$type.",'".$starttime.
        			"','".$endtime."','".$reason."','".$Account."',\"{$detail}\")";
        		}        		
        	}	
        	$j++;		
        } 
        if(!empty($resultlog))
        {
        	$loret = Roleban_Model::setlog($platform,$resultlog);
        	$loret!=true
        	?
        	__log_message("用户日志封停操作erroe:".$resultlog)
        	:"";
        }
        !empty($errcode)
        ?
        $this->outputJson(-1,$result)
        :
        $this->outputJson(0,$outdata); 
    }

    /***
     * Role ban status update
     * **/
    public function edit_roleBan_statu()
    {
    		$data = json_encode($_POST);
    		// 平台
    		$platId = $_POST['platId'];
    		// 区服
    		$serverId = (int)$_POST['serverId'];
    		// 角色Id
    		$player_id = $_POST['player_id'];
    		// 禁封类型
    		$lockType = $_POST['lockType'];
    		// 描述
    		$description = $_POST['description'];
    		// 
    		$originalBanType =  $_POST['originalBanType'];
    		
    		
    		$lockTimeType = 0; 
    		$lockEndtime = 0;
    		$lockStatus = 0;
    		$dbconfig = Platfrom_Service::getServer(true,'globaldb');
    		
    		$codeOut = $this->getLogmessageCode($lockType);
    		
    		switch ($lockType)
    		{
    			case 1 : $lockInfo = '禁言'; break;
    			case 2 : $lockInfo = '禁止登陆'; break;
    			case 3 : $lockInfo = '解封禁言'; break;
    			case 4 : $lockInfo = '解封登陆'; break;
    			default: $lockInfo = '未知';
    		}
    		
    		if ( $lockType==1 || $lockType==2 )
    		{ 
    			$lockTimeType = $_POST['lockTimeType'];
    			
    			$lockEndtime = strtotime($_POST['lockEndtime']);
    			
    			if(empty($lockTimeType))
    			{
    				$this->outputJson(-1,'封禁时间类型不能为空！');
    			}
    			if($lockTimeType==1)
    			{ 
    				if (empty($lockEndtime))
    				{
    					$this->outputJson(-1,'封禁截止时间不能为空！');
    				}
    				if (time()>$lockEndtime)
    				{
    					$this->outputJson(-1,'时间格式有误,截止日期不能小于当前日期');	
    				}	 
    			} 
    		}
    		$nick_name = $_POST['nick_name'];
    		
    		$data = [
    		 'platId'=>$platId,
    		 'serverId'=>$serverId,
    		 'player_id'=>$player_id,
    		 'nick_name'=>$nick_name,
    		 'description'=>$description,
    		 'createtime'=>date('Y-m-d H:i:s',time()),
    		 'lockType'=>$lockType,
    		 'lockTimeType'=>($lockType==3)?0:$lockTimeType,
    		 'lockEndtime'=>($lockType==3)?0:(int)$lockEndtime,
    		 'executor'=>$_SESSION['account'],
    		 'lockStatus'=>0
    		];
    		 
    		if ( $lockType==1 || $lockType==2 )
    		{
    			
	    		$speak = 0 ;
	    		 
	    		if ($lockType == 1 && $lockTimeType==1)
	    		{
	    			$speak = 1;
	    		}
	    		elseif ($lockType==1 && $lockTimeType==2)
	    		{
	    			$speak = 2;
	    		}
	    		 
	    		$login = 0;
	    		 
	    		if ($lockType==2 && $lockTimeType==1)
	    		{
	    			 
	    			$login = 1;
	    		}
	    		elseif ($lockType==2 && $lockTimeType==2)
	    		{
	    			 
	    			$login = 2;
	    		} 
	    		$mesage =[
    				'player_id'=>$player_id,
    				'server_id'=>$serverId,
    				'speak'=>$speak,
    				'login'=>$login,
    				'lasttime'=>(int)$lockEndtime
	    		];
	    		
	    		$code = 'RoleForbidden';
	    		 
    		}
    		else if($lockType ==3 || $lockType ==4 )
    		{ 
    			/* if ($originalBanType==$lockType || ($originalBanType==3 || $originalBanType==4))
    			{
    				$this->outputJson(-1,'很抱歉你所更改的封禁信息已经是处于解封状态!');
    			} */
    			
    			$code = 'RoleUnForbidden';
    			 
    			$mesage =array();
    			
    			$speak = 0;
    			
    			$login = 0 ;
    			
    			if($lockType==3)
    			{
    				$lockStatus = 1;
    				$speak = 1;
    			}
    			if($lockType==4)
    			{
    				$lockStatus = 2;
    				$login = 1;
    			} 
    			$data['lockStatus'] = $lockStatus;
    			
    			$mesage =[
    				'player_id'=>$player_id,
    				'server_id'=>$serverId,
    				'speak'=>$speak,
    				'login'=>$login,
    				'lasttime'=>(int)$lockEndtime
	    		];
    		} 
    		// 通知server 
    		$inHeader =  $this->VerifyToken($mesage,NULL,$code,$serverId);
    		$code = 'RoleUnForbidden';
    		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
    		$retOut = json_decode(trim($ret),true); 
    		
    		$retError = NULL;
    		
    		if ( isset($retOut['status']) && $retOut['status']==0)
    		{  
    			Roleban_Model::setRoleBan($dbconfig,$data,true);
    			
    			if(Roleban_Model::setRoleBan($dbconfig,$data))
    			{    				
    				$this->outputJson(0,'封禁变更成功!');
    			}
    			else
    			{
    				$retError = '平台为'.$platId.'区服'.$serverId.'区,的'.$player_id.'信息记录失败';
    				$this->outputJson(0,$retError);
    			}
    			
    			$this->outputJson(0,$lockInfo.'封禁失败!');
    		}
    }
    /**
     * 禁封操作
     * @method roleLock user lock
     * @method type 4011 lock to chat with  
     * ***/
    public function roleLock()
    {
    	  $endtime ='0'; 
    	  // 平台
    	  $platId = (int)$_POST['platId'];
    	  // 区服
    	  $servr = (Int)$_POST['server'];
    	  // 2-角色Id  3 - 角色昵称
    	  $roleType = (Int)$_POST['roleType']; 
    	  // 角色值
    	  $userVar = $_POST['userVar'];
    	  // 描述 原由
    	  $desc = $_POST['desc'];
    	  // lock status  
    	  $lockStatus = 0 ;
    	  
    	  $mesage =array();
    	  
    	  $AllplatformInfo = session::get('AllplatformInfo');
    	  // 根据platId get stat serverDB config
    	  
    	  foreach ($AllplatformInfo as $var)
    	  {
    	  	if ((int)$var['platformId']==$platId && (int)$var['type']==0)
    	  	{
    	  		$platConfig = $var;
    	  	}
    	  }
    	 
    	  $accountModel = new Account_Model();
    	  
    	  if($roleType == 3)
    	  { 
    	  	
    	  	// 根据 nikename get roleIndex
    	  	$inplayer = $accountModel->getRoleIndex($platConfig,$userVar,'nick_name');
    	  	
    	  	if($inplayer==false)
    	  	{
    	  		$this->outputJson(-1,'找不到的角色昵称,请重试');
    	  	}
    	  	$userVar = $inplayer['player_id']; 
    	  	$nick_name = $inplayer['nick_name'];
    	  }
    	  else
    	  {
    	  	// 根据 nikename get roleIndex
    	  	$inplayer = $accountModel->getRoleIndex($platConfig,$userVar,'play_id');
    	  	
    	  	if($inplayer==false)
    	  	{
    	  		$this->outputJson(-1,'找不到的角色昵称,请重试');
    	  	}
    	  	//$userVar = $inplayer['player_id'];
    	  	$nick_name = $inplayer['nick_name'];
    	  }
    	  
    	  $dbconfig = Platfrom_Service::getServer(true,'globaldb');
    	  
    	  // 封禁类型 1-禁言 2- 封号
    	  $bantype = (Int)$_POST['bantype'];
    	  // 封号时间类型
    	  $banTimetype =(Int)$_POST['banTimetype'];
    	  
    	  //  1-禁言 2- 封号 组装
    	  if ($bantype==1 || $bantype ==2)
    	  {
    	  	
    	  	 $code = 'RoleForbidden';
    	  	
	    	  if($banTimetype==1)
	    	  {
	    	  	   // 截止日期
	    	   	   $endtime = strtotime($_POST['endtime']);	
		    	   
		    	   if (time()>$endtime)
		    	   {
		    	   		$this->outputJson(-1,'封禁截止日期不能小于当前日期');	
		    	   }
	    	  }
	    	  
	    	  $speak = 0 ;
	    	   
	    	  if ($bantype==1 && $banTimetype==1)
	    	  {
	    	  	$speak = 1;
	    	  }
	    	  elseif ($bantype==1 && $banTimetype==2){
	    	  	$speak = 2;
	    	  }
	    	   
	    	  $login = 0;
	    	   
	    	  if ($bantype==2 && $banTimetype==1)
	    	  {
	    	  	$login = 1;
	    	  }
	    	  elseif ($bantype==2 && $banTimetype==2){
	    	  	$login = 2;
	    	  }  
	    	  
	    	  // 通知server
	    	  $mesage =array(
	    	  	'player_id'=>$userVar,
	    	  	'server_id'=>$servr,
	    	  	'speak'=>$speak,
	    	  	'login'=>$login,
	    	  	'lasttime'=>(int)$endtime
	    	  );
    	  }
    	  //  解封组装
    	  else if ($bantype ==3 || $bantype==4)
    	  {  
    	  	 
    	  	$code = 'RoleUnForbidden';
    	  	// 解封
    	  	$getRoleBanOut = Roleban_Model::getRoleBanInfo($dbconfig, [
    	  	'platId'=>$platId,
    	  	'serverId'=>$servr,
    	  	'player_id'=>$userVar]);
    	  	 
    	  	//$InroleBantype = (int)$getRoleBanOut['lockType']; 
    	  	 
    	  	/* if ($InroleBantype==$bantype || ($InroleBantype==3 || $InroleBantype==4))
    	  	{
    	  		$this->outputJson(-1,'很抱歉你所更改的封禁信息已经是处于解封状态!');
    	  	}  */
    	  	$speak = 0;
    	  	 
    	  	$login = 0 ;
    	  	 
    	  	if($bantype==3)
    	  	{
    	  		$speak = 1;
    	  		$lockStatus = 1;
    	  	}
    	  	if($bantype==4)
    	  	{
    	  		$lockStatus = 2;
    	  		$login = 1;
    	  	} 
    	  	 
    	  	$mesage =[
    	  		'player_id'=>$userVar,
    	  		'server_id'=>$servr,
    	  		'speak'=>$speak,
    	  		'login'=>$login,
    	  		'lasttime'=>(int)$endtime
    	  	];
    	  } 
    	   
    	  $data = array(
    	  	'player_id'=>$userVar,
    	  	'serverId'=>$servr,
    	  	'nick_name'=>$nick_name,
    	  	'platId'=>$platId,
    	  	'description'=>$desc,
    	  	'createtime'=>date('Y-m-d H:i:s',time()),
    	  	'lockType'=>$bantype,
    	  	'lockTimeType'=>($bantype==3)?0:$banTimetype,
    	  	'lockEndtime'=>($bantype==3)?0:(int)$endtime,
    	  	'executor'=>$_SESSION['account'],
    	  	'lockStatus'=>$lockStatus
    	  ); 
    	  
    	  $codeOut = $this->getLogmessageCode($bantype);
    	  
    	  $inHeader =  $this->VerifyToken($mesage,null,$code,$servr);
    	  $ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');    	  
    	  $retOut = json_decode(trim($ret),true);    	  
    	  $retError =NULL;    	  
    	  $codeOut = $this->getLogmessageCode($bantype); 
    	  
    	  if ( isset($retOut['status']) && $retOut['status']==0)
		  {  
		  	Roleban_Model::setRoleBan($dbconfig,$data,true);
    	  //__log_message('lock role15'.json_encode($data),'rock-log');
		  	if(Roleban_Model::setRoleBan($dbconfig,$data))
		  	{  
		  		//__log_message('lock role1','rock-log');
		  		log_message($codeOut['code'], $data, $retOut);
		  		//__log_message('lock role17','rock-log');
    	  		$this->outputJson(0,'封禁成功!');
		  	}
		  	else
		  	{   //__log_message('lock role18','rock-log');
		  		$retError = '平台为'.$platId.'区服'.$servr.'区,的'.$userVar.'信息记录失败';
		  	
		  		log_message($codeOut['code'], $data, $retError,$codeOut['errorCode']);
		  		//__log_message('lock role19','rock-log');
		  		$this->outputJson(0,'封禁日志记录失败!');
		  	}
    	   }
    	  log_message($codeOut['code'], $data, $retOut,$codeOut['errorCode']);
    	  $this->outputJson(-1,'封禁失败');
    }  
    public function getLogmessageCode($type)
    { 
    	switch ($type){
    		case 1: $logmesageCode=4014;$logErrorCode=-4014;break;
    		case 2:$logmesageCode=4015;$logErrorCode=-4015;break;
    		case 3:$logmesageCode=4016;$logErrorCode=-4016;break;
    		default: $logmesageCode=0;$logErrorCode=0;break;
    	}
    	return ['code'=>$logmesageCode,'errorCode'=>$logErrorCode];
    }
    /**
     * 解除
     * @method get post data 
     * userUnlock user unlock 
     * @param $protocolCode type 4012  接除聊天 
     * ***/ 
    public function userUnlock(){
    	$server = $_POST['server'];
    	$playerId = $_POST['playerId'];
    	$protocolCode = $_POST['protocolCode'];// 解除聊天
    	$keyid = $_POST['keyid'];
    	switch ($protocolCode){
    		case 4011: $code =4012; break;
    		default:break;
    	}    	
    	$jsonOut = $this->RequestHeader(
    	$code, $server,array('playerId'=>$playerId));    	
    	$url = $jsonOut['url'];
    	$postdata = $jsonOut['postdata'];
    	
    	$servrChenck = $this->serverCheck($server);
    	
    	if( $servrChenck==1 )
    	{ 
    		$ret = $this->send_request($url, $postdata);    		
    		if($ret)
    		{ 
    			$retOut = json_decode($ret,true);
    			if (isset($retOut['status']) && $retOut['status']==0)
    			{  
    				if (!empty($_SESSION['userbanLog']) 
    				&& isset($_SESSION['userbanLog'][$keyid]) )
    				{
    				 unset($_SESSION['userbanLog'][$keyid]);
    				}
	    			$this->outputJson(0,"解除成功!");
    			}else
    			{
    				$this->outputJson( -1, $this->keystatus($retOut,'解除失败') );
    			}
    		}
    		$this->outputJson(-1,"服务器未响应!");
    	}else {   
    		if ($servrChenck==false){$this->outputJson(-1,"错误的服务器,没有找到该服务器");}
    		$cachelogOut= array
    		(
    			"createtime"=>date("Y-m-d h:i:s",time()),    				 
    			"server"=>$server,
    			"protocolCode"=>$code,
    			"source"=>4,
    			"account"=>$_SESSION['account'],
    			"requestData"=>$postdata
    		);    		
    		if(Apilog_Model::insertAdminCache($cachelogOut))
    		{
    			$this->outputJson(-1,
    			'该角色所在服务器正在维护,以帮你记录系统缓存，待服务器开启后由服务器自动执行!');
    		}
    		$this->outputJson(-1,
    		"'该角色所在服务器正在维护,在帮你记录系统缓存时录入失败,请重试或联系管理管。");    		 
    	}
    	
    }
    /**
     * 查询指定用户基本数据
     * @method userCurrentInfo get user info
     * @return boolean json data
     * **/ 
    public function userCurrentInfo(){
    	
    	$postData = file_get_contents("php://input"); 
		  
		$dataOut = json_decode($postData,true);
		
    	$serverStatus = $this->serverCheck($dataOut['server']); 
    	
    	//__log_message("servar 状态".$serverStatus,"server");
    	
    	if ($serverStatus==1)
    	{
    		__log_message("servar 状态11".$serverStatus,"server");
	    	$request['playerId'] = $dataOut['playerId'];
	    	$inHeader = $this->RequestHeader(
	    	1001, $dataOut['server'],$request);
	    	$url = $inHeader['url'];
	    	$postdata = $inHeader['postdata'];	
	    	 
	    	$ret = $this->send_request($url,$postdata);
	    	if ($ret){ 
	    		$this->outputJson(0,$ret);
	    	}else{
	    		$this->outputJson(-1,"服务器未响应!");
	    	}
	    	return true;
    	}
    	 
    	if ($serverStatus==2){
    		//__log_message("servar 状态22".$serverStatus,"server");
    		return $this->outputJson(2,"服务器未开启,如果需要对该角色继续执行解锁,请点击确定!");
    	}else{
    		//__log_message("servar 状态-1".$serverStatus,"server");
    		return $this->outputJson(-1,"错误的服务器,没有找到该服务器");
    	}
    	
    }  
    /**
     * @global serverCheck
     * @param  $server type sid 
     * 服务器校验状态是否开启     *  
     * **/
    public function  serverCheck($server) 
    {
    	$platfrom = Platfrom_Service::getServer(true,'admin');    	 
    	$Servers_Model = new Servers_Model($platfrom);    	   
    	$serverdata =$Servers_Model->serverStatusCheck($platfrom, $server);  
    	 
    	if (isset($serverdata['status']) && !empty($serverdata['sid'])
    	&& count($serverdata)>0 
    	&& $serverdata['status']!=2)
    	{ 
    	  return  1;    	  	
    	}
    	elseif (!empty($serverdata['sid']) && $serverdata['status']==2)
    	{
    	  return 2;
    	}
    	return  false;
    }
}
