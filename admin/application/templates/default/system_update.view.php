<?php
$insert_html .= Page_Lib::loadJs('system');
$insert_html .= Page_Lib::loadJs('multselect');
echo Page_Lib::head($insert_html);
?> 
<script type="text/javascript">
<!--
 
//-->
</script>
<!-- 站内导航 -->
<div id="content-header">
	<h1>平台版本更新</h1>
	<div class="btn-group">                 
		<a class="btn btn-large tip-bottom" title="添加菜单" data-toggle="modal" data-backdrop="static" 
		data-target="#addplatformCfgModel" id="addplatformCfg"><i class="icon-plus"></i>添加平台配置</a>
	</div>
</div>

<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">配置信息列表</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<!-- 查询组件 end-->
<br>
<table id="paltformcofTable" class="table table-bordered table-striped" >
<thead >
    <tr>
        <th>平台ID</th>
        <th>平台名称</th>
        <th>安装包版本号</th>
        <th>资源版本号</th>
        <th>安装包下载地址</th>
        <th>资源下载地址</th>
        <th>操作</th>	
    </tr>
    </thead>
    <tbody>
    <!--统计数据项 begin-->
      <?php  
      //var_dump($InPlatform);
      $j = 0;
      if (isset($systemCfgInfo)): ?>
        <?php foreach ($systemCfgInfo as $var): ?> 
            <tr>      
                <td style="text-align: center;" data-name="id"><?php echo $var['id'];?></td>
				<td style="text-align: center;" data-name="name"><button class="btn btn-link platformSIDLock"><?php echo $var['name'];?></button></td>
				<td style="text-align: center;" data-name="appVersion"><?php echo $var['appVersion'];?></td>
				<td style="text-align: center;" data-name="resVersion"><?php echo $var['resVersion'];?></td>
				<td style="text-align: center;" data-name="downloadAppURL"><?php  echo $var['downloadAppURL'];?></td>
				<td style="text-align: center;" data-name="downloadResURL"><?php  echo $var['downloadResURL'];?></td>
                <td style="text-align: center;">
                <!-- <button class="btn btn-link delplatfomr" 
                data-value="<?php echo $var['id'] ?>">删除</button> --> 
                <button class="btn btn-link editPlatformCfgBtn">编辑</button> 
                </td>                 
			</tr> 
        <?php endforeach; ?>
    <?php endif; ?> 
    <tr></tr>
    <!-- stat data end -->
    </tbody>
</table>							
</div>
<!--system plat sid list info-->
<div class="modal fade" id="serverinfoModal" tabindex="-1" role="dialog" aria-labelledby="serverInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">所属区服信息</h4> 
            </div>
            <form action="#" id='serverInfoForm'>
	            <div class="modal-body">               
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">                        
                        <table class="table table-striped table-bordered" id="lookserverlist">
				 		 
				 		 </table>
                         </div>
                    </div>                                             
	            </div>
            </form>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<!-- Modal -->
<!-- add Menu Begin -->
<div class="modal fade" id="addplatformCfgModel" tabindex="-1" role="dialog" aria-labelledby="#addplatformCfgLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addMenuModalLabel">添加服务器</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="system/addPlatformCfg" method="POST" id="addForm" onsubmit="return false;">
                    <div class="control-group">
                        <label class="col-md-4 control-label">*平台名称：</label>
                        <div class="controls"><input type="text" class="form-control" name="name" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*安装包版本号：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="appVersion" required="" autofocus="" value="1.0.0.1"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*资源版本号:</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="resVersion" required="" autofocus="" value="0.0.0.0"/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*安装包下载地址：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="downloadAppURL" required="" autofocus=""/></div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">*资源下载地址：</label>
                        <div class="controls"><input type="text" class="form-control" 
                        name="downloadResURL" required="" autofocus=""/></div>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addplatformCfgBtn">确认添加</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
 
<!-- edit begin-->
<div class="modal fade" id="editPlatformModal" tabindex="-1" role="dialog" aria-labelledby="editPlatformModlabe" aria-hidden="true">
    <div id="test57a"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editUserModalLabel">修改组</h4>
            </div>           
            <div class="modal-body">
                <form class="form-horizontal"  method="POST" id="editplatformCfgForm">
                    <input type="hidden" name="id"/> 
                     <div class="control-group">
                        <label class="col-md-4 control-label">*平台名称：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="name"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">安装包版本号：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="appVersion" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">资源版本号：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="resVersion" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">安装包下载地址：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="downloadAppURL" required="" autofocus=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">资源下载地址：</label>
                        <div class="controls">
                        <input type="text" class="form-control" name="downloadResURL" value='3306'/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">是否更新区服：</label>
                        <div class="controls">
                        <select name="ifUpserver" id="ifUpserver" onclick="setSidVerify()">
                        <option value=0>--是否更新区服--</option>
                        <option value=1>是</option>
                        <option value=0>否</option>                        
                        </select>
                        </div>
                    </div> 
                    <div class="control-group" >			    
					    <div class="controls" style="margin-left:40px">
				 		 <select  class="form-control liOption"  id="liOption"  multiple="multiple"  name="serverId[]" size="10">
				 		  		 	 		   
					     </select>
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
