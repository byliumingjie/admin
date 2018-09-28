<?php
$insert_html = Page_Lib::loadJs('server');
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
    <h1>游戏开服设置</h1>
 	<div class="btn-group">                 
		<a class="btn btn-large tip-bottom" title="添加菜单" data-toggle="modal" data-backdrop="static" 
		data-target="#addServerModal" id="addserverMenu"><i class="icon-plus"></i>添加服务器</a>
		 
		<a class="btn btn-success btn-large" title="批量发布" data-toggle="modal"
		data-backdrop="static" data-target="#addDBconfigModel" id="adddbconfig">
		<i class="icon-plus"></i>统计数据库配置</a>
	
	</div>	 
	
	
</div>
         <div id="breadcrumb">
            <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
            <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus">    
            <?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     		<i class="icon-question"></i></a> 
           	<a href="#" title="服务器配置,在添加与编辑设置的时候注意每一个表单后面的标识符*号,表示为必填项,在操作栏
           	,分别从左向右依次顺序为编辑,删除,导出区服配置文件"
    data-placement="bottom" data-trigger="focus"  
    class="tip-bottom"><i class="icon-question-sign"></i></a>
        </div> 
<?php 
  $page = Config::get("common.page");
  $systemurl  = $page['host'].'/System/index';
  $InplatOut = Platfrom_Service::getStatDBconfig();
?>

