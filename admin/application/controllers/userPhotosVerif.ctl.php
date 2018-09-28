<?php

/**
 * 
 * 用户头像审核
 * @author Administrator
 ***/

class UserPhotosVerif_Controller extends Module_Lib {
  	
	private  $mode = null;
	 
	public function __construct()
	{
		parent::__construct();
		$connCfg = Platfrom_Service::getServer(true,'globaldb');
 		$this->mode = new  UserPhotos_Model($connCfg);
	}
	
    public function index()
    {    
    	$data = array(); 
    	$page = empty($_GET['p']) ? 1 : $_GET['p']; 
    	$pagesize = 18;
    	$showUserPhotos = 0;
    	if ( isset($_POST['sut']) )
    	{ 
    		$showUserPhotos = 1;
    		
	    	$platId = !empty($_POST['platId']) 
	    	? 
	    	' platformId ='.$_POST['platId']:'';
	    	
	    	if ((int)$_POST['serverId']!=0)
	    	{
	    		$serverId = !empty($_POST['serverId']) 
	    		? 
	    		'ServerId ='. $_POST['serverId']:'';
	    	}
	    	else{
	    		$serverId = NULL;
	    	}
	    	 
	    	// 审核类型	    	
	    	$type = ' and  type = 1 ';
	    	$AdoptType = ' and  type = 2 ';
	    	$RefuseType = ' and type = 3 ';
	    	
	    	$havingAry = array($platId,$serverId);
	    	
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
	    	$_SESSION['facehaving'] = $indata.$type;
	    	
	    	// 通过
	    	$_SESSION['faceAdopthaving'] = $indata.$AdoptType;
	    	
	    	// 拒绝
	    	$_SESSION['faceRefusehaving'] = $indata.$RefuseType; 
    	}
    	if (!empty($_SESSION['facehaving']) 
    	|| !empty($_SESSION['faceAdopthaving']) 
    	|| !empty($_SESSION['faceRefusehaving']))
    	{
			// 待审核	    	
	    	$faceTotal = $this->mode->rolefaceTotal($_SESSION['facehaving']);	     
	    	// 通过	    	
	    	$faceAdoptTotal = $this->mode->rolefaceTotal($_SESSION['faceAdopthaving']);	    	 
	    	// 拒绝	    	
	    	$faceRefuseTotal = $this->mode->rolefaceTotal($_SESSION['faceRefusehaving']);
    		// 待审核
	    	$Untreatedhtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($faceTotal['cont'],
	    	$page,$pagesize)); 
	    	
	    	$Untreatedlist = $this->mode->rolefaceInfo(
	    	$_SESSION['facehaving'],$page,$pagesize);
	    	// 通过
	    	$Adoptdhtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($faceAdoptTotal['cont'],
	    	$page,$pagesize));
	    	
	    	$Adoptlist = $this->mode->rolefaceInfo(
	    	$_SESSION['faceAdopthaving'],$page,$pagesize);
	    	// 拒绝
	    	$Refusehtml = htmlspecialchars(
	    	Helper_Lib::getPageHtml($faceRefuseTotal['cont'],
	    	$page,$pagesize));
	    	
	    	$Refuselist = $this->mode->rolefaceInfo(
	    	$_SESSION['faceRefusehaving'],$page,$pagesize);
	    	
