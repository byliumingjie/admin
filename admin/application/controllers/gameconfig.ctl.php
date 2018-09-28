<?php
//活动管理模块

class GameConfig_Controller extends Module_Lib{
	
	private  $mode = null;
	public function __construct($conn='')
	{
		parent::__construct();
		$conn = Platfrom_Service::getServer(true,'globaldb');
		$this->mode = new GameConfig_Model($conn);
	}
	/**
	 * 活动列表信息
	 * **/
	public function PayConfigIndex()
	{
		$data = array();
		
		$InpayData = $this->mode->getPayConfig();
		
		
		if ( count($InpayData) >0  && !empty($InpayData))
		{
			$data['payObject'] = $InpayData;
			
		} 
		
		$this->load_view('gm/pay_config',$data);
	}
	public function editPayconfig()
	{		
		$id = $_POST['id'];
		$fee = $_POST['fee'];
		$diamond = $_POST['diamond'];
		$extra_diamond = $_POST['extra_diamond'];
		$if_special_get = $_POST['if_special_get'];
		$special_diamond = $_POST['special_diamond'];
		$product_id = $_POST['product_id'];
		
		$data = 
		[
		'fee'=>$fee,
		'diamond'=>$diamond,
		'extra_diamond'=>$extra_diamond,
		'if_special_get'=>$if_special_get,
		'special_diamond'=>$special_diamond,
		'product_id'=>$product_id,
		];
		
		$ret = $this->mode->editPayconfig($id, $data);
		
		if($ret){
			$this->outputJson(0,'充值配置更新成功!');
		}
		$this->outputJson(-1,'充值配置更新失败!');
	}
	
	// 付费配置删除
	public function delPayconfig()
	{
		$id = $_POST['id'];
		$ret = $this->mode->delPlatConfig($id);
		if($ret){
			$this->outputJson(0,'充值配置删除成功!');
		}
		$this->outputJson(-1,'充值配置删除失败!');
	}
	// 添加付费配置
	public function addPayconfig()
	{
		
		$fee = $_POST['fee'];
		$diamond = $_POST['diamond'];
		$extra_diamond = $_POST['extra_diamond'];
		$if_special_get = $_POST['if_special_get'];
		$special_diamond = $_POST['special_diamond'];
		$product_id = $_POST['product_id'];
		
		$data =
		[
		'fee'=>$fee,
		'diamond'=>$diamond,
		'extra_diamond'=>$extra_diamond,
		'if_special_get'=>$if_special_get,
		'special_diamond'=>$special_diamond,
		'product_id'=>$product_id,
		];
		
		$ret = $this->mode->addPayConfig($data);
		
		if($ret){
			$this->outputJson(0,'充值配置添加成功!');
		}
		$this->outputJson(-1,'充值配置添加失败!');
	}
	// 导入 道具配置
	public function ItemConfigIndex()
	{
		$this->load_view('gm/item_config');		
	}
	public function ImportItemConfg()
	{
		$row = (int)$_POST['row'];
		$clos = (int)$_POST['clos'];
		$filepath = $_POST['filepath'];
		$checkboxid = (int)$_POST['checkboxid'];
		//$loadplatform = $_POST['loadplatform'];
		//$settype = (int)$_POST['settype'];//0清楚数据库 重新导入 1追加
		$datainfo ='';
		$nameOut = array(array());
		$data = new excelreader();
		$data->setOutputEncoding('UTF-8');
		$data->read($filepath);
		$dataOut = array();
		// 以下是获取相关的数据全部的相关信息
		if($checkboxid>=0 && $row>=2 && $clos>=1)
		{
			for ($i = $row; $i<=$data->sheets[$checkboxid]['numRows']; $i++)
			{
				$strinfo = '';
				$trim = '';
				$Inrowdata = array();
				for ($j = $clos; $j <= 7; $j++)
				{
					$dat = $data->sheets[$checkboxid]['cells'][$i][$j];
					$trim .=$dat;
					$Inrowdata[] = $dat;
				}
				if(empty($trim)){
					continue;
				}
				$dataOut[] = $Inrowdata;
			}
		}
		$paydataOut = [] ;
		 
		foreach ($dataOut as $var)
		{
			if (empty($var) || count($var)<=0){
				continue;
			}
			$paydataOut[] =
			[
					'fee'=>(int)$var[1],
					'diamond'=>(int)$var[2],
					'extra_diamond'=>(int)$var[3],
					'if_special_get'=>(int)$var[4],
					'special_diamond'=>(int)$var[5],
					'product_id'=>'"'.$var[6].'"',
			];
		}
	
		if($this->mode->clostPayConfig())
		{
			$ret = $this->mode->addAllPayConfig($paydataOut);
			 
			unlink ($filepath);
			 
			if ($ret)
			{
				$this->outputJson(0,'充值配置导入成功!');
			}
			$this->outputJson(-1,'充值配置导入失败!');
		}
		$this->outputJson(-1,'清理失败！');
	}
	/**
	 * 活动列表创建入口 
	 * */
    public function CreateActivityIndex()
    {
    	$data = array(); 
    	
    	$Config = new  Config_Model();
    	// 测试用的
    	$itemList = $Config->getConfig('tb_suit_config');
    	$data['itemListObject'] = $itemList;
    	//
    	$data['activityinfo'] =$this->mode->getActivityCfgInfo();
    	 
    	// 编辑
    	if (isset($_POST['activityId']) && !empty($_POST['activityId']))
    	{
    		$activityId = (int) $_POST['activityId'];
    		
    		$data['EditActivitylist'] =$this->mode->getActivityList($_POST['activityId']);
    		
    		$this->load_view("dev/activity/edit",$data);
    		
    		 
    	}// 创建活动
    	else
    	{
        	$this->load_view("dev/activity/create",$data);
    	}
    } 
    // 活动删除
    public function delActivity()
    {
    	__log_message("del acitivity",'activity-log');
    	$id = $_POST['id'];
    	$serverId = $_POST['serverId'];
    	$code ='ServerActivityImport';
    	
    	$data = [
    	  'server_id'=>$serverId,
    	  'id_list'=>'',
    	  'delete_id_list'=>$id
    	];
    	$datajson = json_encode($data);
    	
    	__log_message($datajson,'gamesActivity-log');
    	
    	if($this->send($data, $code,$serverId))
    	{
	    	$ret = $this->mode->delActivity($id);
	    	
	    	if ($ret)
	    	{    		
	    		$this->outputJson(0,'活动删除成功');
	    	}
	    	$this->outputJson(-1,'活动删除失败');
    	}
    	 
    }
    
