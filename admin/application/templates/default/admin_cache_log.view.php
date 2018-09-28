<?php 
$insert_html.= Page_Lib::loadJs('statdata');
$insert_html.= Page_Lib::loadJs('log','','tool');

echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>缓存日志记录</h1> 
        <div class="btn-group">                
        </div>
</div> 
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">系统缓存日志</a>   
 </div> 
<div class="widget-content"></div>
<div class="container-fluid">	
	<div class="row-fluid">
<!-- 条件选项菜单 begin-->
<?php echo DevToolbox_Lib::adminCacheHtml();?> 
<?php  if(!empty($Logdata)): ?>
<!-- 视图列表信息 begin-->
<table id="cachetableExcel" class="table table-striped table-bordered table-hover" >
<thead>
    <tr>
    	<th>ID</th>     	 
        <th>渠道</th>
        <th>功能</th> 
        <th>区服</th>
        <th>操作账号</th>
        <th>请求数据</th>
        <th>响应结果</th>        
        <th>创建日期</th>
        <th>最后执行日期</th>
        <th>状态</th>
        <th>操作</th>        
    </tr> 
</thead>
<tbody>	
	<?php if(is_array($Logdata) && count($Logdata)>0):?>
	<?php $cfg = Config::get("key.gm"); ?>
	<?php $errorCfg = Config::get("key.gm.error"); ?>	 
	<?php foreach ($Logdata as $var):?>	 
	<tr>
		<td style="text-align: center;"><?php echo $var['id'];?></td>
		<td style="text-align: center;">
		<?php  
		switch ($var['source'])
		{
			case 1:echo '网易';break;
			case 2:echo '萌宫坊';break;
			case 3:echo '账号操作';break;
			case 4:echo '萌宫坊系统缓存';break;
			default:echo ''; break;
		}
		?></td>		
		<td style="text-align: center;">
		<?php 
			echo  !empty($cfg[$var['protocolCode']])
			?
			$cfg[$var['protocolCode']]
			:
			"未知";
		?>
		</td>
		<td style="text-align: center;"><?php echo $var['server'];?></td>
		<td style="text-align: center;"><?php echo $var['account'];?></td>
		<td style="display: none" data-name="RequestData"><?php $requestJson = json_decode($var['requestData'],true);		
		echo json_encode( $requestJson ,JSON_PRETTY_PRINT);?></td>
		<td style="text-align: center;">
		<button class="btn btn-link serverInfo">请求数据详情</button>
		</td>
		<td style="text-align: center;"><?php echo !empty($var['responseData'])?$var['responseData']:"无返回";?></td>
		<td style="text-align: center;"><?php echo $var['createtime'];?></td>
		<td style="text-align: center;"><?php echo empty($var['lasttime'])?'暂无执行':$var['lasttime'];?></td>		 
		<td style="text-align: center;">
		<?php 
		switch ($var['status']){
			case -1:$statusinfo = '服务器无响应';break;
			case -2:$statusinfo = '执行失败无返回码';break;
			case 0:$statusinfo = '待执行';break;
			default:$statusinfo = '未知';break;
		}
		echo $statusinfo;?></td> 
		<td style='display: none' data-name='cacheid'><?php echo $var['id'] ?></td>
		<td  style="text-align: center;">		
		<button class="btn btn-link " id="delcache" >删除</button></td> 
	</tr>	 
	<?php endforeach;?>
	<?php endif;?>
</tbody>
</table>
<?php endif;?>
<!-- 视图列表信息  end-->
</div>
</div>
<!-- log info  -->
<div class="modal fade" id="cacheloginfoModal" tabindex="-1" role="dialog" aria-labelledby="cacheinfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">日志详细</h4> 
            </div>
            <form action="#" id='logInfoForm'>
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
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 版权信息begin -->
<?php echo Page_Lib::footer();?>
<!-- 版权信息 end -->
 
