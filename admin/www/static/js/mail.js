
$(function() {
    
    $("#savePerBtn").click(function() {
        var server = $("#savePerMailForm select[name=sid]").val();
        if(server==0||server ==="")
        {
            alert("请选择服务器");
            return false;
        } 
        // 表情包ID
        var faceid = $("#faceid").val(); 
        $("#savePerMailForm select[name=faceid]").val(faceid);
        // 装备
        var equipid = $("#equipid").val();
        $("#savePerMailForm select[name=equipid]").val(equipid);
        // 套装 suitId
        var suitId = $("#suitId").val();
      
        $("#savePerMailForm select[name=suitId]").val(suitId);
        // 道具ID1
        var propid1 = $("#propid1").val();        
        $("#savePerMailForm select[name=propid1]").val(propid1);
        // 道具ID2
        var propid2 = $("#propid2").val();
        $("#savePerMailForm select[name=propid2]").val(propid2); 
        // 道具ID3
        var propid3 = $("#propid3").val();
        $("#savePerMailForm select[name=propid3]").val(propid3);
        // 道具ID4
        var propid4 = $("#propid4").val();
        $("#savePerMailForm select[name=propid4]").val(propid4);
        
        var form = $("#savePerMailForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/mail/addPerMail",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = '/mail/showmail';
                }
            }
        });
    }); 

    $("#saveSerBtn").click(function() {
        var form = $("#saveSerMailForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/mail/addSerMail",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = '/mail/showmail';
                }
            }
        });
    });
    
    $("#saveAllBtn").click(function() {
        var server = $("#saveAllMailForm select[name=sid]").val();
        if(server==0||server ==="")
        {
            alert("请选择服务器");
            return false;
        }    
        var form = $("#saveAllMailForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/mail/addAllMail",
            data: form,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = '/mail/showmail';
                }
            }
        });
    });
    
    $("#saveReimburseBtn").click(function() {
        var server = $("#saveReimburseMailForm select[name=sid]").val();
        if(server==0||server ==="")
        {
            alert("请选择服务器");
            return false;
        }    
        var form = $("#saveReimburseMailForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/mail/addReimburseMail",
            data: form,
            dataType: 'json',
            success: function(result) 
            { 
            	alert(result.msg);
                if ( result.errcode==0 ) 
                { 
                    window.location.href = '/mail/showmail';
                }
                /*if( result.errcode==5 )
                {
                	var remimburseData =  result.msg;
                	 
                	if(confirm("你所添加的补偿邮件已经存在,点击确定会进行更改此邮件数据是否确认？"))
                    {
	                	$.ajax({
	                        type: 'POST',
	                        url: "/mail/remimburse_up",
	                        data: remimburseData,
	                        dataType: 'json',
	                        success: function(result) 
	                        {
	                        	alert(result.msg);
	                            if (result.errcode == 0) { 
	                            	 
	                                window.location.href = '/mail/showmail';
	                            }
	                        }
	                    });
                    }
                }*/
               
            }
        });
    });
    
    //删除一个mail
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".delmailBtn").click(function() {
            if(confirm("确认删除?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/mail/delMail",
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
    //删除一个mail
     $("#tab1 table  tr").each(function() {
        var _this = $(this);
        _this.find(".delmailBtn").click(function() {
            if(confirm("确认删除?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/mail/delMail",
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
    
    
    //删除一个mail
     $("#tab3 table  tr").each(function() {
        var _this = $(this);
        _this.find(".delmailBtn").click(function() {
            if(confirm("确认删除?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/mail/delMail",
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
    
    //撤回一个mail
     $("#tab2 table tr").each(function() {
        var _this = $(this);
        _this.find(".returnmailBtn").click(function() {
            if(confirm("确认撤回?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text(); 
                var sid = $(this).parent().parent().find('[data-name=sid]').text();
                if(sid < 0)
                {
                    alert("区服为空，请刷新后再试");
                }
                    $.ajax({
                    type: 'POST',
                    url: "/mail/revokeMail",
                    data: "id="+id+'&sid='+sid,
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
    
    
    //提交审核
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".submailBtn").click(function() {
            
            if(confirm("确认提交?"))
            {
                var id = $(this).parent().parent().find('[data-name=id]').text();           
                    $.ajax({
                    type: 'POST',
                    url: "/mail/submitMail",
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
    
    //提交审核
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".editmailBtn").click(function() {
           var form = $("#editMailForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }

                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#editMailForm [name="+name+"]").val(value);
            }
            $("#editMailModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    
    //审核
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".passmailBtn").click(function() {
           var form = $("#passMailForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }

                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#passMailForm [name="+name+"]").val(value);
            }
            $("#passMailModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //审核
     $("#passMailbtn").click(function() {
        var form = $("#passMailForm").serializeArray();
   
                $.ajax({
                type: 'POST',
                url: "/mail/passMail",
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
  
  
  //发送邮件
     $("#mailTable tr").each(function() {
       var _this = $(this);
        _this.find(".sendmailBtn").click(function() {
           var form = $("#sendMailForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }

                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                
                $("#sendMailForm [name="+name+"]").val(value);
            }
            $("#sendMailModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //发送邮件
     $("#sendMailbtn").click(function() {
        var form = $("#sendMailForm").serializeArray();
        var tag = $("#sendMailForm [name=tag]").val();
        if(tag == 0)
        {
          $.ajax({
                type: 'POST',
                url: "/mail/sendMail",
                data: form,
                dataType: 'json',
                success: function(result) {
                    alert(result.msg);
                    if (result.errcode == 0) {
                        window.location.href = window.location.href;
                    }
                }
            });  
        }else
        {
           $.ajax({
                type: 'POST',
                url: "/mail/sendAllMail",
                data: form,
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
  
  //审核提交
     $("#alterMailbtn").click(function() {
        var form = $("#editMailForm").serializeArray();
            if(confirm("确认修改?"))
            {   
                $.ajax({
                type: 'POST',
                url: "/mail/editMail",
                data: form,
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

//查看角色列表
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".lookrole").click(function() {
           var value = $(this).parent().parent().find('[data-name=roleid]').text();
           if(value ==0)
           {
               value ="全服";
           }
            var b = document.createElement('tbody');
            document.getElementById("rolecontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("rolecontext").appendChild(b);
            $("#lookRoleModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    
    //查看角色列表
     $("#tab1 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookrole").click(function() {
           var value = $(this).parent().parent().find('[data-name=roleid]').text();
           if(value ==0)
           {
               value ="全服";
           }
            var b = document.createElement('tbody');
            document.getElementById("rolecontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("rolecontext").appendChild(b);
            $("#lookRoleModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看角色列表
     $("#tab2 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookrole").click(function() {
           var value = $(this).parent().parent().find('[data-name=roleid]').text();
           if(value ==0)
           {
               value ="全服";
           }
            var b = document.createElement('tbody');
            document.getElementById("rolecontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("rolecontext").appendChild(b);
            $("#lookRoleModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看角色列表
     $("#tab3 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookrole").click(function() {
           var value = $(this).parent().parent().find('[data-name=roleid]').text();
           if(value ==0)
           {
               value ="全服";
           }
            var b = document.createElement('tbody');
            document.getElementById("rolecontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("rolecontext").appendChild(b);
            $("#lookRoleModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看每个内容
     $("#mailTable tr").each(function() {
        var _this = $(this);
        _this.find(".lookcontext").click(function() {
           var value = $(this).parent().parent().find('[data-name=context]').text();
            var b = document.createElement('tbody');
            document.getElementById("mailcontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailcontext").appendChild(b);
            $("#lookcontextModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
     //查看每个内容
     $("#tab2 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookcontext").click(function() {
           var value = $(this).parent().parent().find('[data-name=context]').text();
            var b = document.createElement('tbody');
            document.getElementById("mailcontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailcontext").appendChild(b);
            $("#lookcontextModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看每个内容
     $("#tab3 table tr").each(function() {
        var _this = $(this);
        _this.find(".lookcontext").click(function() {
           var value = $(this).parent().parent().find('[data-name=context]').text();
            var b = document.createElement('tbody');
            document.getElementById("mailcontext").innerHTML = "";

            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(value);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailcontext").appendChild(b);
            $("#lookcontextModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看每个内容
     $("#mailTable tr").each(function() {
        var _this = $(this);
        
        _this.find(".looktag").click(function() {
            var htmlPage = "";
            document.getElementById("mailtag").innerHTML = "";
            
           var coin = $(this).parent().parent().find('[data-name=coin]').text();
           if(coin != 0)
           {
              htmlPage +="金币"+coin+" | ";
           }
           var money = $(this).parent().parent().find('[data-name=money]').text();
           if(money != 0)
           {
              htmlPage +="钻石"+money+" | ";
           }
           var herosoul = $(this).parent().parent().find('[data-name=herosoul]').text();
           if(herosoul != 0)
           {
              htmlPage +="英雄魂石"+herosoul+" | ";
           }
           var honour = $(this).parent().parent().find('[data-name=honour]').text();
           if(honour != 0)
           {
              htmlPage +="荣誉点"+honour+" | ";
           }
           var honor_s = $(this).parent().parent().find('[data-name=honor_s]').text();
           if(honor_s != 0)
           {
              htmlPage +="声望"+honor_s+" | ";
           }
           var equipsoul = $(this).parent().parent().find('[data-name=equipsoul]').text();
           if(equipsoul != 0)
           {
              htmlPage +="装备魂石"+equipsoul+" | ";
           }
           
           var heroname = $(this).parent().parent().find('[data-name=heroname]').text();
           var heronum = $(this).parent().parent().find('[data-name=heronum]').text();
           if(heronum != 0)
           {
              htmlPage += heroname+"数量:"+heronum+" | ";
           }
           var equipname = $(this).parent().parent().find('[data-name=equipname]').text();
           var equipnum = $(this).parent().parent().find('[data-name=equipnum]').text();
           if(equipnum != 0)
           {
              htmlPage += equipname+"数量:"+equipnum+" | ";
           }
           var prop1name = $(this).parent().parent().find('[data-name=prop1name]').text();
           var propnum1 = $(this).parent().parent().find('[data-name=propnum1]').text();
           if(propnum1 != 0)
           {
              htmlPage +=prop1name+"数量:"+propnum1+" | ";
           }
           var propname2 = $(this).parent().parent().find('[data-name=prop2name]').text();
           var propnum2 = $(this).parent().parent().find('[data-name=propnum2]').text();
           if(propnum2 != 0)
           {
              htmlPage +=propname2+"数量:"+propnum2+" | ";
           }
           
           var propname3 = $(this).parent().parent().find('[data-name=prop3name]').text();
           var propnum3 = $(this).parent().parent().find('[data-name=propnum3]').text();
           if(propnum3 != 0)
           {
              htmlPage +=propname3+"数量:"+propnum3+" | ";
           }
           var propname4 = $(this).parent().parent().find('[data-name=prop4name]').text();
           var propnum4 = $(this).parent().parent().find('[data-name=propnum4]').text();
           if(propnum4 != 0)
           {
              htmlPage +=propname4+"数量:"+propnum4+" | ";
           }
           
            var b = document.createElement('tbody');
            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(htmlPage);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailtag").appendChild(b);
            $("#looktagModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看每个内容
     $("#tab2 table tr").each(function() {
        var _this = $(this);
        
        _this.find(".looktag").click(function() {
            var htmlPage = "";
            document.getElementById("mailtag").innerHTML = "";
            
           var coin = $(this).parent().parent().find('[data-name=coin]').text();
           if(coin != 0)
           {
              htmlPage +="金币"+coin+" | ";
           }
           var money = $(this).parent().parent().find('[data-name=money]').text();
           if(money != 0)
           {
              htmlPage +="钻石"+money+" | ";
           }
           var herosoul = $(this).parent().parent().find('[data-name=herosoul]').text();
           if(herosoul != 0)
           {
              htmlPage +="英雄魂石"+herosoul+" | ";
           }
           var honour = $(this).parent().parent().find('[data-name=honour]').text();
           if(honour != 0)
           {
              htmlPage +="荣誉点"+honour+" | ";
           }
           var honor_s = $(this).parent().parent().find('[data-name=honor_s]').text();
           if(honor_s != 0)
           {
              htmlPage +="声望"+honor_s+" | ";
           }
           var equipsoul = $(this).parent().parent().find('[data-name=equipsoul]').text();
           if(equipsoul != 0)
           {
              htmlPage +="装备魂石"+equipsoul+" | ";
           }
           var heroname = $(this).parent().parent().find('[data-name=heroname]').text();
           var heronum = $(this).parent().parent().find('[data-name=heronum]').text();
           if(heronum != 0)
           {
              htmlPage += heroname+"数量:"+heronum+" | ";
           }
           var equipname = $(this).parent().parent().find('[data-name=equipname]').text();
           var equipnum = $(this).parent().parent().find('[data-name=equipnum]').text();
           if(equipnum != 0)
           {
              htmlPage += equipname+"数量:"+equipnum+" | ";
           }
           var prop1name = $(this).parent().parent().find('[data-name=prop1name]').text();
           var propnum1 = $(this).parent().parent().find('[data-name=propnum1]').text();
           if(propnum1 != 0)
           {
              htmlPage +=prop1name+"数量:"+propnum1+" | ";
           }
           var propname2 = $(this).parent().parent().find('[data-name=prop2name]').text();
           var propnum2 = $(this).parent().parent().find('[data-name=propnum2]').text();
           if(propnum2 != 0)
           {
              htmlPage +=propname2+"数量:"+propnum2+" | ";
           }
           
           var propname3 = $(this).parent().parent().find('[data-name=prop3name]').text();
           var propnum3 = $(this).parent().parent().find('[data-name=propnum3]').text();
           if(propnum3 != 0)
           {
              htmlPage +=propname3+"数量:"+propnum3+" | ";
           }
           var propname4 = $(this).parent().parent().find('[data-name=prop4name]').text();
           var propnum4 = $(this).parent().parent().find('[data-name=propnum4]').text();
           if(propnum4 != 0)
           {
              htmlPage +=propname4+"数量:"+propnum4+" | ";
            }
            var b = document.createElement('tbody');
            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(htmlPage);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailtag").appendChild(b);
            $("#looktagModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    //查看每个内容
     $("#tab3 table tr").each(function() {
        var _this = $(this);
        
        _this.find(".looktag").click(function() {
            var htmlPage = "";
            document.getElementById("mailtag").innerHTML = "";
            
           var coin = $(this).parent().parent().find('[data-name=coin]').text();
           if(coin != 0)
           {
              htmlPage +="金币"+coin+" | ";
           }
           var money = $(this).parent().parent().find('[data-name=money]').text();
           if(money != 0)
           {
              htmlPage +="钻石"+money+" | ";
           }
           var herosoul = $(this).parent().parent().find('[data-name=herosoul]').text();
           if(herosoul != 0)
           {
              htmlPage +="英雄魂石"+herosoul+" | ";
           }
           var honour = $(this).parent().parent().find('[data-name=honour]').text();
           if(honour != 0)
           {
              htmlPage +="荣誉点"+honour+" | ";
           }
           var honor_s = $(this).parent().parent().find('[data-name=honor_s]').text();
           if(honor_s != 0)
           {
              htmlPage +="声望"+honor_s+" | ";
           }
           var equipsoul = $(this).parent().parent().find('[data-name=equipsoul]').text();
           if(equipsoul != 0)
           {
              htmlPage +="装备魂石"+equipsoul+" | ";
           }
            var heroname = $(this).parent().parent().find('[data-name=heroname]').text();
           var heronum = $(this).parent().parent().find('[data-name=heronum]').text();
           if(heronum != 0)
           {
              htmlPage += heroname+"数量:"+heronum+" | ";
           }
           var equipname = $(this).parent().parent().find('[data-name=equipname]').text();
           var equipnum = $(this).parent().parent().find('[data-name=equipnum]').text();
           if(equipnum != 0)
           {
              htmlPage += equipname+"数量:"+equipnum+" | ";
           }
           var prop1name = $(this).parent().parent().find('[data-name=prop1name]').text();
           var propnum1 = $(this).parent().parent().find('[data-name=propnum1]').text();
           if(propnum1 != 0)
           {
              htmlPage +=prop1name+"数量:"+propnum1+" | ";
           }
           var propname2 = $(this).parent().parent().find('[data-name=prop2name]').text();
           var propnum2 = $(this).parent().parent().find('[data-name=propnum2]').text();
           if(propnum2 != 0)
           {
              htmlPage +=propname2+"数量:"+propnum2+" | ";
           }
           
           var propname3 = $(this).parent().parent().find('[data-name=prop3name]').text();
           var propnum3 = $(this).parent().parent().find('[data-name=propnum3]').text();
           if(propnum3 != 0)
           {
              htmlPage += propname3+"数量:"+propnum3+" | ";
           }
           var propname4 = $(this).parent().parent().find('[data-name=prop4name]').text();
           var propnum4 = $(this).parent().parent().find('[data-name=propnum4]').text();
           if(propnum4 != 0)
           {
              htmlPage +=propname4+"数量:"+propnum4+" | ";
           }
           
            var b = document.createElement('tbody');
            var r = document.createElement('tr');
            var c = document.createElement('td');
            var e = document.createTextNode(htmlPage);
            c.appendChild(e);
            r.appendChild(c);
            b.appendChild(r);
                
            document.getElementById("mailtag").appendChild(b);
            $("#looktagModal").modal({backdrop:"static"}).modal('show');
        });
    });
    
    /**
     * 用户导入显示模态框
     * **/
     $("#loadUserBtn").click(function(){
    	 
    	 $("#addFileModal").modal({backdrop:"static"}).modal('show');
     
     }); 
    
$("#mailTable tr").each(function() {
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
  
  /* 邮件管理
  $(window).load(function() 
  {
    	var tabaparent = null;
    	var i = 1;
    	var total = 0;
    	var sum = 0 ;
    	var tabaparent = null;  
    	var menuType = null;
    	
    	$(".tabaparent li").click(function(e){
    		// 发布中
    		if(e.target == $("#releasetab")[0])
    		{    
    			setCookie('tab2pages',0); 
    			setCookie('tab4pages',0);
    			
    			setCookie('menuType','releasetab'); 
				
    			 
    			if(getCookie('tab2pages')>0)
    			{
    				total = parseInt(getCookie('tab2pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab2pages',sum);
    			
    			i++;
    			
    		}// 已失效
    		else if( e.target == $("#failuretab")[0] )
    		{
    			setCookie('tab2pages',0); 
    			setCookie('tab4pages',0);
    			
    			setCookie('menuType','failuretab'); 
				
    			 
    			if(getCookie('tab4pages')>0)
    			{
    				total = parseInt(getCookie('tab4pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab4pages',sum);
    			
    			i++;
    		}
    	}); 
    	 
    	tabaparent = getCookie('menuType'); 
    	
    	if(tabaparent)
    	{
    		$("#"+tabaparent).click(); 
    	}  
    	
   });   
		    
    // 待审核 图标 初始 page
    $("#releasetab").click(function()
    {
    	var pageTota = getCookie('tab2pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "showNotice?p="+1; 
    	}
    });
    // 通过 图标 初始 page
    $("#failuretab").click(function()
    {
    	var pageTota = getCookie('tab4pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "showNotice?p="+1; 
    	}
    });**/
  // 邮件撤销
  
  // 撤回一个mail
  $("#mailrelease-table tr td").each(function() {
     var _this = $(this);
     
     _this.find("#editMailBtn").click(function() {
    	// alert(56568999);
    	 var mailId = $(this).attr("lick-mail-id");
         var ServerId = $(this).attr("lick-serverId");
         
    	 //alert('mialid:'+mailId);
    	 //alert('serverid:'+ServerId);
         
         if(confirm("是否要撤回"+ServerId+"服"+"邮件ID是"+mailId+"么？"))
         {
        	 $.ajax({
                 type: 'POST',
                 url: "/mail/mail_retract",
                 data: "mailId="+mailId+"&ServerId="+ServerId,
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
  $(window).load(function() 
    {
    	var tabaparent = null;
    	var i = 1;
    	var total = 0;
    	var sum = 0 ;
    	var tabaparent = null;  
    	var menuType = null;
    	
    	$(".tabaparent li").click(function(e){
    		// 发布中
    		if(e.target == $("#releasetab")[0])
    		{    
    			setCookie('tab2pages',0);
    			setCookie('tab3pages',0);
    			setCookie('tab4pages',0);
    			
    			setCookie('menuType','releasetab'); 
				
    			 
    			if(getCookie('tab2pages')>0)
    			{
    				total = parseInt(getCookie('tab2pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab2pages',sum);
    			
    			i++;
    			
    		}// 已失效
    		else if( e.target == $("#failuretab")[0] )
    		{	setCookie('tab2pages',0);
    			setCookie('tab3pages',0);
    			setCookie('tab4pages',0);
    			
    			setCookie('menuType','failuretab'); 
				
    			 
    			if(getCookie('tab4pages')>0)
    			{
    				total = parseInt(getCookie('tab4pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab4pages',sum);
    			
    			i++;
    		}
    		// 邮件补偿
    		else if( e.target == $("#reimbursettab")[0] )
    		{
    			 
    			setCookie('tab2pages',0);
    			setCookie('tab3pages',0);
    			setCookie('tab4pages',0);
    			
    			setCookie('menuType','reimbursettab'); 
				
    			 
    			if(getCookie('tab3pages')>0)
    			{
    				total = parseInt(getCookie('tab3pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab3pages',sum);
    			
    			i++;
    		}
    	}); 
    	 
    	tabaparent = getCookie('menuType'); 
    	
    	if(tabaparent)
    	{
    		$("#"+tabaparent).click(); 
    	}  
    	
   });   
    
    // 待审核 图标 初始 page
    $("#releasetab").click(function()
    {
    	var pageTota = getCookie('tab2pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "mail_manage?p="+1; 
    	}
    });
    // 通过 图标 初始 page
    $("#failuretab").click(function()
    {
    	var pageTota = getCookie('tab4pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "mail_manage?p="+1; 
    	}
    });
    // 通过 图标 初始 page
    $("#reimbursettab").click(function()
    {
    	var pageTota = getCookie('tab3pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "mail_manage?p="+1; 
    	}
    });
});
///////////////
function mailVerify(obj)
{
	// alert(123);
	var startTime = $(obj).find('input[name=createTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endtime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }   
} 
 