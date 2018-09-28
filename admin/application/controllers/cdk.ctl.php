<?php

class Cdk_Controller extends Module_Lib 
{ 
	public function Index($data=array())
	{   
		$Config = new  Config_Model();
		// 表情包配置
		$faceConfig = $Config->getfaceConfig();
		// 装备盘配置
		$equipConfig =$Config->getequipConfig();
		// 道具配置
		$itemConfig = $Config->getItemconfig();
		// 技能配置
		$skillConfig = $Config->getSkillconfig();
		// 套装配置
		$suitConfig = $Config->getConfig('tb_suit_config');
		$data['configinfo'] = array
		(
			"faceConfig"=>$faceConfig,
			"equipConfig"=>$equipConfig,
			"skillConfig"=>$skillConfig,
			"suitConfig"=>$suitConfig,
			"itemConfig"=>$itemConfig
		);
        $this->load_view("dev/cdk/add_gift",$data);
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
           	 
             $data = new Spreadsheet_Excel_Reader();
             
      		 $data->setOutputEncoding('UTF-8');
      		 
      		 $data->read($filepath);     
      		  		 
      		 // 获取execl shell单元格属性
      		 $j = 0;
			 foreach($data->boundsheets as $var)
			 {  		 	
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
		//$data = array();
		
		$row = (int)$_POST['row']; 
		$clos = (int)$_POST['clos']; 
		$filepath = $_POST['filepath']; 
		$checkboxid = (int)$_POST['checkboxid'];
		$loadplatform =0; //$_POST['loadplatform'];
		$settype = 0;//(int)$_POST['settype'];//0清楚数据库 重新导入 1追加
		
		if($filepath && file_exists($filepath)){	
		$ret = false;
		$datainfo ='';	 
		$nameOut = array(array());
        $data = new Spreadsheet_Excel_Reader();
      	$data->setOutputEncoding('UTF-8');
      	$data->read($filepath);
      	$numRows = $data->sheets[$checkboxid]['numRows'];
      	$g_numcols = $data->sheets[$checkboxid]['numCols'];
      	$numCols = 6;
      	$strin='';
      	//$strinfo='';
      	$datainfo ='';
      	$dbdatainfo = '';
      	// 以下是获取相关的数据全部的相关信息
      	if($checkboxid>=0 && $row>=1 && $clos>=1)
      	{
	      	for ($i = $row; $i <= $numRows; $i++) 
			{
				//$strinfo = '';
				$trim = '';
				$strin ='';
				for ($j = $clos; $j <= $numCols; $j++) 
				{
					$dat = $data->sheets[$checkboxid]['cells'][$i][$j];
					$trim .=$dat;
					$strin .=$dat.",";
					/*$strinfo.= !is_numeric($dat)
					?"\"".$dat."\","
					:
					$dat.",";	*/
				}
				
				if(empty($trim)){
					continue;
				}
				$datainfo.=substr($strin,0,-1)."#";
				//$dbdatainfo .= "(".substr($strinfo,0,-1)."),"."\n";
			}
      	} 
		$data = array(
			"object"=>substr(trim($datainfo),0,-1),
			"numclos"=>$g_numcols,
		); 
		unlink ($filepath);
		}		
		$this->load_view("dev/cdk/list",$data);
  	}
  	
  	// 录入礼包
  	public function setPackage(){
  		
  		$time = date('Y-m-d H:i:s',time());
	  	// json_decode
  		$cheagedata = empty($_POST['cheagedata'])
  		?
  		$this->prompt("批量导入的数据不能为空")
  		:
  		$_POST['cheagedata'];
  		
  		// 选项
  		$checkcdk = empty($_POST['checkcdk'])
  		?
  		$this->prompt("选项不能为空!")
  		:
  		$_POST['checkcdk'];
  			
	  	$chageout = json_decode($cheagedata,true);
	  	
	  	$giftname = json_decode($_POST['giftname'],true);
	  	
	  	!is_array($chageout) or !is_array($giftname)
	  	?
	  	$this->prompt2("不正确的数据格式!","off")
	  	:""; 
	  	// 初始
	  	$nameout = $dataOut = '';
	  	 
	  	foreach($checkcdk as $checkID)
	  	{ 
	  		foreach($chageout as  $key=>$var)
			{
	  		 	if($key == $checkID)
				{
					$dataOut.=!$dataOut
					?
					"(".$var.",'".$time."')"
					:
					",(".$var.",'".$time."')";					
	  		 	}
	  		 }
		  	foreach ($giftname as $namekey=>$iname)
			{
				if($namekey == $checkID)
				{
					$nameout .= !$nameout
					?
					"'".trim($iname)."'"
					:
					",'".trim($iname)."'";
				}
			}
	  	}
	  	$nameout = " WHERE name in (".$nameout.")";
		  
	  	$platfrom = Platfrom_Service::getServer(true,'globaldb');
		
	  	$ret = CDK_Model::setgift($platfrom,$dataOut);
	  	
		 
		$ret==true
		?
		$this->prompt2("录入成功!","on")
		:
		$this->prompt("失败!");
		
		if($ret==true){
			$_SESSION['nameout']=$nameout;
			$_SESSION['setgift']=1;
		}else{
			$_SESSION['setgift']=2;
		}		
  		header('Location:giftlist');		
  	}
  	
  	// 单个礼包录入
 /*  	public function setSinglePackage()
  	{
  		$time = date('Y-m-d H:i:s',time());
  		  		
  		$cdkname = $_POST['cdkname'];
  		$gold = $_POST['gold'];
  		$diamond = $_POST['diamond'];
  		$equipment = $_POST['equipment'];
  		$props1 = $_POST['props1'];
  		$props2 = $_POST['props2'];
  		$props3 = $_POST['props3'];
  		$props4 = $_POST['props4'];
  		$title  = $_POST['title'];
  		
  		$dataOut = "('".$cdkname."','".$title."','".$props1.
  		"','".$props3."','".$props4."','".$props4."','".$time."')";
  		
  		$platfrom = Platfrom_Service::getServer(true,'globaldb');
  		
  		$ret = CDK_Model::setgift($platfrom,$dataOut);
	  	
		 
		$ret==true
		?
		$this->prompt2("录入成功!","on")
		:
		$this->prompt("失败!");
		
		if($ret==true){
			//$nameout = $cdkname;
			$nameout = " WHERE name in ('".$cdkname."')"; 
			$_SESSION['nameout']=$nameout;
			$_SESSION['setgift']=1;
		}else{
			$_SESSION['setgift']=2;
		}		
  		header('Location:giftlist');
  		
		//$this->giftlist();
  	} */
 	
	//文件导出
	public function ExportfileIndex()
	{
		$type = isset($_POST['type'])?$_POST['type']:0;
		$time = isset($_POST['time'])?explode(',',trim($_POST['time'])):'';		
		$data = isset($_POST['data'])?explode(',',trim($_POST['data'])):0;
		$sid  = isset($_POST['sid'])?$_POST['sid']:0;
		$key  = isset($_POST['key'])?$_POST['key']:'';
		
		$keystr = str_replace(array("\r\n", "\r", "\n"), "",$key);
		 
		foreach($data as $var)
	 	{
	 		$Data[] = explode("=",$var); 
	 	}	
		$data = array($keystr=>$Data);
	 	
	 	$fileName = $this->output_file($time,$type,$data,'',$sid);
	 	
	 	if($fileName)
	 	{	 	
	 		$page = Config::get("common.page");
	 		$acction = $page['host'].'/statfile/';	 
	 		header("location:".$acction.$fileName); 
	 		$this->load_view("dev/cdk/giftlist",$data);
	 	}  
	}
	 
	//设置cdk
	public function setcdk()
	{ 
		$page = empty($_GET['p']) ? 1 : $_GET['p']; 
  		
  		$pagesize = 50;
  		$platfrom = Platfrom_Service::getServer(true,'globaldb');
  		
  		// GIFTID 
  		if (isset($_POST['sub_btn'])){
  		$giftid = !empty($_POST['giftid'])
  		?
  		" giftid ={$_POST['giftid']}"
  		:
  		"";
  		 
  		$title = !empty($_POST['name'])
  		?
  		" title like '%".$_POST['name']."%'"
  		:"";
  		
  		$code = !empty($_POST['cdkcode'])
  		?
  		" code='".$_POST['cdkcode']."'"
  		:"";
  		//__log_message("code".$code);
  		$startTime = !empty($_POST['startTime'])
  		?
  		" creattime>='".$_POST['startTime']."'"
  		:
  		"";
  		
  		$endTime = !empty($_POST['endTime'])
  		?
  		" creattime<='".$_POST['endTime']."'"
  		:
  		"";  		 
  		
  		$type = !empty($_POST['type'])?" gift_type=".$_POST['type']:"";
  		
  		$rule = !empty($_POST['rule'])?" gift_rule=".$_POST['rule']:"";
  		
  		$havingAry = array($giftid,$code,$title,$startTime,$endTime,$type,$rule);
	
		if(!empty($giftid) || !empty($title)||
		!empty($startTime) || !empty($endTime)||
		!empty($type)|| !empty($rule) || !empty($code)){
			//__log_message("存在");
	        foreach ($havingAry as $var)
	        {
				//__log_message("存在".$var);
	        	if(empty($var)){continue;}
	        	 
	        	if(!$indata){
	        		
	        		$indata.= " where ".$var;
	        	}else{
	        		$indata .= " and ".$var;
	        	}
	        } 
		}
	       
		$indata.= ' GROUP BY batch';
		$_SESSION['cdkHaving'] = $indata;
		
  		$total = CDK_Model::Stat_CdkTotal($platfrom,$_SESSION['cdkHaving']);  
  		$_SESSION['total'] = $total['cont']; 
  		 
  		}
  		 
  		if ($_SESSION['cdkHaving'] && $_SESSION['total'])
  		{  			
  			__log_message("cdk pagetotal:".$_SESSION['total'],'cdk-list');
	  		$pagehtml = htmlspecialchars(
	  		Helper_Lib::getPageHtml($_SESSION['total'],
			$page,$pagesize));
			$list = CDK_Model::Stat_CdkInfo(
			$platfrom,$_SESSION['cdkHaving'],$page,$pagesize);
  		
			//$giftinfo = CDK_Model::Stat_giftInfo($platfrom);
			 
	  		$data = array
			(
	            'pagehtml'=>$pagehtml,
	            'object'=>$list, 
	        );
  		}  
        //$langcode = Session::get("langcode");
        
		$this->load_view("dev/cdk/edit",$data); 
	}
	
	public function create_uuid($prefix = "",$codelent='',$batch='')
	{ 
			$uuid = uniqid($batch.mt_rand(),true);
			
			$str   = strtoupper(MD5($uuid)); 
			
			//__log_message($str,'cdkcode-log');
			//__log_message('uuid'.$uuid,'cdkcode-log');
			$arrOut = array("W","S",9,"P","X","N","M","B","C",2,"D",3,"E","F","G",
			"H","J","K",8,"L","A",7,6,"Q",5,"R",4,"T","U","V","Y");
			$num = mt_rand(0,22);
			 
			$uuid = substr($str,0,$codelent);  

			$uuid  = str_replace("1",mt_rand(2,9),$uuid);
			$uuid  = str_replace("0",mt_rand(2,9),$uuid);
			$uuid  = str_replace("I",$arrOut[mt_rand(1,30)],$uuid);
			$uuid  = str_replace("L",$arrOut[mt_rand(1,30)],$uuid);
			$uuid  = str_replace("O",$arrOut[mt_rand(1,30)],$uuid);
			$uuid  = str_replace("Z",$arrOut[mt_rand(1,30)],$uuid);

			return  strtoupper($prefix).$uuid;
	} 
	// 
	/***
	 * 兑换码生成
	 * 
	 * **/
	public function setcode()
	{ 
		// 前缀
		//$prefix = $_POST['prefix'];		
		/*$plafromdata = $_POST['platformid'];		
		(!is_array($plafromdata) or count($plafromdata)<=0)
		?
		$this->outputJson(-1, '平台为空！'):'';
		// 获取平台数量
		$contPlafrom = count(explode(",",$_POST['contPlafrom']));
		
		// 若是所有平台指定字符否则转换字符,分割ID
		if(count($plafromdata)==$contPlafrom)
		{
		$plafromID = -1;
		}else{
		$plafromID = implode(",",$plafromdata);
		} */		
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		
		$serverId =0;
		// 礼包Id
		$giftid = empty($_POST['giftId'])
		?
		$this->outputJson(-1, '礼包ID为空！')
		:
		$_POST['giftId'];
		
		// 平台类型
		$platformid = empty($_POST['platformid'])
		?
		$this->outputJson(-1, '平台ID为空！')
		:
		$_POST['platformid'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		// 账号领取规则		
		$gift_type = empty($_POST['type']) 
		? 
		$this->outputJson(-1, '账号领取规则为空！') 
		: 
		$_POST['type'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		// 服务器限制规则	
		$gift_rule = empty($_POST['rule']) 
		? 
		$this->outputJson(-1, '服务器限制规则为空！')
		:
		$_POST['rule'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		if ($gift_rule == 2)
		{			
		$serverId = empty($_POST['ServerId']) 
		? 
		$this->outputJson(-1, '区服为空！') 
		: 
		$_POST['ServerId'];
		}
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		//开始日期		
		$startTime = empty($_POST['startTime']) 
		? 
		$this->outputJson(-1, '开始日期为空！') 
		: 
		$_POST['startTime'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		//截止日期
		$endtime = empty($_POST['endtime'])
		?
		$this->outputJson(-1, '结束日期为空！')
		:
		$_POST['endtime'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		// 礼包说明
		$title = empty($_POST['title'])
		?
		$this->outputJson(-1, '礼包说明为空！')
		:
		$_POST['title'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		// 生产数量
		$number = (empty($_POST['number']) 
		&& 
		!is_numeric($_POST['number']))  
		? 
		$this->outputJson(-1, '礼包生成数量为空,或填写类型有误请以整数类型设置！') 
		: 
		$_POST['number'];

		if ($number<=0)
		{
			$this->outputJson(-1, '请输入兑换码数量!');
		}
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		//礼包码长度
		$sizes = (empty($_POST['cdklenth']) 
		&& 
		!is_numeric($_POST['cdklenth'])) 
		? 
		$this->outputJson(-1, '长度为空,或填写类型有误请以整数类型设置！') 
		: 
		$_POST['cdklenth'];
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
	    ($sizes>20 || $sizes<6 ) 
	    ? 
	    $this->outputJson(-1, 'CDK长度最高20位,,最小6位请重设'):""; 
		
		($number>50000) 
		? 
		$this->outputJson(-1, '生成数量超出范围上限为5万！'):"";
		// 《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《《 //
		// 读取批次
		$btachData = CDK_Model::getVerifyCode($platfrom);
		
		foreach($btachData as $batchVar)
		{
			$batchOut[] = $batchVar['batch'];
		}
		// 生成随机批次/检验
		$c = 0;
		while(true)
		{
			$arrOut = ["W","S","P","X","N","M",
			"B","C","D","E","F","G","H","J","K","L","A","Q","R","T","U","V","Y"];

			$batch = $arrOut[mt_rand(0,22)].$this->create_uuid("",3);
			
			if (is_numeric($batch))
			{
				continue;
			}
			if( !in_array("{$batch}",$batchOut,true)){ break; }				
			$c++;
			if ( $c == 5 ){
				$this->outputJson(-1,'批次重复检索排除超时，请重试!'); exit();break;
			} 
		}
		$i = 0;
		$j = 0;
		$data = array();		 
		// get cdk 对照前缀批次等存放数组或session里 
		// 后期改为读取数据库其次放进该数组键值为礼包ID批次或者其它 
		while(true){		
			$codeids= $this->create_uuid($prefix,$sizes,$batch);	
				
			$data[$codeids] = null;
			 
			// 不存在相关的数组键值数据
			if(isset($data) && count($data)==$number ){
				
				break;
			}		 
		 }
		 $invalues = '';
		 $langcode = array();
		 $i=0;
		 $batchcode = 0;
		foreach($data as $incode=>$var)
	 	{
	 		$i++;/* 
	 		'title,giftid,batch,code,cdk,gift_type,
			gift_rule,starttime,endtime,plafrominfo,serverId,creattime'; */
	 		
	 		$invalues[] = array(
	 		'title'=>'"'.$title.'"',
 			'giftid'=>$giftid,
 			'batch'=>'"'.$batch.'"',
 			'code'=>'"'.$incode.'"',
 			'cdk'=>'"'.$giftid.$batch.$incode.'"',
 			'gift_type'=>$gift_type,
 			'gift_rule'=>$gift_rule,
 			'starttime'=>strtotime($startTime),
 			'endtime'=>strtotime($endtime),
 			'plafrominfo'=>'"'.$platformid.'"',
 			'serverId'=>$serverId,
 			'creattime'=>'"'.date('Y-m-d H:i:s',time()).'"',
	 		);
	 		
	 		/* if(!$invalues)
	 		{
	 			$invalues .="(".$giftid.",'".$title."','".$incode.
	 			"',".$gift_type.",".$gift_rule.",'".$datetime."'".
	 			",'".$batch."','".$plafromID."',now())"; 
	 		}else{
	 			$invalues .=",(".$giftid.",'".$title."','".$incode.
	 			"',".$gift_type.",".$gift_rule.",'".$datetime."'".
	 			",'".$batch."','".$plafromID."',now())";
	 		}	 */
	 		if($i==5000)
	 		{	
	 			$batchcode++;
	 		
	 			$langcode[$batch+$batchcode] = $invalues;
		 		$i = 0;
		 		$invalues = array();
		 		
		 		unset($invalues);
		 		continue;	 		 
	 		}   		
	 	}	
	 	if(!empty($invalues) && count($invalues)>0)
	 	{
	 		$langcode[] = $invalues;
	 		
	 		unset($invalues);
	 	}
	 	
	 	unset($data);	
	 	/* 
	 	var_dump($invalues);
	 	
	 	if(CDK_Model::setcode($platfrom,$invalues)){
	 		
	 		$this->outputJson(0,"生成成功");
	 	}
	 	$this->outputJson(0,"生成失败"); */
	 	$retOut = array();
	 	//__log_message("cdk".json_encode($langcode),'cdkcode-log');
		foreach ($langcode as $code )
		{  
			//__log_message("cdk11".json_encode($code),'cdkcode-log');
			if(!CDK_Model::setcode($platfrom,$code))
			{
				
				$retOut[] = 1; 
			}
			else
			{
				//释放资源
				unset($langcode[$batch+$batchcode]);
			} 
			 
		}
		unset($langcode);
		if(empty($retOut) && count($retOut)<=0)
		{
			$this->outputJson(0,"生成成功");
		}else{ 
			$this->outputJson(-1,"生成失败!失败数量".count($retOut));
		}
		//后期自动下载
	}
	public function download()
	{
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		$codeid = $_GET['id'];
		$batch = $_GET['batch'];
		$data = CDK_Model::getcode($platfrom,$codeid,$batch);
		 
		$fileName = date('Y-m-d h-i',time()).".xls";	 // Name xls or set up their own
		$url  = $url==""?PROJECT_ROOT."www//statfile//":$url;// Default(www\\file\\)  
		$file = fopen($url.$fileName,'w'); 				 // Fopen create xls
		//平台
		$platId = "平台".$data[0]['plafrominfo'];
		// 
		$giftTypeInfo  = ($data[0]['gift_type']==1) 
		? 
		'账号规则:多账号兑换'
				:'账号规则:单账号兑换';
		
		$serverId ="";		
		$giftRule = (int)$data[0]['gift_rule'];
		$giftRuleInfo ="";
		 
		//var_dump($giftRule);
		switch ($giftRule)
		{
			case 1:
				$giftRuleInfo="服务器规则:不限制";
				break;
			case 2:
				$giftRuleInfo="服务器规则:单服务器兑换(指定服务器)";
				$serverId="区服".(int)$data[0]['serverId']; 
				break;
			case 3:
				$giftRuleInfo='服务器规则:任意单服务器(限定1次)';
				break;
			default:
				$giftRuleInfo= '服务器规则:未知'; 
				break; return false;
		}	
		
		fwrite($file,"code"."\t".$data[0]['title'].
		"\t".$giftTypeInfo."\t".$giftRuleInfo."\t".$platId."\t".$serverId."\n");
		
	 	foreach($data as $var)
	 	{
	 		//$dataInfo.=$var['giftid'].$var['batch'].$var['code']."\n";
	 		$dataInfo.=$var['cdk']."\n";
	 	}
	 	fwrite($file,$dataInfo."\n");	 	
	 	unset($dataInfo);
	 	unset($data);
	 	//$fileName = $this->output_file('','',$data,'','');
	 	
	 	if($fileName)
	 	{	 	
	 		$page = Config::get("common.page");
	 		$acction = $page['host'].'/statfile/';	 
	 		header("location:".$acction.$fileName); 
	 		//$this->load_view("stat/stat_data",$data);
	 	} 	
	 	
	}
	//<<<<<<<<<<<<<<<<<<<<<<<<
	// 添加礼包
	public function Add_Gift()
	{
		$itemArrayList = array();
		if(empty($_POST['bewrite']))
		{
			$this->outputJson(-1, '礼包描述不能为空');
		}
		if(empty($_POST['mailtitle']))
		{
			$this->outputJson(-1, '礼包标题不能为空');
		}
		if(empty($_POST['context']))
		{
			$this->outputJson(-1, '请填写礼包内容');
		}
		$checkbox = $_POST['checkbox'];
	
		if(count($checkbox) >15)
		{
			$this->outputJson(-1, '附件勾选超过上限，请保留四个');
		}
	
		//__log_message("cdk gift :::".json_encode($checkbox),'gift-log');
	
		$indata = file_get_contents("php://input");
			
		//__log_message("cdk gift :::".json_encode($_POST),'gift');
	
		// all attachment type array
	
		if(empty($_POST['annex']))
		{
			$mail =new  Mail_Controller();
	
			$indataOut = $mail->attachmentConfig($checkbox);
	
			foreach ($indataOut as $var){
	
				$indataInfo[] = $var;
	
			}
		} else{
	
			$annex = explode("&",trim($_POST['annex']));
	
			foreach ($annex as $var )
			{
				$invarOut = explode(",",$var);
				if (!is_numeric($invarOut[0]) || !is_numeric($invarOut[1]))
				{
					$this->outputJson(-1,'批量附件类型有误');
				}
				$itemArrayList[] = ["ItemId"=>(int)$invarOut[0],"ItemNumber"=>(int)$invarOut[1]];
			}
	
		}
		if (count($itemArrayList)>0){
	
			$indataInfo = $itemArrayList;
		}
		$platform = Platfrom_Service::getServer(true,'globaldb');
	
		$data = array
		(
			'bewrite'=>$_POST['bewrite'],
			'title'=>$_POST['mailtitle'],
			'context'=>$_POST['context'],
			'datetime'=>date('Y-m-d H:i:s',time()),
			'ItemList'=>json_encode($indataInfo)
		);
		//__log_message("cdk gift :::".json_encode($indataInfo),'gift-log');
	
		$ret = CDK_Model::setgift($platform,$data);
		if ($ret)
		{
			//__log_message($ret,'cdk-log');
			
			$this->outputJson(0,$ret);
		}
		$this->outputJson(0,'礼包添加失败!');
	
		//
	}
	// 礼包码编辑
	public function gifedit(){
	
		$id    = $_POST['id'];
		$bewrite  = $_POST['bewrite'];
		$title = $_POST['title'];
		$context = $_POST['context'];
		$ItemList = $_POST['ItemList'];		 
		$datetime = $_POST['datetime'];
		
		if (!empty($ItemList))
		{
			$annex = explode("&",trim($ItemList));	
			
			if (!is_array($annex) || count($annex)<=0)
			{
				$this->outputJson(-1,'附件转换异常请检查填写格式 是否正确！');
			}
			foreach ($annex as $var )
			{
				if (empty($var))
				{
					continue;
				}
				$invarOut = explode(",",$var);
				
				if (!is_array($invarOut) || !is_numeric($invarOut[0]) || !is_numeric($invarOut[1]))
				{
					$this->outputJson(-1,'附件类型属性有误请检查填写属性类型！');
				}
				$itemArrayList[] = ["ItemId"=>(int)$invarOut[0],"ItemNumber"=>(int)$invarOut[1]];
			}
			if (count($itemArrayList)>0)
			{
				$ItemList = json_encode($itemArrayList);
				//$this->outputJson(-1,'变更类型奖励暂时不');
			}
		}
		$data = array
		(
			"bewrite"=>$bewrite,
			"title"=>$title,
			"context"=>$context,
			"ItemList"=>$ItemList, 
			"datetime"=>$datetime
		);
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
	
		$ret = CDK_Model::giftEdit($platfrom,$id,$data);
	
		if(!$ret)
		{
			$this->outputJson(-3, '编辑失败！');
		}
		$this->outputJson(0, '编辑用户成功！');
	}
	
	// 礼包码删除
	public function gifdel(){
	
		$id = $_POST['id'];
			
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
	
		$ret = CDK_Model::giftdelete($platfrom,$id);
	
		if(!$ret)
		{
			$this->outputJson(-3, '删除失败！');
		}
		$this->outputJson(0, '已成功删除！');
	}
	/**
	 * RETURN LIST
	 * **/
	public function giftlist()
	{
		//__log_message("giftlist",'gift');
		//__log_message("132131");
	
		/* if($_SESSION['setgift']===1 && !empty($_SESSION['nameout']))
		 {
		 $this->prompt2("录入成功!","on");
		 $indata =$_SESSION['nameout'];
		 $_SESSION['setgift'] = false;
		 $_SESSION['nameout'] = false;
		 }
		 elseif ($_SESSION['setgift']===2 && !empty($_SESSION['nameout']))
		 {
		 $this->prompt("失败!");
		 $_SESSION['setgift']=false;
		 $_SESSION['nameout']=false;
		 } */
			
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
	
		$pagesize = 20;
	
		$platfrom = Platfrom_Service::getServer(true,'globaldb');
		// $_GET['giftid'];
		$id= $_GET['giftid'];
		
		if (isset($_POST['sub']) || (isset($id) && !empty($id)))
		{ 
			// GIFTID
			$giftid = !empty($_POST['giftid'])
			?
			" id in({$_POST['giftid']})"
			:
			"";
			if ($id)
			{
				$giftid = " id in($id)";
			}
			$name = $_POST['name'];
	
			if (!empty($name))
			{
				$cdkCode = explode(",",$name);
				$cdkcont = count($cdkCode);
				foreach ($cdkCode as $IncdkCode)
				{
					if(!$IncdkCode){
						continue;
					}
					$cdkcode .="'".trim($IncdkCode)."',";
				}
	
				$giftname = $cdkcont>=2
				?
				'bewrite in('.substr($cdkcode,0,-1).')'
						:
						'bewrite = '.substr($cdkcode,0,-1);
			}
	
			$startTime = !empty($_POST['startTime'])
			?
			" datetime>='".$_POST['startTime']."'"
					:
					"";
	
			$endTime = !empty($_POST['endTime'])
			?
			" datetime<='".$_POST['endTime']."'"
					:
					"";
	
			$havingAry = array($giftid,$giftname,$startTime,$endTime);
	
			if(!empty($giftid) || !empty($name) ||
					!empty($startTime) || !empty($endTime)){
					
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
	
			$total = CDK_Model::Stat_giftTotal($platfrom,$indata);
			$_SESSION['gifthaving'] = $indata;
			$_SESSION['total'] = $total['cont'];
		}
		if ($_SESSION['total'] && $_SESSION['gifthaving'])
		{	
			$pagehtml = htmlspecialchars(Helper_Lib::getPageHtml($_SESSION['total'],
					$page,$pagesize));
		
			$list = CDK_Model::Stat_giftInfo($platfrom,$_SESSION['gifthaving'],$page,$pagesize);
				
			$data = array
			(
					'pagehtml'=>$pagehtml,
					'object'=>$list,
			);
		}	
		$this->load_view("dev/cdk/giftlist",$data);
	}
	
}
























