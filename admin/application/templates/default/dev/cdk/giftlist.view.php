<?php
$insert_html=Page_Lib::loadJs('ajaxupload');
$insert_html.=Page_Lib::loadJs('cdk');

echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>礼包列表</h1> 
         
        <div class="btn-group"> 
		<a class="btn btn-large tip-bottom" title="新增礼包" id="addName" href="index">
          <i class="icon-plus"></i>新增礼包</a>         
        </div>
</div>
<!-- top begin-->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">礼包列表</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    <?php //echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
    <i class="icon-question"></i></a>
 </div>

<!-- top end -->
<div class="widget-content">
<!-- 条件选项菜单 begin-->
<?php echo DevToolbox_Lib::cdkHtml($object);?>
<!-- 条件选项菜单  end-->

<!-- 视图列表信息 begin-->
<?php if (!empty($object)):?>
<div style="overflow:scroll;">
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr> 
    	<th>礼包编号Id</th>
        <th>礼包描述</th>
        <th>礼包标题</th>
        <th>礼包内容</th>
        <th>礼包奖励信息</th>  
        <th>生成日期</th>
        <th>操作</th>
    </tr> 
</thead>
<tbody>
	<?php if($object):?>
	<?php foreach($object as $var):?>
	<tr>
		<td style="text-align: center;" class='id'><?php echo $var['id'];?></td>
		<td	style="text-align: center;" class='bewrite'><?php echo $var['bewrite'];?></td>
		<td	style="text-align: center;" class='title'><?php echo $var['title'];?></td>
		<td	style="text-align: center;"	class='context'><?php echo $var['context'];?></td>		
		<td	style="text-align: center;" class='ItemList'><?php echo $var['ItemList'];?></td>
		<td	style="text-align: center;" class='datetime'><?php echo $var['datetime'];?></td>
		<td	><button class="btn btn-link editMenu">编辑</button>
		<button class="btn btn-link delUser" data-value="<?php echo $var['id'] ?>" >删除</button></td>
	</tr>
	<?php 
	/* $dat.=$var['id']."=".$var['bewrite']."=".$var['title']."=".$var['context']."=".
	$var['ItemList']."=".$var['datetime'].","; */
	?>
	<?php endforeach;?>
	<?php endif;?>
</tbody>
</table>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>

 
<!-- 视图列表信息  end-->
</div>
<input type="hidden" id="selector">
<!-- edit begin -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editMenuModalLabel">修改活动</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="gifedit" method="POST" id="editMenuForm" onsubmit="return false;">
                	<input type="hidden" class="form-control" name="addType"  value='0'/>
                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">ID:</label>
                        <div class="controls"><input  type="text" readonly='readonly' 
                        class="form-control" name="id" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">礼包名称:</label>
                        <div class="controls"><input  type="text" 
                        class="form-control" name="bewrite" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">礼包标题:</label>
                        <div class="controls"><input  type="text" 
                        class="form-control" name="title" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">礼包内容:</label>
                        <div class="controls">
                        <textarea name ="context" rows="" cols=""></textarea>
                         </div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-4 control-label">生成日期:</label>
                        <div class="controls"><input  type="text" 
                        class="form-control" name="datetime" readonly='readonly'  autofocus=""/></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-4 control-label">奖励信息:</label>
                        <div class="controls"><textarea  rows="10" 
                        class="form-control" name="ItemList" ></textarea></div>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editMenuBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
    </div> 
 <!-- end edit -->
 

<!-- 分页组件 end -->
</div>

<?php 
 
$key ="ID"."\t"."礼包描述"."\t"."礼包标题"."\t"."礼包内容"."\t"."道具信息"."\t"."生成日期";
// echo $key;
?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $dat;?>"/>	                    
</form> 

<!-- 版权信息begin -->
<?php echo Page_Lib::footer();?>
<!-- 版权信息 end -->