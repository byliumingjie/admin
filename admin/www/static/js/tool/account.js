/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
	setInterval(function() {          
        updataRoleStatus();
    }, 1000*60);
	//修改服务器
	  
	function loadVariable(formId,modeId)
	{ 
		var userName = $("#userName").text();
		var userid  =  $("#userid").text();		 
		var server = $("#server").text();
		$("#"+formId+" [name=name]").val(userName);
		$("#"+formId+" [name=userid]").val(userid);		 
		$("#"+formId+" [name=server]").val(server);		
        $("#"+modeId+"").modal({backdrop:"static"}).modal('show');		
	}
	 
   /**
    * 货币简校验
    ** */
   function  userInfoProcess(status)
   {
	   var property =null;
	   var formId = null;
	   var propertyName = null;
	   
	   switch(status)
	   {
	   	case 1:  property = 'gold'; 
	   			 formId = "editAccountGoldForm";
	   			 propertyName ='金币';
	   			 break;
	   	case 2:  property = 'sugar';
	   			 formId = "editsugarForm";
	   			propertyName ='棒棒糖';
	   			 break;
	   	case 3:  property = 'pill';
	   			 formId = "editpillForm";
	   			 propertyName ='药丸';
	   			 break;
	   	case 4:  property = 'vip';
	   			 formId = "editvipForm";
	   			 propertyName ='vip等级';
	   			 break;
	   	default: property = null;
	   			 formId = null;
	   			 break;   	
	   }
	   
	   if( property == null && formId == null )
	   {
		  alert('设置为空的属性,请检查所更改的属性系统是否更新!');
	   }	    
	   //var amount = Number($("#amount").text());
	   var oldAmount = Number($("#"+property+"").text());
	   
	   var userName =  $("#userName").text();
	   
	   var userid =  $("#userid").text();	
	   
	   var Type = $("#"+formId+" input[name='"+property+"Type']:checked").val();
	   
	   if( Type =='' || Type==0 || Type==false || Type==null )
	   {
           alert(propertyName+'空的选项类型，请填写选项类型!');
           return false;
       }	   
	   var amount = Number($("#"+formId+" input[name=amount]").val());
	   
	   if( amount=='' && amount<0 )
	   {
           alert('请输入'+propertyName+'，且不能小于<0');
           return false;
       }
	   
        var param = $("#"+formId+"").serializeArray();
        
        var statusInfo = Type==3001?"提升":"下降";
        
       if(Type==3003)
 	   { 
 		  if( amount>oldAmount ){
 			  alert(propertNyame+'超出上限!');
 	          return false;
 		  }  
 	   }
        
        var prompt = userid+statusInfo+propertyName+"为"+amount+"么?";
        
	 	if (confirm("你确定要给 "+userName+"角色ID为"+prompt)) 
	 	{  
	        $.ajax({
	            type:'POST',
	            url:'/account/editmoney',
	            data:param,
	            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0){
	                    window.location.href = window.location.href;
	                }
	            }
	        });
	 	  }
   }
   
   $("#coseGuide").click(function()
		   {
	   var PlayerId =  $("#userid").text();	 
	   var ServerId =  $("#server").text();
	   var userName =  $("#userName").text();
	   var ifonline = $("#LevelPropsForm input[name=ifonline]").val();	   
	   ifonline =  parseInt(ifonline);
	   
	   if(ifonline==1)
	   {
		   alert('在线玩家信息禁止更改,请退出重试');
		   return false;
	   }
	   if(PlayerId=='' || PlayerId==null || PlayerId=='undefined'){
		   
		   alert('玩家数据为空!不可更改');
		   return false;
	   }
	   if (confirm("你确定要关闭,"+userName+"正在进行中的引导么？")) 
	   { 
		   $.ajax({
	           type:'POST',
	           url:'/account/roleCoseGuide',
	           data:"ServerId="+ServerId+"&PlayerId="+PlayerId,
	           dataType:'json',
	           success:function(result){
	        	   
	        	   alert(result.msg);
	        	   
	               if(result.errcode == 0){            	   
	            	   location.reload();
	               } 
	           }
	       });
	 	}
   });
    
    // 等级变更
   $("#LevelPropbtn").click(function() {
	   var PlayerId =  $("#userid").text();	 
	   var ServerId =  $("#server").text();
	   var Roleaccount =  $("#Roleaccount").text();
	   
	   var ifonline = $("#LevelPropsForm input[name=ifonline]").val();	   
	   ifonline =  parseInt(ifonline);
	   
	   if(ifonline==1)
	   {
		   alert('在线玩家信息禁止更改,请退出重试');
		   return false;
	   }
	   if(PlayerId=='' || PlayerId==null || PlayerId=='undefined'){
		   
		   alert('玩家数据为空!不可更改');
		   return false;
	   }
	   $.ajax({
           type:'POST',
           url:'/account/roleOnlineStatusVerif',
           data:"ServerId="+ServerId+"&PlayerId="+PlayerId,
           dataType:'json',
           success:function(result){
               
               if(result.errcode == 0){            	   
            	   $("#LevelPropsForm [name=ServerId]").val(ServerId);
            	   $("#LevelPropsForm [name=PlayerId]").val(PlayerId);
            	   $("#LevelPropsForm [name=RoleAccount]").val(Roleaccount); 
            	   
                   $("#levelPropmodal").modal({backdrop:"static"}).modal('show');
               }else{
            	   alert(result.msg);
               }
           }
       });
	   
	  
   });
   // levelPropsBtn
