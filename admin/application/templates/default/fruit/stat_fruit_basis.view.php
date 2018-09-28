<?php
$insert_html =Page_Lib::loadJs("statdata");
/* $insert_html .=Page_Lib::loadJs("unicorn.dashboard");	
$insert_html .=Page_Lib::loadJs("jquery.peity.min"); */
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>基础统计</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid" id="tableExcel">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statDailyReportHtml();?>
<div style="overflow:scroll;">
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead  >
     <tr>
		<th>日期</th>
		<th>打开人数</th>
		<th>游戏人数</th>
		<th>局数</th>
		<th>投币</th>
		<th>得分</th>
		<th>回收</th>
		<th>回收率</th>
	</tr>              
   </thead>
    <tbody>
    <?php for ($i=0;$i<=20;$i++):?>
    	<tr>
    	 <td>2016-09-20 10:00</td>
    	 <td>505</td>
    	 <td>200%</td>
    	 <td>100%</td>
    	 <td>2321</td>
    	 <td>557</td>
    	 <td>778</td>
    	 <td>&nbsp</td>
    	 
     	</tr>
    	<?php endfor;?>
    </tbody>
</table>
</div>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
