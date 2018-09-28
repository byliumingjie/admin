<?php
$insert_html  = Page_Lib::loadJs('mall','','dev');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('ajaxupload');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
	<h1>商城推荐列表</h1>
	<div class="btn-group">
	<a class="btn btn-large tip-bottom" title="创建活动" id="addName" href="create_mall_config_index">
    <i class="icon-plus"></i>配置商城推荐</a>
    <a class="btn btn-success btn-large" title="批量发布" data-toggle="modal" 
    data-backdrop="static" data-target="#ReleaseViewActivityModel" id="ReleaseViewActivity">
	<i class="icon-plus"></i>批量发布</a>
	</div>
</div>

<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">活动列表</a>
    <a href="#" title=""
    data-placement="bottom" data-trigger="focus"  
    class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">
<?php echo DevToolbox_Lib::MallHtml();?> 
<script type="text/javascript">
$(function() { 
 <?php if ($showActivity===1):?>
	$('#untreatedRelease').click();
 <?php endif;?>
});
</script>
  <div class="row-fluid">
	  <div class="span12">
	  	<div class="widget-box">	
		    <div class="widget-title">
				<span class="icon">
		        	<i class="icon-picture"></i>
		        </span> 
		        <div id='photosAllBtn'>
		        	
		        </div>  
		        <ul class="nav nav-tabs tabaparent" id="tabaparent">
		          <li ><a id='untreatedRelease'  data-toggle="tab" href="#tab1">待发布</a></li>
		          <li><a id='successRelease' data-toggle="tab" href="#tab2">已发布</a></li>	              
	              <li ><a id='failureRelease' data-toggle="tab" href="#tab3">发布失败</a></li>
		        </ul>
			</div> 
        </div> 
       
        <div class="widget-content tab-content">
	        <div id="tab1" class="tab-pane">
	        	<?php if (isset($UntreatedList) && !empty($UntreatedList)): ?>
	        	 
	        	<table id="activityListTable"  class="table table-bordered table-striped" >
				<?php echo DevToolbox_Lib::MallTableHtml();?>
				    <tbody>
				    <!--统计数据项 begin-->
				      <?php   $j = 0; ?>
				        <?php foreach ($UntreatedList as $var): ?> 
				            <tr>      
				                <td style="text-align: center;" data-name="id"><?php echo $var['id'];?></td>
				                <td style="text-align: center;" data-name="platformId"><?php echo $var['platId'];?></td>
				                <td style="text-align: center;" data-name="serverId"><?php echo $var['serverId'];?></td>
								<td style="text-align: center;" data-name="title"><?php echo $var['title'];?></td>
								<td style="text-align: center;" data-name="content"><?php echo $var['description'];?></td>
								
								<td style="text-align: center;" data-name="starttime">
								<?php  echo ($var['startTime']==0)?0:date('Y-m-d H:i:s',$var['startTime']);?></td>
								<td style="text-align: center;" data-name="endtime">
								<?php  echo ($var['endTime']==0)?0:date('Y-m-d H:i:s',$var['endTime']);?></td>
								 
								<td style="text-align: center;" data-name="createtime"><?php echo $var['createtime'];?></td>
								<td style="text-align: center;" data-name="rules">
								<button class="btn btn-link platformSIDLock"><button class="btn btn-link serverInfo">规则详情</button></td>
								<?php  
								$rulesOut = json_decode($var['rules'],true); 
								?>
								<td style="display: none" data-name="RequestData"><?php 
								echo  urldecode ( json_encode( $rulesOut ,JSON_PRETTY_PRINT) );?></td>
								<td data-name="type">
								<?php switch ((int)$var['type']){
									case 1:
										echo '商城推荐';
										break;
									case 2:
										echo '首冲';
										break;
									default: echo '未定义'; break;
									
								}?></td>
								
								<td style="text-align: center;" data-name="status"><?php
								switch ((int)$var['status']){
									case 0: echo "待发布";
									break;
									case 1: echo "已发布";
									break;
									case 2: echo "发布失败";
									break;
									default: echo '异常';break;
								}
								?></td>
				                <td style="text-align: center;">				                 
				                <button class="btn btn-link editActivityBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">编辑</button>
				                <?php if ((int)$var['status']==0 || (int)$var['status']==2):?>
				                <button class="btn btn-link ReleaseMallBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">发布</button>
				                <?php endif;?>  
				                </td>                 
							</tr> 
				        <?php endforeach;?>		    
				    <!-- stat data end -->
				    </tbody>
				</table>
				<div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Untreatedpagehtml);?>
				</div>
				<?php endif; ?>  
	        </div>
	        <!-- 已发布 -->
	        <div id="tab2" class="tab-pane">
	        	<?php if (isset($Adoptlist) && !empty($Adoptlist)): ?>
	        	<table id="activityListTable"  class="table table-bordered table-striped" >
				<?php echo DevToolbox_Lib::MallTableHtml();?>
				    <tbody>
				    <!--统计数据项 begin-->
				      <?php $j = 0; ?>
				        <?php foreach ($Adoptlist as $var): ?> 
				            <tr>      
				                <td style="text-align: center;" data-name="id"><?php echo $var['id'];?></td>
				                <td style="text-align: center;" data-name="platformId"><?php echo $var['platId'];?></td>
				                <td style="text-align: center;" data-name="serverId"><?php echo $var['serverId'];?></td>
								<td style="text-align: center;" data-name="title"><?php echo $var['title'];?></td>
								<td style="text-align: center;" data-name="content"><?php echo $var['description'];?></td>
								
								<td style="text-align: center;" data-name="starttime">
								<?php  echo ($var['startTime']==0)?0:date('Y-m-d H:i:s',$var['startTime']);?></td>
								<td style="text-align: center;" data-name="endtime">
								<?php  echo ($var['endTime']==0)?0:date('Y-m-d H:i:s',$var['endTime']);?></td>
								 
								<td style="text-align: center;" data-name="createtime"><?php echo $var['createtime'];?></td>
								<td style="text-align: center;" data-name="rules">
								<button class="btn btn-link platformSIDLock"><button class="btn btn-link serverInfo">规则详情</button></td>
								<?php  
								$rulesOut = json_decode($var['rules'],true); 
								?>
								<td style="display: none" data-name="RequestData"><?php 
								echo  urldecode ( json_encode( $rulesOut ,JSON_PRETTY_PRINT) );?></td>
								<td data-name="type">
								<?php switch ((int)$var['type']){
									case 1:
										echo '商城推荐';
										break;
									case 2:
										echo '首冲';
										break;
									default: echo '未定义'; break;
									
								}?></td>								
								<td style="text-align: center;" data-name="status"><?php
								switch ((int)$var['status']){
									case 0: echo "待发布";
									break;
									case 1: echo "已发布";
									break;
									case 2: echo "发布失败";
									break;
									default: echo '异常';break;
								}
								?></td>
				                <td style="text-align: center;">				                 
				                <button class="btn btn-link editActivityBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">编辑</button>
				                <?php if ((int)$var['status']==0 || (int)$var['status']==2):?>
				                <button class="btn btn-link ReleaseMallBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">发布</button>
				                <?php endif;?>  
				                </td>                 
							</tr> 
				        <?php endforeach; ?> 
				    <!-- stat data end -->
				    </tbody>
				</table>
				<div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Adoptdhtml);?>
				</div>
				<?php endif; ?>
	        </div>
	        <!-- 发布失败 -->
	        <div id="tab3" class="tab-pane">
	        	<?php if (isset($Refuselist) && !empty($Refuselist)): ?>
	        	<table id="activityListTable"  class="table table-bordered table-striped" >
					<?php echo DevToolbox_Lib::MallTableHtml();?>
				    <tbody>
				    <!--统计数据项 begin-->
				      <?php $j = 0; ?>
				        <?php foreach ($Refuselist as $var): ?> 
				           <tr>      
				                <td style="text-align: center;" data-name="id"><?php echo $var['id'];?></td>
				                <td style="text-align: center;" data-name="platformId"><?php echo $var['platId'];?></td>
				                <td style="text-align: center;" data-name="serverId"><?php echo $var['serverId'];?></td>
								<td style="text-align: center;" data-name="title"><?php echo $var['title'];?></td>
								<td style="text-align: center;" data-name="content"><?php echo $var['description'];?></td>
								
								<td style="text-align: center;" data-name="starttime">
								<?php  echo ($var['startTime']==0)?0:date('Y-m-d H:i:s',$var['startTime']);?></td>
								<td style="text-align: center;" data-name="endtime">
								<?php  echo ($var['endTime']==0)?0:date('Y-m-d H:i:s',$var['endTime']);?></td>
								 
								<td style="text-align: center;" data-name="createtime"><?php echo $var['createtime'];?></td>
								<td style="text-align: center;" data-name="rules">
								<button class="btn btn-link platformSIDLock"><button class="btn btn-link serverInfo">规则详情</button></td>
								<?php  
								$rulesOut = json_decode($var['rules'],true); 
								?>
								<td style="display: none" data-name="RequestData"><?php 
								echo  urldecode ( json_encode( $rulesOut ,JSON_PRETTY_PRINT) );?></td>
								<td  data-name="type">
								<?php switch ((int)$var['type']){
									case 1:
										echo '商城推荐';
										break;
									case 2:
										echo '首冲';
										break;
									default: echo '未定义'; break;
									
								}?></td>
								<td style="text-align: center;" data-name="status"><?php
								switch ((int)$var['status']){
									case 0: echo "待发布";
									break;
									case 1: echo "已发布";
									break;
									case 2: echo "发布失败";
									break;
									default: echo '异常';break;
								}
								?></td>
				                <td style="text-align: center;">				                 
				                <button class="btn btn-link editActivityBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">编辑</button>
				                <?php if ((int)$var['status']==0 || (int)$var['status']==2):?>
				                <button class="btn btn-link ReleaseMallBtn" 
				                data-value="<?php echo $var['id'] ?>" data-server-id="<?php echo $var['serverId']?>">发布</button>
				                <?php endif;?>  
				                </td>                 
							</tr> 
				        <?php endforeach; ?>
				    <!-- stat data end -->
				    </tbody>
				</table>
				<div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Refusehtml);?>
				</div>
				<?php endif; ?>
	        </div>
        </div>
	  </div>
  </div>
