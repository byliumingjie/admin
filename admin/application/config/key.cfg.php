<?php
$config['key.gm.channel'] = array(
	1=>"网易",
	2=>"米喏斯后台",
	3=>"后台工具",
	4=>"米喏斯后台缓存",		
);
$config['key.gm'] = array
(
	1001=>"查询在线玩家",
	1002=>"踢下指定用户",
	4001=>"发送指定玩家公告",
	4011=>"禁止玩家发送聊天",
	4012=>"取消禁止玩家发送聊天",
	4013=>"查询禁言玩家列表",
	4014=>"禁言",
	4015=>"禁登陆",
	4016=>"解封",
	5001=>"个人邮件",
	5002=>"批量用户邮件",
	5003=>"批量全服邮件",
	6001=>"平台添加",
	6002=>"平台更改",
	6003=>"区服增加",
	6004=>"区服编辑",
	6005=>"区服删除",
	7001=>"头像审核通过",
	7002=>"头像审核拒绝",
	8001=>"走马灯发送",
	9001=>"邮件撤销",
	10001=>"走马灯撤销",
	20001=>"充值补单",
	 105=>"获取服务器信息",	
	 106=>"服务器录入",		
	 107=>"获取多个服务器信息",
	50001=>"开服",
	50002=>"关服",
	103=>"后台账号注册",
	104=>"后台账号编辑",
	105=>"后台账号删除",
);
// 请求配置
$config['key.gm.request.fields'] = array
(
	1001=>['desc'=>'查询在线玩家','playerId'=>'用户ID'],
	1002=>['desc'=>'踢下指定用户','playerId'=>'用户ID'],
	2001=>['desc'=>'增加道具','itemId'=>'道具ID','itemNum'=>'道具数量','playerId'=>'用户ID'],
	2002=>['desc'=>'扣除道具','itemId'=>'道具ID','itemNum'=>'道具数量','playerId'=>'用户ID'],
	3001=>['desc'=>'增加金币','moneyNum'=>'金币数量','playerId'=>'角色ID'],
	3002=>['desc'=>'增加钻石','moneyNum'=>'钻石数量','playerId'=>'角色ID'],
	3003=>['desc'=>'扣除金币','moneyNum'=>'金币数量','playerId'=>'角色ID'],
	3004=>['desc'=>'扣除钻石','moneyNum'=>'钻石数量','playerId'=>'角色ID'],
	4001=>['desc'=>'发送指定玩家公告','playerId'=>'角色ID','message'=>'公告内容','loopTimes'=>'公告循环次数'],
	105=> ['desc'=>'获取服务器信息'],
	106=> ['desc'=>'服务器录入','sid'=>'区服ID','sname'=>'区服名称','zoneserver_ip'=>'服务器IP','zoneserver_port'=>'服务器端口']	 
);
$config['key.gm.error'] = array
(		  0=>"操作成功",
	"-1001"=>"玩家不存在",
	"-1002"=>"玩家未在线",
	"-2001"=>"道具操作数量不正确", 
	"-2004"=>"道具不存在", 
	"-106"=> "服务器添加失败",
	"-107"=> "服务器获取失败",
	"-103"=> "账号注册失败",
	"-104"=> "账号编辑失败",
	"-105"=> "账号删除失败", 
	"-4014"=> "禁言失败",
	"-4015"=> "禁登陆失败",
	"-4016"=> "解封失败",
	"-6001"=> "平台添加失败",
	"-6002"=> "平台更改失败",
	"-6003"=> "区服增加失败",
	"-6004"=> "区服编辑失败",
	"-6005"=> "区服删除失败",
	"-7001"=> "头像审核通过失败",
	"-7002"=> "头像审核拒绝失败",
	"-8001"=> "邮件发送失败",
	"-1"=> "操作失败",		
);
$config['local.memcache'] = array
(
'host'=>'172.0.0.1',
'port'=>11211,
);
$config['key.order'] = array(
    '0' => '未支付',
    '1' => '待发货',
    '2' => '充值成功',
    '3' => '充值失败', 
    '4' => '人工补单成功',
    '5' => '人工补单失败',
);
$config['key.ordertype'] = array
(
	'1' => '测试订单',   	
	'2' => '正式订单',       
);

