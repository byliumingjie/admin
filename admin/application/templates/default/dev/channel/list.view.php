<?php
$insert_html .= Page_Lib::loadJs('channel-config', '', 'dev');
$insert_html .= Page_Lib::loadJs('channel-config-load-file', '', 'dev');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('ajaxupload');
echo Page_Lib::head($insert_html, '', null, true);
?>
<!-- title set tool begin-->
<div id="content-header">
    <h1>渠道配置</h1>
    <div class="btn-group">
        <!--<a class="btn btn-success btn-large" title="批量发布"
           data-backdrop="static" id="addName">
            <i class="icon-plus"></i>导入充值配置</a>-->
        <a class="btn btn-large tip-bottom" title="添加用户"
           data-toggle="modal" data-backdrop="static" data-target="#addPayConfigModal" id="addPayConfigId">
            <i class="icon-plus"></i>添加配置</a>
    </div>
</div>
<!-- >top title begin-->
<div id="breadcrumb">
    <!-- 坐标系 -->
    <a href="/index/index" title="跳到首页" class="tip-bottom">
        <i class="icon-home"></i> 首页</a>
    <a href="#" class="current">渠道配置</a>
</div>
<div class="container-fluid">
    <br>
    <!-- warning info begin -->
    <?php if (empty($payObject) && isset($payObject)): ?>
    <div class="widget-content" id="roleinfo">
        <div class="alert">
            <button class="close" data-dismiss="alert">×</button>
            <strong>Warning!</strong><?php echo "无配置数据"; ?>.
        </div>
        <?php endif; ?>

        <?php if (!empty(payObject)): ?>
        <table id="tableExcel" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>渠道名称</th>
                <th>渠道码</th>
                <th>token</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($config_data as $inkey => $invar): ?>
                <tr>
                    <td style="text-align: center;" data-name="id"><?php echo $invar['id']; ?></td>
                    <td style="text-align: center;" data-name='channel_name'><?php echo $invar['channel_name']; ?></td>
                    <td style="text-align: center;" data-name='channel_name'><?php echo $invar['channel_code']; ?></td>
                    <td style="text-align: center;"
                        data-name="channel_token"><?php echo $invar['channel_token']; ?></td>
                    <td style="text-align: center;" data-name="create_at"><?php echo $invar['create_at']; ?></td>
                    <td style=" text-align: center;">
                        <a href="#" class="tip-top editpayConcif" data-original-title="编辑"
                           data-value="<?php echo $invar['id'] ?>">
                            <i class="icon-cog"></i></a>
                        <a href="#" class="tip-top delpayConfig" data-original-title="删除"
                           data-value="<?php echo $invar['id'] ?>">
                            <i class="icon-remove"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!-- lod file infoend -->
        <!-- 分页组件 begin -->
        <div class="row center" style="text-align: center;">
        </div>
    </div>
</div>
<?php endif; ?>
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
                        <input type="button" class="" value="..." id="selector"/>&nbsp
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
     aria-labelledby="EditConfigLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="addUserModalLabel">配置更改</h3>
            </div>

            <div class="modal-body" style="max-height:40%">
                <form class="form-horizontal" action="#" method="POST" id="editpayconfigForm">
                    <input type="hidden" name='id'/>
                    <!--<div class="control-group">
                        <label class="control-label">游戏昵称：*</label>
                        <div class="controls"><input type="text"
                                                     style="width:62%" class="input-mini" name="pc"/>
                        </div>
                    </div>-->
                    <div class="control-group">
                        <label class="control-label">渠道：*</label>
                        <div class="controls"><input type="text"
                                                     style="width:62%" class="input-mini" name="channel_name"/>
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
                        data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="addMenuModalLabel">添加配置</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#"
                      method="POST" id="addPayConfigForm" onsubmit="return false;">
                    <div class="control-group">
                        <label class="control-label">游戏昵称：*</label>
                        <div class="controls"><input type="text"
                                                     style="width:62%" class="input-mini" name="channel_name"/>
                        </div>
                    </div>
                    <!--<div class="control-group">
                        <label class="control-label">渠道：*</label>
                        <div class="controls"><input type="text"
                                                     style="width:62%" class="input-mini" name="channel"/>
                        </div>
                    </div>-->
                    <!--<div class="control-group">
                        <label class="control-label">活动状态：*</label>
                        <div class="controls"><input type="text" style="width:62%" class="input-mini"
                                                     name="act_status"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">活动状态：*</label>
                        <div class="controls">
                            <select class="form-control" name="if_special_get">
                                <option value="0">关</option>
                                <option value="1">开</option>
                            </select>
                        </div>
                    </div>-->

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addPayCofnigBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<?php echo Page_Lib::footer(null, true); ?>
