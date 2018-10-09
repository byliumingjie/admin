<?php 
$insert_html  = Page_Lib::loadJs('global-activity','','dev');
//$insert_html .= Page_Lib::loadJs('global-activity-file-load','','dev');;
$insert_html.= Page_Lib::loadJs('server.list');
$insert_html.= Page_Lib::loadJs('item');
$insert_html .= Page_Lib::loadJs('multselect');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>全局活动管理</h1> <div class="btn-group">
      	</div>
</div>
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">全局活动配置</a>
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
             <li class="active"><a data-toggle="tab" href="#tab1">添加活动配置</a></li>                 
         </ul>
	</div> 
	 
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
	    <?php 
 			echo DevToolbox_Lib::ItemConfigHtml($itemListObject);
 		?>
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal"  method="POST" id="CreateActivityForm" > 
                    <input type="hidden" name='SetactivityType' value="1"/>
                    <table class="table  table-striped" > 
                    <tbody>  
                    <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*平台</label>
									<div class="controls">
									<select   name="platId" >
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
							</td>
							<td>
							</td>
						</tr>
						 
                    <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*活动名称</label>
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
									<label class="control-label">*活动描述</label>
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
									<label class="col-md-3 control-label">*活动类型</label>
									<div class="controls">
									<!-- class="selectpicker" 
										data-live-search="true" data-live-search-style="begins" -->
										<?php if ($activityinfo):?>										
										<select id="activityType" name="activityType">
										<option value=0>--请选择活动类型--</option>
										<?php foreach ($activityinfo as $var):?>		 
											<option value=<?php echo $var['id']?>>
											<?php echo $var['remarks'];?></option>
										<?php endforeach;?>
										</select>
										<?php endif;?>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动起始时间(时间戳秒)</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						             <input type="text" name="starttimeDay" style="width:5%" placeholder="日"/>
						             - <input type="text" name="starttimeHour" style="width:5%" placeholder="时"/>
						             : <input type="text" name="starttimeMint" style="width:5%" placeholder="分"/>
						             : <input type="text" name="starttimeSec" style="width:5%" placeholder="秒"/>
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
						            <input type="text" name="endtimeDay" style="width:5%" placeholder="截至日"/>
						            - <input type="text" name="endtimeHour" style="width:5%" placeholder="截至时"/>
						            : <input type="text" name="endtimeMint" style="width:5%" placeholder="截至分"/>
						            : <input type="text" name="endtimeSec" style="width:5%" placeholder="截至秒"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动关闭时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						              <input type="text" name="stoptimeDay" style="width:5%" placeholder="活动关闭日"/>
						              - <input type="text" name="stoptimeHour" style="width:5%" placeholder="活动关闭时"/>
						              : <input type="text" name="stoptimeMint" style="width:5%" placeholder="活动关闭分"/>
						              : <input type="text" name="stoptimeSec" style="width:5%" placeholder="活动关闭秒"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                         <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">重置类型/时间点</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
                                     <select name="ResetType">
                                     	<option>--请选择--</option>
                                     	<option value=1>时间点</option>
                                     	<option value=2>整点</option>
                                     </select>       
                                     <input type="text" class="form-control"  name ="ResetTime" placeholder="请以整形对应重置类型填写">
                                    </div>
                                    </label>
                                </div>
                            </td> 
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
						  
                        </tbody>
                    </table>
                    <div id="configRules">
                     
                    </div> 
                </form>  
                   <div style="text-align: center;">	
                        <button  type="button" class="btn btn-success" id="Globactivitybtn" style="margin: auto;">保存</button>
                    </div>              
            </div> 
        </div>
    </div>
   </div>
 </div>
 
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

