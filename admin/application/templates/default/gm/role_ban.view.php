<?php
$insert_html = Page_Lib::loadJs('ajaxupload');
$insert_html.= Page_Lib::loadJs('role.ban','','tool');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html,'',true);
?>
<!-- title set tool begin-->
<div id="content-header">
    <h1>角色封停</h1>
    <div class="btn-group">        
		<a class="btn btn-danger btn-success tip-bottom" title="禁止操作" data-toggle="modal" 
		data-backdrop="static" data-target="#BanchatModal" id="forbidaccountbtn">
		<i class="icon-ban-circle"></i>禁止设置</a>
 	</div>
</div>
<!-- >top title begin-->
<div id="breadcrumb">
	<!-- 坐标系 -->
    <a href="/index/index" title="跳到首页" class="tip-bottom">
    <i class="icon-home"></i> 首页</a>
    <a href="#" class="current">处理角色</a>
    <!-- 提示帮助 -->
    <a href="#"  data-placement="bottom" data-trigger="focus"  
    class="tip-bottom" title="角色封禁解除,由查询以及禁止操作功能，本功能支持缓存记录,如果服务器处于维护状态，将有系统缓存代理执行,待
    该服开服就会执行缓存操作，查询的数据是对应选项匹配下的封禁信息，可以进行在操作栏查询玩家的当前数据，如果区服暂无开服会做错提示，
    查看操作栏弹出角色当前基本数据，并且可以进行解封，如该服务器为维护则不显示当前数据但可以根据提示，会给解封操作，最终会记录缓存,
  在查不到的时候注意角色的封禁最后结束时间是否过去或是否被接除封禁">
    <i class="icon-question-sign"></i>
    </a>     
     
</div>
<!-- <top title end-->
<div class="container-fluid">
<br>
<!-- warning info begin -->
<?php if(empty($object) && isset($datasub)):?>
<div class="widget-content" id= "roleinfo">
		 
		<div class="alert">
			<button class="close" data-dismiss="alert">×</button>
			<strong>Warning!</strong><?php echo "无封禁数据";?>.
		</div> 
	 
<?php endif;?>
<!-- end --> 
<!-- error info begin --> 
<?php if ( isset($cachedata) && !empty($cachedata) && is_array($cachedata)):?>
<?php $methodcfg = Config::get("key.gm");?>
<div class="alert alert-error">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error! 
	<span class="badge badge-important"><?php echo count($cachedata)?></span>  .
	</strong>
	<br>
	<textarea rows="" style="margin: 0px 0px 20px; width: 90%; height: 40px;" 
	class="alert alert-error">
	<?php foreach ($cachedata as $cachevar):?>	
	<?php
	$codeinfo =	"code:".$cachevar['protocolCode'];
	if (isset($methodcfg[$cachevar['protocolCode']])){
		$codeinfo =	$methodcfg[$cachevar['protocolCode']];
	}
	echo $codeinfo.",缓存数据执行失败,创建日期".$cachevar['createtime'].
	",系统执行日期".$cachevar['lasttime']."失败原因".$cachevar['responseData'];?>	
	<?php endforeach;?>
	</textarea>
	 
