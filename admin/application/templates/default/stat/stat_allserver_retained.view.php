<?php
$insert_html = Page_Lib::loadJs('platformnotice');
echo Page_Lib::head($insert_html);
?> 
<script type="text/javascript">
</script>
<!-- 站内导航 -->
<div id="content-header">
<h1>区服留存</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" title="区服留存根据一个时间点得到一个大于这个时间点的所有区服的留存数据"
     data-placement="bottom" data-trigger="focus"  
     class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">
 <?php 
 if(isset($data) && !empty($data)){
	 foreach ($data as $indat)
	 {
	 	$sidOut[$indat["server_id"]]=0;
	 }
 }
 
 $dataOut =''
 ?>
 <?php 
 $i = 0;
 foreach ($sidOut as $Insidvar=>$va)
 {
  foreach($time as $Intimes)
  {
  	 foreach($data as $Invar)
  	 {
  	  	//
    	if($Invar['datetime']==$Intimes && $Invar['server_id']==$Insidvar)
    	{	
    		$rem = array($Intimes,$Invar['roleCont']);
    		// 如果第一次的注册日期记录保存
    		if ($datetime == $Intimes)
    		{
    			$IncreateTimeData[$Insidvar] =array($Invar['datetime'],$Invar['roleCont']); 	
    		}
    		break;
    	}
    	else{
    		$rem = array($Intimes,0);
    	}
  	  }	
  	  $newData[] = array($Insidvar=>$rem);
  }
}
foreach($newData as $vad)
{
	foreach($vad as $sidd=>$value)
	{
		$dat[]=array($sidd,$value[0],$value[1]);
	}		
}
?>
 
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::allserver_retainedHtml($time);?>
<?php if(isset($data) && !empty($data)):?>
<div style="overflow:scroll;">
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr>
		<th>区服ID</th>
        <th><?php echo isset($datetime)?$datetime:'';?>注册</th>
        <?php if(isset($space)):?>
        <?php for($i=1;$i<=$space;$i++):?> 	
        <th><?php echo $i."日留存率";?></th>
        <?php endfor;?>
        <?php endif;?>
    </tr>
</thead>
 <tbody>
    <!--统计数据项 begin-->
    <?php foreach($sidOut as $sid=>$Invar):?>
    <?php 
    $da = ''; 
    ?>
     <tr>
     	  <td style="text-align: center;"><?php echo $sid;?></td>
     	  <?php foreach ($time as $intime){?>
     	  
    	  <?php foreach($dat as $var):?>
    	  
    	  <?php 
    	  
    	  if($sid == $var[0] && $intime == $var[1])
    	  {
    	  	$createRoleCont = $var[2];
	    	// 如果非注册日期内进行计算
    	  	if ($intime!=$datetime)
    	  	{    	  		
    	  		$createRoleCont =round(($var[2]/$IncreateTimeData[$sid][1])*100,2).'%';
    	  		
    	  		//$createRoleCont .= $var[2];
    	  	}
    	  	
    	  	//数据导出
    	  	$da .=$createRoleCont."="; 
    	  ?>
    	  <td style="text-align: center;"><?php  echo $createRoleCont; ?></td> 
      	  <?php 	
    	  } 
    	  ?>      	      	  
   		  <?php endforeach;?>
   		  <?php }?>
   	</tr>
    <?php 
    	$dac.=$sid."=".$da.",";
    ?>
    <?php endforeach;?>
    <!-- stat data end -->
    </tbody>
<tbody> 
<!-- stat data end -->
</tbody>
</table>
</div>
<?php endif;?>	 				
</div>
<?php 
for($j=1;$j<=$space;$j++){
	$spaceKey .= $j."日留"."\t"; 
}
$key ="区服"."\t".$datetime."注册"."\t".$spaceKey; 
?>

<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $dac;?>"/>	                    
</form> 
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  //echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',true);?>