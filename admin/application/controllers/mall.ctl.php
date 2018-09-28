<?php
//活动管理模块

class Mall_Controller extends Module_Lib{
	
	private  $mode = null;
	public function __construct($conn='')
	{
		parent::__construct();
		$conn = Platfrom_Service::getServer(true,'globaldb');
		$this->mode = new  Mall_Model($conn);
		
	}
	/**
	 * 活动列表信息
	 * **/
	public function index()
	{
		$data = array();
		$page = empty($_GET['p']) ? 1 : $_GET['p'];
		$pagesize = 30;
		$showActivity = 0;  
		//  0-待发布  1-已发布  2-发布失败		
		if ( isset($_POST['sut']) )
		{
			$showActivity = 1; 
			$platId = !empty($_POST['platId'])
			?
			' platId ='.$_POST['platId']:NULL;
		
			$serverId = !empty($_POST['serverId'])
			?
			' platId ='. $_POST['serverId']:NULL;
			
			$title =  !empty($_POST['title'])
			?
			' title like "%'. $_POST['title'].'%"':NULL;
			 
			$strattime = !empty($_POST['title']) 
			? 
			'"'.$_POST['title'].'"':NULL;
			
			$endtime = !empty($_POST['endtime']) 
			? 
			'"'.$_POST['endtime'].'"':NULL;
			
			// 状态类型  0-待发布  1-已发布  2-发布失败	
			$wait = ' AND status = 0 ';
			$success  = ' AND status = 1 ';
			$failure  = '  AND status = 2 '; 
		
			$havingAry =[
			$platId,$serverId,
			$title,$strattime,
			$endtime];
		
			if(!empty($platId) || !empty($serverId))
			{
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
			// 待审核
			$_SESSION['activityhaving'] = $indata.$wait;		
			// 发布
			$_SESSION['activityAdopthaving'] = $indata.$success;		
			// 发布失败
			$_SESSION['activityRefusehaving'] = $indata.$failure;
		}
		if (!empty($_SESSION['activityhaving'])
		|| !empty($_SESSION['activityAdopthaving'])
		|| !empty($_SESSION['activityRefusehaving']))
		{ 
			/**++++++++++++++++++++++++++++++++++++++++++++++++**/
			// 待审核
			$activityWaitTotal = $this->mode->mallTotal(
			$_SESSION['activityhaving']);
			// 通过
			$activityAdopTotal = $this->mode->mallTotal(
			$_SESSION['activityAdopthaving']);
			// 拒绝
			$activityRefuseTotal = $this->mode->mallTotal(
			$_SESSION['activityRefusehaving']);
			
			/**++++++++++++++++++++++++++++++++++++++++++++++++**/			
			// 待审核
			$Untreatedhtml = htmlspecialchars(
			Helper_Lib::getPageHtml($activityWaitTotal['cont'],
			$page,$pagesize));
			
			$Untreatedlist = $this->mode->mallInfo(
			$_SESSION['activityhaving'],$page,$pagesize);
			
			/*||||||||||||||||||||||||||||||||||||||||||||||||||*/
			// 通过
			$Adoptdhtml = htmlspecialchars(
			Helper_Lib::getPageHtml($activityAdopTotal['cont'],
			$page,$pagesize));
		
			$Adoptlist = $this->mode->mallInfo(
			$_SESSION['activityAdopthaving'],$page,$pagesize);
			/*||||||||||||||||||||||||||||||||||||||||||||||||||*/
			// 拒绝
			$Refusehtml = htmlspecialchars(
			Helper_Lib::getPageHtml($activityRefuseTotal['cont'],
			$page,$pagesize));
		
			$Refuselist = $this->mode->mallInfo(
			$_SESSION['activityRefusehaving'],$page,$pagesize);
			/*||||||||||||||||||||||||||||||||||||||||||||||||||*/
			$data = 
			[
				'Untreatedpagehtml'=>$Untreatedhtml,
				'UntreatedList'=>$Untreatedlist,
				'Adoptdhtml'=>$Adoptdhtml,
				'Adoptlist'=>$Adoptlist,
				'Refusehtml'=>$Refusehtml,
				'Refuselist'=>$Refuselist,
				'showActivity'=>$showActivity,
			];
		}
		 
		$this->load_view("dev/mall/list",$data);
		
	}
	/**
	 * 活动列表创建入口 
	 * */
    public function create_mall_config_index()
    {
    	$data = array(); 
    	 
    	// 编辑
    	if (isset($_POST['activityId']) && !empty($_POST['activityId']))
    	{
    		$activityId = (int) $_POST['activityId'];
    	
    		$data['EditActivitylist'] =
    		$this->mode->getMallList($_POST['activityId']);
    		
    		 
    		$this->load_view("dev/mall/edit",$data);
    	
    		 
    	}else{
			$this->load_view("dev/mall/create",$data);
    	}
    } 
    // 活动删除
    public function delMall()
    {
    	__log_message("del acitivity",'activity-log');
    	$id = $_POST['id'];
    	$serverId = $_POST['serverId'];
    	$code ='SecondRecharge';
     
    	$InmallListOut = $this->mode->getMallList((int)$id);
    	
    	if (empty($InmallListOut)  || count($InmallListOut)<=0)
    	{
    		$this->outputJson(-1,'没有获取指定商城推荐信息');
    	}
    	foreach ($InmallListOut as $var)
    	{
	    	$IndataOut =
	    	[
	    		'platId'=>(int)$var['platId'],
	    		'serverId'=>(int)$var['serverId'],
	    		'startTime'=>(int)$var['startTime'],
	    		'endTime'=>(int)$var['endTime'],
	    		'rules'=>[],
	    	]; 
    	}
    	
    	$datajson = json_encode($data);
    	 
    	if($this->send($IndataOut, $code,$IndataOut['serverId']))
    	{
	    	$ret = $this->mode->delMall($id);
	    	
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
    public function createMall()
    {
    	$data = json_encode($_POST);
    	
    	$IndataOut = [];
    	
    	__log_message("activity-data info : ".$data,'activity-log');
    	
    	if (isset($_POST['SetMall']))
    	{
    		$serverIdOut = $_POST['serverIdInfo'];
    		
    		$Inserver = Platfrom_Service::server_match_Plat(
    		implode(',',$serverIdOut));    		
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
    	$title = $_POST['title'];
    	// 活动名称
    	if (empty($title))
    	{
    		$this->outputJson(-1,'请输入活动名称');
    	}
    	$content = $_POST['content'];
    	// 活动描述
    	if (empty($content))
    	{
    		$this->outputJson(-1,'请输入活动描述');
    	}    	
    	$malltype = $_POST['malltype'];
    	// 活动名称
    	if (empty($malltype))
    	{
    		$this->outputJson(-1,'请输入推荐类型');
    	}
    	 
    	$starttime = $endtime = 0 ;
    	
    	if ($malltype==1)
    	{
    		$starttime = (empty($_POST['starttime']))
    		?
    		0:
    		strtotime($_POST['starttime']);
    		 
    		$endtime = (empty($_POST['endtime']))
    		?
    		0:
    		strtotime($_POST['endtime']);
    		
	    	if (empty($starttime))
		    {
		    	$this->outputJson(-1,'请输入活动开始时间');
		    }
		    if (empty($endtime))
		    {
		    	$this->outputJson(-1,'请输入活动结束时间');
		    }	    
		    if($endtime <= time(0))
		    {
		    	$this->outputJson(-1, '结束时间不能小于当前时间');
		    }
    	}
    	$indataOut = NULL;
    	 
    	$cost = empty($_POST['cost']) ? NULL : $_POST['cost'];
    	
    	$reward = empty($_POST['reward']) ? NULL : $_POST['reward'];
    	
    	$rulelist = $this->ruleVerify($cost,$reward,$malltype);
    	
    	// 创建 & 发送数据
    	if (isset($_POST['SetMall']))
    	{
    		$IndataOut = [];
    	
    		foreach ($Inserver as $InserverId=>$InplatId)
    		{
    			$IndataOut[] =
    			[
    			'platId'=>$InplatId,
    			'serverId'=>$InserverId,
    			'startTime'=>$starttime,
    			'endTime'=>$endtime,
    			'rules'=>"'".json_encode($rulelist)."'",
    			'createtime'=>"'".date('Y-m-d H:i:s',time())."'",
    			'title'=>"'".$title."'",
    			'description'=>"'".$content."'",
    			'status'=>0,
    			'type'=>$malltype
    			];
    		}
    	}
    	else
    	{
    		$IndataOut =
    		[
    			'platId'=>$_POST['platformId'],
    			'serverId'=>$_POST['server'],
    			'startTime'=>$starttime,
    			'title'=>$title,
    			'description'=>$content,
    			'createtime'=>date('Y-m-d H:i:s',time()),
    			'endTime'=>$endtime,
    			'status'=>0,
    			'rules'=>json_encode($rulelist),
    			'type'=>$malltype
    		];
    	}
    	// 编辑
    	if (isset($_POST['editMallId']))
    	{
    		if($this->mode->edit_Mall((int)$_POST['editMallId'],$IndataOut))
    		{
    			$this->outputJson(0,'变更成功!');
    		}
    	}
    	else // set 
    	{
    		if ($this->mode->add_mall($IndataOut)){
    			$this->outputJson(0,'配置成功!');
    		}
    	}
    	$this->outputJson(-1,'设置失败!');
    }
    // 规则验证
    public function ruleVerify($cost,$reward,$malltype)
    { 
    	if ($malltype == 1)
    	{
    		if (empty($cost) ||  empty($reward))
    		{
    			$this->outputJson(-1,'配置条件不能为空');
    		}
    		
    		$costListOut = $this->exploade_item_config($cost);
    		
    		$rewardListOut = $this->exploade_item_config($reward);
    		
    		if (count($rewardListOut)>0)
    		{
    			return ['costList'=>$costListOut,
    			'rewardList'=>$rewardListOut];
    		}
    		$this->outputJson(-1,'配置1异常为空!');
    		
    	}
    	else
    	{
    		if (empty($reward))
    		{
    			$this->outputJson(-1,'配置条件1不能为空');
    		}
    		$rewardListOut = $this->exploade_item_config($reward);
    		
    		if (count($rewardListOut)>0)
    		{
    			return ['rewardList'=>$rewardListOut];
    		}
    		$this->outputJson(-1,'配置2异常为空!');    		
    	}
    }
    
    public function exploade_item_config($itemlist)
    {
    	$dataOut = explode('&', $itemlist);
    	
    	if (count($dataOut)<=0)
    	{
    		$this->outputJson(-1,'配置解析失败');
    	}
    	$itemListOut = [];
    	    	
    	foreach ($dataOut as $Invar)
    	{
    		if (empty($Invar))
    		{
    			 
    			continue;
    		}
    		$Iteminfo = explode(',', $Invar);
    		 
    		$itemId  = (int)$Iteminfo[0];
    		$itemNum = (int)$Iteminfo[1];
    		 
    		if (!empty($itemId) && !empty($itemNum))
    		{
    			$itemListOut[] = ['ItemId'=>$itemId,'ItemNumber'=>$itemNum];
    		}
    		else
    		{
    			$this->outputJson(-l,'配置格式有误,请检查属性是否为空');
    		}
    	}
    	return $itemListOut;
    }
	/**
	 * 商城推荐发布
	 * **/
    public function ReleaseMall(){
    	 
    	$id = $_POST['id'];
    	$serverId = $_POST['serverId'];
    	
    	$code ='SecondRecharge';
    	// 批量发布
    	if (isset($_POST['batchMall']) 
    	&& !empty($_POST['batchMall']))
    	{
    		__log_message(json_encode($_POST),'batch-activity-log');
    		
    		if (empty($_POST['ReleaseMallyOut']) && 
    		count($_POST['ReleaseMallyOut'])<=0)
    		{
    			$this->outputJson(-1,'没有所要选的活动！');
    			
    		}
    		$ReleaseMallOut = $_POST['ReleaseMallyOut'];
    		
    		$mallId = implode(",",$ReleaseMallOut);
    		
    		$InmallListOut = $this->mode->getMallList($mallId);
    		
    		if (empty($InmallListOut)  || count($InmallListOut)<=0)
    		{
    			$this->outputJson(-1,'没有获取指定商城推荐信息');    			
    		}
    		
    		__log_message('senddata json:'.json_encode($senddata),'activity-log');
    		$retOut =NULL;
    		$ErrorOut=NULL;
    		foreach ($InmallListOut as $var)
    		{ 
    			$IndataOut =
	    		[	    			
	    			'platId'=>(int)$var['platId'],
	    			'serverId'=>(int)$var['serverId'],
	    			'startTime'=>(int)$var['startTime'],
	    			'endTime'=>(int)$var['endTime'],
	    			'type'=>(int)$var['type'],
	    			'rules'=>json_decode(trim($var['rules']),true),
	    		]; 
	    		__log_message($var['rules'],'mall-log');
	    		__log_message(json_encode($IndataOut),'mall-log');
    			
    			if($this->send($IndataOut, $code,$IndataOut['serverId']))
    			{
    				if($this->mode->MallEdit($var['id'],1))
    				{
    					$retOut.= $InserverId.'区,活动ID为'.$Invar.'发布成功!';
    				}
    				else
    				{
    					$ErrorOut.= InserverId.'区,活动ID为'.$Invar.'发布记录日志失败!';
    				}
    			}
    			else{
    				$this->mode->MallEdit($var['id'],2);
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
	    	// 单个发布
	    	if(empty($id))
	    	{
	    		$this->ouktputJson(-1,'商城推荐ID为空！');
	    	}
	    	if(empty($serverId))
	    	{
	    		$this->outputJson(-1,'商城推荐区服为空！');
	    	}
    	} 
    	
    	// 组装 
    	$dataOut = $this->mode->getMallList($id);
    	
    	$IndataOut = NULL;
    	
    	if (empty($dataOut) || count($dataOut)<=0)
    	{
    		$this->outputJson(-1,'发布的商城推荐无效查询不到!');    		
    	}
    	
    	foreach ($dataOut as $var)
    	{
    		$IndataOut =
    		[    			
    			'platId'=>(int)$var['platId'],
    			'serverId'=>(int)$var['serverId'],
    			'startTime'=>(int)$var['startTime'],
    			'endTime'=>(int)$var['endTime'],
    			'type'=>(int)$var['type'],
    			'rules'=>json_decode($var['rules'],true),
    		];    		
    	}
    	 
    	if($this->send($IndataOut,$code,$IndataOut['serverId']))
    	{
    		if($this->mode->MallEdit($id,1))
    		{
    			$this->outputJson(0,'发布成功');
    		} 
    	}
    	$this->mode->MallEdit($id,2);
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
    		foreach ($itemList as $var)
    		{
    			$id = $var['id'];
    			$name = $var['name'];
    			$checkName = 'id'.$id;
    			$checkId = 'edituseropt';
    			
    			$tiemOptionHtml .= "<li>{$name} 数量 :<input type='text'
    			style='width: 20px'  id='itemnumbers' data-name='itemnumbers'/>";
    			$tiemOptionHtml.="<input name='idd[{$i}]' data-key='checkbox'
    			data-name={$name} value={$id}  id={$checkId} class={$checkId} type='checkbox'/>添加</li>";
    			
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
    
    public function ImportactivityConfg()
    {
    	__log_message('file dataOut: 
    	ImportactivityConfg','file-dir-log');
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
    			for ($j = $clos; $j <= 21; $j++)
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
    	// 解析活动配置		 
    	foreach ($dataOut as $key=>$Invar){
    		//platformId 
    		$serverId = empty($Invar[0])
	    	?
	    	$this->outputJson(-1,'区服不能为空 读取起始行'.$key) 
	    	: 
	    	trim($Invar[0]);
    		
    		$sidCont = count(explode(',', $serverId));
    		
    		// 录入批量区服信息
    		$InserverOut =
    		Platfrom_Service::server_match_Plat($serverId);
    		
    		__log_message('sid count '.count($sidCont).' platcont : '.
    		count($InserverOut) ,'game-activity-log');
    		
    		if (!empty($serverId) && 
    		count($InserverOut)>0 
    		&& ($sidCont == count($InserverOut)))
    		{
	    		foreach ($InserverOut as $insid=>$inplatId)
	    		{
					$retOut[] = $this->FileLoadDataVerify(
					$Invar,(int)$key,$insid,$inplatId);
	    		}
    		}
    		else
    		{
    			$this->outputJson(-1,'无效区服,或格式有误 起始行'.$key);
    		}
    	}	 
    	 
    	__log_message('retOut : '.json_encode($retOut),'game-activity-log');
    	
    	// inserBrghit = ($retOut);
    	$ret = $this->mode->add_activity($retOut);
    	
    	//删除所上传的文件
    	unlink ($filepath);
    	
    	if ($ret)
    	{ 
    		$this->outputJson(0,'活动导入成功!');
    	}
    	$this->outputJson(-1,'活动导入失败!');
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
    
    	__log_message('file ext'.trim($ext),'file-dir-log');
    
    	if(trim($ext)!= "xls" && trim($ext)!="xlsx")
    	{
    		$this->outputJson(-1,"文件格式错误");
    	}
    	 
    	if(trim($ext) == "xls" || trim($ext) =="xlsx" )
    	{
    		__log_message('file ext 1','file-dir-log');
    		$nameOut = array(array());
    		__log_message('file ext 2','file-dir-log');
    		$data = new excelreader();
    		__log_message('file ext 3','file-dir-log');
    		$data->setOutputEncoding('UTF-8');
    		__log_message('file ext 4','file-dir-log');
    		$data->read($filepath);
    		__log_message('file ext 5','file-dir-log');
    		 
    		__log_message('file data info '.json_encode($data->boundsheets),'file-dir-log');
    		// 获取execl shell单元格属性
    		$j = 0;
    		foreach($data->boundsheets as $var)
    		{
    				
    			//__log_message("datrainfoo:::Name:::".$var['name']);
    			$nameOut[$j]["id"] = $j;
    			$nameOut[$j]["name"] = $var['name'];
    			$j++;
    		}
    		__log_message('file ext 6','file-dir-log');
    		//文件存放路径
    		$nameOut[0]["filepath"] = $filepath;
    		 
    		$datainfo ='';
    		// 以下是获取相关的数据全部的相关信息
    	}
    	//删除所上传的文件
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
