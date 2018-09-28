<?php
$insert_html = Page_Lib::loadJs('ajaxupload');
$insert_html.= Page_Lib::loadJs('role.ban','','tool');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>玩家封停</h1> 
        <div class="btn-group"> 
        </div>
</div>
<!-- top begin-->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">玩家封停</a>
    <!-- 提示帮助 -->
    <a href="#"  data-placement="bottom" data-trigger="focus"  
    class="tip-bottom" title="可根据自定义的方式查询相关的日志数据">
    <i class="icon-question-sign"></i></a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    <?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
    <i class="icon-question"></i></a>
 </div>
<!-- top end -->
<div class="widget-content">
<!-- 条件选项菜单 begin-->
       
<?php echo DevToolbox_Lib::rolebanlogHtml($object);?>
<!-- 条件选项菜单  end-->

<!-- 视图列表信息 begin-->
<?php if (!empty($object)):?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr> 
    	<th>ID</th>
        <th>创建日期</th>
        <th>UIN</th>
        <th>区服</th>
        <th>角色ID</th>
        <th>设备</th>
        <th>日志记录类型</th>
        <th>状态</th>
        <th>开始日期</th>
        <th>截止日期</th>
        <th>操作原由</th>
        <th>操作人</th>
        <th>设备详情</th>        
    </tr> 
</thead>
<tbody>
	<?php if($object):?>
	<?php foreach($object as $var):?>
	<tr>
		<td style="text-align: center;" ><?php echo $var['ID'];?></td>
		<td	style="text-align: center;" ><?php echo $var['CREATRTIME'];?></td>
		<td	style="text-align: center;" ><?php echo $var['UIN'];?></td>
		<td	style="text-align: center;" ><?php echo $var['ROLESID'];?></td>
		<td	style="text-align: center;" ><?php echo $var['ROLEID'];?></td>
		<td	style="text-align: center;" ><?php echo $var['DEVICE'];?></td>		
		<td	style="text-align: center;" ><?php 
		switch ($var['TYPE']){
			case 1:echo "角色封禁日志";break;
			case 2:echo "账号封禁日志";break;		
			default:echo "0";break;
		}		
		?></td>		
		<td	style="text-align: center;" >
		<?php 
		switch ($var['STATUS']){
			case 3:echo "角色封停";break;
			case 4:echo "解除封停";break;
			case 5:echo "角色禁言";break;
			case 6:echo "解除禁言";break;
			default:echo "0";break;
		}?></td>
		<td	style="text-align: center;" ><?php echo $var['BEGINTIME'];?></td>
		<td	style="text-align: center;" ><?php echo $var['ENDTIME'];?></td>
		<td	style="text-align: center;" ><?php echo $var['REASON'];?></td>
		<td	style="text-align: center;" ><?php echo $var['OPERATOR'];?></td>
		<td	style="text-align: center;" title ="<?php echo $var['DETAIL']?>">
		<?php echo mb_substr($var['DETAIL'],0,10,'utf-8');?></td>
	</tr>
	<?php 
	$dat.=$var['id']."=".$var['name']."=".$var['title']."=".$var['item1']."=".
	$var['item2']."=".$var['item3']."=".$var['item4']."=".$var['datetime'].",";
	?>
	<?php endforeach;?>
	<?php endif;?>
</tbody>
</table>
<?php endif;?>
<!-- 视图列表信息  end-->
</div>
<input type="hidden" id="selector">
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 版权信息begin -->
<?php echo Page_Lib::footer('',true);?>
<!-- 版权信息 end -->