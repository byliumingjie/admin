<?php
$insert_html .= Page_Lib::loadJs('global-config', '', 'dev');
$insert_html .= Page_Lib::loadJs('global-config-load-file', '', 'dev');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('ajaxupload');

$insert_html .= Page_Lib::loadCss('bootstrap-select.min');
$insert_html .= Page_Lib::loadJs('loadmailfile');
$insert_html .= Page_Lib::loadJs('bootstrap-select');

echo Page_Lib::head($insert_html, '', null, true);
?>
<?php $platformOut = System_Service::getplatformInfo(); ?>
<!-- title set tool begin-->
<div id="content-header">
    <h1>活动配置</h1>
    <div class="btn-group">
       <!-- <a class="btn btn-success btn-large" title="批量发布"
           data-backdrop="static" id="addName">
            <i class="icon-plus"></i>导入充值配置</a>-->
        <!--<a class="btn btn-large tip-bottom" title="添加用户" href="createConfig"
           data-toggle="modal" data-backdrop="static" data-target="#addPayConfigModal" id="addPayConfigId">-->
        <a class="btn btn-success btn-large" title="添加用户" href="createConfig">
            <i class="icon-plus"></i>添加配置</a>
    </div>
</div>
<!-- >top title begin-->
<div id="breadcrumb">
    <!-- 坐标系 -->
    <a href="/index/index" title="跳到首页" class="tip-bottom">
        <i class="icon-home"></i> 首页</a>
    <a href="#" class="current">活动配置</a>
</div>

<?php $channel_ary = Channel_Model::channelList(); ?>

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

        <?php if (!empty($config_data)): ?>
        <table id="tableExcel" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>游戏</th>
                <th>渠道</th>
                <th>活动状态</th>
                <th>活动类型</th>
                <th>区服前缀</th>
                <th>server min</th>
                <th>server max</th>
                <th>活动配置</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($config_data as $inkey => $invar): ?>
                <tr>
                    <td style="text-align: center;" data-name="id"><?php echo $invar['id']; ?></td>
                    <td style="text-align: center;" data-name='pc'><?php echo $invar['pc']; ?></td>
                    <td style="text-align: center;" data-name="channel_id"><?php echo $invar['channel_id']; ?></td>
                    <td style="text-align: center; display: none"
                        data-name="act_status"><?php echo $invar['act_status']; ?></td>
                    <td style="text-align: center;display: none"
                        data-name="act_type"><?php echo $invar['act_type']; ?></td>
                    <!---->
                    <td style="text-align: center;"><?php echo GlobalConfig_Model::$ACT_STATUS[$invar['act_status']]; ?></td>
                    <td style="text-align: center; "><?php echo GlobalConfig_Model::$ACT_TYPE[$invar['act_type']]; ?></td>
                    <!---->
                    <td style="text-align: center;" data-name="sign"><?php echo $invar['server_prefix']; ?></td>
                    <td style="text-align: center;" data-name="server_min"><?php echo $invar['server_min']; ?></td>
                    <td style="text-align: center;" data-name="server_max"><?php echo $invar['server_max']; ?></td>
                    <td style="text-align: center;">
                        <button class="btn btn-link serverInfo">请求数据详情</button>
                    </td>
                    <td style="text-align: center;" data-name="create_at"><?php echo $invar['create_at']; ?></td>
                    <td style="text-align: center; display: none"
                        data-name="act_rule"><?php echo json_encode(json_decode($invar['act_rule'], true), JSON_PRETTY_PRINT); ?></td>


                    <td style=" text-align: center;">
                        <!--<a href="#" class="tip-top editpayConcif" data-original-title="编辑"
                           data-value="<?php /*echo $invar['id'] */?>">
                            <i class="icon-cog"></i></a>-->
                        <a href="#" class="tip-top delpayConfig" data-original-title="删除"
                           data-value="<?php echo $invar['id'] ?>">
                            <i class="icon-remove"></i></a>
                        <a href="#" class="tip-top loadConfig" data-original-title="上传"
                           data-value="<?php echo $invar['id'] ?>">
                            <i class="icon-upload"></i></a>
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
                    <div class="control-group">
                        <label class="control-label">游戏昵称：*</label>
                        <div class="controls"><input type="text"
                                                     style="width:62%" class="input-mini" name="pc"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">渠道：*</label>
                        <div class="controls">
                            <!--<input type="text" style="width:62%" class="input-mini" name="channel_id"/>-->
                            <select id="channelId" name="channel_id"
                                    class="selectpicker" data-live-search="true" data-live-search-style="begins">
                                <option value="0">--渠道信息--</option>
                                <?php foreach ($channel_ary as $var): ?>
                                    <?php $id = $var['id'];
                                    $name = $var['channel_name'];
                                    ?>
                                    <option value="<?php echo $id ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <br><br><br><br>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editPayBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>

        </div>
    </div>
</div>
<!-- log info  -->
<div class="modal fade" id="loginfoModal" tabindex="-1" role="dialog" aria-labelledby="logInfoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">活动规则详细</h4>
                <!-- <div class="widget-title">
                <div class="buttons"><a href="#" class="btn btn-mini">
                <i class="icon-random" id="originalSwap"></i> 原始数据</a></div></div> -->
            </div>
            <form action="#" id='logInfoForm'>
                <div class="modal-body">
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">
                            <textarea cols="3" name="act_rule" class="form-control"
                                      style="margin: 0px 0px 10px; width: 512px; height: 100px;"></textarea>
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
                        <div class="controls"><input type="text" style="width:62%" class="input-mini" name="pc_name"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">渠道：*</label>
                        <div class="controls" style="margin-left:40px">
                            <select class="form-control" id="channelOpeion" multiple="multiple" name="channel[]"
                                    size='10'>
                                <?php if (is_array($channel_ary) && !empty($channel_ary)) : ?>
                                    <?php foreach ($channel_ary as $server): ?>
                                        <?php echo '<option value="' . $server['id'] . '">' . $server["channel_name"] . '</option>'; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">活动类型：*</label>
                        <div class="controls" style="margin-left:40px">
                            <select class="form-control" id="liOption" multiple="multiple"
                                    name="activity[]" size='10' data-type-name='channel'>
                                <?php $actlist_ary = GlobalConfig_Model::$ACT_LIST; ?>
                                <?php if (is_array($actlist_ary) && !empty($actlist_ary)) : ?>
                                    <?php foreach ($actlist_ary as $key => $var): ?>
                                        <?php echo '<option value=' . $key . '>' . $var . '</option>'; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">游戏区服标识：*</label>
                        <div class="controls" style="margin-left:40px">
                            <input type="text" style="width:62%" class="input-mini" name="server_prefix"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">游戏起始最小区服：*</label>
                        <div class="controls" style="margin-left:40px">
                            <input type="text" style="width:62%" class="input-mini" name="server_min"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">游戏最大区服：*</label>
                        <div class="controls" style="margin-left:40px">
                            <input type="text" style="width:62%" class="input-mini" name="server_max"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">活动配置：*</label>
                        <div class="controls" style="margin-left:40px">
                            <input type="text" style="width:62%" class="input-mini" name="server_max"/>
                        </div>
                    </div>
                    <!--
                        <select id="giftId" name="ttt"
                            class="selectpicker" data-live-search="true" data-live-search-style="begins">';
                        <option value=0>--礼包信息--</option>
                        <option value="1">嘻嘻哈哈</option>
                        <option value="2">是的</option>
                        <option value="3">sad阿法狗</option>
                        <option value="4">反反复复</option>
                        <option value="5">共同话题</option>
                    </select>

                    -->
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
