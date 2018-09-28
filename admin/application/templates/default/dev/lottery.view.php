
<?php
$insert_html.= Page_Lib::loadJs('lottery','','tool');
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
    <h1>后台基本操作</h1>
        <div class="btn-group">   
          <a class="btn btn-large tip-bottom" title="创建活动" 
          data-toggle="modal"data-backdrop="static" 
          data-target="#addActivityuModal" id="addName">
          <i class="icon-plus"></i>创建活动</a>   
    	</div>
</div>
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">抽奖开启/关闭</a>
 </div>
<div class="container-fluid">	
<div class="row-fluid">
<!-- 条件选项菜单 begin-->
<?php echo DevToolbox_Lib::lotteryHtml();?>
<!-- 条件选项菜单  end-->
 
<?php if(isset($object['result']) && count($object['result'])>0):?>
<?php 
 
?>
<table class="table table-striped table-bordered table-hover" id="lotterylists">
<thead>
    <tr> 
    	<th>流水号</th>
    	<th>抽卡类型</th>
    	<th>活动Id</th>
    	<th>活动状态</th>  
    	<?php if ($object['result'][0]['type']!=4 && $object['result'][0]['type']!=3):?>      
        <th>活动和开始时间</th>          
        <th>活动结束时</th>        
        <?php endif;?>
        <th>服务器</th>
        <th>操作</th>
    </tr> 
</thead>
<tbody>	 
	<?php foreach ($object['result'] as $var):?>
	<?php 
		$id = $var['id'];//
		$type =  $var['type'];
		$card_id = $var['cardId'];
		$status = $var['status'];//
		$start_time = $var['startTime'];
		$end_time = $var['endTime'];
		$server = $var['server']; 
	?>
	<tr>
		<td style="text-align: center;" class='idd' data-name="id"><?php echo $id;?></td>
		<td style="display: none" data-name="type"><?php echo $type;?></td>			 
		<td style="text-align: center;" class='idd' data-name="typeStr"><?php 
		switch ($type){
			case 1: echo '限时';break;
			case 2: echo '每周';break;
			case 3: echo '常驻';break;
			case 4: echo '常驻';break;
			default:echo("未知");break;			
		}
		?></td>
		<td style="text-align: center;" class='idd' data-name="card_id"><?php echo $card_id;?></td>
		<td style="display: none" data-name="status"><?php echo $status;?></td>	
		<td style="text-align: center;" class='idd' data-name="statusStr"><?php 
		switch ($status){
			case -1: echo '尚未开启';break;
			case 0: echo '已结束';break;
			case 1: echo '激活';break;			 
			default:echo("未知");break;
		}		
		?></td>
		<?php if ($object['result'][0]['type']!=4 && $object['result'][0]['type']!=3):?>  
		<td style="text-align: center;" class='idd' data-name="startTime"><?php echo date("Y-m-d H:i:s",$start_time);?></td>
		<td style="text-align: center;" class='idd' data-name="endtime">
		<?php 
		if($object['result'][0]['type']!=2){
			echo date("Y-m-d H:i:s",$end_time);
		}else{
			echo $end_time;
		}
		?></td>
		<?php endif;?>
		<td style="display: none" data-name="sid"><?php echo 1;?></td>
		<td style="text-align: center;" class='idd' data-name="server"><?php echo $servers;?></td>
		<td> 
		<?php if ($status!=0 || $type ==3 || $type ==4):?>
		<button class="btn btn-link editlotter">编辑</button>          
        <?php endif;?>                         
        
        </td>
	</tr>
	<?php endforeach;?>
</tbody>
</table>

<?php endif;?>
<!-- 视图列表信息  end-->
</div></div>
<div class="modal fade" id="addActivityuModal" tabindex="-1" 
role="dialog" aria-labelledby="addFileModalLabel" 
aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editMenuModalLabel">活动创建</h4>
            </div>
            <div class="modal-body" >
                <form class="form-horizontal" action="createActivity" 
                method="POST" id="editMenuForm"   onsubmit="return false;">
                	<input type="hidden" class="form-control" name="addType"  value='0' /> 
                                  
                    <div class="control-group">
                        <label class="col-md-4 control-label">区服ID:*</label>
                        <div class="controls"><input type="text"  style="width:62%"
                        class="form-control" name="server" /></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">抽卡id:*</label>
                        <div class="controls">
                        <input type="text"  style="width:62%" tyle="width:62%" class="form-control" name="card_id" />
                      
                        </div>
                    </div>
                     <div class="control-group" >
                        <label class="col-md-4 control-label">抽奖类型:*</label>
                        <div class="controls">
                        <select name="type" class="form-control" id="type"  style="width:62%">
                        <option value=0>--请选择--</option>
                        <option value=1>限时</option>
                        <option value=2>每周</option>
                        <!-- <option value=2>常驻遗迹</option>
                        <option value=4>常驻王座</option> --> 
                        </select>
                        </div>
                    </div>                   
                     
                     <div class="control-group" id="timeType">
                        <label class="col-md-4 control-label" >执行时间:*</label>
                        <div class="controls">
	                        <select name='timeType' id="timeTypeName" class="form-control" >
                        		<option value=0>--请选择--</option>
                        		<option value=1>立即执行</option>
                        		<option value=2>指定开始时间点</option> 
                        	</select>
                        </div>
                    </div>  
                    <div class="control-group"  id="startTime">
                        <label class="col-md-4 control-label" >开始时间:*</label>
                        <div class="controls">
	                        <input type="text" class="datetimepicker form-control" name="startTime"
                        	placeholder="必须为大于当前的日期"> 
                        </div>
                    </div>  
                     <div class="control-group"  id="endtime" style="display: none">
                        <label class="col-md-4 control-label" >结束时间:*</label>
                        <div class="controls">
	                        <input type="text" class="datetimepicker form-control" name="endtime"
                        	placeholder="必须为大于当前的日期"> 
                        </div>
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="buttton" class="btn btn-primary btn-xs ladda-button" 
                id="addLottery" data-style="expand-right">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
            
        </div>
    </div>
