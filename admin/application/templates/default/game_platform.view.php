<?php
$insert_html .= Page_Lib::loadJs('platform');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 -->
<div id="content-header">
	<h1>服务器配置</h1>
	<div class="btn-group">                 
		<a class="btn btn-large tip-bottom" title="添加菜单" data-toggle="modal" data-backdrop="static" 
		data-target="#addMenuModal" id="addMenu"><i class="icon-plus"></i>添加服务器</a>
	</div>
</div>

<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">游戏列表</a>
</div>
<div class="container-fluid" style="overflow:scroll;">
<!-- 查询组件 begin-->
<!-- 查询组件 end-->
<br>
<table id="paltformcofTable" class="table table-bordered table-striped" >
<thead >
    <tr>
        <th>ID</th>
        <th>平台ID</th>
        <th>名称</th>
        <th>区服数据库地址</th>
        <th>区服数据库端口</th>
        <th>区服数据库用户名</th>
        <th>区服db名称</th>
        <th>游戏服地址</th>
        <th>游戏服端口</th>
        <th>说明</th>											
        <th>服务器Id</th>
        <th>服务器总节点数量</th>
        <th>服务器成功开启节点数量</th>
        <th>客户端请求端口</th>
        <th>客户端请求外网地址</th> 
        <th>客户端请求内网地址</th>
        <th>操作</th>     
        <!-- <th>累计在线时长</th> --> 					
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin-->
      <?php  
      //var_dump($InPlatform);
      $j = 0;
      if (isset($InPlatform)): ?>
        <?php foreach ($InPlatform as $var): ?>   
        <?php if((int)$var['type']===0){continue;}?>   
        <?php $typedat[] = $var['type']; 
        $mysqlpwd = xxtea_lib::encode($var['mysqlpasswprd']);
        ?>	
            <tr>      
                <td style="text-align: center;" data-name="id"><?php echo $var['id'];?></td>
                <td style="text-align: center;" data-name="platformId"><?php echo $var['platformId'];?></td>
				<td style="text-align: center;" data-name="platformname"><?php echo $var['platformname'];?></td>
				<td style="text-align: center;" data-name="mysqlhost"><?php echo $var['mysqlhost'];?></td>
				<td style="text-align: center;" data-name="mysqlport"><?php echo $var['mysqlport'];?></td>
				<td style="text-align: center;" data-name="mysqluse"><?php  echo $var['mysqluse'];?></td>
				<td style="text-align: center;" data-name="db"><?php  echo $var['db'];?></td>
				<td style="text-align: center;" data-name="platformhost"><?php  echo $var['platformhost'];?></td>			 
				<td style="text-align: center;" data-name="platformport"><?php  echo $var['platformport'];?></td>                
				<td style="text-align: center;" data-name="desc"><?php  echo $var['desc'];?></td>   
				<td style="text-align: center;" data-name="type"><?php  echo $var['type'];?></td>    
				<td style="text-align: center;" data-name="totalNode"><?php  echo $var['totalNode'];?></td>
				<td style="text-align: center;" data-name="openNode"><?php  echo $var['openNode'];?></td>
				<td style="text-align: center;" data-name="gameNodePort"><?php  echo $var['gameNodePort'];?></td>
				<td style="text-align: center;" data-name="gameNodeHost"><?php  echo $var['gameNodeHost'];?></td>
				<td style="text-align: center;" data-name="gameInternalHost"><?php  echo $var['gameInternalHost'];?></td>
                <td style="text-align: center;">
                <button class="btn btn-link delplatfomr" 
                data-value="<?php echo $var['id'] ?>">删除</button> 
                <button class="btn btn-link editPlatform">编辑</button> 
                </td>                 
			</tr> 
        <?php endforeach; ?>
    <?php endif; ?> 
    <tr></tr>
    <!-- stat data end -->
    </tbody>
</table>							
</div>
<!-- Modal -->
<!-- add Menu Begin -->
<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addMenuModalLabel">添加服务器</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="/user/addPlatform" method="POST" id="addForm" onsubmit="return false;">
                    <div class="control-group">
                        <label class="col-md-4 control-label">*服务器名称：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformName" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*服务器ID(默认唯一)：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="type" readonly="readonly" autofocus="" value="<?php echo max($typedat)+1?>"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">web通讯IP:</label>
                        <div class="controls"><input type="text" class="form-control" name="platformAddr" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">web通讯端口：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformPort" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库地址：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformDBAddr" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库端口：</label>
                        <div class="controls"><input type="text" class="form-control" name="mysqlport" value='3306'/></div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库名：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformDBName" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">数据库用户名：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformDBUser" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库密码：</label>
                        <div class="controls"><input type="text" class="form-control" name="platformDBPwd" required="" autofocus=""/></div>
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
                <button type="button" class="btn btn-primary" id="addPlatformBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- edit begin-->
<div class="modal fade" id="editserverModal" tabindex="-1" role="dialog" aria-labelledby="editserverModlabe" aria-hidden="true">
    <div id="test57a"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editUserModalLabel">修改组</h4>
            </div>           
            <div class="modal-body">
                <form class="form-horizontal"  method="POST" id="editserverForm">
                    <input type="hidden" name="id" />
                     <div class="control-group">
                        <label class="col-md-4 control-label">*服务器名称：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="platformname"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*服务器ID(默认唯一)：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="type" required="" autofocus="" value="<?php echo max($typedat)+1?>" readonly="readonly"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射游戏服地址</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="platformhost" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射游戏服端口：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="platformport" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库地址：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="mysqlhost" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库端口：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="mysqlport" value='3306'/>
                        </div>
                    </div>                    
                    <div class="control-group">
                        <label class="col-md-4 control-label">映射数据库名：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="db" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">数据库用户名：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="mysqluse" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">服务器总节点数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="totalNode" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">服务器成功开启节点数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="openNode" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">客户端请求端口：</label>
                        <div class="controls"><input type="text" class="form-control" name="gameNodePort" required="" autofocus=""/></div>
                    </div>
                     <div class="control-group">
                        <label class="col-md-4 control-label">客户端外网请求地址：</label>
                        <div class="controls"><input type="text" class="form-control" name="gameNodeHost" required="" autofocus=""/></div>
                    </div>
                   <div class="control-group">
                        <label class="col-md-4 control-label">客户端内网请求地址：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="gameInternalHost" required="" autofocus=""/>
                        </div>
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
                <button type="button" class="btn btn-primary" id="editPlatformBtn">确认修改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer();?>
