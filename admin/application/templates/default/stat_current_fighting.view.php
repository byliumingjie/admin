<?php
$insert_html =Page_Lib::loadJs("statdata");
/* $insert_html .=Page_Lib::loadJs("unicorn.dashboard");	
$insert_html .=Page_Lib::loadJs("jquery.peity.min"); */
	
echo Page_Lib::head($insert_html,'',1);
?> 
 
<!-- 站内导航 -->
<div id="content-header">
<h1>当前战斗查询</h1>
</div>
<div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
</div>
<div class="container-fluid">
<!-- 查询组件 begin-->
<?php echo Statdata_Lib::statFightingHtml();?>
<?php 
	if (isset($request) && empty($object))
	{	 
		echo DevToolbox_Lib::show(2, '数据为空', '所请求的数据查找为空或并不存在');
	}
?> 
<!-- <span class="caret"></span>
<span class="caret"></span>

<button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
<span class="caret btn-group dropup""></span> 

<div class="btn-group dropup"> 
                                
  <span class="caret"></span> 
 </div> -->
<!-- 查询组件 end-->
<?php if (!empty($object) && !empty($total)):?>
 
 <div class="widget-title">
	<span class="icon">
		<i class="icon-eye-open"></i>
	</span>
	<h5>list</h5>
</div>
<table id="tableExcel" class="table table-striped table-bordered table-hover" >
<thead >
    <tr>
	    <th>战斗ID</th>
		<th>游戏开始时间，精确到秒</th>
		<th>游戏模式</th>
		<th>玩家1名称</th>
		<th>玩家1匹配分数</th>
		<th>玩家名字2</th>
		<th>玩家2匹配分数</th>
		<th>玩家名字3</th>
		<th>玩家3匹配分数</th>
		<th>所有玩家的名字和匹配分数</th>
    </tr>
    </thead>
    <tbody>
    	<?php foreach ($object as $var):?>
        <tr>  
        <td style="text-align: center;"><?php  echo $var['id']; ?></td>
		<td style="text-align: center;"><?php  echo $var['startTime']; ?></td>
		<td style="text-align: center;"><?php  echo $var['mode']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userName1']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userNum1']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userName2']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userNum2']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userName3']; ?></td>
		<td style="text-align: center;"><?php  echo $var['userNum3']; ?></td>
		<td style="text-align: center;"><?php  echo $var['allname']; ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>					
<?php endif;?>
</div>
<!-- 分页组件 begin -->
<div class="row center" style="text-align: center;">	
<?php  echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1);?>
