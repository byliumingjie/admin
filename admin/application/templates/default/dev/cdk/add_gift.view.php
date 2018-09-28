<?php 
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
$insert_html =Page_Lib::loadJs('cdk');
$insert_html .= Page_Lib::loadCss('bootstrap-select.min');
$insert_html .= Page_Lib::loadJs('loadmailfile');
//$insert_html .= Page_Lib::loadJs('mail');
$insert_html .= Page_Lib::loadJs('multselect');
$insert_html .= Page_Lib::loadJs('bootstrap-select');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>新增礼包</h1>  
</div>
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">新增礼包</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    	<?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     	<i class="icon-question"></i></a>
 </div>
<div class="container-fluid">					
  <div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title">
	<span class="icon">
            <i class="icon-th"></i>
        </span>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab1">添加礼包配置</a></li>                 
            </ul>
	</div> 
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
	  
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal"  method="POST" id="savePerMailForm" onsubmit="return false;">        
                    <table class="table  table-striped" > 
                    <tbody> 
                    	<tr>
							<td>
								<div class="control-group">
									<label class="control-label">*礼包描述</label>
									<div class="controls">
										<input type="text" class="form-control" 
										maxlength="60" placeholder="你所配的礼包是一个怎样的礼包呢 ? 请以小于20字以内来进行描述" 
										maxlength="30" name="bewrite">
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
                        <tr>
							<td>
								<div class="control-group">
									<label class="col-md-3 control-label">批量附件</label>
									<div class="controls">
										<textarea class="form-control" 
										name="annex" rows="7" maxlength="10000" 
										placeholder="默认可以空,格式:'道具Id,数量&道具Id,数量'例:123,50&456,10"></textarea>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
                        <?php echo DevToolbox_Lib::mailModuleHtml($configinfo,true);?>
                        </tbody>
                    </table>
                     <?php //if(!empty($addPerMail)):?>
                     <div style="text-align: center;">	
                        <button class="btn btn-success" id="savePerBtn" style="margin: auto;">保存</button>
                    </div>
                     <?php //endif;?>
                </form>                
            </div> 
        </div>
    </div>
   </div>
 </div>
<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

