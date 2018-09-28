<?php
$insert_html =Page_Lib::loadJs('recharge');
$insert_html.=Page_Lib::loadJs('chosen.jquery');
$insert_html.=Page_Lib::loadCss('chosen');
$insert_html.=Page_Lib::loadJs('statdata');
echo Page_Lib::head($insert_html,'',1);
?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>综合数据</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>

<div class="widget-content">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statComplexHtml();?>
 
<!-- 查询组件 end-->
<?php if (!empty($object) && !empty($total)):?>

<table id="tableExcel" class="table table-striped table-bordered table-hover" >
<thead >
    <tr>
	    <th>区服</th>
		<th>日期</th>
		<th>新增玩家数</th>
		<th>登陆玩家数</th>
		<th>老用户数</th>
		<th>PCU</th>
		<th>充值用户数</th>
		<th>Arpu</th>
		<th>Arppu</th>
		<th>付费率</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($object as $var):?>
        <tr>  
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>
        <td style="text-align: center;"><?php echo $var['']?> </td>		 
        </tr>
        <?php endforeach;?>
    </tbody>
</table>					
<?php endif;?>
</div>
<script type="text/javascript">
	$(".chzn-select").chosen();		
</script>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
