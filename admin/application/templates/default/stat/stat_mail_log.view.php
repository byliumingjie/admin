<?php
$insert_html =Page_Lib::loadJs("statdata");
echo Page_Lib::head($insert_html,'',1);
?> 
<!-- 站内导航 -->
<div id="content-header">
<h1>玩家邮件领取记录</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statUserMailAnnalHtml();?>
 
<!-- 查询组件 end-->
<?php if (!empty($UserMailList)):?>
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead>
     <tr>
		<th>区服Id</th>
		<th>角色编号</th>
		<th>角色邮件领取时间</th>
		<th>邮件编号</th>
		<th>服务端邮件编号</th>
		<th>邮件领取规则</th>
		<th>邮件标题</th>
		<th>邮件描述</th>
		<th>奖励信息</th>
		<th>邮件创建日期</th>
	<!-- 	<th>总在线时长 (时:分:秒)</th>      -->                
	</tr>              
   </thead>
    <tbody>
    	<?php foreach ($UserMailList as $var):?>
    	<td style="text-align: center;"><?php echo $var['server_id'];?></td>
    	<td style="text-align: center;"><?php echo $var['player_id'];?></td>
    	<td style="text-align: center;"><?php echo date('Y-m-d H:i:s',$var['time']);?></td>
    	<td style="text-align: center;"><?php echo $var['mail_id_php'];?></td>
    	<td style="text-align: center;"><?php echo $var['mail_id'];?></td>
    	<td style="text-align: center;"><?php echo $var['Rules'];?></td>
    	<td style="text-align: center;"><?php echo $var['MailTitle'];?></td>
    	<td style="text-align: center;"><?php echo $var['MailText'];?></td>
    	<!-- - <td style="text-align: center;"><?php //echo $var['ItemList'];?></td>-->
    	<td style="text-align: center;" data-name="rules">
		<button class="btn btn-link platformSIDLock"><button class="btn btn-link serverInfo">邮件道具详情</button></td>
		<?php $rulesOut = json_decode($var['ItemList'],true); ?>
		<td style="display: none" data-name="RequestData"><?php 
		echo  urldecode ( json_encode( $rulesOut ,JSON_PRETTY_PRINT) );?></td>
    	<td style="text-align: center;"><?php echo $var['Createtime'];?></td>
    	<?php //$datainfo .=$var['server_id'].'='.$var['cont'].'='.$var['createtime'].',' ?> 
    	<?php endforeach;?>
    </tbody>
</table>	
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>

<!-- log info  -->
<div class="modal fade" id="serverinfoModal" tabindex="-1" role="dialog" aria-labelledby="logInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">日志详细</h4>
            </div>
            <form action="#" id='serverInfoForm'>
	            <div class="modal-body">               
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">                        
                        	<textarea   cols="3" name="RequestData" class="form-control"style="margin: 0px 0px 10px; width: 512px; height: 100px;"></textarea>
                         </div>
                    </div>                                             
	            </div>
            </form>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
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
