/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
	 
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
 	   var pagehtml = '表：<pre class="prettyprint linenums" style="pading:1px"><form id="formm" name="formm" action="http://localhost/activityconfig/loadfile" method="post">'
 	   pagehtml +='<input type="hidden" name="filepath"  value='+data[0].filepath+'><table border =0>';
 		for(var i = 0; i< data.length;++i)
        {
    	   //data[i].id
            pagehtml += '<tr><td><input style="margin:0px"  type="checkbox" value = "'+data[i].id+'" name="checkboxid"/></td><td>'+data[i].name+'</td></tr>'; 
        } 
 	    pagehtml += 
    	   "<tr><td>栏位行:</td><td><input type='text' value='2' style='width:30px;height:10px;margin:0px' name='row'/></<td></tr>" +
    	   "<tr><td>数据行 :</td><td><input type='text' value='1' style='width:30px;height:10px;margin:0px' name='clos'/></<td></tr>";
        pagehtml +='<tr><td>追加配置:</td><td><input name="settype" type="radio" value="1" checked="true"/> 添加新的记录项,所设置的平台的id平台,不允许出现重复</td></tr>';
        pagehtml +='<tr><td>覆盖配置:</td><td><input name="settype" type="radio" value="2" />(覆盖前会清理所设置平台的所有数据重新导入)</td></tr></table>';
        pagehtml +='<script>$(function() {$("#liOption").multiselect2side({selectedPosition: "right",moveOptions: false,labelsx: "平台待选区",labeldx: "平台已选区"});});</script> '+
        '<div class="control-group" style="margin-bottom:100px" >'+			    
    	'<div class="controls> '+
    	'<div class="control-group">'+							 
    		 '<select class="form-control"  id="liOption"  multiple="multiple" name="loadplatform[]">'+
    			'<option value="101">测试平台</option>'+    			
    			'<option value="1">混服+应用宝</option>'+
    			'<option value="3">Appstore</option>'+
    			'<option value="5">winpone</option>'+
    			'<option value="4">新马简体</option>'+
    			'<option value="7">新马英文</option></select>'+							    
    	'</div></div></div>';
        pagehtml +='</form></pre>';
        $('#listsheet').html(pagehtml); 
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
	/**
	 * 修改数据
	 
    // each tr td list info 
    $("#tableExcel tr").each(function() {
        var _this = $(this);
        _this.find(".editMenu").click(function() {
            var form = $("#editMenuForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                var value = _this.find("."+name).text();
                value = value.replace(/(^\s*)|(\s*$)/g, "");
                $("#editMenuForm [name="+name+"]").val(value);
            }
            $("#editMenuModal input[name=id]").val(_this.attr("id"));
            $("#editMenuModal").modal({backdrop:"static"}).modal('show');
        }); 
    });
    // edit click alert tr and td info 
    $("#editMenuBtn").click(function(){
        var param = $("#editMenuForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'edit',
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
    });**/
    
});
