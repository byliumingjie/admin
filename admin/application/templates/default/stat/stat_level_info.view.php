<?php
$insert_html = Page_Lib::loadJs('statdata');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html);
?> 
<script type="text/javascript">
<!--
 
//-->
</script>
 
<!-- 站内导航 -->
<div id="content-header">
<h1>玩家等级分布</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
	<a href="#" title="通过新增的时间范围获取平台下所有区服的等级分布信息(开始时间及截止日期根据游戏服玩家注册的日期获得)"
     data-placement="bottom" data-trigger="focus"  
     class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">

<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statLevelInfoHtml($leveldata);?>
<!-- 查询组件 end-->

<?php if ( isset($leveldata) && !empty($leveldata)): ?>
<?php
// get serverlist info 
foreach ($leveldata as $Invar)
{
	$serverListOut[$Invar['server_id']]=null;	 
} 
?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr>
        <th>区服编号</th>
		 
        <th>等级</th>
        <th>人数</th>	 
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin-->
      <?php   $j = 0;
	  foreach($serverListOut as $Inserver=>$value):
	  ?>
	  <?php  for($i=1;$i<=100;$i++):?>
	  <?php 
		$levelNubmer = 0;
		$serverId = 0 ; 
	  ?>
        <?php foreach ($leveldata as $var): ?>
		<?php 
			
			if($i==(int)$var['level'] && $Inserver== $var['server_id'])
			{
				 
				$levelNubmer = $var['cont']; 
				
			}
	   ?>
	   <?php endforeach; ?>
      <tr>            	
		<td style="text-align: center;"><?php echo $Inserver; ?></td> 
		<td style="text-align: center;"><?php echo $i; ?></td>
		<td style="text-align: center;"><?php echo $levelNubmer; ?></td>
      </tr>
      	<?php $datainfo.=$Inserver.'='.$i.'='.$levelNubmer.',';?>  
		<?php endfor;?>
		<?php endforeach;?>
    <!-- stat data end -->
    </tbody>
</table>	
<?php endif;?>
</div>
 
<?php $key ="区服"."\t".'等级'."\t"."人数"; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="sid"  value="<?php echo $serverid;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
