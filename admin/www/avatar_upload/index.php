<?php 
ini_set('display_errors', '1');
include_once '../../include/lib/log.php';
include_once "../../include/lib/mysql.lib.php";
include_once "core.function.php";	


$TokenKey = '3VEIDXI7AGEEQQ7N79ROU9FXU14W9P92';

$dataOut = dataVerify($_GET);

$signStr =  'app='.$dataOut['app'].'&cbi='.$dataOut['cbi'].'&ct='.$dataOut['ct'].
'&fee='.$dataOut['fee'].'&pt='.$dataOut['pt'].'&sdk='.$dataOut['sdk'].'&ssid='.$dataOut['ssid'].'&st='.
$dataOut['st'].'&tcd='.$dataOut['tcd'].'&uid='.$dataOut['uid'].'&ver='.$dataOut['ver'];

$signEncrypt = md5($signStr.$TokenKey);

if($signEncrypt == $dataOut['sign'])
{
	$db = new  Mysqldb();

	$cbiOut = explode(',',$dataOut['cbi']); 

	$platId = (int)$cbiOut[0];
	$server_id = (int)$cbiOut[1];
	$RoleIndex = $cbiOut[2];
	$cbi = $cbiOut[3];
	
	$data = [
	'platId'=>$platId,	
	'server_id'=>$server_id,
	'RoleIndex'=>$RoleIndex,
	'cbi'=>$cbi,
	'uid'=>$dataOut['uid'],
	'fee'=>$dataOut['fee'],
	'ssid'=>$dataOut['ssid'],
	'tcd'=>$dataOut['tcd'],
	'ssid'=>$dataOut['fee']
	];

	$sendret = send($data,$db,'file load server set ok!','fileload server set false');
	$retOut = json_decode(trim($sendret),true); 
	
	if(isset($retOut['status']) && $retOut['status']==0)
	{
		_log(' sign is ok success! signEncrypt'.$signEncrypt.' #|# sign'.$dataOut['sign'],'1sdk-log');
		exit('SUCCESS');
	}
	exit('FAILURE');
}
_log(' sign is FAILURE ! signEncrypt'.$signEncrypt.' #|# sign'.$dataOut['sign'],'1sdk-log');
exit('FAILURE');