$("#levelPropsBtn").click(function(){
	   var userName =  $("#userName").text();
	   var PlayerId =  $("#userid").text();	 
	   var ServerId =  $("#server").text();
	   var Roleaccount = $("#Roleaccount").text();
	   
	   var level = $("#LevelPropsForm input[name=uplevel]").val(); 
	     
	   if (confirm("你确定要给 "+userName+"角色ID为"+PlayerId+"变更等级么?")) 
	   {  
		   var param = $("#LevelPropsForm").serializeArray();
		   
	        $.ajax({
	            type:'POST',
	            url:'/account/editRoleInfo',
	            data:param,
	            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0){
	                    window.location.href = window.location.href;
	                }
	            }
	        });
	 	 }	   
   });
	/**
	 * 体力变更
	 ***/ 
	$("#BodyStrength").click(function() {
		   var PlayerId =  $("#userid").text();	 
		   var ServerId =  $("#server").text();
		   var Roleaccount =  $("#Roleaccount").text();
		   
		   $("#BodyStrengthForm [name=ServerId]").val(ServerId);
		   $("#BodyStrengthForm [name=PlayerId]").val(PlayerId);
		   $("#BodyStrengthForm [name=RoleAccount]").val(Roleaccount); 
		   
	    $("#BodyStrengthmodal").modal({backdrop:"static"}).modal('show');
	});
	$("#BodyStrengthBtn").click(function(){
		   var userName =  $("#userName").text();
		   var PlayerId =  $("#userid").text();	 
		   var ServerId =  $("#server").text();
		   var Roleaccount = $("#Roleaccount").text(); 
		     
		   var strength = $("#BodyStrengthForm input[name=strength]").val(); 
		   
		   if(strength<0 || strength=='' || strength==false ||  strength==null){
			   alert('请选择体力!');
	        return false;
		   }  
		   if (confirm("你确定要给 "+userName+"角色ID为"+PlayerId+"变更体力么?")) 
		   {  
			   var param = $("#BodyStrengthForm").serializeArray();
			   
		        $.ajax({
		            type:'POST',
		            url:'/account/editBodyStrength',
		            data:param,
		            dataType:'json',
		            success:function(result){
		                alert(result.msg);
		                if(result.errcode == 0){
		                    window.location.href = window.location.href;
		                }
		            }
		        });
		 	 }	   
	});
   // 金币变更提交
   $("#goldAccountBtn").click(function(){
	   userInfoProcess(1); 
   });
   // 棒棒糖变更提交
   $("#sugarBtn").click(function(){ 
	   userInfoProcess(2);  
    });
   // 药丸变更提交
   $("#pillBtn").click(function(){
	   userInfoProcess(3); 
   });
   // vip变更提交
   $("#vipBtn").click(function(){
	   userInfoProcess(4); 
   });
   // 道具更改
   $("#addPropbtn ").click(function() {
	   var userid =  $("#userid").text();	 
	   var server =  $("#server").text(); 
	   $("#PropsForm [name=userid]").val(userid);
	   $("#PropsForm [name=server]").val(server); 
       $("#addPropmodal").modal({backdrop:"static"}).modal('show');
   });
   
   // 道具变更提交
   $("#PropsBtn").click(function(){
	   
	   var userName =  $("#userName").text();
	   
	   var userid =  $("#userid").text();	
	   
	   var propsid = $("#PropsForm input[name=propsid]").val();
	   var propsType = $("#PropsForm input[name='propsType']:checked").val(); 
	   var propsNum = Number($("#PropsForm input[name=propsNum]").val());
	   
	   if(propsid<0 || propsid=='' || propsid==false ||  propsid==null){
		   alert('请选择道具Id!');
           return false;
	   }
	   if(propsType =='' || propsType==0 || propsType==false || propsType==null ){
           alert('请选择道具修改类型!');
           return false;
       }
	   if( propsNum== '' && propsNum <=0 )
	   {
           alert('请选择道具数量!');
           return false;
       }	   
	   var statusInfo = propsType==2001?"增加":"扣除";
	   
	   if (confirm("你确定要给 "+userName+"角色ID为"+userid+statusInfo+"道具么?")) 
	   {  
		   var param = $("#PropsForm").serializeArray();
		   
	        $.ajax({
	            type:'POST',
	            url:'/account/editProps',
	            data:param,
	            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0){
	                    window.location.href = window.location.href;
	                }
	            }
	        });
	 	 }	   
   });
   
   // 公告model
   $("#addnoticebtn").click(function() {
	    var userid =  $("#userid").text();	 
		var server =  $("#server").text();
		
		$("#noticeForm [name=userid]").val(userid);
		$("#noticeForm [name=server]").val(server);
	 
       $("#addnoticemodal").modal({backdrop:"static"}).modal('show');
   });
   // 公告发布
   $("#noticeBtn").click(function(){
	   
	   var userName =  $("#userName").text();	   
	   var userid =  $("#userid").text();	
	   
	   var message = $("#noticeForm textarea[name=message]").val();
	   var loopTimes = $("#noticeForm input[name='loopTimes']").val(); 
	    
	   if(message=='' || message==false ||  message==null){
		   alert('请输入公告内容!');
           return false;
	   }
	   if(loopTimes =='' || loopTimes==0 || loopTimes==false || loopTimes==null ){
           alert('请输入循环次数!');
           return false;
       } 
	   if (confirm("你确定要给 "+userName+"角色ID为"+userid+"发布公告么?")) 
	   {  
		   var param = $("#noticeForm").serializeArray();
		   	
	        $.ajax({
	            type:'POST',
	            url:'/account/sendUserNotice',
	            data:param,
	            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0){
	                    window.location.href = window.location.href;
	                }
	            }
	        });
	 	 }	   
   });
   
