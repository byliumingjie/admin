<?php
$insert_html .= Page_Lib::loadJs('global-config', '', 'dev');
$insert_html .= Page_Lib::loadJs('global-config-load-file', '', 'dev');
$insert_html .= Page_Lib::loadCss('bootstrap-select.min');
$insert_html .= Page_Lib::loadJs('ajaxupload');
$insert_html .= Page_Lib::loadJs('loadmailfile');
$insert_html .= Page_Lib::loadJs('mail');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('bootstrap-select');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
    <h1>后台基本操作</h1>
    <div class="btn-group">
    </div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">新建配置</a>
    <a href="#" title="..."
       data-placement="bottom" data-trigger="focus"
       class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>

<?php $channel_ary = Channel_Model::channelList(); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="widget-box">
            <div class="widget-title">
	<span class="icon">
            <i class="icon-th"></i>
        </span>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab1">活动配置</a></li>
                </ul>

            </div>
            <div class="widget-content tab-content">
                <!-- 1页表格 正文 -->
                <div id="tab1" class="tab-pane active">
                    <form class="form-horizontal" method="POST" id="addPayConfigForm" onsubmit="return false;">
                        <table class="table  table-striped">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="col-md-3 control-label">*游戏昵称</label>
                                        <div class="controls">
                                            <input type="text" style="width:62%" class="input-mini" name="pc_name"/>
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">*渠道</label>
                                        <div class="controls">
                                            <select class="form-control" id="channelOpeion" multiple="multiple"
                                                    name="channel[]"
                                                    size='10'>
                                                <?php if (is_array($channel_ary) && !empty($channel_ary)) : ?>
                                                    <?php foreach ($channel_ary as $server): ?>
                                                        <?php echo '<option value="' . $server['id'] . '">' . $server["channel_name"] . '</option>'; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                </td>
                                <td></td>
                            </tr>
                            <!-- <tr>
                                 <td>
                                     <div class="control-group">
                                         <label class="col-md-3 control-label">APP ID </label>
                                         <div class="controls">
                                             <input type="text" style="width:62%" class="input-mini" name="appid"/>
                                         </div>
                                     </div>
                                 </td>
                                 <td>
                                 </td>
                             </tr>-->
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="col-md-3 control-label">区服前缀:</label>
                                        <div class="controls">
                                            <input type="text" style="width:62%" class="input-mini"
                                                   name="server_prefix"/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="col-md-3 control-label">区服起始:</label>
                                        <div class="controls">
                                            <input type="text" style="width:62%" class="input-mini"
                                                   name="server_min"/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="col-md-3 control-label">区服最大范围:</label>
                                        <div class="controls">
                                            <input type="text" style="width:62%" class="input-mini"
                                                   name="server_max"/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <div class="control-group">
                                            <label class="col-md-3 control-label">添加附件:</label>
                                            <div class="controls">
                                                <span style="color:gray">附件个数不能超过四个</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                            <div class="controls">
                                                排行榜活动 : <input type="checkbox" value="ranking" name="checkbox[]"
                                                               style="margin: 0px; padding: 0px">&nbsp;&nbsp;
                                                起始时间:<input type="text" class=" datetimepicker" name="ranking_starttime"
                                                            style="width:25%" placeholder="起始时间"/>-
                                                <input type="text" class=" datetimepicker " name="ranking_endtime"
                                                       style="width:25%" placeholder="截至时间"/>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                            <div class="controls">
                                                抽奖活动 : <input type="checkbox" value="lottery" name="checkbox[]"
                                                              style="margin: 0px; padding: 0px">&nbsp;&nbsp;
                                                起始时间:<input type="text" class=" datetimepicker" name="lottery_starttime"
                                                            style="width:25%" placeholder="起始时间"/>-
                                                <input type="text" class=" datetimepicker " name="lottery_endtime"
                                                       style="width:25%" placeholder="截至时间"/>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                            <div class="controls">
                                                签到活动 : <input type="checkbox" value="signin" name="checkbox[]"
                                                              style="margin: 0px; padding: 0px">&nbsp;&nbsp;
                                                起始时间:<input type="text" class=" datetimepicker" name="signin_starttime"
                                                            style="width:25%" placeholder="起始时间"/>-
                                                <input type="text" class=" datetimepicker " name="signin_endtime"
                                                       style="width:25%" placeholder="截至时间"/>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php //echo DevToolbox_Lib::mailModuleHtml($configinfo); ?>
                            </tbody>
                        </table>
                        <?php //if(!empty($addPerMail)):?>
                        <div style="text-align: center;">
                            <button class="btn btn-success" id="addPayCofnigBtn" style="margin: auto;">保存</button>
                        </div>
                        <?php //endif;?>
                    </form>
                </div>
                <!-- 2页表格 正文 -->
                <div id="tab2" class="tab-pane ">
                    <form class="form-horizontal" method="POST" id="saveSerMailForm" onsubmit="return false;">
                        <table class="table  table-striped">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="control-group">
                                        <div class="buttons">
                                            <a title="导入用户" class="btn btn-success" id="loadUserBtn"><i
                                                        class="icon-plus"></i> 导入批量用户</a>
                                        </div>
                                    </div>

                                </td>
                                <td></td>
                            </tr>
                            <?php echo DevToolbox_Lib::mailModuleHtml($configinfo); ?>
                            </tbody>
                        </table>
                    </form>

                    <div style="text-align: center;">
                        <button class="btn btn-success" id="saveSerBtn" style="margin: auto;">保存</button>
                    </div>

                </div>
                <!-- 3页表格 正文 -->
                <div id="tab3" class="tab-pane ">
                    <form class="form-horizontal" method="POST" id="saveAllMailForm" onsubmit="return false;">
                        <table class="table  table-striped">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="liOption" multiple="multiple" name="sid[]"
                                                size='10' style="width:150%">
                                            <?php if (is_array($serverInfo) && !empty($serverInfo)) : ?>
                                                <?php foreach ($serverInfo as $server): ?>
                                                    <?php if ($server['type'] == 0) {
                                                        continue;
                                                    } ?>
                                                    <?php echo '<option value="' . $server['type'] . '">' . $server["type"] . '服 ' . $server['platformname'] . '</option>'; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">*筛选条件</label>
                                        <label class="checkbox-inline">
                                            <div class="controls">
                                                角色等级段
                                                <input type="text" class="  form-inline " name="minlevel"
                                                       style="width:15%" placeholder="1"/>级-
                                                <input type="text" class="  form-inline " name="maxlevel"
                                                       style="width:15%" placeholder="100"/>级
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group ">
                                        <label class="control-label">*邮件有效期时间</label>
                                        <label class="checkbox-inline">
                                            <div class="controls ">

                                                <input type="text" class=" datetimepicker" name="starttime"
                                                       style="width:25%" placeholder="起始时间"/>-
                                                <input type="text" class=" datetimepicker " name="endtime"
                                                       style="width:25%" placeholder="截至时间"/>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <?php echo DevToolbox_Lib::mailModuleHtml($configinfo); ?>
                            </tbody>
                        </table>
                    </form>
                    <div style="text-align: center;">
                        <button class="btn btn-success" id="saveAllBtn" style="margin: auto;">保存</button>
                    </div>
                </div>

                <!-- 4页表格 正文 -->
                <div id="tab4" class="tab-pane ">
                    <form class="form-horizontal" method="POST" id="saveReimburseMailForm"
                          action="addReimburseMail"
                          onsubmit="return false;">
                        <table class="table  table-striped">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" id="liEditOption" multiple="multiple" name="sid[]"
                                                size='10' style="width:150%">
                                            <?php if (is_array($serverInfo) && !empty($serverInfo)) : ?>
                                                <?php foreach ($serverInfo as $server): ?>
                                                    <?php if ($server['type'] == 0) {
                                                        continue;
                                                    } ?>
                                                    <?php echo '<option value="' . $server['type'] . '">' . $server["type"] . '服 ' . $server['platformname'] . '</option>'; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group ">
                                        <label class="control-label">*补偿类型</label>
                                        <label class="checkbox-inline">
                                            <div class="controls ">
                                                <select class="form-control" name="HeadreviewType">
                                                    <option value=''>--请选择补偿类型--</option>
                                                    <option value=1>头像审核拒绝</option>
                                                    <option value=2>头像审核通过</option>
                                                </select>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr>

                            <!-- <tr>
                                <td>
                                    <div class="control-group ">
                                        <label class="control-label">*邮件有效期时间</label>
                                        <label class="checkbox-inline">
                                        <div class="controls ">

                                                <input type="text" class=" datetimepicker" name="starttime" style="width:25%" placeholder="起始时间"/>-
                                                <input type="text" class=" datetimepicker " name="endtime" style="width:25%" placeholder="截至时间"/>
                                        </div>
                                        </label>
                                    </div>
                                </td>
                                <td></td>
                            </tr> -->
                            <?php echo DevToolbox_Lib::mailModuleHtml($configinfo); ?>
                            </tbody>
                        </table>

                    </form>

                    <div style="text-align: center;">
                        <button class="btn btn-success" id="saveReimburseBtn" style="margin: auto;">保存</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<?php
echo Page_Lib::footer();
?>
<!-- 群发邮件用户批量导入 -->
<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addFileModalLabel">选择文件</h4>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label class="col-md-3 control-label">
                        <input type="button" class="btn btn-success" value="导入txt文件" id="selector"/>
                    </label>
                    <div class="controls">
                        <input type="text" readonly="readonly" value="" id="state"/>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addFileBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    //检查输入的值
    function checkProp(value, type) {
        if (value !== '') {
            $.ajax({
                type: 'POST',
                url: "/mail/CheckProp",
                data: 'id=' + value + '&type=' + type,
                dataType: 'json',
                success: function (result) {
                    if (result.errcode != 0) {
                        alert('输入的ID' + value + '无效');
                        document.location.reload();
                    }
                }
            });
        }

    }

    var handleRegister = function () {

        function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='assets/img/flags/" + state.id.toLowerCase() + ".png'/>  " + state.text;
        }


        $("#select2_sample4").select2({
            placeholder: '<i class="fa fa-map-marker"></i> Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });
</script>