<?php
echo Page_Lib::head();
?>
	<!-- 站内导航 -->
 <div id="content-header">
            <h1>工具箱</h1>    	       
    </div>
<div id="breadcrumb">
        <a href="/index/index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        <a href="#" class="current">工具箱</a>
</div>
<div class="container-fluid">
<div class="row-fluid">
    <div class="span12 center" style="text-align: center;">				
    <div class="widget-box">

</div>
    </div>
</div>						
<div class="row-fluid">
<div class="widget-content">
<div class="row-fluid">
    <div class="span12">
        <div class="alert alert-info" style="text-align: center;">
                <strong>欢迎进入Unicorn Games !</strong>
                <a href="#" data-dismiss="alert" class="close">×</a>
        </div>
    </div>
<div class="widget-box">
	<div class="widget-title">
	<span class="icon">
		<i class="icon-th"></i>
		</span>
		<h5>菜单</h5>
	</div>
	<div class="widget-content">			
        <ul class="quick-actions">
        <li>
        	<i>
           <a href="/account/index" title="进入" class="tip-bottom"><i class="icon-search"></i>玩家基本信息查询</a> 
            </i>
        </li>
        <li>
        	<i>
           <a href="/UserPhotosVerif/index" title="进入" class="tip-bottom"><i class="icon-search"></i>玩家头像审核</a> 
            </i>
        </li>
        <li>
        	<i>
           <a href="/roleban/index" title="进入" class="tip-bottom"><i class="icon-search"></i>角色封停</a> 
            </i>
        </li>
        <!-- <li><i>
            <a href="/account/index" title="进入" class="tip-bottom"><i class="icon-search"></i>玩家数据编辑</a>
            </i>
        </li>  
        <li><i>
            <a href="/roleban/index" title="进入" class="tip-bottom"><i class="icon-search"></i>玩家封停</a>
            </i>
        </li>  -->  
  </ul>          
</div>
</div>
    </div>
</div>
    </div>
    </div>  
 <?php echo Page_Lib::footer();?>
