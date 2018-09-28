<?php
//$IP =  gethostbyname($_ENV['COMPUTERNAME']); 
define('IP', '192.168.0.140');
define('PORT', ':8057');

$_STATIC= "http://".gethostbyname($_ENV['COMPUTERNAME']).":8057/static";

/* db 配置 */
if (SETDB == 1) 
{
	// upload..
    $config['common.page']['host'] = 'http://'.IP.PORT;
    $config['common.page']['static'] = 'http://'.IP.PORT.'/static';
} else if (SETDB == 0) {
    // dev 
    $config['common.page']['host'] = 'http://'.IP.PORT;
    $config['common.page']['static'] = 'http://'.IP.PORT.'/static';
} else if (SETDB == 2) {
    // test  
    $config['common.page']['host'] = 'http://'.IP.PORT;
    $config['common.page']['static；'] = 'http://'.IP.PORT.'/static';
}

/*服务器配置*/
if (SETSERVER == 0)
{
	$config['common.page']['apihost'] = '0.0.0.0';	 
}
?>