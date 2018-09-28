<?php
date_default_timezone_set("PRC");
$insert_html =Page_Lib::loadJs("statdata");
$insert_html.= Page_Lib::loadJs('highcharts');
$insert_html.= Page_Lib::loadJs('exporting');
$insert_html.= Page_Lib::loadJs('real.time');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html,'',1);
?>
<style>
.stat-boxes .right {
    font-size: 12px;
    padding: 8px 12px 12px 0;
    text-align: center;
    width: 100%;
    color: #666666;
} 
</style>
<!-- 站内导航 -->
<div id="content-header" >
<h1>实时在线数据&nbsp;&nbsp;<span id="time" style="margin:0px;padding:0px"></span></h1>
<h1><span id="time2"></span></h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<?php 

if ($realtimeinfo)
{    
	//var_dump($realtimeinfo);
	// 当前新增玩家
	$newRow = (int)$realtimeinfo['current_online']['cont'];
	$newRow = (isset($newRow) && $newRow>0)?$newRow:0;
	// 当前总活跃人数
	$dau = $realtimeinfo['current_login']['RoleNum'];	
	$dau = (isset($dau) && $dau>0)?$dau:0;	
	// 当前登录次数
	$loginFrequency = $realtimeinfo['current_login']['loginNum'];
	$loginFrequency = (isset($loginFrequency) && $loginFrequency>0)?$loginFrequency:0;
	// 平均登录时长
	$AvglogingTime = $realtimeinfo['current_login']['AvglogingTime'];
	//ceil($AvglogingTime/60).'分'
	$AvglogingTime = (isset($AvglogingTime) && $AvglogingTime>0) 
	? ceil($AvglogingTime/60).'分':0;
	// 当前老玩家
	$onlinedays = (int)$realtimeinfo['current_online']['onlinedays'];
	$onlinedays = (isset($onlinedays) && $onlinedays>0)?$onlinedays:0;	
	// 当前付费玩家
	$payUserNum = $realtimeinfo['current_pay']['rolenum'];
	$payUserNum = (isset($payUserNum) && $payUserNum>0)?$payUserNum:0;
	// 当前付费次数
	$payfrequency = $realtimeinfo['current_pay']['frequency'];
	$payfrequency = (isset($payfrequency) && $payfrequency>0)?$payfrequency:0;
	// 当前付费金额
	$payfee = (int)$realtimeinfo['current_pay']['fee'];	
	$payfee = (isset($payfee) && $payfee>0) ? ($payfee/100) : 0;
	// 当前累计金额
	$total_monery =  (int)$realtimeinfo['total_monery'];
	$total_monery = (isset($total_monery) && $total_monery>0)?($total_monery/100) : 0;
	// 新增付费人数	
	$newpayRole = $realtimeinfo['newpay']['newPayRoleNum'];
	$newpayRole = (isset($newpayRole) && $newpayRole>0) ? $newpayRole : 0;
	// 新增付费次数
	$newpayFreqy = $realtimeinfo['newpay']['newPayFrequency'];	
	$newpayFreqy = (isset($newpayFreqy) && $newpayFreqy>0) ? $newpayFreqy : 0;
	// 新增付费金额
	$newpaymoney = $realtimeinfo['newpay']['newPaymoney'];
	$newpaymoney = (isset($newpaymoney) && $newpaymoney>0) ? $newpaymoney : 0;
	
	$g_json = new Gjson_Lib();	
	// 实时在线 
	if(isset($realtimeinfo['real_time_online']))
	{ 
		//var_dump($realtimeinfo['real_time_online']);
		$inRealtimeOut = [];
		$inRealtimeBeforeOut = [];
		foreach ($realtimeinfo['real_time_online'] as $var)
		{	
			// 今日
			$datetime = strtotime($var['createtime']);
			$number = (int)$var['online_nums'];
			
			if (date('Y-m-d',$datetime) == $realtimeinfo['current_time'])
			{    
				array_push($inRealtimeOut,["name"=>date('H:i:00',$datetime),"y"=>$number]);
			}
			// 昨日
			if (date('Y-m-d',$datetime) == $realtimeinfo['Yesterday_time'])
			{ 
				array_push($inRealtimeBeforeOut,["name"=>date('H:i:00',$datetime),"y"=>$number]);
			}				
		}		 
		$realtimejson =  json_encode($inRealtimeOut);;
		$realtimeBeforejson = json_encode($inRealtimeBeforeOut);
	}  
	 
	// 新增玩家
	if(isset($realtimeinfo['total_online']))
	{
		//var_dump($realtimeinfo['real_time_online']);
		$inRealOnlineOut = [];
		$inRealOnlineBeforeOut = [];
		 
		foreach ($realtimeinfo['total_online'] as $var)
		{
			// 今日
			$datetime = strtotime($var['datetime']);
			 
			$number = (int)$var['cont'];				
			if (date('Y-m-d',$datetime) == $realtimeinfo['current_time'])
			{
				//array_push($inRealOnlineOut,[$datetime*1000,$number]);
				array_push($inRealOnlineOut,[date('H:i',$datetime),$number]);
			}
			// 昨日
			if (date('Y-m-d',$datetime) == $realtimeinfo['Yesterday_time'])
			{
				//array_push($inRealOnlineBeforeOut,[$datetime*1000,$number]);
				array_push($inRealOnlineBeforeOut,[date('H:i',$datetime),$number]);
			}
		}
		$realOnlinejson =  $g_json->encode($inRealOnlineOut);
		$realOnlineBeforejson = $g_json->encode($inRealOnlineBeforeOut);
	 
	}
	// 收入金额
	if(isset($realtimeinfo['total_pay']))
	{ 
		$inRealPayOut = [];
		$inRealPayBeforeOut = [];
			
		foreach ($realtimeinfo['total_pay'] as $var)
		{
			// 今日
			$datetime = strtotime($var['datetime']);
			 	
			$number = ((int)$var['fee']/100);
			
			if (date('Y-m-d',$datetime) == $realtimeinfo['current_time'])
			{
				array_push($inRealPayOut,[date('H:i',$datetime),$number]);
			}
			// 昨日
			if (date('Y-m-d',$datetime) == $realtimeinfo['Yesterday_time'])
			{
				array_push($inRealPayBeforeOut,[date('H:i',$datetime),$number]);
			}
		}
		$realPayjson =  $g_json->encode($inRealPayOut);
		$realPayBeforejson = $g_json->encode($inRealPayBeforeOut);
	}
	$colors ="#578bbb','#c0504d','#578bbb','#0410fd','#0410fd','#0410fd','#0410fd','#0410fd','#0410fd";
	echo Statdata_Lib::ChartGallery($realtimejson,"今日",'昨日','spline',true,3,$colors,$realtimeBeforejson,'realtime','实时在线');
	echo Statdata_Lib::ChartGallery($realOnlinejson,"今日",'昨日','spline',true,4,$colors,$realOnlineBeforejson,'newrole','新增玩家');
	echo Statdata_Lib::ChartGallery($realPayjson,"今日",'昨日','spline',true,4,$colors,$realPayBeforejson,'income','收入金额');
}
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
		if(timeFormat==1)
		{
			return add0(h)+':'+add0(mm)+':'+add0(s);
		}
		else{
		return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
		}
	}
	else
	{
		var t=new Date();  
	      with(t){  
	          var _time=(getHours()<10 ? "0" :"") + 
	          getHours() + ":" + (getMinutes()<10 ? "0" :"") 
	          + getMinutes() + ":" + (getSeconds()<10 ? "0" :"") 
	          + getSeconds() + " " ;  
	      }  
		return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
	}
}
function settime(datetime){
	
	 
	var time = new Date(datetime);
	var y = time.getFullYear();
	var m = time.getMonth()+1;
	var d = time.getDate();
	var h = time.getHours();
	var mm = time.getMinutes();
	var s = time.getSeconds();
	return add0(h)+':'+add0(mm)+':'+add0(s);
}
</script>
<span style="display: none" id="getserverId"><?php if ($realtimeinfo['serverId']){echo (int)$realtimeinfo['serverId'];}?></span>
<span style="display: none" id="getPlatId"><?php if ($realtimeinfo['PlatId']){echo (int)$realtimeinfo['PlatId'];}?></span>
<span style="display: none" id="getstartTime"><?php if ($realtimeinfo['current_time']){echo $realtimeinfo['current_time'];}?></span>
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statRealTimedataHtml();?>
<?php if ($realtimeinfo && count($realtimeinfo)>0):?>
<div class="container-fluid">
<div class="row-fluid">
	<div class="span11" style="text-align: center;margin:0px;padding:0px;">						
		<ul class="stat-boxes" >
			<li >				
				<div class="right" >
					<strong id="newregistered">
					<?php echo $newRow;?></strong>
					新增玩家
				</div>
			</li>
			<li>
				<div class="right">
					<strong id="dau">
					<?php echo $dau;?></strong>
					总活跃
				</div>
			</li>
			<li>				
				<div class="right">
					<strong id="onlinedaysId">
					<?php echo $onlinedays;?></strong>
					老玩家
				</div>
			</li>			
			<li>				
				<div class="right">
					<strong id="payUserNumId">
					<?php echo $payUserNum;?></strong>
					付费玩家
				</div>
			</li> 
		</ul>
		<ul class="stat-boxes" >
			<li>				
				<div class="right" style="width: 100%">
					<strong id="payfrequencyId">
					<?php echo $payfrequency;?></strong>
					付费次数
				</div>
			</li>
			<li>
				<div class="right">
					<strong id="total_moneryId">
					<?php echo $total_monery;?></strong>
					累积收入
				</div>
			</li>
			<li>				
				<div class="right">
					<strong id="loginFrequencyId">
					<?php echo $loginFrequency;?></strong>
					游戏次数
				</div>
			</li> 
			<li>				
				<div class="right">
					<strong id="avglogingTimeId">
					<?php echo $AvglogingTime;?></strong>
					平均游戏时长
				</div>
			</li> 
			<li>				
				<div class="right">
					<strong id="payfeeId">
					<?php echo $payfee;?></strong>
					今日收入
				</div>
			</li>
			<li>				
				<div class="right">
					<strong id="payfeeId">
					<?php echo $newpayRole;?></strong>
					新增付费人数
				</div>
			</li>
			<li>				
				<div class="right">
					<strong id="payfeeId">
					<?php echo $newpaymoney;?></strong>
					新增付费金额
				</div>
			</li>
			<li>				
				<div class="right">
					<strong id="payfeeId">
					<?php echo $newpayFreqy;?></strong>
					新增付费次数
				</div>
			</li>
		</ul>
	</div> 
