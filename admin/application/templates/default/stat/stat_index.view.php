<?php
$insert_html =Page_Lib::loadJs("statdata");
$insert_html.= Page_Lib::loadJs('highcharts');
$insert_html.= Page_Lib::loadJs('exporting');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html,'',1);
?>
	<!-- 站内导航 -->
<div id="content-header">
      <h1>日常数据统计</h1>    	       
</div>
<div id="breadcrumb">
        <a href="/index/index" title="Go to Home" class="tip-bottom">
        <i class="icon-home"></i> Home</a>
        <a href="#" class="current">Dashboard</a>
</div>
<?php  
$g_json = new Gjson_Lib();

// 活跃 
if(isset($onlineOut))
{ 
	$indata = array();
	foreach ($onlineOut as $var){	 
		$datetime = strtotime($var['createtime']); 
		$onlineContTime = time();
		$number = (int)$var['cont']; 
		array_push($indata,array($datetime*1000,$number)); 
	}
	 $strjson =  $g_json->encode($indata); 
}  
// 注册
if(isset($loginOut))
{
	$indata = array();
	foreach ($loginOut as $var){
		 
		$datetime = strtotime($var['createtime']);
		 
		$onlineContTime = time();
		$number = (int)$var['cont'];
		array_push($indata,array($datetime*1000,$number));
	}
	$strjson2 =  $g_json->encode($indata);
}
// 实时在线
if (isset($realtimeDauOut)){
	
	$indata = array();
	foreach ($realtimeDauOut as $var){
			
		$datetime = strtotime($var['createtime']);
		 
		$onlineContTime = time();
		$number = (int)$var['online_nums'];
		array_push($indata,array($datetime*1000,$number));
	}
	$strjson3 =  $g_json->encode($indata);
}
// 充值
if (isset($realtimeDauOut)){

	$indata = array();
	foreach ($realtimeDauOut as $var){
			
		$datetime = strtotime($var['createtime']);
			
		$onlineContTime = time();
		$number = (int)$var['online_nums'];
		array_push($indata,array($datetime*1000,$number));
	}
	$strjson3 =  $g_json->encode($indata);
}
?>