<!-- 站内导航 结束 -->
 <div class="container-fluid">	
	  <div class="row-fluid">
	  <div class="span12">
	  	<div class="widget-box" style="margin-bottom:2px">	
		    <div class="widget-title">
				<span class="icon">
		        	<i class="icon-signal"></i>
		        </span> 
		        <ul class="nav nav-tabs tabaparent" id="tabaparent">
		          <li class='active'><a id='untreatedRelease'  data-toggle="tab" href="#tab1">服务器列表</a></li>
		          <li><a id='successRelease' data-toggle="tab" href="#tab2">平台统计数据库配置列表</a></li>
		        </ul>
			</div> 
        </div> 
        <div class="tab-content">
	        <div id="tab1" class="tab-pane active">
	        	<table class="table table-striped table-bordered table-hover" id="serverTable">
                <tr>
                    <th>开关服</th>	
                    <th>服务器ID</th>
                    <th>平台Id</th>                     
                    <th>名称</th>
                    <th>后台通讯地址</th>
                    <th>后台通讯端口</th>                   
                    <th>服务器状态</th>
                    <th>客户端通讯外网地址</th>
                    <th>客户端通讯内网地址</th>
                    <th>中心服进程数</th>
                    <th>竞技场进程数</th>
                    <th>地图进程数</th>
                    <th>网关进程数</th>
                    <th>游戏服数据库DB地址</th>                    
                    <th>游戏服数据库DB库名</th>
                    <th>游戏服数据库DB用户</th>
                    <th>游戏服数据库DB密码</th>                    
                    <th>描述</th>                    
                    <th>创建日期</th>	
                    <th>操作</th>
                    				
                </tr>
                <?php if (is_array($servers) && !empty($servers)): ?>
                
                    <?php foreach ($servers as $server): ?>
                    	<?php $openServer= NULL;?>
                		<?php if ((int)$server['type'] == 0){continue;}?>
                		
                		<?php $AllSid[] = (int)$server['type'];?>
                		<?php 
                            switch (intval($server['serverStatus']))
                            {  
                                 case 1:
                                 	   $openServer = '<button class="btn btn-mini btn-success 
 										openServer ladda-button progress-demo" data-style="zoom-in" data-value=2 data-sid='.$server['type'].'>
										<i class="icon-off icon-white"></i>
										</button>';
                                       $serverStatus =  "<font color='#5fa750'>开启</font>";
                                       break;
                                 case 2:
                                 	   $openServer = '<button class="btn btn-mini btn-danger openServer
            						   ladda-button progress-demo" data-style="zoom-in" data-value=1 data-sid='.$server['type'].'>
										<i class="icon-off icon-white"></i>
										</button>';
                                       $serverStatus= "<font color='#f89406'>维护</font>";
                                       break; 
                                 default:
                                 		$openServer = '<button class="btn btn-mini btn-danger openServer
            						   ladda-button progress-demo" data-style="zoom-in" data-value=1 data-sid='.$server['type'].'>
										<i class="icon-off icon-white"></i>
										</button>';
                                 		$serverStatus =  "关闭";
                                       break;
                             }?>
                             
                       	<tr id="<?php echo $server['id']; ?>">
                       	<td style="text-align: center;"><?php echo $openServer;?></td>			
               			<td data-name="desc2" style="display:none"><?php echo $server['desc']; ?></td>                       						
	    				<td data-name="type" style="text-align: center;"><?php echo $server['type']."服"; ?></td>
	    				<td data-name="platformId" style="text-align: center;"><?php echo $server['platformId']; ?></td>
                    	<td data-name="platformname" style=" text-align: center;"><?php echo $server['platformname']; ?></td>                            
                    	<td data-name="platformhost" style="text-align: center;"><?php echo $server['platformhost']; ?></td>
                    	<td data-name="platformport"style="text-align: center;"><?php echo $server['platformport']; ?></td>                           
                         <td data-name="serverStatus" style="display:none" ><?php echo (int)$server['serverStatus']; ?></td>
                         <td style=" text-align: center;"><?php echo $serverStatus?></td>
                            <td data-name="gameNodeHost" style="text-align: center;"><?php echo $server['gameNodeHost']; ?></td>
                            <td data-name="gameInternalHost" style="text-align: center;"><?php echo $server['gameInternalHost']; ?></td>
                            <td data-name="centerServerWorker" style="text-align: center;"><?php echo $server['centerServerWorker']; ?></td>
                       	 	<td data-name="arenaServerWorker" style="text-align: center;"><?php echo $server['arenaServerWorker']; ?></td>
                        	<td data-name="mapServerWorker" style="text-align: center;"><?php echo $server['mapServerWorker']; ?></td>
                        	<td data-name="gatewayServerWorker" style="text-align: center;"><?php echo $server['gatewayServerWorker']; ?></td>
                        	
                        	<td data-name="gamesdbHost" style="text-align: center;"><?php echo $server['gamesdbHost']; ?></td>
                        	<td data-name="platformdb" style="text-align: center;"><?php echo $server['platformdb']; ?></td>
                        	<td data-name="platformuser" style="text-align: center;"><?php echo $server['platformuser']; ?></td>
                        	<td data-name="platformpwd" style="text-align: center;"><?php echo $server['platformpwd']; ?></td>
                        	
                            <td data-name="desc" style="text-align: center;"><?php echo $server['desc']; ?></td>
                            <td data-name="openServerdate" style="text-align: center;"><?php echo $server['createtime']; ?></td>
                            <td style=" text-align: center;">
					        <a href="#" class="tip-top editServer" data-original-title="编辑"><i class="icon-cog"></i></a> 
							<a href="#" class="tip-top delServer" data-original-title="删除" 
							data-value="<?php echo $server['id'] ?>"  data-server="<?php echo $server['type']?>" data-dev-type='1'>
							<i class="icon-remove"></i></a>
							<a href="downloadSidConfig?id=<?php echo $server['id']?>" 
							class="tip-top" data-original-title="下载服务器配置"><i class="icon-download-alt"></i></a>			        
					        </td>		
					        				
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
	        </div>
	        <div id="tab2" class="tab-pane">
	        <table class="table table-striped table-bordered table-hover" id="dbconfigTable">
                <tr>
                    <th>平台Id</th>                     
                    <th>名称</th>
                    <th>数据库地址</th>
                    <th>数据库端口</th>                   
                    <th>数据库用户</th>
                    <th>数据库密码</th>
                    <th>数据库名</th>
                    <th>CDK服务器地址</th>
                    <th>CDK进程数</th>                                                       
                    <th>描述</th>                    
                    <th>创建日期</th>	
                    <th>操作</th>					
                </tr>
                <?php if (is_array($servers) && !empty($servers)): ?>
                
                    <?php foreach ($servers as $server): ?>
                		<?php if (!empty($server['type']) &&  !empty($server['platformId']) || 
                		($server['type']==0 && $server['platformId']==0)){
                			continue;
                		}?>
                		<?php $AllSid[] = (int)$server['type'];?>
                       	<tr id="<?php echo $server['id']; ?>">	
	    				 
	    				<td data-name="platformId" style="text-align: center;"><?php echo $server['platformId']; ?></td>
	    				<td data-name="platformname" style="text-align: center;"><?php echo $server['platformname']; ?></td>
                    	<td data-name="mysqlhost" style=" text-align: center;"><?php echo $server['mysqlhost']; ?></td>                            
                    	<td data-name="mysqlport" style="text-align: center;"><?php echo $server['mysqlport']; ?></td>
                        <td data-name="mysqluse" style="text-align: center;"><?php echo $server['mysqluse']; ?></td>
                        <td data-name="mysqlpasswprd" style="text-align: center;"><?php echo $server['mysqlpasswprd']; ?></td>
                        <td data-name="db" style="text-align: center;"><?php echo $server['db']; ?></td>
                        <td data-name="cdkServerHost" style="text-align: center;"><?php echo $server['cdkServerHost']; ?></td>
                        <td data-name="cdkServerWorker" style="text-align: center;"><?php echo $server['cdkServerWorker']; ?></td>
                        <td data-name="desc" style="text-align: center;"><?php echo $server['desc']; ?></td>
                        <td data-name="createtime" style="text-align: center;"><?php echo $server['createtime']; ?></td>
                         <td style=" text-align: center;">
				        <a href="#" class="tip-top editDBConcif" data-original-title="编辑"><i class="icon-cog"></i></a> 
						<a href="#" class="tip-top delServer" data-original-title="删除" 
						data-value="<?php echo $server['id'] ?>" data-server="<?php echo $server['type']?>" data-dev-type='2'>
						<i class="icon-remove"></i></a>
				        </td>						
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
	        </div>
	   </div>
   </div>
