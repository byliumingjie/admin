<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once 'include/lib/log.php';
include 'xxtea.lib.php';
//$postStr = file_get_contents("php://input");
$username = isset($_GET['userName'])?$_GET['userName']:0;
 
$ciphertext = new xxTea();
$wechatName = xxTea::decrypt($username);

_log('解密之后'.$wechatName,'wecatlog.account');

//_log("create menu false 加密之后".$userName.'----解密之后'.$wechatName,'wecatlog.account');
 
_log("post data account  postStr 11----".$wechatName,'wecatlog.account');


echo "<h2>账号绑定</h2>"."<br>";
?>
 
 