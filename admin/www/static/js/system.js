$(function() {
	// 添加平台
    $("#addplatformCfgBtn").click(function() {
    	 
        var param = $("#addForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/system/addPlatformCfg',
            data: param,
            dataType: 'json',
            success: function(result) {        	
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                    
                }
            }
        });
    });    
    /** 
     * edit
     ***/
    $("#editPlatformBtn").click(function() {
  	  var param = $("#editplatformCfgForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/system/editPlatformCfg',
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                }
            }
        });
    });
    $("#paltformcofTable tr").each(function() {    	
        var _this = $(this);
        _this.find(".editPlatformCfgBtn").click(function() {
        	// 初始选区内容
        	$("#editplatformCfgForm select[name=ifUpserver]").val(0);        	 
        	$("#liOption").empty();
        	$("#serverIdms2side__sx").empty();
        	$("select[name='serverIdms2side__dx[]']").empty();
        	// 是否选区type
        	var ifUpserverType = $("#editplatformCfgForm select[name=ifUpserver]").val();
        	if(ifUpserverType!=1 )
        	{
        		$('.ms2side__div').css('display','none');
        	}
        	else{
        		$('.ms2side__div').css('display','block');
        	} 
        	// location.reload();
            var form = $("#editplatformCfgForm").serializeArray();
             
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                // 对于区服的控制需要排除name = updatPermission[] 与updatPermissionms2side__dx[]
                // 为什么要排除其实在循环是的name是为了获取数据并且赋值编辑表单内，但是ben
                if(name ==null ||name =="")
                {
                    continue;
                } 
                if(name=="serverIdms2side__dx[]" || name=="serverId[]" || name=='ifUpserver')
                {
                    continue;
                } 
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#editPlatformModal [name="+name+"]").val(value);
            } 
            //$("#editPlatformModal input[name=id]").val(_this.attr("id"));
            $("#editPlatformModal").modal({backdrop:"static"}).modal('show'); 
        }); 
        /** 
         * 平台区服信息列表查看展示
         * **/
        _this.find(".platformSIDLock").click(function() {
        	 
        	$("#lookserverlist").html('');
        	document.getElementById("lookserverlist").innerHTML = "";        	
        	
        	var id = $(this).parent().parent().find('[data-name=id]').text();
        	  
        	$.ajax({
       		 type:'POST',
       		 url:"/System/ServerPlatSolveCfg",
       		 data:{platformId:id},
       		 dataType:'json',
       		 success: function(result) { 
                   if (result.errcode != 0) 
                   {   
                  	 	alert(result.msg);
                       return false;
                   }  
                   LookserverList(result.beenselect);
                   //var newArr = JSON.parse(result.beenselect);
                   //alert(result.beenselect.length);
                   //LookserverList(result.beenselect);
                }
        	 });  
            
        }); 
    }); 
});

function LookserverList(data)
{
	if(typeof(data) == 'object'){    		
  	   	var server = $.makeArray( data );  	    
 	}
	if(server.length>0)
	{
		var b = document.createElement('tbody');
		for(var i =0; i <server.length; i+=4)
	    {
	        var r = document.createElement('tr');
	 
	        if(server[i] !=="" && server[i] !==0&& server[i] !== undefined)
	        {
	            var c = document.createElement('td');
	            var e = document.createTextNode(server[i] + '服');
	            c.appendChild(e);
	            r.appendChild(c);
	        }else
	        {
	             var c = document.createElement('td');
	             var e =  document.createTextNode('');
	             c.appendChild(e);
	             r.appendChild(c);
	        }
	        if(server[i+1] !=="" && server[i+1] !==0&& server[i+1] !== undefined)
	        {
	            var c = document.createElement('td');
	            var e = document.createTextNode(server[i+1] + '服');
	            c.appendChild(e);
	            r.appendChild(c);
	        }else
	        {
	             var c = document.createElement('td');
	             var e =  document.createTextNode('');
	             c.appendChild(e);
	             r.appendChild(c);
	        }
	        if(server[i+2] !=="" && server[i+2] !==0&& server[i+2] !== undefined)
	        {
	           var c = document.createElement('td');
	            var e = document.createTextNode(server[i+2] + '服');
	            c.appendChild(e);
	            r.appendChild(c);
	        }else
	        {
	             var c = document.createElement('td');
	             var e =  document.createTextNode('');
	             c.appendChild(e);
	             r.appendChild(c);
	        }
	        if(server[i+3] !=="" && server[i+3] !==0 && server[i+3] !== undefined)
	        {
	           var c = document.createElement('td');
	            var e = document.createTextNode(server[i+3] + '服');
	            c.appendChild(e);
	            r.appendChild(c);
	        }else
	        {
	             var c = document.createElement('td');
	             var e =  document.createTextNode('');
	             c.appendChild(e);
	             r.appendChild(c);
	        }
	        b.appendChild(r);
	    } 
		document.getElementById("lookserverlist").appendChild(b);
	} 
	$("#serverinfoModal").modal({backdrop:"static"}).modal('show');
}

function setSidVerify()
{ 
	$("#liOption").empty();
	$("#serverIdms2side__sx").empty();
	$("select[name='serverIdms2side__dx[]']").empty();
	
	var id = $("#editplatformCfgForm input[name=id]").val();
	var ifUpserverType = $("#editplatformCfgForm select[name=ifUpserver]").val();
	
	if(ifUpserverType==1 && id>0) 
	{   
		var param ='account=1557';        	 
		 $.ajax({
		 type:'POST',
		 url:"/System/ServerPlatSolveCfg",
		 data:{platformId:id},
		 dataType:'json',
		 success: function(result) { 
             if (result.errcode != 0) 
             {   
            	 alert('-1'+result.msg);
                 return false;
             }  
             sidSelectList(id,ifUpserverType,result.beenselect,result.waitselect);
         }
		 }); 
	}	
	else{ 
		$("#liOption").empty();
    	$("#serverIdms2side__sx").empty();
    	$("select[name='serverIdms2side__dx[]']").empty();
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
		default_SelectList +="<option value="+value+" selected='selected'>"+value+"区</option>";
		been_SelectedList+="<option value="+value+">"+value+"区</option>";
	}); 	
	// 待选区域Id serverIdms2side__sx + 默认去状态初始属性不增加  selected='selected'
	$.each(waitdata, function(index, value) 
	{
		default_SelectList +="<option value="+value+">"+value+"区</option>";
		wait_SelectList +="<option value="+value+">"+value+"区</option>";
	});
	// 已选区域Id serverIdms2side__dx[]
	  
 	//html2="<option value='5'>5区</option>";
 	
 	$('.ms2side__div').css('display','block');
 	// 【默认展示列表信息主控制】 根据平台 不存在的进行赋值如果已经存在的进行设置 selected='selected'
 	$("#liOption").append(default_SelectList); 
 	// 【待选区设置还没有选择的区配置】
 	$("#serverIdms2side__sx").append(wait_SelectList); 	 
 	// 【已选区控制】已经选区设置 根据平台设置已经存在的进行打印赋值
 	$("select[name='serverIdms2side__dx[]']").append(been_SelectedList); 
	
	}else{
		$('.ms2side__div').css('display','none');
		$("#liOption").empty();
    	$("#serverIdms2side__sx").empty();
    	$("select[name='serverIdms2side__dx[]']").empty();
	}
}
 
// 视图展示


