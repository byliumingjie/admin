<?php
// 
$insert_html  = Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('notice');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">                           
	<h1>Dashboard</h1>
	<div class="btn-group">
	<a class="btn btn-large btn-default tip-bottom" title="走马灯" 
	data-toggle="modal" data-backdrop="static" data-target="#addNoticeModal" 
	id="addNotice"><i class="icon-plus-sign"></i> 添加走马灯</a>
	</div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">添加走马灯</a>
</div>
<div class="container-fluid">					
<!-- 查询组件 begin-->
<div class="widget-box">
<div class="widget-title">
    <span class="icon">
            <i class="icon-search"></i>
    </span>
    <h5>查询条件</h5>
</div>
    <div class="widget-content">			
        <form  method="POST" class="form-horizontal"  id="publicNoticeForm">         
                <div class="control-group">
						<div class="controls">                           
                            <input type="text" class="datetimepicker form-control" 
                            placeholder="创建日期"  name="createTime" style="width:auto;"/>
                            <span class="add-on"><i class="icon-th "></i></span>
                            <input type="text" class="datetimepicker form-control" 
                            placeholder="结束时间"  name="endtime" style="width:auto;"/>
                            <span class="add-on"><i class="icon-th "></i></span>
                            <input  name="account" 
                            placeholder="请输入发布人" class="form-control" style="width:auto;">
                        	<button class="btn btn-primary" 
                        	type="submit" id="btn_date" name="sub_btn">
                        	<i class="icon-search icon-white"></i> 查询</button>
                        </div>
                </div> 
        </form>       
    </div>
</div>
 <!-- 查询组件 end-->
 <?php if (is_array($data) && !empty($data)): ?>
 
<table class="table table-bordered table-striped" id="noticeTable">
<thead>
    <tr>
        <th>创建日期</th>
        <th>区服</th>											
        <th>内容</th>
        <th>发布人</th>	
        <th>状态</th>
        <th>间隔时间</th>
        <th>播放次数</th> 											
    </tr>
    </thead>
    <tbody>
      
        <?php foreach ($data as $listdata): ?>
            <tr id="<?php echo $listdata['id']; ?>">            
                <td data-name="createtime"><?php echo $listdata['createtime']; ?></td>
                <td data-name="server_id" style=" text-align: center;">
                <?php echo $listdata['server_id']; ?></td>
                <td data-name="context" style=" text-align: center;">
                <?php echo $listdata['context']; ?></td>
                <td data-name="sender" style=" text-align: center;">
                <?php echo $listdata['sender']; ?></td>
                <td data-name="status" style=" text-align: center;">
                <?php 
                if ($listdata['status'] == 4 && strtotime($listdata['endtime'])>time(0)){
                	echo "发布中";	
                }elseif(strtotime($listdata['endtime'])<time(0)){
                	echo "失效";
                }
                ?></td>
                <td data-name="gaptime" style="text-align: center;">
                <?php echo $listdata['gaptime']; ?></td>
                <td data-name="playnum" style="text-align: center;">
                <?php echo $listdata['playnum']; ?></td>               
           	</tr>
        <?php endforeach; ?>
    
    </tbody>
</table>

<div class="row center" style="text-align: center;">	
	<?php echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif; ?>
</div>
<!-- 分页组件 begin -->
 
<!-- 分页组件 end -->
<!-- 表格 end -->
 <?php echo Page_Lib::footer();?>

