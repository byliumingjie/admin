/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
		
		$("#roleType").click(function()
		{
			var roleType = $("#roleType").val();			
			if(roleType ==3)
			{				  
				$("#rolekey").attr("placeholder","请输入角色昵称");
			}
			if(roleType ==2){				 
				$("#rolekey").attr("placeholder","请输入角色Id");
			}			
		});
		 
		$("#banTimetype").click(function()
		{
			var banTimetype = $("#banTimetype").val();	
			 
			if(banTimetype ==1){				 
				$("#Banendtime").css('display','block');
			}			
			else{
				$("#Banendtime").css('display','none');
			}
		});
		
		$("#editlockType").click(function(){
			
			var lockType = $("#editlockType").val();
			
			if(lockType==1 || lockType==2)
			{
				var editbanTimetype = $("#editbanTimetype").val(); 
				 
	    		$("#editBanTimestatus").css('display','block'); 
	        	 
	        	if(editbanTimetype ==1){ 
	        		 
					$("#editBanEndTime").css('display','block');
				}			
				else{
					$("#editBanEndTime").css('display','none');
				}
			}else if(lockType ==3 || lockType ==4){ 
	    		$("#editBanTimestatus").css('display','none');
	    		$("#editBanEndTime").css('display','none');
			}
		});
		
		$("#editbanTimetype").click(function(){
			
			var banTimetype = $("#editbanTimetype").val();	
			 
			if(banTimetype ==1){				 
				$("#editBanEndTime").css('display','block');
			}			
			else{
				$("#editBanEndTime").css('display','none');
			}
		});
		
		$("#tableExcel tr").each(function() {
	        var _this = $(this);
	        _this.find(".currentData").click(function() { 
	        	// 平台
	        	var platId = $(this).parent().parent().find('[data-name=platId]').text();
	        	// 区服
	        	var serverId = $(this).parent().parent().find('[data-name=serverId]').text();
	        	// roleIndex
	        	var player_id = $(this).parent().parent().find('[data-name=player_id]').text();
	        	// description
	        	var description =  $(this).parent().parent().find('[data-name=description]').text();
	        	// lockEndtime
	        	var lockEndtime =  $(this).parent().parent().find('[data-name=lockEndtime]').text();
	        	// lockType
	        	var lockType =  $(this).parent().parent().find('[data-name=lockType]').text();
	        	// lockTimeType
	        	var lockTimeType =  $(this).parent().parent().find('[data-name=lockTimeType]').text();
	        	
	        	var nickname =  $(this).parent().parent().find('[data-name=nick_name]').text();
	        	
	        	$("#EditRoleBanForm [name=platId]").val(platId);
	        	$("#EditRoleBanForm [name=serverId]").val(serverId);
	        	$("#EditRoleBanForm [name=player_id]").val(player_id);
	        	$("#EditRoleBanForm [name=description]").val(description);
	        	$("#EditRoleBanForm [name=lockEndtime]").val(lockEndtime);
	        	$("#EditRoleBanForm [name=lockType]").val(lockType);
	        	$("#EditRoleBanForm [name=lockTimeType]").val(lockTimeType);
	        	$("#EditRoleBanForm [name=originalBanType]").val(lockType);
	        	$("#EditRoleBanForm [name=nick_name]").val(nickname);
	        	//
	        	if(lockType==1 || lockType==2)
	        	{ 
	        		$("#editBanTimestatus").css('display','block');
	        		
		        	setSelectOption('editRoleBanModal','lockType',lockType);
		        	//
		        	setSelectOption('editRoleBanModal','lockTimeType',lockTimeType);
		        	
		        	if(lockTimeType ==1){
		        		
		        		$("#EditRoleBanForm [name=lockEndtime]").val(lockEndtime);
						$("#editBanEndTime").css('display','block');
					}			
					else{
						$("#editBanEndTime").css('display','none');
					}
	        	}else if(lockType==3 || lockType==4){
	        		$("#editBanTimestatus").css('display','none');
	        		$("#editBanEndTime").css('display','none');
	        	}
	        	$("#editRoleBanModal").modal({backdrop:"static"}).modal('show');
	        	 
	        });
	    }); 
		
		// 禁封变更
		$("#editBanRoleBtn").click(function(){
			var param = $("#EditRoleBanForm").serializeArray();
			var platId = $("#EditRoleBanForm [name=platId]").val();
			var serverId =  $("#EditRoleBanForm [name=serverId]").val();
			var RoleIndex = $("#EditRoleBanForm [name=player_id]").val();
			
			var RoleBanType = $("#EditRoleBanForm [name=lockType]").val();
			
			RoleBanType = parseInt(RoleBanType);
			
			var banStatusInfo = null;
			 
			switch(RoleBanType)
			{
				case  1: banStatusInfo = '禁言'; break;
				case  2: banStatusInfo = '禁止登陆'; break;
				case  3: banStatusInfo = '解封禁言'; break;
				case  4: banStatusInfo = '解封登陆'; break;
			} 
			if (confirm("你确定要把 "+"角色Id"+RoleIndex+"进行"+banStatusInfo+"么?")) {
		        $.ajax({
		            type:'POST',
		            url:'/roleban/edit_roleBan_statu',
		            data:param,
		            dataType:'json',
		            success:function(result){
		                
		                if(result.errcode == 0){
		                	alert(result.msg);
		                    window.location.href = window.location.href;
		                }else{
		                	alert(result.msg);
		                	window.location.href = window.location.href;
		                }
		            }
		        });
	        }
		});
	// 封禁解除
		$("#UnlockBtn").click(function(){
			//var userid = $("#userModal").parent().parent().find(" [name=playerId]").text();
			//alert(userid);
	        var param = $("#userUnlockForm").serializeArray();
	        var playerId =0;
	        var server = 0;
	        var playerName='';
	         
	        $.each(param, function(i, field){
	        	 if(field.name == 'playerId'){
	        		 playerId = field.value;
	        	 }
	        	 switch(field.name){
	        	 	case 'playerId':playerId = field.value;break;
	        	 	case 'playerName':playerName = field.value;break;
	        	 	default :break;
	        	 }
	            //$("#results").append(field.name + ":" + field.value + " ");
	          });
	        
	        if (confirm("你确定要把 "+playerName+"角色Id"+playerId+"进行解封么?")) {
		        $.ajax({
		            type:'POST',
		            url:'/roleban/userUnlock',
		            data:param,
		            dataType:'json',
		            success:function(result){
		                
		                if(result.errcode == 0){
		                	alert(result.msg);
		                    window.location.href = window.location.href;
		                }else{
		                	alert(result.msg);
		                	window.location.href = window.location.href;
		                }
		            }
		        });
	        }
	    }); 
		
	// 封禁操作js定位信息
	$(window).load(function() 
	{
		$("#banIpMode").css('display','none');	
		$("#Banendtime").css('display','none');
	});	
	
	$('#bantype').change(function()
	{
		var type = $("#bantype").val(); 
		type = parseInt(type);
		 
		
		$('#banIpMode').css('display',( type==3 ) ? 'block' : 'none');
		
				
	}); 
	$("#bantype").click(function(){
		var type = $("#bantype").val(); 
		type = parseInt(type);
		
		if(type==3 ||type ==4)
		{
			$('#timetype').css('display','none');
			$('#Banendtime').css('display','none');
		}else if(type==1 || type ==2)
		{
			$('#timetype').css('display','block');
			
			var banTimetype = $("#banTimetype").val();
			
			if(banTimetype==2)
			{
				$('#Banendtime').css('display','none');
			}
			else{
				$('#Banendtime').css('display','block');
			}
			
			
		} 
	});
	// set lock 
	$("#lockBtn").click(function(){
		var platId = $("#lockFrom").find('select[name=platId]').val();
		 
		 if(platId=='' || platId==0 || platId == null ){
			 alert('平台不能为空');
			 return false;
		 } 
		 var server = $("#lockFrom").find('select[name=server]').val();
		 
		 if(server=='' || server==0 || server == null ){
			 alert('区服不能为空');
			 return false;
		 }
		 var roleType = $("#lockFrom").find('select[name=roleType]').val();
		 
		 if(roleType=='' || roleType==0 || roleType == null ){
			 alert('用户类型不能为空!');
			 return false;
		 }
		 var userVar = $("#lockFrom").find('input[name=userVar]').val();
		 if(userVar=='' || userVar==0 || userVar == null ){
			 alert('用户值不能为空!');
			 return false;
		 }
		 var bantype = $("#lockFrom").find('select[name=bantype]').val();
		 
		 if(bantype==1 || bantype ==2)
		 {
			 	
			 var banTimetype = $("#lockFrom").find('select[name=banTimetype]').val();
			 
			 if(banTimetype==1){
				 
				 var endtime = $("#lockFrom").find('input[name=endtime]').val();
				 
				 if(endtime=='' || endtime==0 || endtime == null)
				 {
					 alert('封禁截止时间不能为空!');
					 return false;
				 }
			 }
		 }
		/* var timetype = $("#lockFrom").find('select[name=timetype]').val();
		 
		 if(timetype=='' || timetype==0 || timetype == null ){
			 alert('时长类型不能为空!');
			 return false;
		 } */ 
		
		 var desc = $("#lockFrom").find('textarea[name=desc]').val();
		 if(desc=='' || desc==0 || desc == null ){
			 alert('原由不能为空!');
			 return false;
		 }		 
		 var roletypeNam = '';		 
		 switch( parseInt(roleType) )
		 {		 
		 	case 1:roletypeNam = '账号';break;
		 	case 2:roletypeNam = '角色Id';break;
		 	case 3:roletypeNam = '角色名';break;
		 	default:alert('角色类型错误!');return false; break;
		 }
		 var rolelockNam = '';
		 
		 switch( parseInt(bantype) )
		 {		 
		 	case 1:rolelockNam = '禁言';break;
		 	case 2:rolelockNam = '禁止登录';break;
		 	case 3:rolelockNam = '解封禁言';break;
		 	case 4:rolelockNam = '解封登陆';break;
		 	default:alert('封禁类型错误!');return false; break;
		 }
		 
		 if (confirm("你确定要把"+roletypeNam+"为"+userVar+" "+rolelockNam+"么?")) 
		 {
			var param = $("#lockFrom").serializeArray();
			$.ajax({
	            type:'POST',
	            url:'/roleban/roleLock',
	            data:param,
	            dataType:'json',
	            success:function(result){
	                alert(result.msg);
	                if(result.errcode == 0)
	                {
	                    window.location.href = window.location.href;
	                }
	            },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                	alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus); // paser error;
                }
		    });
		 }
	});
	
    //封号
   $("#confirmBtn").click(function(){ 
	   
	    /*批量导入*/ 
	    var listrole = getlistRole();	    
        /*单独引入获取*/
	    var rolestr ='';
	    //角色ID
        var roleid = $("#roledata").find('input[name=roleid]').val();
        //区服
        var sid = $("#roledata").find('input[name=sid]').val();
        /* 验证*/
        if(roleid!='' && sid!='' ){
        	var rolestr = roleid+","+sid;
        }
        if(listrole==''  &&  rolestr==''){        	
        	alert("角色属性不能为空!");
        	return false;
        }
        if(listrole=="" && rolestr!=""){        	
        	listrole = rolestr;
        }        
        $("#forbidAccountForm input[name=listroleid]").val(listrole);
        var param = $("#forbidAccountForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/roleban/save',
            data:param,
            dataType:'json',
            success:function(result){
        		
        		if(result.errcode == 0)
        		{   
        			alert("操作成功！");     
        			//alert(result.msg);
        			$("#forbidAccountModal").modal({backdrop:"static"}).modal('hide');
        			$('#roleinfo').html("");
        			
        			// 此次获取是要进行跳转到一个日志记录表后期就不需要这些了 end ...html(...);
        			var data = result.msg; 
        			alert(data[0].id);
        			GetPage(data);
        			/*var pagehtml = "<tr><th>选项</th><th>区服</th><th>执行成功角色ID 操作状态("+status+")</th></tr>";
        			alert("222");
        			
        			for(var i = 0; i< data.length;++i)
  	              	{          				
  	                  	pagehtml += '<tr id = '+data[i].id+'><td><div class="checkbox"><label class="checkbox-inline"><input name="checkboxid" type="checkbox" value = "'+data[i].id+'"checked="true" > </label></div></td>';
  	                  	pagehtml += '<td>'+data[i].sid+'</td>';
  	                  	pagehtml += '<td>'+data[i].roleid+'</td></tr>'; 
  	              	} 
        			alert("333");
        			$('#roleinfo').html(pagehtml)*/
                }else{
                	alert('false');
					alert(result.msg);
					return false;
                	//window.location.href = window.location.href;
                	
                }
            }
        });
    });
   // 获得check 为 true项的数据追加到数组里
   function getlistRole()
   {
       var arrListRole = new Array();
       var checkbox = document.getElementsByName('checkboxid');
       for (var i = 0; i < checkbox.length; i++) {
           if( checkbox[i].checked == true)
           {
              var roleid =  $("#"+checkbox[i].value).children().last().text();
              var sid = $("#"+checkbox[i].value).children().prev().text();
              alert(roleid);
              arrListRole.push(new Array(roleid,sid));
              alert(arrListRole);
           }
       }
      return arrListRole;
   }
   
	/*,
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
            	alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus); // paser error;
            }*/
    //禁言
    $("#confirmforbidBtn").click(function(){
    	
    	  if($("#forbidTlakForm input[name=starttime]").val() == ''){
              alert('请选择禁言日期!');
              return false;
          }
        if($("#forbidTlakForm select[name=talktype]").val() == ''){
            alert('请选择禁言类型!');
            return false;
        }
        /*批量导入*/ 
	    var listrole = getlistRole();	    
        /*单独引入获取*/
	    var rolestr ='';
	    //角色ID
        var roleid = $("#roledata").find('input[name=roleid]').val();
        //区服
        var sid = $("#roledata").find('input[name=sid]').val();
        /* 验证*/
        if(roleid!='' && sid!='' ){
        	var rolestr = roleid+","+sid;
        }
        if(listrole==''  &&  rolestr==''){        	
        	alert("角色属性不能为空!");
        	return false;
        }
        if(listrole=="" && rolestr!=""){        	
        	listrole = rolestr;
        }        
        $("#forbidTlakForm input[name=listroleid]").val(listrole);
        
        var param = $("#forbidTlakForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/roleban/save',
            data:param,
            dataType:'json',
            success:function(result){
                
                if(result.errcode == 0){
                	alert('操作成功！');
                	$("#forbidTalkModal").modal({backdrop:"static"}).modal('hide');
        			$('#roleinfo').html("");
        			
        			// 此次获取是要进行跳转到一个日志记录表后期就不需要这些了 end ...html(...);
        			var data = result.msg; 
        			//alert(data[0].id);
        			GetPage(data);
                    //window.location.href = window.location.href;
                }
            }
        });
    });
    


	//显示模态框
     $("#addName").click(function(){     
     $("#addFileModal").modal({backdrop:"static"}).modal('show');
     });
    
    
    // 创建一个上传参数(提交至本控制层的函数addExecl进行处理上传其次判断是否成功，成功返回)
    var uploadOption =
    {
        // 提交目标
        action: "uploadfile",
        // 服务端接收的名称
        name: "myfile",
        // 自动提交
        autoSubmit: false,
        // 选择文件之后…
        onChange: function (file, ext) {
            
            if (!(ext && /^(xml|XML|txt)$/.test(ext))) {  
                alert("您上传的文档格式不对，请重新选择！");  
                return false;  
            } 
            $("#state").val( file);
        },
        // 开始上传文件
        onSubmit: function (file, extension) {
            $("#state").val("正在上传" + file + "..");
        },

        // 上传完成之后
        onComplete: function (file, response) {
            $("#state").val("上传完成");
               
            var data = JSON.parse(response);
            
            /**
			* 这个地方判断的是在控制器里面的$this->outputJson目录在（\framework\system\libraries\controller.lib） 
			* 找到这个函数里面的设置所引用的参数进行判断
			**/
            if(data.errcode == 0 )
            {
            	 
                $("#addFileModal").modal({backdrop:"static"}).modal('hide');
                //处理接收到的数据
               GetPage(data.msg);
            }else{
            	 
            	/**
            	 * 其实在$this->outputJson 本身的echo json_encode($rs);
            	 * 是不会提示的调用js进行把在控制器里面传入的第一个变量值所做的引用如果是非0就会进行提示相关错误的信息
            	 * */
            	//alert(1);
                alert(data.msg);
            }
        }
    };

    /***
     * （点击确定按钮执行-》执行控制层函数-》addExecl()进行处理上传文件其次进行读取相关数据进行解析然后在进行返回结果集）
     * */
   /* var oAjaxUpload = new AjaxUpload('#selector', uploadOption);
    
    // 给上传按钮增加上传动作
    $("#addFileBtn").click(function ()
    {
        oAjaxUpload.submit();
    });*/
    
 
    
     function GetPage(data)
     {
    	// alert(111);
    	 $('#roleinfo').html("");     
      var pagehtml = "<script>$('#checkall').click(function(){" +
	 		"if(this.checked){" +
	 		" $('#listrole :checkbox').attr('checked', true);" +
	 		"}else{" +
	 		"$('#listrole :checkbox').attr('checked', false);" +
	 		"}});" +
	 		"</script>" +
	 		"<input type='hidden' name='tr_id'/>" +
	 		"<table class='table table-bordered table-striped'  id='listrole'>" +
	 		"<tr>" +
	 		"<th style='width:5%'><input name='checkboxid' id='checkall' " +
	 		"type='checkbox' style='margin:0px'>&nbsp选项(全选/取消)</th>" +	 		
	 		"<th>区服</th><th>角色ID</th>";
      		//alert(data[0].status);
      		/*if(data[0].status!=''){
      			pagehtml+="<th>状态</th>";
      		}*/
      		pagehtml+="</tr>";
      		 
      		var statusinfo = '';
      		//alert(222);	 
       for(var i = 0; i< data.length;++i)
        {
    	   	
    	    
            pagehtml +="<tr id ="+data[i].id+">" +
    		"<td><div class='checkbox'>" +
    		"<label class='checkbox-inline'>" +
    		"<input name='checkboxid' " +
    		"type='checkbox' value = "+data[i].id+">" +
    		"</label>" +
    		"</div></td>" +
    		"<td>"+data[i].sid+"</td>" +
    		"<td>"+data[i].roleid+"</td></tr>";
            
           /* var status = data[i].status;          
            
			if(data[i].status)
			{   
				var status = data[i].status
				//alert(status);
				if(status=2){
					statusinfo="普通封号";
				}
				if(status=1){
					statusinfo="解除封号";
				}
				//alert(statusinfo);
				pagehtml +="<td>"+statusinfo+"</td>";
			}
			
            pagehtml +="</tr>";*/
      
        }     
        	pagehtml += "</table>";
        	//alert(333);	 
        $('#roleinfo').html(pagehtml)
               
     }
    
    var checkbox = document.getElementsByName('checkboxid');
    
    //全选
    $("#checkAll").click(function() {
        for (var i = 0; i < checkbox.length; i++) {
            checkbox[i].checked = true;
        }
    });

    //取消全选
    $("#checkNone").click(function() {
        for (var i = 0; i < checkbox.length; i++) {
            checkbox[i].checked = false;
        }
    });
    
    //封号
