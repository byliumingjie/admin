<?php
$insert_html =Page_Lib::loadJs("statdata");
echo Page_Lib::head($insert_html,'',1);
?> 
<!-- 站内导航 -->
<div id="content-header">
<h1>当前在线玩家</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statRealTimeRoleHtml($onlineData);?>

<!-- 查询组件 end-->
<?php if (!empty($onlineData)):?>
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead>
     <tr>
		<th>区服Id</th>
		<th>在线人数</th>
		<th>当前日期</th>
 
	</tr>              
   </thead>
    <tbody>
    	<?php foreach ($onlineData as $sid=>$onlineNum):?>
    	<?php $datetime = date('Y-m-d H:is',time());?>
    	<tr>    	
	    	<td style="text-align: center;"><?php echo $sid;?></td>
	    	<td style="text-align: center;"><?php echo $onlineNum;?></td>
	    	<td style="text-align: center;"><?php echo $datetime;?></td>
    	<?php $datainfo .=$sid.'='.$onlineNum.'='.$datetime.',' ?> .
    	</tr>
    	<?php endforeach;?>
    </tbody>
</table>	
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  //echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>
</div>
<?php $key ="区服"."\t".'人数'."\t".'当前日期'; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>


<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
