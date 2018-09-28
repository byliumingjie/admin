<?php
// 邮件管理
//$insert_html  = Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('mail');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>邮件管理</h1>
<div class="btn-group">
</div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">邮件管理</a>
     <a href="#" title="输入一下表单提交之后才可看到指定数据"
    data-placement="bottom" data-trigger="focus"  
    class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<div class="container-fluid">					
<!-- 查询组件 begin-->
<div class="widget-box">
	<div class="widget-title">
		<span class="icon"><i class="icon-search"></i></span>
		<h5>查询条件</h5>
	</div>
	<div class="widget-content">
		<form method="POST" class="form-horizontal" action='mail_manage' onsubmit="return mailVerify(this);">
			<div class="control-group">
				<div class="controls">
					<select style="width:auto" name="ServerId">
					<option value=0>--请选择区服列表--</option>
					<?php 
					$serverInfo = session::get('AllplatformInfo');
					?>
					<?php if (is_array($serverInfo) && !empty($serverInfo)) :?> 
                        <?php foreach ($serverInfo as $server):?>
                        <?php if ($server['type']==0){continue;}?>
                        <?php echo '<option value="'.$server['type'].'">'.
                        $server["type"].'服 '.$server['platformname'].'</option>';?>
                        <?php endforeach;?>
                    <?php endif;?>
					</select>					 
										
					<span class="add-on"><i class="icon-th"></i></span>	
					
					<input type="text" class="datetimepicker form-control" 
					placeholder="创建日期*" name="createTime" style="width:auto">					
					<span class="add-on"><i class="icon-th"></i></span>					
					<input type="text" class="datetimepicker form-control" 
					placeholder="结束时间*" name="endtime" style="width:auto">
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
                <li><a id="releasetab" data-toggle="tab" href="#tab2">发布中</a></li>
                <li><a id="reimbursettab" data-toggle="tab" href="#tab3">邮件补偿</a></li> 
                <li><a id="failuretab" data-toggle="tab" href="#tab4">已失效</a></li>                
            </ul>
	</div>
	
        <div class="widget-content tab-content">
          
          <!-- 2页表格 正文 -->
             <div id="tab2" class="tab-pane">
             <?php if (is_array($mailreleaselist) && !empty($mailreleaselist) ): ?>
		<table class="table table-bordered" id="mailrelease-table">
                    <thead>
                        <tr>
                        	<th>id</th>
                            <th>创建日期</th>
                            <th>区服Id</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>发布规则</th>
                            <th>附件信息</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th>                           
                            <th>操作</th> 
                        </tr>
                    </thead>
                    <tbody>                         
                         
                     <?php foreach ($mailreleaselist as $listdata): ?>
                     <?php 
                     $context = mb_substr( 
                     htmlentities($listdata['MailText'],ENT_QUOTES,"UTF-8"),0,10,'utf-8');
                     $contextTitle = htmlentities($listdata['MailTitle'],ENT_QUOTES,"UTF-8");
                     ?>
                     <tr>
                    	<td style="text-align: center;" data-name='id'><?php echo  $listdata['id'];?></td>
                   		<td style="text-align: center;" data-name='createtime'><?php echo  $listdata['Createtime'];?></td>
                        <td style="text-align: center;" data-name='ServerId'><?php echo  $listdata['ServerId'];?></td>
                        <td style="text-align: center;" data-name='MailTitle'><?php echo  $contextTitle;?></td>
                        <td style="text-align: center;" data-name='context'title='<?php echo $context; ?>'><?php echo $context;?></td>
                        <td style="text-align: center;" data-name='Rules'><?php echo  $listdata['Rules'];?></td>
                        <td style="text-align: center;" data-name='ItemList'><?php echo  $listdata['ItemList'];?></td>
                        <td style="text-align: center;" data-name='Starttime'><?php echo date('Y-m-d H:i:s',$listdata['Starttime']);?></td>
                        <td style="text-align: center;" data-name='Endtime'><?php echo   date('Y-m-d H:i:s',$listdata['Endtime']);?></td>
                        <td style="text-align: center;" data-name='Sender'><?php echo  $listdata['Sender'];?></td>
               			<td>
                        <a class="btn btn-link editMailBtn" title="撤销" 
                        data-toggle="modal" data-backdrop="static" 
                        data-target="#editMailModal"  
                        id="editMailBtn"
                        lick-mail-id ="<?php echo $listdata['id'] ?>"
                        lick-serverId="<?php echo $listdata['ServerId'];?>"
                        >撤销</a>  
                        </td>
                     </tr>                     
                    <?php endforeach;?>
                </tbody>
                </table>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($mailreleaselist);?>
				</div>	
				<?php endif;?>						
            </div>          
          <!-- 4页表格 正文 -->
            <div id="tab4" class="tab-pane">
            <?php if (is_array($mailfailurelist) && !empty($mailfailurelist) ): ?> 
			<table class="table table-bordered table-striped">
                    <thead>
                         <tr>
                         	<th>id</th>
                            <th>创建日期</th>
                            <th>区服Id</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>发布规则</th>
                            <th>附件信息</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th> 
                        </tr>
                    </thead>
                    <tbody>                          
                    <?php foreach ($mailfailurelist as $listdata): ?>
                     <?php 
                     $context = mb_substr( 
                     htmlentities($listdata['MailText'],ENT_QUOTES,"UTF-8"),0,10,'utf-8');
                     $contextTitle = htmlentities($listdata['MailTitle'],ENT_QUOTES,"UTF-8");
                     ?>
                     <tr>
                    	<td style="text-align: center;" data-name='id'><?php echo  $listdata['id'];?></td>
                   		<td style="text-align: center;" data-name='createtime'><?php echo  $listdata['Createtime'];?></td>
                        <td style="text-align: center;" data-name='ServerId'><?php echo  $listdata['ServerId'];?></td>
                        <td style="text-align: center;" data-name='MailTitle'><?php echo  $contextTitle;?></td>
                        <td style="text-align: center;" data-name='context'title='<?php echo $context; ?>'><?php echo $context;?></td>
                        <td style="text-align: center;" data-name='Rules'><?php echo  $listdata['Rules'];?></td>
                        <td style="text-align: center;" data-name='ItemList'><?php echo  $listdata['ItemList'];?></td>
                        <td style="text-align: center;" data-name='Starttime'><?php echo date('Y-m-d H:i:s',$listdata['Starttime']);?></td>
                        <td style="text-align: center;" data-name='Endtime'><?php echo   date('Y-m-d H:i:s',$listdata['Endtime']);?></td>
                        <td style="text-align: center;" data-name='sender'><?php echo  $listdata['Sender'];?></td>
               			 
                     </tr>       
                <?php endforeach; ?>            
        		</tbody>
                </table>
                <?php endif; ?>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($mailfailurehtml);?>
				</div>								
            </div>
             <!-- 3页表格 正文 -->
            <div id="tab3" class="tab-pane">
            
            <?php if (is_array($reimbursetlist) && !empty($reimbursetlist) ): ?> 
			<table class="table table-bordered table-striped">
                    <thead>
                         <tr>
                         	<th>id</th>
                            <th>创建日期</th>
                            <th>区服Id</th>
                            <th>公告标题</th>
                            <th>公告内容</th>
                            <th>发布规则</th>
                            <th>附件信息</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>补偿类型</th> 
                            <th>发布人</th> 
                        </tr>
                    </thead>
                    <tbody>                          
                    <?php foreach ($reimbursetlist as $listdata): ?>
                     <?php 
                     $context = mb_substr( 
                     htmlentities($listdata['MailText'],ENT_QUOTES,"UTF-8"),0,10,'utf-8');
                     $contextTitle = htmlentities($listdata['MailTitle'],ENT_QUOTES,"UTF-8");
                     ?>
                     <tr>
                    	<td style="text-align: center;" data-name='id'><?php echo  $listdata['id'];?></td>
                   		<td style="text-align: center;" data-name='createtime'><?php echo  $listdata['Createtime'];?></td>
                        <td style="text-align: center;" data-name='ServerId'><?php echo  $listdata['ServerId'];?></td>
                        <td style="text-align: center;" data-name='MailTitle'><?php echo  $contextTitle;?></td>
                        <td style="text-align: center;" data-name='context'title='<?php echo $context; ?>'><?php echo $context;?></td>
                        <td style="text-align: center;" data-name='Rules'><?php echo  $listdata['Rules'];?></td>
                        <td style="text-align: center;" data-name='ItemList'><?php echo  $listdata['ItemList'];?></td>
                        <td style="text-align: center;" data-name='Starttime'><?php echo date('Y-m-d H:i:s',$listdata['Starttime']);?></td>
                        <td style="text-align: center;" data-name='Endtime'><?php echo   date('Y-m-d H:i:s',$listdata['Endtime']);?></td>
                        <td style="text-align: center;" data-name='ReimburseType'>
                        <?php $ReimburseType = $listdata['ReimburseType'];
                        	switch ($ReimburseType){
                        		
                        		case 1: echo '头像审核拒绝补偿邮件';
                        		break;
                        		case 2: echo '头像审核通过补偿邮件';
                        		break;
                        		default:echo '未知';
                        		break;
                        	}
                        ?></td>
                        <td style="text-align: center;" data-name='sender'><?php echo  $listdata['Sender'];?></td>               			 
                     </tr>       
                <?php endforeach; ?>            
        		</tbody>
                </table>
                <?php endif; ?>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($reimbursethtml);?>
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
<?php echo Page_Lib::footer('');?>
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
                           <textarea class="form-control " name="context" rows="4" maxlength="1200" placeholder="最多输入1200汉字,包括标点符号和空格"></textarea>
                        </div>
                    </div> 
                    
                </form>
            </div>
            <div class="modal-footer">               
                <button type="button" class="btn btn-primary" id="editLoginNoticeBtn">确认编辑</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
 
 
 
 