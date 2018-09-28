<?php 
//$insert_html = Page_Lib::loadJs('multselect');

$insert_html = Page_Lib::loadJs('common');
echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>公告发布</h1> 
        <div class="btn-group"> 
               
        </div>
</div>
 
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">公告发布</a>
    <!-- <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    	<?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     	<i class="icon-question"></i></a>-->
 </div>
<div class="widget-content">
<?php echo   DevToolbox_Lib::noticeHtml();?>
</div>
 
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer('',true);?>
<!-- 版权info END -->

