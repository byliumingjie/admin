/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
	 
    //显示模态框
     $("#addName").click(function(){  
		  
    	 $("#state").val('');
    	 //??
    	 $("#hidput").attr("value",1);
    	 //初始化文件表单值
    	 $('#listsheet').html("");
    	 // title
    	 $("#addFileModalLabel").text("选择文件");
    	 // 表单 选择的文件的按钮 样式  显示 , load ok set css ( visibility , hidden );
    	 $("#selector").css("visibility","visible");
    	 // 移除确认添加
    	 $("#addFiletestBtn").remove();   
    	 // 弹出模板框
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
                alert("您上传的文档格式不对，请重新选择！");  
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
    
    $("#loadActivityMode").click(function()
    {
    	$('#frommode').submit();    	
    });
    //添加数据
    $(".activityFileBtn").click(function() {
    	alert(11);
        var param = $("#activityFileLoadForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'ImportactivityConfg',
            data: param,
            dataType: 'json',
            success: function(result) {
        		alert(result.msg);
                if (result.errcode == 0) 
                {
                	history.go(0);
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
    function getPageJs(){
    	
    	var jsdata = "<script type='text/javascript'>$(function(){" +
    			"$('.activityFileBtn').click(function() {" +
    			"var param = $('#formm').serialize();" +
    			"if (confirm('你确定导入活动,覆盖原由数据配置么？')) " +
    			"{" +
    			"$.ajax({" +
    			"type: 'POST'," +
    			"url: 'ImportItemConfg'," +
    			"data: param," +
    			"dataType: 'json'," +
    			"success: function(result) {" +
    			"alert(result.msg);" +
    			"if (result.errcode == 0) {" +
    			" history.go(0); " +
    			"}},error:function(XMLHttpRequest, textStatus, errorThrown){" +
    			"alert(XMLHttpRequest.status);alert(XMLHttpRequest.readyState);" +
    			"alert(errorThrown);" +
    			"alert(textStatus);}});" +
    			"}" +
    			"});" +
    			"});</script>";
    	return jsdata;
    					
    }
     //接收数据   action="ImportactivityConfg"    
     function GetPage(data)
     {
 	   $('#listsheet').html(""); 
 	   $("#summodel").prepend("<button type='button' class='btn btn-success activityFileBtn' " +
 	   	"id='addFiletestBtn' name='addFiletestBtn'>开始</button> ");
 	   var pagehtml = '原表：<pre class="prettyprint linenums" style="pading:1px"><form id="formm" name="formm"  method="post">'
 	   pagehtml +='<input type="hidden" name="filepath"  value='+data[0].filepath+'><table border =0>';
 		for(var i = 0; i< data.length;++i)
        {
    	   //data[i].id
            pagehtml += '<tr><td><input style="margin:0px"  type="checkbox" value = "'+data[i].id+'" name="checkboxid"/></td><td>'+data[i].name+'</td></tr>'; 
        } 
 	    pagehtml += 
    	   "<tr><td>栏位行:</td><td><input type='text' value='2' style='width:30px;height:10px;margin:0px' name='row'/></<td></tr>" +
    	   "<tr><td>数据行 :</td><td><input type='text' value='1' style='width:30px;height:10px;margin:0px' name='clos'/></<td></tr>";
 	   pagehtml +='<tr><td>目标配置:</td><td><select><option value=1>道具</option><option value=2>装备</option><option value=3>表情</option><option value=4>套装</option><option value=5>技能</option></select></td></tr>';
        pagehtml +='<tr><td>追加配置:</td><td><input name="settype" type="radio" value="1" checked="true"/> 添加新的记录项</td></tr>';
        pagehtml +='</table>';
         
        var jsinfo = getPageJs();
        pagehtml +='</form></pre>';
        $('#listsheet').html(pagehtml+jsinfo); 
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
    
});