</div>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->

<?php echo Page_Lib::footer(); ?>

<!-- add server Begin -->
<div class="modal fade" id="addServerModal" tabindex="-1" role="dialog" aria-labelledby="addserverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addMenuModalLabel">添加服务器</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/user/addPlatform" method="POST" id="addserverForm" onsubmit="return false;">
                	<input type="hidden" name='devType' value='1'/>
                    <div class="control-group">
                        <label class="col-md-4 control-label">服务器名称：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformName"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">服务器ID(默认唯一):*</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="type" readonly="readonly" autofocus="" value="<?php echo max($AllSid)+1?>"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">隶属平台:*</label>
                        <div class="controls">
                        	<?php $platOut  = System_Service::getplatformInfo();?>                        	
                        	<select name='platformId'>
                        	<?php foreach ($platOut as $Inplatvar):?>
                        		<option value="<?php echo $Inplatvar['id']?>">
                        		<?php echo $Inplatvar['name'];?>
                        		</option>
                        	<?php endforeach;?>
                        	
                        	</select> <a href="<?php echo $systemurl;?>">去添加</a>
                        </div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">后台通讯IP:*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformhost" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">后台通讯端口：*</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="platformPort" value='9875'/></div>
                    </div>                                             
                    <div class="control-group">
                        <label class="col-md-4 control-label">客户端通讯外网地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gameNodeHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">客户端通讯内网地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gameInternalHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">中心服进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="centerServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">竞技场进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="arenaServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">地图进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="mapServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">网关进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gatewayServerWorker"/></div>
                    </div>
                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gamesdbHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB库名：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformdb"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB地址用户：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformuser"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB密码：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformpwd"/></div>
                    </div>
                   
                    <div class="control-group">
                        <label class="col-md-4 control-label">详细说明：</label>
                        <div class="controls">
                            <textarea class="form-control" name="desc"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addServerBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- 统计数据库配置 begin -->