<!--lookserverlist Modal -->
<div class="modal fade" id="lookserverModal" tabindex="-1" role="dialog" aria-labelledby="lookserverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="lookserverModalLabel">服务器列表</h4>
            </div>
            <div class="modal-body">
             <table class="table table-striped table-bordered" id="lookserverlist" > 
             </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<!--addNotice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" role="dialog" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addNoticeModalLabel">走马灯公告</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" id="addNoticerForm" onsubmit="return false;">                      
                    <!-- <div class="control-group">
                        <label class="col-md-3 control-label" for="">播放等级*</label>
                        <div class="controls">
                            <select class="form-control" name="level" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="0">普通</option>
                                <option value="-1">紧急</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放开始时间*</label>
                        <div class="controls">
                        <input type="text" class="datetimepicker form-control"  name="starttime"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放结束时间*</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="endtime"/></div>
                    </div>
                    <!-- <div class="control-group">
                        <label class="col-md-3 control-label">播放次数*</label>
                        <div class="controls"><input type="text" class="form-control"  name="playnum"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">时间间隔*</label>
                        <div class="controls"><input type="text" class="form-control"  name="looptime" placeholder="单位是秒"/></div>
                    </div> -->
                    <div class="control-group">
                        <label class="col-md-3 control-label">走马灯内容*</label>
                        <div class="controls">
                           <textarea class="form-control " name="context" rows="7" maxlength="20" placeholder="最多输入20个汉字,包括标点符号和空格"></textarea>
                        </div>
                    </div>
                    <div class="control-group" >		
                    	<label class="col-md-3 control-label">区服*</label>	    
					    <div class="controls" style="margin-left:40px">
					    <div class="control-group">							 
							      <select   class="form-control"  id="liOption"  multiple="multiple"  name="platformid[]" size='10' >
									<?php  $platformOut = session::get('AllplatformInfo');?>									
									<?php if (is_array($platformOut) && !empty($platformOut)) :?>
									<?php foreach ($platformOut as $var):?>
									<?php if((int)$var['type']===0){continue;}?>
									<?php echo '<option value='.$var['type'].'>'.$var["platformname"].'</option>';?>
									<?php endforeach;?>
									<?php endif;?>
								</select>					    
					    </div> 
					</div> 
			  		</div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-primary" id="addNoticeBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<!--serverlist Modal -->
<div class="modal fade" id="serverListModal" tabindex="-1" role="dialog" aria-labelledby="serverListModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverListModalLabel">服务器列表</h4>
            </div>
            <div class="modal-body">
                <button id="checkAll" class="btn btn-info">全选</button>
                <button id="checkNone" class="btn btn-info">取消全选</button>
                    <table class="table table-striped table-bordered" id="serverlist"> 
                        <input type="hidden" name="tr_id"/>                       
                            <?php if (is_array($servers) && !empty($servers)): ?>                                     
                                <?php for ($i = 0; $i < count($servers);$i+=4): ?>
                                    <tr><?php if(!empty($servers[$i])):?>
                                    <td>
                                        <div class="checkbox">
                                        <label class="checkbox-inline">
                                         <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$servers[$i]['sid'].'">'.$servers[$i]['sid']."区 ".$servers[$i]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                        <?php endif;?>
                                    <?php if(!empty($servers[$i+1])):?>
                                    <td>
                                        <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$servers[$i+1]['sid'].'">'.$servers[$i+1]['sid']."区 ".$servers[$i+1]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                        <?php endif;?>
                                    <?php if(!empty($servers[$i+2])):?>
                                    <td>
                                       <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$servers[$i+2]['sid'].'">'.$servers[$i+2]['sid']."区 ".$servers[$i+2]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                     <?php endif;?>
                                    <?php if(!empty($servers[$i+3])):?>
                                    <td>
                                     <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$servers[$i+3]['sid'].'">'.$servers[$i+3]['sid']."区 ".$servers[$i+3]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                      <?php endif;?>
                                    </tr>
                            <?php endfor; ?>
                            <?php endif; ?>                           
                        </table>
                    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="getServerbtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<!--editNotice Modal -->
<div class="modal fade" id="alterNoticeModal" tabindex="-1" role="dialog" aria-labelledby="alterNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="alterNoticeModalLabel">修改走马灯公告</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/notice/alterNotice" method="POST" id="alterNoticeForm" onsubmit="return false;">
                    <input type="hidden" name="tr_id"/>
                    <input type="hidden" name="sid"/>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">播放等级</label>
                        <div class="controls">
                            <select class="form-control" name="level" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="0">普通</option>
                                <option value="-1">紧急</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放开始时间</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="starttime" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放结束时间</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="endtime" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放次数</label>
                        <div class="controls"><input type="text" class="form-control"  name="playnum" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">时间间隔</label>
                        <div class="controls"><input type="text" class="form-control"  name="looptime" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告内容</label>
                        <div class="controls">
                            <textarea class="form-control " name="context" rows="7" maxlength="20" placeholder="最多输入20个汉字,包括标点符号和空格"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="alterNoticeBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<!--sumbitNotice Modal -->
<div class="modal fade" id="submitNoticeModal" tabindex="-1" role="dialog" aria-labelledby="submitNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="submitNoticeModalLabel">走马灯公告</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/notice/sumbitNotice" method="POST" id="submitNoticeForm" onsubmit="return false;">
                    <input type="hidden" name="tr_id"/>
                    <input type="hidden" name="sid"/>                  
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告内容</label>
                        <div class="controls">
                            <textarea class="form-control " name="context" rows="7" maxlength="20" placeholder="最多输入20个汉字,包括标点符号和空格" readonly="true"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitbtn">确认提交</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
