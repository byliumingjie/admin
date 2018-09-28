$(function(){
	// 添加服务器列表
    $("#addServerBtn").click(function(){
        /*if($("#addServerForm select[name=platformname]").val() == '' || $("#addServerForm select[name=platformname]").val() == 0){
            alert('请选择平台!');
            return false;
        }*/
        var param = $("#addserverForm").serializeArray();

        $.ajax({
            type:'POST',
            url:'/servers/addServer',
            data:param,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){
                	window.location.reload();
                }
            }
        });
    });
    
     
	// 添加统计配置表
    $("#addDBConfigBtn").click(function(){
     
        var param = $("#addDBconfigForm").serializeArray();

        $.ajax({
            type:'POST',
            url:'/servers/addServer',
            data:param,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){
                	window.location.reload();
                }
            }
        });
    });
    // 游戏平台删除
    $(".delServer").each(function() {
    	 
        var _this = $(this);
        _this.click(function() {        	
        	
        	var id = $(this).attr('data-value');
        	var serverId = $(this).attr('data-server');
        	var devtype = $(this).attr('data-dev-type');
        	
        	devtype = parseInt(devtype);
        	
        	var Reminder = 'error!';
        	
        	if(devtype==1)
        	{
        		Reminder = "确定删除"+serverId+"服么？";
        	}
        	if(devtype==2)
        	{
        		Reminder = "确定要删除该数据库配置么么？";
        	}
        	
            if (confirm(Reminder)) {
               
                if (id == '' || id == 'undefined') {
                    alert("id不能为空!");
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '/servers/deleServer',
                    data: 'id='+id,
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
    // 修改 数据库配置
    
    $("#dbconfigTable tr").each(function() {
    	   var _this = $(this);
           _this.find(".editDBConcif").click(function() {
               
               var form = $("#editDBConfigForm").serializeArray();
               for(var i=0;i<form.length;i++){
                   var name = form[i].name; 
                   
                   if(name ==null ||name =="" || name=='devType')
                   {
                       continue;
                   }
                   var value = null;
                   
                   if(name=='serverStatus')
                   { 
                   	value = $(this).parent().parent().find('[data-name='+name+']').text();
                   	 
                   	optionVerifySet('serverType',parseInt(value));
                   }
                   else if(name=='platformId')
                   {
                   	value = $(this).parent().parent().find('[data-name='+name+']').text();
                   	$("#editDBConfigForm [name='platId'").val(value);
                   	optionVerifySet('editDBcfgplatFormId',parseInt(value));                	
                   }
                   else
                   {
                	   
                   	value = $(this).parent().parent().find('[data-name='+name+']').text();
                   	$("#editDBConfigForm [name="+name+"]").val(value);
                   }
                    
               }
           	 
               $("#editDBConfigForm input[name=id]").val(_this.attr("id"));
               $("#editdbconfigrModal").modal({backdrop:"static"}).modal('show');
           });
    });
    $("#editconfigrBtn").click(function(){
        
        var param = $("#editDBConfigForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/servers/editServer',
            data:param,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){
                	window.location.reload();
                }
            }
        });
    });
	$("#editDBcfgplatFormId").click(function()
	{
		var id = $("#editDBcfgplatFormId2").val();
		
		$.ajax({
            type:'POST',
            url:'/servers/DB_config_plat_Verify',
            data:'id='+id,
            dataType:'json',
            success:function(result){
               
                if(result.errcode == 0)
                {
                	$("#editDbconfigPlatList").html("");
                	$("#editDbconfigPlatList").html(result.option);
                	//
                	//window.location.reload();
                }else{
                	
                	 alert(result.msg);
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
	//修改服务器
	$("#serverTable tr").each(function() {
		 
        var _this = $(this);
        _this.find(".editServer").click(function() {
            
            var form = $("#editServerForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name; 
                
                if(name ==null ||name =="" || name=='devType')
                {
                    continue;
                }
                var value = null;
                
                if(name=='serverStatus')
                { 
                 
                	value = $(this).parent().parent().find('[data-name='+name+']').text();
                 
                	
                	$("#editServerForm [name='sidstatus']").val(value);
                	 
                }
                else if(name=='platformId')
                {
                	value = $(this).parent().parent().find('[data-name='+name+']').text();
               	 
                	optionVerifySet('editplatFormId',parseInt(value));                	
                }
                else
                {
                	value = $(this).parent().parent().find('[data-name='+name+']').text();
                	$("#editServerForm [name="+name+"]").val(value);
                }
                 
            }
        	 
            $("#editServerModal input[name=id]").val(_this.attr("id"));
            $("#editServerModal").modal({backdrop:"static"}).modal('show');
        });
        // 导出配置文件
        _this.find(".download-server-cfg").click(function() {
        	var id = $(this).attr('data-id');
        	$.ajax({
                type: 'POST',
                url: '/servers/downloadSidConfig',
                data: 'id='+id,
                dataType: 'json',
                success: function(result) {
                    alert(result.msg);
                    if (result.errcode == 0) {
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
        //开服
        // 导出配置文件
        _this.find(".openServer").click(function() {
        	// 开1 关 2
        	var status = $(this).attr('data-value');
        	status = parseInt(status);
        	
        	var sid = $(this).attr('data-sid');
        	
        	var statusDec = (status==1) ?'开启':'关闭';
        	
        	var statusInfo = (status==1) 
        	? 
        	'你确定要开启'+sid+'服么？' 
        	: 
        	'你确定要关闭'+sid+'服么？'; 
        	
        	var setStatusInfo = (status==1) 
        	? 
        	sid+'区,已经在'+statusDec+'中,大约需要2分钟!如要终止请点击取消！...' 
        	: 
        	sid+'区,已经在'+statusDec+'中,大约需要3分钟!如要终止请点击取消！'
        	
        	if(confirm(statusInfo))
            { 
        		if(confirm(sid+'区正在准备,'+statusDec+',如提前终止请点取消！'))
                {
        			if(confirm('操作开关时请勿离开,请耐心等待服务器处理结果!'))
        			{
	        			if(confirm(setStatusInfo))
		                {
				        	$.ajax({
				                type: 'POST',
				                url: '/servers/OpenSever',
				                data: 'status='+status+'&serverId='+sid,
				                dataType: 'json',
				                success: function(result) {
				                    alert(result.msg);
				                    if (result.errcode == 0) {
				                        window.location.reload();
				                    }else{
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
		                }
	        			
		            }
                }
            }
        	window.location.reload();
	   });
        ///<<<END
    });
	
	//改变服务器状态
	$("#serverTable tr").each(function() {
        var _this = $(this);
        _this.find(".severState").click(function() {
            var form = $("#serverStateForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();
                  $("#serverStateForm [name="+name+"]").val(value);
                
            }
            $("#serverstateModal [name=id]").val(_this.attr("id"));
            $("#serverstateModal").modal({backdrop:"static"}).modal('show');
        });
    });
	
	//服务器状态信息
	$("#serverTable tr").each(function() {
        var _this = $(this);
        _this.find(".serverInfo").click(function() {
            var form = $("#serverInfoForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#serverInfoForm [name="+name+"]").val(value);
        
            }
            $("#serverinfoModal input[name=id]").val(_this.attr("id"));
            $("#serverinfoModal").modal({backdrop:"static"}).modal('show');
        });
    });
	
	
	$("#editServerBtn").click(function(){
        /*if($("#editPlatformForm select[name=platformname]").val() ==='' || $("#editPlatformForm select[name=platformname]").val() === 0){
            alert('请选择平台!');
            return false;
        }*/
        var param = $("#editServerForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/servers/editServer',
            data:param,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){
                	window.location.reload();
                }
            }
        });
    });
	
	$("#serverStateBtn").click(function(){
        var param = $("#serverStateForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/server/serverState',
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
	
	$("#addWhiteListBtn").click(function(){
        var param = $("#addWhiteListForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/whitelist/addWhiteAccount',
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
	
            //删除白名单
	$("#whiteTable tr").each(function() {
        var _this = $(this);
        _this.find(".deleteWhiteAccount").click(function() {
           var uin = $(this).parent().parent().find('[data-name=uin]').text();
                $.ajax({
               type:'POST',
               url:'/whitelist/deleteWhiteAccount',
               data :"uin="+uin,
               dataType:'json',
               success:function(result){
                   alert(result.msg);
                   if(result.errcode === 0){
                       window.location.href = window.location.href;
                   }
               }
           });
        });
    });
    
	$("#serverinfoBtn").click(function(){	
        var param = $("#serverInfoForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/server/serverInfo',
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




     var checkbox = $("#serverlist .checkbox-inline").find('input ');
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
    
    //获取对应选择的服务器和对应的id
    $("#UpdateServerBtn").click(function(){ 
        var param = $("#ListForm textarea[name=desc]").val();
        var sel = $("#ListForm select[name=serverStatus]").val();
        var state = $("#ListForm select[name=State]").val();
        var serverlist = new Array();
        $("#serverlist :checked").each(function() {
                serverlist.push($(this).val());
         });        
         $.ajax({
            type:'POST',
             url:'/server/serverStateUpdate',
            data: "serverlist=" + serverlist+"&desc="+param+"&serverStatus="+sel+"&State="+state,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){                  
                    window.location.href = window.location.href;
                    
                }
            }
        });
         
        }); 
});
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

