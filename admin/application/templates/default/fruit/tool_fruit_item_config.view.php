<?php
$insert_html .= Page_Lib::loadCss("uniform");
$insert_html .= Page_Lib::loadJs("ajaxupload");
$insert_html .= Page_Lib::loadJs('unicorn.form_common');
$insert_html .= Page_Lib::loadJs('fruit.item.prizes');
$insert_html .= Page_Lib::loadJs('jquery.uniform');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
	<h1>道具配置</h1>
	<div class="btn-group">                 
		<a class="btn btn-large tip-bottom" title="添加菜单" data-toggle="modal" data-backdrop="static" 
		data-target="#addItemModal" id="addMenu"><i class="icon-plus"></i>添加道具</a>
	</div>
</div> 
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">道具列表</a>
</div>
<div class="container-fluid">
<?php echo DevToolbox_Lib::fruitItemConfigHtml();?>
<!-- 查询组件 begin-->
<!-- 查询组件 end-->
<?php if (isset($object) && !empty($object)): ?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead >
    <tr>
        <th>ID</th>
        <th>道具名称</th>
        <th>道具类型</th>											
        <th>打开后获得</th>
        <th>描述</th>
        <th>图标</th> 
        <th>操作</th>     
        <!-- <th>累计在线时长</th> --> 					
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin-->
         <?php 
             $page = Config::get('common.page');
             $imgdir  = $page['host'].'//frut_item_img//';
         ?>
        <?php foreach ($object as $var): ?> 
            <tr>      
                <td style="text-align: center;" class="id"><?php  echo $var['id'];?></td>
                <td style="text-align: center;" class="itme_name"><?php  echo $var['itme_name'];?></td>   
                <td style="text-align: center;" class="item_type"><?php  echo $var['item_type'];?></td>
                <td style="text-align: center;"	class="obtain"><?php  echo $var['obtain'];?></td>
                <td style="text-align: center;"	class="desc"><?php  echo $var['desc'];?></td>
                <td style="display: none" class="image"><?php echo $var['image'];?></td>
                <td style="text-align: center;"> 
                <a href="<?php echo $imgdir.$var['image'];?>"><img width="20" height="20" src="<?php echo $imgdir.$var['image'];?>"/>
                </td></a>                 
                <td style="text-align: center;">
                <button class="btn btn-link editItem" id="editItem"
                data-value="<?php echo $var['id'] ?>">编辑</button>
                <button class="btn btn-link delItem" 
                data-value="<?php echo $var['id'] ?>" id="delItem">删除</button>                
                </td> 
			</tr> 
        <?php endforeach; ?> 
    <!-- stat data end -->
    </tbody>
</table>
<?php endif;?>
</div>
<!-- Modal -->
<!-- edit begin -->
 
 <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" 
aria-labelledby="addItemModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addMenuModalLabel">添加道具</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" 
                		id="itemForm"  action="addItem"enctype="multipart/form-data">
                	<div class="control-group">
                        <label class="col-md-4 control-label">道具ID：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="itemId" /></div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">道具名称：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="itemName" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">道具类型：</label>
                        <div class="controls">
                         <select class="form-control"style='' name='itemType'>
				          <option value='0'>红包</option> 
				          <option value='1'>话费</option>
						  <option value='2'>实物</option>
				        </select> 	        
                      </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">打开后获得:</label>
                        <div class="controls">
                         <select class="form-control"style="width:100px" name='itemoBtainType'>
				          <option value='ITEM'>道具</option> 
				          <option value='TICKET'>奖券</option>
						  <option value='GOLD'>金币</option>
				        </select>&nbsp<input type="text"style="width:100px" 
				        placeholder="数量" class="form-control" name="itemBtainNum"/>
                        </div>                         
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">描述：</label>
                        <div class="controls">
                        	<textarea class="form-control" name="itemdesc"></textarea>
                        </div>
                    </div>	                    
                     <div class="control-group">
							<label class="col-md-4 control-label">图标：</label>
							<div class="controls">
								<input type="file" name='itemImage'/>
							</div>
					</div>
					</form> 
            </div> 
             <div class="modal-footer">
                	<button type="button" id="ItemconfigBtn" class="btn btn-primary" >确认添加</button>
                	<button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editMenuModalLabel">修改道具</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="ItemEdit" method="POST" id="editItemForm" onsubmit="return false;">
                	<input type="hidden" class="form-control" name="originalImg" />
                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">ID:</label>
                        <div class="controls"><input  type="text" readonly='readonly' 
                        class="form-control" name="id" /></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">道具名称:</label>
                        <div class="controls"><input  type="text" 
                        class="form-control" name="itme_name" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">道具类型:</label>
                        <div class="controls">
                        <select class="form-control"style='' name='item_type'>
				          <option value='0'>红包</option> 
				          <option value='1'>话费</option>
						  <option value='2'>实物</option>
				        </select>
                    </div>                    
                     <div class="control-group">
                        <label class="col-md-4 control-label">获得:</label>
                        <div class="controls">
                         <select class="form-control"style="width:100px" name='itemoBtainType'>
				          <option value='ITEM'>道具</option> 
				          <option value='TICKET'>奖券</option>
						  <option value='GOLD'>金币</option>
				        </select>&nbsp<input type="text"style="width:100px" 
				        placeholder="数量" class="form-control" name="itemBtainNum"/>				        
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">描述:</label>
                        <div class="controls"><input  type="text" 
                        class="form-control" name="desc" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">图标(默认不选为原始)：</label>
						<div class="controls">
							<input type="file" name='image'/>							
						</div>
                    </div>                     
                </form>
            </div> 
        </div>
         <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editMenuBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
    </div>
</div> 
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer();?>
