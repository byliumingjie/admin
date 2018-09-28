/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {

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
                alert(data.msg);
            }
        }
    };

    /***
     * （点击确定按钮执行-》执行控制层函数-》addExecl()进行处理上传文件其次进行读取相关数据进行解析然后在进行返回结果集）
     * */
    var oAjaxUpload = new AjaxUpload('#selector', uploadOption);
    
    // 给上传按钮增加上传动作
    $("#addFileBtn").click(function ()
    {
        oAjaxUpload.submit();
    });
                
     function GetPage(data)
     {
         $('#listrole').html(""); 
         
         var pagehtml = "<tr><th>选项</th><th>区服</th><th>角色ID</th></tr>";
               for(var i = 0; i< data.length;++i)
                {
                    pagehtml += '<tr id = '+data[i].id+'><td><div class="checkbox"><label class="checkbox-inline"><input name="checkboxid" type="checkbox" value = "'+data[i].id+'"checked="true" > </label></div></td>';
                    pagehtml += '<td>'+data[i].sid+'</td>';
                    pagehtml += '<td>'+data[i].roleid+'</td></tr>';   
                } 
                $('#listrole').html(pagehtml)
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
    $("#confirmBtn").click(function(){ 
         var listrole = getlistRole();
         $("#forbidAccountForm input[name=listroleid]").val(listrole);
         var param = $("#forbidAccountForm").serializeArray();
         $.ajax({
             type:'POST',
             url:'/rolesupervise/save',
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
     }); 
    	/*,
         error:function(XMLHttpRequest, textStatus, errorThrown)
         {
         	alert(XMLHttpRequest.status);
             alert(XMLHttpRequest.readyState);
             alert(textStatus); // paser error;
         }*/
     //禁言
     $("#confirmforbidBtn").click(function(){
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