//渠道
$config['key.channel'] = array(    
    '1' => '37',
    '2' => 'xy',
	'3' => '九游',
	'4' => '啄木',
);
//道具
$config['key.item'] = array(    
    '2' => '礼包',
    '5' => '月卡',
);

$config['key.bag'] = array(
    '0' => '道具',
    '1' => '装备碎片',
    '2' => '英雄碎片',
    '3' => '饰品碎片',
    '4' => '材料',
    '5' => '材料碎片',
);
$config['key.equip'] = array(
    'white' => '白装',
    'green' => '绿装',
    'blue' => '蓝装',
    'purple' => '紫装',
);

//公告状态
$config['key.statu'] = array(
    '0' => '默认',    
    '1' => '最热',
    '2' => '最新',
);


//公告状态
$config['key.link'] = array(
    '0' => '无跳转',
    '1' => '充值',
    '2' => '商店',
    '3' => '神秘商店',
    '4' => '竞技场',
    '5' => '酒馆',
    '6' => 'boss',
    '7' => '超链接',
    '8' => '限时神将',
    '9' => '充值回馈',
    '10' => '消费回馈',
    '11' => '充值有礼',
    '12' => '充值豪礼',
    '13' => '这箱有礼',
    '14' => '等级回馈',
    '15' => '新手福利',
    '16' => '等级奖励',
    '17' => 'Vip福利',
    '18' => '限时兑换',
    '19' => '好钻成双',
    '20' => '全民闯关',
    '21' => '大宝藏',
    '22' => '登录礼包',
    '23' => '周礼包',
);

//活动类型
$config['key.activity'] = array(
    '4801' => 'ACT_RECHARGE',    
    '4802' => 'ACT_COST',
    '4803' => 'ACT_BEST_HERO',
    '4804' => 'ACT_RECHARGE_EVERYDAY',
    '4805' => 'ACT_RECHARGE_LASTDAY',
    '4806' => 'ACT_GOLD_CHESH',
    '4807' => 'ACT_TREASURE',
    '4808' => 'ACT_EXCHANGE',
    '4809' => 'ACT_DOUBLE',
    '4810' => 'ACT_CROSS_PVE',
    '4811' => 'ACT_LOGIN',
);
$config['key.activityname'] = array(
    '4801' => '充值',    
    '4802' => '消费',
    '4803' => '限时神将',
    '4804' => '每日充值奖励',
    '4805' => '连续充值奖励',    
    '4806' => '开金宝箱',
    '4807' => '限时寻宝',
    '4808' => '限时兑换',
    '4809' => '好钻成双',
    '4810' => '全民闯关',
    '4811' => '登录奖励',
);

