<?php
//include 'include/lib/log.php';
 
function get_device_type()
{
	//全部变成小写字母
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$type = 'http://www.minosgame.com';
	//分别进行判断
	if(strpos($agent, 'iphone') || strpos($agent, 'ipad'))
	{
	 $type = 'https://fir.im/983b';
	}  
	if(strpos($agent, 'android'))
	{
	 $type = 'https://fir.im/pqsd';
	}
	return $type;
}
//http://m.minosgame.com/wechat/app.download.php
// http://m.minosgame.com/wechat/app.download.php
function create_menu_list(){
	$menudata =array
	(
	'button' => array (
		0 => array (
		'name' => '为何叼',
		'sub_button' => array (
				0 => array (
						'type' => 'view',
						'name' => '官网',
						'url' => 'http://www.minosgame.com',
				),
				1=> array (
						'type' => 'view',
						'name' => '游戏下载',
						'url' => 'http://www.minosgame.com',
				),
				2=> array (
						'type' => 'click',
						'name' => '游戏攻略',
						'key' => 'rselfmenu_0_1',
				),
					
				3=> array (
						'type' => 'click',
						'name' => '账号绑定',
						'key' => 'rselfmenu_0_2',
				),
		),
		),
		1 => array (
		'name' => '就是叼',
		'sub_button' => array (
				0 => array (
						'type' => 'click',
						'name' => '最新活动',
						'key' => 'rselfmenu_2_0',
				),
				1=> array (
						'type' => 'click',
						'name' => '福利礼包',
						'key' => 'rselfmenu_2_1',
				),
				2=> array (
						'type' => 'click',
						'name' => '宣传视频',
						'key' => 'rselfmenu_2_2',
				)
		),
		),
		2 => array (
		'name' => '一起叼',
		'sub_button' => array (
				0 => array (
						'type' => 'click',
						'name' => '表情包下载',
						'key' => 'rselfmenu_3_0',
				) ,
				1 => array (
						'type' => 'click',
						'name' => '有奖投稿',
						'key' => 'rselfmenu_3_1',
				)
				,
				2 => array (
						'type' => 'click',
						'name' => '玩家QQ群',
						'key' => 'rselfmenu_3_2',
				)
				,
				3 => array (
						'type' => 'click',
						'name' => 'BUG建议',
						'key' => 'rselfmenu_3_3',
				)
		),
		),
	),
	);
	return $menudata;
}