    public function getActivityTypeInfo($type)
    {
    	
    	$data =$this->mode->getActivityCfgInfo();
    	
    	foreach ($data as $var){
    		if ($var['id']==$type)
    		{
    			$activityRemarks = $var['remarks'];	
    			return $activityRemarks;
    			//??
    			break;
    		}
    	} 
    }
    /**
     * @method createActivity
     * add activity or edit activity
     * **/
    public function createActivity(){
    	$data = json_encode($_POST);
    	 
    	__log_message("activity-data info :".$data,'activity-log');
    	
    	if (isset($_POST['SetactivityType']))
    	{
    		$serverIdOut = $_POST['serverIdInfo'];
    		
    		$Inserver = Platfrom_Service::server_match_Plat(implode(',',$serverIdOut));
    		
    	}
    	else
    	{
	    	if (empty($_POST['platformId']))
	    	{
	    		$this->outputJson(-1,'请输入平台');
	    	}
	    	// 区服
	    	if (empty($_POST['server']))
	    	{
	    		$this->outputJson(-1,'请输入区服');
	    	}
    	}
    	// 活动名称
    	if (empty($_POST['title']))
    	{
    		$this->outputJson(-1,'请输入活动名称');
    	}
    	// 活动描述
    	if (empty($_POST['content']))
    	{
    		$this->outputJson(-1,'请输入活动描述');
    	}
    	
    	//活动类型
    	if (empty($_POST['activityType']))
    	{
    		$this->outputJson(-1,'请输入活动类型');
    	}
    	
    	$activityType = (int)$_POST['activityType'];
    	/* if (empty($activityType)){
    		$this->outputJson(-1,'请输入活动类型'.$activityType);
    		
    	} */
    	// 除了活动类型1(首冲活动)都需要开始截止关闭日期
    	
    	$starttime = (empty($_POST['starttime']))
    	?
    	0:
    	strtotime($_POST['starttime']);
    	
    	$endtime = (empty($_POST['endtime'])) 
    	? 
    	0:
    	strtotime($_POST['endtime']);
    	
    	$stoptime = (empty($_POST['stoptime'])) 
    	? 
    	0: 
    	strtotime($_POST['stoptime']);
    	
    	if (empty($starttime))
	    {
	    	$this->outputJson(-1,'请输入活动开始时间');
	    }
	    if (empty($endtime))
	    {
	    	$this->outputJson(-1,'请输入活动结束时间');
	    }
	    if (empty($stoptime))
	    {
	    	$this->outputJson(-1,'请输入活动关闭时间');
	    }
	    if($endtime <= time(0))
	    {
	    	$this->outputJson(-1, '结束时间不能小于当前时间');
	    }
	    if($stoptime <= $endtime)
	    {
	    	$this->outputJson(-1, '活动关闭时间，不能小于活动截止时间');
	    } 
    	// 重置日期只有每日有
    	if ($activityType==2 || $activityType==3 || $activityType ==5)
    	{
    		$ResetType = (int)$_POST['ResetType']; 
    		
    		if (empty($ResetType))
    		{
    			$this->outputJson(-1,'请输入重置时间类型');    			
    		} 
    		 
    		if (empty($_POST['ResetTime']))
    		{
    			$this->outputJson(-1,'请输入重置时间点');
    		}
    		
    		$ResetTime = (!is_numeric($_POST['ResetTime']))
    		?
    		$this->outputJson(-1,'请输入有效的整形时间点') : $_POST['ResetTime'];
    		
    		
    		if ($ResetType==1)
    		{
    			if ($ResetTime>36){
    				$this->outputJson(-1,'时间点数额超出限制,最高为36小时');
    			}    			
    		}
    		if ($ResetType==2)
    		{
    			
    			if ($ResetTime>24)
    			{
    				$this->outputJson(-1,'整点类型时间点异常,请以24小时制时间点作为重置时间');
    			}
    		} 
    	}
    	else
    	{
    		$ResetType = 0;
    		$ResetTime = 0;
    	} 
    	
    	$checkbox = $_POST['checkbox'];
    	
    	if(count($checkbox) <=0 )
    	{
    		$this->outputJson(-1, '配置条件不能为空,请点击设置');
    	}
    	 
    	$indataOut = NULL;
    	
    	$activityRemarks =$this->getActivityTypeInfo($activityType);
    	
    	switch ($activityType)
    	{ 
    		// 1:首冲类  2:每日单笔充值类 
    		// 3: 每日累积充值类 4:多日累积充值类 5: 每日累积消费类 6 :多日累积消费类
    		case 1:case 2:case 3:case 4:case 5:case 6:	
    		$indataOut = $this->attachmentConfig(
			$checkbox,[
			'prompt'=>['string',$activityRemarks],
			'number'=>['int','数额'],
			'NodeBewrite'=>['string','描述'],
			'itemList'=>['item','奖励配置'],
			'unique'=>['number','条件规则'],
			'frequency'=>['int','领取次数'],
			'intervene'=>[$activityType]
			]);
			break;
			// 7:冲榜类
			case 7:
			$indataOut = $this->attachmentConfig(
			$checkbox,[
			'prompt'=>['string',$activityRemarks],
			'type'=>['int','类型'],
			'NodeBewrite'=>['string','描述'],
			'IntervalStart'=>['int','开始区间'],
			'IntervalEnd'=>['int','截止区间'],
			'itemList'=>['item','奖励配置'],
			'unique'=>['type','条件规则'],
			'frequency'=>['int','领取次数'],
			'intervene'=>[$activityType]
			]);
			break;
			// 8:等级类
			case 8:
			$indataOut = $this->attachmentConfig(
			$checkbox,[
			'prompt'=>['string',$activityRemarks],
			'NodeBewrite'=>['string','描述'],
			'number'=>['int','等级'],
			'itemList'=>['item','奖励配置'],
			'unique'=>['number','条件规则'],
			'frequency'=>['int','领取次数'],
			'intervene'=>[$activityType]
			]);
			break;
			// 9:任务目标类
			case 9:
			$indataOut = $this->attachmentConfig(
			$checkbox,[
			'prompt'=>['string',$activityRemarks],
			'type'=>['int','类型'],
			'NodeBewrite'=>['string','描述'],
			'number'=>['string','目标数额格式'],
			'itemList'=>['item','奖励配置'],
			'unique'=>['type','条件规则'],
			'frequency'=>['int','领取次数'],
			'intervene'=>[$activityType]
			]);
			break;
			// 10 小游戏榜单类
			case 10:
			$indataOut = $this->attachmentConfig(
			$checkbox,[
			'prompt'=>['string',$activityRemarks],
			'type'=>['int','类型'],
			'NodeBewrite'=>['string','描述'],
			'IntervalStart'=>['int','开始区间'],
			'IntervalEnd'=>['int','截止区间'],
			'itemList'=>['item','奖励配置'],
			'unique'=>['type','条件规则'],
			'frequency'=>['int','领取次数'],
			'intervene'=>[$activityType]
			]);
			break;
    		default:break;return false; 
    	}
    	__log_message("indata out:".json_encode($indataOut),'activity-log');
    	
    	// 录入sql $Inserver
    	if (isset($_POST['SetactivityType']))
    	{
    		unset($data);
    		
    		foreach ($Inserver as $InserverId=>$InplatId)
    		{
    			
    		$rules = json_encode($indataOut);
	    	$data[] = array(
		    	'platformId'=>$InplatId,
		    	'serverId'=>$InserverId,
				'title'=>'"'.$_POST['title'].'"',
				'content'=>'"'.$_POST['content'].'"',
				'activityType'=>$_POST['activityType'],
				'starttime'=>$starttime,
				'endtime'=>$endtime,
				'stoptime'=>$stoptime,
				'ResetType'=>$ResetType,
				'ResetTime'=>$ResetTime,
				'rules'=>"'".$rules."'",
		    	'createtime'=>'"'.date('Y-m-d H:i:s',time()).'"'
				);
    		}
    	}
    	else{
    		
    		$data = array(
    		'platformId'=>$_POST['platformId'],
    		'serverId'=>$_POST['server'],
    		'title'=>$_POST['title'],
    		'content'=>$_POST['content'],
    		'activityType'=>$_POST['activityType'],
    		'starttime'=>$starttime,
    		'endtime'=>$endtime,
    		'stoptime'=>$stoptime,
    		'ResetType'=>$ResetType,
    		'ResetTime'=>$ResetTime,
    		'rules'=>json_encode($indataOut),
    		'createtime'=>date('Y-m-d H:i:s',time())
    		);
    		
    	}
    	if (!empty($_POST['editActivityId']) && $_POST['editActivityId']>0)
    	{
    		$setPrompt ='更新';
    		$code = '';
    		
    		$data['status'] = 0 ; 
    		$ret = $this->mode->activityEdit($_POST['editActivityId'],0,$data);
    		 
    	}
    	else
    	{
    		__log_message(" activity-log#".json_encode($data),'games-avtivity-log');
    		$setPrompt ='创建';
    		$ret = $this->mode->add_activity($data);
    	}
    	if ($ret)
    	{
    		$this->outputJson(0,'活动'.$setPrompt.'成功!');
    	}
    	$this->outputJson(-1,'活动'.$setPrompt.'失败!');
    	// 验证配置条件
    	
    	
    }
	/**
	 * 活动发布
	 * **/
    public function ReleaseActivity(){
    	
    	
    	$id = $_POST['id'];
    	$serverId = $_POST['serverId'];
    	
    	$code ='ServerActivityImport';
    	// 批量发布
    	if (isset($_POST['batchActivity'])&& !empty($_POST['batchActivity']))
    	{
    		__log_message(json_encode($_POST),'batch-activity-log');
    		if (empty($_POST['ReleaseActivityOut']) && 
    		count($_POST['ReleaseActivityOut'])<=0)
    		{
    			$this->outputJson(-1,'没有所要选的活动！');
    			
    		}
    		$ReleaseActivityOut = $_POST['ReleaseActivityOut'];
    		
    		foreach ($ReleaseActivityOut as $var)
    		{
    			
    			$dataOut = explode('-', $var); 
    			$id = (int)$dataOut[0];    			
    			$serverId = (int)$dataOut[1];
    			
    			$sidOut[$serverId] = $serverId;
    			$Inactivity[] = [$id,$serverId];
    		}
    		foreach ($sidOut as $insid)
    		{ 
    			$acitvityId = NULL;
    			foreach ($Inactivity as $inact)
    			{
    				if ((int)$insid == (int)$inact[1]){
    					 
    					if (empty($acitvityId))
    					{
    						$acitvityId = $inact[0];
    					}else{
    						$acitvityId .=','.$inact[0];
    					}    					
    				} 
    			}
    			$senddata[$insid] = $acitvityId;
    		} 
    		__log_message('senddata json:'.json_encode($senddata),'activity-log');
    		$retOut =NULL;
    		$ErrorOut=NULL;
    		foreach ($senddata as $InserverId=>$Invar)
    		{ 
    			$data = [
    				'server_id'=>$InserverId,
    				'id_list'=>$Invar,
    				'delete_id_list'=>''
    			];
    			
    			if($this->send($data, $code,$InserverId))
    			{
    				if($this->mode->activityEdit($Invar,1))
    				{
    					$retOut.= $InserverId.'区,活动ID为'.$Invar.'发布成功!';
    					//$this->outputJson(0,'发布成功');
    				}
    				else
    				{
    					$ErrorOut.= InserverId.'区,活动ID为'.$Invar.'发布记录日志失败!';
    				}
    				//$this->outputJson(0,'发布成功,记录变更失败');
    			}
    			else{
    				$this->mode->activityEdit($Invar,2);
    				$ErrorOut.= InserverId.'区,活动ID为'.$Invar.'发布失败!';
    			}
    			if (!empty($ErrorOut))
    			{
    				$this->outputJson(0,$ErrorOut);
    			}
    			else{
    				$this->outputJson(0,'发布成功!');
    			}
    		}
    	}else{
	    	// 当个发布
	    	if(empty($id))
	    	{
	    		$this->ouktputJson(-1,'活动ID为空！');
	    	}
	    	if(empty($serverId))
	    	{
	    		$this->outputJson(-1,'区服为空！');
	    	}
    	}
    	
    	$data = [
    		'server_id'=>$serverId,
    		'id_list'=>$id,
    		'delete_id_list'=>''
    	];
    	__log_message(json_encode($data),"activity-log");
    	
    	if($this->send($data, $code,$serverId))
    	{
    		if($this->mode->activityEdit($id,1))
    		{
    			$this->outputJson(0,'发布成功');
    		}
    		$this->outputJson(0,'发布成功,记录变更失败');
    	}
    	$this->mode->activityEdit($id,2);
    	$this->outputJson(0,'发布失败');
    	 
    }
    /**
     * 活动附件规则验证
     * **/
    public function  attachmentConfig($checkbox,$data=array())
    {
    	if (count($_POST)<0 || empty($_POST) || !isset($_POST)){
    			
    		$this->outputJson(-1,'无效的post类型');
    	}
    	if (empty($data) ||count($data)<=0)
    	{
    		$this->outputJson(-1,'条件配置无效!');	
    	}
    	
    	$indataTotal = NULL;
    	
    	__log_message('activity checkbox key info:'
    	.json_encode(array_keys($checkbox)),'activity-log');
    	if(!empty($checkbox))
    	{
    		for($i = 0; $i < count($checkbox);++$i)
    		{
    			if (empty($checkbox[$i]))
    			{
    				continue;
    			}
    			// 这里需要申明为什么我们需要一个checkbox最为有效验证而不是直接读取表单
    			// 因为在确定点击一个checkbox的时候以为这你必须要设置这一行的表单属性格式正确且不能为空
    			// 这样也避免了不必要的冗余验证,只有你确定点击了那个条件才进行验证并且处理相关数据    			
    			__log_message('checkbox'.$checkbox[$i],'activity-log');
    			
    			switch ($checkbox[$i])
    			{ 
    			case 'term1':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term2':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term3':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term4':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term5':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term6':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term7':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term8':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term9':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term10':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term11':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			case 'term12':
    				$indataTotal[$i] = $this->ActivitydataEach($data,$i);break;
    			default:$this->outputJson(-1,'无效附件');
    			}
    		} 
    		return $indataTotal;
    	}
    	$this->outputJson(-1,'条件不能为空！');
    }
    
