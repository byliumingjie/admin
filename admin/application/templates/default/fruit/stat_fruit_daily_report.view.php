<?php
$insert_html =Page_Lib::loadJs("statdata");
/* $insert_html .=Page_Lib::loadJs("unicorn.dashboard");	
$insert_html .=Page_Lib::loadJs("jquery.peity.min"); */
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
<h1>水果机日报统计</h1>
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
		<th>新增用户</th>
		<th>用户次日留存率</th>
		<th>付费用户次日留存率</th>
		<th>总用户红包兑换人数</th>
		<th>新用户红包兑换人数</th>
		<th>新用户红包兑换金额</th>
		<th>红包总产出</th>
		<th>新用户充值</th>
		<th>总奖券产出</th>
		<th>总奖券人数</th>
		<th>新用户奖券产出</th>
		<th>新用户奖券人数</th>
		<th>金币产出</th>
		<th>金币回收</th> 
		<th>金币池总量</th>
		<th>发放金额</th>
	    <th>净收益</th>
	    <th>游戏局数（初级）</th>
	    <th>游戏人数（初级）</th>
	    <th>游戏局数（中级）</th>
	    <th>游戏人数（中级）</th>
	    <th>游戏局数（高级）</th>
	    <th>游戏人数（高级）</th>  
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
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
    	 <td>&nbsp</td>
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
