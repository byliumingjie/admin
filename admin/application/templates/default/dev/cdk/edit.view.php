<?php 
$insert_html = Page_Lib::loadJs('ajaxupload');
$insert_html.= Page_Lib::loadJs('cdk');
$insert_html.= Page_Lib::loadCss('bootstrap-select.min');
$insert_html.= Page_Lib::loadJs('loadmailfile');
$insert_html.= Page_Lib::loadJs('bootstrap-select');
$insert_html.= Page_Lib::loadJs('multselect');
echo Page_Lib::head($insert_html,null,true);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>礼包码生成/记录</h1> 
        <div class="btn-group"> 
        <a class="btn btn-large tip-bottom" title="礼包生成" 
          data-toggle="modal"data-backdrop="static" 
          data-target="#addCdkModal" id="addName">
          <i class="icon-plus"></i>礼包码生成</a>    
        </div>
</div>
<!-- top begin-->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">礼包码生成</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    <?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
    <i class="icon-question"></i></a>
 </div>
   
 <input type="hidden" id="selector">
<!-- top end -->
<!-- edit begin -->
<div class="modal fade" id="addCdkModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editMenuModalLabel">修改活动</h4>
            </div>
            <div class="modal-body" >
                <form class="form-horizontal" action="setcode" method="POST" id="setcdkForm">
                	<input type="hidden" class="form-control" name="addType"  value='0'/> 
                                  
                    <div class="control-group">
                        <label class="col-md-4 control-label">礼包ID:*</label>
                        <div class="controls">
                         <?php echo DevToolbox_Lib::Get_giftInfo();?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">平台:*</label>
                        <div class="controls">
                         <select   name="platformid" id="platformid">
                            <?php  
							$platformOut = System_Service::getplatformInfo(); 
							?>									
							<?php if (is_array($platformOut) && !empty($platformOut)) :?>
							<?php foreach ($platformOut as $var):?> 
							<?php echo '<option value='.$var['id'].'>'.$var["name"].'</option>';?>
							<?php endforeach;?>
							<?php endif;?>		
                         </select>
                        </div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">账号领取规则:*</label>
                        <div class="controls">
                        <select name="type" class="form-control" 
                        style="width:62%" onclick="CdkAccountVerify()">
                        <option value="2">单账号兑换</option>
                        <option value="1">多账号兑换</option>                                                 
                        </select>
                        </div>
                    </div>                   
                     <div class="control-group">
                        <label class="col-md-4 control-label">服务器限制规则:*</label>
                        <div class="controls">
                        <select name="rule" class="form-control" 
                        style="width:62%" onclick="setCdkRuleVerify()">
                        <option value=1>不限制</option>
                        <option value=2>单服务器兑换（指定服务器）</option>
                        <option value=3>任意单服务器</option>
                        </select>
                        </div>
                    </div>  
                     <div class="control-group" id="ServerInfo" style="display:none">
                        <label class="col-md-4 control-label">指定区服信息:*</label>
                        <div class="controls">
                     	<select  name="ServerId" id="ServerId">
                     					 		  		 	 		   
					    </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">开始时间:*</label>
                        <div class="controls"><input  type="text" style="width:62%"
                        class="datetimepicker form-control" name="startTime" /></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">结束时间:*</label>
                        <div class="controls"><input  type="text" style="width:62%"
                        class="datetimepicker form-control" name="endtime" /></div>
                    </div>
                    <!--
                     <div class="control-group" style="display:none">
                        <label class="col-md-4 control-label">批次:*</label>
                        <div class="controls"><input  type="text" style="width:62%"
                        class="form-control" name="prefix" /></div>
                    </div>
                    -->
                    <div class="control-group">
                        <label class="col-md-4 control-label" >生成数量:*</label>
                        <div class="controls">
                        <input  type="text"  style="width:62%" placeholder="生成数量上限为50000"
                         name="number" id="cdknumber"/>
                        </div>
                    </div>                 
                    <div class="control-group">
                        <label class="col-md-4 control-label">礼包码长度:*</label>
                        <div class="controls"><input type="text"  style="width:62%"
                        class="form-control" name="cdklenth" placeholder="长度上限20,最低6"/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">礼包说明:*</label>
                        <div class="controls">
                        <textarea rows="5" name="title" placeholder="最多输入35个汉子,包括特殊字符及空格" 
                        style="width:62%" maxlength="35"></textarea>
                        </div>
                    </div>
                            
                </form>
            </div>
            <div class="modal-footer">
             	 <button type="submit" 
             	 class="btn btn-primary btn-xs ladda-button progress-demo" 
                id="AddCDKBtn" data-style="zoom-in" >确认生成</button>  
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div> 
 <!-- end edit -->