<?php  
//line, spline, area, areaspline, column, bar, pie , scatter0
$colors ="#578bbb','#c0504d','#578bbb','#0410fd','#0410fd','#0410fd','#0410fd','#0410fd','#0410fd";
echo Statdata_Lib::ChartGallery($strjson,"每日活跃",'每日注册','spline',false,1,$colors,$strjson2,'container');
//echo Statdata_Lib::ChartGallery($strjson3,"当前每5分钟在线人数",'','area',true,1,$colors,null,'container1');
//echo Statdata_Lib::ChartGallery($strjson,"渠道分布",'','spline',false,1,$colors,$strjson2,'container2');
?>	
<script>
function add0(m){return m<10?'0'+m:m }
function format(shijianchuo,timeFormat=0)
{
	//shijianchuo是整数，否则要parseInt转换
	var time = new Date(shijianchuo);
	var y = time.getFullYear();
	var m = time.getMonth()+1;
	var d = time.getDate();
	var h = time.getHours();
	var mm = time.getMinutes();
	var s = time.getSeconds();
	 
	if(timeFormat>0)
	{
		return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
	}
	else
	{
		return y+'-'+add0(m)+'-'+add0(d);
	}
}
</script>
<div class="container-fluid">
<?php echo Statdata_Lib::stat_chart_daily();?>
<div class="row-fluid">
<div class="span12 center" style="text-align: center;">				
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-signal"></i>
				</span> 
				<ul class="nav nav-tabs">
				<li class='active'><a data-toggle="tab" href="#container">图表</a></li>
				<li><a data-toggle="tab" href="#tab2">区服详情列表</a></li>
				<li><a data-toggle="tab" href="#tab3">平台详情列表</a></li>  
						
				</ul>
			</div>
			<div class="widget-content tab-content"> 
			
				 <div id="container" class='tab-pane active' style="height: 400px;">
				 
				 </div> 
				 <div id="tab2" class="tab-pane">
				 <?php if ($detailsOut):?>
				 <div class="buttons  " style="float:right;margin-bottom:10px">
					<input  type="button"  style="padding:0;width:120px" 
					class="btn btn-success" value="导出区服详情" id="ExportfileBtn"/>
				</div>
				<br>
				 <table id="tableExcel" class="table table-bordered table-striped">
				 	<thead>
				 	<th>日期</th>
				 	<th>注册人数</th>
				 	<th>活跃人数</th>
				 	<th>付费人数</th>
				 	<th>付费金额</th>
				 	<th>付费次数</th>	
				 	<th>新增付费人数</th>
				 	<th>新增付费金额</th>				 	
				 	<th>新增付费次数</th>			 	 
				 	</thead>
				 	<tbody>
				 	<?php foreach ($detailsOut as $key=>$var):?>
				 	<tr>
				 	<td><?php echo $key;?></td>
				 	<td><?php echo $var['logingCont'];?></td>
				 	<td><?php echo $var['onlineCount'];?></td>
				 	<td><?php echo $var['payerCont'];?></td>
				 	<td><?php echo $var['feeCont'];?></td>
				 	<td><?php echo $var['frequency'];?></td>
				 	<td><?php echo $var['newPayRole'];?></td>
				 	<td><?php echo $var['newPayMoney'];?></td>
				 	<td><?php echo $var['newPayFrequency'];?></td>
				 	</tr>
				 	<?php $datainfo.= 
				 	$key.'='.$var['logingCont'].'='.$var['onlineCount'].
				 	'='.$var['payerCont'].'='.$var['feeCont'].'='.$var['frequency'].
				 	'='.$var['newPayRole'].'='.$var['newPayMoney'].'='.$var['newPayFrequency'].',';?>
				 	<?php endforeach;?>
				 	</tbody>
				 </table>
				 <?php endif;?>
				 </div>
				<div id="tab3" class="tab-pane ">
				<?php $datainfo2 = NULL;?>
				 <?php if ($dailydata):?>
				  <div class="buttons" style="float:right;margin-bottom:10px">
					<input  type="button"  style="padding:0;width:120px" 
					class="btn btn-success" value="导出平台详情" id="PlatExportfileBtn"/>
				</div>	
				<br>
				 <table id="tableExcel" class="table table-bordered table-striped">
				 	<thead>
				 	
				 	<th>日期</th>
				 	<th>区服</th>				 	
				 	<th>注册人数</th>
				 	<th>活跃人数</th>
				 	<th>付费人数</th>
				 	<th>付费金额</th>
				 	<th>付费次数</th>	
				 	<th>新增付费人数</th>
				 	<th>新增付费金额</th>				 	
				 	<th>新增付费次数</th>			 	 
				 	</thead>
				 	<tbody>
				 	<?php foreach ($dailydata as $key=>$var):?>
				 	<tr>
				 	<td><?php echo $var['createtime'];?></td>
				 	<td><?php echo $var['serverId'];?></td>
				 	<td><?php echo $var['logingNum'];?></td>
				 	<td><?php echo $var['dau'];?></td>
				 	<td><?php echo $var['payRoleNum'];?></td>
				 	<td><?php echo $var['payRoleNum'];?></td>
				 	<td><?php echo $var['payFrequency'];?></td>
				 	<td><?php echo $var['newPayRoleNum'];?></td>
				 	<td><?php echo $var['newPayMonery'];?></td>
				 	<td><?php echo $var['newPayFrequency'];?></td>
				 	</tr>
				 	<?php $datainfo2.= 
				 	$var['createtime'].'='.$var['serverId'].'='.$var['logingNum'].
				 	'='.$var['dau'].'='.$var['payRoleNum'].'='.$var['monery'].
				 	'='.$var['payFrequency'].'='.$var['newPayRoleNum'].'='.$var['newPayMonery'].'='.$var['newPayFrequency'].',';?>
				 	<?php endforeach;?>
				 	</tbody>
				 </table>
				 <?php endif;?>
				 </div>
			</div>
		</div>
	</div>
</div>
<!--  <div class="row-fluid"> 
		<div class="span6">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-signal"></i>
				</span>
				<h5>Line chart</h5>
			</div>
			<div class="widget-content">
				 <div id="container1" style="height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
		<div class="span6">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-signal"></i>
				</span>
				<h5>Line chart</h5>
			</div>
			<div class="widget-content">
				 <div id="container2" style="height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
</div>-->
</div>
<?php $key = "日期"."\t"."注册人数"."\t"."活跃人数"."\t"."付费人数".
"\t"."付费金额"."\t"."付费次数"."\t"."新增付费人数"."\t"."新增付费金额"."\t"."新增付费次数";?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="sid"  value="<?php echo $serverId;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form> 
<?php $key2 = "日期"."\t"."区服"."\t"."注册人数"."\t"."活跃人数"."\t"."付费人数".
"\t"."付费金额"."\t"."付费次数"."\t"."新增付费人数"."\t"."新增付费金额"."\t"."新增付费次数";?>
<form  action="ExportfileIndex" method="POST" id ="from2">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key2;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo2;?>"/>	                    
</form> 
 <?php echo Page_Lib::footer('',1);?>