<div class="modal fade" id="addDBconfigModel" tabindex="-1" role="dialog" 
aria-labelledby="addDBModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" 
                data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="adddbconfigModalLabel">添加统计数据库配置</h4>
            </div>
            <?php if (is_array($InplatOut) && count($InplatOut)>0):?>
            <div class="modal-body">
                <form class="form-horizontal" action="#" 
                method="POST" id="addDBconfigForm" onsubmit="return false;">
                	<input type="hidden" name='devType' value='2'/>
                    <div class="control-group">
                        <label class="col-md-4 control-label">名称：*</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="platformName"/></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-4 control-label">隶属平台:*</label>
                        <div class="controls">
                        	<?php //$platOut  = System_Service::getplatformInfo();?>                        	
                        	<select name='platformId'>
                        	<?php foreach ($InplatOut as $Inplatvar):?>
                        		<option value="<?php echo $Inplatvar['id']?>">
                        		<?php echo $Inplatvar['name'];?>
                        		</option>
                        	<?php endforeach;?>
                        	
                        	</select> <a href="<?php echo $systemurl;?>">去添加</a>
                        </div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库地址:*</label>
                        <div class="controls"><input type="text" 
                        class="form-control" name="mysqlhost" value='localhost'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库端口：*</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="mysqlport" value='3306'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库用户*</label>
                        <div class="controls"><input type="text" class="form-control" name=mysqluse value='root'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库密码:*</label>
                        <div class="controls"><input type="password" class="form-control" name="mysqlpasswprd"/></div>
                    </div> 
                  <!--   <div class="control-group">
                        <label class="col-md-4 control-label">重复确认密码:*</label>
                        <div class="controls"><input type="password" class="form-control" name="mysqlpasswprd2"/></div>
                    </div>  -->                                       
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库名：*</label>
                        <div class="controls"><input type="text" class="form-control" name="db"/></div>
                    </div>            
                    <div class="control-group">
                        <label class="col-md-4 control-label">CDK服务器地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="cdkServerHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">CDK服务器进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="cdkServerWorker"/></div>
                    </div>   
                    <div class="control-group">
                        <label class="col-md-4 control-label">详细说明：</label>
                        <div class="controls">
                            <textarea class="form-control" name="desc"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addDBConfigBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
            <?php endif;?>
            <?php if (empty($InplatOut)):?> 
			<div class="alert">
				<button class="close" data-dismiss="alert"></button>
				<strong>Warning!</strong>暂时没有可添加的统计配置平台,请尝试增加新平台,增加平台统计配置,或更新平台统计配置
			</div>  
			<?php endif;?>
        </div>
    </div>
