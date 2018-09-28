<?php
/* 
 * 区服管理
 */
class FruitItemPrizes_Model extends Model{
 	private $table = 'tb_platform';
    private $db = null;
    private $cdd = null;
     
    public function __construct($region='') 
    { 
        parent::__construct(); 
		 
		$this->cdd = !empty($region) 
		? 
		Mysql::database('',$region) 
		: 
		null; 
    }
   
   /**
 	* 获取最大一个iD
	**/ 
	public static function getItemInfo($platform)
	{   
		$obj = new FruitItemConfig_Model( $platform );		 
		$sql ="SELECT * FROM tb_fruit_item_config";
		
		if($obj->cdd->query($sql) && $obj->cdd->rowcount() > 0){
            $row = $obj->cdd->fetch_all(); 
            return $row;
        }
	} 
	
	public static  function setIteminfo($conn,$data)
	{
		 
		$obj = new FruitItemConfig_Model( $conn );
		 
		if($obj->cdd->insert('tb_fruit_item_config',$data)==true)
		{
			return true;
		} 
		return false;
	}
	 
	
}

