<?php 
$insert_html  = Page_Lib::loadJs('mall','','dev');
$insert_html.= Page_Lib::loadJs('server.list');
$insert_html .= Page_Lib::loadJs('multselect');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>商城推荐配置</h1>  
</div>
 

<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">商城推荐配置</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    	<?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     	<i class="icon-question"></i></a>
 </div>
<div class="container-fluid">					
  <div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title">
	<span class="icon">
            <i class="icon-th"></i>
        </span>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab1">添加商城推荐配置</a></li>                 
            </ul>
	</div> 
	 
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
 <div id="itemresetid2">
 
 </div>	
<div class="row-fluid">
<?php  $platformOut = Platfrom_Service::get_plat_server();?>
<?php 
$itemInfo =null;
$i= 0;
?> 
</div>
 
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal"  method="POST" id="CreateMallForm" action="createMall" > 
                    <input type="hidden" name='SetMall' value="1"/>
                    <table class="table  table-striped" > 
                    <tbody>  
						<tr>
							<td>
								<div class="control-group" >
									<label class="control-label">区服*</label>
									<div class="controls">
										<select   class="form-control"  id="liOption"  
										multiple="multiple"  name="serverIdInfo[]" size='10' >
										<?php $platConfg = System_Service::getplatformInfo(true);?>
								<?php  $platformOut = Platfrom_Service::get_plat_server();?>									
								<?php if (is_array($platformOut) && !empty($platformOut)) :?>
									<?php foreach ($platformOut as $var):?>
										<?php if((int)$var['type']===0){continue;}?>
											<?php if (isset($platConfg[$var['platformId']]['name'])):?>
											<?php echo '<option value='.$var['type'].'>'.$platConfg[$var['platformId']]['name'].'
											'.' '.$var["type"].'区 '.'</option>';?>
											<?php endif;?>
									<?php endforeach;?>
								<?php endif;?>
										</select>	
								
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
						
                    <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*商城推荐名称</label>
									<div class="controls">
										<input type="text" class="form-control"  
										placeholder="请以小于30字以内来进行描述" 
										maxlength="30" name="title" >
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
                    	<tr>
							<td>
								<div class="control-group">
									<label class="control-label">*商城推荐描述</label>
									<div class="controls">
									<textarea rows="5" cols="6"  
									placeholder="请以小于60字以内来进行描述" 
									maxlength="60" name="content"></textarea>
										<!-- <input type="text" class="form-control" 
								placeholder="请以小于60字以内来进行描述" 
								> -->
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
                          <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*推荐类型</label>
									<div class="controls">
										 <select name='malltype'>
										 	<option>--请选择--</option>
										 	<option value=1>商城推荐</option>
										 	<option value=2>首冲推荐(无需填写开始结束时间)</option>
										 </select>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
						<tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动起始时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						             <input type="text" class=" datetimepicker" 
						             name="starttime" style="width:25%" placeholder="起始时间"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动截止时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						            <input type="text" class=" datetimepicker " 
						             name="endtime" style="width:25%" placeholder="截至时间"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
							<td>
								<div class="control-group">
									<div class="control-group">
										<label class="col-md-3 control-label">配置条件:</label>
										<div class="controls">
											<span style="color:gray">可使用的配置条件为根据活动类型去填写的，需要填写对应的数值以及生产描述，
											每条都需要填写对应的完成奖励。</span>
										</div>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
						  <tr id="activityBytr2">
								<td> 
									<div class="control-group">
									<label class="control-label">*商城推荐显示配置信息,或 首冲</label>
									<div class="controls">
									<textarea rows="5" cols="1" style="width: 25%" 
									placeholder="奖励配置,格式:道具Id,数量&amp;道具Id,数量例:123,500&456,100" 
									maxlength="10000" name="reward"></textarea>										 
									</div>
								</div>								
								</td>								 
								
							</tr>
							<tr id="activityBytr2">
								<td> 
									<div class="control-group">
									<label class="control-label">*商城推荐消耗配置信息</label>
									<div class="controls">
									<textarea rows="5" cols="1" style="width: 25%" 
									placeholder="奖励配置,格式:道具Id,消耗&amp;道具Id,数量,价位  例:123,500&456,100" 
									maxlength="10000" name="cost"></textarea>										 
									</div>
								</div>								
								</td>								 
								
							</tr>
                        </tbody>
                    </table>                     
                </form>  
                   <div style="text-align: center;">	
                        <button  type="submit" class="btn btn-success" id="mallbtn" style="margin: auto;">保存</button>
                    </div>              
            </div> 
        </div>
    </div>
   </div>
 </div>
 
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