</div> 
<?php endif;?>
<!-- end -->
 
		          
	<?php echo   DevToolbox_Lib::rolebanHtml('index');?>  
	<!-- lod file info -->  
	<?php  if(!empty($roleBanInfo)): ?>
	<table id="tableExcel" class="table table-striped table-bordered table-hover" >
		<thead>
		    <tr>
		   		<th>Id</th>
		    	<th>平台Id</th> 		    	 
		        <th>区服</th>          
		        <th>封禁角色Id</th>
		        <th>封禁角色昵称</th>			        
		        <th>封禁类型</th>
		        <th>封禁规则</th>
		        <th>创建日期</th>        
		        <th>封禁截止日期</th>
		        <th>封禁原因</th>
		        <th>操作人</th>		         
		        <th>操作</th>
		    </tr> 
		</thead>
		<tbody>	
		 	<?php $channelcfg = Config::get("key.gm.channel");?>
			<?php foreach ($roleBanInfo as $inkey =>$invar):?>			 
			<tr>
			<td style="text-align: center;" data-name="id"><?php echo $invar['id'];?></td> 
			<td style="text-align: center;"data-name='platId'><?php echo $invar['platId'];?></td>
			<td style="text-align: center;" data-name="serverId"><?php echo $invar['serverId'];?></td>
			<td style="text-align: center;" data-name="player_id"><?php echo $invar['player_id'];?></td>
			<td style="text-align: center;" data-name="nick_name"><?php echo $invar['nick_name'];?></td>
			<td  style="display: none" data-name="lockType"><?php echo (int)$invar['lockType'];?></td>
			<td style="text-align: center;" >
			<?php 
			 switch ((int)$invar['lockType'])
			 {
			 	case 1: echo '禁言'; break;
			 	case 2: echo '封号'; break;
			 	case 3: 
			 		if ($invar['lockStatus']==1){
			 			echo '解封禁言';
			 		}
			 		if($invar['lockStatus']==2){
			 			echo '解封登陆';
			 		} 
			 		break;
			 	case 4: echo '异常'; break;
			 	
			 	default: echo '未知'; break;
			 }
			?>
			</td>
			<td  style="display: none" data-name="lockTimeType"><?php echo (int)$invar['lockTimeType'];?></td>
			<td style="text-align: center;"><?php 
			 
			switch ((int)$invar['lockTimeType'])
			{
				case 1: echo '普通封号'; break;
				case 2: echo '永久封号'; break;
				default: echo '无'; break;
			}
			?></td>
			<td style="text-align: center;" data-name="createtime">
			<?php echo $invar['createtime'];?></td>
			<td style="text-align: center;" data-name="lockEndtime"><?php  
			if($invar['lockEndtime']!=0)
			{
				echo date('Y-m-d H:i:s',$invar['lockEndtime']);
			}else{
				echo 0;
			}?></td>
			<td style="text-align: center;" data-name="description"><?php echo $invar['description'];?></td>
			<td style="text-align: center;" data-name="executor">
			<?php echo $invar['executor'];?></td>
			<td>
			<button class="btn btn-link btn-xs  currentData" 
			data-style="expand-right" id="currentData">变更</button></td>
			</tr>	
			<?php endforeach;?>
		</tbody> 
		</table>
		<!-- lod file infoend -->
	<!-- 分页组件 begin -->
	<div class="row center" style="text-align: center;">	
	<?php  echo htmlspecialchars_decode($pagehtml);?>
	</div>
	</div>
	
</div>

<?php endif;?>
<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addFileModalLabel">选择文件</h4>
            </div>
            <div class="modal-body">
               <div class="control-group">
                    <label class="col-md-3 control-label">
                    <input type="text" readonly="readonly" value="" id="state" style="margin:0px"/>                    
                    <a class="btn" id="selector">
                    <i class="icon-file"></i></a></label>                    
                    <div class="controls" id="listsheet">                     
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