/*    $("#confirmBtn").click(function(){ 
         var listrole = getlistRole();
         $("#forbidAccountForm input[name=listroleid]").val(listrole);
         var param = $("#forbidAccountForm").serializeArray();
         $.ajax({
             type:'POST',
             url:'/roleban/save',
             data:param,
             dataType:'json',
             success:function(result){
         		
         		if(result.errcode == 0)
         		{   
         			alert("操作成功！");        		
         			$("#forbidAccountModal").modal({backdrop:"static"}).modal('hide');
         			$('#listrole').html("");
         			
         			var data = result.msg; 
         			var status = null;
         			
         			switch(data[0].status){ 
     				case "2":status = "普通封号";break;
     				case "0":status = "解封账号";break;
     				}
         			 
         			var pagehtml = "<tr><th>选项</th><th>区服</th><th>执行成功角色ID 操作状态("+status+")</th></tr>";
         			
         			
         			for(var i = 0; i< data.length;++i)
   	              	{          				
   	                  	pagehtml += '<tr id = '+data[i].id+'><td><div class="checkbox"><label class="checkbox-inline"><input name="checkboxid" type="checkbox" value = "'+data[i].id+'"checked="true" > </label></div></td>';
   	                  	pagehtml += '<td>'+data[i].sid+'</td>';
   	                  	pagehtml += '<td>'+data[i].roleid+'</td></tr>'; 
   	              	} 
         			$('#listrole').html(pagehtml)
                 }else{
 					alert(result.msg);
                 	window.location.href = window.location.href;
                 }
             }
         });
     }); */
    	/*,
         error:function(XMLHttpRequest, textStatus, errorThrown)
         {
         	alert(XMLHttpRequest.status);
             alert(XMLHttpRequest.readyState);
             alert(textStatus); // paser error;
         }*/
     //禁言
