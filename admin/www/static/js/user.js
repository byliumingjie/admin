$(function() {
	
    $(".editpasswordtype").click(function()
    {   
    	$("#upUidtypeId").val(1);
    	$("#upPassswordREy").val(null);
    	$("#upPassswordREy").removeAttr("readonly");
    	 
    });
    //添加用户
    $("#addUserBtn").click(function() {
    	
       if($("#addUserForm input[name='account']").val() == ''){
           alert("帐号不能为空");
           return false;
       }
       //密码and确认密码
       var password = $("#addUserForm input[name='password']").val();       
       
       if(password == '')
       {
           alert("密码不能为空！");
           return false;
       }
       var password2 = $("#addUserForm input[name='password2']").val();
       
       if(password2 == '')
       {
           alert("请确认密码！");
           return false;
       }
       //
       if(password != password2)
       {
    	   alert("密码输入不一致请重新输入！");
           return false;
       }
       if($("#addUserForm select[name='roleid']").val() == 0){
           alert("请选择所属组");
           return false;
       }
       
        var param = $("#addUserForm").serialize();
        var url = $("#addUserForm").attr('action');
        $.ajax({
            type: 'POST',
            url: 'addUser',
            data: param,
            dataType:' json',
            success: function(result) {
            	  
                if (result.errcode == 0) {
                	alert("添加成功！");  
                    //window.location.href = window.location.href;
                	 
                    window.location.reload();
                }
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
            	alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(errorThrown);
                alert(textStatus); // paser error;
            }
        });
    });
    //
    
    //动态传参 编辑 遍历     
    /*$("#rolelists tr td a").click(function() {    	 
    	var uid = $(this).attr("lick-uid");
    	var account = $(this).attr("lick-account");
    	//display-userAccount
    	//display-userUid
    	$('#display-userUid').val(uid);
    	$('#display-userAccount').val(account);
    	//$('#display-userAccount-look').val(account);
    	$("#display-userAccount").attr("value",account);
    	
     });*/
    $("#rolelists tr td").each(function() {
        var _this = $(this);
        _this.find("#editUser").click(function() {
        	var editPermission =null;
            var form = $("#updatUserForm").serializeArray();
            for(var i=0;i<form.length;i++)
            {  
            	var value = null;
            	var name = form[i].name;  
            	if(name ==null ||name =="" || name=="updatPermission[]")
                {
                    continue;
                }
            	if(name=="updatRoleid")
            	{
            		value = $(this).parent().parent().find('[data-name=updatRoleid]').text();
            		optionVerifySet("rolegroup",parseInt(value));
            		
            	}
            	else if(name=="editPermission"){
            		value = $(this).parent().parent().find('[data-name='+name+']').text();
            		editPermission = value;
            	}
            	else{
            		value = $(this).parent().parent().find('[data-name='+name+']').text();
            		$("#updatUserForm [name="+name+"]").val(value);
            	}
            }  
            $("#upPassswordREy").attr("readonly","readonly");
            $("#upUidtypeId").val(0);
            $("#editUserModal").modal({backdrop:"static"}).modal('show');
            setSidVerify(editPermission);
        }); 
    });
    
    //编辑账号
    $("#editUserBtn").click(function() {    	 
         
       //密码and确认密码
       var password = $("#updatUserForm input[name='updatPassword']").val();
       if(password == '')
       {
           alert("密码不能为空！");
           return false;
       }       
       if($("#updatUserForm select[name='updatRoleid']").val() == 0){
           alert("请选择所属组");
           return false;
       }
       var param = $("#updatUserForm").serialize();
       var url = $("#updatUserForm").attr('action');
       $.ajax({
           type: 'POST',
           url: url,
           data: param,
           dataType: 'json',
           success: function(result) {
        	   alert(result.msg);
               if (result.errcode == 0) {
                    
                   window.location.reload();
               }
           }
       });
    });
    
    //编辑用户组 
    $("#rolelists select[name=roleid]").change(function(){
        var url = '/user/editUser';
        var account = $(this).parent().parent().find("td[data-name=account]").text();
        var roleid = $(this).val();
        var param = 'account='+account+'&roleid='+roleid;
        $.ajax({
            type: 'POST',
            url: url,
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode != 0) {
                    return false;
                }
            }
        });
    });
   
    $(".delUser").each(function() {
        var _this = $(this);
        _this.click(function() {
            if (confirm("确定删除此用户么？")) {
                var uid = $(this).attr('data-value');
                if (uid == '' || uid == 'undefined') {
                    alert("uid不能为空!");
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '/user/delUser',
                    data: 'uid='+uid,
                    dataType: 'json',
                    success: function(result) {
                        alert(result.msg);
                        if (result.errcode == 0) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }); 
});
// ~~~~~ 
function optionVerifySet(byid=null,type=null,display=false,serverId=null)
{ 
  var count=$("#"+byid+" option").length;
  for(var i=0;i<count;i++)  
  {     
	  var optionstr = $("#"+byid).get(0).options[i].value;
	  
	  if(optionstr == type)  
      {  
        $("#"+byid).get(0).options[i].selected = true; 
        break;  
      } 
  } 
}
function setSidVerify(id)
{ 
	$("#liEditOption").empty();
	$("#updatPermissionms2side__sx").empty();
	$("select[name='updatPermissionms2side__dx[]']").empty();
	
	var IdList = id;
	var ifUpserverType = 1;
	
	if(ifUpserverType==1 && IdList!="") 
	{   
		var param ='platformId='+IdList+'&serverType=1';        	 
		 $.ajax({
		 type:'POST',
		 url:"/System/ServerPlatSolveCfg",
		 data:param,
		 dataType:'json',
		 success: function(result) { 
             if (result.errcode != 0) 
             {   
            	 alert('-1'+result.msg);
                 return false;
             }  
             sidSelectList(IdList,1,result.beenselect,result.waitselect);
         }
		 }); 
	}	
	else{ 
		$("#liEditOption").empty();
    	$("#updatPermissionms2side__sx").empty();
    	$("select[name='updatPermissionms2side__dx[]']").empty();
    	$('.ms2side__div').css('display','none');
		return false; 
	}
}
function sidSelectList(id,type,beenselect,waitdata){
	// 
	
	var default_SelectList = ""; // 默认选区
	var been_SelectedList= "";//已经被选 
	var wait_SelectList = ""; // 等待选择
	if(type==1)
	{
	// 已经选的 + 初始默认区更改状态的
	$.each(beenselect, function(index, value) { 
		default_SelectList +="<option value="+value[0]+" selected='selected'>"+value[1]+"</option>";
		been_SelectedList+="<option value="+value[0]+">"+value[1]+"</option>";
	}); 	
	// 待选区域Id updatPermissionms2side__sx + 默认去状态初始属性不增加  selected='selected'
	$.each(waitdata, function(index, value) 
	{
		default_SelectList +="<option value="+value[0]+">"+value[1]+"</option>";
		wait_SelectList +="<option value="+value[0]+">"+value[1]+"</option>";
	});
	// 已选区域Id serverIdms2side__dx[]
	  
 	//html2="<option value='5'>5区</option>";
 	
 	$('.ms2side__div').css('display','block');
 	// 【默认展示列表信息主控制】 根据平台 不存在的进行赋值如果已经存在的进行设置 selected='selected'
 	$("#liEditOption").append(default_SelectList); 
 	// 【待选区设置还没有选择的区配置】
 	$("#updatPermissionms2side__sx").append(wait_SelectList); 	 
 	// 【已选区控制】已经选区设置 根据平台设置已经存在的进行打印赋值
 	$("select[name='updatPermissionms2side__dx[]']").append(been_SelectedList); 
	
	}else{
		$('.ms2side__div').css('display','none');
		$("#liEditOption").empty();
    	$("#updatPermissionms2side__sx").empty();
    	$("select[name='updatPermissionms2side__dx[]']").empty();
	}
}