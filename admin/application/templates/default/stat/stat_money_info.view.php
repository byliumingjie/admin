<?php
$insert_html = Page_Lib::loadJs('platformnotice');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html);
?> 
<script type="text/javascript">
<!--
 
//-->
</script>
 
<!-- 站内导航 -->
<div id="content-header">
<h1>货币流水</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" title="
		区服编号查询可以多个区服,例:1,2,3在每一个区服后面追加英文符','号,不过有限制区服不能过多，
		此外，平台、区服ID、消耗类型是必填项"
     data-placement="bottom" data-trigger="focus"  
     class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">

<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statMoneyinfoHtml($type,$sid,$data,$time,$exctypestr);?>
<!-- 查询组件 end-->
<?php if ( isset($data) && !empty($data)): ?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr>
        <th>区服编号</th>
        <th>角色ID</th>
        <th>角色名</th>											
        <th>货币类型</th>
        <th>流水类型</th>
        <!-- <th>状态</th> -->
        <th>货币流量</th>
       <!--  <th>剩余货币</th> -->
        <th>道具时间</th> 
        <?php if ($exctypestr ==6 || $exctypestr ==0):?>
       <!--  <th>招募次数</th>
        <th>招募英雄ID</th>   -->
        <?php endif;?>  
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin-->
      <?php   $j = 0;?>
        <?php foreach ($data as $var): ?>
        
        <tr>            	
		<td style="text-align: center;"><?php echo $var['server_id']; ?></td>
		<td style="text-align: center;"><?php echo $var['play_id']; ?></td>
		<td style="text-align: center;"><?php echo $var['nick_name']; ?></td>
		<td style="text-align: center;"><?php  
		$loginDate = Statdata_Lib::log_type_config(NULL,$var['currency']);
		echo $loginDate[1];
		 //var_dump($loginDate);
		?></td>
		<td style="text-align: center;">
		<?php  
			$exctype = Statdata_Lib::exctype($var['module']);
			echo $exctype; 
		?></td>
		<!--<td style="text-align: center;"><?php //echo $var['count']>0?'收入':'支出'; ?></td>  -->
		<td style="text-align: center;"><?php echo $var['money']; ?></td>
		<!--<td style="text-align: center;"><?php //echo $var['totalnum']; ?></td> -->
		<td style="text-align: center;"><?php echo $var['time']; ?></td> 
      </tr>
      	<?php $datainfo.=$var['server_id'].'='.$var['play_id'].'='.
      	$var['nick_name'].'='.$loginDate[1].'='.$exctype.'='.$var['money'].'='.$var['time'].','?>
        <?php endforeach; ?>
    <!-- stat data end -->
    </tbody>
</table>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>
</div>
<?php $key ="区服"."\t".'角色Id'."\t".'角色名'."\t"."货币类型"."\t"."流水类型"."\t"."数量"."\t"."创建日期"; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>

<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