/*     $("#confirmforbidBtn").click(function(){
         if($("#forbidTlakForm select[name=talktype]").val() == ''){
             alert('请选择禁言类型!');
             return false;
         }
         var listrole = getlistRole();
         $("#forbidTlakForm input[name=listroleid]").val(listrole);
         var param = $("#forbidTlakForm").serializeArray();
         $.ajax({
             type:'POST',
             url:'/gameuser/forbidTalk',
             data:param,
             dataType:'json',
             success:function(result){
                 alert(result.msg);
                 if(result.errcode == 0){
                     window.location.href = window.location.href;
                 }
             }
         });
     });
     */
     function getlistRole()
     {
         var arrListRole = new Array();
         var checkbox = document.getElementsByName('checkboxid');
         for (var i = 0; i < checkbox.length; i++) {
             if( checkbox[i].checked == true)
             {
                var roleid =  $("#"+checkbox[i].value).children().last().text();
                var sid = $("#"+checkbox[i].value).children().prev().text();
                arrListRole.push(new Array(roleid,sid));
             }
         }
        return arrListRole;
     } 
});
function setSelectOption(byId,byName,type,ifclick=false)
{ 
	var count=$("#"+byId+" option").length;
	
	var name =name+'[]';
	
	for(var i=0;i<count;i++)  
	{ 
		  var optionstr = $("#"+byId).find('select[name="'+byName+'"]').get(0).options[i].value;

		  optionstr = parseInt(optionstr);
		  
		  if(optionstr == type)  
	      {  
			$("#"+byId).find('select[name="'+byName+'"]').get(0).options[i].selected = true; 
	        break;  
	      } 
		  
	 }	  
	
	
}
// 封禁
function roleBanVerify(obj)
{ 
	$("#btn_date").removeAttr("disabled");
 	$("#btn_date").removeAttr("data-loading");
 	
	var lockStatus = $(obj).find('select[name=lockStatus]').val();	 
	 if(lockStatus == 0 || lockStatus == null || lockStatus == '')
	 {
	        alert("禁用类型不能为空！");
	        location.reload( true );
	        return false;
	 }	
	 
	 var platId = $(obj).find('select[name=platId]').val();
	  
	 if(platId == 0 || platId == null || platId == '')
	 {
	        alert("平台不能为空！"); 
	        return false;
	 }  
	 
	 var server = $(obj).find('select[name=server]').val();
	  
	 if(server == 0 || server == null || server == '')
	 {
	        alert("区服不能为空！");
	         location.reload( true );
	        return false;
	 }  
	 
}
//封禁日志
function roleBanLogVerify(obj)
{ 
	  
	 var platId = $(obj).find('select[name=platId]').val();
	  
	 if(platId == 0 || platId == null || platId == '')
	 {
	        alert("平台不能为空！"); 
	        return false;
	 }  
	 
	 var server = $(obj).find('select[name=server]').val();
	  
	 if(server == 0 || server == null || server == '')
	 {
	        alert("区服不能为空！");
	         location.reload( true );
	        return false;
	 }  
	 
}