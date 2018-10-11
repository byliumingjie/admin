<?php

//所有define 都放在这个文件
//当前平台
define('DEFAULT_PASS', '123456'); //默认密码

if (defined('SETDB') && 0 === SETDB) {
    define('PROTOBUF_DEBUG', 'debug');
} else {
    define('PROTOBUF_DEBUG', '');
}

define('DIR_SEPARATOR', "/");
define('LINUX_LOGDIRS', dirname(dirname(dirname(__FILE__))) . '/logs/'); // 日志目录
define('WINDOWS_LOGDIRS', dirname(dirname(dirname(__FILE__))) . '/logs/error.txt'); // 日志目录
define('LOGDIRS_FILENAME', 'error.log'); // 日志目录
define("LOG_SERVER_URL", dirname(dirname(dirname(__FILE__))) . "/logs/");

#error info
define('SUCCESS', 0);
define('FAILURE', -1);

#file upload url

define('RES_URL', 'http://192.168.181.133:9095');
define('RES_PATH', dirname(dirname(dirname($_SERVER['DOCUMENT_ROOT']))) . '/res/config/');
# config file path
//http://192.168.181.133/chuanqi_mofang12.json
$patch = RES_PATH;
