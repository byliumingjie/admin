<?php 
 
$insert_html  = Page_Lib::loadJs('global-activity','','dev');
$insert_html.= Page_Lib::loadJs('item');
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
	$platformId = 0;
	$title =NULL;
	$content=NULL;
	$activityType=0;
	$starttime =NULL;
	$endtime=NULL;
	$stoptime =NULL;
	$ResetType=NULL;
	$ResetTime=NULL;
	$rules =NULL;
	$itmeInfo = NULL;
	$itemOut = NULL;
	$rulesTotal =NULL;
	if ($EditActivitylist)
	{
		foreach ($EditActivitylist as $var)
		{
			$activityId = (int)$var['id'];
			$platformId = (int)$var['platId'];
			$serverId = (int)$var['serverId'];
			$title = $var['title']; 			
			$content = $var['content'];
			$activityType = (int)$var['activityType'];
			$starttime = $var['starttime'];
			$endtime = $var['endtime'];
			$stoptime = $var['stoptime'];
			$ResetType = $var['ResetType'];
			$ResetTime = $var['ResetTime'];
			$rules = json_decode($var['rules'],TRUE);
			
			//var_dump($rules);
			
			foreach ($rules as $data){
				
				$itemOut =array();
				
				foreach ($data as $key=>$var)
				{		
				$itmeInfo =NULL;
					
				 if ($key == 'itemList'){
					 
					foreach ($var  as $invar)
					{
						
						$itemId = 	$invar['ItemId'];
						$itemNumber =  $invar['ItemNumber'];
						if ($itmeInfo==NULL)
						{
							$itmeInfo  =$itemId.','.$itemNumber;
						}
						else
						{
							$itmeInfo  .='&'.$itemId.','.$itemNumber;
						}
					} 
					$itemOut[$key] = $itmeInfo;
					 continue;
				}
				//echo $key."<br>";
				$itemOut[$key] = $var; 
				}
				$rulesTotal[] = $itemOut;
			}
			
			
		}
		//var_dump($rulesTotal);
	}
	?>
    <script type="text/javascript">
$(function() { 
	<?php if ($activityType && $activityType>0):?>
		 
		/**执行活动类型**/
		optionVerifySet('activityType',<?php echo $activityType;?>);		
		/**执行click 更新规则属性 **/
		$("#activityType").click();
		
		//setTimeout(function(){
		
		/**执行活执行平台**/
		optionVerifySet('platByIdd',<?php echo $platformId;?>,false,<?php echo $serverId;?>);
		/**区服**/
		optionVerifySet('ServerId',<?php echo $serverId;?>,true);
		/**执行重置时间类型**/
		<?php if ($ResetType>0):?>
		optionVerifySet('ResetType',<?php echo $ResetType;?>);
		<?php endif;?>
		<!--规则验证-->
		<?php if ($rulesTotal && count($rulesTotal)>0):?>
		<?php $i = 1;?>
		<?php foreach ($rulesTotal as $invardata):?>
			<?php foreach ($invardata as $key=>$Inrules):?>
			  
				<?php  $byid = 'activityBytr'.$i; $termId = 'term'.$i; ?>
				<!--set term checkbox-->
				$("[id = <?php echo $termId;?>]:checkbox").attr("checked", true);
				<!--set select option type-->
				<?php  if ($key=='type'): ?>
				 
				optionVerify("<?php echo $byid;?>","<?php echo $key;?>",<?php echo (int)$Inrules;?>);
				<?php endif;?>
				<!--set default -->	
				<?php if ($key=='itemList')
				{
					$findOption = 'textarea';
				}else{
					$findOption = 'input';
				}
				?>			
				$("#<?php echo $byid;?>").find('<?php echo $findOption;?>[name="<?php echo $key;?>[]"]').val("<?php echo $Inrules;?>");
			<?php endforeach;?>
		<?php $i++;?>
		<?php endforeach;?> 
		<?php endif;?>
	<?php endif;?>
		//},1000);
});


