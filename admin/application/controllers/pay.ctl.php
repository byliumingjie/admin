<?php

/*
 * 充值
 */

class Pay_Controller extends Module_Lib {

	// 入口
    public function index()
    {	 
		$data = array();
		
		/* $data = [
		'platId'=>12,	
		'server_id'=>1,
		'RoleIndex'=>'340000000035',			
		'uid'=>'C2DC63C2854D296FEF20684526C10475',			
		'tcd'=>'d447367a00ce4809a2df7d014a3d07cc-test',			
		]; */
		 
		
		$data = [
			'platId'=>12,
			'server_id'=>1,
			'RoleIndex'=>'330000000817',
			'cbi'=>1,
			'uid'=>'7C517436D1DC9B78F5D1F1E4C1FAD5A9',
			'fee'=>600,
			'ssid'=>600,
			'tcd'=>'dfdda4ec5e674e4380c97ae4f25aa78e',
			'ssid'=>600
		];
		
		 //echo json_encode($data)."<br>";
		
		/*$inHeader =  $this->VerifyToken($data,null,'SaveMoneryInform',1);
		
		$code = 'SaveMoneryInform'; 
		 
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
		
		echo "<br>";
		
		//var_dump($inHeader);
		 
		echo ($ret)."<br>"; */
		
		/* $retOut = json_decode(trim($ret),true); 
		  
		$ret = Socket_Service::SendMsg($data,null,'SaveMoneryInform',4);
		if($ret){
			$dd = Socket_Service::RecvMsg();
			var_dump($dd);
		}
		else{
			
			echo "false-";
		} */
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			 echo "ok";
		}
		echo 'false';
		
    	$this->load_view('dev/pay/list', $data);
	}
	
}

















