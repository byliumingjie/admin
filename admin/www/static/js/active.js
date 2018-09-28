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
 
        $('#tree').show();
        $.ajax({
            type:'POST',
            url:'/active/loadserver',
            dataType:'json',
            success:function(result){
                if(result.errcode == 0){
                    var zTree;
                    $.fn.zTree.init($("#tree"), setting, result.msg);
                    zTree = $.fn.zTree.getZTreeObj("tree");
                    zTree.expandAll(true);
                }
            }
        });
 
        function GetTree()
        {
            var treeObj = $.fn.zTree.getZTreeObj("tree");  
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
       
    
    //显示模态框
     $("#loadFileBtn").click(function(){     
     $("#addFileModal").modal({backdrop:"static"}).modal('show');
    }); 
    
    
    //修改备注
   $("#activityTable tr").each(function() {
        var _this = $(this);
         _this.find(".lookBookBtn").click(function() {

             var form = $("#alertActiveForm").serializeArray();
             for(var i=0;i<form.length;i++){
                 var name = form[i].name;
                 if(name ==null ||name =="")
                 {
                     continue;
                 }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();

                 $("#alertActiveForm [name="+name+"]").val(value);
             }
             
             $("#lookBookModal").modal({backdrop:"static"}).modal('show');
         });
    });
    //添加活动
     $("#alertActivityBtn").click(function() {
        
        var form = $("#alertActiveForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/active/alertBookActivity",
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
    
    //添加的公告的修改
   $("#activityTable tr").each(function() {
        var _this = $(this);
         _this.find(".ceateBtn").click(function() {

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
            url: "/active/createActivity",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = "/activity/activity";
                }
            }
        });
    });
    
     $("#activityTable tr").each(function() {
        var _this = $(this);
        _this.find(".deleteBtn").click(function() {
            if(confirm("确认删除?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/active/deletActivity",
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
       
     //查看每个内容
     $("#activityTable tr").each(function() {
        var _this = $(this);
        _this.find(".lookBtn").click(function() {
           var value = $(this).parent().parent().find('[data-name=configdesc]').text();
           var ContentConfig = value.split("|");
           
            var b = document.createElement('tbody');
            document.getElementById("noticecontext").innerHTML = "";
            for(var i = 0; i< ContentConfig.length;++i)
            {
                var r = document.createElement('tr');
                var c = document.createElement('td');
                var e = document.createTextNode(ContentConfig[i]);
                c.appendChild(e);
                r.appendChild(c);
                b.appendChild(r);
            }
  
            document.getElementById("noticecontext").appendChild(b);
            $("#lookcontextModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
 }); 

