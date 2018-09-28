<?php 
$insert_html=Page_Lib::loadCss("map");
$insert_html.=Page_Lib::loadJs("map",'','map');
$insert_html.= Page_Lib::loadJs('log','','tool');
$insert_html.= Page_Lib::loadJs('statdata');

echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>日志记录</h1> 
        <div class="btn-group">                
        </div>
</div>
 
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">日志记录</a>   
 </div>
 
<div class="widget-content"></div>
<div class="container-fluid">	
	<div class="row-fluid">
<!-- 条件选项菜单 begin-->
<?php echo DevToolbox_Lib::LOGHtml();?>
<?php 
  /*  $dataa = array(
        		"name"=>"james",
        		"server"=>1,
        		"userid"=>221,
        		'Result'=>array('level'=>111,'vip'=>222),
        );
   echo json_encode($dataa); */
?>
<?php  if(!empty($Logdata)): ?>
<div style="overflow:scroll;">
<!-- 视图列表信息 begin table table-bordered table-striped-->
<table id="tableExcel" class="table table-striped table-bordered table-hover" >
<thead>
    <tr>
    	<th>ID</th>     	 
        <th>渠道</th>
        <th>功能</th> 
        <th>区服</th>
        <th>操作账号</th>
        <th>请求数据</th>
        <th>响应结果</th>
        <th>生成日期</th>
        <th>请求ip</th>
    </tr> 
</thead>
<tbody>	
	<?php if(is_array($Logdata) && count($Logdata)>0):?>
	<?php $cfg = Config::get("key.gm"); ?>
	<?php $errorCfg = Config::get("key.gm.error"); ?>
	 
	<?php foreach ($Logdata as $var):?>
	 
	<tr>
		<!-- ID -->
		<td style="text-align: center;"><?php echo $var['id'];?></td>
		<!-- 渠道来源 -->
		<td style="text-align: center;">
		<?php  
		switch ($var['source'])
		{ 
			case 3:echo '账号操作';break;
			default:echo '未知'; break;
		}
		?></td>		
		<td style="text-align: center;">
		<?php 
			echo  !empty($cfg[$var['protocolCode']])
			?
			$cfg[$var['protocolCode']]
			:
			"未知";
		?>
		</td>
		<!-- 功能 -->
		<td style="text-align: center;"><?php echo $var['sname'];?></td>
		<!-- 账号 -->
		<td style="text-align: center;"><?php echo $var['account'];?></td>
		<!-- 请求数据 -->
		<td style="text-align: center;" ><?php if(!empty($var['RequestData'])){
			echo '<button class="btn btn-link serverInfo">请求数据详情</button>';
		};?>
		</td>
		
		<td style="display: none" data-name="originalData"><?php echo $var['RequestData'];?></td>
		<!-- 请求数据拆解展示 json -->
		<?php
			$jsonSTR = '';
			$testJSON =array();
			$jsonSTR  = json_decode($var['RequestData'],true);
			 
			foreach ($jsonSTR as $key=>$vara)
			{			
				 
				switch ($key){
					case 'code':
						$vara = $cfg[$vara];
						$key=urlencode('协议功能'); 
					break;
					case 'operator':$key=urlencode('操作者');break;
					case 'source':$key=urlencode('操作渠道');
					if ($vara==3){
						$vara ='米喏斯后台工具';
					}else $vara ='未知';					
					break;
					case 'server':$key=urlencode("区服");	break;
					case 'ServerId':$key=urlencode("区服ID");	break;
					default:break;
				}
				if (is_array($vara)){
				 
					foreach ($vara as $key2 =>$Inva){
						 
						$json2[$key2] = ($Inva);
					}
					$vara = json_encode ( $json2,JSON_PRETTY_PRINT ); 
				}
				$testJSON[$key] = urlencode ( $vara );
			}
			#echo urldecode ( json_encode ( $testJSON ) )."<br>";
		?>
		<td style="display: none" data-name="RequestData"><?php 
		echo  urldecode ( json_encode( $testJSON ,JSON_PRETTY_PRINT) );?></td>
		 
		<td style="text-align: center;">
		<?php	
			$ponseSTR = '';
			$ponseJSON =array();
			
			$ExecutionState =  $var['ExecutionState'];
			if (isset($errorCfg[$ExecutionState])){
				if ($ExecutionState==0){
					echo $errorCfg[$ExecutionState];
				}else {
					echo "<button class='btn btn-link ponsefailureInfo'>" .
					$errorCfg[$ExecutionState] . "</button>";
					$ponseSTR = '';
					$ponseJSON =array();
					
					$ponseSTR  = json_decode($var['ResponseData'],true);
					
					foreach ($ponseSTR as $key=>$inponse)
					{
						$ponseJSON[$key] = urlencode ( $inponse );
					}
				}
			}
			else 
			{ 	$ponseSTR = '';
				$ponseJSON =array();
					
				$ponseSTR  = json_decode($var['ResponseData'],true);
					
				foreach ($ponseSTR as $key=>$inponse)
				{
					$ponseJSON[$key] = urlencode ( $inponse );
				} 
				echo  !empty($ExecutionState)?$ExecutionState:"<button class='btn btn-link ponsefailureInfo'>空</button>";
			}			 
		?></td>		
		<td style="display: none" data-name="ResponseData"><?php echo urldecode (json_encode ( $ponseJSON ,JSON_PRETTY_PRINT))?></td>
		<td style="text-align: center;"><?php echo date("Y-m-d H:i:s",$var['create_time']);?></td>
		<td style="text-align: center;"><?php 
		$ipDir = array();
		$pointXy = '';
		$locaIp = gethostbyname($_ENV['COMPUTERNAME']);
		$locaIp = isset($locaIp)?$locaIp:'127.0.0.1';
		$ipOut = array('127.0.0.1',"$locaIp","::1");
		if(in_array($var['RequestIp'], $ipOut) )
		{
			echo  "本机"	;
		}else{
			echo "<button class='btn btn-link Ippoint'>".$var['RequestIp']."</button>";
 			$content = file_get_contents("http://api.map.baidu.com/location/ip?ak=1hQ9Oqlkbhy5ZvXnpCF78MzmwnRA4Vh7&ip={$var['RequestIp']}&coor=bd09ll");
 			$ipDir = json_decode($content,true);
			$pointXy = $ipDir['content']['point']['x'].','.$ipDir['content']['point']['y'];
		}?></td>  
		<td style="display: none" data-name='pointxy'><?php echo $pointXy;?></td>  
	</tr>	 
	<?php endforeach;?>
	<?php endif;?>
