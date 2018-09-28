<?php
$insert_html =Page_Lib::loadJs("statdata");
$insert_html.= Page_Lib::loadJs('highcharts');
$insert_html.= Page_Lib::loadJs('exporting');
 
echo Page_Lib::head($insert_html,'',1);
?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>日常在线用户图表</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>

<div class="container-fluid">

 
<!--  -->

<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statfruitRetainedHtml();
//line, spline, area, areaspline, column, bar, pie , scatter0
?> 
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead>
     <tr> 
		<th>日期</th>
		<th>次日留存</th>
		<th>次日留存率</th>
		<th>七日留存</th>
		<th>七日留存率</th>                 
	</tr>              
   </thead>
    <tbody>
    	<tr>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>    	 
    	 </tr>
    	 <?php $dowloadOut .= $var['order_time']."=".$var['orderId'].',';?>     
    </tbody>
</table>					
</div>


<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
