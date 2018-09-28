<?php
// 登录公告管理
$insert_html  = Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('notice');
$insert_html .= Page_Lib::loadJs('login_notice');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>Dashboard</h1>
<div class="btn-group">
<a class="btn btn-large btn-default tip-bottom" title="登录公告" 
data-toggle="modal" data-backdrop="static" data-target="#addloginNoticeModal" id="addLoginNotice">
<i class="icon-plus-sign"></i> 添加登录公告</a>
</div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">管理登录公告</a>
</div>
<div class="container-fluid">					
<!-- 查询组件 begin-->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon"><i class="icon-search"></i></span>
		<h5>查询条件</h5>
	</div>
	<div class="widget-content">
		<form method="POST" class="form-horizontal" action='passLoginNotice'>
			<div class="control-group">
				<div class="controls">
					<input type="text" class="datetimepicker form-control" 
					placeholder="创建日期" name="createTime" style="width:auto">
					<span class="add-on"><i class="icon-th"></i></span>
					<input type="text" class="datetimepicker form-control" 
					placeholder="结束时间" name="endtime" style="width:auto">
					<span class="add-on"><i class="icon-th"></i></span>
					<input name="account" class="form-control" style="width:auto" 
					placeholder="请输入发布人" >
					<button class="btn btn-primary" type="submit" id="btn_date1" name="btn_sub">
					<i class="icon-search icon-white"></i> 查询</button>
				</div>
			</div>
		</form>
	</div>
