<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
class DevToolbox_Lib  extends Page_Lib{

	/**
	 * @param $idName key 
	 * @param $title  title[0] is initial title[1] option is true
	 * @param $config item config list
	 * @param desc option list 
	 * **/
	public static  function elementMailOption($idName,$title=array(),
	$config=array(),$desc=null,$ItemNumber=NULL)
	{
		if (!empty($ItemNumber))
		{
			for ($i=1;$i<=$ItemNumber;$i++)
			{
			$itemOption = '道具ID'.$i.'&nbsp<input type="checkbox" value="propid'.$i.'" name="checkbox[]">
			<input type="text" class="form-control"
			name="propid'.$i.'" onblur="checkProp(this.value,3)">';
			//
			if ( count($config['itemConfig'])>0 ){
				
				$itemOption = '道具'.$i.':&nbsp<input type="checkbox" value="propid'.$i.'" name="checkbox[]">&nbsp';
				
				/* if ($i==1){
					$itemOption = '<select id="propid'.$i.'" name="propid'.$i.'"
				class="selectpicker" data-live-search-placeholder="Search" value="20003" data-actions-box="true">';
				}
				else{ */
				$itemOption.= '<select id="propid'.$i.'" name="propid'.$i.'"
				class="selectpicker" data-live-search-placeholder="Search" data-actions-box="true">';
				//}
				$itemOption.="<option value=0>--{$desc}列表--</option>";
				foreach ($config['itemConfig'] as $Invar)
				{
					$itemOption.="<option value=".$Invar['id'].">".$Invar['name']."</option>";
				}
				$itemOption.='</select>';
			}
		  
			  $ItemInfoHtml .= '<tr>
				<td>
					<div class="control-group">
						<label class="checkbox-inline">
						<div class="controls">
							'.$itemOption.'
						</div>
						</label>
					</div>
				</td>
				<td>
					<div class="control-group">
						<label class="control-label">道具数量'.$i.'</label>
						<div class="controls">
							<input type="text" class="form-control" 
							maxlength="10" placeholder="不可输入小数" name="propnum'.$i.'"'.'">
						</div>
					</div>
				</td>
			</tr>';
			  $itemOptionOut [] =$itemOption;
		  }
		  $itemOutArray = 
		  [
		  	'ItemInfoHtml'=>$ItemInfoHtml,
		  	'ItemOption'=>$itemOptionOut		  		
		  ];
		  return $itemOutArray;
		}
		// 道具配置 itemConfig
		$itemOption = $title[0].'&nbsp<input type="checkbox" value="'.$idName.'" name="checkbox[]">
		<input type="text" class="form-control"
		name="'.$idName.'" onblur="checkProp(this.value,3)">';
		//
		if ( count($config['itemConfig'])>0 ){
			$itemOption = $title[1].':&nbsp<input type="checkbox" value="'.$idName.'" name="checkbox[]">&nbsp';
			$itemOption.= '<select id="'.$idName.'" name="'.$idName.'"
			class="selectpicker" data-live-search="true" data-live-search-style="begins">';
			$itemOption.="<option value=0>--{$desc}列表--</option>";
			foreach ($config['itemConfig'] as $Invar)
			{
			  $itemOption.="<option value=".$Invar['id'].">".$Invar['name']."</option>";
			}
			$itemOption.='</select>'; 
		}
		return $itemOption;
		
	}
/**
 *  mail 组件
 * 
 * @param unknown $error_code
 * @paramparam unknown $title
 * @param unknown $content
 * @return string**/
public static  function mailModuleHtml($config=NULL,$Itemtype=false){
 
	// 表情包配置
	$faceOption = '表情ID <input type="checkbox" value="faceid" name="checkbox[]">
	<input type="text" class="form-control" placeholder="装备或饰品的ID" 
	name="faceid" onblur="checkProp(this.value,2)">';
	
	if (count($config['faceConfig'])>0)
	{
		$faceOption = '表情:<input type="checkbox" value="faceid" name="checkbox[]"> &nbsp';
		$faceOption .= '<select id="faceid" name="faceid" class="selectpicker" 
		data-live-search="true" data-live-search-style="begins">';
		$faceOption.="<option value=0>--表情包列表--</option>";
		foreach ($config['faceConfig'] as $Invar){
			$faceOption.="<option value=".$Invar['id'].">".$Invar['name']."</option>";			
		}
		$faceOption.='</select>';
	}
	// 套装配置
	$suitOption = '套装ID: <input type="checkbox" value="suitId" 
	name="checkbox[]"><input type="text" class="form-control" 
	placeholder="套装的ID" name="suitId" onblur="checkProp(this.value,1)">';
	
	if ( count($config['suitConfig'])>0 ){
		
		$suitOption = '套装:<input type="checkbox" value="suitId" name="checkbox[]">&nbsp';
		$suitOption .= '<select id="suitId" name="suitId" 
		class="selectpicker" data-live-search="true" data-live-search-style="begins">';
		$suitOption.="<option value=0>--套装列表--</option>";
		foreach ($config['suitConfig'] as $Invar){
			$suitOption.="<option value=".$Invar['id'].">".$Invar['name']."</option>";			
		}
		$suitOption.='</select>';
	}
	// 装备配置
	$equipOption = '装备ID <input type="checkbox" value="equipid" name="checkbox[]">
	<input type="text" class="form-control" placeholder="装备或饰品的ID" 
	name="equipid" onblur="checkProp(this.value,2)">';
	
	if ( count($config['equipConfig'])>0 ){
	
		$equipOption = '装备:<input type="checkbox" value="equipid" name="checkbox[]">&nbsp';
		$equipOption .= '<select id="equipid" name="equipid"
		class="selectpicker" data-live-search="true" data-live-search-style="begins">';
		$equipOption.="<option value=0>--装备列表--</option>";
		foreach ($config['equipConfig'] as $Invar){
			$equipOption.="<option value=".$Invar['id'].">".$Invar['name']."</option>";
		}
		$equipOption.='</select>';
	}
	$itemOption =NULL;
	$ItemHtml = NULL;
	if ($Itemtype ==false)
	{
		 $itemOption1 = self::elementMailOption('propid1',['道具ID1','道具1'],$config,'道具列表');
		 $itemOption2 = self::elementMailOption('propid2',['道具ID2','道具2'],$config,'道具列表');
		 $itemOption3 = self::elementMailOption('propid3',['道具ID3','道具3'],$config,'道具列表');
		 $itemOption4 = self::elementMailOption('propid4',['道具ID4','道具4'],$config,'道具列表');
		 
		 $ItemHtml = '<tr>
			<td>
				<div class="control-group">
					<label class="checkbox-inline">
					<div class="controls">
						'.$itemOption1.'
					</div>
					</label>
				</div>
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">道具数量1</label>
					<div class="controls">
						<input type="text" class="form-control" 
						maxlength="10" placeholder="不可输入小数" name="propnum1">
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="control-group">
					<label class="checkbox-inline">
					<div class="controls">
						'.$itemOption2.'
					</div>
					</label>
				</div>
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">道具数量2</label>
					<div class="controls">
						<input type="text" class="form-control" maxlength="10" 
						placeholder="不可输入小数" name="propnum2">
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="control-group">
					<label class="checkbox-inline">
					<div class="controls">
						'.$itemOption3.'
					</div>
					</label>
				</div>
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">道具数量3</label>
					<div class="controls">
						<input type="text" class="form-control" maxlength="10" 
						placeholder="不可输入小数" name="propnum3">
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="control-group">
					<label class="checkbox-inline">
					<div class="controls">
						'.$itemOption4.'
					</div>
					</label>
				</div>
			</td>
			<td>
				<div class="control-group">
					<label class="control-label">道具数量4</label>
					<div class="controls">
						<input type="text" class="form-control" maxlength="10" 
						placeholder="不可输入小数" name="propnum4">
					</div>
				</div>
			</td>
		</tr>';
	}
	else
	{
		$itemOption = self::elementMailOption('propid',array(),$config,'道具列表',10);	
		$ItemHtml = $itemOption['ItemInfoHtml']; 
	}
	
$html = <<<EOF
<!--<tr>
	<td>
		<div class="control-group">
			<label class="control-label">*发送时间</label>
			<div class="controls">
				<input type="text" class="datetimepicker form-control" 
		placeholder="请选择时间" name="sendtime">
			</div>
		</div>
	</td>
	<td>
	</td>
</tr>-->
<tr>
	<td>
		<div class="control-group">
			<label class="control-label">*标题</label>
			<div class="controls">
				<input type="text" class="form-control" 
				maxlength="60" placeholder="最多输入十个汉字" name="mailtitle">
			</div>
		</div>
	</td>
	<td>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<label class="col-md-3 control-label">*内容</label>
			<div class="controls">
				<textarea class="form-control" 
				name="context" rows="7" maxlength="800" 
				placeholder="最多输入800汉字,包括标点符号和空格"></textarea>
			</div>
		</div>
	</td>
	<td>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<div class="control-group">
				<label class="col-md-3 control-label">添加附件:</label>
				<div class="controls">
					<span style="color:gray">附件个数不能超过四个</span>
				</div>
			</div>
		</div>
	</td>
	<td>
	</td>
</tr>
<tr>
	<td>
		<label class="checkbox-inline">
		<div class="control-group">
			<div class="controls">
				金币 <input type="checkbox" value="coin" name="checkbox[]">
				<input type="text" class="form-control" 
				maxlength="9" placeholder="不可输入小数" name="coin">
			</div>
		</div>
		</label>
	</td>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				药丸 <input type="checkbox" value="pill" name="checkbox[]">
				  <input type="text" class="form-control" maxlength="10" 
				  style="width:50%" placeholder="不可输入小数" name="pill">
			</div>
			</label>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				棒棒糖 <input type="checkbox" value="sugar" name="checkbox[]">
				<input type="text" class="form-control" maxlength="10" 
				placeholder="不可输入小数" name="sugar">
			</div>
			</label>
		</div>
	</td>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				石中剑 <input type="checkbox" value="sword" name="checkbox[]">
				<input type="text" class="form-control" maxlength="10" style="width:50%" 
				placeholder="不可输入小数" name="sword">
			</div>
			</label>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				竞技场次数 <input type="checkbox" value="sports" name="checkbox[]">
				<input type="text" class="form-control" maxlength="10" 
				placeholder="不可输入小数" name="sports">
			</div>
			</label>
		</div>
	</td>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				经验 <input type="checkbox" value="exp" name="checkbox[]">
				<input type="text" class="form-control" maxlength="10" 
				placeholder="不可输入小数" name="exp">
			</div>
			</label>
		</div>
	</td>
</tr>
	<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				竞技场积分<input type="checkbox" value="sportsIntegral" name="checkbox[]">
				<input type="text" class="form-control" maxlength="10" 
				placeholder="不可输入小数" name="sportsIntegral">
			</div>
			</label>
		</div>
	</td>
	 <td></td>
</tr>	
<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
			{$suitOption}
			</div>
			</label>
		</div>
	</td>
	<td>
		<div class="control-group">
			<label class="control-label">套装数量</label>
			<div class="controls">
				<input type="text" class="form-control" 
				maxlength="10" placeholder="不可输入小数" name="suitnum">
			</div>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				{$equipOption}
			</div>
			</label>
		</div>
	</td>
	<td>
		<div class="control-group">
			<label class="control-label">装备数量</label>
			<div class="controls">
				<input type="text" class="form-control" 
				maxlength="10" placeholder="不可输入小数" name="equipnum">
			</div>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div class="control-group">
			<label class="checkbox-inline">
			<div class="controls">
				$faceOption
			</div>
			</label>
		</div>
	</td>
	<td>
		<div class="control-group">
			<label class="control-label">表情数量</label>
			<div class="controls">
				<input type="text" class="form-control" 
				maxlength="10" placeholder="不可输入小数" name="facenum">
			</div>
		</div>
	</td>
</tr>
{$ItemHtml}
  <!-- 提示组件 end-->
EOF;
return $html;
}

 
public static function show($error_code,$title,$content){
	 	$status = (Int)$status;
		switch($error_code){
			case 1: $error_code ='success';break;
			case 2: $error_code ='info';break;
			case 3: $error_code ='warning';break;
			case 4: $error_code ='danger';break;
			default:'';break;			
		}
      	//组装  modal-backdrop, .modal-backdrop.fade.in
$html = <<<EOF

      			 
    <!-- 提示组件 begin-->
	<div class="modal fade in" id="showMenuModal" tabindex="-1" role="dialog" 
	aria-labelledby="addMenuModalLabel" aria-hidden="false"  >
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button"class="close" id="closemode" 
	                data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title" id="addMenuModalLabel">{$error_code}:{$title}</h4>
	            </div>
	            <div class="modal-body">            
					{$content}
	            </div>            
	        </div>
	    </div>
  </div>
  <div class="modal-backdrop fade in" id="showScreenMode" ></div>
  <!-- 提示组件 end-->
		
EOF;
        return $html;
	
}

public static  function function_statData_script($clickByid="type",
		$lookbyid="lookstatusid",$optionbyid="optiontimid",$value='default',$idto='')
{
	$scriptjava = "
	<script>
	window.onload = function()
	{
	 
	var obj_select = document.getElementById('{$clickByid}');
	var obj_div = document.getElementById('{$lookbyid}');
	var obj_replacetype = document.getElementById('{$optionbyid}');
	var idto = document.getElementById('{$idto}'); 
	
	if(obj_select.value==1 || obj_select.value=='{$value}'){
	obj_div.style.display = 'block';
	idto.style.display = 'block';
	obj_replacetype.style.display = 'none';
	}
	else{
		idto.style.display = 'none';
		obj_div.style.display = 'none';
		obj_replacetype.style.display = 'block';
	}
	obj_select.onchange = function(){
		idto.style.display = this.value==1? 'block' : 'none';
		obj_div.style.display = this.value==1? 'block' : 'none';
		obj_replacetype.style.display = this.value==2? 'block' : 'none';
	}
}
</script>";
	return $scriptjava;
}

// cdk new add 
public static function cdkToolboxHtml( ) 
{     	
		$style ="style='margin:0px';";
		 
	 	$maxidBtn = '';
	 	// 礼包名称
	 	$cdkname ="<input type='text' style='' class='input-mini' 
	 	name='cdkname'>";
	 	// 金币
	 	$goldBtn = "<input type='text' title='金币' id='gold' style='' class='input-mini' 
	 	name='gold'>";
	 	// 钻石
	 	$diamond = "<input type='text' title='钻石'style='' class='input-mini' 
	 	name='diamond'>";
	 	// 装备
	 	$equipment ="<input type='text' title='装备'style='' class='input-mini' 
	 	name='equipment'>";
	 	// 道具1
	 	$propst1 ="<input type='text' title='道具1'style='' class='input-mini' 
	 	name='props1'>";
	 	// 道具2
	 	$propst2 ="<input type='text' title='道具2'style='' class='input-mini' 
	 	name='props2'>";
	 	// 道具3
	 	$propst3 ="<input type='text' title='道具3'style='' class='input-mini' 
	 	name='props3'>";
	 	
	 	$propst4 ="<input type='text' title='道具4'style='' class='input-mini' 
	 	name='props4'>";
	 	
	 	$title = "<textarea type='text' title='备注'style='' class='input-mini' 
	 	name='title'></textarea>";
	 	
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
		data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
		<span class="ladda-label"> 保存</span></button>'; 
	 	
      	//组装
      	$html = <<<EOF
      			 
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
				
			<div class="buttons">
		</div>	
		</div>
	    <div class="widget-content">			
        <form  action ="setSinglePackage" method="POST" class="form-horizontal" onsubmit="return cdkPutVerify(this);" >         
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>						  
						 <td><B>礼包名称:</B></td>						  
		                 <td>$cdkname</td>
		                 <td><B>金币:</B></td>
						 <td><input name="checkbox0" $style type="hidden" value="1"/>$goldBtn</td>
						</tr>
						<tr>						 
						 <td><B>钻石:</B></td>						  
		                 <td><input name="checkbox1" $style type="hidden" value="2" />$diamond</td>
		                 <td><B>装备:</B></td>
						 <td><input name="checkbox2" $style type="hidden" value="3" />$equipment</td>
						</tr>
						<tr>
						 <td><B>道具1:</B></td>						  
		                 <td><input name="checkbox3" $style type="hidden" value="4" />$propst1</td>
		                  <td><B>道具2:</B></td>
						 <td><input name="checkbox4" $style type="hidden" value="5" />$propst2</td>
						</tr>
						<tr>
						
						 <td><B>道具3:</B></td>						  
		                 <td><input name="checkbox5" $style  type="hidden" value="6" />$propst3</td>
		                 <td><B>道具4:</B></td>						  
		                 <td><input name="checkbox6" $style  type="hidden" value="7" />$propst4</td>
		                 
						</tr>
						<tr>
						<td><B>备注:</B></td>
						<td>$title</td>
						<td>$submitBtn</td>
						</tr>						 
					</table>                         
                 </div>
             </div>   	               
        </form>       
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
}
// 充值订单查询
public static function rechargeHtml($data) 
{     	
		$style ="style='margin:0px';";
		 
	 	$maxidBtn = '';
	 	// 开始时间
	 	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	// 截止时间
	 	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	$plafrom = self::getplatListInfo();
    	
    	$sid = "<select name='sid' id='ServerId' class='form-control' 
    	style='width:120px;display:none' title='请选择区服'></select>";
	 	// 平台
	 	//$plafrom = $platList;
	 	// 渠道
	 	$channel = "
	 	<div id='container2'>
	 	 <div class='side-by-side clearfix'> 
	      <div>               
	        <select data-placeholder=' ' class='chzn-select2' id='channelinfo' 
	         multiple style='width:160px;' tabindex='4'>
	          <option value='1'>37</option> 
	          <option value='2'>xy</option>
	          <option value='3'>九游</option>
	          <option value='4'>啄木</option> 
	        </select>
	      </div>
	    </div>
	    <div>";
	 	 
	 	$orderstauDat = Config::get("key.order");
	 	$option = '';
	 	foreach($orderstauDat as $key=>$var)
	 	{
	 		$option.="<option value='{$key}'>{$var}</option> ";	
	 	}
	 	// 订单状态
	 	$orderstau = "
	 	<div id='container3'>
	 	 <div class='side-by-side clearfix'> 
	      <div>               
	        <select data-placeholder=' ' class='chzn-select3' 
	         multiple style='width:160px;' tabindex='4'>
	           {$option}
	        </select>
	      </div>
	    </div>
	    <div>
	 	";
	 	 
	 	// 账号
	 	$account = "<input type='text' title='账号' id='account' style='' 
	 	class='input-mini' name='account'>";
	 	// 角色昵称
	 	$name= '<input type="text" style="height:20px"  class="newInput"
    		name="name" title="角色名" size="10"　  placeholder="用户昵称">';
	 	
	 	// uid
	 	$uid = "<input type='text' title='uid'style='' class='input-mini' 
	 	name='uid'>";
	 	 
	 	// 联运订单号
	 	$orderid ="<input type='text' title='联运订单号'style='' class='input-mini' 
	 	name='orderid'>";
	 	// 游戏订单号
	 	$gamOrderId ="<input type='text' title='游戏订单号'style='' class='input-mini' 
	 	name='gamorderid'>";
	 	
	 	// uin
	 	$uin ="<input type='text' title='uin' style='' class='input-mini' 
	 	name='uin'>";
	 	// sid
	 	/* $sid ="<input type='text' title='区服ID'   class='input-mini' 
	 	name='sid'>"; */
	 	
	 	$roleid ="<input type='text' title='角色ID'style='' class='input-mini' 
	 	name='roleid'>";
		
	 	$ordertype = Config::get("key.ordertype");
         
		foreach ($ordertype as  $Typekey=>$Typevar)
		{
			$typeoption.=" <option value='{$Typekey}'>{$Typevar}</option>";
		}
	 	$orderType = "
	 	<select name='orderType' id='orderType' style='width:160px;'> 		  
        <option value='0'>--是否测试--</option>   
	 	$typeoption
	    </select>";
	 	
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
		data-style="expand-right" name="sub_btn" type="submit"  id="btn_date">
		<span class="ladda-label"> 查询订单</span></button>'; 
	 	
	 	$readySubmitJs =!empty($data)?self::readySubmit('ExportfileBtn','from1'):"";
	 	
		$btnType = !empty($data)
		?
		'<input  type="button"  style="padding:0;width:120px" 
    	 class="btn btn-success" value="Dowload" id="ExportfileBtn"/>'
		:""; 
		
      	//组装
      	$html = <<<EOF
      			 {$readySubmitJs}
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
				
			<div class="buttons">
			$btnType
			</div>	
		</div>
	    <div class="widget-content">			
        <form  action ="setpay" method="POST" class="form-horizontal" >  
        		<input type="hidden" name='platfrominfo' id='platfrominfo'>
        		<input type="hidden" name='channelinfo' id='channelinfo'>       
        		<input type="hidden" name='orderstau' id='orderstau'>
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>						 
							 <td><B>平台:*</B></td>						  
			                 <td>$plafrom</td>
			                 <td></td>
							 <td>$sid</td>
						</tr>
						<tr>						  
							 <td><B>开始时间:</B></td>						  
			                 <td>$startTime</td>
			                 <td><B>截止时间:</B></td>
							 <td>$endTime</td>
						</tr>						
						<tr>
						 	<td><B>订单号:</B></td>						  
		                 	<td>$orderid</td>
		                 	<td><B>uid:</B></td>						  
			                 <td>$uid</td>	
						</tr>
						<tr>						
							 <td><B>角色昵称:</B></td>						  
			                 <td>$name</td>
			                 <td><B>角色ID:</B></td>
							<td>$roleid</td>	                 
						</tr>
						 
						<tr> 
							<td><B>订单状态:</B></td>
							<td>$orderstau</td>
							<td>$submitBtn</td>
						</tr>	
						<!--<tr>
							<td><B>订单类型:</B></td>
							<td>$orderType</td>
							<td></td>
							<td>$submitBtn</td>
						</tr>-->				 
					</table>                         
                 </div>
             </div>   	               
        </form>     
          
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
}

//事物提交
public static function readySubmit($clickID,$sumitID)
{
	$scriptjave ="
    	<script>
			$(document).ready(function() { 
			  $('#{$clickID}').click(function() 
			  { 
				 $('#{$sumitID}').submit();
			  }); 
			});
		</script>
    	";
	return $scriptjave;
}
public static function cdkHtml($object='') 
{     	
		$btnType =NULL;
	 
		$style ="style='margin:0px';";
	 
		// 礼包ID btn
	 	$maxidBtn ="<input type='text' style='' class='input-mini' 
	 	name='giftid'>";
	 	// 礼包名称
	 	$cdkname ="<input type='text' style='' class='input-mini' 
	 	name='name'>";
	 	
	 	// 开始日期
	 	$startTime ="<input type='text' class='datetimepicker form-control' 
	 	name='startTime'>";
	 	// 截止日期
	 	$endTime ="<input type='text' class='datetimepicker form-control' 
	 	name='endTime'>";
	 	
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
		data-style="expand-right" name="sub" type="submit" id="sub_date">
		<span class="ladda-label"> 查询</span></button>'; 
	 	
      	//组装
      	$html = <<<EOF
      			 {$readySubmitJs}
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>				
			<div class="buttons">
				{$btnType}
			</div>	
		</div>
	    <div class="widget-content">			
        <form  method="POST" class="form-horizontal"  action="giftlist">         
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
						 <td>礼包ID:</td>
						 <td>$maxidBtn</td>
						 <td>礼包名称:</td>						  
		                 <td>$cdkname</td>
						</tr>
						<tr>
						 <td>开始日期:</td>
						 <td>$startTime</td>
						 <td>截止日期:</td>						  
		                 <td>$endTime&nbsp$submitBtn</td>
						</tr> 
					</table>                         
                 </div>
             </div>   	               
        </form>       
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
}
 
//CDK SET 
public static function CdkSetHtml() 
{     	
		
		$style ="style='margin:0px';";
		/*$platfrom = Accountreplace_Service::getServer(true,'global');
		$maxid = CDK_Model::MaxID($platfrom);	  */
		// 礼包ID btn
	 	$maxidBtn =self::Get_giftInfo('giftid');
	 	// 礼包说明
	 	$cdkname ="<input type='text' style='' class='input-mini' 
	 	name='name'>";
	 	// 礼包码
	 	$code = null;
	 	// 礼包类型
	 	$giftType ="<input type='text' class='datetimepicker form-control' 
	 	name='giftType'>";
	 	
	 	// 开始日期
	 	$startTime ="<input type='text' class='datetimepicker form-control' 
	 	name='startTime'>";
	 	
	 	// 截止日期
	 	$endTime ="<input type='text' class='datetimepicker form-control' 
	 	name='endTime'>";
	 	// 金币
	 	$goldBtn = "<input type='text' title='金币' id='gold' style='' class='input-mini' 
	 	name='gold'>";
	 	// 钻石
	 	$diamond = "<input type='text' title='钻石'style='' class='input-mini' 
	 	name='diamond'>";
	 	// 装备
	 	$equipment ="<input type='text' title='装备'style='' class='input-mini' 
	 	name='equipment'>";
	 	// 道具1
	 	$propst1 ="<input type='text' title='道具1'style='' class='input-mini' 
	 	name='props1'>";
	 	// 道具2
	 	$propst2 ="<input type='text' title='道具2'style='' class='input-mini' 
	 	name='props2'>";
	 	// 道具3
	 	$propst3 ="<input type='text' title='道具3'style='' class='input-mini' 
	 	name='props3'>";
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
		data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
		<span class="ladda-label">查询</span></button>'; 
	 	
      	//组装
      	$html = <<<EOF
      			 {$readySubmitJs}
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>				
			<div class="buttons">
				
			</div>	
		</div>
	    <div class="widget-content">			
        <form  method="POST" class="form-horizontal" action="setcdk" onsubmit="return cdkVerify(this);">         
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
						 <td>*礼包ID:</td>
						 <td>$maxidBtn</td>
						 <td>礼包名称:</td>						  
		                 <td>$cdkname</td>
						</tr>
						<tr>
						 <td>开始日期:</td>
						 <td>$startTime</td>
						 <td>截止日期:</td>						  
		                 <td>{$endTime}&nbsp&nbsp{$submitBtn}</td>
						</tr>						
						 
					</table>                         
                 </div>
             </div>   	               
        </form>       
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
}
public static function Get_giftInfo($id=NULL)
{
	$id = empty($id)?'giftId':$id;
	
	$platfrom = Platfrom_Service::getServer(true,'globaldb');  
	
	//$cdkMod = new  CDK_Model();
	$giftinfo = CDK_Model::Stat_giftInfo($platfrom); 
	
	$suitOption = '<select id="giftId" name="'.$id.'"
		class="selectpicker" data-live-search="true" data-live-search-style="begins">';
	$suitOption.="<option value=0>--礼包信息--</option>";
	foreach ($giftinfo as $Invar){
		$suitOption.="<option value=".$Invar['id'].">".$Invar['bewrite']."</option>";
	}
	$suitOption.='</select>';	
	
	return $suitOption;
}
//CDK SET 
public static function rolebanHtml($formaction='index') 
{     	 
		$style ="style='margin:0px';";
		
		//$serverco$_COOKIE['roleban-server'];
		if ($_COOKIE['roleban-server']){
			
			echo $_COOKIE['roleban-server'];
		}
		$platHtml = self::getplatListInfo('width:90%');
		// sid
		 
		$sid = "<select name='server' id='ServerId'
		class='form-control' style='width:100%;display:none' 
		title='请选择区服'></select>";
		//角色ID
		$roleid = "<input type='text' style='' class='input-mini' 
	 	name='roleid'>";
		$lockStatus = "
	 	<select name='lockStatus' class='form-control' style='width:90%'>	 
	 		<option value=0>--请选择--</option>	  
			<option value=1>禁言</option>
			<option value=2>封号</option>
			<option value=3>解封禁言</option>
			<option value=4>解封登陆</option>
	 	</select>";
		
		// 开始时间
		$startTime = '<input type="text" 
		class="datetimepicker form-control" placeholder="开始时间" name="startTime">';
		// 截止时间
		
		$time = date("Y-m-d H:i:s",time());
		
		$endTime = "<input type='text'
		class='datetimepicker form-control' 
		placeholder='结束时间' name='endtime' value='{$time}'>";
		
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button progress-demo" 
		data-style="expand-right" name="sub_btn"  type="submit" id="btn_date">
		<span class="ladda-label"> 查询</span></button>'; 
	 	
	 	$roleBanListType = 0 ;
	 	$display = 'display:none;';
	 	if ($formaction=='roleBanlogIndex')
	 	{	$display = 'display:block';
	 		$roleBanListType = 1; 
	 	}
      	//组装
      	$html = <<<EOF
      			 
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>查询条件</h5>				
				<div class="buttons">
					 <!--<a title="添加名单" class="btn  btn-mini" id="addName">
					 <i $style class="icon-folder-open"></i> 导入用户</a>
					 -->
				</div>
		</div>
	    <div class="widget-content">			
        <form  method="POST" action ="index" id="robanSearchForm"
        class="form-horizontal" onsubmit="return roleBanVerify(this);">         
                <div class="control-group">
		<div class="controls">
		<table border=0 id="roledata">
		 <tr>
		 <td><B>禁用类型:</B>*</td>						  
                 <td>$lockStatus</td>
                 <td><B>平台:</B>*</td>
                 <td>$platHtml</td>
                 <td>$sid</td> 
                 <td>$submitBtn</td> 
                </tr> 
		</table>                         
 		</div>
             </div>   	               
        </form>       
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
}
 
public static function rolebanlogHtml() 
{     	
		
		$style ="style='margin:0px';";
		$platHtml = self::getplatListInfo('width:90%');
		// sid			
		$sid = "<select name='server' id='ServerId'
		class='form-control' style='width:100%;display:none'
		title='请选择区服'></select>";

		//角色ID
		$roleid = "<input type='text' class='input-mini' name='player_id'>";
		// 开始日期
	 	$startTime ="<input type='text' class='datetimepicker form-control' 
		name='startTime'>";
	 	$Nick_name = "<input type='text' class='input-mini' name='nick_name'>";
	 	//<option>账号封禁</option>
	 	$type = "
	 	<select name='type' class='form-control' style='width:100px'>	 
	 	<option value=''>--请选择--</option>	 
	 	<option value=1>角色封禁</option>	 		 	
	 	</select>";
	 	
	 	$status = "<select name='lockStatus' class='form-control' style='width:100px'>
	 	<option value=''>--请选择--</option>
		 	<option value=1>禁言</option>
			<option value=2>封号</option>
			<option value=3>解封禁言</option>
			<option value=4>解封登陆</option>	
	 	</select>";
	 	// 截止日期
	 	$endTime ="<input type='text' class='datetimepicker form-control' 
	 	name='endTime'>";
	 	// 提交
	 	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
		data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
		<span class="ladda-label"> 查询</span></button>';
	 	
      	//组装
      	$html = <<<EOF
      			 
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   <div class="widget-title" id="titl">
		<span class="icon">
			<i class="icon-search"></i>
		</span>
		<h5>设置条件</h5>				
		<div class="buttons">
		</div>	
	   </div>
	    <div class="widget-content">			
        <form  method="POST" action="roleBanlogIndex" class="form-horizontal" onsubmit="return roleBanLogVerify(this);">         
                <div class="control-group">
		<div class="controls">
		<table border=0 id="roledata"> 
		<tr>						  
		 <td>状态:</td>						  
                 <td>$status</td>
                 <td>平台:*</td>						  
                 <td>$platHtml</td>
                 <td>$sid</td>
		</tr>
		<tr>						
		 <td>角色ID:</td>						  
                 <td>$roleid</td>	
                 <td>角色昵称:</td>
		 <td>$Nick_name</td>
		 <td>&nbsp</td>
		</tr> 						
		<tr>
		 <td>开始日期:</td>
		 <td>$startTime</td>
		 <td>截止日期:</td>						  
 		 <td>$endTime</td>
		 <td>$submitBtn</td>
		</tr> 						 
		</table>                         
                 </div>
             </div>   	               
        </form>       
    </div>
</div>
</div>
  <!-- 查询组件 end-->
		
EOF;
        return $html;
} 	
public static function getServerInfo($name)
{
	$platformOut = session::get('AllplatformInfo');

	if ($platformOut)
	{
		$html ='<select name="'.$name.'">';

		foreach ($platformOut as $var)
		{
			if ($var['type']==0){continue;}
			$platformList[(int)$var['type']] = $var; 
		}

		ksort($platformList);

		foreach ($platformList as $var)
		{
			if ($var['type']==0){continue;}

			$platformList[(int)$var['type']] = $var;

			$serverHtml .= '<option value='.$var['type'].'>'.$var['type'].'区'.'</option>';
		}

		$serverHtmlTotal = $html.$serverHtml.'</select>';
		return $serverHtmlTotal;
	}
	return NULL;
}
//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// account info 
public static function accountHtml( )
{
	$style ="style='margin:0px';";
		 
	$maxidBtn = '';
	//$sid = self::getServerInfo('sid');
	
	$sid = "<select name='sid' id='ServerId' 
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	 
	/* $roleIdHtml = '<input type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size="10" >'; */
	
	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  placeholder="用户昵称">';
	$status = '';
	
	//$selecttype = self::selectregionType($type);
	$selecttype = self::getplatListInfo();
	
	//$sid ="<input type='text' class='input-mini' name='sid'>";
	// 角色ID
	$userId = "<input type='text'  
	placeholder='用户ID编号' class='input-mini'name='userid'>";	
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button" 
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';	 
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal" onsubmit="return statAccountVerify(this);" >
                <div class="control-group">
		<div class="controls">
		<table border=0>
			<tr>
			<td><B>平台:</B>*</td>
         	 <td>$selecttype</td>
			 <td>&nbsp</td>
         	 <td>$sid</td> 
			 <td>&nbsp;$userId</td> 
			 <td>$name</td>
			 <td>$submitBtn</td>		
			</tr>  
		</table>
         </div>
             </div>
        </form>
    </div>
</div>
</div>
<!-- 查询组件 end-->

EOF;
	return $html;
}

public static function lotteryHtml( )
{
	$style ="style='margin:0px';";
	$formJs = " 
	</script>";
	$maxidBtn = '';
	// 区服ID
	$server ="<input type='text' class='input-mini' name='server'>";
	// 角色ID
	/*<option value=3>遗迹</option> 
		<option value=4>王座</option>*/
	$type = "<select name='type' class='form-control' style='width:100px'>
	 	<option value=0>--请选择--</option>
	 	<option value=1>限时</option>
		<option value=2>每周</option>
		<option value=3>常驻</option>
	 	</select>";
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装
	$html = <<<EOF
		{$formJs}
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal" onsubmit="return lotteryVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
		                 <td><B>活动类型:&nbsp&nbsp</B></td>
						 <td>$type</td>
						 <td>&nbsp&nbsp<B>区服ID:</B></td>
		                 <td>$server</td>
						 <td>$submitBtn</td>
						</tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
<!-- 查询组件 end-->

EOF;
	return $html;
}

// 公告发布
public static function noticeHtml( )
{
	$displayScript = self::function_statData_script("sendType","userid","usertitle","","idto");
	
	$style ="style='margin:0px';";

	$maxidBtn = '';
	// 区服ID
	$sid ="<input type='text'   class='input-mini' id='server' name='server' >";
	// 发布类型
	$sendtype = "
			<select name='sendType' id='sendType' style='width:155px'>
			 	<option value='0'>--请选择--</option>
       			<option value='1'>角色ID</option>
      			 <option value='-1'>全服</option>
			</select>
	";
	$usertitle = "<font id='usertitle'></font>";
	// 角色ID
	$userId = "<input type='text'    class='input-mini'name='userid' id='userid'>";
	// 提交
	$submitBtn = '<button class="btn btn-lg btn-success ladda-button"
				data-style="expand-right" name="sut" type="submit" id="btn_date">
						  发布公告 </button>';
	
	$message = '<textarea  name="message" rows="3" cols="50	"></textarea>';
	$loopTimes = '<input type="text" class="form-control" name="loopTimes"  />';
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="sendNotice" method="POST" class="form-horizontal" onsubmit="return noticeVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>						
		                <tr>
		                 <td>&nbsp<B>发布类型:</B></td>
						 <td>$sendtype</td>
						</tr>
						<tr>
						 <td>&nbsp&nbsp&nbsp&nbsp&nbsp<B>区服Id:</B></td>
		                 <td>$sid</td>
		                </tr>
						<tr>
						 <td><B>公告内容:</B></td>
		                 <td>$message</td>
		                 </tr>
		                <tr>
		                 <td><B>循环次数:</B></td>
						 <td>$loopTimes</td>
						</tr>
						<tr>  
						 <td><B id='idto'>&nbsp&nbsp&nbsp&nbsp角色ID:</B></td>
						 <td>$userId</td> 
						</tr>						
						<tr> 
						 <td> </td>
						 <td><br> $submitBtn</td>
						 <td> </td>
						</tr>
					</table>
					<BR>
                 </div>
             </div>
        </form>
    </div>
	$usertitle
</div>
</div>
	$displayScript
  <!-- 查询组件 end-->

EOF;
	return $html;
}
//日志
public static function LOGHtml( )
{
	$style ="style='margin:0px';";
	$maxidBtn = '';
	// 区服ID
	$startTime ='<input type="text" class="datetimepicker form-control" 
	placeholder="创建日期"  name="startTime" style="width:auto;"/>';
	// 角色ID
	$endtime = '<input type="text" class="datetimepicker form-control" 
	placeholder="结束时间"  name="endtime" style="width:auto;"/>';	
	$channel = 
	'<select name="channelId"   style="width:auto;">
		<option value="0">--请选择渠道--</option> 
		<option value="3">后台账号操作</option>
	</select>';	
	$account = 
	'<input  name="account" placeholder="管理员" class="input-mini" style="width:auto;" type="text">';	
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';	
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="messageLog" method="POST" class="form-horizontal" onsubmit="return statLogVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td>$channel</td>
						 	<td><i class="icon-th "></i></td>
						 	<td>$account</td> 
						 </tr>
						 <tr>						 	
						 	<td>$startTime</td>
		                 	<td><i class="icon-th "></i></td>
						 	<td>$endtime</td>
						 	<td>$submitBtn</td>
						</tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}
// 系统缓存adminCacheHtml
public static function adminCacheHtml( )
{
	$style ="style='margin:0px';";
	$maxidBtn = '';
	// 区服ID
	$server ="<input type='text'   class='input-mini' id='server' name='server' >";
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	 name="startTime" style="width:auto;"/>';
	
	$lasttim = date('Y-m-d H:i:s',time());	
	// 截止时间
	$endtime = "<input type='text' class='datetimepicker form-control'
	 name='endtime' style='width:auto;' value='{$lasttim}'/>";
	 
	$account =
	'<input  name="account" class="input-mini" style="width:auto;" type="text">';
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal" 
       	onsubmit="return statCacheVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>区服Id:</B>*&nbsp;</td>
						 	<td>$server&nbsp;</td>
						 	<td><B>操作账号:</B>*&nbsp;</td>
						 	<td>$account</td> 
						 </tr>
						 <tr> 
		                 	<td><B>开始时间:</B>*&nbsp;</td>
						 	<td>$startTime</td>
						 	<td><B>结束时间:</B>*&nbsp;</td>
						 	<td>$endtime</td>
						 	<td>$submitBtn</td>
						</tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}
//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

public static function fruitItemConfigHtml( )
{
	$style ="style='margin:0px';";
	$maxidBtn = '';
	// 道具ID
	$itemId ="<input type='text'   class='input-mini'  name='itemId' >";
	// 道具名称
	$itemName ="<input type='text'   class='input-mini' name='itemName' >";
	// 道具创建开始日期
	$startTime ='<input type="text" class="datetimepicker form-control"
	 name="startTime" style="width:auto;"/>';
	// 道具创建结束日期$lasttim = date('Y-m-d H:i:s',time());
	$endtime = "<input type='text' class='datetimepicker form-control'
	name='endtime' style='width:auto;'/>";	
	// 道具类型
	$itemType ="<select style='width:160px;' tabindex='4' name='itemType'>
	          <option value='0'>红包</option> 
	          <option value='1'>话费</option>
			  <option value='2'>实物</option>
	        </select>";
	$account =
	'<input  name="account" class="input-mini" style="width:auto;" type="text">';
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal"
       	onsubmit="return statCacheVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>道具ID:</B>*&nbsp;</td>
						 	<td>$itemId&nbsp;</td>
						 	<td><B>道具名称:</B>*&nbsp;</td>
						 	<td>$itemName</td>
						 </tr>
						 <tr>
		                 	<td><B>开始时间:</B>*&nbsp;</td>
						 	<td>$startTime</td>
						 	<td><B>结束时间:</B>*&nbsp;</td>
						 	<td>$endtime</td> 
						</tr>
						 <tr>
		                 	<td><B>道具类型:</B>*&nbsp;</td>
						 	<td>$itemType</td> 
						 	<td>$submitBtn</td>
						</tr>
						 				
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}

public static function fruitItemPrizesHtml()
{
	$style ="style='margin:0px';";
	$maxidBtn = '';
	// 道具配置ID
	$itemId ="<input type='text'   class='input-mini'  name='itemId' >";
	// 道具名称
	$itemName ="<input type='text'   class='input-mini' name='itemName' >";
	 
	// 道具类型
	$itemType ="<select style='width:160px;' tabindex='4' name='itemType'>
	          <option value='0'>红包</option>
	          <option value='1'>话费</option>
			  <option value='2'>实物</option>
	        </select>";
	$account =
	'<input  name="account" class="input-mini" style="width:auto;" type="text">';
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal"
       	onsubmit="return statCacheVerify(this);" >
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>道具配置ID:</B>*&nbsp;</td>
						 	<td>$itemId&nbsp;</td>
						 	<td><B>道具名称:</B>*&nbsp;</td>
						 	<td>$itemName</td>
						 </tr>
						  
						 <tr>
		                 	<td><B>道具类型:</B>*&nbsp;</td>
						 	<td>$itemType</td>
						 	<td>$submitBtn</td>
						</tr>
						
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}
//

public static function getplatListInfo($stytle=NULL,$putId='platById')
{
	$platOut  = System_Service::getplatformInfo();
	$platOptionHtml = '';
	$OptionHtml='<option value=0>--请选择平台--</option>';
	foreach ($platOut as $var)
	{ 
		$platinfo[$var['id']] = $var['name'];
	}
	//
	foreach ($platinfo as $key=>$var)
	{
		$OptionHtml.="<option value={$key}>".$var.'</option>';
	}
	if (empty($stytle)){
		$stytle = 'width:120px';
	}
	$selecttype ="
	<select name='platId' data-plat='platId' id='{$putId}' 
	class='form-control' style='{$stytle}' title='*设置平台类型'>
	{$OptionHtml}
	</select>";

	return  $selecttype;
}

// 用户头像审核
public static function userPhotos()
{
	$platHtml = self::getplatListInfo(NULL,'PlatUserPhotosId');
	 
	$ServerIdHtml = "<select name='serverId' id='allclickserver' 
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装 onsubmit="return statCacheVerify(this);" 
	$html = <<<EOF
	
       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" method="POST" class="form-horizontal">
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>平台类型:</B>*&nbsp;</td>
						 	<td>$platHtml</td>
						 	<td>&nbsp;&nbsp;</td>
						 	<td>$ServerIdHtml</td>
						 	<td>$submitBtn</td>
						 </tr> 
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->
	
EOF;
	return $html;
	
}
// 活动
public static function ActivityHtml()
{
	$platHtml = self::getplatListInfo();

	$ServerIdHtml = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装 onsubmit="return statCacheVerify(this);"
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="ActivityIndex" id ="activityindexFormId" method="POST" class="form-horizontal">
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>平台类型:</B>*&nbsp;</td>
						 	<td>$platHtml</td>
						 	<td>&nbsp;&nbsp;</td>
						 	<td>$ServerIdHtml</td>
						 	<td>$submitBtn</td>
						 </tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}
// 全局活动
public static function GlobalActivityHtml()
{
	$lasttim = date('Y-m-d H:i:s',time());
	
	$platHtml = self::getplatListInfo();

	$ServerIdHtml = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	
	//  全局活动开始日期
	$startTime ='<input type="text" class="datetimepicker form-control"
	 name="startTime" style="width:auto;" value="2017-06-07 10:00:00"/>';
	//  全局活动结束日期
	$endtime = "<input type='text' class='datetimepicker form-control'
	name='endtime' style='width:auto;' value='{$lasttim}'/>";
	
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装 onsubmit="return statCacheVerify(this);"
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="Index" id ="activityindexFormId" method="POST" class="form-horizontal">
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>开始时间:</B>*&nbsp;</td>
						 	<td>$startTime</td>
						 	<td><B>截止时间:</B>*&nbsp;</td>
						 	<td>$endtime</td>
						 	<td>$submitBtn</td>
						 </tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;
}
public static function MallHtml()
{
	$platHtml = self::getplatListInfo();

	$ServerIdHtml = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	//组装 onsubmit="return statCacheVerify(this);"
	$html = <<<EOF

       <!-- 查询组件 begin-->
	   <div class="widget-box">
	   		<div class="widget-title" id="titl">
				<span class="icon">
					<i class="icon-search"></i>
				</span>
				<h5>设置条件</h5>
			<div class="buttons">
		</div>
		</div>
	    <div class="widget-content">
        <form  action ="index" id ="activityindexFormId" method="POST" class="form-horizontal">
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
							<td><B>平台类型:</B>*&nbsp;</td>
						 	<td>$platHtml</td>
						 	<td>$submitBtn</td>
						 </tr>
					</table>
                 </div>
             </div>
        </form>
    </div>
</div>
</div>
  <!-- 查询组件 end-->

EOF;
	return $html;

}
public static function ActivityTableHtml($ifglobal=false)
{
	
	 $thead = '
 		<thead>
		<tr>
		<th>活动编号</th>
		<th>平台编号</th>
		<th>区服</th>
		<th>活动名称</th>
		<th>活动描述</th>
		<th>活动类型</th>
		<th>活动开始时间</th>
		<th>活动截止时间</th>
		<th>活动关闭时间</th>
		<th>重置时间类型</th>
		<th>重置时间点</th>
		<th>创建日期</th>
		<th>规则</th>
		<th>状态</th>
		<th>操作</th>
		</tr>
		</thead>
 		';
	if ($ifglobal==true){
		
		$thead = '
 		<thead>
		<tr>
		<th>平台Id</th>
		<th>活动编号</th>		
		<th>活动名称</th>
		<th>活动描述</th>
		<th>活动类型</th>
		<th>活动开始时间</th>
		<th>活动截止时间</th>
		<th>活动关闭时间</th>
		<th>重置时间类型</th>
		<th>重置时间点</th>
		<th>创建日期</th>
		<th>规则</th>
		<th>状态</th>
		<th>操作</th>
		</tr>
		</thead>
 		';
	}
	$html = <<<EOF
 	{$thead}
EOF;
return $html; 
}

public static function MallTableHtml()
{
	$thead = '
 		<thead>
			<tr>
				<th>id</th>
				<th>平台编号</th>
				<th>区服</th>
				<th>活动名称</th>
				<th>活动描述</th>
				<th>活动开始时间</th>
				<th>活动截止时间</th>
				<th>创建日期</th>
				<th>规则</th>
				<th>类型</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
 		';
	$html = <<<EOF
 	{$thead}
EOF;
 	return $html;
}
// item 组件
public static function ItemConfigHtml($itemListObject)
{
	$itemlistliHtml = NULL;
	
	if (!empty($itemListObject)){
		
		foreach ($itemListObject as $invar)
		{
			$itemId = $invar['id'];
			$itemName = trim($invar['name']);			;
			$itemlistliHtml .= "<li><span style='color: #08c'>{$itemName}</span>							 
			<div style='margin-top: 5px'>
				<input type='text'  style='width: 30px;height:12px'  
				id='itemnumbers' placeholder='数量' 
				data-name='itemnumbers{$itemId}'/>&nbsp;
				<input name='idd[{$i}]' data-key='checkbox'  
				data-name='{$itemName}' 
				value='{$itemId}'  id='editUser'  
				type='checkbox' style='padding: 0px;margin:0px'/></div> 
			</li>";
			$i++;
		}
	} 
	$thead = '
 		<!--addNotice Modal Bengin-->
<div class="modal fade" id="addloginNoticeModal" tabindex="-1" role="dialog" aria-labelledby="addloginNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addloginNoticeModalLabel">道具配置</h4>
            </div> 
 			<div id="itemresetid2"></div>	
			<div class="modal-body">
			<?php  $platformOut = Platfrom_Service::get_plat_server();?>
			<?php  $itemInfo =null;$i= 0;?> 
			<div class=""> 
			<div class="span6" >
					<div class="widget-box">
						<div class="widget-title">				 
							<h5>待选区</h5>
						</div>
						<div class="widget-content" id="iteminfoSetUl" style="height: 200px;
						border:1px solid #CCCCCC;overflow-y:scroll; overflow-x:scroll;">						
							<form action="CreateActivityIndex" id="ItemListForm" method="post">
							<input type="hidden" name="itemOptionBtn">
							<select name="itemtype" id="ItemtypelistId">
							<option value=0>--请选择道具类型--</option>
							<option value=1>表情</option>
							<option value=2>装备</option>
							<option value=3>道具</option>
							<option value=5>套装</option>
							</select>
							</form>
							<ul class="activity-list" id="iteminfosetId" class="left"> 
							'.$itemlistliHtml.'
							</ul>
						</div>
					</div>
				</div>
					<div class="span6">
					<div class="widget-box">
						<div class="widget-title"> 
							<h5>已选区</h5>
						</div>
						<textarea rows="10"  style="width: 225px;margin-bottom:0px" cols="" 
						class="widget-content" name="itemInfo" id="itemInfo"></textarea> 
					</div>
				</div>
				
			</div>
			</div>
 			<div class="modal-footer">
                <button type="button" class="btn btn-primary" id=addIteminfocfg>确认增加道具</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
		</div>	
	</div>
</div>
 		';
	$html = <<<EOF
 	{$thead}
EOF;
 	return $html;
}
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
}

?>