</div>
<!-- 统计数据库配置 End -->
<!--editServer Modal -->
<div class="modal fade" id="editServerModal" tabindex="-1" role="dialog" aria-labelledby="editServerModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editServerModalLabel">服务器设置</h4>
            </div>
            <div class="modal-body">
	                <form class="form-horizontal" action="/servers/editServer" method="POST" id="editServerForm" onsubmit="return false;">
                	<input type="hidden" name='devType' value='3'/>
                	<input type="hidden" name="id"/>
            		<input type="hidden" name="sidstatus"/>
                    <div class="control-group">
                        <label class="col-md-3 control-label">服务器ID:*</label>
                        <div class="controls"><input type="text" class="form-control" name="type" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label">区服名称:*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformname"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">隶属平台:*</label>
                        <div class="controls">
                        	<?php $platOut  = System_Service::getplatformInfo();?>                        	
                        	<select name='platformId' id ="editplatFormId">
                        	<?php foreach ($platOut as $Inplatvar):?>
                        		<option value="<?php echo $Inplatvar['id']?>">
                        		<?php echo $Inplatvar['name'];?>
                        		</option>
                        	<?php endforeach;?>                        	
                        	</select> <a href="<?php echo $systemurl;?>">去添加</a>
                        </div>
                    </div>
                   <div class="control-group">
                        <label class="col-md-3 control-label">后台通讯IP:*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformhost"/></div>
                   </div>
                   <div class="control-group">
                        <label class="col-md-3 control-label">后台通讯端口:*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformport"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">客户端通讯外网地址：*</label>
                        <div class="controls"><input type="text" class="form-control " name="gameNodeHost" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">客户端通讯内网地址：*</label>
                        <div class="controls"><input type="text" class="form-control " name="gameInternalHost" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">中心服进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="centerServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">竞技场进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="arenaServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">地图进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="mapServerWorker"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">网关进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gatewayServerWorker"/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="gamesdbHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB库名：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformdb"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB地址用户：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformuser"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">游戏服数据库DB密码：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformpwd"/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-3 control-label">服务器是否维护:</label>
                        <div class="controls">
                            <select class="form-control"  name='serverStatus'>
                            		<option value='0'>--默认初始状态--</option>  
                            	    <option value='2'>是</option> 
                            </select>
                        </div>
                    </div>  
                    <div class="control-group">
                        <label class="col-md-3 control-label">区服描述：</label>
                        <div class="controls"><input type="text" class="form-control " name="desc" /></div>
                    </div>                  
            		
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editServerBtn">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- 编辑数据库配置 Begin -->
<div class="modal fade" id="editdbconfigrModal" tabindex="-1" role="dialog" aria-labelledby="editdbconfigModalLabel" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editdbConfigh">统计数据库配置变更</h4>
            </div>
            <div class="modal-body">
	                <form class="form-horizontal" action="/servers/editServer" method="POST" id="editDBConfigForm" onsubmit="return false;">
                	<input type="hidden" name='devType' value='4'/>
                	<input type="hidden" name="id"/>
                		<input type="hidden" name="platId" id="editDBcfgplatFormId2"/>
                    <div class="control-group">
                        <label class="col-md-4 control-label">名称：*</label>
                        <div class="controls"><input type="text" class="form-control" name="platformname"/></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-4 control-label">隶属平台:*</label>
                        <div class="controls" id="editDbconfigPlatList">
                        	<?php $platOut  = System_Service::getplatformInfo();?>                        	
                        	<select name='platformId' id="editDBcfgplatFormId">
                        	<?php foreach ($platOut as $Inplatvar):?>
                        		<option value="<?php echo $Inplatvar['id']?>">
                        		<?php echo $Inplatvar['name'];?>
                        		</option>
                        	<?php endforeach;?>
                        	
                        	</select> <a href="<?php echo $systemurl;?>">去添加</a>
                        </div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库地址:*</label>
                        <div class="controls"><input type="text" 
                        class="form-control" name="mysqlhost" value='localhost'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库端口：*</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="mysqlport" value='3306'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库用户*</label>
                        <div class="controls"><input type="text" class="form-control" name=mysqluse value='root'/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库密码:*</label>
                        <div class="controls"><input type="text" class="form-control" name="mysqlpasswprd"/></div>
                    </div>                          
                    <div class="control-group">
                        <label class="col-md-4 control-label">统计数据库名：*</label>
                        <div class="controls"><input type="text" class="form-control" name="db"/></div>
                    </div>             
                    <div class="control-group">
                        <label class="col-md-4 control-label">CDK服务器地址：*</label>
                        <div class="controls"><input type="text" class="form-control" name="cdkServerHost"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">CDK服务器进程数：*</label>
                        <div class="controls"><input type="text" class="form-control" name="cdkServerWorker"/></div>
                    </div>     
                    <div class="control-group">
                        <label class="col-md-4 control-label">详细说明：</label>
                        <div class="controls">
                            <textarea class="form-control" name="desc"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editconfigrBtn">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateServerModal" tabindex="-1" role="dialog" aria-labelledby="updateServerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="updateServerModalLabel">所有服务器</h4>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="/server/addWhiteList" method="POST" id="ListForm" onsubmit="return false;">
                    <div class="control-group">
                            <label class="col-md-4 control-label">关服说明：</label>
                            <div class="controls">
                                <textarea class="form-control" name="desc"></textarea>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="">服务器状态:</label>
                        <div class="controls">
                            <select class="form-control" name="serverStatus" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="1">白名单</option>
                                <option value="2">开服</option>
                                <option value="3">关服</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">客户端状态:</label>
                        <div class="controls">
                            <select class="form-control" name="State" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="0" >推荐</option>
                                <option value="1">爆满</option>
                                <option value="2">默认新服</option>
                                <option value="3">新服</option>
                                <option value="4">关服维护</option>
                            </select>
                        </div>
                    </div>
                </form>
                <button id="checkAll" class="btn btn-info">全选</button>
                <button id="checkNone" class="btn btn-info">取消</button>
                <table class="table table-striped table-bordered" id="serverlist">                                   
                            <?php if (is_array($allservers) && !empty($allservers)): ?>                                     
                                <?php for ($i = 0; $i < count($allservers);$i+=4): ?>
                                    <tr><?php if(!empty($allservers[$i])):?>
                                    <td>
                                        <div class="checkbox">
                                        <label class="checkbox-inline">
                                         <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$allservers[$i]['sid'].'">'.$allservers[$i]['sid']."区 ".$allservers[$i]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                        <?php endif;?>
                                    <?php if(!empty($allservers[$i+1])):?>
                                    <td>
                                        <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$allservers[$i+1]['sid'].'">'.$allservers[$i+1]['sid']."区 ".$allservers[$i+1]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                        <?php endif;?>
                                    <?php if(!empty($allservers[$i+2])):?>
                                    <td>
                                       <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$allservers[$i+2]['sid'].'">'.$allservers[$i+2]['sid']."区 ".$allservers[$i+2]['sname'];?>
                                        </label>
                                        </div> 
                                    </td>
                                    <?php else:?>
                                    <td></td>
                                     <?php endif;?>
                                    <?php if(!empty($allservers[$i+3])):?>
                                    <td>
                                     <div class="checkbox">
                                        <label class="checkbox-inline">
                                           <?php echo '<input name="servercheckbox" type= "checkbox"  value="'.$allservers[$i+3]['sid'].'">'.$allservers[$i+3]['sid']."区 ".$allservers[$i+3]['sname'];?>
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
                <button type="button" class="btn btn-primary" id="UpdateServerBtn">确认关闭</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="serverstateModal" tabindex="-1" role="dialog" aria-labelledby="serverstateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverstateModalLabel">服务器状态</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/server/serverState" method="POST" id="serverStateForm" onsubmit="return false;">
                 
                <div class="control-group">
                        <label class="col-md-3 control-label">服务器ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="serverid" readonly="true" /></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">服务器显示ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="showid"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">区服名称：</label>
                        <div class="controls"><input type="text" class="form-control" name="sname" readonly="true"/></div>
                </div>
                <div class="form-group">
                        <label class="col-md-3 control-label" for="">服务器状态:</label>
                        <div class="controls">
                            <select class="form-control" name="serverStatus" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="1">白名单</option>
                                <option value="2">开服</option>
                                <option value="3">关服</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label" for="">客户端状态:</label>
                        <div class="controls">
                            <select class="form-control" name="State" required="" autofocus="">
                                <option value="" selected="selected">请选择</option>
                                <option value="0" >推荐</option>
                                <option value="1">爆满</option>
                                <option value="2">默认新服</option>
                                <option value="3">新服</option>
                                <option value="4">关服维护</option>
                            </select>
                        </div>
                    </div>
                   <div class="control-group">
                        <label class="col-md-4 control-label">详细说明：</label>
                        <div class="controls">
                            <textarea class="form-control" name="desc"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="serverStateBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
  </div>
</div>

<!-- serverinfo Begin -->
<div class="modal fade" id="serverinfoModal" tabindex="-1" role="dialog" aria-labelledby="serverInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">服务器信息说明</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/server/serverInfo" method="POST" id="serverInfoForm" onsubmit="return false;">
                    <div class="control-group">
                        <label class="col-md-3 control-label">服务器ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="sid"  readonly="ture"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">区服名称：</label>
                        <div class="controls"><input type="text" class="form-control" name="sname" readonly="true"/></div>
                    </div>
  		   <div class="form-group">
                        <label class="col-md-4 control-label">详细说明：</label>
                        <div class="controls">
                            <textarea class="form-control" name="server_desc"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" id="serverinfoBtn">确认</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<?php echo Page_Lib::footer('');?>