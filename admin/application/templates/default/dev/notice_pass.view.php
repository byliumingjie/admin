<?php
/**
 * 走马灯
 * **/
$insert_html  = Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('marquee');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>走马灯</h1>
<div class="btn-group">
	<a class="btn btn-large btn-default tip-bottom" title="走马灯" 
	data-toggle="modal" data-backdrop="static" data-target="#addNoticeModal" 
	id="addNotice"><i class="icon-plus-sign"></i> 发送走马灯</a>
	</div>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">管理走马灯</a>
</div>
<?php 
	$platformOut = session::get('AllplatformInfo');
	if ($platformOut)
	{
		foreach ($platformOut as $var)
		{											
			if ($var['type']==0 ){continue;}
			$platformList[(int)$var['type']] = $var; 
		}
		ksort($platformList); 
	}
?>	
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
        <form  method="POST" class="form-horizontal" action="showNotice">         
                <div class="control-group">
				<div class="controls">                           
                      <input type="text" class="datetimepicker form-control" placeholder="创建日期"  name="createTime" style="width:auto;"/>
                      <span class="add-on"><i class="icon-th "></i></span>
                      <input type="text" class="datetimepicker form-control" placeholder="结束时间"  name="endtime" style="width:auto;"/>
                      <input  name="account" placeholder="请输入发布人" class="form-control" style="width:auto;">
                      <button class="btn btn-primary" type="submit" id="btn_date"
                      name ="btn_sub"><i class="icon-search icon-white"></i> 查询</button>
                </div>
                </div> 
            </form>       
    </div>
</div>
 <div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title">
		<span class="icon">
            <i class="icon-th"></i>
        </span>
            <ul class="nav nav-tabs tabaparent" id="tabaparent"> 
                <li><a id="releasetab" data-toggle="tab" href="#tab2">发布中</a></li> 
                <li><a id="failuretab" data-toggle="tab" href="#tab4">已失效</a></li>
            </ul>
	</div>
        <div class="widget-content tab-content"> 
          <!-- 2页表格 正文 -->
           
            <div id="tab2" class="tab-pane">
		 	 <?php if (is_array($releaseList) && !empty($releaseList) ): ?>
		<table class="table table-bordered table-striped" id="Manage-release-Table">
                    <thead>
                        <tr>
                        	<th>id</th>
                            <th>创建日期</th>
                            <th>区服</th>                            
                            <th>走马灯内容</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th>
                            <th>操作</th>		
                        </tr>
                    </thead>
                    <tbody>                                       
                     <?php foreach ($releaseList as $listdata): ?>
                    <tr>
                    	<td style="text-align: center;" data-name='id'><?php echo  $listdata['id'];?></td>
                   		<td style="text-align: center;"><?php echo  $listdata['createtime'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['Serverid'];?></td> 
                        <td style="text-align: center;"><?php echo  $listdata['context'];?></td>
                        <td style="text-align: center;"><?php echo  date('Y-m-d H:i:s',$listdata['starttime']);?></td>
                        <td style="text-align: center;"><?php echo  date('Y-m-d H:i:s',$listdata['endtime']);?></td>
                        <td style="text-align: center;"><?php echo  $listdata['sender'];?></td>
                        <td>
                        <a class="btn btn-link editMailBtn" title="撤销" 
                        data-toggle="modal" data-backdrop="static" 
                        data-target="#editManageModal"  
                        id="editManageBtn"
                        lick-mail-id ="<?php echo $listdata['id'] ?>"
                        lick-serverId="<?php echo $listdata['Serverid'];?>"
                        >撤销</a>  
                        </td> 
                    </tr>                     
                    <?php endforeach;?>
                </tbody>
                </table>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($releasehtml);?>
				</div>	
				<?php endif;?>						
            </div>
    
           
          <!-- 4页表格 正文 -->
            <div id="tab4" class="tab-pane">
               	 
		 		    <?php if (is_array($failurelist) && !empty($failurelist) ): ?> 
		<table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>创建日期</th>
                            <th>区服</th>                          
                            <th>走马灯内容</th>
                            <th>生效时间</th>
                            <th>失效时间</th>
                            <th>发布人</th>	
                                                  					
                        </tr>
                    </thead>
                    <tbody>                          
                    <?php foreach ($failurelist as $listdata): ?>  
                                     
                      	<tr>
	                <td style="text-align: center;" data-name='id'><?php echo  $listdata['id'];?></td>
                   	<td style="text-align: center;"><?php echo  $listdata['createtime'];?></td>
                        <td style="text-align: center;"><?php echo  $listdata['Serverid'];?></td> 
                        <td style="text-align: center;"><?php echo  $listdata['context'];?></td>
                        <td style="text-align: center;"><?php echo  date('Y-m-d H:i:s',$listdata['starttime']);?></td>
                        <td style="text-align: center;"><?php echo  date('Y-m-d H:i:s',$listdata['endtime']);?></td>
                        <td style="text-align: center;"><?php echo  $listdata['sender'];?></td> 
                    	</tr>       
                <?php endforeach; ?>            
        </tbody>
                </table>
                <?php endif; ?>
                <!-- 分页组件 begin -->
				<div class="row center" style="text-align: center;">	
				<?php  echo htmlspecialchars_decode($failurehtml);?>
				</div>							
            </div>
        </div> 
    </div>    
</div>
 
<!-- 表格 end -->
<?php echo Page_Lib::footer();?>
<!--editNotice Modal -->
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
                   
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放开始时间*</label>
                        <div class="controls">
                        <input type="text" class="datetimepicker form-control"  name="starttime"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">播放结束时间*</label>
                        <div class="controls"><input type="text" class="datetimepicker form-control"  name="endtime"/></div>
                    </div>
                      <div class="control-group">
                        <label class="col-md-3 control-label">间隔时间(s)*</label>
                        <div class="controls"><input type="text" class="form-control"  name="Intervaltime"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">走马灯内容*</label>
                        <div class="controls">
                           <textarea class="form-control " name="context" rows="7" maxlength="40" placeholder="最多输入40个汉字,包括标点符号和空格"></textarea>
                        </div>
                    </div>
                    <div class="control-group" >		
                    	<label class="col-md-3 control-label">区服*</label>	    
					    <div class="controls" style="margin-left:40px">
					    <div class="control-group">							 
						      <select   class="form-control"  id="liOption"  multiple="multiple"  
						      name="platformid[]" size='10' >
								<?php if (is_array($platformList) && !empty($platformList)) :?> 
								<?php foreach ($platformList as $var):?>
								<?php if((int)$var['type']===0){continue;}?>
								<?php echo '<option value='.$var['type'].'>'.$var["type"].'区'.'</option>';?>
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
                <button type="button" class="btn btn-primary" id="addNoticeBtn">确认发送</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
 
