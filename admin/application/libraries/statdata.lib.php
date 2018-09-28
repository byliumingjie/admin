<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
class Statdata_Lib  extends Page_Lib{

	//动态表单元素
 
public static function DynamicForms(){
	 
	$scriptjava = '	
	<script> 
	window.onload = function(){
		var delFromid = document.getElementById("delFromid");
		delFromid.style.display = "none";
	}
	$(document).ready(function() {
	   
		$("#addFromid").bootstrapValidator({ 
			message: "This value is not valid",
			feedbackIcons: {
				valid: "glyphicon glyphicon-ok",
				invalid: "glyphicon glyphicon-remove",
				validating: "glyphicon glyphicon-refresh"
			},
			fields: {
				firstName: {
					validators: {
						notEmpty: {
							message: "The first name is required and cannot be empty"
						}
					}
				},
				lastName: {
					validators: {
						notEmpty: {
							message: "The last name is required and cannot be empty"
						}
					}
				}
			}
		}); 
	});
	
	//以下代码是动态添加表单元素。
 
	var delFromid = document.getElementById("delFromid"); 
	var cont = 0;
    //动态增加表单元素。
    //输送餐品可以动态生成样式通过设定  
    function AddElement(mytype){     
        //得到需要被添加的html元素。  冗余
        var TemO=document.getElementById("add"); 
	 	var Tem=document.getElementById("add");
		cont>=0?delFromid.style.display = "block":delFromid.style.display = "none";//删除元素
		
        //创建一个指定名称（名称指定了html的类型）html元素。
        var fontHtml  = document.createElement("font");  
        var newInput = document.createElement("input");
        var litype = document.createElement("li");        
	    var newInpu  = document.createElement("input");
	    var newInput2 = document.createElement("input");
	    
	    // by and bd
  		var newline = document.createElement("br"); 		
		var newlin = document.createElement("bd");
		
	    if( cont>=14)
  		{
			alert("区间添加长度冗余最大为15");			
			window.history.go(-1);
			return false;
		}
		cont = cont +1 ;    
          
        //指定input的类型。  
        newInput.type=mytype;      
        newInpu.type=mytype;  
        newInput2.type=mytype;
        
        fontHtml.id="fontFrom"+(cont);
		fontHtml.name="fontFrom"+"["+(cont)+"]";
		 	
		cont<=9?fontHtml.innerHTML="区间"+cont+":  ":fontHtml.innerHTML="区间"+cont+":";		
		 
		litype.id="liFrom"+(cont);
		litype.name="liFrom"+"["+(cont)+"]";
		litype.innerHTML="";
		
		litype.className="icon-th";  
        // 动态生成id。  
        newInput.id ="FirstFrom"+(cont); 
		newInput.name="FirstFrom"+"["+(cont)+"]";
		newInput.style.width="7%";
		newInput.placeholder = "RmbIntervalStart";
		// newInput
		newInpu.id="secondFrom"+(cont);    
        newInpu.name="secondFrom"+"["+(cont)+"]"; 
		newInpu.style.width="7%"; 
		newInpu.placeholder = "RmbIntervalEnd";
		// newInput2
		newInput2.id="put"+(cont);    
        newInput2.name="grandTotal"+"["+(cont)+"]"; 
		newInput2.style.width="7%"; 
		newInput2.placeholder = "grand total";
		 
		Tem.appendChild(fontHtml); 
        TemO.appendChild(newInput);
        Tem.appendChild(litype);     
		Tem.appendChild(newInpu);
		Tem.appendChild(newInput2); 
		//br 
        Tem.appendChild(newline);
		Tem.appendChild(newlin); 
    }     
      
    //动态删除表单元素。     
    function delElement(mytype){         
        var TemO=document.getElementById("add");
        
        cont<=1?delFromid.style.display = "none":delFromid.style.display = "block";//删除元素        
        if (cont>0)
        {    
            var ElemBr =   document.getElementById("br"+cont);
            var ElemBr2 =   document.getElementById("br2"+cont); 
            var fontFrom = document.getElementById("fontFrom"+cont);
            var newInput = document.getElementById("FirstFrom"+cont);
            var liFrom = document.getElementById("liFrom"+cont);
            var Elemput2 = document.getElementById("put"+cont);
            var newInpu = document.getElementById("secondFrom"+cont);
            TemO.removeChild(liFrom); 
            TemO.removeChild(newInput);   
     		TemO.removeChild(fontFrom);
     		TemO.removeChild(Elemput2);
            TemO.removeChild(newInpu);
            cont = cont - 1; 
        }   
		
    }     	 
	</script>';    	 
	return $scriptjava; 
}
public static  function function_statData_script($clickByid="type",
$lookbyid="lookstatusid",$optionbyid="optiontimid",$value='default') 
	{
		$scriptjava = "
	        <script>
	        window.onload = function()
	        {        
	        	 
				var obj_select = document.getElementById('{$clickByid}');
				var obj_div = document.getElementById('{$lookbyid}');
				var obj_replacetype = document.getElementById('{$optionbyid}');			
				
				if(obj_select.value==1 || obj_select.value=='{$value}'){				 
					obj_div.style.display = 'block';
					obj_replacetype.style.display = 'none';
				}else{				 	
	    			obj_div.style.display = 'none';
					obj_replacetype.style.display = 'block';
	    		} 
				obj_select.onchange = function(){
					obj_div.style.display = this.value==1? 'block' : 'none';
					obj_replacetype.style.display = this.value==2? 'block' : 'none';
				}
				 			
			} 
	        </script>"; 
		return $scriptjava;
	}
public static  function function_statData_script2($clickByid="",$lookbyid="",$optionbyid="",$value='') 
	{
		$scriptjava = "
	        <script>
	        window.onload = function()
	        {    
				var obj_select = document.getElementById('{$clickByid}');
				var obj_div = document.getElementById('{$lookbyid}');
				var obj_replacetype = document.getElementById('{$optionbyid}');			
				
				if(obj_select.value==1 || obj_select.value=='{$value}'){				 
					obj_div.style.display = 'block';
					obj_replacetype.style.display = 'none';
				}else{				 	
	    			obj_div.style.display = 'none';
					obj_replacetype.style.display = 'block';
	    		} 
				obj_select.onchange = function(){
					obj_div.style.display = this.value==1? 'block' : 'none';
					obj_replacetype.style.display = this.value==2? 'block' : 'none';
				}
			} 
	        </script>"; 
		return $scriptjava;
	}
	
// ---------------------------------------------------------------------------------------------------------
//事物提交
public static function readySubmit($clickID='',$sumitID='')
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
//日常数据统计html
public static function statDataOptionsHtml($time,$type='') 
{    	 
    	$scriptjava = self::function_statData_script();
    	$btnType = '';    
    	$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
    	if(isset($time))
    	{  
    		 
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="button"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';	 
    		$time = implode(',',$time); 
    	} 
    	# 服务器类型
    	 
		$servertype = '';
        # 区服编号
    	$regionIdHtml = '<input type="text" value="1" size="10" name="regionid" placeholder="区服编号" title="*区服编号"/>';         
        # 选择条件 
        $selecttype = '<select name="servertype" id ="type" class="form-control" style="width:100px" title="*设置类型">';
        # 周期区间        
        $selecttype.= '
        <option value="0">--请选择--</option>
        <option value="2">时间区间</option>
        <option value="1">周期区间</option></select>';
        # 首末时间        
      	$optionTimeHtml = '<div id ="optiontimid"><i class="icon-th "></i> 
      	<input type="hidden" name="lookstatus" value="custom">
      	<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" style="width:auto;"/>';    
      	$optionTimeHtml.= ' — <input type="text" class="datetimepicker form-control"   
      	placeholder="截止时间" name="endtime" style="width:auto;"/></div>';
      	# 统计类型
      	$lookstatusHtml = ' <div id="lookstatusid">
      	统计类型:<input  checked="true" type="radio" value="day" name="lookstatus"/> 
      	日  <input  name="lookstatus" type="radio" value="week" /> 周';
      	$lookstatusHtml.= ' <input   type="radio" value="month" name="lookstatus"/> 月 </div>';
      	//$data = isset($object)?$object:0;  
      	//组装
      	$html = <<<EOF
      			{$readySubmitJs}
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
									{$btnType}				 
								</div>	
							</div>
	    <div class="widget-content">			
        <form  method="POST" class="form-horizontal" onsubmit="return StatdataPutVerify(this);" >         
                <div class="control-group">
			<div class="controls">
					<table border=0>
						<tr>
						 <td>{$servertype}</td>
						 <td>{$scriptjava}</td>
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td>						  
		                 <td>{$optionTimeHtml}</td>		         
		                 <td><i class="icon-th "></i></td>
		                 <td>{$lookstatusHtml}</td>                 
		                 <td><button class="btn btn-primary btn-xs ladda-button" 
						 data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						 <span class="ladda-label"> 查询</span></button></td>
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
// ---------------------------------------------------------------------------------------------------------
public static function StatRechargeGoldHtml($time="",$dau="",$register="",$payrole="",$payal="",$type = "",$contentType ="",$totalPay="") 
{    	 
  		 /*添加动态表单动态生成以及删除*/ 	
    	$scriptjava = self::DynamicForms();
    	$js_path = self::getJsHost();
    	$readySubmitJs = self::readySubmit('btn_date',"from");
    	$btnType = '';
    	if($time!="" && $contentType == 1)
    	{  
    		$action = "payExportfile";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" class="btn btn-success" value="导出Execl" />';	    	
    		$register = !empty($register)?$register:array(0=>"空");    		    		 
	    	foreach($register as $var)
			{  	
				foreach($var as $var)
				{             	
						$registString .= $var['datetime'].'='.$var['roleid'].'='.$var['iworld'].','; 
				} 	                
			} 	    	 
	    	$dau = !empty($dau)?$dau:array(0=>"空");
	    	foreach($dau as $var)
			{  	
				foreach($var as $var)
				{             	
						$dauString .= $var['datetime'].'='.$var['roleid'].'='.$var['iworld'].',';
				} 	                
			}
			$payrole = !empty($payrole)?$payrole:array(0=>"空");
    		foreach($payrole as $var)
			{  	
				foreach($var as $var){             	
						$payroleString .= $var['order_time'].'='.$var['money'].'='.$var['rolenum']."=".$var['user_sid'].',';
				} 	                
			}
			$payal = !empty($payal)?$payal:array(0=>"空");
			foreach($payal as $var)
			{  	
				foreach($var as $var){             	//
						$payalString .= $var['order_time'].'='.$var['order_money'].'='.$var['order_roleid'].'='.$var['user_sid'].',';
				} 	                
			} 			
			$time = implode(',',$time); 
    	}
    	if($contentType == 2)
    	{
    		$action = "IntervalExport";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" class="btn btn-success" value="导出Execl" />';
    		$scalePay = $dau;
    		$Interval = $register;
    		$time = implode(',',$time); 
    		foreach ($payrole as $inpayrole)
    		{	
    			foreach($inpayrole as $var) 
    			{
    				$payroleString .= $var['order_roleid'].'='.$var['order_money'].'='.$var['uin'].'='.$var['level'].'='.$var['strName'].'='.$var['updatetime'].'='.$var['entry']."=".$var['worldID'].",";
    			}
    		}
    		foreach ($totalPay as $inalpayrole)
    		{	
    			foreach($inalpayrole as $var) {
    				$payalString .= $var['order_roleid'].'='.$var['order_money'].'='.$var['uin'].'='.$var['level'].'='.$var['strName'].'='.$var['updatetime']."=".$var['entry']."=".$var['worldID'].",";    				
    			}
    		}
    		 
    		foreach ($scalePay as $inscalepay)
    		{	
    			foreach($inscalepay as $var) 
    			{ 
    				if(!$var['user_sid']){
    					continue;
    				}
					$payscaleStr.= $var['money'].'='.$var['controle'].'='.$var['entry'].'='.$var['user_sid'].","; 
    			}
    		} 
    	} 
		// 区服编号
    	$regionIdHtml = '
    	<textarea type="text" style="height:20px" value="1" class="newInput"
    	name="regionid" placeholder="区服编号" title="*区服编号"></textarea>';         
		// 选择条件 sidSpace1 sidSpace2
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">';
		// 服务器类型
        $selecttype = self::selectregionType($type); 
		// 日期范围
      	$startTime = '
      	<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" style="width:auto;"/>';    
      	$endTime= '
      	<input type="text" class="datetimepicker form-control"   
      	placeholder="截止时间" name="endtime" style="width:auto;"/>';
		// 提交事物
		$selectbut = ' 
      	<button class="btn btn-primary btn-xs ladda-button" 
      	data-style="expand-right" name="sub_btn" type="button" id="btn_date">
		<span class="ladda-label"> 查询</span></button>';
      	$startInterval = '';
      	$endInterval ='';
		// 添加区间
      	$updateFrom = '<button class="btn btn-success"  type="button"  
		style="padding:0;width:100px" id="addFromid" name ="addfrom" 
		onclick = "AddElement(\'text\')">
		<i class="icon-plus icon-white"></i> 添加区间</button>';
		// 删除区间
      	$delFrom = '<button class="btn btn-danger" 
		id="delFromid"  type="button" name ="delfrom" 
		onclick = "delElement(\'text\')" style="padding:0;width:100px;">
		<i class="icon-minus icon-white"></i>删除</button>';
      	$data = isset($object)?$object:0;  

		$selectbut = ' 
      	<button class="btn btn-primary btn-xs ladda-button" style="padding:0;width:100px"
      	data-style="expand-right" name="sub_btn" type="button" id="btn_date">
		<span class="ladda-label"> 查询</span></button>';

      	//组装
      	$html = <<<EOF
      				<script src='{$js_path}/bootstrapValidator.js'></script>
					{$readySubmitJs}
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">																									 
    								<form  action="{$action}" method="POST"> 								
										 <input  type="hidden" name="time" value="{$time}"/>
									     <input  type="hidden" name="register" value="{$registString}"/>
									     <input  type="hidden" name="dau" value="{$dauString}"/>
									     <input  type="hidden" name="payrole" value="{$payroleString}"/>
									     <input  type="hidden" name="payal" value="{$payalString}"/>
									     <input  type="hidden" name="type" value="{$type}"/>  
									     <input  type="hidden" name="contentType" value="{$contentType}"/>
									     <input  type="hidden" name="payscaleStr" value="{$payscaleStr}"/>
      									<div>
									     <table border=0>
								         <tr>
								        	<td>{$delFrom}</td>				
								        	<td>{$updateFrom}</td>								        	
								        	<td>{$selectbut}</td>
								        	<td>{$btnType}</td>		
								         </tr>
								         </table> 
								         </div>          
									</form>    							 
								</div>	
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" 
		onsubmit="return statdataVerify(this);" id="from">         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型&nbsp:</B></td>
						 <td>{$selecttype}</td>
						 <td>&nbsp<B>区服编号&nbsp:</B>&nbsp</td>				  
						 <td>{$regionIdHtml}</td> 
						</tr>
						<tr>
						 <td><B>开始时间&nbsp:</B>&nbsp</td>					  
		                 <td>{$startTime}</td>
		                 <td>&nbsp<B>截止时间&nbsp:</B>&nbsp</td>
						 <td>{$endTime}</td> 
						</tr>
						<tr>
						 <td><B>区服起始&nbsp:</B></td>
						 <td>{$sidSpace1}</td>
						 <td>&nbsp<B>区服截止&nbsp:</B></td>				  
						 <td>{$sidSpace2}</td> 
						</tr>	 
					</table> 					
					<p></p>
					<div class="form-group" id="add">
					
					<div>							                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
}
// 
//日期差（流失率）
public static function StatjetLagDay($startTime,$endtime)
{  
		$endtime = strtotime(date('Y-m-d',strtotime($endtime)));
		$startTime = strtotime(date('Y-m-d',strtotime($startTime)));			 
		$IntervalDays = $endtime - $startTime;																
		$DayNumber = ceil($IntervalDays/3600/24);	
		 	
		return $DayNumber;		
} 
	
/*
 * 充值排行Html Element
 * */
public static function StatRechargeRankingHtml($stattime="",$endtime="",$type="",$regionid="",
$payRankingobj="",$onlineInfoobj="") 
{
	 
    	$js_path = self::getJsHost();
    	
    	$btnType = '';
    	if( $contentType == 1)
    	{  
    		$action = "payExportfile";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" />';	    	
    		$register = !empty($register)?$register:array(0=>"空");    		    		 
	    	foreach($register as $var)
			{  	
				foreach($var as $var)
				{             	
						$registString .= $var['datetime'].'='.$var['roleid'].'='.$var['iworld'].','; 
				} 	                
			} 	    	 
	    	$dau = !empty($dau)?$dau:array(0=>"空");
	    	foreach($dau as $var)
			{  	
				foreach($var as $var)
				{             	
						$dauString .= $var['datetime'].'='.$var['roleid'].'='.$var['iworld'].',';
				} 	                
			}
			$payrole = !empty($payrole)?$payrole:array(0=>"空");
    		foreach($payrole as $var)
			{  	
				foreach($var as $var){             	
						$payroleString .= $var['order_time'].'='.$var['money'].'='.$var['rolenum']."=".$var['user_sid'].',';
				} 	                
			}
			$payal = !empty($payal)?$payal:array(0=>"空");
			foreach($payal as $var)
			{  	
				foreach($var as $var){             	//
						$payalString .= $var['order_time'].'='.$var['order_money'].'='.$var['order_roleid'].'='.$var['user_sid'].',';
				} 	                
			} 			
			$time = implode(',',$time); 
    	} 
    	$regionIdHtml = '
    	<textarea type="text" style="height:20px"  class="newInput"
    	name="regionid" placeholder="区服编号" title="*区服编号"></textarea>';         
        //选择条件 
        $selecttype =   self::selectregionType($type);        
      	$optionTimeHtml = '<div id ="optiontimid"><i class="icon-th "></i> <input type="text" class="datetimepicker form-control" placeholder="开始时间"  name="startTime" style="width:auto;"/>';    
      	$optionTimeHtml.= ' — <input type="text" class="datetimepicker form-control"   placeholder="截止时间" name="endtime" style="width:auto;"/></div>';
      	$startInterval = '';
      	$endInterval ='';
      /*	$updateFrom = '<button class="btn btn-success"  type="button"  style="width:100px" id="addFromid" name ="addfrom" onclick = "AddElement(\'text\')"><i class="icon-plus icon-white"></i> 添加区间</button>';*/
      	$delFrom = '<button class="btn btn-danger" id="delFromid"  type="button" name ="delfrom" onclick = "delElement(\'text\')" style="width:100px;"><i class="icon-minus icon-white"></i>删除</button>';
      	$data = isset($object)?$object:0;  
      	//组装
      	$html = <<<EOF
      				<script src='{$js_path}/bootstrapValidator.js'></script>
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">																										 
    								<form  action="{$action}" method="POST"> 								
										 <input  type="hidden" name="time" value="{$time}"/>
									     <input  type="hidden" name="register" value="{$registString}"/>
									     <input  type="hidden" name="dau" value="{$dauString}"/>
									     <input  type="hidden" name="payrole" value="{$payroleString}"/>
									     <input  type="hidden" name="payal" value="{$payalString}"/>
									     <input  type="hidden" name="type" value="{$type}"/>  
									     <input  type="hidden" name="contentType" value="{$contentType}"/>
									     <input  type="hidden" name="payscaleStr" value="{$payscaleStr}"/>
      									<div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>          
									</form>    							 
								</div>	
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statdataVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td>						  
		                 <td>{$optionTimeHtml}</td>		                 
		                 <td><button class="btn btn-primary btn-xs ladda-button" style="padding:0;width:100px"
						 data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						 <span class="ladda-label"> 查询</span></button></td>
		                
						</tr>
					</table> 					
					<p></p>
					<div class="form-group" id="add">
					
					<div>							                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
}
/*
 * 等级滞留分布Html Element
 * */
public static function statGradeRemainHtml($type='',$startTime='',$endTime='',
$statInter='',$remainnter='',$regionid='',$userRemainNum='',$remainNum='',$userNumber='',
$remainNumber='',$object='') 
{ 
    	$currentTime =date('Y-m-d H:i:s',time());    	
    	$btnType = ''; 
		$currentTime =date('Y-m-d H:i:s',time());    	
    	$btnType = ''; 
    	$GradeRemain = array();
    	if(!empty($type))
    	{ 			   
    		$action = "ExportfileGradeRemain";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" />';	    	
    		//$register = !empty($register)?$register:array(0=>"空");    		    		 
	    	 
    		for($i=1;$i<=80;$i++){
    			$UserNum = 0; 
    			//用户数
      			foreach($userRemainNum as $Indata)
      			{
      				foreach($Indata as $var)
      				{
      					if($i == $var['ilevel'] )
      					{
      						$UserNum = $var['cont'];
      						 
      					} 
      				}	
      			}
      			//滞留数
      			$RemainNum = 0;
      			foreach($remainNum as $Inremaindata)
      			{
      				foreach($Inremaindata as $vad)
      				{
      					if($vad['ilevel'] == $i)
      					{
      						$RemainNum = $vad['cont']; 
      					}   					
      				}	
      			}
      			
      			//玩家比例
      			$userNumscale = round(($UserNum/$userNumber)*100,2);
      			$userscaleNumber += $userNumscale; 
      			//滞留比例
    			$RemainNumscale = round(($RemainNum/$UserNum)*100,2);
    			$RemainscaleNumber += $RemainNumscale;
    			//echo $UserNum."<br>";
    			$gradeRemain .= $i.'='.$UserNum.'='.
    			$userNumscale.'='.$RemainNum.'='.$RemainNumscale.',';
				// 合计 
				//$GradeRemain = array($UserNum,$userNumscale,$RemainNum,$RemainNumscale);
    		}   	
			
			$gradeRemain.= "合计"."=".$userNumber.'='.
			$userscaleNumber.'='.$remainNumber.'='.$RemainscaleNumber; 
    	}

    	$regionIdHtml = '
    	<input type="text" style="height:20px;width:90px"  class="newInput"
    	name="regionid" placeholder="区服编号" size="10" title="*区服编号">'; 
    	        
        # 选择条件 
        $selecttype = self::selectregionType($type);                
      	$optionTimeHtml = '  
      	<input type="text" class="datetimepicker form-control"placeholder="注册开始时间" 
      	name="startTime" style="width:auto;"/>';    
      	$endoptionTimeHtml.= '<input type="text" class="datetimepicker form-control" placeholder="注册截止时间" 
      	name="endtime" style="width:auto;"/> ';
      	# 统计时间
      	$statInterval = '<input type="text"  class="datetimepicker form-control"  placeholder="统计时间" 
      	name="statInterval" style="width:auto;" value="'.$currentTime.'" tile="默认当前时间"/>';
      	# 滞留条件      	
      	$remainnterval ='<input type="text" size="10" value = "1" class="newInput" placeholder="滞留条件" 
      	name="remainnterval" style="width:auto;" title="默认大于等于1天"/>'; 
      	//组装
      	$html = <<<EOF
      				 
            	<!-- 查询组件 begin-->
				   <div class="widget-box">				   
				   	<div class="widget-title">
								<span class="icon"> 
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5> 
							<div class="buttons">
    						<form  action="{$action}" method="POST"> 								
								 <input  type="hidden" name="startTime" value="{$startTime}"/>
							     <input  type="hidden" name="endtime" value="{$endTime}"/>
							     <input  type="hidden" name="sid" value="{$regionid}"/>
							     <input  type="hidden" name="gradeRemain" value="{$gradeRemain}"/>							     
							     <input  type="hidden" name="type" value="{$type}"/>
      						<div><table border=0><tr><td>{$btnType}</td></tr></table></div>          
							</form>    							 
						</div>	
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statGradeRemainVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:</B>&nbsp</td>
						 <td>{$selecttype}</td>
						 <td>&nbsp<B>区服编号:</B>&nbsp</td>					  
						 <td>{$regionIdHtml}</td>
						 <td></td>
						 <td></td>
						 </tr>
						 <tr>  
						 <td><B>开始时间:</B>&nbsp</td>		  
		                 <td>{$optionTimeHtml}</td>
		                 <td>&nbsp<B>截止时间:</B>&nbsp</td>
		                 <td>{$endoptionTimeHtml}</td>
		                 <td></td>		                 
		                 <td></td>		                
						</tr>
						<tr>
						  <td>&nbsp&nbsp<B>未 登 录:</B>&nbsp</td>						 
						 <td>{$remainnterval}&nbsp天</td>	
						 <td>&nbsp<B>统计时间:</B>&nbsp</td>					  
						 <td>{$statInterval}&nbsp*统计日期不能小于注册截止日期</td>
						 <td><button class="btn btn-primary btn-xs ladda-button" 
						 data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						 <span class="ladda-label"> 查询</span></button></td>
						 <td></td>
						 </tr>
					</table> 					
					<p></p>
					<div class="form-group" id="add">
					
					<div>							                        
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
// 货币滞留
public static function statHtml($time="",$type="",$output="",$consume = "",$RetentionRate = "") 
{     	
    	$currentTime =date('Y-m-d H:i:s',time()); #当前时间    	
    	$btnType = '';
    	 
    	$regionIdHtml = '
    	<input type="text" style="height:20px"  class="newInput"
    	name="regionid" placeholder="区服编号" size="10" title="*区服编号">'; 
    	        
        # 选择条件 
        $selecttype =  self::selectregionType($type);                
      	$optionTimeHtml = '<div id ="optiontimid"><i class="icon-th "></i> 
      	<input type="text" class="datetimepicker form-control"placeholder="开始时间" 
      	name="startTime" style="width:auto;"/>';    
      	$optionTimeHtml.= ' — <input type="text" class="datetimepicker form-control" placeholder="截止时间" 
      	name="endtime" style="width:auto;"/></div>'; 
      	# 滞留条件 
    	$logTypeConfig = self::log_type_config();
    	$remainnterval =$logTypeConfig[0]; 
      	//组装
      	$html = <<<EOF
      	      				 
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>								 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statMoneryRemainVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td>
						 <td><i class="icon-th "></i></td>
						 <td>{$remainnterval}</td>						  
		                 <td>{$optionTimeHtml}</td>
		                 <td><i class="icon-th "></i></td>		                 		                 
		                 <td> 
		                 <button class="btn btn-primary btn-xs ladda-button" 
						 data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						 <span class="ladda-label"> 查询</span></button>
		                 </td>		                
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
// 分时注册
public static function statGraphRegisterHtml($strjson="") 
{       
    	$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	
    	$btnType = '';
    	$data = '';
    	if (isset($strjson) && !empty($strjson)){
    		 
    	} 
    	$regionIdHtml = '
    	<input type="text" style="height:20px"  class="newInput"
    	name="regionid" placeholder="区服编号" size="10" title="*区服编号">';    	        
        # 选择条件 
        $selecttype = self::selectregionType($type);;  
      	//组装
      	$html = <<<EOF
      	      				 
            	  
            	  {$data}
            	  
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>								 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statMoneryRemainVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr> 
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td> 
		                 <td><i class="icon-th "></i></td>		                 		                 
		                 <td> 
		                 <button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button>
		                 </td>		                
						</tr>
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
}

// 总体在线数据
public static function statOverallOnlineHtml($time,$type,$PCU,$ACU,$pcuTimeTotal,
$pcuMonthTotal,$regionID)
{	
		$scriptjava = self::function_statData_script();
    	$btnType = '';    	
    	if(isset($PCU))
    	{      
    		$action = "pcuExportfile";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" />';
    		 	
	    	foreach($PCU as $var)
			{      
				$cont = (Int)$var['RoleNum'];         	
				$pcuString .= $var['RecordTime'].'='.$cont.',';
			}  
			foreach($ACU as $inacu)
			{
				$acuString .= $inacu[0].'='.$inacu[1].',';	
			} 
			
			$pcuTotalString .= $pcuTimeTotal['maxOnline'].'='.$pcuTimeTotal['avgonline'].',';
			
			$acuTotalString .= $pcuMonthTotal['maxOnline'].'='.$pcuMonthTotal['avgonline'].',';
    		 
			$time = implode(',',$time);
    	} 
    	# 服务器类型
    	$servertype = self::selectregionType($type);;
        # 区服编号
    	$regionIdHtml = 
    	'<input type="text" value="1" size="10" name="regionid" 
    	placeholder="区服编号" title="*区服编号"/>';         
        # 选择条件 
        $selecttype ='
        <select name="type" id ="type" class="form-control" 
        style="width:100px" title="*设置类型">';
        # 周期区间        
        $selecttype.= '
        <option value="0">--请选择--</option>
        <option value="2">时间区间</option>
        <option value="1">周期区间</option>
        </select>';
        # 首末时间        
      	$optionTimeHtml = '<div id ="optiontimid"><i class="icon-th "></i> 
      	<input type="hidden" name="lookstatus" value="custom">
      	<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" style="width:auto;"/>';    
      	$optionTimeHtml.= ' — <input type="text" class="datetimepicker form-control"   
      	placeholder="截止时间" name="endtime" style="width:auto;"/></div>';
      	# 统计类型
      	$lookstatusHtml = '
      	<div id="lookstatusid">统计类型:
      	<input  checked="true" type="radio" value="day" name="lookstatus"/> 日  
      	<input  name="lookstatus" type="radio" value="week"/> 周
      	</div>';      
      	$data = isset($object)?$object:0;  
      	//组装
      	$html = <<<EOF
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="time" 
										 value="{$time}"/>
										 <input  type="hidden" name="regiontype" 
										 value="{$type}"/>
										 <input  type="hidden" name="sid" 
										 value="{$regionID}"/>
										 <input  type="hidden" name="pcu" 
										 value="{$pcuString}"/>
										 <input  type="hidden" name="acu" 
										 value="{$acuString}"/>
										 <input  type="hidden" name="pcuTotal" 
										 value="{$pcuTotalString}"/>
										 <input  type="hidden" name="acuTotal" 
										 value="{$acuTotalString}"/>
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>	
							</div>
	    <div class="widget-content">			
        <form  method="POST" class="form-horizontal" 
        onsubmit="return StatdataPutVerify(this);">         
                <div class="control-group">
			<div class="controls">
					<table border=0>
						<tr>
						 <td>{$servertype}</td>
						 <td>{$scriptjava}</td>
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td>						  
		                 <td>{$optionTimeHtml}</td>		         
		                 <td><i class="icon-th "></i></td>
		                 <td>{$lookstatusHtml}</td>                 
		                 <td> 
		                <button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button>
		                 </td>
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

// 图标库
/*
 * Labels常用属性有enabled、formatter、setp、staggerLines。
 * */
public static function ChartGallery( $strjson="",$ChannelOne="",
 $ChannelTwo="",$chartType = "",$timeFormat="",
$xAxis ="",$colors='',$strjson2='',$outId='',$charTitle='')
{
	if ($timeFormat)
	{
		$timeStatus = 1;
	}
	else{
		$timeStatus = 0;
	}
	# 数据展示
	$ChannelOne = empty($ChannelOne)?'Data1':$ChannelOne;
	$ChannelTwo = empty($ChannelTwo)?'Data2':$ChannelTwo;
	$chartType = empty($chartType)?'line':$chartType;
 	//$timeFormat = empty($timeFormat)?'%Y-%m-%d':'%Y-%m-%d %H:%M:%S';
 	$strjson2 = empty($strjson2)?$strjson:$strjson2;
 	$colors = empty($colors)
 	?
 	"#f6a328', '#058DC7', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', 
	'#FFF263', '#6AF9C4"
 	:$colors; 
 	//
 	$totip = "formatter: function() {
 			var dateTime = format(this.x,0);
 			return '<b>'+ this.series.name +'</b><br/>'+
 			dateTime +'<br/>'+
 			Highcharts.numberFormat(this.y, 2);
 	}";  	
 	if ($xAxis==1)
 	{
 		$xAxis =  
	 	'labels: {
	       formatter: function()
	       {
	          return Highcharts.dateFormat("%m-%d",this.value);	 		
	       }
	     },
	 	 tickPixelInterval:160,	 		
	     tickmarkPlacement:"on",';
 	}
 	if ($xAxis == 2){
 		
 		$xAxis = 
 		'type: "datetime",
	     tickPixelInterval: 160,
	 	 tickmarkPlacement:"on",';
 	}
 	if ($xAxis == 3)
 	{
 		$xAxis = "type:'category',
    	tickInterval:30,
    	uniqueNames: true ";
 		
 		$totip = "borderWidth: 0,
 		shared: true,
 		crosshairs: true,";
 	}
 	if ($xAxis == 4)
 	{
 		$xAxis = "type:'category'";
 		
 		$totip = "borderWidth: 0,
 		shared: true,
 		crosshairs: true,";
 	}
 	// xAxis
 	$xAxis = ($xAxis==1) 
 	?
 	"type:'category',
    tickInterval:180,
    uniqueNames: true ":$xAxis;
 	
 	
 	$yAxis = !empty($yAxis) ?
 	" allowDecimals: false, 
            title: {
                text: ''
            },
			plotLines: [{
				value: 0,
				width: 2,
					color: '#808080'
			}] 
 			":"
	 		allowDecimals: false,
       		/*labels: {
                formatter: function() {
                        return this.value / 1000 +'k';
               }
            },*/
            title: {
                text: ''
            },
			plotLines: [{
				value: 0,
				width: 2,
					color: '#808080'
			}]    				
	";
 	 
if(isset($strjson) && !empty($strjson))
{	
	$seriesId = $outId.'1';
	
	$Chartscript = "
	<script type='text/javascript'>
	var hdata =  '{$outId}';
	function activeLastPointToolip(chart) {
    var points = chart.series[1].points;
    	chart.tooltip.refresh(points[points.length -1]);
	}
	
 $(function () {
 var arrayObj = new Array(); 
 	               	 
	var d = new Date();
	var g_data = {$strjson};
	var timeStatus = {$timeStatus};
	if({$strjson2}!='')
	{
		g_data2 = {$strjson2};
	}else{
		g_data2 = {$strjson};
	}
	 
	Highcharts.setOptions({ 
    colors: ['{$colors}'] 
		});
    var options = ({
         /*
         line, spline, area, areaspline, column, bar, pie , scatter0         
         */
        chart: {         
			animation:'true', 
			type: '{$chartType}',
			marginRight: 5,
			renderTo:'{$outId}',
			events: {
			load: function () {
                	/*var series = this.series[1],
                    chart = this;
                	setInterval(function () 
                	{	                	 
                    var x = (new Date()).getTime();
                    var y = parseInt(Math.random() * 500)+1;
                   
                    var time2 = $('#time2').text();
                    
			      	time2 = parseInt(time2);	      
				 
			     	var ms =  (1000 * 60 * 60 * 24);
					
			      	if(time2>0)
			      	{						
						var newDatex = (time2 + ms);				
						$('#time2').text(newDatex);
			      	}else{				
						var newDatex = (parseInt( (new Date()).getTime() ) + ms );				
						$('#time2').text(newDatex);
			      	}	      
                    series.addPoint([newDatex, y], true, true);	
                    activeLastPointToolip(chart);
                	}, 1000);*/
              }
            }
        },
        subtitle: {
            text: ''
        },
        
        xAxis: {
        {$xAxis}             
        },
        yAxis: { 
        {$yAxis}        
        },
        credits:{            
        	enabled:true,
        	position:{
        		align:'left',
        		x:50
        	},
        	style:{
        		color:'bule',
        		fontWeight:'bold'
        	}
        },   
        title: {
            text: '{$charTitle}'
        },
		tooltip: {
			 {$totip}
		},
        legend:{
        	enabled:true
        },
          plotOptions: {
            spline: {
                
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [
         		
		{
					  
			id : '{$seriesId}',
			name: '{$ChannelOne}',
			marker: 
			{
				symbol: 'circle',
				fillColor: '#FFFFFF',
                lineWidth: 3,
                lineColor: null
			},
				data:g_data,
							
		}, 

		{
		 			 
			name: '{$ChannelTwo}',
			marker: 
			{
				symbol: 'circle',
				fillColor: '#FFFFFF',
                lineWidth: 3,
                lineColor: null
			},
				data:g_data2
		}
	]
		
    });
  var chart = new Highcharts.Chart(options);
    
    var creditsEnabled = false;
    var legendEnabled  = false;
    var xLabelsEnabled = true;
    var yLabelsEnabled = false;
    var yTitleEnabled  = 'Temperature';
    var yGridLinesEnabled = 1;
 
   /** $('button.update').click(function(){
    	var target = $(this).attr('target'); 
    	   	
    	if(target == 'credits') {
    		options.credits.enabled = creditsEnabled;
    		creditsEnabled = !creditsEnabled;
    	}
    	
    	if(target == 'legend') {
    		options.legend.enabled = legendEnabled;
    		legendEnabled = !legendEnabled;
    	}
    	
    	if(target == 'xLabels') {
    		options.xAxis.labels.enabled = xLabelsEnabled;
    		xLabelsEnabled = !xLabelsEnabled;
    	}
    	
    	if(target == 'yLabels') {
    		options.yAxis.labels.enabled = yLabelsEnabled;
    		yLabelsEnabled = !yLabelsEnabled;
    	}
    	
    	if(target == 'yTitle') {
    		options.yAxis.title.text = (yTitleEnabled=='Temperature' ? null : 'Temperature');
    		yTitleEnabled = (yTitleEnabled=='Temperature' ? null : 'Temperature');
    	}
    	
    	if(target == 'gridLines') {
    		options.yAxis.gridLineWidth = (yGridLinesEnabled==1 ? 0 :1);
    		yGridLinesEnabled = (yGridLinesEnabled==1 ? 0 :1);
    	}
    	
    	
    	chart = new Highcharts.Chart(options);
    	
    });**/
    
});
-->

</script>
	";	 
	
}else{
	
		$Chartscript = '';
}

	return $Chartscript;
	
}
// 总体登录统计
public static function statOverallLoginHtml($strjson="") 
{       
    	$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	
    	$btnType = '';
    	$data = '';
    	if (isset($strjson) && !empty($strjson)){
    		 
    	} 
    	$regionIdHtml = '
    	<input type="text" style="height:20px"  class="newInput"
    	name="regionid" placeholder="区服编号" size="10" title="*区服编号">';    	        
        # 选择条件 
        $selecttype =  self::selectregionType($type);  
        $optionTimeHtml = '<div id ="optiontimid"><i class="icon-th "></i> 
      	<input type="text" class="datetimepicker form-control"placeholder="开始时间" 
      	name="startTime" style="width:auto;"/>';    
      	$optionTimeHtml.= ' — <input type="text" class="datetimepicker form-control" placeholder="截止时间" 
      	name="endtime" style="width:auto;"/></div>'; 
      	
      	//组装
      	$html = <<<EOF
      	      				 
            	  
            	  {$data}
            	  
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>								 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statMoneryRemainVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr> 
						 <td>{$selecttype}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td> 
						 <td>{$optionTimeHtml}</td>
		                 <td><i class="icon-th "></i></td>		                 		                 
		                 <td> 
		                <button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button>
		                 </td>		                
						</tr>
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
}
 	// 用户搜索
	public static  function  stat_UserSerachHtml($type='',$sid='',
	$data,$sumrecharge) 
	{  
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	
    	if(isset($data) && !empty($data) && !empty($sumrecharge))
    	{      
    		$action = "ExportfileUserSerach";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl"  onclick="arr()"/>'; 
	    	foreach($data as $var)
			{   
				$rechargeNum = 0;
		    	foreach ($sumrecharge as $Inrecharge)
		    	{ 
		    		if($var['roleid'] === $Inrecharge['roleid'] )
		    		{
		    			$rechargeNum = $Inrecharge['sumrecharge']; 
		    		}
		    	}      	
				$userString .= $var['worldid'].'='.$var['roleid'].'='.
				$var['uin'].'='.$var['ilevel'].'='.$var['NickName'].
				'='.$var['date'].'='.$var['vip'].'='.$var['datetime'].
				'='.$var['coin'].'='.$var['cashs'].'='.$rechargeNum.',';
			}  
			
			
			$pcuTotalString .= $pcuTimeTotal['maxOnline'].'='.$pcuTimeTotal['avgonline'].',';
			
			$acuTotalString .= $pcuMonthTotal['maxOnline'].'='.$pcuMonthTotal['avgonline'].',';
    		 
			$time = implode(',',$time);
    	} 
    	 
    	$regionIdHtml = "
    	<input type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'>"; 
    	$roleIdHtml = '<textarea type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size=""></textarea>';   	
    	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  >';
    	$level = '<input type="text" style="height:20px"  class="newInput"
    	name="level"  size="10" title="角色等级">';
    	$level2 = '<input type="text" style="height:20px"  class="newInput"
    	name="level2"  size="10" title="角色等级">';
    	
    	$vip = '<input type="text" style="height:20px"  class="newInput"
    	name="vip" title="vip等级"   size="10">';
    	$vip2 = '<input type="text" style="height:20px"  class="newInput"
    	name="vip2" title="vip等级2"  size="10">';
    	
    	$coin = '<input type="text" style="height:20px"  
    	class="newInput" name="coin" title="金币" size="10">';
    	$coin2 = '<input type="text" style="height:20px" 
    	class="newInput" name="coin2" title="金币2" size="10">';
        $selecttype =  self::selectregionType($type);  
    	
    	$diamond = '<input type="text" style="height:20px"  class="newInput"
    	name="diamond" title="钻石" size="10">';
    	$diamond2 = '<input type="text" style="height:20px"  class="newInput"
    	name="diamond2" title="钻石2" size="10">';

		$uin ='<input type="text" style="height:20px" 
    	class="newInput" name="uin" title="UIN" size="10">';

      	//组装
      	$html = <<<EOF
      	      				 
            	 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$userString}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>										  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statMoneryRemainVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp*</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:&nbsp&nbsp&nbsp&nbsp&nbsp*</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr>
						
						<tr>
						 <td><B>角色编号:&nbsp&nbsp</B></td>
		                 <td>{$roleIdHtml}</td>	
		                 <td><B>角色名:</B></td>		                 		                 
		                 <td>{$name}</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr>
						
						<tr>
						 <td><B>角色等级:&nbsp&nbsp</B></td>  				  
						 <td>{$level}</td>   
		                 <td>　— </td>		                 
		                 <td>{$level2}</td>		                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr>

						<tr>
						 <td><B>VIP   &nbsp等级:&nbsp</B></td>
		                 <td>{$vip}</td>
		                 <td>　— </td>		
		                 <td>{$vip2}</td>		                 	      
						</tr>
						<tr>
						  <td><B>钻　　石:&nbsp&nbsp</B></td>
		                 <td>{$diamond}</td>
		                 <td>　— </td>		
		                 <td>{$diamond2}</td>		                       
						</tr>
						 <tr> 
						 <td><B>金　　币:&nbsp&nbsp</B></td> 	  
						 <td>{$coin}</td>   
		                 <td>　—  </td>		
		                 <td>{$coin2}</td>		                                   		                 
		                 <td> 
		                  
		                 </td> 
						</tr>
						<tr> 
						 <td><B>UIN:&nbsp&nbsp</B></td> 	  
						 <td>{$uin}</td>   
		                 <td></td>                          		                 
		                 <td> 
		                 <button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="btn_date">
		                	<span class="ladda-label">查询</span>
		                 </td>		                
						</tr>
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
	}
// 货币流水	
public static function statMoneyinfoHtml ($type=NULL,$sid=NULL,
$data=NULL,$time=NULL,$exctypestr=NULL){
		$btnType = NULL;
		if(isset($data) && !empty($data))
    	{   
    		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
    		 
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="submit"  style="padding:0;width:120px" 
    		class="btn btn-success" id="ExportfileBtn" value="导出Execl"/>'; 
	    	 
			$time = implode(',',$time);
			$times = isset($time)?explode(',',$time):'';
			 
    	} 

		//流水类型
		$exctypeAry = range(1,14);    	
    	foreach ($exctypeAry as $Invar)
    	{  
    		$Name = self::exctype($Invar);
    		if (empty($Name)){continue;}
    		$option.= "
    		<option value='{$Invar}'>"
    		.$Name.
    		"</option>"; 
    	}    	
    	$exctype ="<select name='exctype' id ='type' 
    	class='form-control' style='width:160px' title='流水类型'>
		<option value='0 '>	--请选择--</option>
		 {$option}
    	</select>";

    	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	/* $regionIdHtml = "
    	<input type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'>"; */
    	$regionIdHtml = "<select name='regionid' id='ServerId' value='{$sid}'
		class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
    	
    	$roleIdHtml = '<input type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size="10">';    	
    	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  >'; 
    	$status = '';
    	 
        //$selecttype = self::selectregionType($type);  
    	$selecttype = self::getplatListInfo2();
		$logTypeConfig = self::log_type_config();
    	$remainnterval =$logTypeConfig[0]; 

    	$diamond = '<input type="text" style="height:20px"  class="newInput"
    	name="diamond" title="钻石" size="10">';
    	$diamond2 = '<input type="text" style="height:20px"  class="newInput"
    	name="diamond2" title="钻石2" size="10">';
      	//组装
      	$html = <<<EOF
      	      				 
            	 	{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
								{$btnType}	 					 
								</div>							 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statmoneyInfoVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp*</B></td> 
						 <td>{$selecttype}</td> 
						 <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr>
						<tr>
						 <td><B>角色编号:&nbsp&nbsp</B></td>
		                 <td>{$roleIdHtml}</td>	
		                 <td><B>角色名:</B></td>		                 		                 
		                 <td>{$name}</td>       
						</tr>
						<tr>
						 <td><B>时间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>   
						</tr> 
						 <tr> 
						 <td><B>流水类型:&nbsp&nbsp</B></td>
						 <td>$exctype</td>
						 <td><B>消耗类型:&nbsp&nbsp*</B></td> 	  
						 <td>{$remainnterval}</td>
						</tr> 	
						<tr>
						<td>&nbsp</td>		
		                <td>&nbsp</td>	
		                <td>&nbsp</td>	
		                <td> 
		                 <button class="btn btn-primary btn-xs ladda-button" 
				      	data-style="expand-right" name="sut" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button>
		                 </td>	
						</tr>
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
	}
	/*
	 * 货币流水类型
	 * @param 状态值
	 * */
	public  static function exctype($type)
	{
		switch($type)
		{
            case 1 : $exctype ="技能书商店购买道具";   break;
			case 2 : $exctype ="技能书商店刷新";   break;
			case 3 : $exctype ="小游戏厅商店刷新";   break;
			case 4 : $exctype ="水管竞技场次数购买";   break;
			case 5 : $exctype ="水管竞技场秒cd";   break;
			case 6 : $exctype ="签到补签";   	 break;
			case 7 : $exctype ="抢车位商店购买道具 ";   break;
			case 8 : $exctype ="抢车位商店刷新"; 		break;
			case 9 : $exctype ="抢车位车辆修理秒cd";   break;
			case 10: $exctype ="抢车位扩展车位";   break;
			case 11: $exctype ="表情商店购买商品";   break;
			case 12: $exctype ="表情商店刷新"; break;
			case 13: $exctype ="套装商店购买商品";   break;
			case 14: $exctype ="充值购买"; break;
			default: $exctype = $type;break;
           } 
           return $exctype;
	} 
	// 登录流水	
public static function statLogininfoHtml (){
	
		$btnType =''; 
    	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	$regionIdHtml = "
    	<input type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'>"; 
    	$roleIdHtml = '<input type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size="10">';    	
    	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  >'; 
    	$status ="";
        $selecttype = self::selectregionType($type);  
    	$remainnterval =' '; 
    	$diamond = '';
      	//组装
      	$html = <<<EOF
      	      				 
            	 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$MoneyinfoStr}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>	
										 
										 <input  type="hidden" name="time" 
										 value="{$time}"/>	
										 									  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statloginInfoVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr>						
						<tr>
						 <td><B>角色编号:&nbsp&nbsp</B></td>
		                 <td>{$roleIdHtml}</td>	
		                 <td><B>角色名:</B></td>		                 		                 
		                 <td>{$name}</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr>
						<tr>
						 <td><B>时间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>		                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td> <button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sut" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button></td>          
						</tr> 
						 
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
	}
// 货币消耗流水	
public static function statmoneyDataHtml ($type,$sid){
		 $btnType ='';
 	
    	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	$regionIdHtml = "
    	<input type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'>"; 
    	$roleIdHtml = '<input type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size="10">';    	
    	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  >'; 
    	$status ="";

		$exctypeAry = range(1,100);    	
    	foreach ($exctypeAry as $Invar)
    	{  
    		$Name = self::exctype($Invar);
    		if (empty($Name)){continue;}
    		$option.= "
    		<option value='{$Invar}'>"
    		.$Name.
    		"</option>"; 
    	}
    	$exctype ="<select name='exctype' id ='type' 
    	class='form-control' style='width:105px' title='流水类型'>
		<option value='0 '>	--请选择--</option>
		 {$option}
    	</select>";
    	 
        $selecttype = self::selectregionType($type);
        $status ="
    	<select name='status' id ='status' class='form-control' 
    	style='width:105px' title='状态'>    	 
        <option value='3'>全部</option>
    	<option value='1'>消耗</option>
        <option value='2'>收入</option>        
        </select>
    	";
        $logTypeConfig = self::log_type_config();
    	$remainnterval =$logTypeConfig[0];
      	//组装
      	$html = <<<EOF
      	      				 
            	 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$MoneyinfoStr}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>	
										 
										 <input  type="hidden" name="time" 
										 value="{$time}"/>	
										 									  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statmoneyDataVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr>
						 
						
						<tr>
						 <td><B>消耗类型:&nbsp&nbsp</B></td>
		                 <td>{$remainnterval}</td>	
		                 <td><B>状    态:</B></td>		                 		                 
		                 <td>{$status}</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr>
						 
						<tr>
						 <td><B>时间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>		                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td> </td>          
						</tr> 
						
						 <tr>
						 <td><B>流水类型:&nbsp&nbsp</B></td>
		                 <td>{$exctype}</td>	
		                 <td></td>		                 		                 
		                 <td></td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                <td> <button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sut" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button></td>          
						</tr>
					</table> 			                        
                </div>
                </div>                
            </form>       
    </div>
</div>
{$scriptjava} 
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
	}
// 留存数据统计
public static function statRetainedDataHtml($type="",$sid="",
$time="",$data="",$createNumber=""){
	
		$btnType =''; 
		$action = '#';
		$readySubmitJs='';

		if(!empty($type)){
			$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
			$action = "ExportfileDaylyData";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="button"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';	 
		}	
    	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	//区服编号
    	$regionIdHtml = "
    	<input type='text' style='height:20px;width:90px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'>"; 
    	//
        $selecttype =  self::selectregionType($type);
        
        // 留存类型
        $status ="
    	<select name='status' id ='status' 
    	class='form-control' 
    	style='width:105px' title='留存类型'>
    	<option value='2'>角色留存</option> 
    	<option value='1'>账号留存</option>    	               
        </select>
    	";            
        // 留存区间
    	$retainedSpace = '<input type="text" 
    	style="height:20px;width:90px"  class="newInput"
    	name="space" title="留存区间" size="10">';
      	// 组装
      	$html = <<<EOF
      	      				 
				  {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$data}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>	
										 
										 <input  type="hidden" name="time" 
										 value="{$time}"/>	
										 									  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statretainedDataVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服编号:&nbsp&nbsp</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr> 
						<tr>
						 <td><B>留存类型:&nbsp&nbsp</B></td>
		                 <td>{$status}</td>	
		                 <td><B>留存区间:</B></td>		                 		                 
		                 <td>{$retainedSpace}&nbsp&nbsp天</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr> 
						<tr>
						 <td><B>时　　间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>		                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td><button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sut" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button></td>          
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
// cdk	
public static function statRecordcdkHtml($type="",$sid="",
$time="",$data="",$createNumber=""){
	
		$btnType =''; 
		$action = '#';
		if(!empty($data)){
			
			$btnType =''; 
			$action='';
		}	
    	$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="starttime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endtime" />';
    	//区服编号
    	$regionIdHtml = "
    	<textarea type='text' style='height:20px;width:90px'  class='newInput'
    	name='regionid'   size='10' title='*区服编号'></textarea>"; 
    	
    	$name = "<input  type ='text' style='height:20px;width:90px'  
    	class='newInput' name ='name' size='10' title='*角色昵称'>";
    	
    	$roleIdHtml = '<textarea type="text" style="height:20px;width:92px"  class="newInput"
    	name="roleid"  title="角色ID" size="10"></textarea>';  
 
    	$cdk = "<textarea  type ='text' style='height:20px;width:90px'  
    	class='newInput' name ='cdkCode' size='10' title='CDK码'></textarea>";
    	//
        $selecttype =  self::selectregionType($type);
        
      
      	// 组装
      	$html = <<<EOF
      	      				 
            	 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$data}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>	
										 
										 <input  type="hidden" name="time" 
										 value="{$time}"/>	
										 									  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" onsubmit="return statmoneyDataVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服编号:&nbsp&nbsp</B></td>	 				  
						 <td>{$regionIdHtml}</td>
						 <td></td>
		                 <td></td>   	                
						</tr> 
						<tr>
						 <td><B>角色编号:&nbsp&nbsp</B></td>
		                 <td>{$roleIdHtml}</td>	
		                 <td><B>cdk码:&nbsp&nbsp</B></td>		                 		                 
		                 <td>{$cdk}</td>
		                 <td></td>          
						</tr> 
						<tr>
						 <td><B>使用时间:&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>		                 
		                 <td><button class="btn btn-primary btn-xs ladda-button" 
						data-style="expand-right" name="sut" type="submit" id="btn_date">
						<span class="ladda-label"> 查询</span></button></td>
		                       
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

/**
*铭刻记录
**/
public static function stat_posyAnnalHtml($time='')
{
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	$readySubmitJs = '';
    	if(isset($time))
    	{  
    		$action = "ExportfileDaylyData";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="button"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>'; 
    		$readySubmitJs =self::readySubmit('ExportfileBtn','from1'); 
    	}  
    	$selecttype = self::selectregionType($type);
    	//流水类型
		$exctypeAry = range(1,100);    	
    	foreach ($exctypeAry as $Invar)
    	{  
    		$Name = self::exctype($Invar);
    		if (empty($Name)){continue;}
    		$option.= "
    		<option value='{$Invar}'>"
    		.$Name.
    		"</option>"; 
    	}    	
    	
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">'; 
        
    	$exctype ="<select name='exctype' id ='type' 
    	class='form-control' style='width:160px' title='流水类型'>
		<option value='0 '>	--请选择--</option>
		 {$option}
    	</select>";
		// 支出收入
        $status ="
    	<select name='status' id ='status' class='form-control' 
    	style='width:105px' title='状态'>
    	<option value='1'>消耗</option>
        <option value='2'>收入</option>        
        </select>";
        //  消耗类型
    	$logTypeConfig = self::log_type_config();
    	$remainnterval =$logTypeConfig[0]; 
    	
    	$regionIdHtml = "
    	<textarea type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'></textarea>"; 
		$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
      	//组装
      	$html = <<<EOF
      	      				 
            	 {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$userString}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>										  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" onsubmit="return StatdataPutVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr>
						 
						<tr>
						 <td><B>区服起始&nbsp:</B></td>
						 <td>{$sidSpace1}</td>
						 <td>&nbsp<B>区服截止&nbsp:</B></td>				  
						 <td>{$sidSpace2}</td> 
						</tr>	
						<tr>
						 <td><B>消耗类型:&nbsp&nbsp</B></td>
		                 <td>{$remainnterval}</td>	
		                 <td><B>状    态:</B></td>		                 		                 
		                 <td>{$status}</td>	 
						</tr>
						 
						<tr>
						 <td><B>时间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td>		                 
		                   
						</tr>  
						 <tr>
						 <td><B>流水类型:&nbsp&nbsp</B></td>
		                 <td>{$exctype}</td>	
		                 <td></td>		                 		                 
		                 <td><button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td>		     	                 
		                 
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
// 邮箱记录
public static function stat_mail_recordingHtml ($time)
{
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	$readySubmitJs = '';
    	 
    	$selecttype = '';
    	
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">'; 
        
    	$exctype ="";
		// 支出收入
        $status =""; 
        $selecttype = self::selectregionType($type);
        //  消耗类型
    	$loginTypeConfig = '';
    	$remainnterval =''; 
    	$regionIdHtml = "
    	<textarea type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'></textarea>"; 
		$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	
    	$roleIdHtml = '<textarea type="text" style="height:20px"  class="newInput"
    	name="roleid"  title="角色ID" size=""></textarea>';
    	    	
    	$name= '<input type="text" style="height:20px"  class="newInput"
    	name="name" title="角色名" size="10"　  >';
    	
      	//组装
      	$html = <<<EOF
      	      				 
            	 {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								<form  action="{$action}" method="POST" >
    									<input  type="hidden" name="data" 
										 value="{$userString}"/>
										 <input  type="hidden" name="type" 
										 value="{$type}"/>	
										  <input  type="hidden" name="sid" 
										 value="{$sid}"/>										  
										 <div>
									     <table border=0>
								         <tr>
								        	<td>{$btnType}</td>	
								         </tr>
								         </table> 
								         </div>     									                       
									</form>    							 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" 
		onsubmit="return statMailRecordingatVerify(this);">         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr> 
						<tr>
						 <td><B>角色编号:&nbsp&nbsp</B></td>
		                 <td>{$roleIdHtml}</td>	
		                 <td><B>角色名:</B></td>		                 		                 
		                 <td>{$name}</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr>
						
						<tr>
						 <td><B>时间:&nbsp&nbsp</B></td>  				  
						 <td>{$startTime}</td>   
		                 <td>　— </td>		                 
		                 <td>{$endTime}</td> 
						</tr>  
						 <tr>
						 <td>&nbsp</td>
		                 <td>&nbsp</td>	
		                 <td>&nbsp</td>		                 		                 
		                 <td><button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td>	 
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

/**
 * 获得公会信息
 **/
public static function stat_guild_dataHtml( $time )
{
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	$readySubmitJs = '';
    	 
    	$selecttype = '';
    	
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">'; 
        
    	$exctype ="";
		// 支出收入
        $status =""; 
        $selecttype = self::selectregionType($type);
        //  消耗类型
    	$loginTypeConfig = '';
    	$remainnterval =''; 
    	$regionIdHtml = "
    	<textarea type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'></textarea>"; 
		$startTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="startTime" />';
    	$endTime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="截止时间"  name="endTime" />';
    	
    	$guildIdHtml = '<textarea type="text" style="height:20px"  class="newInput"
    	name="guildId"  title="公会ID" size=""></textarea>';
    	    	
    	$guildName= '<input type="text" style="height:20px"  class="newInput"
    	name="guildName" title="公会名" size="10"　  >';
    	
      	//组装
      	$html = <<<EOF
      	      				 
            	 {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								 					 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" onsubmit="return statMailRecordingatVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服ID:</B></td>	 				  
						 <td>{$regionIdHtml}</td> 	                	                
						</tr> 
						<tr>
						 <td><B>公会ID:&nbsp&nbsp</B></td>
		                 <td>{$guildIdHtml}</td>	
		                 <td><B>公会名:</B></td>		                 		                 
		                 <td>{$guildName}</td>		     	                 
		                 <td></td>
		                 <td></td>		
		                 <td></td>
		                 <td></td>
		                 <td></td>          
						</tr> 
						 <tr>
						 <td>&nbsp</td>
		                 <td>&nbsp</td>	
		                 <td>&nbsp</td>		                 		                 
		                 <td><button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td>	 
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
/**
 * 统计区服留存数据
 **/
public static function allserver_retainedHtml( $time )
{
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	$readySubmitJs = '';
    	 
    	$selecttype = '';
    	//self::getplatformInfo($cookie)
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">'; 
        
    	$exctype ="";
		// 支出收入
        $status =""; 
        $selecttype = self::getplatListInfo2();
        // 消耗类型
    	$loginTypeConfig = '';
    	$remainnterval =''; 
    	$regionIdHtml =""; 
		$datetime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="datetime" />';
    	$endTime =' ';
    	
    	$guildIdHtml = ' ';
    	$btnType = ''; 

    	if(isset($time))
    	{  
    		$readySubmitJs =self::readySubmit('ExportfileBtn','from1'); 
    		$action = "ExportfileDaylyData";
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="button"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';	 
    		$time = implode(',',$time);  
    	} 

    	$space= '<input type="text" style="height:20px"  class="newInput"
    	name="space" title="留存" size="10">';
    	
      	//组装
      	$html = <<<EOF
      	 
            	 {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
								{$btnType}	 					 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" 
		onsubmit="return statAllserverRetainedVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>注册时间:</B></td>	 				  
						 <td>{$datetime}</td> 	                	                
						</tr> 
						<tr>
						 <td><B>留存:</B></td>
		                 <td>{$space}天</td>	 
		                  <td>&nbsp</td>		                 		                 
		                 <td><button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td>	 
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
/**
 * LTV 值
 **/
public static function stat_ltvHtml( $time ,$sid='')
{
		$currentTime =date('Y-m-d H:i:s',time()); #当前时间    
    	$readySubmitJs = '';
    	
    	$regionIdHtml = "
    	<textarea type='text' style='height:20px'  class='newInput'
    	name='regionid' value='{$sid}' size='10' title='*区服编号'></textarea>";
    	$selecttype = '';
    	
        $sidSpace1 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace1" placeholder="sid begin" size="10" title="*区服开始编号">'; 
        
        $sidSpace2 = '<input type="text" style="height:20px"  class="newInput"
    	name="sidSpace2" placeholder="sid end" size="10" title="*区服截止编号">'; 
        
    	$exctype ="";
		// 支出收入
        $status =""; 
        $selecttype = self::selectregionType($type);
        //  消耗类型
    	$loginTypeConfig = '';
    	$remainnterval =''; 
    	 
		$datetime ='<input type="text" class="datetimepicker form-control" 
      	placeholder="开始时间"  name="datetime" />';
    	$endTime =' ';
    	
    	$guildIdHtml = ' ';
    	    	
    	$space= '<input type="text" style="height:20px"  class="newInput"
    	name="space" title="留存" size="10"　  >';
    	
      	//组装
      	$html = <<<EOF
      	 
            	 {$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								 					 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" 
        onsubmit="return statltvVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 
						 <td><B>区服:</B></td>	
		                 <td>{$regionIdHtml}</td> 	            	                
						</tr> 
						<tr>						 	                 
						 <td><B>注册时间:</B></td>	 				  
						 <td>{$datetime}</td> 	 
						 <td><B>留存:</B></td>
		                 <td>{$space}&nbsp天</td>		                    		                 
		                 <td><button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td>	 
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
/**
 * 活动查看配置
 **/
public static function activity_configHtml( $time='' ,$sid='')
{	 
        $selecttype =
        '<select name="type" id="type1" class="form-control" style="width:100px" title="*设置类型">
	        <option value="">--请选择--</option>
	        <option value="101">测试专属平台</option>
	        <option value="1">应用宝</option>
	        <option value="2">混服</option>
	        <option value="3">Appstore</option>
	        <option value="4">Morefun简体</option>
	        <option value="5">winphone</option>
	        <option value="6">小小魔兽团</option>
	        <option value="7">Morefun英文</option>	        
        </select>';
      	//组装
      	$html = <<<EOF
      	 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">								 
    								 					 
								</div>									 
							</div> 
	    <div class="widget-content">  
        <form  method="POST" class="form-horizontal" id="subfrom" 
        onsubmit="return StatdataPutVerify(this);" >         
                <div class="control-group" >
				<div class="controls" > 
					<table border=0>
						<tr>
						 <td><B>平台类型:&nbsp&nbsp</B></td> 
						 <td>{$selecttype}</td> 						 
		                 <td>&nbsp&nbsp<button class="btn btn-info ladda-button" name='sut' 
		                 data-style="zoom-in" type="submit" id="submit_btn_id">
		                 <span class="ladda-label">查询</span></td> 	            	                
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
public static  function selectregionType($type=0,$status=''
,$starttime='',$endtime='',$sid='')
{ 
		 
		switch ($_SESSION['account'])
		{
			case '37wan001':
			case '37wan002':
			case '37wan003':$value =array(2,1,3,6);break;
			case 'test001' :$value =array(4); break;
			default: $value = array(1,2,3,4,5,6,7);break;
		}  
        foreach ($value as $var) 
        {        	
        	switch($var)
        	{
        		case 1: $typeName = '应用宝';break; 
        		case 2: $typeName = '混服';break;
        		case 3: $typeName = 'Appstore';break;
        		case 4: $typeName = 'Morefun简体';break;
        		case 5: $typeName = 'winphone';break;			
        		case 6: $typeName = '小小魔兽团';break;
				case 7: $typeName = 'Morefun英文';break;
			}
			$option .="<option value='{$var}'>{$typeName}</option>";  
        }  
		if(!empty($type)){
			switch ($type){        	
	        	case 1:$strType='应用宝';break;
	        	case 2:$strType='安卓混服';break;
	        	case 3:$strType='Appstore';break;
	        	case 4:$strType='Morefun简体';break;
	        	case 5:$strType='winPhone';break;
				case 6:$strType='小小魔兽团';break;
				case 7: $typeName = 'Morefun英文';break;
	        	default:$strType='--请选择--';break;
	        }
		}else{			
			$strType='--请选择--';
		}
        $selecttype ="
		<select name='type' id ='type1' class='form-control' 
	        style='width:100px' title='*设置类型'>
	        <option value='{$type}'>{$strType}</option>
	        {$option}
        </select>";
	     //区服编号
	    if(!empty($sid)){
		    $sid = "
		    <textarea type='text' style='height:20px;width:90px' 
		    class='newInput'name='regionid'   
		    size='10' title='*区服编号' value={$sid}></textarea>";
	    }else{
	    	
	    	$sid = '';
	    }
	    //
	   	$data = array
	   	(
	   		'selecttype'=>$selecttype,
	   		'sid'=>$selecttype,	   		
	   	);     
        return $selecttype;	
}
/**
 * login type 配置
 */
public static function log_type_config($dateName=NULL,$loginType=NULL)
{	
	$logName = '';
	if(!empty($loginType))
	{
		/* switch ($loginType)
		{
			case 1:
			case 2:$logName = "钻石";# 钻石
			break;
			case 3:
			case 4:$logName ="勋章";# 勋章
			break;
			case 5:
			case 6:$logName ="金币";# 金币
			break;
			case 7:
			case 8:$logName ="荣誉点";  # 荣誉点
			break;
			case 9:
			case 10:$logName ="英雄魂石"; # 英雄魂石
			break;
			case 11:
			case 12:$logName ="声望";  	 # 声望
			break;
			case 13:
			case 14:$logName ="装备魂石"; # 装备魂石
			break;
			case 15:	
			case 16:$logName ="征途币";  # 征途币
			break;
			case 17:
			case 18:$logName ="公会勋章";# 公会勋章
			break;
			case 19:
			case 20:$logName ="魔法卷轴";# 公会勋章
			break;			
			default:'';break;
		 } */
		switch ($loginType)
		{	
			case 1:$logName = "金币";# 金币
			break;
			case 2:$logName = "体力";# 体力
			break;
			case 3:$logName = "钻石";# 钻石
			break;
			case 4:$logName = "经验";# 经验
			break;
			case 5:$logName = "技能点";# 技能点
			break;
			case 6:$logName = "竞技场积分";# 竞技场积分
			break;
			case 7:$logName = "积极场次数";# 积极场次数
			break;
			case 8:$logName = "石中剑次数";# 石中剑次数
			break;
			case 9:$logName = "预分配货币";# 预分配货币
			break;
			default:$loginType;break;
		}
		$remainnterval =
        "<select name='remainType' id ='remainType' 
      	class='form-control' style='width:100px' title='*设置类型'>
      	<option value='{$loginType}'>{$logName}</option>
	    <option value='1'>金币</option>
		<option value='2'>体力</option>
		<option value='3'>钻石</option>
		<option value='4'>经验</option>
		<option value='5'>技能点</option>
		<option value='6'>竞技场积分</option>
		<option value='7'>积极场次数</option>
		<option value='8'>石中剑次数</option>
		<option value='9'>预分配货币</option> 
      	</select>";
	}else{
		// default
		$remainnterval =
        '<select name="remainType" id ="remainType" 
      	class="form-control" style="width:100px" title="*设置类型">
      	<option value="1">金币</option>
		<option value="2">体力</option>
		<option value="3">钻石</option>
		<option value="4">经验</option>
		<option value="5">技能点</option>
		<option value="6">竞技场积分</option>
		<option value="7">积极场次数</option>
		<option value="8">石中剑次数</option>
		<option value="9">预分配货币</option>
      	</select>';
	}
	$logtypeData = array($remainnterval,$logName);
	
	return $logtypeData;
}

//实时数据统计html
public static function statRealTimeHtml($data) 
{    	 
    	$scriptjava = self::function_statData_script();
    	$btnType = '';    
    	$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
    	if(isset($data))
    	{   
    		$btnType ="btn btn-success";
    		$btnType = '<input  type="button"  style="padding:0;width:120px" 
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';	 
    		$time = implode(',',$time); 
    	} 
    	 
      	//组装
      	$html = <<<EOF
      		{$readySubmitJs}
            <!-- 查询组件 begin-->
		    <div class="widget-box">
		   		<div class="widget-title">
					<span class="icon">
						<i class="icon-search"></i>
					</span>
					<h5> </h5>
					<div class="buttons">
						{$btnType}				 
					</div>	
				</div>
		    </div>
		
EOF;
        return $html;
}
public static function statLongHtml($data){
		$scriptjava = self::function_statData_script();
		
    	$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
		$btnType = !empty($data)
		?
		'<input  type="button"  style="padding:0;width:120px" 
    	 class="btn btn-success" value="导出" id="ExportfileBtn"/>'
		:""; 
        // 区服编号
    	$regionIdHtml = '    	
		<select name="region" id ="region" 
      	class="form-control" style="width:100px" title="区服列表">
      	<option value="">--区服--</option>
      	<option value="1">1</option>
		<option value="2">2</option>
      	<option value="3">3</option>
	    <option value="4">4</option>			
		<option value="5">5</option>			
      	</select>
    	';         
        $seletinfo = '
    	<select name="remainType" id="remain5"   
      	class="form-control" style="width:100px" title="*设置类型">
      	<option value="">--查询条件--</option>
      	<option value="uin">uin</option>
		<option value="uid">uid</option>
      	<option value="accountType">账号</option>
	    <option value="roleid">角色ID</option>			
		<option  value="NickName">角色名</option>			
      	</select>  
      	 &nbsp 
      	';
       // $NameclickOpenLike = self::function_statData_script2("region","optiontimid","null","NickName");         
        
        $logininsel = '
    	<select name="remainType" id ="remainType" style="width:100px" 
      	class="form-control"  title="*设置类型">
      	<option value="">--查询条件--</option>
      	<option value="uin">uin</option>	
	    <option value="roleid">角色ID</option>
	    <option  value="NickName">角色名</option>
      	</select>
      	';
        
        $value= '<input type="text" style="width:100px"  class="value" 
      	name="name" title="查询值"  placeholder="查询值">';
        // 周期区间        
        $type.= '
        <select name="type" id ="type" class="form-control" style="width:100%" title="*设置类型">
        <option value="">--类型--</option>
        <option value="1">玩家搜索</option>
        <option value="2">登录日志</option></select>';
        // loginglog        
      	$optionTimeHtml = "<div id ='optiontimid'>
      	
      		 
		<input type='text' class='datetimepicker form-control' 
      	placeholder='开始时间'  name='startTime' style='width:auto;'/>
      	<input type='text' class='datetimepicker form-control'  
      	placeholder='截止时间' name='endtime' style='width:auto;'/>&nbsp<i class='icon-th '></i> {$logininsel}
      	 </div>";
      	// 统计类型 usersearch
      	$lookstatusHtml = " <div id='lookstatusid'>
      	{$seletinfo}  
      	</div>";
      	// 组装
      	$html = <<<EOF
       
      			{$readySubmitJs}
      			{$scriptjava}
            	<!-- 查询组件 begin-->
				<div class="widget-box">
				   	<div class="widget-title">
							<span class="icon">
								<i class="icon-search"></i>
							</span>
							<h5>设置条件</h5>
							<div class="buttons">
								{$btnType}				 
							</div>	
					</div>
	    <div class="widget-content">			
        <form  method="POST" class="form-horizontal" onsubmit="return StatdataPutVerify(this);" >         
            <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
						 <td>{$type}</td>
						 <td><i class="icon-th "></i></td>					  
						 <td>{$regionIdHtml}</td>	
						 <td><i class="icon-th "></i></td>					  
		                 <td>{$optionTimeHtml}</td>
		                 <td>{$lookstatusHtml}</td>
		                 <td>{$value}</td>
		                 <td class='like'></td>		                                  
		                 <td>
		                 <i class="icon-th "></i>
		                 <button class="btn btn-primary btn-xs ladda-button" 
						 data-style="expand-right" name="sub_btn" type="submit" id="btn_date">
						 <span class="ladda-label"> 查询</span></button></td>
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

///<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// 在线玩家
public static function statOnlineRoleHtml()
{
	
	$server ='<input type="text"  class ="form-control" name="server">';
	$Sequence = '
			<select name="sequence">
			<option value="">--请选择--</option>
			<option value="level">等级</option>
			<option value="diamond">钻石</option>
			<option value="gold">金币</option>
			<option value="paynumber">总充值</option>
			<option value="totalTime">在线时长</option>
			<option value="onlinetime">在线总时长</option>
			</select>';
	//组装
	$html = <<<EOF
	
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">    						
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="onlineRole" id="onlineRoleForm" class="form-horizontal"
        onsubmit="return StatOnlineRoleVerify(this);">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>区服Id:&nbsp&nbsp</B></td>
						 <td>{$server}</td>
						<td><B>排序类型:&nbsp&nbsp</B></td>
						 <td>{$Sequence}</td>						 		
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" 
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
//当前战斗查询
public static function  statFightingHtml(){
$server ='<input type="text"  class="form-control" name="server">';
	 
	//组装
	$html = <<<EOF
	
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">    						
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="currentFighting" id="onlineRoleForm" class="form-horizontal"
        onsubmit="return StatOnlineRoleVerify(this);">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>区服Id:&nbsp&nbsp</B></td>
						 <td>{$server}</td>					 		 		
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" 
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
public static function timelist($datetime,$format){
	
	$timenumebr = intval($datetime / 1000);
	
	if ($format=='H:i:s'){		
		$str = ":" . sprintf("%02d", $timenumebr % 60);
		//"%02d" 格式化为整数，2位，不足2位，左边补0
		$minutes = intval($timenumebr / 60);
		$str = ":" . sprintf("%02d", $minutes % 60) . $str;
		$hours = intval($minutes / 60);
		$str = $hours . $str;
		return $str;
	}
	if ($format == 'Y-m-d H:i:s'){ 
		$lasttime = date($format,$timenumebr);
		return $lasttime;
	}
	return $datetime;
}
// 实时在线查询
public static function  statRealTimeRoleHtml($data){
	// 平台
	$serverInfo =  self::getplatListInfo2();
	if(isset($data) && !empty($data))
	{
		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
		 
		$btnType ="btn btn-success";
		$btnType = '<input  type="button"  style="padding:0;width:120px"
    		class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';
		//$time = implode(',',$time);
	}
	
	$server ='<input type="text"  class ="form-control" name="server">';
	$orderColumn =  '
			<select name="sequence">
			<option value="">--请选择--</option>		
			<option value="level">等级</option>
			<option value="vip">vip</option>			 
			</select>
			';
	//组装
	$html = <<<EOF
				{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons"> 
								{$btnType}
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="realTimeRole" id="onlineRoleForm" class="form-horizontal"
        onsubmit="return StatOnlineRoleVerify(this);">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>平台:&nbsp&nbsp</B></td>
						 <td>{$serverInfo}</td>
						 <!--<td><B>排序:&nbsp&nbsp</B></td>
						 <td>{$orderColumn}</td>-->
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" 
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
// 在线时长
public static function  statTimeLengthHtml(){

	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
			placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
			placeholder="截止时间"  name="endTime"/>';

	$server =" <div id='container'>
	    <div class='side-by-side clearfix'>
	      <div>
	        <select class='chzn-select' name='server' multiple style='width:120px'>
	          <option value=1>1服</option>
	          <option value=2>2服</option>
	          <option value=3>3服</option>
	        </select>
			</div>
		</div>
		</div>";
	//组装
	$html = <<<EOF

				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="onlineTimeLength" id="complexForm"
        class="form-horizontal" onsubmit="return statComplexVerify(this);">
        		<input type="hidden" name="platfrominfo" id="platfrominfo">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>
						 <td>区服:*</td>
						 <td>{$server}</td>
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td>
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
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
// 综合日常数据统计statTimeLengthHtml
public static function  statComplexHtml(){

	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control" 
			placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
			placeholder="截止时间"  name="endTime"/>';
	
	$server =" <div id='container'>		 
	    <div class='side-by-side clearfix'> 
	      <div> 
	        <select class='chzn-select' name='server' multiple style='width:120px'>
	          <option value=1>1服</option>
	          <option value=2>2服</option>
	          <option value=3>3服</option>	          
	        </select>
			</div>
		</div>
		</div>";
	//组装
	$html = <<<EOF

				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="complexDistributed" id="complexForm" 
        class="form-horizontal" onsubmit="return statComplexVerify(this);">
        		<input type="hidden" name="platfrominfo" id="platfrominfo">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>
						 <td>区服:*</td>
						 <td>{$server}</td>
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td>						 		
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
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
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// __________________________________________
// 水果机日常分布统计
public static function  statDailyHtml(){

	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 渠道统计 ------------》后期获取配置	
	$channel ='<input type="checkbox" name="channel" 
	style="margin:0px;padding:0px" value=1>';	
	//组装
	$html = <<<EOF
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="fruitDaily" id="complexForm"
        class="form-horizontal" onsubmit="return statfruitDailyVerify(this);">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>						 
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td>
						 <td>渠道:</td>
						 <td>{$channel}</td>
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
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
//水果机日常报表统计分布
// 水果机日常分布统计
public static function  statDailyReportHtml(){

	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	 
	//组装
	$html = <<<EOF
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="fruitDaily" id="complexForm"
        class="form-horizontal" onsubmit="return statfruitDailyVerify(this);">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td>						  
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
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
// 水果机留存分布统计
public static function  statfruitRetainedHtml(){

	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	 
	//组装
	$html = <<<EOF
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="fruitDaily" id="complexForm"
        class="form-horizontal" onsubmit="return statfruitDailyVerify(this);">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td> 
		                 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
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
public  static function getplatListInfo2()
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
	$selecttype ="
	<select name='platId' data-plat='platId' id='platById' 
	class='form-control' style='width:120px' title='*设置平台类型'>
	{$OptionHtml}
	</select>";

	return  $selecttype;
}
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
public static function  stat_chart_daily(){
	
	$platHtml = self::getplatListInfo2();
	//$ServerIdHtml = "<input type='text' name='serverId'>";
	
	$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
	$readySubmitJs2 =self::readySubmit('PlatExportfileBtn','from2');
	$ServerIdHtml = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	
	// 提交
	$submitBtn = '<button class="btn btn-primary btn-xs ladda-button progress-demo"
	data-style="expand-right" name="sut" type="submit" id="btn_date">
	<span class="ladda-label"> 查询</span></button>';
	 
	$html = <<<EOF
		{$readySubmitJs}
		{$readySubmitJs2}
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
        onsubmit="return statChartDailyVerify(this);">
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

public static function statLevelInfoHtml($data)
{
	if(isset($data))
	{
		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
		$action = "ExportfileDaylyData";
		$btnType ="btn btn-success";
		$btnType = '<input  type="button"  style="padding:0;width:120px"
    	class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';
		$time = implode(',',$time);
	} 
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 平台
	$serverInfo =  self::getplatListInfo2();;
	$ServerIdHtml = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	
	//组装
	$html = <<<EOF
	{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
						<span class="icon">
							<i class="icon-search"></i>
						</span>
						<h5>设置条件</h5>
						<div class="buttons">
						{$btnType}
						</div>
					</div>
	    <div class="widget-content">
        <form  method="POST" action="stat_level_info" id="complexForm"
        class="form-horizontal" onsubmit="return statLevelInfoVerify(this);">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr>  
						 <td>$serverInfo</td>
						 <td>$ServerIdHtml</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						</tr>
						<tr>						 
						 <td>{$startTime}</td>						 
						 <td>{$endTime}</td> 
						 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
						 <td>&nbsp;</td>
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


public static function statHighestOnlineHtml($data)
{
	if(isset($data))
	{
		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
		 
		$btnType ="btn btn-success";
		$btnType = '<input  type="button"  style="padding:0;width:120px"
    	class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';
		$time = implode(',',$time);
	} 
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 平台
	$serverInfo =  self::getplatListInfo2();;
	// serverId
	$serverId ='<input type="text"  class ="form-control" name="server">';
	//组装
	$html = <<<EOF
	{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								{$btnType}
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="stat_highest_online" id="complexForm"
        class="form-horizontal" onsubmit="return HighestOnlineVerify(this);">
                <div class="control-group" >
				<div class="controls">
					<table border=0>
						<tr> 
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td> 
						</tr>
						<tr>
						 <td><B>平台类型:</B>*&nbsp;</td>
						 <td>$serverInfo</td>
						 <td>&nbsp&nbsp
						 <button class="btn btn-info ladda-button" data-style="expand-right" name='sut' type='submit'>
		                 <span class="ladda-label" id="complexbtn_date">查询</span></td>
						 <td>&nbsp;</td>
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

/**
 * 玩家在线时长
 * **/
public static function  statOnlineTimeLengthHtml($data)
{
	$btnType = null;
	
	if(isset($data))
	{
		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
		 
		$btnType = '<input  type="button"  style="padding:0;width:120px"
    	class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>';
		$time = implode(',',$time);
	}
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 平台
	$serverInfo =  self::getplatListInfo2();
	$subbtn = '<button class="btn btn-info ladda-button" 
	data-style="expand-right" name="sut" type="submit">
    <span class="ladda-label" id="complexbtn_date">查询</span>'; 
	 
	//$serverId ='<input type="text"  class ="form-control" name="serverId">';
	$serverId = "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	
	$typenum = '<input type="text" style="width:20px" class="form-control" name="num">';
	$type ='<input type="text" style="width:35px" class="form-control" name="num">'."
	<select name='type' class='form-control' style='width:100px' title='*设置时间刻度'>
	<option value='0'>--时间刻度--</option>
	<option value='1'>日</option>
	<option value='2'>时</option>
	<option value='3'>分</option>
	<option value='4'>秒</option>
	</select>".'&nbsp;<input type="text" style="width:35px" class="form-control" name="num1">';
	$typenum1 = '<input type="text" style="width:20px" class="form-control" name="num1">';
	//组装
	$html = <<<EOF
	{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								{$btnType}
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="onlineTimeLength" id="complexForm"
        class="form-horizontal" onsubmit="return onlineTimeLengthVerify(this);">
                <div class="control-group">
				<div class="controls">
					<table border=0>
						<tr>
						 <td>开始时间:*</td>
						 <td>{$startTime}</td>
						 <td>截止时间:*</td>
						 <td>{$endTime}</td>
						</tr>
						<tr>
						 <td><B>平台类型:</B>*&nbsp;</td>
						 <td>$serverInfo</td>
						 <td>&nbsp;</td>
						 <td>{$serverId}</td>
						</tr>
						<tr>
				 		<td><B>时间刻度:</B>*&nbsp;</td>
				 		<td>$type</td>
				 		<td></td>
						<td>$subbtn</td> 		
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
//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<|
// 实时在线查询
public static function  statUserMailAnnalHtml($data=NULL){
	 
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 区服
	$server = self::getServerInfo('serverId');
	// 角色编号
	$RoleIndex ='<input type="text"  class ="form-control" name="RoleIndex">';
	
	$nikeName = '<input type="text"  placeholder="角色昵称" class ="form-control" name="nikeName">';
	$SubBtn = '';
	 
	//组装
	$html = <<<EOF
				 
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons"> 
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="stat_user_mail_annal" id="onlineRoleForm" class="form-horizontal"
        onsubmit="return StatMailAnnalVerify(this);">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>开始日期:&nbsp&nbsp</B></td>
						 <td>{$startTime}</td>
						 <td><B>截止日期:&nbsp&nbsp</B></td>
						 <td>{$endTime}</td> 
						 <td>&nbsp</td>
						</tr>
						<tr>
						<td><B>*区服编号:&nbsp&nbsp</B></td>
						 <td>{$server}</td>
						 <td><B>*角色编号:&nbsp&nbsp</B></td>
						 <td>{$RoleIndex}</td>
						 <td>{$nikeName}</td>
						 <td><button class="btn btn-info ladda-button"
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
// 
public static function statRealTimedataHtml() 
{
	// 开始时间
	$startTime ='<input type="text" class="datetimepicker form-control"
	placeholder="开始时间"  name="startTime"/>';
	// 截止时间
	$endTime ='<input type="text" class="datetimepicker form-control"
	placeholder="截止时间"  name="endTime"/>';
	// 区服
	$server =  "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;display:none' title='请选择区服'></select>";
	// 平台
	$plat = self::getplatListInfo2();
	// 角色编号
	$RoleIndex ='<input type="text"  class ="form-control" name="RoleIndex">';
	
	$SubBtn = '';
	
	//组装
	$html = <<<EOF
			
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="Stat_real_time" id="onlineRoleForm" 
        class="form-horizontal" onsubmit="return StatMailAnnalVerify(this);">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>平台:&nbsp</B></td>
						 <td>{$plat}</td>
						 <td>{$server}</td>						 
						 <td>{$startTime}</td>
						 <td><button class="btn btn-info ladda-button"
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
//
public static function statRankdiamondHtml($data)
{	 
	// 区服
	$server =  "<select name='serverId' id='ServerId'
	class='form-control' style='width:120px;
	display:none' title='请选择区服'></select>";
	// 平台
	$plat = self::getplatListInfo2();
	
	$rankRange = '<input  type="text" name="range" 
	value=100 placeholder="最低名次"  title="最低名次"/>';
	
	if(isset($data))
	{
		$readySubmitJs =self::readySubmit('ExportfileBtn','from1');
			
		$btnType = '<input  type="button"  style="padding:0;width:120px"
    	class="btn btn-success" value="导出Execl" id="ExportfileBtn"/>'; 
	}
	$SubBtn = NULL;	
	//组装
	$html = <<<EOF
				{$readySubmitJs}
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
								<div class="buttons">
								{$btnType}
								</div>
							</div>
	    <div class="widget-content">
        <form  method="POST" action="Stat_diamond_rank" 
        id="onlineRoleForm" class="form-horizontal">
                <div class="control-group" >
				<div class="controls" >
					<table border=0>
						<tr>
						 <td><B>平台:&nbsp</B></td>
						 <td>{$plat}</td>
						 <td>{$server}</td>
						 <td>{$rankRange}</td>
						 <td><button class="btn btn-info ladda-button"
		                 data-style="expand-right" type="submit" name='sut'>
		                 <span class="ladda-label">查询</span></td>
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
//~~~
}

?>