    public function ActivitydataEach($data,$seq)
    {
    	$indata = array();
    	 
    	$indataTotal = array();
    	// 活动大类昵称
    	$prompt = $data['prompt'][1];
    	// 子类规则 type 下拉类别 还是 number
    	$unique = $data['unique'][0];
    	// 子类规则 描述
    	$uniqueDepict = $data['unique'][1];
    	// 获取干预类型项
    	$interveneType = (int)$data['intervene'][0];
    	// 获取下拉列表值或者表单number值 指其中一种根据  $data['unique']综合判断 
    	// 表单的值是12条规则,那么怎么才能相同POST name获取不同的值呢?这时候需要在视图表单内用数组命名    	
    	// 例如 <input type='text' name='XX[]'/> XX[]  XX按照表单规则可任意命名
    	
    	// 验证在12条规则中是否出现了相同规则 begin>>
    	if ($interveneType!=9)
    	{
	    	$uniqueType = $_POST[$unique];    	
	    	$uniqueOut =array();
	    	foreach ($uniqueType as $var)
	    	{    		
	    		if (empty($var) && $var<=0)
	    		{
	    			continue;
	    		}
	    		$uniqueOut[] =$var;
	    	}
	    	__log_message("unique :".json_encode($uniqueType),'activity-log');
	    	__log_message("unique".count($uniqueOut)."-".count(array_unique($uniqueOut)),'activity-log');    	
	    	
	    	if (count($uniqueOut)!=count(array_unique($uniqueOut)))
	    	{	
	    		$this->outputJson(-1,$prompt.$uniqueDepict.'存在重复条件规则请重试!');
	    	}
    	}
    	// <<验证在12条规则中是否出现了相同规则  END <<<<~~~~
    	
    	foreach ($data as $key=>$var)
    	{
    		if ($key=='prompt' || $key =='unique' || $key=='intervene')
    		{
    			continue;
    		}
    		
    		$$key = $_POST[$key][$seq];
    		
    		__log_message("activity key info: keys :::"
    		.json_encode(array_keys($_POST[$key])),'activity-log');
    		
    		__log_message('activity key info:'.$$key,'activity-log');
    		
    		if (empty($$key))
    		{
    			$this->outputJson(-1,'条件'.($seq+1).$prompt.$var[1].'不能为空！');
    		}
    		$indata[$key] = $this->checkVerify($$key,'条件'.($seq+1).$prompt,$var[0]);
    	}
    	return $indata ;
    	
    }
    