<!-- 查询组件 begin-->
<!-- 查询组件 end-->
<br>
<input type="hidden">
<div style="overflow:scroll;">

</div>
</div>
<form action="create_mall_config_index" method="post" id="activityEditForm">
<input type="hidden" name="activityId"/>
</form>
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
<!--活动发布 -->
<?php $MallOut = Activity_Service::getMallInfo();?>
<div class="modal fade" id="ReleaseViewActivityModel" tabindex="-1" role="dialog" 
aria-labelledby="addUserModalLabel" aria-hidden="true" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">活动批量发布</h4>
            </div>
            <?php if (is_array($MallOut) && !empty($MallOut)) :?>		
            <form action="ReleaseMall" id='ReleaseMallForm' method="post">
	             <input type="hidden" name="batchMall" value="1"/>
	            <div class="control-group" >			    
					    <div class="controls" style="margin-left:40px">
					    <div class="control-group">
							 <select class="form-control"  id="liOption"  multiple="multiple"  
								  	name="ReleaseMallyOut[]" size='10' data-type-name='ReleaseActivityId'>
									<?php foreach ($MallOut as $var):?>
									<?php $varId = $var['id'];?>
									<?php echo '<option value='.$varId.' title="'.$var['description'].'">'.$var["title"].'</option>';?>
									<?php endforeach;?>									
							</select>							
					    </div> 
					</div> 
			  </div> 
            </form> 
            <div class="modal-footer">     
            	<button type="button" class="btn btn-primary" id="addMallBtn">确认发布</button>           
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
            <?php endif;?> 
            <?php if (empty($MallOut)):?> 
			<div class="alert">
				<button class="close" data-dismiss="alert"></button>
				<strong>Warning!</strong>暂时没有可发布的活动
			</div>  
			<?php endif;?>
        </div>
  </div>
