<?php
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>充值补单</h1> 
        <div class="btn-group">  
        </div>
</div>
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">充值补单</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    	<?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     	<i class="icon-question"></i></a>
 </div>
<div class="container-fluid">
	<div class="widget-content">
	<?php echo DevToolbox_Lib::rechargeHtml();?>
	</div>
	 <!-- 表格 正文 
	 id
platId
server_id
RoleIndex
cbi
fee
ssid
tcd
uid
sign
signlist
status
createtime 
-->
<table class="table table-bordered table-striped" id="noticeTable">
        <thead>
        <tr>
            <th>平台</th>
            <th>区服</th>
            <th>角色ID</th>
            <th>cbi</th>
            <th>充值金额(人民币)</th>
            <th>订单号</th>
            <th>uid</th>
            <th>订单状态</th>
            <th>创建时间</th>          														
        </tr>
        </thead>
        <tbody>
          <?php if (is_array($object) && !empty($object)): ?>
            <?php foreach ($object as $listdata): ?>                 
                <tr>                   
                    <td data-name="platId" style="text-align: center;">
                    <?php echo $listdata['platId']; ?></td> 
                    
                    <td data-name="server_id" style="text-align: center;">
                    <?php echo $listdata['server_id']; ?></td>
                    
                    <td data-name="RoleIndex" style="text-align: center;">
                    <?php echo $listdata['RoleIndex']; ?></td>
                    
                    <td data-name="cbi" style="text-align: center;">
                    <?php echo $listdata['cbi']; ?></td>
                    
                    <td data-name="fee" style="text-align: center;">
                    <?php echo (int)$listdata['fee']/100; ?></td>
                    
                    <td data-name="tcd" style="text-align: center;">
                    <?php echo $listdata['tcd']; ?></td>
                    
                    <td data-name="uid" style="text-align: center;">
                    <?php echo $listdata['uid']; ?></td>
                    
                    <td data-name="status" style="text-align: center;">
                    <?php echo $listdata['status']; ?></td>
                    
                    <td data-name="createtime" style="text-align: center;">
                    <?php echo $listdata['createtime']; ?></td>                     
                </tr>               
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
 </table>	
 <!-- 分页组件 begin -->
 <div class="row center" style="text-align: center;">	
 <?php  echo htmlspecialchars_decode($pagehtml);?>
 </div>
</div>
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

