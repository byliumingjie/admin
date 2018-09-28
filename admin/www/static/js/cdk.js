/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
	 
	$("#savePerBtn").click(function() {
        var server = $("#savePerMailForm select[name=sid]").val();
        if(server==0||server ==="")
        {
            alert("请选择服务器");
            return false;
        } 
        // 表情包ID
        /* var faceid = $("#faceid").val(); 
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
        $("#savePerMailForm select[name=propid4]").val(propid4);*/
        
        var form = $("#savePerMailForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: "/cdk/Add_Gift",
            data: form,
            dataType: 'json',
            success: function(result) {
                
                if (result.errcode == 0) 
                {
                	alert('礼包添加成功!');
                    window.location.href = '/cdk/giftlist?giftid='+result.msg;
                }else{
                	alert(result.msg);
                }
            }
        });
    }); 
	
    //显示模态框
     $("#addName").click(function(){  
		  
    	 $("#state").val('');
    	 $("#hidput").attr("value",1);
    	 //初始化
    	 $('#listsheet').html("");
    	 $("#addFileModalLabel").text("选择文件");
    	 $("#selector").css("visibility","visible");
    	 $("#addFiletestBtn").remove();
    	 $("#addFileModal").modal({backdrop:"static"}).modal('show');
    }); 
     $("#cancelBtn").click(function(){ 
    	 history.go(0);
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
            // if (!(ext && /^(xml|XML|txt)$/.test(ext))) {
            if (!(ext && /^(xls)$/.test(ext))) {  
                alert("您上传的文档格式不对,不是正确的xls文件,请重新选择！");  
                return false;  
            } 
          $("#state").val( file);
        },
        // 开始上传文件
        onSubmit: function (file, extension) {
            //$("#state").val("正在上传" + file + "..");
            $("#addFileModalLabel").text("正在上传" + file + "..");
        },

        // 上传完成之后
        onComplete: function (file, response) {
           //$("#state").val("上传完成");
           $("#addFileModalLabel").text("上传完成");
           $("#addFileModalLabel").text("请完成附加文件选项,开始进行添加");
           $("#selector").css("visibility","hidden");
           //$("#addFileBtn").css("visibility","hidden"); 
           $("#addFileBtn").detach();
           var data = JSON.parse(response);
            
           /**
			* 这个地方判断的是在控制器里面的$this->outputJson目录在（\framework\system\libraries\controller.lib） 
			* 找到这个函数里面的设置所引用的参数进行判断
			**/
            if(data.errcode == 0 )
            { 
                //处理接收到的数据
                GetPage(data.msg);
               
       			$("#addFiletestBtn").click(function ()
    		    { 
    		    	 $("#formm").submit(); 
    		    }); 
            }else{            	 
            	/**
            	 * 其实在$this->outputJson 本身的echo json_encode($rs);
            	 * 是不会提示的调用js进行把在控制器里面传入的第一个变量值所做的引用如果是非0就会进行提示相关错误的信息
            	 * */
                alert(data.msg);
            }
        }
    };
 
    //添加数据
    $("#confirmBtn").click(function() {
        var param = $("#forbidAccountForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'add',
            data: param,
            dataType: 'json',
            success: function(result) {
        		alert(result.msg);
                if (result.errcode == 0) 
                {
                	history.go(0);
                    //window.location.href = window.location.href;
                } 
            }
        });
    });
    // 
    
    /***
     * （点击确定按钮执行-》执行控制层函数-》addExecl()进行处理上传文件其次进行读取相关数据进行解析然后在进行返回结果集）
     * */
    var oAjaxUpload = new AjaxUpload('#selector', uploadOption);
    
    // 给上传按钮增加上传动作
    $("#addFileBtn").click(function ()
    {
        oAjaxUpload.submit();
    });
 
     //接收数据       
     function GetPage(data)
     {
 	   $('#listsheet').html(""); 
 	   $("#summodel").prepend("<button type='submit' class='btn btn-success' id='addFiletestBtn' name='addFiletestBtn'>开始</button> ");
 	   var pagehtml = '表：<pre class="prettyprint linenums" style="pading:1px"><form id="formm" name="formm" action="loadfile" method="post">'
 	   pagehtml +='<input type="hidden" name="filepath"  value='+data[0].filepath+'><table border =0>';
 		for(var i = 0; i< data.length;++i)
        {
    	   //data[i].id
            pagehtml += '<tr><td><input style="margin:0px"  type="checkbox" value = "'+data[i].id+'" name="checkboxid"/></td><td>'+data[i].name+'</td></tr>'; 
        } 
 	    pagehtml += 
    	   "<tr><td>栏位行:</td><td><input type='text' value='2' style='width:30px;height:10px;margin:0px' name='row'/></<td></tr>" +
    	   "<tr><td>数据行 :</td><td><input type='text' value='1' style='width:30px;height:10px;margin:0px' name='clos'/></<td></tr></table>";
        pagehtml +='</form></pre>';
        $('#listsheet').html(pagehtml); 
     }
    
     // 选中数据是否录入（预览数据层）
    $("#checkAll").click(function(){  
        if(this.checked){ 
            $("#list :checkbox").attr("checked", true);   
        }else{     
            $("#list :checkbox").attr("checked", false); 
        }    
    });  
    // $(selector).submit()
    $("#packageBtn").click(function(){  
    	$("#package").submit();
    }); 
    /**
     * 修改数据
     * **/
    $("#tableExcel tr").each(function() {
        var _this = $(this);
        var  text =null;
        _this.find(".editMenu").click(function() {
            var form = $("#editMenuForm").serializeArray();
            var trs = null;
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                var value = _this.find("."+name).text();
                
                if(name=='ItemList')
                {
                	var jsonData = _this.find("."+name).text();
                	var resultobj  = $.parseJSON(jsonData);
                	
                	$.each(resultobj, function(n, value) {
                	 
	                	if(trs==null){
	                		trs = value.ItemId + "," + value.ItemNumber;
	                	}else{
	                		trs += "&" + value.ItemId + "," + value.ItemNumber;
	                	} 
                	});
                	$("#editMenuForm [name="+name+"]").val(trs);
                	 
                }
                else
                {
                	$("#editMenuForm [name="+name+"]").val(value);
                }
                //value = value.replace(/(^\s*)|(\s*$)/g, "");
                
            }
            //$("#editMenuModal input[name=id]").val(_this.attr("id"));
            $("#editMenuModal").modal({backdrop:"static"}).modal('show');
        }); 
    });
    // AND EIDT
    $("#editMenuBtn").click(function(){    
    	//alert('da');
        var param = $("#editMenuForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'gifedit',
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                	history.go(0);
                    //window.location.href = window.location.href;
                }
            }
        });
    });
    
    // AND EIDT 录入码
    $("#AddCDKBtn").click(function(){    	
        var param = $("#setcdkForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'setcode',
            data: param,
            dataType: 'json',
            success: function(result) {
               
                if (result.errcode == 0) 
                {
                	alert(result.msg);
                	history.go(0);
                    //window.location.href = window.location.href;
                }else{
                	 alert(result.msg);
                	 
                	 $("#AddCDKBtn").removeAttr("disabled");
                	 $("#AddCDKBtn").removeAttr("data-loading");
                	 //location.reload( true );
                     return false;
                }
            }
        });
    });
    
    // 删除
    $(".delUser").each(function() {
        var _this = $(this);
        _this.click(function() {
        	
        	var id = $(this).attr('data-value');
        	
            if (confirm("确定删除礼包Id"+id+"么?")) {
                
                if (id == '' || id == 'undefined') {
                    alert("礼包id不能为空!");
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: 'gifdel',
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
    
  
    $("#platformid").click(function() {
    		 
    		var cdkServerRuleType = $("#setcdkForm select[name=rule]").val();
    		if(cdkServerRuleType!="" && cdkServerRuleType == 2)
    		{ 
    			setCdkRuleVerify();
    		}
    		
    });
});

function CdkAccountVerify()
{ 
	$('#cdknumber').removeAttr("readonly");
	//("#setcdkForm input[name=number]").val("");
	var type = $("#setcdkForm select[name=type]").val();
	
	if(type==1)
	{
		$("#setcdkForm input[name=number]").val(1);
		$('#cdknumber').attr("readonly",true);
	}
	else
	{
		$('#cdknumber').removeAttr("readonly");
		$("#setcdkForm input[name=number]").val('');
	}
		
}
function setCdkRuleVerify()
{ 
	$("#ServerId").empty();
	$('#ServerInfo').css('display','none');
	//$("#serverIdms2side__sx").empty();
	//$("select[name='serverIdms2side__dx[]']").empty();
	// 根据平台遍历出子类区服信息
	var platid = $("#setcdkForm select[name=platformid]").val();
	var cdkServerRuleType = $("#setcdkForm select[name=rule]").val();
	
	if(!platid ||platid<0 || platid=="")
	{
		alert('指定区服规则，请确认平台类型!');
	}
	if(cdkServerRuleType==2 && platid>0) 
	{   
		var param ='platid='+platid;        	 
		 $.ajax({
		 type:'POST',
		 url:"/System/plat_server",
		 data:param,
		 dataType:'json',
		 success: function(result) { 
             if (result.errcode != 0) 
             {   
            	 alert('-1'+result.msg);
            	 location.reload( true );
                 return false;
             }  
             sidSelectList(cdkServerRuleType,result.serverlist);
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
	else{ 
		$("#ServerId").empty();
		$('#ServerInfo').css('display','none');
		
		//$("#ServerInfo").empty();    	
    	//$("select[name='serverIdms2side__dx[]']").empty();    	
		return false; 
	}
}
/*function platformReset()
{
	var cdkServerRuleType = $("#setcdkForm select[name=rule]").val();
	if(cdkServerRuleType!="" && cdkServerRuleType == 2)
	{
		alert(12321);
		setCdkRuleVerify();
	}
	
}*/
function sidSelectList(type,serverlist){	// 
	
	//var serverlist = null; // 平台区服信息
	var serverlistHtml= "";//平台区服html
	//alert(typeof(serverlist));
	if(type==2)
	{
	// 已经选的 + 初始默认区更改状态的
	$.each(serverlist, function(index, value) { 
		serverlistHtml +="<option value="+value+">"+value+"区</option>";
		//serverlistHtml+="<option value="+value+">"+value+"区</option>";
	}); 	
	 
 	$('#ServerInfo').css('display','block');
 	// 【默认展示列表信息主控制】 根据平台 不存在的进行赋值如果已经存在的进行设置 selected='selected'
 	$("#ServerId").append(serverlistHtml); 
 	//$("#liOption").append(serverlistHtml);
 	//$("select[name='ServerId']").append(serverlistHtml);
 	// 【待选区设置还没有选择的区配置】
 	//$("#serverIdms2side__sx").append(wait_SelectList); 	 
 	// 【已选区控制】已经选区设置 根据平台设置已经存在的进行打印赋值
 	//$("select[name='serverIdms2side__dx[]']").append(been_SelectedList); 
	
	}else{
		$('#ServerInfo').css('display','none');
		$("#ServerId").empty();
    	//$("#serverIdms2side__sx").empty();
    	//$("select[name='serverIdms2side__dx[]']").empty();
	}
}

function cdkVerify(obj)
{   
	 var giftid = $(obj).find('select[name=giftid]').val();	
	 
	 giftid = parseInt(giftid);
	 if(giftid == 0 || giftid === null)
	 {
	        alert("礼包Id不能为空！");
	        location.reload( true );
	        return false;
	 } 
}
