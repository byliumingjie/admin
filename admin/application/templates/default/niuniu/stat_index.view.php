<?php
$insert_html =Page_Lib::loadJs("statdata");
$insert_html.= Page_Lib::loadJs('highcharts');
$insert_html.= Page_Lib::loadJs('exporting');
echo Page_Lib::head($insert_html);
?><!-- 站内导航 -->
 <div id="content-header">
            <h1>Dashboard</h1>    	       
    </div>
<div id="breadcrumb">
        <a href="/index/index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        <a href="#" class="current">Dashboard</a>
</div>
<?php  
$g_json = new Gjson_Lib();
 if(isset($object))
{ 
	$indata = array();
	foreach ($object as $var){		
		$datetime = strtotime(substr($var['date'],0,4).'-'.
		substr($var['date'],4,2).'-'.substr($var['date'],6,2));		
		$onlineContTime = time();
		$number = (int)$var['cont'];
		 
		array_push($indata,array($datetime*1000,$number)); 
	}
	 $strjson =  $g_json->encode($indata); 
}  
?>
<?php  
$colors ="#219fc8', '#c0504d', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', 
'#FFF263', '#6AF9C4";
echo Statdata_Lib::ChartGallery($strjson,"每日注册",'','line','',1,$colors,$strjson2,'container');
echo Statdata_Lib::ChartGallery($strjson,"每日活跃",'','column','',1,$colors,$strjson2,'container1');
echo Statdata_Lib::ChartGallery($strjson,"渠道分布",'','pie','',1,$colors,$strjson2,'container2');
?>	
<div class="container-fluid">		
<div class="row-fluid">
<div class="widget-content">
<div class="row-fluid">
    <div class="span12">
        <div class="alert alert-info" style="text-align: center;">
                <strong>欢迎进入Unicorn Games !</strong>
                <a href="#" data-dismiss="alert" class="close">×</a>
        </div>
    </div>
<!--
<div class="widget-box">
	<div class="widget-title">
	<span class="icon">
		<i class="icon-th"></i>
		</span>
		<h5>数据统计游戏菜单</h5>
	</div>
	<div class="widget-content">			
        <ul class="quick-actions">
        <li><i>
           <a href="fruitAction" title="进入" class="tip-bottom"><i class="icon-search"></i>日常注册活跃图表</a> 
            </i>
        </li>
        <li><i>
            <a href="/account/index" title="进入" class="tip-bottom"><i class="icon-search"></i>充值图表</a>
            </i>
        </li> 
        <li><i>
            <a href="/roleban/index" title="进入" class="tip-bottom"><i class="icon-search"></i>在线用户</a>
            </i>
        </li>  
        <li><i>
            <a href="/roleban/index" title="进入" class="tip-bottom"><i class="icon-search"></i>用户场次</a>
            </i>
        </li>    
  </ul>          
</div>
</div>
  -->
<div class="row-fluid">
    <div class="span12 center" style="text-align: center;">				
    <div class="widget-box">
</div>
<div class="row-fluid"> 
	<div class="span12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-signal"></i>
				</span>
				<h5>Line chart</h5>
			</div>
			<div class="widget-content">
				 <div id="container" style="height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid"> 
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
</div>
    </div>
</div>	
    </div>
</div>
    </div>
 </div>  
 <?php echo Page_Lib::footer();?>
