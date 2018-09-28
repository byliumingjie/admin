<?php 
 
$insert_html  = Page_Lib::loadJs('mall','','dev');
$insert_html.= Page_Lib::loadJs('server.list');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>活动编辑</h1>  
</div>
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index " title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">活动编辑</a>
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
                <li class="active"><a data-toggle="tab" href="#tab1">编辑活动配置</a></li>                 
            </ul>
	</div> 
	<?php 
	if ($EditActivitylist){
		foreach ($EditActivitylist as $var)
		{ 
			$MallId = (int)$var['id'];
			$platformId = (int)$var['platId'];
			$serverId = (int)$var['serverId'];
			$title = $var['title']; 			
			$content = $var['description'];			 
			$starttime = $var['startTime'];
			$endtime = $var['endTime'];
			$rules = json_decode($var['rules'],TRUE);
			$type = $var['type'];
			$rewardList = isset($rules['rewardList']) 
			? 
			$rules['rewardList']:NULL;
			
			$costList =  isset($rules['costList']) 
			? 
			$rules['costList']:NULL;
			
			$cost = $reward = $itmeInfo = NULL;
			
			$CostInfoOut = $rewardInfoOut = [];
			
			if (!empty($costList) && count($costList)>0){
				
				foreach ($costList as $initem)
				{	
					$itemId = $initem['ItemId'];
					$itemNum = $initem['ItemNumber'];
					 
					$CostInfoOut[]=$itemId.','.$itemNum; 
				}
				$cost = implode('&', $CostInfoOut);
			}	
			if (!empty($rewardList) && count($rewardList)>0){
				   
				foreach ($rewardList as $initem)
				{
					$itemId = $initem['ItemId'];
					$itemNum = $initem['ItemNumber'];
			
					$rewardInfoOut[]=$itemId.','.$itemNum;
				}
				$reward = implode('&', $rewardInfoOut);
			}
		}
	}
	?>
<script type="text/javascript">
$(function() { 
	<?php if (!empty($platformId) && !empty($serverId) ):?>
		/**执行活执行平台**/
		optionVerifySet('platById',<?php echo $platformId;?>,false,<?php echo $serverId;?>);
		/**区服**/
		optionVerifySet('ServerId',<?php echo $serverId;?>,true);
		optionVerifySet('malltypeId',<?php echo $type;?>,true);
	<?php endif;?>
});
</script>
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
	  
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal"  method="POST" id="CreateMallForm">
                 	<input type="hidden" name="editMallId" value="<?php echo $MallId;?>">        
                    <table class="table  table-striped" > 
                    <tbody> 
                     <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*平台</label>
									<div class="controls">
									<?php   
									$platformOut = System_Service::getplatformInfo();
									?>	
									<select  id="platById" name="platformId" data-plat="platId">									 
									<option value=0>--请选择平台--</option>
									<?php if (is_array($platformOut) && !empty($platformOut)) :?>
									<?php foreach ($platformOut as $var):?> 
									<option value=<?php echo $var['id'];?>>
									<?php echo $var["name"];?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>	
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
						<tr>
							<td>
								<div class="control-group" >
									<label class="control-label"></label>
									<div class="controls">
										<select name='server' id='ServerId'
										class='form-control'  
										title='请选择区服'></select>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
                    <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*活动名称</label>
									<div class="controls">
										<input type="text" class="form-control"  
										placeholder="请以小于30字以内来进行描述" 
										maxlength="30" name="title" value="<?php echo $title;?>">
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
                    	<tr>
							<td>
								<div class="control-group">
									<label class="control-label">*活动描述</label>
									<div class="controls">
									<textarea rows="5" cols="6"  
									placeholder="请以小于60字以内来进行描述" 
									maxlength="60" name="content"><?php echo $content;?></textarea>
										<!-- <input type="text" class="form-control" 
										placeholder="请以小于60字以内来进行描述" 
										> -->
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
                        <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*推荐类型</label>
									<div class="controls">
										 <select name='malltype' id="malltypeId">
										 	<option>--请选择--</option>
										 	<option value=1>商城推荐</option>
										 	<option value=2>首冲推荐(无需填写开始结束时间)</option>
										 </select>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr> 
						<tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动起始时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						             <input type="text" class=" datetimepicker" 
						             name="starttime" value="<?php if ($starttime>0){echo date('Y-m-d H:i:s',$starttime);}?>"
						             style="width:25%" placeholder="起始时间"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动截止时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						            <input type="text" class=" datetimepicker " 
						             name="endtime" value="<?php if ($endtime>0){echo date('Y-m-d H:i:s',$endtime);}?>" 
						             style="width:25%" placeholder="截至时间"/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        </tr>
						   <tr id="activityBytr2">
								<td> 
									<div class="control-group">
									<label class="control-label">*商城推荐显示配置信息,或 首冲</label>
									<div class="controls">
									<textarea rows="5" cols="1" style="width: 25%" 
									placeholder="奖励配置,格式:道具Id,数量&amp;道具Id,数量例:123,500&456,100" 
									maxlength="10000" name="reward"><?php echo $reward; ?></textarea>										 
									</div>
								</div>								
								</td>
							</tr>
							<tr id="activityBytr2">
								<td> 
									<div class="control-group">
									<label class="control-label">*商城推荐消耗配置信息</label>
									<div class="controls">
									<textarea rows="5" cols="1" style="width: 25%" 
									placeholder="奖励配置,格式:道具Id,消耗  例:3,500" 
									maxlength="10000" name="cost"><?php echo ($type==1)?$cost:NULL; ?></textarea>										 
									</div>
								</div>								
								</td>								 
								
							</tr>
                        </tbody>
                    </table>
                    <div id="configRules">
                     
                    </div>
                       
                </form>  
                   <div style="text-align: center;">	
                        <button  type="button" class="btn btn-success" id="activitybtn" style="margin: auto;">提交</button>
                    </div>              
            </div> 
        </div>
    </div>
   </div>
 </div>
 <div id='test'></div>

<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

