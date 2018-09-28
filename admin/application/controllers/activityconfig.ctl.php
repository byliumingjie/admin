<?php
header('Cache-control: private, must-revalidate');
class activityconfig_Controller extends Module_Lib {
	
   /**
	* Index by liumingjie add 2015-08-12 入口函数加载视图
	**/
	public  function index()
	{
		session_start();
		$data = array();
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		//__log_message($_GET['p']);
		if(isset($_SESSION['tbBoxstatu']) && $_SESSION['tbBoxstatu']==1)
		{
			@$this->prompt2("导入成功！",'on');			 
			$_SESSION['tbBoxstatu'] = false;
		}elseif (isset($_SESSION['tbBoxstatu']) && $_SESSION['tbBoxstatu']==2){
			@$this->prompt2("导入失败！".$_SESSION['tbBoxerror'],'on');			 
			$_SESSION['tbBoxstatu'] = false;
		}
		
		$type = (Int)$_POST['type']; 
		
		if(isset($type) && !empty($type) && isset($_POST['sut']))
	 	{ 
	 		$_SESSION['dbType'] = $type;
			$indb = $this->get_dbinfo($_SESSION['dbType']); 
			
			$boxID = !empty($_POST['boxidd'])?'where boxid in ('.$_POST['boxidd'].')':'';
			$_SESSION['boxID'] = $boxID; 
			foreach($indb as $key=>$var)
			{
				  //$conn = $key ;
				  //$dbname = $var;
				  $_SESSION['dbConn']= $key;
				  $_SESSION['dbName']= $var;
			}  
			
			$total =Box_Model::get_boxTotal($_SESSION['dbConn'],$_SESSION['dbName'],$_SESSION['boxID']);
			$_SESSION['total'] = $total['cont'];
	 	}
	 	if($_SESSION['dbConn'])
	 	{
	 		//__log_message("CCCCCtotal:::".$_SESSION['total']);
	 		
	 		$pagesize = 100;
	 		
	 		$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($_SESSION['total'],
			$page,$pagesize)); 
			
	 		$list = Box_Model::get_box($_SESSION['dbConn'],$_SESSION['dbName'],$_SESSION['boxID'],$page,$pagesize);
	 		
			$full = Box_Model::get_full($_SESSION['dbConn'],$_SESSION['dbName']);
			
			$data = array
			(
			 'pagehtml'=>$pagehtml,
			 "data"=>$list,
			 "full"=>$full,
			 "type"=>$_SESSION['dbType'],
			);
	 	}
		$this->load_view("activity_config",$data);
		
	}
	public function get_dbinfo($type)
	{ 
		$loadplatform = array();
		if(isset($type) && $type>=0)
		{ 
			//__log_message("进入：：：".$type);
			$roledbinfo[] = $this->region_configinfo(1,
	 		$type,"payjoin0928");#  
	        
			foreach($roledbinfo as $rolevar)
			{  
				foreach($rolevar as $dbvar)
				{
					$dbname = $dbvar[0];
					$conn   = $dbvar[1];				
				}				
			}		
			
			switch($type)
			{  				
				case 1:
				case 2:$platformOutdb = array($conn=>"db_mt_admin");break;
				case 3:$platformOutdb = array($conn=>"db_qmonster_appstroe");break;
				case 4:$platformOutdb = array($conn=>"db_qmonster_mf");break;
				case 5:$platformOutdb = array($conn=>"db_qmonster_wp");break;
				case 7:$platformOutdb = array('MorefunActivity'=>"db_qmonster_uk");break;													   
			    case 101:$platformOutdb = array($conn=>"globaldb");break;				
				default: return false;break;
			}
			return $platformOutdb;
		}else{
			return  false;
		}
	}
	 
	// ADD 
	public function add()
	{
		$data = array(); 
		$type  = (int)$_POST['addType'];		 
		$boxid = empty($_POST['boxid'])?$this->outputJson(-1,"boxID不能为空"):$_POST['boxid'];
		$thingtotal = $_POST['thingtotal'];
		$thing1ID   = $_POST['thing1ID'];
		$thing1type = $_POST['thing1type'];
		$thing1num  = $_POST['thing1num'];
		$thing2type = $_POST['thing2type'];
		$thing2ID   = $_POST['thing2ID'];
		$thing2num  = $_POST['thing2num'];
		$thing3type = $_POST['thing3type'];
		$thing3ID   = $_POST['thing3ID'];
		$thing3num  = $_POST['thing3num'];
		$thing4type = $_POST['thing4type'];
		$thing4ID   = $_POST['thing4ID'];
		$thing4num  = $_POST['thing4num'];
		
		$platformOut = $this->get_dbinfo($type);
		$Data = array (
		'boxid'=>$_POST['boxid'],
		'thingtotal'=>$_POST['thingtotal'],
		'thing1ID'  =>$_POST['thing1ID'],
		'thing1type'=>$_POST['thing1type'],
		'thing1num' =>$_POST['thing1num'],
		'thing2type'=>$_POST['thing2type'],
		'thing2ID'  =>$_POST['thing2ID'],
		'thing2num' =>$_POST['thing2num'],
		'thing3type'=>$_POST['thing3type'],
		'thing3ID'  =>$_POST['thing3ID'],
		'thing3num' =>$_POST['thing3num'],
		'thing4type'=>$_POST['thing4type'],
		'thing4ID'  =>$_POST['thing4ID'],
		'thing4num' =>$_POST['thing4num']
		);
		
		foreach($platformOut as $key=>$var)
		{	
			$conn = $type === 0?substr($key,0,-1):$key;
			$dbname = $var;
			//后期开启
			$ret = Box_Model::set_box($conn,$dbname,$Data);
			//$ret = false;
			if($ret == false){
				$this->outputJson(-1,'添加失败');
				return false;
				exit();
			}else{
				$this->outputJson(0,'添加成功');
			} 
		}  
	}
	public function edit()
	{
		$type = $_POST['addType'];
		
		$boxid = $_POST['boxid']; 
		
		$Data = array(		 
		'thingtotal'=>$_POST['thingtotal'],
		'thing1ID'  =>$_POST['thing1ID'],
		'thing1type'=>$_POST['thing1type'],
		'thing1num' =>$_POST['thing1num'],
		'thing2type'=>$_POST['thing2type'],
		'thing2ID'  =>$_POST['thing2ID'],
		'thing2num' =>$_POST['thing2num'],
		'thing3type'=>$_POST['thing3type'],
		'thing3ID'  =>$_POST['thing3ID'],
		'thing3num' =>$_POST['thing3num'],
		'thing4type'=>$_POST['thing4type'],
		'thing4ID'  =>$_POST['thing4ID'],
		'thing4num' =>$_POST['thing4num']
		);
		 
		$dbinfoOut = $this->get_dbinfo($type);
		
		foreach($dbinfoOut as $key=>$var)
		{
			$conn = $loadplatform === 0?substr($key,0,-1):$key;
			$dbname = $var;	
			$ret = Box_Model::update($conn,$boxid,$dbname,$Data);
			if($ret== true){
				$this->outputJson(0,'编辑成功');
			}else{
				$this->outputJson(0,'编辑失败'.$conn.'-dbanme-'.$dbname);
			}
		} 
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
        
        $filepath = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$filename;
        __log_message($_SERVER['DOCUMENT_ROOT'],'file-dir-log');
        __log_message($filepath,'file-dir-log');
        
        $data = null;
        
        if(is_uploaded_file($pathname['tmp_name']))
        {
          if(! move_uploaded_file($pathname['tmp_name'],$filepath))//上传文件，成功返回true    
          {
              $this->outputJson(-1,"上传失败");
          }
        }else
        {
            $this->outputJson(-1,"非法上传文件");
        } 
        
        $ext = pathinfo($pathname['name'], PATHINFO_EXTENSION | PATHINFO_FILENAME);  
        if(trim($ext) != "xls")
        {
            $this->outputJson(-1,"文件格式错误");
        }
        
        if(trim($ext) == "xls")
        { 
           	 $nameOut = array(array());
             $data = new excelreader();
      		 $data->setOutputEncoding('UTF-8');
      		 $data->read($filepath);      		 
      		 // 获取execl shell单元格属性
      		 $j = 0;
			 foreach($data->boundsheets as $var)
			 {  			 	
			 	//__log_message("datrainfoo:::Name:::".$var['name']);			 	
				$nameOut[$j]["id"] = $j; 
				$nameOut[$j]["name"] = $var['name'];
				$j++;
			 }
			 //文件存放路径
      		 $nameOut[0]["filepath"] = $filepath;
      		 
       		 $datainfo ='';       			
       		 // 以下是获取相关的数据全部的相关信息
        }  
        //删除所上传的文件
        $this->outputJson(0,$nameOut);
    }
	//录入数据
	public function loadfile()
	{  
		$row = (int)$_POST['row']; 
		$clos = (int)$_POST['clos']; 
		$filepath = $_POST['filepath']; 
		$checkboxid = (int)$_POST['checkboxid'];
		$loadplatform = $_POST['loadplatform'];
		$settype = (int)$_POST['settype'];//0清楚数据库 重新导入 1追加

		$datainfo ='';	 
		$nameOut = array(array());
        $data = new excelreader();
      	$data->setOutputEncoding('UTF-8');
      	$data->read($filepath);
      	// 以下是获取相关的数据全部的相关信息
      	if($checkboxid>=0 && $row>=2 && $clos>=1)
      	{
		      	for ($i = $row; $i <= $data->sheets[$checkboxid]['numRows']; $i++) 
				{
					$strinfo = '';
					$trim = '';
					for ($j = $clos; $j <= 21; $j++) 
					{
						$dat = $data->sheets[$checkboxid]['cells'][$i][$j];
						$trim .=$dat;
				
						$strinfo.= !is_numeric($dat)
						?"\"".$dat."\","
						:
						$dat.",";		 
					}
				
					if(empty($trim)){
						continue;
					}
					$datainfo .= "(".substr($strinfo,0,-1)."),"."\n";
				}
      	}
      	 
		//
		$Data = substr(trim($datainfo),0,-1);
	 
		$platformOutdb = array();
 		$retErrorOut = '';
		if(!empty($loadplatform))
 		{
	 		foreach ($loadplatform as $var)
	 		{
				$platformOutdb[] = $this->get_dbinfo((int)$var);
	 		}
 		}
		if(!empty($platformOutdb) && $loadplatform>=0)
		{ 
		  foreach($platformOutdb as  $Inplatform)
		  {
			foreach ($Inplatform as $key=>$var)
			{
				$conn = $loadplatform === 0?substr($key,0,-1):$key;
				$dbname = $var;	
				//清理数据库(覆盖式)
				$status = $settype===2?Box_Model::delet_box($conn,$dbname):$settype;

				if($status==true)
				{
					$ret = Box_Model::set_box($conn,$dbname,$Data,true);
				}				
				if($ret == false){
					$retErrorOut.="失败通信为:".$conn."database".$dbname; 
				}else{
					 $ret = true;
				}	 
			}
		  }
		} 
	 	 
		if($ret == true && empty($retErrorOut)){
			$_SESSION['tbBoxstatu'] = 1;//成功提交状态显示
		}
		else{
			$_SESSION['tbBoxstatu'] = 2;//Error显示
			$_SESSION['tbBoxerror'] = $retErrorOut;			 
		}
		//删除所上传的文件		
		unlink ($filepath); 
		//__log_message("//删除所上传的文件");
	 	header("location:index"); 
  	}
    /**
     * _____________________________________________________________________________________ 
     * Set account blockade  by liumingjie add 2015-08-18
     * @ save 在角色封锁解除以及禁言等去执行此函数
     * @ __log_message();日志
     * @ $data = Rolesupervise_Model::get_region($region_id);
     * 其实如果是获取平台以及是所配置的角色区服库名配置表大可不比一直进行切换db连接
     * 只有在有些数据并非在一个db连接上面才可以切换比如设置的accout 的uin封号设置是需要进行切换的
     * 其次是通过角色ID找到他的uin也是需要进行切换db的
     * _____________________________________________________________________________________ 
     **/
    public function save()
    {        	 
        $platform = Helper_Lib::getCookie('zoneid');//获取平台ID 
        #$platform = 2;
        
        $outdata  = null; // 返会数据
        
        $global_platform = 1; // 定义唯一的平台ID
        
        $forbidtype = $_POST['forbidtype']; // 操作类型 1-永久锁定  2-普通  0-解锁
        
        $rolemode = new Rolesupervise_Model($platform);
        
        $GameuserServer = new  Gameuser_Service();
		
        $LookDataService = new LookData_Service();
         
        #解析upload file get file(txt) data info actions is usersupervise.js
        $listrole = explode(',',$_POST['listroleid']);#第一列区服ID 第二列角色ID            
        $j = 0;
        for($i = 0; $i < count($listrole);$i+=2)
        {  
        	$region_id  = trim($listrole[$i+1]); //获取上传文件区ID
        	
        	$roleid 	= trim($listrole[$i]);	//获取上传文件角色ID
        	
        	#首先根据区服ID找到所对应的那个库然后根据角色找到他所属的uin
        	//这个地方之前的
        	//__log_message("平台 or 区服编号：：：".$platform.'__region区服编号'.$region_id);		  
        	//$regioninfo = $rolemode->get_region($platform,$region_id); 
        	
			$regioninfo = $LookDataService->getOneServer($platform,$region_id);	//提供角色	
					
        	if(empty($regioninfo))
        	{
        		__log_message($regioninfo['roledb']);
        		
        		$this->outputJson(-1, '没有获取到对应区服！');
        	} 
        	#拼接区服 区服标识符+区服编号//获取正式服要进行区服标识符与编号进行调整一下位置
        	//$dbname = $region_id.trim($regioninfo['dbIdentifier']);
        	 $dbname = trim($regioninfo['roledb']);       	 
         	
        	#$platform 可以改为默认的  	
        	//如果是非账号管理的数据库要进行相对应的切换对应上面获取的区服的相关
        	$uin  = $rolemode->get_role_uin($dbname,$roleid,$regioninfo); // 获取全局唯一ID 角色uin(通过角色ID找到uin)  
        	      	 
        	//__log_message("getUINUINUINUINUINUIN".$uin['uin']);
        	
        	$data = array('state' => $_POST['forbidtype']); // 获取锁定状态
        	
        	$rolemode->set_uin($uin['uin'],$data,$regioninfo);// 通过获得到的uin设置账号 
        	
        	$stServer =  Platform_Model::getPlatformByID($platform);//得到数据库连接池
        	
        	$server  = Server_Service::getServerByPtAndId($platform,$region_id,$stServer);	//获得通信IP 
             #后期上传linux server 添加此代码 是与服务端进行通讯的                 
        	try{
                 $data = array(
                    'roleuin'   => $roleid,
                    'starttime' => 0,
                    'endtime'   => 0,
                    'forbidtype'=> $forbidtype,
                    'type'	    => 1,
                    'talktime'  => 0,
                    'worldid'   => $region_id,
                    'account'   => $_SESSION['account'],
                ); 
                //__log_message("ForbidRole");
                $ret = $GameuserServer->ForbidRole($server,$data);
                
				if(empty($ret)){
					//__log_message($regioninfo['zoneserver_ip'].'ippp__'.$regioninfo['zoneserver_port']);					 
				} 
                
                if (!$ret) 
				{
					$flag[$i] = $listrole[$i]; 
				}
				else{
					$outdata[$i]["id"] = "$i";
					$outdata[$i]["sid"]  = "$region_id";
					$outdata[$i]["roleid"] = "$roleid";
					$outdata[$i]["status"] = "$forbidtype";//操作类型
				}
            } catch (Exception_Lib $e) {
            	//__log_message($e->getCode());
                $this->outputJson($e->getCode(), $e->getMessage());
            }  
			$j++;
        } 
        
   		if(count($flag)==0)
        {   unset($_POST['forbidtype']);
			 
            $this->outputJson(0,$outdata);
        }else
        {
            $this->outputJson(-1, '未成功的有！'.  implode(',', $flag));
        }
   
    }
    public function load(){
    
    }
	/*__________________________________________________________________________________*/ 
     
}
