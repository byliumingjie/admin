<?php
$insert_html .= Page_Lib::loadJs('user.photos');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html);
?> 
 
<div id="content-header">
	<h1>玩家头像审核管理</h1>
	<div class="btn-group" style="width: auto;"></div>
</div> 
 <script type="text/javascript">
$(function() { 
 <?php if ($showUserPhotos===1):?>
	$('#menuUntreated').click();
 <?php endif;?>
});
</script>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">玩家头像审核管理详情</a>
</div>
<?php 
$host = "http://".IP;
?>
<div class="container-fluid">	
<?php echo DevToolbox_Lib::userPhotos();?> 
  <div class="row-fluid">
  <div class="span12">
    	<div class="widget-box">	
		    <div class="widget-title">
				<span class="icon">
		        	<i class="icon-picture"></i>
		        </span> 
		        <div id='photosAllBtn'>
		        	
		        </div>  
		        <ul class="nav nav-tabs tabaparent" id="tabaparent">
		          <li ><a id='menuUntreated'  data-toggle="tab" href="#tab1">待审核图标</a></li>
		          <li><a id='menuUntreatedDetail' data-toggle="tab" href="#tab4">待审核详情</a></li>	              
	              <li><a id='menuAdopt' data-toggle="tab" href="#tab2">通过</a></li>                
	              <li><a id='menuRefuse' data-toggle="tab" href="#tab3">拒绝</a></li>
		        </ul>
			</div> 
        </div> 
        <div class="widget-content tab-content">
	  	<!-- 1页表格 正文 -->
	  		<?php 
	  		//var_dump($UntreatedList);
	  		?>
	  		<?php if (!empty($UntreatedList) && count($UntreatedList)>0 ):?>
	  		 
            <div id="tab1" class="tab-pane "> 
            	<form class="form-horizontal"  
            	method="POST" id="showIconListForm" onsubmit="return false;">        
            	 <input type="hidden" name='icontype' value='1'>
            	 <input type="hidden" name='serverId' value='1'>
            	 <ul class="thumbnails" > 
            	 
					<?php foreach ($UntreatedList as $var):?>
					<li class="span2">
						<div>
						<input  type="checkbox" 
						value=<?php echo $var['PlayerId'].'-'.$var['ImageId'].'-'.$var['ServerId'].'-'.
						$var['ExclusiveKey'].'-'.$var['PlatformId'].'-'.$var['id'];?>
						data-name="checkbox" name="checkbox[]" style="display:none">
						<span style='display:none' data-name='id'>899</span>
						<a href="#" class="thumbnail editUserPhotos tip-bottom" data-name='images'
						data-placement="bottom" data-trigger="hover" 
						title='<?php 
						echo ' 角色 '.$var['ServerId'].
						' 角色Id '.$var['PlayerId'].
	  					" 上传日期 ".$var['createTime'];?>'>
							<?php 
							//
							$platform = $_SESSION['platformInfo'][$var['ServerId']];
							/*$url = 'http://'.$platform['platformhost'].':'.$platform['platformport']
							.'/DownRoleImage/'.$var['type'].'/'.$var['PlatformId'].'/'.$var['ServerId'].'/'.
							$var['PlayerId'].'/'.$var['ImageId']; */
							$url = $host.'/facefile/untreated/'.$var['PlayerId'].'_'.$var['ImageId'].'.png';
							?>
							<img src="<?php echo $url;?>" 
							alt="找不到" />
						</a> 
						<!--<div class="actions">
							<a title="" href="#"><i class="icon-pencil icon-white"></i></a>
							<a title="" href="#"><i class="icon-remove icon-white"></i></a>
						</div>-->
						</div>
					</li>
					<?php endforeach;?>
				</ul>
				</form>
				
				<?php //if(!empty($addPerMail)):?>
                     <div style="text-align: center;">	
                        <button class="btn btn-success" id="adoptBtn" style="margin: auto;">通过</button>
                        &nbsp&nbsp
                        <button class="btn btn-danger" id="refuseBtn" style="margin: auto;">拒绝</button>
                    </div>
                <?php //endif;?>
                <!-- 分页组件 begin -->
				 <div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Untreatedpagehtml);?>
				</div>
            </div>
          <?php endif;?>
          <!-- 4页表格 待审核详情 -->
          <?php if ($UntreatedList):?>
            <div id="tab4" class="tab-pane">
            	 
                 <form class="form-horizontal"  method="POST" 
				id="showDetailListForm" onsubmit="return false;">       
            	  <input type="hidden" name='detailtype' value='2'> 
 				<table class="table table-striped table-bordered table-hover" id="rolelists">
                    <tbody style=" text-align: center;"> 
                    	<tr> 
                            <th>Id</th>
                            <th>区服</th>
                            <th>角色Id</th>
                            <th>角色昵称</th>
                            <th>头像</th>
                            <th>状态</th>                            
                            <th>上传时间</th>
                            <th>操作选项 　
                            <input style='margin: 0px' type='checkbox' id='allbtn2' title='全选/反选'/></th>
                        </tr>  
                    	<?php foreach ($UntreatedList as $var):?>
                    	<tr> 
                            <td style=" text-align: center;"><?php echo $var['id'];?></td>
                            <td style=" text-align: center;"><?php echo $var['ServerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['PlayerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['NickName'];?></td>
                            <td style=" text-align: center;"> 
                            <?php  
							$platform = $_SESSION['platformInfo'][$var['ServerId']];
							/*$url ='http://'.$platform['platformhost'].':'.$platform['platformport']
							.'/DownRoleImage/'.$var['type'].'/'.$var['PlatformId'].'/'.$var['ServerId'].'/'.
							$var['PlayerId'].'/'.$var['ImageId']; */
							
							$url = $host.'/facefile/untreated/'.$var['PlayerId'].'_'.$var['ImageId'].'.png';;

							?> 
                            <img src = "<?php echo $url;?>" 
                            width="80px" height='80px' class='img-thumbnail' alt='<?php echo $var['ImageId']?>'> 
                            </td>
                            <td style=" text-align: center;"><?php
                            
                            switch ((int)$var['type']){
                            	case 1: echo '待审核';break;
                            		case 2:echo '通过审核';break;
                            			case 3:echo '拒绝审核';break;
                            				default:echo '未定义';break;
                            }
                              ?></td>
                            <td style=" text-align: center;"><?php echo $var['createTime'];?></td>
                        <td style=" text-align: center;"><input  type="checkbox" value="<?php 
                        echo $var['PlayerId'].'-'.$var['ImageId'].'-'.$var['ServerId'].'-'.
						$var['ExclusiveKey'].'-'.$var['PlatformId'].'-'.$var['id'];?>"
						data-name="checkbox" name="checkbox[]"></td>
                        </tr> 
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    </form>
                    <div style="text-align: center;">	
                        <button class="btn btn-success" id="adoptDetaiBtn" style="margin: auto;">通过</button>
                        &nbsp&nbsp
                        <button class="btn btn-danger" id="refuseDetaiBtn" style="margin: auto;">拒绝</button>
                    </div>
                    <!-- 分页组件 begin -->
				 <div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Untreatedpagehtml);?>
				</div>
            </div>
            <?php endif;?>
          <!-- 2页表格 正文  Adoptlist $Adoptdhtml-->
          <?php if (!empty($Adoptlist) && count($Adoptlist)>0):?>
            <div id="tab2" class="tab-pane ">
                  <form class="form-horizontal"  method="POST" id="saveSerMailForm" onsubmit="return false;">        
                   <table class="table table-striped table-bordered table-hover" id="rolelists">
                    <tbody> 
                    	<tr> 
                            <th>Id</th>
                            <th>区服</th>
                            <th>角色Id</th>
                            <th>角色昵称</th>
                            <th>头像</th>
                            <th>状态</th>                            
                            <th>上传时间</th>
                        </tr>
                        <?php foreach ($Adoptlist as $var):?>
                        <tr> 
                            <td style=" text-align: center;"><?php echo $var['id'];?></td>
                            <td style=" text-align: center;"><?php echo $var['ServerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['PlayerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['NickName'];?></td>
                            <td style=" text-align: center;"> 
                            <?php				
			   $url = $host.'/facefile/adopt/'.$var['PlayerId'].'_'.$var['ImageId'].'.png';
			   ?>
                            <img src = "<?php echo $url;?>" 
                            width="80px" height='80px' class='img-thumbnail' alt='找不到'> 
                            </td>
                            <td style=" text-align: center;"><?php
                            
                            switch ((int)$var['type']){
                            	case 1: echo '待审核';break;
                            		case 2:echo '通过审核';break;
                            			case 3:echo '拒绝审核';break;
                            				default:echo '未定义';break;
                            }
                              ?></td>
                            <td style=" text-align: center;"><?php echo $var['createTime'];?></td>
                        </tr> 
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </form>  
                 <!-- 分页组件 begin -->
				 <div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Adoptdhtml);?>
				</div>
            </div>
            <?php endif;?>
            <!-- tab3 拒绝审核  BEGIN Refusehtml-->
            
            <?php if (!empty($Refuselist) && count($Refuselist)>0):?>
            <div id="tab3" class="tab-pane ">
                  <form class="form-horizontal"  method="POST" id="saveSerMailForm" onsubmit="return false;">        
                   <table class="table table-striped table-bordered table-hover" id="rolelists">
                    <tbody> 
                    <tr> 
                            <th>Id</th>
                            <th>区服</th>
                            <th>角色Id</th>
                            <th>角色昵称</th>
                            <th>头像</th>
                            <th>状态</th>                            
                            <th>上传时间</th>
                        </tr>
                        <?php foreach ($Refuselist as $var):?>
                        <tr> 
                            <td style=" text-align: center;"><?php echo $var['id'];?></td>
                            <td style=" text-align: center;"><?php echo $var['ServerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['PlayerId'];?></td>
                            <td style=" text-align: center;"><?php echo $var['NickName'];?></td>
                            <td style=" text-align: center;"> 
                            <?php  
							$platform = $_SESSION['platformInfo'][$var['ServerId']];
							/*$url ='http://'.$platform['platformhost'].':'.$platform['platformport']
							.'/DownRoleImage/'.$var['type'].'/'.$var['PlatformId'].'/'.$var['ServerId'].'/'.
							$var['PlayerId'].'/'.$var['ImageId']; */
							
							$url = $host.'/facefile/refuse/'.$var['PlayerId'].'_'.$var['ImageId'].'.png';;

							?>
                            <img src = "<?php echo $url;?>" 
                            width="80px" height='80px' class='img-thumbnail' alt='找不到'> 
                            </td>
                            <td style=" text-align: center;"><?php
                            
                            switch ((int)$var['type']){
                            	case 1:echo '待审核';break;
                            	case 2:echo '通过审核';break;
                            	case 3:echo '拒绝审核';break;
                            	default:echo '未定义';break;
                            }
                              ?></td>
                            <td style=" text-align: center;"><?php echo $var['createTime'];?></td>
                        </tr> 
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </form>  
                <!-- 分页组件 begin -->
				 <div class="row center" style="text-align: center;">	
					<?php echo htmlspecialchars_decode($Refusehtml);?>
				</div>
            </div> 
            <?php endif;?>   
        </div>
    </div>
   </div>
   <div class="modal fade" id="modal-add-event" tabindex="-1" role="dialog" aria-labelledby="showPmsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="showPmsModalLabel">头像审核</h4>
            </div>
            
            <div class="modal-body">
            <form class="form-horizontal" action="<?php echo $page['host'] ?>/user/edit" method="POST" id="updatUserForm" onsubmit="return false;">
                   
                    <input type="hidden" name="updatUid" id="display-userUid"/>                                         
                    <!-- 账号 -->
                    <div class="control-group">
                        <label class="control-label">账号：</label>
                       <div class="controls"> 
                       <input type="text" style="width:62%" 
                       class="input-mini" id="display-userAccount" name="updatAccount"  readonly="readonly"/>
					    </div>
                    </div> 
                    <!-- 密码 -->
                    <div class="control-group">
                        <label class="control-label">密码：</label>
                       <div class="controls"> 
                       <input type="password" style="width:62%" 
                       class="input-mini" name="updatPassword"/>
					    </div>
                    </div>   
              </form>                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!--    <div class="modal hide" id="modal-add-event">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Add a new event</h3>
		</div>
		<div class="modal-body">
			<p>Enter event name:</p>
			<p><input id="event-name" type="text"></p>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			<a href="#" id="add-event-submit" class="btn btn-primary">Add event</a>
		</div>
	</div> -->
	
   
	<!-- 分页组件  end -->
</div>

<!-- 分页组件 end -->
<?php echo Page_Lib::footer();?>
