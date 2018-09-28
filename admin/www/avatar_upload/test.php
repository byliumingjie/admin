<?php
 
	include_once "../include/lib/log.php";
	include_once "../include/lib/mysql.lib.php";
	
	$data = file_get_contents("http://172.168.2558.83/avatar_upload/index.php");
var_dump($data);
	if($data){
		var_dump($data);
	}
	echo "false";
	
	/*_log("datainfo:::".$data,'face-log');
	_log("datainfo::post :".json_encode($data),'face-log');
		_log("datainfo::_GET :".json_encode($_GET),'face-log');

_log("datainfo::_SERVER :".json_encode($_SERVER),'face-log');
	*/
/*$filedata = $GLOBALS[HTTP_RAW_POST_DATA];//得到post过来的二进制原始数据  
if(empty($filedata)){   
  $filedata=file_get_contents("php://input");  
} */  
/* 
PHP 2（第二个php文件） 
所要存放的远程服务器的php文件这个地方执行的就是从上面的curl传送过来的参数 
 
 if(file_put_contents('test1.png',$data))
 {
	_log("datainfo::: ok ",'face-log');
 } 

 if(file_put_contents('test2.png',base64_decode($data)))
 {
	_log("datainfo::: ok2 ",'face-log');
 }
 
if( file_put_contents('test3.png', $data, true)){
	_log("datainfo::: ok3 ",'face-log');
}

 

if($_FILES)  
{   

	_log("datainfo222:::",'face-log');
	$filename = $_FILES['file']['name'];   
	$tmpname = $_FILES['file']['tmp_name'];   

	$url = dirname(__FILE__).'/test4.png';  

	_log("files:::".json_encode($_FILES),'face-log');

	if( move_uploaded_file($tmpname,$url) )
	{   
			_log("datainfo::: ok4 ",'face-log');
			exit;
	}  

	_log("datainfo::: ok5 ",'face-log');
}  */ 
  
?>