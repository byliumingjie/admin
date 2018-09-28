<?php
$insert_html = Page_Lib::loadJs('managerlog');
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
        <h1>后台账号登录具体信息</h1>
 <!-- 页面按钮集合 -->

</div>

<div id="breadcrumb">
        <a href="/index/index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        <a href="#" class="current">后台账号登录日志查询</a>
</div>
<!-- 站内导航 结束 -->
<div class="container-fluid">	
<!-- 查询组件 begin-->
<div class="widget-box">
<div class="widget-title">
    <span class="icon">
    <i class="icon-search"></i>
    </span>
    <h5>查询条件</h5>
</div> 
    <div class="widget-content">			
       <form  method="POST"  action="index" class="form-horizontal" >         
          <div class="control-group">
			<div class="controls">                           
                <input type="text" class="datetimepicker form-control" placeholder="创建日期"  
                name="createTime" style="width:auto;"/>
                <span class="add-on"><i class="icon-th "></i></span>
                <input type="text" class="datetimepicker form-control" placeholder="结束时间"  
                name="endtime" style="width:auto;"/>                
                <input  name="account" placeholder="管理员" class="form-control" style="width:auto;">
                
                <button class="btn btn-primary btn-xs ladda-button" data-style="expand-right" name="sut" type="submit">
				<span class="ladda-label"> 查询</span>	 
				</button>
             </div>
          </div>                
       </form>       
    </div>
</div>
</div>
<!-- 查询组件 end--> 
<?php if(is_array($Logdata) && !empty($Logdata)):?>
<div class="widget-content nopadding">
<table class="table table-striped table-bordered table-hover">
<thead>
    <tr> 
        <th style="text-align: center;" >账号</th>
        <th style="text-align: center;">操作创建日期</td>
        <th style="text-align: center;">上次最后一次操作日期</th>
        <th style="text-align: center;">登陆IP</th> 
    </tr>
</thead>
<?php foreach ($Logdata as $log):?>
    <tr>
        <td style=" text-align: center;"><?php echo $log['operator'];?></td>
        <td style=" text-align: center;"><?php echo $log['createTime'];?></td>
        <td style=" text-align: center;"><?php echo $log['lastUpTime'];?></td>
        <td style=" text-align: center;"><?php echo $log['ip'];?></td>
    </tr>
<?php endforeach;?>
</table>
</div>
<?php endif;?>

<!-- 分页组件 begin -->
 <div class="row center" style="text-align: center;">	
	<?php echo htmlspecialchars_decode($pagehtml);?>
</div>
<!-- 分页组件 end -->
<?php echo Page_Lib::footer('',1); ?>
