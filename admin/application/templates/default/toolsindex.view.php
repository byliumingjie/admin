<?php
echo Page_Lib::head();
?>
	<!-- 站内导航 -->
 <div id="content-header">
            <h1>游戏管理目录</h1>    	       
    </div>
<div id="breadcrumb">
        <a href="/index/index" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        <a href="#" class="current">游戏管理目录</a>
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
                <strong>欢迎进入 Unicorn Games  !</strong>
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
    <!-- <li><i>
            <a href="/index/index" title="进入" class="tip-bottom"><i class=" icon-search"></i>区服列表</a>
        </i></li>
        <li><i>
           <a href="/gameuser/show" title="进入" class="tip-bottom"><i class="icon-search"></i>白名单</a> 
            </i>
        </li> -->
        <!-- <li><i>
           <a href="/notice/index" title="进入" class="tip-bottom"><i class="icon-search"></i>公告发布</a> 
            </i>
        </li>
        <li><i>
            <a href="/lottery/index" title="进入" class="tip-bottom"><i class="icon-search"></i>创建活动</a>
            </i>
        </li>  --> 
         <li>
         <i>
        <!-- //index  showmail-->
            <a href="/System/index" title="进入" class="tip-bottom"><i class="icon-search"></i>平台版本管理</a>
          </i>
        <li>
        	<i>
        	<!-- //index  showmail-->
            <a href="/Mail/showmail" title="进入" class="tip-bottom"><i class="icon-search"></i>邮件创建</a>
            </i>
    	</li>
    	<li>
        	<i>
        	<!-- //index  showmail-->
            <a href="/Notice/passNotice" title="进入" class="tip-bottom"><i class="icon-search"></i>走马灯</a>
            </i>
    	</li>
        <li>
        	<i>
        	<!-- //index  showmail-->
            <a href="/loginnotice/passLoginNotice" title="进入" class="tip-bottom"><i class="icon-search"></i>登录公告</a>
            </i>
    	</li>         
  </ul>          
</div>
</div>
    </div>
</div>
    </div>
</div>
 <?php echo Page_Lib::footer();?>
