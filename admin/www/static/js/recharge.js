/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {
	
	// 支付
    $("#btn_date").click(function() {
    	 
        var platfrominfo = '';
        
        var channelinfo = '';
        
        var orderstau 	= ''
        
    	var arr = [];
    	
    	/*$('#container li .itemsChoice').each(function()
    	{    			 
    		arr.push( $(this).val());
    	})
    	
    	for(var i=0;i<arr.length;i++)
    	{ 
    		platfrominfo+=platfrominfo!=''?','+arr[i]:arr[i];
    	}
    	
    	// 初始化进行复用该数组
    		arr = [];
    	
    	$('#container2 li .itemsChoice').each(function()
    	{			 
			arr.push( $(this).val());
    	})
    	 
    	for(var i=0;i<arr.length;i++)
    	{
    		channelinfo+=channelinfo!=''?','+arr[i]:arr[i];    		
    	} */
    	
    	// 订单状态	
    	arr = [];
		
    	$('#container3 li .itemsChoice').each(function()
    	{			 
			arr.push( $(this).val());
    	})
    	
    	for(var i=0;i<arr.length;i++)
    	{
    		orderstau+=orderstau!=''?','+arr[i]:arr[i];    		
    	} 
    	
    		    	
    	//$("#platfrominfo").attr("value",platfrominfo);
    	
    	//$("#channelinfo").attr("value",channelinfo);
    	
    	$("#orderstau").attr("value",orderstau);
    	 
    });
    
   /* $("#container2").click(function() {
    	
    	alert('123');
    });*/
    
    function PayPutVerify(obj){ }
    
 /*   $("#selPlatform").change(function()
    {
       var PlatformId = $("#selPlatform").val();
       $.ajax({
            type:'POST',
            url:'/lookform/getServer',
            data:"Selplatform="+PlatformId,
            dataType:'json',
            success:function(result){
                if(result.errcode == 0){
                    loadServer(result.msg);
                }
            },
            beforeSend: function(){
                $("#selPlatform").addClass("disabled");
            }
        });
    });*/
    
  /*  function loadServer(data)
    {
        $("#selServer").find("option").remove();
        var htmlPage = '<option value="">请选择区服</option>';
        for(var i = 0; i< data.length;++i)
        {
           htmlPage += '<option value="'+ data[i]['sid']+'">'+data[i]['sname']+ '</option>';        
        }
        $("#selServer").html(htmlPage);
    }
    
    $("#accountBtn").click(function()
    {
        var param = $("#OrderForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/lookform/getData',
            data:param,
            dataType:'json',
            success:function(result){
               
                if(result.errcode == 0){
                    window.location.href = window.location.href;
                }
            },
            beforeSend: function(){
                $("#accountBtn").addClass("disabled");
            }
        });
    });*/
    // 补单属性获取
    $("#ordertable").each(function() {
    	
        var _this = $(this);
        
        _this.find(".addbackBtn").click(function() {
        	
        	 var id = $(this).attr('data-value');
        	 
        	 if(id == '' || id == 'undefined')
        	 {
        		 alert("id不能为空!");
                 return false; 
        	 }
        	  
             var form = $("#submitOrderForm").serializeArray();
              
             for(var i=0;i<form.length;i++)
             {
                 var name = form[i].name;
                 
                 if(name ==null ||name =="")
                 {
                      continue;
                 } 
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();
                 
                 $("#submitOrderForm [name="+name+"]").val(value);
             }  
             $("#submitOrderForm [name=id]").val(id);
             
             $("#submitOrderModal").modal({backdrop:"static"}).modal('show');
         });
        
        // 订单类型
        _this.find(".upordertype").click(function() {
        	
	       	 var id = $(this).attr('data-value');
	       	 var orderId = $(this).attr('data-orderid');
	       	 var ordertype = $(this).attr('data-ordertype');
	       	 
	       	 if(id == '' || id == 'undefined')
	       	 {
	       		 alert("id不能为空!");
	                return false; 
	       	 }       	   
            
	       	 $("#subeditOrdertype [name=id]").val(id);
            
            $("#subeditOrdertype [name=orderId]").val(orderId);
            if(ordertype == 1)
            {
            	$("#subeditOrdertype [name=OrderType]").html('<option value="2">正常订单</option>');
            }else if(ordertype == 2)
            {
            	$("#subeditOrdertype [name=OrderType]").html('<option value="1">测试订单</option>');
            }
            
            $("#subeditOrderType").modal({backdrop:"static"}).modal('show');
        });
        
        //订单失败原因
        _this.find(".paystauBtn").click(function() {
        	
	       var log = $(this).attr('data-value'); 
           //alert(log);
	       $("#orderLog").html(log); 
          
           $("#orderlogMode").modal({backdrop:"static"}).modal('show');
       });
        
        
    });
    // 补单提交
     $("#submitbtn").click(function(){
    	 var orderid = $("#submitOrderForm input[name=tcd]").val();
    	 
    	 if (confirm("确定要把订单为"+orderid+" 进行重新补单么？")) {  
	          var param = $("#submitOrderForm").serializeArray();
	          $.ajax({
	            type:'POST',
	            url:'EditOrder',
	            data:param,
	            dataType:'json',            
	            success:function(result)
	            {	 
	        		 if (result.errcode == 0) 
	                 {
	                 	alert(result.msg);
	                 	history.go(0);	                     
	                 }else{
	                 	 alert(result.msg);	                 	 
	                 	 $("#submitbtn").removeAttr("disabled");
	                 	 $("#submitbtn").removeAttr("data-loading");	                 	 
	                     return false;
	                 }
	        		 
	            } 
	        }); 
     }
    });
    $("#submitOrderTypebtn").click(function(){    	 
        var param = $("#subeditOrdertype").serializeArray();
          $.ajax({
            type:'POST',
            url:'EditOrderType',
            data:param,
            dataType:'json',            
            success:function(result)
            {	 
        	  	if(result.msg){
        	  		alert(result.msg);
            	}
        		if(result.errcode == 0)
                {
                    window.location.href = window.location.href;  
                }
            },
            beforeSend: function(){
                $("#submitbtn").addClass("disabled");
            }
        }); 
    });
     
     // edit ordery type 
     /*$(".upordertype").each(function() {
     	 
         var _this = $(this);
         _this.click(function() {
        	 	var orderid = $(this).attr('data-orderid');
             if (confirm("确定要把订单为"+orderid+"设置为测试订单么？")) {
                 var id = $(this).attr('data-value');
                 if (id == '' || id == 'undefined') {
                     alert("id不能为空!");
                     return false;
                 } 
                 $.ajax({
                     type: 'POST',
                     url: 'EditOrderType',
                     data: 'id='+id,
                     dataType: 'json',
                     success: function(result) 
                     {
	                	 if(result.msg){
	                         alert(result.msg);
	                	 }
                         if (result.errcode == 0) {
                             window.location.reload();
                         }
                     }
                 });
             }
         });
     });
    */
});

