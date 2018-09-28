<?php
$insert_html .= Page_Lib::loadJs('pay-config','','gm');
$insert_html .= Page_Lib::loadJs('pay-config-load-file','','gm');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('ajaxupload');
echo Page_Lib::head($insert_html,'',null,true);
?>
<!-- title set tool begin-->
<div id="content-header">
    <h1>充值配置</h1>
    <div class="btn-group">        
		<a class="btn btn-success btn-large" title="批量发布"  
    	data-backdrop="static" id="addName">
		<i class="icon-plus"></i>导入充值配置</a>
		<a class="btn btn-large tip-bottom" title="添加用户" 
		data-toggle="modal" data-backdrop="static" data-target="#addPayConfigModal" id="addPayConfigId">
		<i class="icon-plus"></i>添加充值配置</a>
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
<?php if(empty($payObject) && isset($payObject)):?>
<div class="widget-content" id="roleinfo">		 
		<div class="alert">
			<button class="close" data-dismiss="alert">×</button>
			<strong>Warning!</strong><?php echo "无配置数据";?>.
		</div>	 
<?php endif;?>
 
	<!-- lod file info -->  
	<?php  if(!empty(payObject)): ?>
	<table id="tableExcel" class="table table-striped table-bordered table-hover" >
		<thead>
		    <tr>
		   		<th>Id</th>
		    	<th>金额</th> 		    	       
		        <th>基础棒棒糖奖励</th>
		        <th>额外棒棒糖奖励</th>			        
		        <th>是否首次特殊获得</th>
		        <th>特殊获得棒棒糖奖励</th>		               
		        <th>产品ID</th>
		        <th>操作</th>		              
		    </tr> 
		</thead>
		<tbody>
			<?php foreach ($payObject as $inkey =>$invar):?>			 
			<tr>
			<td style="text-align: center;" data-name="id"><?php echo $invar['id'];?></td> 
			<td style="text-align: center;"data-name='fee'><?php echo $invar['fee'];?></td>
			<td style="text-align: center;" data-name="diamond"><?php echo $invar['diamond'];?></td>
			<td style="text-align: center;" data-name="extra_diamond"><?php echo $invar['extra_diamond'];?></td>
			<td style="text-align: center;" data-name="if_special_get"><?php echo $invar['if_special_get'];?></td>
			<td  style="text-align: center;" data-name="special_diamond"><?php echo $invar['special_diamond'];?></td>
			<td  style="text-align: center;" data-name="product_id"><?php echo $invar['product_id'];?></td>
			<td style=" text-align: center;">
	        <a href="#" class="tip-top editpayConcif" data-original-title="编辑"
	        data-value="<?php echo $invar['id'] ?>">
	        <i class="icon-cog"></i></a> 
			<a href="#" class="tip-top delpayConfig" data-original-title="删除" 
			data-value="<?php echo $invar['id'] ?>" >
			<i class="icon-remove"></i></a>
			</td>						 
			</tr>	
		<?php endforeach;?>
		</tbody> 
		</table>
		<!-- lod file infoend -->
	<!-- 分页组件 begin -->
	<div class="row center" style="text-align: center;">
	</div>
	</div>
</div>
<?php endif;?>
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
                    <!-- <a href='#' id='loadActivityMode'>下载模板 xls</a>   -->                  
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
</div>
<div class="modal fade" id="EditConfigModal" tabindex="-1" role="dialog" 
aria-labelledby="EditConfigLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="addUserModalLabel">充值配置更改</h3>
            </div>
           
            <div class="modal-body" style="max-height:40%">           
                <form class="form-horizontal" action="#" method="POST" id="editpayconfigForm">
                	<input type="hidden" name='id'/>
                    <div class="control-group">
                        <label class="control-label">金额：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="fee"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">基础棒棒糖奖励：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="diamond"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">额外棒棒糖奖励：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="extra_diamond"/>
					    </div>
                    </div>                    
                    <div class="control-group">
                        <label class="control-label">是否首次特殊获得：</label>
                        <div class="controls">
                            <select class="form-control" name="if_special_get">
                                <option value="0">否</option>
                                <option value="1">是</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">特殊获得棒棒糖奖励：*</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="special_diamond"/>
					    </div>
                    </div> 
                     <div class="control-group">
                        <label class="control-label">产品ID：*</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="product_id"/>
					    </div>
                    </div>
                
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editPayBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
            
        </div>
    </div>
</div>

<!-- add server Begin -->
<div class="modal fade" id="addPayConfigModal" tabindex="-1" role="dialog" 
aria-labelledby="addPayconfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addMenuModalLabel">添加充值配置</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" 
                method="POST" id="addPayConfigForm" onsubmit="return false;">                
                   <div class="control-group">
                        <label class="control-label">金额：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="fee"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">基础棒棒糖奖励：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="diamond"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">额外棒棒糖奖励：*</label>
                       <div class="controls"> <input type="text" 
                       style="width:62%" class="input-mini" name="extra_diamond"/>
					    </div>
                    </div>                    
                    <div class="control-group">
                        <label class="control-label">是否首次特殊获得：</label>
                        <div class="controls">
                            <select class="form-control" name="if_special_get">
                                <option value="0">否</option>
                                <option value="1">是</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">特殊获得棒棒糖奖励：*</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="special_diamond"/>
					    </div>
                    </div> 
                     <div class="control-group">
                        <label class="control-label">产品ID：*</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="product_id"/>
					    </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addPayCofnigBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<?php echo Page_Lib::footer(null,true); ?>