    public function checkVerify($val,$prompt,$type=NULL)
    {
    	$ArrayList =array();
    	
    	if ($type=='int')
    	{
    		if (!is_numeric($val)){
    			
    			$this->outputJson(-1, $prompt.'格式有误,请输入整数类型！');	
    		}    		
    	}
    	
    	if ($type == 'item')
    	{
    		$annex = explode("&",trim($val));
    			
    		foreach ($annex as $var )
    		{
    			$invarOut = explode(",",$var);
    			
    			if (empty($invarOut[0]) || !is_numeric($invarOut[0]))
    			{
    				$this->outputJson(-1,$prompt.'输入的道具Id格式有误！');	
    			}
    			if (empty($invarOut[1]) || !is_numeric($invarOut[1]))
    			{
    				$this->outputJson(-1,$prompt.'输入的道具数额格式有误！');
    			}
    			 
    			$ArrayList[] = ["ItemId"=>$invarOut[0],"ItemNumber"=>$invarOut[1]];
    		}
    		
    		return $ArrayList;
    		
    	}
    	if(empty($val))
    	{
    		$this->outputJson(-1, $prompt.'不能为空!');
    	}
    	
    	return  $val;
    }
    public function getItemInfo(){
    	
    	__log_message("道具信息配置::--",'game-activity-log');
    	$Config = new  Config_Model();
    	 
    	$itemList = $Config->getItemconfig();
    	
    	if (isset($_POST['itemtype']) && !empty($_POST['itemtype']))
    	{
    		//$this->outputJson(-1,'item config');
    		 
    		$itemtype = (int)$_POST['itemtype'];
    		$itemInfo = $_POST['itemlist'];
    		switch ($itemtype){	
    			case 1: // 表情包配置
    				$itemList = $Config->getfaceConfig();
    				break;
    			case 2: // 装备盘配置
    				$itemList =$Config->getequipConfig();
    				break;
    			case 3: // 道具配置
    				$itemList = $Config->getItemconfig();
    				break;
    			case 4: // 技能配置
    				$itemList = $Config->getSkillconfig();
    				break;
    			case 5: // 套装配置
    				$itemList = $Config->getConfig('tb_suit_config');
    				break;
    			default:
    				$itemList = null;
    				break;
    		} 
    	}
    	// test
    	if ($itemList)
    	{
    		$i = 0 ;
    		
    		$intimeOut = explode(";\n", $itemInfo);
    		
    		foreach ($intimeOut as $Intime)
    		{
    			if (empty($Intime))
    			{
    				continue;
    			}
    			$timeData[] = explode(',', $Intime);
    		}
    		//
    		foreach ($timeData as $itemcheck)
    		{
    			$ItemName = $itemcheck[0];
    			$ItemId = (int)$itemcheck[1];
    			$ItemNum = $itemcheck[2];
    			
    			$itemOut[$ItemId] = [$ItemId,$ItemName,$ItemNum];
    			$itemIdOut[] = $ItemId;
    		}
    		//
    		$tiemOptionHtml  = '';
    		foreach ($itemList as $var)
    		{
    			//checked='checked'
    			$id = (int)$var['id'];
    			$name = $var['name'];
    			$checkName = 'id'.$id;
    			$checkId = 'edituseropt';
    			
    			$checked = null;
    			
    			if (in_array($id, $itemIdOut))
    			{ 
    				$checked = "checked='checked'";
    				
    				$tiemOptionHtml .= "<li><span style='color: red'>{$itemOut[$id][1]}</span>
    				<div style='margin-top: 5px'>
    				<input type='text'
    				style='width: 30px'  value='{$itemOut[$id][2]}' id='itemnumbers' 
    				data-name='itemnumbers{$itemOut[$id][0]}' placeholder='数量'/>&nbsp;";
    				
    				$tiemOptionHtml.="<input name='idd[{$i}]' data-key='checkbox'
    				data-name={$itemOut[$id][1]}  value={$itemOut[$id][0]}  id={$checkId} 
    				class={$checkId} type='checkbox' {$checked} 
    				style='padding: 0px;margin:0px'/></div></li>";    				 
    				
    			}else{    				 
    				$tiemOptionHtml .= "<li><span style='color: #08c'>{$name}</span>
    				<div style='margin-top: 5px'>
    				<input type='text' style='width: 30px'  id='itemnumbers' 
    				data-name='itemnumbers{$id}' placeholder='数量'/>&nbsp;";
    				
    				$tiemOptionHtml.="<input name='idd[{$i}]' data-key='checkbox'
    				data-name={$name}  value={$id}  id={$checkId} 
    				class={$checkId} type='checkbox' {$checked} 
    				style='padding: 0px;margin:0px'/></div></li>
    				";    				 
    			}
    			
    			$i++;
    		} 
    		//$tiemOptionHtml ='<div id="testion"><h1>test1111<h1></div>';
    	}
    	
    	$rs['errcode'] =0;
    	$rs['msg'] = '';
    	$rs['itemlistinfohtml'] = $tiemOptionHtml;     	 
    	echo json_encode($rs);
    	exit;
    }
    public function setIteminfo()
    {
    	$itemInfo = $_POST['iteminfo'];
    	
    /* 	if (empty($_POST['iteminfo']) )
    	{
    		$this->outputJson(-1,'道具不能为空');
    	}
    	 */
    	$itemById  = $_POST['itemId'];
    	
    	$intimeOut = explode(";\n", $itemInfo);
    	
    	$itemjsonlist = NULL;
    	
    	foreach ($intimeOut as $Intime)
    	{
    		if (empty($Intime))
    		{
    			continue;
    		}
    		$timeData[] = explode(',', $Intime);
    	}
    	//
    	foreach ($timeData as $itemcheck)
    	{
    		$ItemName = $itemcheck[0];
    		$ItemId = (int)$itemcheck[1];
    		$ItemNum = $itemcheck[2];
    		  
    		$itemOut[] = $ItemId.','.$ItemNum; 
    	}    	
    	$itemjsonlist = implode('&', $itemOut);
    	
    	if (!empty($itemjsonlist))
    	{
	    	$rs['errcode'] =0;
	    	$rs['msg'] = '';
	    	$rs['IitemjsonList'] = $itemjsonlist;
	    	$rs['itemhtmlid'] = $itemById;
	    	echo json_encode($rs);
	    	exit;
    	}
    	$this->outputJson(-1,'道具列表解析失败!');
    }
    
