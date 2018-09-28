$(function() { 
    
    var setting = {     
        check:{
                enable: true,
                radioType:"all"
                },
        data: { 
                simpleData: {
                    enable: true,
                    idKey: "id", // id编号命名 默认  
                    pIdKey: "pId", // 父id编号命名 默认 
                    rootPId:0
                }
            },
            view:{
                showIcon:false,
                showLine:false
            }
        };
 
        $('#treeid').show();
        $.ajax({
            type:'POST',
            url:'/active/loadserver',
            dataType:'json',
            success:function(result){
                if(result.errcode == 0){
                    var zTree;
                    $.fn.zTree.init($("#treeid"), setting, result.msg);
                    zTree = $.fn.zTree.getZTreeObj("treeid");
                    zTree.expandAll(true);
                }
            }
        });
 
        function GetTree()
        {
            var treeObj = $.fn.zTree.getZTreeObj("treeid");  
            //获得选中所有节点，返回值 Array(JSON)  
            var nodes = treeObj.getCheckedNodes(true);  
            var str_id = "";     
            //遍历选中的节点  Array(JSON)  
            for (var node in nodes){   
                for(var key in nodes[node]){   
                    //只取JSON中的 id,name的值  
                    if("id" == key){  
                        if(str_id!=""){  
                            str_id = str_id + ',' + nodes[node][key];   
                        }else{  
                            str_id += nodes[node][key];   
                        }  
                    }   
                }   
            }           
            return str_id;
        }

    
    //添加活动
     $("#loadBtn").click(function() {
        var serverlist = GetTree(); //获取选中的服务器列表
        $("#addActivityForm input[name=sid]").val(serverlist);  //把服务器列表封装到id中 
        
        var form = $("#addActivityForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/activity/loadactivity",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href =window.location.href;
                }
            }
        });
    });
    
        
     $("#tab1 table tr").each(function() {
        var _this = $(this);
         _this.find(".submitBtn").click(function() {

             var form = $("#submitActivityForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#submitActivityForm [name="+name+"]").val(value);
             }
             
             $("#submitActivityModal").modal({backdrop:"static"}).modal('show');
         });
    });
    
     $("#editBtn").click(function() {   
        var form = $("#submitActivityForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/activity/submitActivity",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href =  window.location.href;
                }
            }
        });
    });
   
   
   
   $("#tab2 table tr").each(function() {
        var _this = $(this);
         _this.find(".sendBtn").click(function() {

             var form = $("#sendActivityForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#sendActivityForm [name="+name+"]").val(value);
             }
             
             $("#sendActivityModal").modal({backdrop:"static"}).modal('show');
         });
    });
    
    $("#subBtn").click(function() {   
        var form = $("#sendActivityForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/activity/sendActivity",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href =  window.location.href;
                }
            }
        });
    });
   
   
   $("#tab3 table tr").each(function() {
        var _this = $(this);
         _this.find(".alertSendBtn").click(function() {

             var form = $("#sendAlertActivityForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#sendAlertActivityForm [name="+name+"]").val(value);
             }
             
             $("#sendAlertActivityModal").modal({backdrop:"static"}).modal('show');
         });
    });
    
    $("#alertSendBtn").click(function() {   
        var form = $("#sendAlertActivityForm").serializeArray();
            if(confirm("确认执行?"))
            {
                $.ajax({
                        type: 'POST',
                        url: "/activity/alertSendActivity",
                        data: form,
                        dataType: 'json',
                        success: function(result) {
                            alert(result.msg);
                            if (result.errcode == 0) {
                                window.location.href =  window.location.href;
                            }
                        }
                    });
            }
        
    });
    
   $("#tab4 table tr").each(function() {
        var _this = $(this);
        _this.find(".deleteBtn").click(function() {
            if(confirm("确认删除?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/activity/deletActivity",
                    data: "id="+id,
                    dataType: 'json',
                    success: function(result) {
                        alert(result.msg);
                        if (result.errcode == 0) {
                            window.location.href = window.location.href;
                        }
                    }
                });
            }
        });
    });
    
    
    //添加的公告的修改
   $("#tab4 table tr").each(function() {
        var _this = $(this);
         _this.find(".alertBtn").click(function() {

             var form = $("#saveActiveForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#saveActiveForm [name="+name+"]").val(value);
             }
             
             $("#addloginNoticeModal").modal({backdrop:"static"}).modal('show');
         });
    });
    
    //添加活动
     $("#addActivityBtn").click(function() {
        var serverlist = GetTree(); //获取选中的服务器列表
        $("#saveActiveForm input[name=sid]").val(serverlist);  //把服务器列表封装到id中 
        
        var form = $("#saveActiveForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/activity/alertActivity",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                }
            }
        });
    });
 }); 