$("#kickedOut").click(function(){
   var userName =  $("#userName").text();	   
   var userid =  $("#userid").text();	 
   var server =  $("#server").text();
   if (confirm("你确定要把 "+userName+"角色ID为"+userid+"踢出下线么?")) 
   {    
	   var jsondata = '{"server":'+server+',"userid":'+userid+'}';
	    
        $.ajax({
            type:'POST',
            url:'/account/kickedOut',
            data:jsondata,
            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0){
	                    window.location.href = window.location.href;
	                }
	            }
	        }); 
	   }	   
   });
//<<<<<<<<<<<<<<<<<<<<<<<<<<<
});
function updataRoleStatus(){
   var PlayerId =  $("#userid").text();	 
   var ServerId =  $("#server").text();
   var userName = $("#userName").text();	    
   var userHtml = null;
	  
	if(PlayerId!='' && ServerId!=''){
		   $.ajax({
	        type:'POST',
	        url:'/account/roleOnlineStatusVerif',
	        data:"ServerId="+ServerId+"&PlayerId="+PlayerId,
	        dataType:'json',
	        success:function(result){
	        	 
	        	if(result.ifonline==0)
	        	{	
	        		$("#userName").text("");
	        		$("#LevelPropsForm input[name=ifonline]").val(0);
	        		// 离线	  echo"<td class='success' id='userName'>".$playerName."&nbsp;".$onlineName;
	        		 userHtml = result.playerName+"&nbsp;" +
	        				"<font style='color: #99a09b;'>(离线)</font>";
	        		$("#userName").append(userHtml);
	        	}
	        	if(result.ifonline==1){
	        		$("#userName").text("");
	        		$("#LevelPropsForm input[name=ifonline]").val(1);
	        		 userHtml = result.playerName+"&nbsp;" +
    				" <font style='color: #48a260;'>(在线)</font>";
	        		$("#userName").append(userHtml);
	        		// 在线
	        	}
	        	 
	        }
	    });
	}
}
