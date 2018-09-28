<?php
$insert_html = Page_Lib::loadJs('server');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
     <h1>白名单首页</h1>
	<!-- 页面按钮集合 -->
<div class="btn-group">
            <a class="btn btn-large tip-bottom" title="添加白名单" data-toggle="modal" data-backdrop="static" data-target="#addWhiteListModal" id="addWhiteList"><i class="icon-plus"></i> 添加白名单</a>
</div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus">    
            <?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?></a>
    <a href="#" class="current">白名单列表</a>
</div>
<!-- 站内导航 结束 -->
        <div class="container-fluid">	
	<div class="row-fluid">
            <table class="table table-striped table-bordered table-hover" id="whiteTable">
                <tr>
                    <th>uin</th>
                    <th>帐号</th>
                    <th>添加时间</th>
                    <th>描述</th>	
                    <th>操作</th>					
                </tr>
                <?php if (is_array($whitelist) && !empty($whitelist)): ?>
                    <?php foreach ($whitelist as $data): ?>
                <tr id="<?php echo $data['uin']; ?>" >
                            <td data-name="uin" style="text-align: center;"><?php echo $data['uin']; ?></td>                            
                            <td data-name="accountid" style="text-align: center;"><?php echo $data['accountid']; ?></td>
                            <td data-name="time" style="text-align: center;"><?php echo $data['time']; ?></td>
                            <td data-name="desc" style=" text-align: center;"><?php echo $data['desc']; ?></td>   
                            <td style=" text-align: center;">
                <?php if ($deleteWhiteAccount): ?><button class="btn btn-link deleteWhiteAccount" >删除</button><?php endif; ?>
                            </td>						
                </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer(); ?>

<!-- addWhiteList Begin -->
<div class="modal fade" id="addWhiteListModal" tabindex="-1" role="dialog" aria-labelledby="addWhiteListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addWhiteListModalLabel">添加平台白名单</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/server/addWhiteList" method="POST" id="addWhiteListForm" onsubmit="return false;">
                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">用户标识(uin)：</label>
                        <div class="controls"><input type="text" class="form-control" name="uin" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">备注：</label>
                        <div class="controls"><input type="text" class="form-control" name="desc" required="" autofocus=""/></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addWhiteListBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
  	</div>
      </div>
 </div>