<div class="widget-content">
<!-- 条件选项菜单 begin-->
<?php echo DevToolbox_Lib::CdkSetHtml();?>
<!-- 条件选项菜单  end-->

<!-- list  start-->
<?php if (!empty($object)):?>
<table id="tableExcel" class="table table-bordered table-striped" >
<thead>
    <tr> 
    	<th>礼包ID</th>    	
    	<th>批次</th>
    	<th>礼包说明</th>    	
        <th>账号领取规则</th>
        <th>服务器限制规则</th>
        <th>开始日期</th>
        <th>截止日期</th>
        <th>创建日期</th>
        <th>平台</th>        
        <th>数量</th>        
        <th>操作</th>        
    </tr> 
</thead>
<tbody>
	<?php if($object):?>
	<?php foreach($object as $var):?>
	<tr>
		<td style="text-align: center;" class='giftid'><?php echo $var['giftid'];?></td>
		<td style="text-align: center;" class='batch'><?php echo $var['batch'];?></td>
		<td style="text-align: center;" class='title'><?php echo $var['title'];?></td>
		<!-- -<td style="text-align: center;" class='cdk'><?php //echo $var['giftid'].$var['batch'].$var['code'];?></td> -->
		
		<td	style="text-align: center;" class='name'>		
		<?php switch ($var['gift_type']){
			case 1: echo "多账号兑换"; break;
			case 2: echo "单账号兑换";break; 
			default:echo $var['gift_type'];break;
		}?>		
		</td>
		<td	style="text-align: center;" class='title'>
		<?php 
		switch ($var['gift_rule']){
			case 1: echo "不限制"; break;
			case 2: echo "单服务器兑换（指定服务器）";break;
			case 3: echo "任意单服务器（限定1次）";break;
			default:echo $var['gift_type'];break;
		}		
		?>
		</td>
		<td	style="text-align: center;" class='datetime'><?php echo date('Y-m-d H:i:s',$var['starttime']);?></td>
		<td	style="text-align: center;" class='datetime'><?php echo date('Y-m-d H:i:s',$var['endtime']);?></td>
		<td	style="text-align: center;" class='datetime'><?php echo $var['creattime'];?></td>
		<td	style="text-align: center;" class='datetime'><?php echo $var['plafrominfo'];?></td>		
		<td	style="text-align: center;" class='datetime'><?php echo $var['codnum'];?></td>
		<td	style="text-align: center;" class='prefix'>
		<a class="btn btn-link" href="download?id=<?php echo $var['giftid']?>&batch=<?php echo $var['batch']?>" title="下载" 
		lick-codeid ="<?php echo $var['batch']?>" id="cdk-download">下载</a>		 
		</td>	
		 
	</tr>
	<?php 
	$dat.=$var['id']."=".$var['name']."=".$var['title']."=".$var['item1']."=".
	$var['item2']."=".$var['item3']."=".$var['item4']."=".$var['endtime'].",";
	?>
	<?php endforeach;?>
	<?php endif;?>
</tbody>
</table>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<?php endif;?>
<!-- list  end-->
</div>

<!-- 版权信息begin -->
<?php echo Page_Lib::footer(null,true);?>
<!-- 版权信息 end -->