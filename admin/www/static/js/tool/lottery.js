/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 */

$(function(){

	
	$("#addLottery").click(function(){		   
		 var param = $("#editMenuForm").serializeArray();
		 	/*if (confirm("你确定要给 "+userName+"角色ID为"+userid+statusInfo+gold+"金币么?")) 
		 	{*/  
		        $.ajax({
		            type:'POST',
		            url:'/lottery/createActivity',
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
	// edit
	$("#editLotteryBtn").click(function(){		   
		 var param = $("#updatLotteryForm").serializeArray();
		 	/*if (confirm("你确定要给 "+userName+"角色ID为"+userid+statusInfo+gold+"金币么?")) 
		 	{*/  
		        $.ajax({
		            type:'POST',
		            url:'/lottery/editActivity',
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
	
    $("#lotterylists tr").each(function() {
        var _this = $(this);
        _this.find(".editlotter").click(function() {
        	  $("#timeTypelist").html(""); 
        	  
        	 // $('#startTimelist').html("");
        	 // $('#endtimelist').html("");
        	  // 必须参数
        	   var id = $(this).parent().parent().find('[data-name=id]').text();
        	   var card_id = $(this).parent().parent().find('[data-name=card_id]').text();        	
               var type = $(this).parent().parent().find('[data-name=type]').text();
               var typeStr = $(this).parent().parent().find('[data-name=typeStr]').text(); 
               var statusStr = $(this).parent().parent().find('[data-name=statusStr]').text();
           	   var status = $(this).parent().parent().find('[data-name=status]').text();
           	   var server = $(this).parent().parent().find('[data-name=server]').text();           	   
           	   
           	   $("#updatLotteryForm [name=id]").val(id);
           	   $("#updatLotteryForm [name=type]").val(type);
           	   $("#updatLotteryForm [name=typeStr]").val(typeStr);
           	   $("#updatLotteryForm [name=card_id]").val(card_id);
           	   $("#updatLotteryForm [name=server]").val(server);
           	   var uptimetype = $("#updatLotteryForm [name=uptimetype]").text();
           	   $('#startTimelist').css('display','none');  
           	   $('#endtimelist').css('display','none');
           	   /***
	              1  限时 
				  2  每周 
				  3  遗迹 
				  4  王座 **/
           	   // 如果为限时并且状态0 关闭就是准备开启
               if(type ==1 && status == -1)
               {    
            	   //alert(uptimetype);
            	   $('#startTimelist').css('display','none');  
            	    
            	   timesList(1,1);
            	   var startTime = $(this).parent().parent().find('[data-name=startTime]').text();
               	   var endtime = $(this).parent().parent().find('[data-name=endtime]').text();               	 
               	  // $("#updatLotteryForm [name=startTime]").val(startTime);
            	   //$("#updatLotteryForm [name=endtime]").val(endtime);            	   
            	   $("#updatLotteryForm [name=statusStr]").val('限时活动设为开启');
            	   $("#updatLotteryForm [name=status]").val(1);
            	  
               }    
               	
               // 限时活动已开启状态设置为关闭
               if(type ==1 && status ==1)
               {         
            	   $('#endtimelist').css('display','none');
            	   $('#startTimelist').css('display','none');
            	   timesList(1,0);
            	   var startTime = $(this).parent().parent().find('[data-name=startTime]').text();
               	   var endtime = $(this).parent().parent().find('[data-name=endtime]').text();
               	   $("#updatLotteryForm [name=startTime]").val(startTime);            	      
               	   
            	   $("#updatLotteryForm [name=statusStr]").val('限时活动设为关闭');
            	   $("#updatLotteryForm [name=status]").val(2);
            	   //设置限制修改字段
            	   //$("#updatLotteryForm [name=startTime]").attr("readonly","readonly");
            	  
            	   $("#updatLotteryForm [name=card_id]").attr("readonly","readonly");
               }
               // 周活动开启
               // 限时活动已开启状态设置为关闭
               if(type ==2 && status ==-1)
               {        
            	   $('#startTimelist').css('display','block');
            	   timesList(2,1);
            	   var startTime = $(this).parent().parent().find('[data-name=startTime]').text();
            	   $("#updatLotteryForm [name=startTime]").val(startTime);   
            	   $("#updatLotteryForm [name=statusStr]").val('周活动设为开启');
            	   $("#updatLotteryForm [name=status]").val(1);
            	   //设置限制修改字段            	 
               }
               // 周活动关闭
               if(type ==2 && status ==1)
               {          
            	   $('#startTimelist').css('display','none');  
            	   $('#endtimelist').css('display','none');
            	   $("#updatLotteryForm [name=statusStr]").val('周活动设为关闭');
            	   $("#updatLotteryForm [name=status]").val(2);
            	   //设置限制修改字段            	   
            	   $("#updatLotteryForm [name=card_id]").attr("readonly","readonly");
               }
              
               if(type ==3 || type==4)
               {       
            	    
            	   $("#updatLotteryForm [name=card_id]").removeAttr("readonly");
            	  
            	   $('#endtimelist').css('display','none');
            	   $('#startTimelist').css('display','none');
            	   if( status ==1 ){
            		$("#updatLotteryForm [name=statusStr]").val('常驻活动设为关闭');
            	   	$("#updatLotteryForm [name=status]").val(2);
            	   //设置限制修改字段            	   
            	   	$("#updatLotteryForm [name=card_id]").attr("readonly","readonly");
            	   }
            	   if(status ==0 ){
            		$("#updatLotteryForm [name=statusStr]").val('常驻活动设为开启');
               	   	$("#updatLotteryForm [name=status]").val(1);               	     
            	   }
               }               
               $("#updatLotteryForm [name=server]").attr("readonly","readonly");
               $("#updatLotteryForm [name=statusStr]").attr("readonly","readonly");
               $("#updatLotteryForm [name=typeStr]").attr("readonly","readonly");
               $("#updatLotteryForm [name=id]").attr("readonly","readonly");
               $("#editlotterModal").modal({backdrop:"static"}).modal('show');
        });
     });
    
    
     /*   var param = $("#editPlatformForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/platform/editPlatform',
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                }
            }
        });*/
    
    
});

function timesList(type,status){
	 
	$("#starttimeId").remove(); 
	$("#endtimeId").remove();
	//$('#endtimelist').css('display','none');
	/*var a = '<script src="http://172.16.8.124:9090/static/js/bootstrap-datetimepicker.min.js"></script>';	
	var b = '<script src="http://172.16.8.124:9090/static/js/bootstrap-datetimepicker.zh-CN.js"></script>';
    var c = '<link rel="stylesheet" href="http://172.16.8.124:9090/static/css/bootstrap-datetimepicker.min.css" />';
    */
	//$("#startTimelist").html("");
	//$("#endtimelist").html("");
	$("#timeTypelist").html("");

	var timJs = "" +
	"<script>" +
	"$('#uptimetype').click(function(){" +
	" $('#endtimelist').css('display','none');$('#startTimelist').css('display','none');" +
	"var typeVar=null;" +
	"typeVar = " +"$('#uptimetype').val(); typeVar = parseInt(typeVar);" +
	"if(typeVar==2){	" +
	" $('#startTimelist').css('display','block'); $('#endtimelist').css('display','block');" +
	"}else if(typeVar==1){$('#endtimelist').css('display','block');}else{$('#startTimelist').css('display','none'); " +
	"$('#endtimelist').css('display','none');" +
	"}" +
	"$('#uptimetype').change(function(){var displayOpt ='';" +
	"displayOpt = typeVar==2? 'block' : 'none';		" +
	" " +
	"" +
	"if(typeVar==0){ $('#endtimelist').css('display','none');$('#startTimelist').css('display','none');}" +
	"if(typeVar==2){ $('#endtimelist').css('display','block');$('#startTimelist').css('display','block');}" +
	"if(typeVar==1){ $('#endtimelist').css('display','block');$('#startTimelist').css('display','none');}" +
	"});" +
	" " +
	"});</script>";
	
	//$('#test57a').prepend(a+b+c);
	
	// 限时活动开启
	if(type==1 && status==1)
	{
		var pagehtml ="";
	    pagehtml += '<label class="control-label" id="endtimeId">执行类型：</label><div class="controls">';
	    pagehtml += "<select name='uptimetype' id='uptimetype' class='form-control' >";
	    pagehtml += "<option value=0>--请选择--</option>";
	    pagehtml += "<option value=1>立即执行</option>";
	    pagehtml += "<option value=2>指定开始时间点</option>"; 
	    pagehtml += '</select></div>';
	     // 开始时间
		var startTimeHtml = '';
		startTimeHtml = '<label class="control-label " id="starttimeId">开始时间：</label>';
		/*startTimeHtml += '<div class="controls">';
		startTimeHtml += '<input type="text" style="width:62%" class="datetimepicker form-control" name="startTime"/></div>';*/
		// 结束时间		
		var endtimeHtml ='<label class="control-label " id="endtimeId">结束时间：</label>';
		/*endtimeHtml += '<label class="control-label">结束时间：</label>';
		endtimeHtml += '<div class="controls">';
		endtimeHtml += '<input type="text" style="width:62%" class="datetimepicker form-control" name="endtime"/></div>';*/
		
		$('#timeTypelist').prepend(pagehtml); 
		$('#startTimelist').prepend(startTimeHtml);
		$('#endtimelist').prepend(endtimeHtml);
		$('#test57a').html(timJs);
	}
	// 周活动活动开启
	if(type==2 && status==1)
	{  
		
		$('#endtimelist').css('display','none');
		var startTimeHtml = '';
		startTimeHtml = '<label class="control-label " id="starttimeId">执行时间：</label>';
		
		/*startTimeHtml += '<div class="controls">';
		startTimeHtml += '<input type="text"  style="width:62%" class="input-mini datetimepicker form-control" name="startTime"/></div>';*/  
		$('#startTimelist').prepend(startTimeHtml);
	} 
}
function GetPage()
{
	$("#startTimelist").html("");
	$("#endtimelist").html("");
	$("#timeTypelist").html("");
	 
	// 开始时间
	var startTimeHtml = '';
	startTimeHtml += '<label class="control-label">开始时间：</label>';
	startTimeHtml += '<div class="controls">';
	startTimeHtml += '<input type="text" style="width:62%" class="input-mini" name="startTime"/></div>';
	// 结束时间
	var endtimeHtml = '';
	endtimeHtml += '<label class="control-label">结束时间：</label>';
	endtimeHtml += '<div class="controls">';
	endtimeHtml += '<input type="text" style="width:62%" class="input-mini" name="endtime"/></div>';
	// 执行时间
	 
    var pagehtml ="";
     pagehtml += '<label class="control-label">执行时间：</label><div class="controls">';
     pagehtml += "<select name='timetype' id='timetype' class='form-control' >";
     pagehtml += "<option value=0>--请选择--</option>";
     pagehtml += "<option value=1>立即执行</option>";
     pagehtml += "<option value=2>指定开始时间点</option>"; 
     pagehtml += '</select></div>';
     
   $('#startTimelist').html(startTimeHtml);
   $('#endtimelist').html(endtimeHtml);
   
   $('#timeTypelist').html(pagehtml); 
   
}

function createActivityVerify(obj){
	// alert(123557);
	/*var server = $(obj).find('input[name=server]').val();//
	if(server === '' || server=== null || server == 0){
       	alert("请输入区服！");
       	location.reload( true );
       	return false;
   } */
}
// 表单验证
function lotteryVerify(obj){ 
	var type = $(obj).find('select[name=type]').val();//   
   if(type === '' || type === null || type == 0){
       	alert("请选择活动类型！");
       	location.reload( true );
       	return false;
   }
	var server = $(obj).find('input[name=server]').val();//   
   if(server === '' || server === null || server == 0){
       	alert("请选择区服ID");
       	location.reload( true );
       	return false;
   }   
}
//submit add 