    public function ImportactivityConfg()
    { 
    	$row = (int)$_POST['row'];
    	$clos = (int)$_POST['clos'];
    	$filepath = $_POST['filepath'];
    	$checkboxid = (int)$_POST['checkboxid'];
    	//$loadplatform = $_POST['loadplatform'];
    	//$settype = (int)$_POST['settype'];//0清楚数据库 重新导入 1追加    	
    	$datainfo ='';
    	$nameOut = array(array());
    	$data = new excelreader();
    	$data->setOutputEncoding('UTF-8');
    	$data->read($filepath);
    	$dataOut = array();
    	// 以下是获取相关的数据全部的相关信息
    	if($checkboxid>=0 && $row>=2 && $clos>=1)
    	{
    		for ($i = $row; $i<=$data->sheets[$checkboxid]['numRows']; $i++)
    		{
    			$strinfo = '';
    			$trim = '';
    			$Inrowdata = array();
    			for ($j = $clos; $j <= 7; $j++)
    			{
    				$dat = $data->sheets[$checkboxid]['cells'][$i][$j];
    				$trim .=$dat; 
    				$Inrowdata[] = $dat; 
    			} 
    			if(empty($trim)){
    				continue;
    			}
    			$dataOut[] = $Inrowdata;
    		}
    	} 
    	$paydataOut = [] ;
    	
    	foreach ($dataOut as $var)
    	{    		
    		if (empty($var) || count($var)<=0){
    			continue;
    		}
    		$paydataOut[] = 
    		[
    		'fee'=>(int)$var[1],
    		'diamond'=>(int)$var[2],
    		'extra_diamond'=>(int)$var[3],
    		'if_special_get'=>(int)$var[4],
    		'special_diamond'=>(int)$var[5],
    		'product_id'=>'"'.$var[6].'"',
    		];
    	}
    	 
    	if($this->mode->clostPayConfig())
    	{    	
    		$ret = $this->mode->addAllPayConfig($paydataOut);
    	
	    	unlink ($filepath);
	    	 
	    	if ($ret)
	    	{
	    		$this->outputJson(0,'充值配置导入成功!');
	    	}
	    	$this->outputJson(-1,'充值配置导入失败!');
    	}
    	$this->outputJson(-1,'清理失败！');
    }
    
