<?php

/*
 * 用户信息相关
 */	
 
class FruitItemPrizes_Controller extends Module_Lib {

	// 入口地址
	public function index($data = array())
    {  
    	/* $page = empty($_GET['p']) ? 1 : $_GET['p'];  		
  		$pagesize = 50;
  		
  		$itemId = !empty($_POST['itemId'])? ' id = '.$_POST['itemId']:null;
  		$itemName = !empty($_POST['itemName']) ? 'itme_name = '.$_POST['itemName']:null;
  		$startTime = $_POST['startTime'];
  		$endtime = $_POST['endtime'];
  		$itemType = $_POST['itemType'];
  		
  		
    	$conn = Platfrom_Service::getServer(true,'global');
    	load_model('fruit\FruitItemConfig'); 
    	
    	$fruitmode = new  FruitItemConfig_Model(); 
    	$data['object']= $fruitmode->getItemInfo($conn);  */
    	 
		$this->load_view("fruit/tool_fruit_item_prizes",$data);
	}
	//  增加兑换道具奖品配置
	public function addItemPrizes() 
	{
		 
		__log_message("addItemPrizes","addItemPrizes");
		/*
		 * itemId
		 * itemName
		 * itemType
		 * price
		 * stock 库存
		 * desc 
		 * prizesicon // 兑换图标
		 * itemoBtainType 兑换类型
		 * itemBtainNum 	兑换数量
		 * sort // 排序
		 * activityicon // 活动图标
		 * itemoBtainType //仅付费用户可见
		 * ifhide // 是否隐藏
		 * ifexchange // 是否可兑换
		 *  version	//最低版本  
		 * */
		$itemId = $_POST['itemId'];		  // 道具配置ID
		$itemName = $_POST['itemName'];  // 道具名称
		$itemType = $_POST['itemType']; // 道具类型
		$price = $_POST['price'];	   // 道具价格
		$stock = $_POST['stock']; 	  // 库存
		$desc = $_POST['desc'];		 // 描述
		$prizesicon = $_FILES['prizesicon']; 	     // 兑换道具图标
		$itemoBtainType= $_POST['itemoBtainType'];  // 道具兑换类型
		$itemBtainNum = $_POST['itemBtainNum'];    // 道具兑换数量
		$sort = $_POST['sort'];					  // 是否排序
		$activityicon = $_FILES['activityicon']; // 活动图标
		$ifvisible = $_POST['ifvisible']; 		// 仅付费用户可见
		$ifhide = $_POST['ifhide'];		  	   // 是否隐藏
		$ifexchange = $_POST['ifexchange'];   // 是否可兑换
		$version = $_POST['version'];		 // 最低版本  
		
		$prizesExit  = pathinfo($prizesicon['name'], PATHINFO_EXTENSION);
		
		$activityIconExit  = pathinfo($activityicon['name'], PATHINFO_EXTENSION);
		 
		if ( trim($prizesExit)!='jpg' &&   trim($prizesExit)!='png'){
			$this->outputJson(-1,'兑换道具图标文件格式错误');
		}
		if ( trim($activityIconExit)!='jpg' &&   trim($activityIconExit)!='png'){
			$this->outputJson(-1,'活动图标文件格式错误');
		}		
		$prizefilename = "itemprizes_".$itemId.".".$prizesExit;
		$activityIconName = "itemactivity_".$itemId.".".$prizesExit;
		$fileDir = $_SERVER['DOCUMENT_ROOT'].'/frut_item_img/';
		
		$this->uploadfile($prizesicon['tmp_name'], $fileDir.$prizefilename);
		$this->uploadfile($activityicon['tmp_name'], $fileDir.$activityIconName);
		
	 
		__log_message("addItemPrizes","ItemPrizes");
	}
	public function uploadfile($tmp_name,$filepath,$desc='')
	{
		if(is_uploaded_file($tmp_name))
		{
			//上传文件，成功返回true
			if(! move_uploaded_file($tmp_name,$filepath))
			{
				$this->outputJson(-1,"上传失败");
			} 
		
		}else
		{
			$this->outputJson(-1,"非法上传文件");
		}
	}
	 
}

















