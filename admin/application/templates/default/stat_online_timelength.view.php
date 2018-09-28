<?php
$insert_html =Page_Lib::loadJs('recharge');
$insert_html.=Page_Lib::loadJs('chosen.jquery');
$insert_html.=Page_Lib::loadCss('chosen');
$insert_html.=Page_Lib::loadJs('statdata');
echo Page_Lib::head($insert_html,'',1);
?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>在线时长</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>

<div class="widget-content">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statTimeLengthHtml();?>
 
<!-- 查询组件 end-->
 
 <div class="widget-title">
	<span class="icon">
		<i class="icon-eye-open"></i>
	</span>
	<h5>Browsers</h5>
</div>
<table id="tableExcel" class="table table-striped table-bordered table-hover" >
<thead >
    <tr>
	    <th>时间区间</th>
		<th>人数</th>
		<th>占比</th>
    </tr>
    </thead>
    <tbody>    	 
        <tr>
			<td style="width: 20%;text-align: center;">5-10分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr> 
		<tr>
			<td style="width: 20%;text-align: center;">10-30分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
		<tr>		
			<td style="width: 20%;text-align: center;">30-60分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
		<tr>
			<td style="width: 20%;text-align: center;">60-120分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
		<tr>
			<td style="width: 20%;text-align: center;">120-240分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
		<tr>
			<td style="width: 20%;text-align: center;">240-480分钟</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
		<tr>
			<td style="width: 20%;text-align: center;">480分钟以上</td>
			<td  style="text-align: center;">50</td>
			<td  style="text-align: center;">5%</td>
		</tr>
    </tbody>
</table>					
 
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