</div>
 <!-- 查询组件 end--> 
 <div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title">
	<span class="icon">
            <i class="icon-th"></i>
        </span>
            <ul class="nav nav-tabs tabaparent">
                <!-- <li class="active">
                	<a data-toggle="tab" href="#tab1">审核中</a></li> -->
                <?php //if (!empty($data1)):?>                	
                <li><a data-toggle="tab"  href="#tab2" class='tab2'>发布中</a></li>
                <?php //endif;?>
                <?php //if (!empty($data2)):?>
                <!-- <li><a data-toggle="tab" href="#tab3">未通过</a></li> -->
                <li><a data-toggle="tab" href="#tab4" class='tab4'>已失效</a></li>
                <?php //endif;?>
            </ul>
	</div>
	
        <div class="widget-content tab-content">
          
          <!-- 2页表格 正文 -->
             <div id="tab2" class="tab-pane">
             <?php if (is_array($noticereleaselist) && !empty($noticereleaselist) ): ?>
		<table class="table table-bordered table-striped" id="loginNoticTable">
                    <thead>
                        <tr>
                            <th>创建日期</th>
                            <th>平台ID</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th>
                            <th>操作</th> 
                        </tr>
                    </thead>
                    <tbody>                         
                                 
                     <?php foreach ($noticereleaselist as $listdata): ?>
                     <?php 
                     $context = mb_substr( 
                     htmlentities($listdata['context'],ENT_QUOTES,"UTF-8"),0,10,'utf-8');
                     $contextTitle = htmlentities($listdata['context'],ENT_QUOTES,"UTF-8");
                     ?>
                     <tr>
                    	<td style="display: none" data-name='id'><?php echo  $listdata['id'];?></td>
                   		<td style="text-align: center;" data-name='createtime'><?php echo  $listdata['createtime'];?></td>
                        <td style="text-align: center;" data-name='platformId'><?php echo  $listdata['platformId'];?></td>
                        <td style="text-align: center;" data-name='title'><?php echo  $listdata['title'];?></td>
			<td style="text-align: center;" title='<?php echo $contextTitle; ?>'><?php echo $context;?></td>
                        <td style="text-align: center;display: none;" data-name='context'><?php echo htmlspecialchars($listdata['context']);?></td>
                        <td style="text-align: center;" data-name='starttime'><?php echo  $listdata['starttime'];?></td>
                        <td style="text-align: center;" data-name='endtime'><?php echo  $listdata['endtime'];?></td>
                        <td style="text-align: center;" data-name='sender'><?php echo  $listdata['sender'];?></td>
               			<td>
                        <a class="btn btn-link editloginNoticeBtn" title="编辑" 
                        data-toggle="modal" data-backdrop="static" data-target="#editnoticModal" 
                        lick-id ="<?php echo $listdata['id'] ?>">编辑</a>  
                        </td>
                     </tr>                     
                    <?php endforeach;?>
                </tbody>
                </table>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($noticereleasehtml);?>
				</div>	
				<?php endif;?>						
            </div>          
          <!-- 4页表格 正文 -->
            <div id="tab4" class="tab-pane">
            <?php if (is_array($noticefailurelist) && !empty($noticefailurelist) ): ?> 
		<table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>创建日期</th>
                            <th>平台ID</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th>                        					
                        </tr>
                    </thead>
                    <tbody>                          
                    <?php foreach ($noticefailurelist as $listdata): ?>
                    <tr>
                      	<td style="display: none" data-name='id'><?php echo $listdata['id'];?></td>
                   	<td style="text-align: center;"><?php echo  $listdata['createtime'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['platformId'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['title'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['context'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['startime'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['endtime'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['sender'];?></td>
                    </tr> 
                <?php endforeach; ?>            
        </tbody>
                </table>
                <?php endif; ?>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($noticefailurehtml);?>
				</div>								
            </div>
        </div> 
    </div>    
</div>
<!-- 分页组件 begin -->
<!--<div class="row center" style="text-align: center;">	
<?php// echo htmlspecialchars_decode($pagehtml);?> 
</div>-->
<!-- 分页组件 end -->
<!-- 表格 end -->
<?php echo Page_Lib::footer();?>
<!--serverlist Modal -->
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
<!--context Modal -->
<div class="modal fade" id="lookcontextModal" tabindex="-1" role="dialog" aria-labelledby="lookcontextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="lookserverModalLabel">公告内容</h4>
            </div>
            <div class="modal-body">
             <table class="table table-striped table-bordered" id="noticecontext" > 
             </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<!--edit Modal -->
<div class="modal fade" id="editLoginNoticeModal" tabindex="-1" role="dialog" aria-labelledby="editLoginNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editLoginNoticeModalLabel">登录公告审核</h4>
            </div>
            <div class="modal-body">
            <!--  -->
                <form class="form-horizontal" action="/loginnotice/editLoginNotice" method="POST" id="editLoginNoticeForm" 
                onsubmit="return false;">
                   <input type="hidden" name="id"/>
                   <input type="hidden" name="platformId"/>
                    <div class="control-group">
                        <label class="col-md-3 control-label">发布人签名</label>
                        <div class="controls"><input type="text" class="form-control" name="sender" readonly="true"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">生效时间</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="starttime" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">失效时间</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="endtime" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告标题</label>
                        <div class="controls"><input type="text" class="form-control"  name="title"  /></div>
                    </div> 
                     
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告内容</label>
                        <div class="controls">
                           <textarea class="form-control " name="context" rows="4" maxlength="10000" placeholder="最多输入1200汉字,包括标点符号和空格"></textarea>
                        </div>
                    </div> 
                    
                </form>
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-primary" id="editLoginNoticebtn">确认编辑</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

 
<!--addNotice Modal Bengin-->
<div class="modal fade" id="addloginNoticeModal" tabindex="-1" role="dialog" aria-labelledby="addloginNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addloginNoticeModalLabel">登录公告</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/loginnotice/addLoginNotice" method="POST" id="addLoginNoticerForm" onsubmit="return false;">        
                    <div class="control-group">
                        <label class="col-md-3 control-label">生效时间*</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="starttime"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">失效时间*</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="endtime"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告标题*</label>
                        <div class="controls">
                        <input type="text" class="form-control"  maxlength="10" placeholder="最多输入十个汉字" name="title"/>
                        
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">标题颜色*</label>
                        <div class="controls">
                        <select style="width: 50px;" name="titleColor" title='titleColor'>
                        	<option value='fdff45'>黄色</option>
                        	<option value='65e71e'>绿色</option>
                        </select>
                        </div>
                    </div>
                     
                    <div class="control-group">
                        <label class="col-md-3 control-label">公告内容*</label>
                        <div class="controls">
                            <textarea class="form-control " name="context" rows="7" maxlength="10000" placeholder="最多输入1200汉字,包括标点符号"></textarea>
                        </div>
                    </div>
                    <div class="control-group" >			    
					    <div class="controls" style="margin-left:40px">
					    <div class="control-group">							 
							      <select   class="form-control"  id="liOption"  multiple="multiple"  
								  name="platformid[]" size='10' data-type-name='platId'>
									<?php  
									$platformOut = System_Service::getplatformInfo(); 
									//$platformOut = session::get('AllplatformInfo');?>									
									<?php if (is_array($platformOut) && !empty($platformOut)) :?>
									<?php foreach ($platformOut as $var):?> 
									<?php echo '<option value='.$var['id'].'>'.$var["name"].'</option>';?>
									<?php endforeach;?>
									<?php endif;?>
								</select>					    
					    </div> 
					</div> 
			  		</div>
                    <!-- <input type="hidden" name="id"/> -->
                </form>
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-primary" id="addLoginNoticeBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>