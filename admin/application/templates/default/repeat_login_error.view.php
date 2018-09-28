<?php
echo Page_Lib::head();
?>
<div id="content-header">
    <h1>登录异常</h1>
</div>
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">登录异常</a> 
 </div>
<div class="container-fluid">	
<div class="widget-box">
                        <div class="widget-title">
                                <span class="icon">
                                        <i class="icon-th-list"></i>
                                </span>
                                <h5>Notifications</h5>
                        </div>
                        <div class="widget-content">
                                <div class="alert alert-error alert-block">
                                        <a class="close" data-dismiss="alert" href="#">×</a>
                                        <h4 class="alert-heading">Error!</h4>
                                        <?php 
                                        if (isset($AccountStatuInfo))
                                        {
                                        	echo $AccountStatuInfo;                                        	
                                        }
                                        else
                                        {
                                        	echo  "您的账号".$_SESSION['account'].已在其他地点登录，不能重复登录;
                                        }
                                        ?> 		
                                </div>
							<a href="<?php 
							if (!empty($action)){
								echo $action;
							}else{
								echo '/user/repeatlogin';
							} ?>"
    						class="btn btn-large btn-primary ">重新登录</a>
                            <a href="/user/loginout" class="btn btn-large btn-primary ">返回登录界面</a>
                        </div>
                </div>
    </div>
<?php
echo Page_Lib::footer()?>