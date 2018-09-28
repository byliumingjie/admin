<?php
$insert_html =Page_Lib::loadJs("statdata");
/* $insert_html .=Page_Lib::loadJs("unicorn.dashboard");	
$insert_html .=Page_Lib::loadJs("jquery.peity.min"); */
	
echo Page_Lib::head($insert_html,'',1);
?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>牛牛日报统计</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statDailyHtml();?>
 
<table id="tableExcel" class="table table-striped table-bordered table-hover"> 
   <thead>
     <tr>
		<th>统计时间</th>
		<th>注册人数</th>
		<th>loading页</th>
		<th>新增登录大厅</th>
		<th>新增操作人数</th>
		<th>新增进场人数</th>
		<th>新增游戏人数</th>
		<th>新增重复游戏</th>
		<th>新增退出人数</th>
		<th>进场人数（初级）</th>
		<th>进场人数（中级）</th>
		<th>进场人数（高级）</th>
		<th>游戏人数</th>
		<th>重复游戏</th>
		<th>退出人数</th> 
		<th>总游戏局数</th>                   
	</tr>              
   </thead>
    <tbody>
    	<tr>
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
    </tbody>
</table>					
 
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