</script>
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
	  	 <?php 
	  	  
 			echo DevToolbox_Lib::ItemConfigHtml($itemListObject);
 		?>
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal"  method="POST" id="CreateActivityForm">
                 	<input type="hidden" name="editActivityId" value="<?php echo $activityId;?>">        
                    <table class="table  table-striped" > 
                    <tbody> 
                    <tr>
							<td>
								<div class="control-group">
									<label class="control-label">*平台</label>
									<div class="controls">
									<select  id="platByIdd" name="platId" >
									<?php  $platformOut = session::get('AllplatformInfo');?>									
									<?php if (is_array($platformOut) && !empty($platformOut)) :?>
									<?php foreach ($platformOut as $var):?>
									<?php if((int)$var['type']===0){continue;}?>
									<?php echo '<option value='.$var['type'].'>'.$var["platformname"].'</option>';?>
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
									<label class="col-md-3 control-label">*活动类型</label>
									<div class="controls">									
										<?php if ($activityinfo):?>										
										<select id="activityType" name="activityType">										 
										<option value=0>--请选择活动类型--</option>
										<?php foreach ($activityinfo as $var):?>		 
											<option value=<?php echo $var['id']?>>
											<?php echo $var['remarks'];?></option>
										<?php endforeach;?>
										</select>
										<?php endif;?>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
						 		<tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动起始时间(时间戳秒)</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						             <input type="text" name="starttimeDay" style="width:5%" placeholder="日"/>
						             - <input type="text" name="starttimeHour" style="width:5%" placeholder="时"/>
						             : <input type="text" name="starttimeMint" style="width:5%" placeholder="分"/>
						             : <input type="text" name="starttimeSec" style="width:5%" placeholder="秒" value='<?php echo $endtime;?>'/>
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
						            <input type="text" name="endtimeDay" style="width:5%" placeholder="截至日"/>
						            - <input type="text" name="endtimeHour" style="width:5%" placeholder="截至时"/>
						            : <input type="text" name="endtimeMint" style="width:5%" placeholder="截至分"/>
						            : <input type="text" name="endtimeSec" style="width:5%" placeholder="截至秒" value='<?php echo $endtime;?>'/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">活动关闭时间</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
						              <input type="text" name="stoptimeDay" style="width:5%" placeholder="活动关闭日"/>
						              - <input type="text" name="stoptimeHour" style="width:5%" placeholder="活动关闭时"/>
						              : <input type="text" name="stoptimeMint" style="width:5%" placeholder="活动关闭分"/>
						              : <input type="text" name="stoptimeSec" style="width:5%" placeholder="活动关闭秒" value='<?php echo $stoptime;?>'/>
                                    </div>
                                    </label>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                         <tr>
                            <td>
                                <div class="control-group ">
                                	<label class="control-label">重置类型/时间点</label>
                                    <label class="checkbox-inline">
                                    <div class="controls ">
                                     <select name="ResetType" id="ResetType" value="<?php echo $ResetType;?>">
                                     	<option>--请选择--</option>
                                     	<option value=1>时间点</option>
                                     	<option value=2>整点</option>
                                     </select>       
                                     <input type="text" class="form-control" 
										placeholder="请以整形对应重置类型填写" value="<?php echo $ResetTime;?>" name="ResetTime" style="width:15%">
                                    </div>
                                    </label>
                                </div>
                            </td>
                           
                        </tr> 
                        <tr>
							<td>
								<div class="control-group">
									<div class="control-group">
										<label class="col-md-3 control-label">配置条件:</label>
										<div class="controls">
											<span style="color:gray">可使用的配置条件为根据活动类型去填写的，需要填写对应的数值以及生产描述，
											每条都需要填写对应的完成奖励。</span>
										</div>
									</div>
								</div>
							</td>
							<td>
							</td>
						</tr>
						  
                        </tbody>
                    </table>
                    <div id="configRules">
                     
                    </div>
                       
                </form>  
                   <div style="text-align: center;">	
                        <button  type="button" class="btn btn-success" id="Globactivitybtn" style="margin: auto;">提交</button>
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