</div> 

<div class="modal fade" id="editlotterModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editUserModalLabel">活动修改</h4>
            </div>
           
            <div class="modal-body">
                <form class="form-horizontal" action="#" onsubmit="return false;" method="POST" id="updatLotteryForm">
                    
                    <input type="hidden" name="type" /> 
                    <input type="hidden" name="status" />
                    
                    <div class="control-group">
                        <label class="control-label">区服ID：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini" id="display-userAccount" name="server" />
					    </div>
                    </div>
                     
                    <div class="control-group">
                        <label class="control-label">流水号：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini" id="display-userAccount" name="id" />
					    </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label">抽卡类型：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="typeStr"/>
					    </div>
                    </div> 
                     
                    <div class="control-group">
                        <label class="control-label">设置状态：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="statusStr"/>                        
					    </div>
                    </div>  
                    <div class="control-group">
                        <label class="control-label">活动Id：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" class="input-mini" name="card_id"/>
					    </div>
                    </div> 
                    <div class="control-group" id="timeTypelist">
                        
                    </div> 
                     <div class="control-group" id="startTimelist">
                        <div class="controls">
							<input type="text" style="width:62%" class="input-mini datetimepicker form-control" name="startTime"/>
						</div>
                    </div>  
                     <div class="control-group" id="endtimelist">
                     	<div class="controls">
                       		<input type="text" style="width:62%" class=" input-mini datetimepicker form-control" name="endtime"/>
                     	</div>
                    </div>
            </div>
            <div class="modal-footer">
            <!-- <button type="submit" class="btn btn-inverse" id="editLotteryBtn"><i class="icon-refresh icon-white"></i>确认修改</button> -->
                <button type="submit" class="btn btn-primary" id="editLotteryBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
		 
        window.onload = function()
        {    
            
            //
            var type = document.getElementById('type');
            var timeType = document.getElementById('timeType');
			var startTime = document.getElementById('startTime');
            var endtime = document.getElementById('endtime');
            var timeTypeName = document.getElementById('timeTypeName');	
			//var operateId = document.getElementById('operateId');
			 endtime.style.display ='none';	
			//operateId.style.display = 'none';
            timeType.style.display = 'none';
			startTime.style.display ='none';
            if( type.value ==1 ){
				//operateId.style.display = 'block';
            	timeType.style.display = 'block'; 
            }else{				
				//operateId.style.display = 'none';
            	timeType.style.display = 'none'; 
            }
			if(type.value == 2){
				endtime.style.display ='none';
				startTime.style.display ='block'; 
			}
            type.onchange = function(){
            	timeType.style.display = this.value==1? 'block' : 'none'; 
            	//operateId.style.display = this.value==1? 'block' : 'none';
			
            	if(this.value>=0){
            		timeTypeName.value = 0;            		
                	endtime.style.display = 'none';
					startTime.style.display ='none';
                }
				if(this.value==2){
					startTime.style.display ='block';
					endtime.style.display ='none';	
				} 
			} 
			 
            timeTypeName.onchange = function()
            {
                //alert(this.value);
            	startTime.style.display = this.value==2? 'block' : 'none';
            	endtime.style.display =  this.value==2 || this.value==1 ? 'block' : 'none';
			}	
			if(timeTypeName.value == 2 ){
				startTime.style.display = 'block';	
				endtime.style.display = 'block';	
 					
			} else{
				startTime.style.display = 'none';
				endtime.style.display = 'none';	
			}
			if(type.value == 2){
				endtime.style.display ='none';
				startTime.style.display ='block'; 
			} 
			
		} 
</script>
<?php 
    echo Page_Lib::footer(''); 
?>
  