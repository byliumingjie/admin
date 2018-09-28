<?php
$insert_html =Page_Lib::loadJs("statdata");
/* $insert_html .=Page_Lib::loadJs("unicorn.dashboard");	
$insert_html .=Page_Lib::loadJs("jquery.peity.min"); */
echo Page_Lib::head($insert_html,'',1);

?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>当前在线玩家</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statOnlineRoleHtml();?>
<?php 
	if (isset($request) && empty($object))
	{	 
		echo DevToolbox_Lib::show(2, '数据为空', '所请求的数据查找为空或并不存在');
	}
?> 
 

<!-- 查询组件 end-->
<?php if (!empty($object) && !empty($total)):?>
<div class="container-fluid">
 	<div class="row-fluid">
      <div class="span11 center" style="text-align: center;">					
          <ul class="stat-boxes">
            <li>
               <div class="left peity_bar_good" >
               <!-- <span>2,4,9,7,12,10,12</span> --></div>
               <div class="right">
               <strong id="number"><?php echo $total;?></strong>
                  	在线总人数
               </div>
           </li>                           
        </ul>
    </div>	
</div>
</div>
 <div class="widget-title">
	<span class="icon">
		<i class="icon-eye-open"></i>
	</span>
	<h5>Browsers</h5>
</div>
<div class="widget-content nopadding">
<table id="tableExcel" class="table table-striped table-bordered table-hover" >
<thead >
    <tr>
	    <th data-field="Id" data-sortable="true">Id</th>
		<th>账号</th>
		<th>玩家名</th>
		<th>等级</th>
		<th>总充值</th>
		<th>当前钻石</th>
		<th>当前金币</th>
		<th>登录时间</th>
		<th>当前在线时长</th> 
		<th>在线总时长</th>
		<th>登陆IP</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($object as $var):?>
        <tr>  
         <td style="text-align: center;"><?php  echo $var['id']; ?></td>
         <td style="text-align: center;"><?php  echo $var['account']; ?></td>
         <td style="text-align: center;"><?php  echo $var['name']; ?></td>
         <td style="text-align: center;"><?php  echo $var['level']; ?></td>
         <td style="text-align: center;"><?php  echo $var['paynumber']; ?></td>
         <td style="text-align: center;"><?php  echo $var['diamond']; ?></td>
         <td style="text-align: center;"><?php  echo $var['gold']; ?></td>
         <td style="text-align: center;"><?php  echo $var['loginTime']; ?></td>
         <td style="text-align: center;"><?php  echo $var['onlinetime']; ?></td>
         <td style="text-align: center;"><?php  echo $var['loginIp']; ?></td>
       	 <td style="text-align: center;"><?php  echo $var['totalTime']; ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>					
<?php endif;?>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
