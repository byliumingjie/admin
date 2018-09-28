<?php
$insert_html= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>钻石排行榜</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom">
    <i class="icon-home"></i>首页</a>
	<a href="#" title="默认上限为100以内排行,对应平台区服要设置排行所允许的名次列表"
     data-placement="bottom" data-trigger="focus"  
     class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statRankdiamondHtml($RankOut);?>
 
<!-- 查询组件 end-->
<?php if ( isset($RankOut) && !empty($RankOut)): ?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr>
        <th>区服编号</th>
        <th>账号</th>
        <th>角色Id</th>											
        <th>角色昵称</th>
        <th>vip</th>
        <th>钻石</th>
        <th>角色等级</th>
        <th>创建时间</th>
        <th>榜单名次</th>        
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin--> 
      <?php foreach ($RankOut as $var):?>
    <tr>            	
		<td style="text-align: center;"><?php echo $var['server_id']; ?></td>
		<td style="text-align: center;"><?php echo $var['account']; ?></td>
		<td style="text-align: center;"><?php echo $var['player_id']; ?></td>
		<td style="text-align: center;"><?php echo $var['nick_name']; ?></td>
		<td style="text-align: center;"><?php echo $var['vip_level']; ?></td>
		<td style="text-align: center;"><?php echo $var['diamond']; ?></td>
		<td style="text-align: center;"><?php echo $var['level']; ?></td>
		<td style="text-align: center;"><?php echo $var['createtime']; ?></td>
		<td style="text-align: center;"><?php echo $var['rank']; ?></td> 
    </tr> 
   <?php $datainfo.=$var['server_id'].'='.$var['account'].'='.$var['player_id'].'='.$var['nick_name'].
   '='.$var['vip_level'].'='.$var['diamond'].'='.$var['level'].'='.$var['createtime'].'='.$var['rank'].',';?>
    <?php endforeach;?>     	
    </tbody>
</table>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>
</div>
<?php $key ="区服"."\t".'账号'."\t".'角色id'."\t"."角色昵称"."\t"."vip"."\t"."钻石数量"."\t"."角色等级"."\t"."创建时间"."\t"."排行"; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>

<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
