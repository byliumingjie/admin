<?php 
$insert_html =Page_Lib::loadJs('recharge');
$insert_html.=Page_Lib::loadJs('chosen.jquery');
$insert_html.= Page_Lib::loadJs('server.list');
$insert_html.=Page_Lib::loadCss('chosen');

echo Page_Lib::head($insert_html,'',true);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
  <h1>订单查询</h1> 
  <div class="btn-group">
   
  </div>
</div>
<!-- top  start -->
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">订单查询</a>
    <a href="#" title="目前暂时不支持使用角色ID及昵称查找,
          后期底层支持在更新该功能,暂时角色ID可输入RoleIndex查询"
    data-placement="bottom" data-trigger="focus"  
    class="tip-bottom"><i class="icon-question-sign"></i></a>
</div>
<!-- data info begin -->
<div class="widget-content">
<!-- 查询条件 bgin -->
<?php echo   DevToolbox_Lib::rechargeHtml($data);?>
<!-- 查询条件 end -->
<?php if (is_array($object) && !empty($object)):?>
<table class="table table-bordered table-striped" id="ordertable">
        <thead>
        <tr>
            <th>平台</th>
            <th>区服</th>
            <th>RoleIndex</th>
            <th>档位</th>
            <th>充值棒棒糖</th>
            <th>充值金额(人民币)</th>
            <th>订单号</th>
            <th>uid</th>
            <th>status</th>
            <th>订单状态</th>
            <th>创建时间</th>												
        </tr>
        </thead>
        <tbody>          
            <?php foreach ($object as $listdata): ?>                 
                <tr>              
                	<td data-name="id" style="display: none"><?php echo $listdata['id']; ?></td> 
                    <td data-name="ssid" style="display: none"><?php echo $listdata['ssid']; ?></td>     
                    <td data-name="platId" style="text-align: center;"><?php echo $listdata['platId']; ?></td>
                    <td data-name="server_id" style="text-align: center;"><?php echo $listdata['server_id']; ?></td>
                    <td data-name="RoleIndex" style="text-align: center;"><?php echo $listdata['RoleIndex']; ?></td>
                    <td data-name="cbi" style="text-align: center;"><?php echo $listdata['cbi']; ?></td>
                    <td data-name="fee" style="text-align: center;"><?php echo (int)$listdata['fee']; ?></td>
                    <td data-name="fee2" style="text-align: center;"><?php echo (int)($listdata['fee']/100); ?></td>
                    <td data-name="tcd" style="text-align: center;"><?php echo $listdata['tcd']; ?></td>
                    <td data-name="uid" style="text-align: center;"><?php echo $listdata['uid']; ?></td>
                    <td data-name="status" style="display: none"><?php echo (int)$listdata['status'];?></td>
                    <td ><?php echo (int)$listdata['status'];?></td>
                    <td style="text-align: center;">
                    <?php 
                    //0-默认0待发布 1-已发布 2-发布失败
                    switch ( (int)$listdata['status'] )
                    {
                    	case  1 : 
                    		echo "待处理";
                    	break;
                    	case  2 : 
                    		echo "充值成功";
                    	break;
                    	case  3 : 
                    	echo "<button class='btn btn-link addbackBtn' 
                    	id='orderup'
				        data-value={$listdata['id']}
				        data-server-id={$listdata['server_id']}>失败补单</button>";
                    	break;
                    	case  4 : 
                    		echo "人工补单成功";
                    	break;
                    	case  5 : 
                    	echo "<button class='btn btn-link addbackBtn' 
                    	id='orderup'
				        data-value={$listdata['id']}
				        data-server-id={$listdata['server_id']}>人工补单失败</button>";
                    	break;
                    	default:
                    		echo '未定义'; break;
                    }
                    ?></td>                    
                    <td data-name="createtime" style="text-align: center;">
                    <?php echo $listdata['createtime']; ?></td>                                        
                </tr>               
            <?php endforeach; ?>        
        </tbody>
 </table>	
 <?php endif; ?>
 <!-- 分页组件 begin -->
 <div class="row center" style="text-align: center;">	
 <?php  echo htmlspecialchars_decode($pagehtml);?>
 </div>
</div>
<!-- data info end -->

<!-- 　补单 show　begin -->
<div class="modal fade" id="submitOrderModal" tabindex="-1" role="dialog" aria-labelledby="submitOrderModalLabel" 
aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="submitOrderModalLabel">补单内容确认</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="EditOrder" method="POST" id="submitOrderForm">                	
                    <input type="hidden" name="id">
                    <input type="hidden" name="platId">
                    <input type="hidden" name="ssid">                    
                     
                    <div class="control-group">
                        <label class="col-md-4 control-label">区服:</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="server_id" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">账号Id</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="RoleIndex" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">UID:</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="uid" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label" >订单:</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="tcd" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">档位:</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="cbi" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">棒棒糖:</label>
                        <div class="controls">
                        	<input type="text" style="width:62%" class="form-control" name="fee" readonly="true"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="col-md-4 control-label">补单原因:</label>
                        <div class="controls">
                        	<textarea rows="5"  style="width:62%" name='description'></textarea>
                        </div>
                    </div>
                
                </form>
            </div>
            <div class="modal-footer">
            <button 
            class="btn btn-primary btn-xs ladda-button progress-demo" id="submitbtn" data-style="zoom-in">
            <span class="ladda-label">确认补单</span><span class="ladda-spinner"></span></button>          
            <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
 

<div class="modal fade" id="orderlogMode" tabindex="-1" role="dialog" aria-labelledby="submitOrderModalLabel" 
aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="submitOrderModalLabel">订单失败日志</h4>
            </div>
            <div class="modal-body" id="orderLog">
                  
            </div> 
        </div>
    </div>
</div>
<!--补单 show　end -->

<!-- 下拉菜单多选回调函数 begin -->
<script type="text/javascript">
	$(".chzn-select").chosen();
	$(".chzn-select2").chosen();
	$(".chzn-select3").chosen();		
</script>
<!-- 下拉菜单多选回调函数 end -->

<!-- 下载组件 Begin-->
<?php if(!empty($data)):?>
<?php 
$key ="日期"."\t"."联运订单号"."\t"."游戏订单号"."\t"."游戏版本"
 ."\t"."渠道"."\t"."账号"."\t"."UID"."\t".
 "UIN"."\t"."平台"."\t"."区服"."\t"."角色ID"."\t"."购买商品"."\t"."金额"."\t"."获得钻石".
 "\t"."是否测试"."\t"."订单状态"."\t"."订单说明"."\t"."订单日志";
?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="type" value=""/>	 
	<input  type="hidden" name="sid" value=""/>	 
	<input  type="hidden" name="data" value="<?php echo $dowloadOut;?>"/>	 
	<input  type="hidden" name="time" value=" "/>
	<input  type="hidden" name="key" value="<?php echo $key;?>"/>                    
</form> 
<?php endif;?>
 
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