    public function FileLoadDataVerify($data=array(),
    $rows,$serverId,$platId)
    {  
    	// ----------------------------------------
    	// 标题
    	// ----------------------------------------
    	$title = empty($data[1]) 
    	? 
    	$this->outputJson(-1,'标题不能为空 读取起始行'.$rows)
    	:
    	$data[1];
    	// ----------------------------------------
    	// 内容
    	// ----------------------------------------
    	$content = empty($data[2])
    	?
    	$this->outputJson(-1,'标题不能为空 读取起始行'.$rows) 
    	: 
    	$data[2]; 
    	// ----------------------------------------
    	// 活动类型
    	// ----------------------------------------    	
    	$activityType = empty($data[3])
    	?
    	$this->outputJson(-1,
    	'活动类型不能为空 读取起始行'.$rows)
    	:
    	$data[3];
    	// ----------------------------------------
    	// 存放活动类型配置表信息到 session
    	// ----------------------------------------
    	if (!isset($_SESSION['activityCfgList']))
    	{  
    		$InactivityCfg = 
    		Activity_Service::getActivityCfgInfo();
    		
    		foreach ($InactivityCfg as $var)
    		{
    		 $_SESSION['activityCfgList'][$var['id']] 
    		 = $var['remarks'];
    		}
    	} 
    	// ----------------------------------------
    	// 验证活动类型是否存在否是视为无效类型
    	// ---------------------------------------- 
    	if(!isset(
    	$_SESSION['activityCfgList'][$activityType])
    	)
    	{
    		$this->outputJson(-1,
    		'无效的活动类型活动 id为:'.$activityType);	
    	}
    	// ----------------------------------------
    	// 活动开始时间
    	// ----------------------------------------
    	$starttime = empty($data[4])
    	?
    	$this->outputJson(-1,'开始时间不能为空起始行'.$rows)
    	:
    	strtotime($data[4]);
    	// ----------------------------------------
    	// 活动结束时间
    	// ----------------------------------------
    	$endtime = empty($data[5])
    	?
    	$this->outputJson(-1,'结束时间不能为空起始行'.$rows)
    	:
    	strtotime($data[5]);
    	// ----------------------------------------
    	// 关闭开始时间
    	// ----------------------------------------
    	$stoptime = empty($data[6])
    	?
    	$this->outputJson(-1,'关闭时间不能为空起始行'.$rows)
    	:
    	strtotime($data[6]); 
    	// ----------------------------------------
    	// 时间验证
    	// ----------------------------------------
    	if($endtime <= time(0))
    	{
    		$this->outputJson(-1, '结束时间不能小于当前时间起始行'
    		.$rows);
    	}
    	if($stoptime <= $endtime)
    	{
    		$this->outputJson(-1,
    		'活动关闭时间,不能小于活动截止时间起始行'.$rows);
    	}
    	// ----------------------------------------
    	// 重置日期只有每日有
    	// ----------------------------------------    	
    	if ($activityType==2 || $activityType==3 || 
    	$activityType==5)
    	{
    		$ResetType = (int)$data[7];
    	
    		if (empty($ResetType))
    		{
    		  $this->outputJson(-1,'请输入重置时间类型起始行'
    		  .$rows);
    		}
    		 
    		if (empty($data[8]))
    		{
    		  $this->outputJson(-1,'请输入重置时间点起始行'
    		  .$rows);
    		}
    	
    		$ResetTime = (!is_numeric($data[8]))
    		?
    		$this->outputJson(-1,'请输入有效的整形时间点起始行'
    		.$rows) 
    		: 
    		$data[8];    	
    		if ($ResetType==1)
    		{
    			if ($ResetTime>36){
    				$this->outputJson(-1,
    				'时间点数额超出限制,最高为36小时起始行'.$rows);
    			}
    		}
    		if ($ResetType==2)
    		{ 
    			if ($ResetTime>24)
    			{
    				$this->outputJson(-1,
    				'整点类型时间点异常,请以24小时制时间点作为重置时间'
    				.$rows);
    			}
    		}
    	}
    	else
    	{
    		$ResetType = 0;
    		$ResetTime = 0;
    	}
    	// --------------------------------------------
    	// 规则配置
    	// --------------------------------------------
    	$InrulesList = []; 
    	
    	for($i=9;$i<=12;$i++)
    	{
    		$rulesOut = [];
    		
    		if (!empty($data[$i]))
    		{
    			$rulesOut = explode('&', $data[$i]);
    			 
    			// ************************************
    			$InrulesList[] = 
    			$this->checkFileDatVerify($rulesOut,
    			$activityType,$rows);
    			// ************************************
    		}else{ continue; }    		
    	}    	
    	if(count($InrulesList)<0 || empty($InrulesList))
    	{    		
    		$this->outputJson(-1,'条件规则不能为空');
    	}
    	// --------------------------------------------
    	// 集合数据流
    	// --------------------------------------------
	    $InmeetdataOut = 
	    [
    	"platformId"  => $platId,
		"serverId"    => $serverId,
		"title"       => "'".$title."'",
		"content"     => "'".$content."'",
		"activityType"=> $activityType,
		"starttime"   => $starttime,
		"endtime"     => $endtime,
		"stoptime"    => $stoptime,
		"ResetType"   => $ResetType,
		"ResetTime"   => $ResetTime,
		"rules"       => "'".json_encode($InrulesList)."'",
		"createtime"  => "'".date('Y-m-d H:i:s',time())."'"
   	 	];
    	return $InmeetdataOut;
    }
    /**
     * 文件数据验证
     * @param value $data 
     * @param string $type
     * ***/
    public function checkFileDatVerify($data,
    $activityType,$rows)
    {
    	$ArrayList = array();    	
    	foreach ($data as $Inrulesvar){
    		$minorOut = explode('=',trim($Inrulesvar));
    		switch ($minorOut[0])
    		{
    		// --------------------------------------------
    		// 活动子类类型
    		// --------------------------------------------
    		case 'type':
    			$type  = (empty($minorOut[1]) &&
    			($activityType == 7 || $activityType==10))
    			?
    			$this->outputJson(-1,'活动条件类型不能为空')
    			:
    			$minorOut[1];break;  			
	    	// --------------------------------------------
	    	// 活动条件数额配置
    		// --------------------------------------------
    		case 'number':    				
	    		$number  = empty($minorOut[1]) 
	    		? 
	    		$this->outputJson(-1,'条件数目为空') 
	    		: 
	    		$minorOut[1];
	    			
	    		if ($activityType == 7 || $activityType==10)
	    		{
	    		$Interval = explode('-', $number);
    			// 开始区间
    			$IntervalStart = (isset($Interval[0])
    			&& is_numeric($Interval[0]))
    			?
    			$Interval[0]
    			:
    			$this->outputJson(-1,'开始区间为空,或类型有误!');
    			// 截止区间
    			$IntervalEnd = (isset($Interval[1])
    			&& is_numeric($Interval[1]))
    			?
    			$Interval[1]
    			:
    			$this->outputJson(-1,'截止区间为空,或类型有误!');
	    		}break;
	    	// --------------------------------------------
	    	// 活动条件描述
    		// --------------------------------------------
    		case 'NodeBewrite': 
	    		$NodeBewrite = empty($minorOut[1])
	    		?
	    		$this->outputJson(-1,'配置条件描述不能为空')
	    		:
	    		$minorOut[1];break;
	    	// --------------------------------------------
	    	// 活动领取次数
    		// --------------------------------------------
    		case 'frequency': 
	    		$frequency = empty($minorOut[1])
	    		?
	    		$this->outputJson(-1,'领取次数不能空或者类型有误')
	    		:
	    		$minorOut[1]; 
	    		break;
	    	// --------------------------------------------
	    	// 活动奖励配置
    		// --------------------------------------------
    		case 'itemList': 
	    		$itemList = empty($minorOut[1])
	    		?
	    		$this->outputJson(-1,'奖励配置不能为空') 
	    		: 
	    		$minorOut[1];
	    		
	    		$invarOut = explode("#",$itemList);
	    			 
	    		foreach ($invarOut as $Initemvar )
	    		{
	    		$ItemOutlist = explode(',', trim($Initemvar));
	    		
    			if (empty($ItemOutlist[0]) 
    			|| !is_numeric($ItemOutlist[0]))
    			{
    				$this->outputJson(-1,
    				'输入的道具Id格式有误！'.$rows);
    			}
    			if (empty($ItemOutlist[1]) 
    			|| !is_numeric($ItemOutlist[1]))
    			{
    				$this->outputJson(-1,
    				'输入的道具数额格式有误！'.$rows);
    			}
    			$ArrayList[] = ["ItemId"=>$ItemOutlist[0]
    			,"ItemNumber"=>$ItemOutlist[1]];
	    		} break;
    			default:break;
    		} 
    	}    
    	// --------------------------------------------
    	// 活动冲榜类 与 小游戏类 参数( 需要区间配置 及 活动类型  )
    	// --------------------------------------------
    	if ($activityType == 7 
    	|| $activityType==10)
    	{
    		$rulesOut = 
    		[
    		'type'=>$type,
    		'NodeBewrite'=>$NodeBewrite,
    		'IntervalStart'=>$IntervalStart,
    		'IntervalEnd'=>$IntervalEnd,
    		'itemList'=>$ArrayList,
    		'frequency'=>$type,    				
    		];	
    		return $rulesOut;
    	}
    	// --------------------------------------------
    	// 活动任务目标类参数 ( 活动目标数额 及 活动类型 )
    	// --------------------------------------------
    	if($activityType==9)
    	{
    		$rulesOut =
    		[
    		'type'=>$type,
    		'NodeBewrite'=>$NodeBewrite,
    		'number'=>$number,    			
    		'itemList'=>$ArrayList,
    		'frequency'=>$type,
    		];
    		return $rulesOut;
    	}
    	// --------------------------------------------
    	// 其他活动类型默认配置参数
    	// --------------------------------------------
    	$rulesOut =
    	[    		
    		'NodeBewrite'=>$NodeBewrite,
    		'number'=>$number,
    		'itemList'=>$ArrayList,
    		'frequency'=>$type,
    	];
    	return $rulesOut;
    }
    /**
     * 文件上传解析 获取文件内容 此函数通过js去submit
     * 文件路径为www/static/js/usersupervise.js
     **/
    public function uploadfile()
    {
    	$pathname = $_FILES['myfile'];
    	
    	__log_message('file name : ' . $pathname['name'],'file-dir-log');
    	
    	$filename = time(0)."_".$pathname['name'].'.xls';
    	
    	__log_message('filename2 : ' . $pathname['name'],'file-dir-log');
    	 
    	
    	$filepath = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$filename;
    	
    	__log_message('filepath : ' . $filepath,'file-dir-log');
    	 
    	
    	$data = null;
    
    	if(is_uploaded_file($pathname['tmp_name']))
    	{
    		if(! move_uploaded_file($pathname['tmp_name'], $filepath))//上传文件，成功返回true
    		{
    			$this->outputJson(-1,"上传失败");
    		}
    	}else
    	{
    		$this->outputJson(-1,"非法上传文件");
    	}
    
    	$ext = pathinfo($pathname['name'], PATHINFO_EXTENSION | PATHINFO_FILENAME);
    
    	if(trim($ext)!= "xls" && trim($ext)!="xlsx")
    	{
    		$this->outputJson(-1,"文件格式错误");
    	}
    	 
    	if(trim($ext) == "xls" || trim($ext) =="xlsx" )
    	{
    		
    		$nameOut = array(array());
    		
    		$data = new excelreader();
    		
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
    	
    	__log_message('nameOut : ' . json_encode($nameOut),'file-dir-log');
    	$this->outputJson(0,$nameOut);
    }
    public function load_acitvity_mode(){
    	 
    	$modeName = $_POST['modeName'];
    	 
    	if($modeName)
    	{
    		$page = Config::get("common.page");
    		$acction = $page['host'].'/tmp/';
    		header("location:".$acction.$modeName);
    		//$this->load_view("stat_data",$data);
    	} 
    }
}