	    	$data = array
	    	(
	    	 	'Untreatedpagehtml'=>$Untreatedhtml,
	    		'UntreatedList'=>$Untreatedlist,
    			'Adoptdhtml'=>$Adoptdhtml,
    			'Adoptlist'=>$Adoptlist,
    			'Refusehtml'=>$Refusehtml,
    			'Refuselist'=>$Refuselist,
	    		'showUserPhotos'=>$showUserPhotos
	    	);
    	}
		$this->load_view('user_photos_verif', $data);
	}
	// 设置通过处理
	public function PhotosAdopt($data = array()) 
	{ 
		$ret =null;
		$dataInfo = array();
		
		$photosAllOut = array();
		
		if ($_POST['checkbox'] && count($_POST['checkbox'])>0)
		{
			$photosAllOut = trim(implode(',', $_POST['checkbox']));
		}
		else{ 
			$this->outputJson(-1,'请选择审核头像!');
		}
		
		$exoutdata = explode(',', $photosAllOut);
		 
		foreach ($exoutdata as $var)
		{
			$outdata = explode('-', $var);
			$RoleIndexOut[] = $outdata[0];
			$dataInfo[] = array
			(
			'RoleFaceId'=>$outdata[5],
			'ServerId'=>$outdata[2],
			'PlatformId'=>$outdata[4],
			'Pass'=>2,
			'PlayerId'=>$outdata[0],
			'ImageId'=>$outdata[1],
			'ExclusiveKey'=>$outdata[3], 
			); 
			$serverId = $outdata[2];
		}
		
		$moverError = null;
		
		// 移动审核开始
		foreach ($dataInfo as $invar)
		{
			if (isset($invar['status']))
			{
				unset($invar['status']);
			}
			$fileName = $invar['PlayerId'].'_'.$invar['ImageId'].'.png';
			$ret = $this->fileProcessing($fileName, 'adopt');
			if($ret == true )
			{
				$invar['status'] = 0;
				$RoleMoveSuccess[] = $invar;
			}
			else{
				$invar['status'] = -1;
				$RoleMoveSuccess[] =$invar;
				__log_message('mover file false'.json_encode($invar),'userPhotos');
				$moverError.= $invar['PlayerId'].'移动失败!';
			}
		}
		$inRequest = json_encode($RoleMoveSuccess);
			
		$inHeader =  $this->VerifyToken($RoleMoveSuccess,null,'RoleImageVerify',$serverId);
		
		$code = 'RoleImageVerify';
		 
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
		  
		$retOut = json_decode(trim($ret),true);
		
		
		if (isset($retOut['success']) && count($retOut['success'])>0){
			
			$RoleIndexOut  = $retOut['success'];
			
			$dbret = $this->mode->roleAdopt($RoleIndexOut);
			if (!$dbret){
				$this->outputJson(0,'db处理失败!'.$moverError); 
			} 
			log_message(7001, $dataInfo, $retOut,0);
			
		}
		if(count($retOut['failure'])>0)
		{
			log_message(7001, $dataInfo, $retOut,-7001);

			$Infailure = $retOut['failure'];
			
			if(is_array($Infailure))
			{
				$Infailure = json_encode($Infailure);
			}
			// <<<<<<<<<adoptReset begin<<<<<<<<<<<
			// 如果审核失败则重置至原始目录
			$InAvatarOut = $this->mode->RoleAvatarFileInfo($retOut['failure']);
			
			$adoptMvReset = NULL ;

			if($InAvatarOut)
			{
				foreach ($InAvatarOut as $var)
				{
					__log_message("userphotos :::44",'userphotos-log');
					$FileName = $var['PlayerId'].'_'.$var['ImageId'].'.png';
					__log_message("userphotos :::55",'userphotos-log');
					$ret = $this->fileProcessing($FileName, 'adoptReset');
					if (!$ret)
					{
						__log_message("userphotos :::66",'userphotos-log');
					   $adoptMvReset.='id'.$var['id']. $FileName.'文件重置失败!';
					}
					__log_message("userphotos :::77",'userphotos-log');
				} 
				if ($adoptMvReset)
				{

					$this->outputJson(0,$adoptMvReset);
				}
			}
			else
			{
				$this->outputJson(0,'重置失败网络异常！'); 
			}
			__log_message("userphotos :::88",'userphotos-log');
			// <<<<<<<<<<adoptReset end <<<<<<<<<<<
			
			$this->outputJson(0,'Id'.$Infailure.'审核失败!'.$moverError); 
		}
		 
		$this->outputJson(0,'审核成功!'.$moverError); 
	}
	// 拒绝处理
	public function Photosrefuse($data=array()) 
	{ 
		// 先等待与服务器响应处理完毕然后在处理sql
		// 协议请求
		
		// 如果校验成功执行db
		//$dbret = $this->mode->roleRefuse($outSid);
		$ret =null;
		$dataInfo = array(); 
		$photosAllOut = array();
		
		if ($_POST['checkbox'] && count($_POST['checkbox'])>0)
		{
			$photosAllOut = trim(implode(',', $_POST['checkbox']));
		}
		else{
			$this->outputJson(-1,'请选择审核头像!');
		}
		
		$exoutdata = explode(',', $photosAllOut);
			
		foreach ($exoutdata as $var)
		{
			$outdata = explode('-', $var);
			$RoleIndexOut[] = $outdata[0];
			$dataInfo[] = array
			(
				'RoleFaceId'=>$outdata[5],
				'ServerId'=>$outdata[2],
				'PlatformId'=>$outdata[4],
				'Pass'=>3,
				'PlayerId'=>$outdata[0],
				'ImageId'=>$outdata[1],
				'ExclusiveKey'=>$outdata[3],
			);
			$serverId = $outdata[2];
		}
		$moverError = null;
		// 移动审核开始 
		foreach ($dataInfo as $invar)
		{
			if (isset($invar['status']))
			{				
				unset($invar['status']);
			}
			$fileName = $invar['PlayerId'].'_'.$invar['ImageId'].'.png';;					 
			$ret = $this->fileProcessing($fileName, 'refuse');
			if($ret == true )
			{
			 $invar['status'] = 0;
			 $RoleMoveSuccess[] = $invar;
			}
			else{
				$invar['status'] = -1;
				$RoleMoveSuccess[] =$invar;
				__log_message('mover file false'.json_encode($invar),'userPhotos');
				$moverError.= $invar['PlayerId'].'移动失败!';
			}
		} 
		$inRequest = json_encode($RoleMoveSuccess);
		 
		$inHeader =  $this->VerifyToken($RoleMoveSuccess,null,'RoleImageVerify',$serverId); 
		
		//__log_message("maill :::".json_encode($data),'mail');
		$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
			
		//$err = $this->is_json(trim($ret));
		//__log_message("server :::".$ret,'userphotos-log');
		$retOut = json_decode(trim($ret),true);
		
		
		if (isset($retOut['success']) && count($retOut['success'])>0){
				
			$RoleIndexOut  = $retOut['success'];
				
			$dbret = $this->mode->roleRefuse($RoleIndexOut);
			if (!$dbret){
				$this->outputJson(0,'db处理失败!'.$moverError);
			}
			log_message(7002, $dataInfo, $retOut,0);
				
		}
		if(count($retOut['failure'])>0)
		{
			log_message(7002, $dataInfo, $retOut,-7002);
			//__log_message('failure '.json_encode($retOut['failure']),'userphotos-log');
			$Infailure = $retOut['failure'];
			
			if(is_array($Infailure))
			{
				$Infailure = json_encode($Infailure);
			}
			// <<<<<<<<< refuse Reset begin<<<<<<<<<<<
			// 如果审核失败则重置至原始目录
			$InAvatarOut = $this->mode->RoleAvatarFileInfo($retOut['failure']);
				
			$adoptMvReset = NULL;
			if($InAvatarOut)
			{	
				foreach ($InAvatarOut as $var)
				{
					$FileName = $var['PlayerId'].'_'.$var['ImageId'].'.png';
					$ret = $this->fileProcessing($FileName, 'refuseReset');
					if (!$ret)
					{
					  $adoptMvReset.='id'.$var['id']. $FileName.'文件重置失败!';
					}
				}
				if ($adoptMvReset)
				{
					$this->outputJson(0,$adoptMvReset);
				}
			}
			else
			{
				$this->outputJson(0,'重置失败网络异常！'); 
			}
			// <<<<<<<<<<refuse Reset end <<<<<<<<<<<
			$this->outputJson(0,'Id'.$Infailure.'审核失败!'.$moverError);
			//log_message($code, $request, $response)
		} 
		$this->outputJson(0,'审核成功!'.$moverError);
	}
	function fileProcessing($fileName,$type,$serverId=NULL)
	{
		// 后期优化指定serverId 集散目录
		$OldAddress = null;
		$NewAddress = null;
		$url = null;
		switch (PHP_OS)
		{
			case 'WINNT': // WINDOWS
				$url = 'D:/xampp/htdocs/fruitadmin/admin/www/facefile/';
				break;
			case 'Linux': // LINUX
				$url = dirname(dirname(dirname($_SERVER['DOCUMENT_ROOT']))).'/facefile/';
				break;
			default:return false;
			break;
		}
		// 采用
		if($type=='adopt')
		{
			$OldAddress = $url.'untreated/'.$fileName;
			if (!file_exists($OldAddress))
			{
				__log_message("file does not exist",'userPhotos');
				return false;
			}
			$NewAddress = $url.'adopt/'.$fileName;
				
			if( rename($OldAddress,$NewAddress)){
	
				return true ;
			}
			return false;
		}
		// 拒绝
		if ($type=='refuse')
		{
				
			$OldAddress = $url.'untreated/'.$fileName;
			if (!file_exists($OldAddress))
			{
				__log_message("file does not exist",'userPhotos');
				return false;
			}
			$NewAddress = $url.'refuse/'.$fileName;
				
			if(rename($OldAddress,$NewAddress)){
	
				return true;
			}
			return false;
		}
		if ($type =='adoptReset')
		{
			$OldAddress = $url.'adopt/'.$fileName;
			if (!file_exists($OldAddress))
			{
				__log_message("file does not exist".$type,'userPhotos');
				return false;
			}
			$NewAddress = $url.'untreated/'.$fileName;
			
			if(rename($OldAddress,$NewAddress)){
			
				return true;
			}
			return false;
		}
		if ($type =='refuseReset')
		{
			$OldAddress = $url.'refuse/'.$fileName;
			if (!file_exists($OldAddress))
			{
				__log_message("file does not exist".$type,'userPhotos');
				return false;
			}
			$NewAddress = $url.'untreated/'.$fileName;
				
			if(rename($OldAddress,$NewAddress)){
					
				return true;
			}
			return false;
		}
		return false;
	} 
}