$config['key.resourcetype'] = array
(    
    '206' => 'diamond',
    '207' => 'gold',
    '208' => 'talent',
    '209' => 'energy',
    '210' => 'props',
    '211' => 'FineSoul',
    '212' => 'ExcellentSoul',
    '213' => 'EpicSoul',
    '214' => 'LegendSoul',
    '215' => 'Equipment',
    '216' => 'VipLevel',
    '217' => 'TeamExp',
    '218' => 'HeroExp',
    '219' => 'Hero',
    '20001' => 'HeroExp_ID',
    '20002' => 'HeroExp_All',
    '20003' => 'souljade',
    '20004' => 'team_level',
    '20005' => 'honour',
    '20006' => 'dailytaskscore',
    '20007' => 'wrestprotect',
    '20008' => 'wrestcount',
    '20009' => 'rechargeamount',
    '20010' => 'vipprivilege',
    '20011' => 'worldchannal',
    '20012' => 'prestige',
    '20013' => 'equipjade',
    '20014' => 'testrecharge',
    '20015' => 'logindays',
    '20016' => 'pvptimes',
    '20017' => 'master_rest_count',
    '20018' => 'freesweeptimes',
    '20019' => 'addsweeptimes',
    '20020' => 'buysweeptimes',
    '20021' => 'journeycoin',
);
// 英雄
if (!empty($_SESSION['account']) && empty($_SESSION['hero.xml']))
{ 
	$_SESSION['hero.xml'] = 1;
	
	$xml = new DOMDocument();
	
	$page = Config::get("common.page");	
	// 英雄
	$herofiledir = $page['host']."/config/hero.xml";	
	  
	$xml->load($herofiledir);	
	$dom = $xml->getElementsByTagName("hero");
	
	if ($dom){
		foreach ($dom as $var)
		{
			$id = $var->getAttribute("id");
			$hero_name = $var->getAttribute("hero_name");
			$herodata[$id] = array("name"=>$hero_name);
		} 
		$_SESSION['hero.key.xml'] = $herodata;
	} 
	// 使魔 pet_id name
	$petfiledir = $page['host']."/config/pet.xml";
	
	if( $InpetObject = loadXml($petfiledir, "pet") )
	{	
		$id ='';$name = '';
		$petdata = array();
		foreach ($InpetObject as $Invar){
			$id = $Invar->getAttribute("pet_id");
			$name = $Invar->getAttribute("name");
			$petdata[$id] = array("name"=>$name);
		}
		unset($Invar);
		unset($InpetObject);
		$_SESSION['pet.key.xml'] = $petdata;
	}
	
	// 道具 item_id item_name
	$itemfiledir = $page['host']."/config/item.xml";
	if( $InitemObject = loadXml($itemfiledir, "item") )
	{
		$id ='';$name = '';
		$itemdata = array();
		foreach ($InitemObject as $Invar){
			$id = $Invar->getAttribute("item_id");
			$name = $Invar->getAttribute("item_name");
			$itemdata[$id] = array("name"=>$name);;
		}
		unset($Invar);
		unset($InitemObject);
		$_SESSION['item.key.xml'] = $itemdata;
	}
	
	// 圣冠 spirit_id name
	$spiritfiledir = $page['host']."/config/spirit.xml";
	 
	if($InspiritObject = loadXml($spiritfiledir, "spirit") )
	{
		$id ='';$name = '';
		$spiritdata = array();
		foreach ($InspiritObject as $Invar)
		{
			$id = $Invar->getAttribute("spirit_id");
			$name = $Invar->getAttribute("name");
			$spiritdata[$id] = array("name"=>$name);;
		}
		unset($Invar);
		unset($InspiritObject);
		$_SESSION['spirit.key.xml'] = $spiritdata;
	}	
	// 战斗  id  info 
	$battlefiledir = $page['host']."/config/battle.xml";	
	
	if($InbattleObject = loadXml($battlefiledir, "battle") )
	{
		$id ='';$name = '';
		$battledata = array();
		foreach ($InbattleObject as $Invar){
			$id = $Invar->getAttribute("id");
			$name = $Invar->getAttribute("info");
			$map_name = $Invar->getAttribute("map_name");
			$battledata[$id] = array("name"=>$name,"map_name"=>$map_name);
		}
		unset($Invar);
		unset($InbattleObject);
		$_SESSION['battle.key.xml'] = $battledata;
	}
	
}
// 英雄
if ($_SESSION['hero.key.xml'])
{ 
	$config['key.hero.xml'] = $_SESSION['hero.key.xml']; 
}
// 使魔
if ($_SESSION['pet.key.xml'])
{
	$config['key.pet.xml'] = $_SESSION['pet.key.xml'];
}
// 道具
if ($_SESSION['item.key.xml'])
{
	$config['key.item.xml'] = $_SESSION['item.key.xml'];
}
//圣冠
if ($_SESSION['spirit.key.xml'])
{
	$config['key.spirit.xml'] = $_SESSION['spirit.key.xml'];
}
// 战斗
if ($_SESSION['battle.key.xml'])
{
	$config['key.battle.xml'] = $_SESSION['battle.key.xml'];
}

function loadXml($file,$key)
{
	$xml = new DOMDocument();
	$xml->load($file);
	$dom = $xml->getElementsByTagName("$key");
	
	if (!empty($dom))
	{
		unset($xml);		
		return $dom;
	}
	__log_message("get xml info return null","xml.cfg.log");
	return false;	
}

//////////////LOAD XLS
/* $data = new excelreader();
$data->setOutputEncoding('UTF-8');
$filepath = $page['host']."/config/face_config.xls";
$data->read($filepath);

for ($i = 1; $i <= $data->sheets[$checkboxid]['numRows']; $i++)
{
	
	
} */