<!-- forbiden account Modal禁止玩家聊天 -->
<div class="modal fade" id="BanchatModal" tabindex="-1" role="dialog" 
aria-labelledby="forbidAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="forbidAccountModalLabel">封禁</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="roleLock" id="lockFrom" method="POST">
                	<div class="control-group">
                        <label class="col-md-3 control-label">平台:*</label>
                        <div class="controls">
                        <?php  echo DevToolbox_Lib::getplatListInfo(NULL,'platById2');?>
                        </div>
                    </div> 
                    <div class="control-group" id="serverGroup" style="display: none">
                        <label class="col-md-3 control-label">区服:*</label>
                        <div class="controls">
                        <select name='server' id='ServerId2'
						class='form-control' style=' display:none' 
						title='请选择区服'></select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">用户类型:*</label>
                        <div class="controls">
                            <select class="form-control" name="roleType" id="roleType">
                                <option value=0 selected="selected">请选择</option>
                                <option value=3>角色昵称</option>  
                                <option value=2>角色ID</option> 
                            </select>
                        </div>
                    </div>
                   	<div class="control-group">
                        <label class="col-md-3 control-label">用户值:*</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="userVar" id="rolekey"style="width:62%"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">禁止类型:*</label>
                        <div class="controls">
                            <select class="form-control" name="bantype" id="bantype"> 
                                <option value=1>禁言</option>
								<option value=2>禁登录</option>
								<option value=3>解封禁言</option>
								<option value=4>解封登陆</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="timetype">
                        <label class="col-md-3 control-label" for="">禁止时间类型:*</label>
                        <div class="controls">
                            <select class="form-control" name="banTimetype" id="banTimetype"> 
                                <option value=2>永久禁封</option>
								<option value=1>普通禁封</option> 
                            </select>
                        </div>
                    </div>
                     
                    <div class="control-group" id="Banendtime">
                        <label class="col-md-3 control-label">封禁截止日期:*</label>
                        <div class="controls">
                         <input type="text" placeholder='对应时间单位填写'
                        class="datetimepicker form-contro"  name="endtime" style="width:62%"/> 
                        </div>
                    </div>   
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">封禁原由:*</label>
                        <div class="controls">
                           <textarea name="desc" cols="" rows="" style="width:62%"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="ban_status" value="1"/>
                  
                </form>
            </div>
            <div class="modal-footer">     
                <button type="button" class="btn btn-danger" id="lockBtn">确认执行</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade" id="editRoleBanModal" tabindex="-1" 
role="dialog" aria-labelledby="editRoleBanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" 
                aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editUserModalLabel">封禁变更</h4>
            </div>
           
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" 
                id="EditRoleBanForm" onsubmit="return false;">
                   
                    <input type="hidden" name="platId"/> 
                    <input type="hidden" name="originalBanType"/>
                    <div class="control-group">
                        <label class="control-label">区服：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini"  name="serverId"  readonly="readonly"/>
					    </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label">角色编号：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini"  name="player_id"  readonly="readonly"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">角色昵称：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini"  name="nick_name"  readonly="readonly"/>
					    </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">禁止类型:*</label>
                        <div class="controls">
                            <select class="form-control" name="lockType" id="editlockType"> 
                                <option value=1>禁言</option>
								<option value=2>禁登录</option>
								<option value=3>解封禁言</option>
								<option value=4>解封登陆</option> 
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="editBanTimestatus">
                        <label class="col-md-3 control-label" for="">禁止时间类型:*</label>
                        <div class="controls">
                            <select class="form-control" name="lockTimeType" id="editbanTimetype"> 
                                <option value=2>永久禁封</option>
								<option value=1>普通禁封</option> 
                            </select>
                        </div>
                    </div>
                     
                    <div class="control-group" id="editBanEndTime">
                        <label class="col-md-3 control-label">封禁截止日期:*</label>
                        <div class="controls">
                         <input type="text" placeholder='对应时间单位填写'
                        class="datetimepicker form-contro"  name="lockEndtime" style="width:62%"/> 
                        </div>
                    </div>   
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">封禁原由:*</label>
                        <div class="controls">
                           <textarea name="description" cols="" rows="" style="width:62%"></textarea>
                        </div>
                    </div>  
			  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editBanRoleBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- 用户当前数据mode展示 -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">用户当前信息获取</h4>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="userUnlockForm" onsubmit="return false;">
                 <input type="hidden" class="form-control" data-name="protocolCode" name="protocolCode" />
                 <input type="hidden" class="form-control" data-name="keyid" name="keyid" />
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色Id：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="playerId" name="playerId" style="width:62%" readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色名：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="playerName" name="playerName" style="width:62%" readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">等级：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="level" name="level" style="width:62%"readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">区服：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="server" name="server" style="width:62%" readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">钻石：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="diamond" name="diamond" style="width:62%"readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">金币：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="gold" name="gold" style="width:62%"readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">充值数量：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="paid" name="paid" style="width:62%"readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">最后登录时间：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="lastOfflineTime" name="lastOfflineTime" style="width:62%"readonly="ture"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">总登陆时长：</label>
                        <div class="controls">
                        <input type="text" class="form-control" 
                        data-name="onlineState" name="onlineState" style="width:62%"readonly="ture"/>
                        </div>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="UnlockBtn">解除封禁</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>

 <?php echo Page_Lib::footer(null,true); ?>
