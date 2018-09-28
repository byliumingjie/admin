$(function(){
    $("#addNoticeBtn").click(function(){
        if(confirm("确认添加?"))
        {
            var param = $("#addNoticerForm").serializeArray();
            $.ajax({
                type:'POST',
                url:'/notice/addNotice',
                data:param,
                dataType:'json',
                success:function(result){
                      alert(result.msg);
                    if(result.errcode == 0){
                        $("#serverListModal").modal({backdrop:"static"}).modal('show');
                        window.location.href = window.location.href;  
                    }
                }
            });
        }
        
    });
    
    // 登录 公告编辑
    $("#loginNoticTable tr").each(function() {
        var _this = $(this);
         _this.find(".editloginNoticeBtn").click(function() {

             var form = $("#editLoginNoticeForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#editLoginNoticeForm [name="+name+"]").val(value);
             }
             //$("#alterNoticeModal input[name=id]").val(_this.attr("id"));
             $("#editLoginNoticeModal").modal({backdrop:"static"}).modal('show');
         });
    });
    $("#editLoginNoticebtn").click(function(){
        if(confirm("是否确认编辑?"))
        {
            var param = $("#editLoginNoticeForm").serializeArray();
            $.ajax({
                type:'POST',
                url:'/loginnotice/editLoginNotice',
                data:param,
                dataType:'json',
                success:function(result){
                      alert(result.msg);
                    if(result.errcode == 0){
                        $("#editLoginNoticeModal").modal({backdrop:"static"}).modal('show');
                        window.location.href = window.location.href;  
                    }
                }
            });
        }
        
    });
    $(window).load(function() 
    {
    	var tabaparent = null;
    	var i = 0;
    	var total = 0;
    	var sum = 0 ;
    	$(".tabaparent *").click(function(e){
    		
    		if(e.target == $(".tab2")[0])
    		{     
    			setCookie('tabpages2',0);
    			
    			if(getCookie('tabpages')>0)
    			{
    				total = parseInt(getCookie('tabpages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tabpages',sum);
    			
    			setCookie('tabaparent',".tab2");
    			
    			//var pageTotal2 = getCookie('tabpages');    			 
    			
    			i++;
    			
    		}else if( e.target == $(".tab4")[0] )
    		{
    			setCookie('tabpages',0);
    			
    			if(getCookie('tabpages2')>0)
    			{
    				total = parseInt(getCookie('tabpages2'));
    			}
    			sum = i + total;
   			 
    			setCookie('tabpages2',sum);
    			
    			setCookie('tabaparent',".tab4");
    			
    			i++;
    		}
    	});
    	
    	tabaparent = getCookie('tabaparent');    	
    	
    	$(tabaparent).click(); 
   });   
    
	

    
    function queryForPages(){ 
    	$.ajax({ 
    	url:url, 
    	type:'post', 
    	dataType:'json', 
    	data:"qo.currentPage="+currentPage+"&qo.pageSize="+pageSize+params, 
    	success:function callbackFun(data){ 
    	//解析json 
    	var info = eval("("+data+")"); 
    	//清空数据 
    	clearDate(); 
    	fillTable(info); 
    	} 
    	}); 
    	} 
   
    $(".tab2").click(function() {
    		
    	var pageTotal2 = getCookie('tabpages');
    	if(pageTotal2==0)
    	{
    		location.href = "/loginnotice/passLoginNotice?p="+1;
    		
    	}
    		//location.href = "/loginnotice/passLoginNotice?p="+1;
    	 
    })
    ;
    $(".tab4").click(function() {
    	var pageTotal2 = getCookie('tabpages2');
    	if(pageTotal2==0)
    	{
    		location.href = "/loginnotice/passLoginNotice?p="+1;
    		
    	}
    	
    	//location.href = "/loginnotice/passLoginNotice?p="+1;
    	 
    })
    
    
    //添加的公告的修改
	$("#noticeTable tr").each(function() {
        var _this = $(this);
         _this.find(".alterNotice").click(function() {

             var form = $("#alterNoticeForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#alterNoticeForm [name="+name+"]").val(value);
             }
             $("#alterNoticeModal input[name=id]").val(_this.attr("id"));
             $("#alterNoticeModal").modal({backdrop:"static"}).modal('show');
         });
    });
    //
    
	//审核中的公告
	$("#tab1 table tr").each(function() {
        var _this = $(this);
        _this.find(".editNotice").click(function() {
            
            var form = $("#editNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#editNoticeForm [name="+name+"]").val(value);
            }
            $("#editNoticeModal input[name=id]").val(_this.attr("id"));
            $("#editNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
        
    //发布中的公告
	$("#tab2 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookNotice").click(function() {
            
            var form = $("#lookNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#lookNoticeForm [name="+name+"]").val(value);
            }
            $("#lookNoticeModal input[name=id]").val(_this.attr("id"));
            $("#lookNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });

    //未通过的公告
	$("#tab3 table tr").each(function() {
        var _this = $(this);
        _this.find(".alterNotice").click(function() {
            
            var form = $("#alterNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#alterNoticeForm [name="+name+"]").val(value);
            }
            $("#alterNoticeModal input[name=id]").val(_this.attr("id"));
            $("#alterNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //已经失效的公告
	$("#tab4 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookNotice").click(function() {
            
            var form = $("#lookNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#lookNoticeForm [name="+name+"]").val(value);
            }
            $("#lookNoticeModal input[name=id]").val(_this.attr("id"));
            $("#lookNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
        //遍历公告内容
	$("#noticeTable tr").each(function() {
        var _this = $(this);
        _this.find(".editNotice").click(function() {
            
            var form = $("#editNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#editNoticeForm [name="+name+"]").val(value);
            }
            $("#editNoticeModal input[name=id]").val(_this.attr("id"));
            $("#editNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
 $("#editNoticeBtn").click(function(){
        var param = $("#editNoticeForm").serializeArray();

        $.ajax({
            type:'POST',
            url:'/notice/editNotice',
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
  
   $("#alterNoticeBtn").click(function(){
        var param = $("#alterNoticeForm").serializeArray();

        $.ajax({
            type:'POST',
            url:'/notice/alterNotice',
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
   
   // 跑马灯查询
   $("#btn_date").click(function(){
       var param = $("#publicNoticeForm").serializeArray(); 
       $.ajax({
           type:'POST',
           url:'/notice/showNotice',
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
    //删除
    $("#noticeTable tr").each(function() {
        var _this = $(this);
        _this.find(".delNotice").click(function() {
            
            var form = $("#delNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
//                var value = $(this).parent().parent().find('[data-name='+name+']').text();
//                if(value =="发布中")
//                {
//                    alert("发布中，不可删除的选项");
//                    window.location.href = window.location.href;
//                }
                
                $("#delNoticeForm [name="+name+"]").val(value);
            }
            $("#delNoticeModal input[name=id]").val(_this.attr("id"));
            $("#delNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
	
    $("#delNoticebtn").click(function(){
        
       if(confirm("确认删除？"))
        {
            var param = $("#delNoticeForm").serializeArray();
            $.ajax({
                type:'POST',
                url:'/notice/delNotice',
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
    $("#getServerbtn").click(function(){       
        var id = $("#serverlist [name=tr_id]").val();
        var serverlist = new Array();
        $("#serverlist :checked").each(function() {
                serverlist.push($(this).val());
         });        
         $.ajax({
            type:'POST',
            url:'/notice/addServer',
            data:"id=" + id + "&serverlist=" + serverlist,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){                  
                    window.location.href = window.location.href;
                    
                }
            }
        });
         
        });

        //服务器列表
      $("#noticeTable tr").each(function() {
            var _this = $(this);
                _this.find(".addServerBtn").click(function() {
                var value = $(this).parent().parent().find('[data-name=tr_id]').text();
             $("#serverlist [name=tr_id]").val(value);
            $("#serverListModal").modal({backdrop:"static"}).modal('show');
        });
    });

    //提交审核内容

$("#noticeTable tr").each(function() {
        var _this = $(this);
            _this.find(".submitNotice").click(function() {
            var form = $("#submitNoticeForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }

                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#submitNoticeForm [name="+name+"]").val(value);
            }
            $("#submitNoticeModal input[name=id]").val(_this.attr("id"));
            $("#submitNoticeModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    
    //提交审核
    $("#submitbtn").click(function(){
        if(confirm("确认提交?"))
        {
            var param = $("#submitNoticeForm").serializeArray();
            $.ajax({
                type:'POST',
                url:'/notice/sumbitNotice',
                data:param,
                dataType:'json',
                success:function(result){
                    alert(result.msg);
                    if(result.errcode == 0){
                        window.location.href ="/notice/passNotice";
                    }
                }
            });
        }
    });
   
    
$("#tab3 table tr").each(function() {
            var _this = $(this);
                _this.find(".addServerBtn").click(function() {
                var value = $(this).parent().parent().find('[data-name=tr_id]').text();
             $("#serverlist [name=tr_id]").val(value);
            $("#serverListModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    
$("#noticeTable tr").each(function() {
            var _this = $(this);
                _this.find(".lookserver").click(function() {
            var value = $(this).parent().parent().find('[data-name=sid]').text();
            var server = new Array();
            server = value.split(","); 
            var b = document.createElement('tbody');
            document.getElementById("lookserverlist").innerHTML = "";
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
            $("#lookserverModal").modal({backdrop:"static"}).modal('show');
    });
   });
  
  //审核中的内容
   $("#tab1 table tr").each(function() {
            var _this = $(this);
                _this.find(".lookserver").click(function() {
            var value = $(this).parent().parent().find('[data-name=sid]').text();
            var server = new Array();
            server = value.split(","); 
            var b = document.createElement('tbody');
            document.getElementById("lookserverlist").innerHTML = "";
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
            $("#lookserverModal").modal({backdrop:"static"}).modal('show');
    });
   });
   
   //发布中的内容
   $("#tab2 table tr").each(function() {
            var _this = $(this);
                _this.find(".lookserver").click(function() {
            var value = $(this).parent().parent().find('[data-name=sid]').text();
            var server = new Array();
            server = value.split(","); 
            var b = document.createElement('tbody');
            document.getElementById("lookserverlist").innerHTML = "";
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
            $("#lookserverModal").modal({backdrop:"static"}).modal('show');
    });
   });
   //未通过的内容
   $("#tab3 table tr").each(function() {
            var _this = $(this);
                _this.find(".lookserver").click(function() {
            var value = $(this).parent().parent().find('[data-name=sid]').text();
            var server = new Array();
            server = value.split(","); 
            var b = document.createElement('tbody');
            document.getElementById("lookserverlist").innerHTML = "";
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
            $("#lookserverModal").modal({backdrop:"static"}).modal('show');
    });
   });
  
  //失效的内容
   $("#tab4 table tr").each(function() {
            var _this = $(this);
                _this.find(".lookserver").click(function() {
            var value = $(this).parent().parent().find('[data-name=sid]').text();
            var server = new Array();
            server = value.split(","); 
            var b = document.createElement('tbody');
            document.getElementById("lookserverlist").innerHTML = "";
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
            $("#lookserverModal").modal({backdrop:"static"}).modal('show');
    });
   });
   

});

