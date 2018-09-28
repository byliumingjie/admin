<?php
$insert_html.= Page_Lib::loadJs('server.list');
$insert_html.= Page_Lib::loadJs('role.ban','','tool');

echo Page_Lib::head($insert_html,'',null,true);
?>
<!-- title set tool begin-->
<div id="content-header">
    <h1>角色封禁日志</h1>
    <div class="btn-group">        
		 
 	</div>
</div>
<!-- >top title begin-->
<div id="breadcrumb">
	<!-- 坐标系 -->
    <a href="/index/index" title="跳到首页" class="tip-bottom">
    <i class="icon-home"></i> 首页</a>
    <a href="#" class="current">处理角色</a>
</div>
<div class="container-fluid">
<br>
<!-- warning info begin -->
<?php if(empty($object) && isset($datasub)):?>
<div class="widget-content" id= "roleinfo">		 
		<div class="alert">
			<button class="close" data-dismiss="alert">×</button>
			<strong>Warning!</strong><?php echo "无封禁数据";?>.
		</div>	 
<?php endif;?>
<!-- end --> 
<!-- error info begin --> 
 
	<?php echo   DevToolbox_Lib::rolebanLogHtml('roleBanlogIndex');?>  
	<!-- lod file info -->  
	<?php  if(!empty($roleBanInfo)): ?>
	<table id="tableExcel" class="table table-striped table-bordered table-hover" >
		<thead>
		    <tr>
		   		<th>Id</th>
		    	<th>平台Id</th> 		    	 
		        <th>区服</th>          
		        <th>封禁角色Id</th>
		        <th>封禁角色昵称</th>			        
		        <th>封禁类型</th>
		        <th>封禁规则</th>		               
		        <th>封禁截止日期</th>
		        <th>封禁原因</th>
		        <th>操作人</th>
		        <th>创建日期</th>	         
		    </tr> 
		</thead>
		<tbody>	
		 	<?php $channelcfg = Config::get("key.gm.channel");?>
			<?php foreach ($roleBanInfo as $inkey =>$invar):?>			 
			<tr>
			<td style="text-align: center;" data-name="id"><?php echo $invar['id'];?></td> 
			<td style="text-align: center;"data-name='platId'><?php echo $invar['platId'];?></td>
			<td style="text-align: center;" data-name="serverId"><?php echo $invar['serverId'];?></td>
			<td style="text-align: center;" data-name="player_id"><?php echo $invar['player_id'];?></td>
			<td style="text-align: center;" data-name="nick_name"><?php echo $invar['nick_name'];?></td>
			<td  style="display: none" data-name="lockType"><?php echo (int)$invar['lockType'];?></td>
			<td style="text-align: center;" >
			<?php 
			 switch ((int)$invar['lockType'])
			 {
			 	case 1: echo '禁言'; break;
			 	case 2: echo '封号'; break;
			 	case 3: 
			 		if ($invar['lockStatus']==1){
			 			echo '解封禁言';
			 		}
			 		if($invar['lockStatus']==2){
			 			echo '解封登陆';
			 		} 
			 		break;
			 	case 4: echo '异常'; break;
			 	
			 	default: echo '未知'; break;
			 }
			?>
			</td>
			<td  style="display: none" data-name="lockTimeType"><?php echo (int)$invar['lockTimeType'];?></td>
			<td style="text-align: center;"><?php 
			 
			switch ((int)$invar['lockTimeType'])
			{
				case 1: echo '普通封号'; break;
				case 2: echo '永久封号'; break;
				default: echo '无'; break;
			}
			?></td>
			
			<td style="text-align: center;" data-name="lockEndtime"><?php  
			if($invar['lockEndtime']!=0)
			{
				echo date('Y-m-d H:i:s',$invar['lockEndtime']);
			}else{
				echo 0;
			}?></td>
			<td style="text-align: center;" data-name="description"><?php echo $invar['description'];?></td>
			<td style="text-align: center;" data-name="executor"><?php echo $invar['executor'];?></td>
			<td style="text-align: center;" data-name="createtime"><?php echo $invar['createtime'];?></td>			 
			</tr>	
			<?php endforeach;?>
		</tbody> 
		</table>
		<!-- lod file infoend -->
	<!-- 分页组件 begin -->
	<div class="row center" style="text-align: center;">	
	<?php  echo htmlspecialchars_decode($pagehtml);?>
	</div>
	</div>
</div>
<?php endif;?>
</div>
<?php echo Page_Lib::footer(null,true); ?>