</div>
 <!-- 活动导入文件模板 Begin-->
 <div class="modal fade" id="addFileModal" tabindex="-2" 
 role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addFileModalLabel"></h4>
            </div>
           <div class="modal-body">
                <div class="control-group">
                    <label class="col-md-3 control-label">
                    <input type="text" readonly="readonly" value="" id="state" style="margin:0px"/>
                    <input type="button" class="" value="..." id="selector" />&nbsp
                    <a href='#' id='loadActivityMode'>下载模板 xls</a>                    
                    <div class="controls" id="listsheet"> 
                    </div> 
                </div>                                
            </div>
            <div class="modal-footer" id="summodel"> 
                <button type="button" class="btn btn-success" id="addFileBtn">确认上传</button>
                <button type="button" class="btn btn-default" id="cancelBtn" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
 $(function(){
	 $(".activityFileBtn").click(function() {
	    	alert(11);
	        var param = $("#activityFileLoadForm").serialize();
	        $.ajax({
	            type: 'POST',
	            url: 'ImportactivityConfg',
	            data: param,
	            dataType: 'json',
	            success: function(result) {
	        		alert(result.msg);
	                if (result.errcode == 0) 
	                {
	                	history.go(0);
	                } 
	            }
	        });
	    });
});
</script>
<!-- 活动导入文件模板 End -->
<!-- forbiden account Modal -->
<div class="modal fade" id="forbidAccountModal" tabindex="-1" role="dialog" aria-labelledby="forbidAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="forbidAccountModalLabel">添加内容h</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="add"  method="POST" id="activityFileLoadForm">                     
                    <input type="hidden" name="listroleid"/>
                    <input type="hidden" name="addType" value="<?php echo !empty($type)?$type:0;?>"/>
                    <div class="control-group"> 
                    	 
                    </div>             
                </form>
            </div>
            <div class="modal-footer">     
                <button type="button" class="btn btn-danger" id="confirmBtn">确认执行</button> 
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<form  action="load_acitvity_mode" method="POST" id ="frommode">
	<input  type="hidden" name="modeName" value="activity_config.xls"/>
</form>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer();?>