</tbody>
</table>
</div>	
<?php endif;?>
<!-- 视图列表信息  end--> 
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
</div>
</div>
<!-- log info  -->
<div class="modal fade" id="loginfoModal" tabindex="-1" role="dialog" aria-labelledby="logInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">日志详细</h4>
              <!-- <div class="widget-title"> 
              <div class="buttons"><a href="#" class="btn btn-mini">
              <i class="icon-random" id="originalSwap"></i> 原始数据</a></div></div> -->
            </div>
            <form action="#" id='logInfoForm'>
	            <div class="modal-body">               
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">                        
                        	<textarea   cols="3" name="RequestData" class="form-control"style="margin: 0px 0px 10px; width: 512px; height: 100px;"></textarea>
                         </div>
                    </div>                                             
	            </div>
            </form>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
 <!-- point view  -->
<div class="modal fade" id="pointModal" tabindex="-1" role="dialog" aria-labelledby="logInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="serverInfoModalLabel">地理位置</h4>
            </div>
            <form action="#" id='logInfoForm'>
	            <div class="modal-body">               
                    <div class="control-group" style="width:520px;height:340px;border:1px solid gray" id="map_canvas">
                         
                    </div>                                             
	            </div>
            </form>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel">取消</button>
            </div>
        </div>
  </div>
</div>
 

<!-- 版权信息begin -->
<?php echo Page_Lib::footer();?>
<!-- 版权信息 end -->
<?php 
$cssHost = Page_Lib::getCssHost();
$JsHost = Page_Lib::getJsHost();
echo "
<link rel='stylesheet' href='".$cssHost."/map.css'/>
<script src='".$JsHost."/map/bnap.js'></script>
";
?>
<script type="text/javascript">
/* 
var map = new BMap.Map("container");//初始化地图                    
//var optsd = {type: BMAP_NAVIGATION_CONTROL_SMALL}   
map.addControl(new BMap.NavigationControl());  //初始化地图控件              
map.addControl(new BMap.ScaleControl());                   
map.addControl(new BMap.OverviewMapControl());  
map.addControl(new BMap.MapTypeControl()); 
 


var point=new BMap.Point(121.48789949,31.24916171);
map.centerAndZoom(point, 15);//初始化地图中心点

var marker = new BMap.Marker(point); //初始化地图标记



marker.enableDragging(); //标记开启拖拽

var gc = new BMap.Geocoder();//地址解析类


//添加标记拖拽监听
marker.addEventListener("dragend", function(e){

    //获取地址信息
    gc.getLocation(e.point, function(rs){
	
        showLocationInfo(e.point, rs);
    });
});

//添加标记点击监听
marker.addEventListener("click", function(e){
   gc.getLocation(e.point, function(rs){ 
	
        showLocationInfo(e.point, rs);
    });
});
 
map.centerAndZoom(point, 15); //设置中心点坐标和地图级别
map.addOverlay(marker); //将标记添加到地图中
 


//显示地址信息窗口
function showLocationInfo(pt, rs){
    var opts = {
      width : 250,     //信息窗口宽度
      height: 100,     //信息窗口高度
      title : ""  //信息窗口标题
    }
    
    var addComp = rs.addressComponents;
    var addr = "当前位置：" + addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber + "<br/>";
    addr += "纬度: " + pt.lat + ", " + "经度：" + pt.lng;
    //alert(addr);
    
    var infoWindow = new BMap.InfoWindow(addr, opts);  //创建信息窗口对象
    marker.openInfoWindow(infoWindow);
}
 */
</script>
