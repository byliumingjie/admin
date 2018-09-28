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
<?php echo Statdata_Lib::statHighestOnlineHtml($object);?>
 
<!-- 查询组件 end-->
<?php if (!empty($object)):?>
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead>
     <tr>
		<th>区服Id</th>
		<th>最高在线人数</th>
		<th>日期</th>
	<!--<th>总在线时长 (时:分:秒)</th>-->                
	</tr>              
   </thead>
    <tbody>
    	<?php foreach ($object as $var):?>
    	<tr>
    	<td style="text-align: center;"><?php echo $var['server_id'];?></td>
    	<td style="text-align: center;"><?php echo $var['num'];?></td>
    	<td style="text-align: center;"><?php 
    	$time = date('Y-m-d H:i:s',$var['time']);
    	echo $time;?></td>
    	</tr>
    	<?php $datainfo .=$var['server_id'].'='.$var['cont'].'='.$time.',' ?> 
    	<?php endforeach;?>
    </tbody>
</table>	
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>
</div>
<?php $key ="区服"."\t".'最高在线人数'."\t".'日期'; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>


<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
