<?php
 
# Gm Config info by liumingjie add 2016-06-21 配置信息
/**
 * 配置函数 信息 db的链接信息是对应的全局数据库，自全局数据库获取对应所区服的编号信息
 * **/
$config['key.gm'] = array
(
	1001=>array("des"=>"查询指定用户",'function'=>'request',"code"=>1001,'conn'=>$dbLink),
	1002=>array("des"=>"踢下指定用户",'function'=>'request',"code"=>1002,'conn'=>$dbLink),	
	1011=>array("des"=>"英雄",'function'=>'request',"code"=>1011,'conn'=>$dbLink),
	1012=>array("des"=>"使魔",'function'=>'request',"code"=>1012,'conn'=>$dbLink),
	1013=>array("des"=>"圣冠",'function'=>'request',"code"=>1013,'conn'=>$dbLink),
	1014=>array("des"=>"好友",'function'=>'request',"code"=>1014,'conn'=>$dbLink),
	1015=>array("des"=>"战斗记录",'function'=>'request',"code"=>1015,'conn'=>$dbLink),
	1016=>array("des"=>"战斗详情",'function'=>'request',"code"=>1016,'conn'=>$dbLink),
	1021=>array("des"=>"玩家前500排行数据",'function'=>'request',"code"=>1021,'conn'=>$dbLink),
	2001=>array("des"=>"增加道具",'function'=>'request',"code"=>2001,'conn'=>$dbLink),
	2002=>array("des"=>"扣除道具",'function'=>'request',"code"=>2002,'conn'=>$dbLink),
	3001=>array("des"=>"增加金币",'function'=>'request',"code"=>3001,'conn'=>$dbLink),
	3002=>array("des"=>"增加钻石",'function'=>'request',"code"=>3002,'conn'=>$dbLink),
	3003=>array("des"=>"扣除金币",'function'=>'request',"code"=>3003,'conn'=>$dbLink),
	3004=>array("des"=>"扣除钻石",'function'=>'request',"code"=>3004,'conn'=>$dbLink),
	4001=>array("des"=>"发送指定玩家公告",'function'=>'request',"code"=>4001,'conn'=>$dbLink),
	4011=>array("des"=>"禁止玩家发送聊天",'function'=>'request',"code"=>4011,'conn'=>$dbLink),
	4012=>array("des"=>"取消禁止玩家发送聊天",'function'=>'request',"code"=>4012,'conn'=>$dbLink),
	4013=>array("des"=>"查询禁言玩家列表",'function'=>'request',"code"=>4013,'conn'=>$dbLink),
	5001=>array("des"=>"发送邮件",'function'=>'request',"code"=>5001,'conn'=>$dbLink),
	6001=>array("des"=>"抽奖设置",'function'=>'request',"code"=>6001,'conn'=>$dbLink),
	6002=>array("des"=>"抽奖获取",'function'=>'request',"code"=>6002,'conn'=>$dbLink),
	105=>array("des"=>"获取服务器信息",'function'=>'getServer',"code"=>105,'conn'=>$dbLink),
	106=>array("des"=>"服务器录入",'function'=>'addServer',"code"=>106,'conn'=>$dbLink),
	107 =>array("des"=>"获取多个服务器信息",'function'=>'getServer',"code"=>107,'conn'=>$dbLink),	
	110 =>array("des"=>"更改区服配置",'function'=>'editServer',"code"=>110,'conn'=>$dbLink),
);

$config['key.gm.error'] = array
(
	"11001"=>array("des"=>"玩家不存在"),
	"11002"=>array("des"=>"玩家未在线"),
	"22001"=>array("des"=>"道具操作数量不正确"),
	"22002"=>array("des"=>"道具添加出错"),
	"22003"=>array("des"=>"道具扣除出错"),
	"22004"=>array("des"=>"道具不存在"),
	"33001"=>array("des"=>"货币操作数量不正确"),
	"40001"=>array("des"=>"操作类型输入错误"),
	"40002"=>array("des"=>"当前的限时任务超过上限"),
	"40003"=>array("des"=>"结束时间输入错误"),
	"40004"=>array("des"=>"未找到该抽卡配置"),
	"40005"=>array("des"=>"开始时间输入错误"),
	"40006"=>array("des"=>"当前限时抽卡类型已重复"),
	"40007"=>array("des"=>"延时任务数量已达上限"),
	"40008"=>array("des"=>"没有此延时任务"),
	"40009"=>array("des"=>"没有此正在开启状态的限时活动"),
	"-106"=>array("des"=>"服务器添加失败"),
	"-107"=>array("des"=>"服务器获取失败"),
	"-110"=>array("des"=>"服务器编辑失败"),		
);