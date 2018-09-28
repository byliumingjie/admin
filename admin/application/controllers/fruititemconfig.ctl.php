<?php

/*
 * 用户信息相关
 */	
 
class FruitItemConfig_Controller extends Module_Lib {

	// 
	public function index($data = array())
    {  
    	$page = empty($_GET['p']) ? 1 : $_GET['p'];  		
  		$pagesize = 50;
  		
  		$itemId = !empty($_POST['itemId'])? ' id = '.$_POST['itemId']:null;
  		$itemName = !empty($_POST['itemName']) ? 'itme_name = '.$_POST['itemName']:null;
  		$startTime = $_POST['startTime'];
  		$endtime = $_POST['endtime'];
  		$itemType = $_POST['itemType'];
  		
  		
    	$conn = Platfrom_Service::getServer(true,'global');
    	load_model('fruit/FruitItemConfig'); 
    	
    	$fruitmode = new  FruitItemConfig_Model(); 
    	$data['object']= $fruitmode->getItemInfo($conn); 
    	
		$this->load_view("fruit/tool_fruit_item_config",$data);
	}
	/**
	 * 文件上传解析 获取文件内容 此函数通过js去submit
	 * 文件路径为www/static/js/usersupervise.js 
	 **/
    public function addItem() 
    { 
		$this->outputJson(-1,"111");
        $pathname = $_FILES['itemImage']; 
        $itemName = $_POST['itemName'];
        $itemType = $_POST['itemType'];
        $itemObtain = $_POST['itemObtain'];
        $itemdesc = $_POST['itemdesc'];
        
        $itemId = (!is_numeric($_POST['itemId']))
        ?$this->outputJson(-1,"道具ID非有效数字")
        :$_POST['itemId']
        ;
        $itemoBtainType = $_POST['itemoBtainType'];
        
        $itemBtainNum = (!is_numeric($_POST['itemBtainNum']))
        ?$this->outputJson(-1,"获得数量非有效数量")
        :$_POST['itemBtainNum']
        ;
        $patchExit  = pathinfo($pathname['name'], PATHINFO_EXTENSION);
         
        if ( trim($patchExit)!='jpg' &&   trim($patchExit)!='png'){
        	$this->outputJson(-1,'文件格式错误'); 
        }
        $filename = "item_".$itemId.".".$patchExit;         
        $filepath = $_SERVER['DOCUMENT_ROOT'].'/frut_item_img/'.$filename;
        
        $conn = Platfrom_Service::getServer(true,'global');        
        //load_model('fruit/FruitItemConfig');         
        //$fruitmode = new  FruitItemConfig_Model();
       
        $data = array
        (
        'id' => $itemId,
        'itme_name' => $itemName,
        'item_type' => $itemType,
        'obtain'=> $itemoBtainType.",".$itemId.",".$itemBtainNum,
        'desc'  => $itemdesc,
        'image' => $filename,        		
        );
       
        if(is_uploaded_file($pathname['tmp_name']))
        {
        	//上传文件，成功返回true
        	if(! move_uploaded_file($pathname['tmp_name'],$filepath))
        	{
        		$this->outputJson(-1,"上传失败");
        	}
        	/*if($fruitmode->setIteminfo($conn, $data)==false){
        		$this->outputJson(-1,"数据库执行失败");
        	}*/
        	 
        }else
        {
        	$this->outputJson(-1,"非法上传文件");
        }  
        $this->outputJson(0,"添加道具成功");
    } 
    public function editItem()
    {    	
    	$pathname = $_FILES['image'];
    	$originalImg = $_POST['originalImg'];
    	$itemId = $_POST['id'];
    	$itme_name = $_POST['itme_name'];
    	$item_type = $_POST['item_type'];
    	$itemoBtainType = $_POST['itemoBtainType'];
    	$itemBtainNum = $_POST['itemBtainNum'];
    	$desc = $_POST['desc'];
    	 
    	if(isset($pathname) && !empty($pathname) && count($pathname)>0 && !empty($pathname['name']))
    	{
	    	$patchExit  = pathinfo($pathname['name'], PATHINFO_EXTENSION);    	 
	    	if ( trim($patchExit)!='jpg' &&   trim($patchExit)!='png'){
	    		$this->outputJson(-1,'文件格式错误');
	    	}
	    	$filename = "item_".$itemId.".".$patchExit;
	    	$originalfileDir = $_SERVER['DOCUMENT_ROOT'].'/frut_item_img/'.$originalImg;
	    	$filepath = $_SERVER['DOCUMENT_ROOT'].'/frut_item_img/'.$filename;
	    	
	    	if(file_exists($originalfileDir))
	    	{
	    		if(!unlink($originalfileDir)){
	    			$this->outputJson(-1,"清理".$originalfileDir."失败");
	    		}
	    	} 
	    	// 清理之后要记得修改数据库或redis数据
	    	if(is_uploaded_file($pathname['tmp_name']))
	    	{
	    		//上传文件，成功返回true
	    		if(! move_uploaded_file($pathname['tmp_name'],$filepath))
	    		{
	    			$this->outputJson(-1,"上传失败");
	    		} 
	    		/* // 删除目录文件 修复之后清理原始图标
	    		if(file_exists($originalfileDir))
	    		{
	    			unlink($originalfileDir);
	    		} */
	    		$this->outputJson(0,"上传成功");
	    	}else
	    	{
	    		$this->outputJson(-1,"非法上传文件");
	    	}
    	}
    	$this->outputJson(0,"编辑成功");
    	
    }
    //  删除道具
    public function delItem(){
    	$id = $_POST['id'];
    	$this->outputJson(0,"删除成功");
    }
    
    
}

