</div>	
</div>

<div class="container-fluid">					
  <div class="row-fluid">
<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-signal"></i>
				</span> 
				<ul class="nav nav-tabs">
				<li class='active'><a data-toggle="tab" href="#realtime">实时在线</a></li>
				<li><a data-toggle="tab" href="#newrole">新增玩家</a></li> 
				<li><a data-toggle="tab" href="#income">收入金额</a></li>
				<?php if ($detailsOut || $detailsOut):?>
				<div class="buttons">
				<input  type="button"  style="padding:0;width:120px" 
				class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>
				</div>
				<?php endif;?>		
				</ul>
			</div>
			<div class="widget-content tab-content"> 
				 <div id="realtime" class='tab-pane active' style="width:99%">
				 
				 </div>
				 <div id="newrole" class='tab-pane' style="width: 85%">
				 
				 </div> 
				 <div id="income" class='tab-pane' style="width: 85%">
				 
				 </div>
			</div>
		</div>
</div>
</div>
<?php endif;?>
</div>
<?php $key ="区服"."\t".'人数'."\t".'当前日期'; ?>
<form  action="ExportfileIndex" method="POST" id ="from1">
	<input  type="hidden" name="platId" value="<?php echo $platId;?>"/>	
	<input  type="hidden" name="key"  value="<?php echo $key;?>"/>
	<input  type="hidden" name="data" value="<?php echo $datainfo;?>"/>	                    
</form>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
