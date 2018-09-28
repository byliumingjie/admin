$(function() { 
   $("#activitybtn").click(function() 
   { 
   	var form = $("#CreateMallForm").serializeArray();
       $.ajax({
           type: 'POST',
           url: "createMall",
           data: form,
           dataType: 'json',
           success: function(result) {
               alert(result.msg);
               if (result.errcode == 0) {
                   //window.location.href = '/Mail/';
               }
           },
           error:function(XMLHttpRequest, textStatus, errorThrown)
           {
           	 alert(XMLHttpRequest.status);
             alert(XMLHttpRequest.readyState);
             alert(errorThrown);
             alert(textStatus); // paser error;
           }
       }); 
   }); 
 
    
   $("#mallbtn").click(function() {
   	var form = $("#CreateMallForm").serializeArray();
       $.ajax({
           type: 'POST',
           url: "createMall",
           data: form,
           dataType: 'json',
           success: function(result) {
               alert(result.msg);
               if (result.errcode == 0) {
                   //window.location.href = '/Mail/';
               }
           },
           error:function(XMLHttpRequest, textStatus, errorThrown)
           {
           	   alert(XMLHttpRequest.status);
               alert(XMLHttpRequest.readyState);
               alert(errorThrown);
               alert(textStatus); // paser error;
           }
       }); 
   });  
   
   $("#activityListTable tr").each(function() {
       var _this = $(this);
       // 删除
       _this.find(".delMall").click(function() {  
    	   var id = $(this).attr('data-value');
    	   var serverId = $(this).attr('data-server-id');
    	   
    	   if (confirm("你确定要把活动编号为"+id+"删除么?")) {
    		  // var id = '{"id":'+id+'}';
    		   $.ajax({
                   type:'POST',
                   url:'delMall',
                   data:'id='+id+'&serverId='+serverId,
                   dataType:'json',
                   success:function(result){
                       alert(result.msg);
                       if(result.errcode == 0){
                           window.location.href = window.location.href;
                       }
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
           
         });
       
       // 编辑
       _this.find(".editActivityBtn").click(function() {  
    	   var id = $(this).attr('data-value');
    	  
    	   $("#activityEditForm")
    	   var form = $("#activityEditForm").serializeArray();
           for(var i=0;i<form.length;i++){
               var name = form[i].name;
               
               if(name ==null ||name =="")
               {
                   continue;
               } 
               //var value = $(this).parent().parent().find('[data-name='+name+']').text();
               $("#activityEditForm [name=activityId]").val(id); 
               
           }
           $("#activityEditForm").submit();
        
         });
       // 发布
       _this.find(".ReleaseMallBtn").click(function() {  
    	   var id = $(this).attr('data-value');
    	   var serverId = $(this).attr('data-server-id');
    	   if (confirm("你确定要把活动编号为"+id+"进行发布么?")) {
    		  // var id = '{"id":'+id+'}';
    		   $.ajax({
                   type:'POST',
                   url:'ReleaseMall',
                   data:'id='+id+'&serverId='+serverId,
                   dataType:'json',
                   success:function(result){
                       alert(result.msg);
                       if(result.errcode == 0){
                           window.location.href = window.location.href;
                       }
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
           
         });

       // 请求数据
       _this.find(".serverInfo").click(function() {
       	 
           var form = $("#serverInfoForm").serializeArray();
           for(var i=0;i<form.length;i++){
               var name = form[i].name;
               
               if(name ==null ||name =="")
               {
                   continue;
               }
                
               var value = $(this).parent().parent().find('[data-name='+name+']').text();
               $("#serverInfoForm [name=RequestData]").val(value);
       
           }
           //$("#serverinfoModal input[name=id]").val(_this.attr("id"));
           $("#serverinfoModal").modal({backdrop:"static"}).modal('show');
       });
    });
   
       // 批量活动发布
      	$("#addMallBtn").click(function(){
      		
      		var form = $("#ReleaseMallForm").serializeArray();
      		if (confirm("你确定要发布已选的活动么?")) { 
     		   $.ajax({
                    type:'POST',
                    url:'ReleaseMall',
                    data:form,
                    dataType:'json',
                    success:function(result){
                        alert(result.msg);
                        if(result.errcode == 0){
                            window.location.href = window.location.href;
                        }
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
      	});
 
     // 分页
   $(window).load(function() { 
		    	var tabaparent = null;
		    	var i = 1;
		    	var total = 0;
		    	var sum = 0 ;
		    	var tabaparent = null;  
		    	var menuType = null;
		    	
		    	$(".tabaparent li").click(function(e){
		    		// 待发布
		    		if(e.target == $("#untreatedRelease")[0])
		    		{    
		    			setCookie('tab1pages',0);
		    			setCookie('tab2pages',0);
		    			setCookie('tab3pages',0);
		    			
		    			setCookie('menuType','untreatedRelease'); 
						
		    			 
		    			if(getCookie('tab1pages')>0)
		    			{
		    				total = parseInt(getCookie('tab1pages'));
		    			}
		    			sum = i + total;
		    			 
		    			setCookie('tab1pages',sum);
		    			
		    			i++;
		    			
		    		}// 已发布
		    		else if( e.target == $("#successRelease")[0] )
		    		{	setCookie('tab1pages',0);
		    			setCookie('tab2pages',0);
		    			setCookie('tab3pages',0);
		    			
		    			setCookie('menuType','successRelease'); 
						
		    			 
		    			if(getCookie('tab2pages')>0)
		    			{
		    				total = parseInt(getCookie('tab2pages'));
		    			}
		    			sum = i + total;
		    			 
		    			setCookie('tab2pages',sum);
		    			
		    			i++;
		    		}
		    		// 发布失败
		    		else if( e.target == $("#failureRelease")[0] )
		    		{
		    			 
		    			setCookie('tab1pages',0);
		    			setCookie('tab2pages',0);
		    			setCookie('tab3pages',0);
		    			
		    			setCookie('menuType','failureRelease'); 
						
		    			 
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
		    // 待发布 初始 page
		    $("#untreatedRelease").click(function()
		    { 
		    	var pageTota = getCookie('tab1pages');		    	
		    	if(pageTota==0)
		    	{
		    		location.href = "index?p="+1; 
		    	}
		    });
		    // 已发布标 初始 page
		    $("#successRelease").click(function()
		    {
		    	var pageTota = getCookie('tab2pages');		    	
		    	if(pageTota==0)
		    	{
		    		location.href = "index?p="+1; 
		    	}
		    });
		    // 发布失败  初始 page
		    $("#failureRelease").click(function()
		    {
		    	var pageTota = getCookie('tab3pages');		    	
		    	if(pageTota==0)
		    	{
		    		location.href = "index?p="+1; 
		    	}
		    });
 }); 


function setConditionalRules(htmlinfo,amount)
{
	
	var trStartHtml = "";
	var htmllist = "";
	var tokenStartHtml = "<table class='table  table-striped'><tbody>";
	var tokenEndHtml = "</tbody></table>";
	var trEndHtml = "</div></label></div></td><td></td></tr>";
	var html = "";
	var termCheckHtml ="";
	for(var i=1;i<=amount;i++)
	{
		termCheckHtml = '<input type="checkbox" id="term'+i+'" value="term'+i+'" name="checkbox[]">&nbsp;';
		
		trStartHtml = "<tr id='activityBytr"+i+"'>" +
		"<td><div class='control-group' style='margin:0px;padding:0px'>" +
		"<label class='control-label'>条件"+i+"</label>" +
		"<label class='checkbox-inline'><div class='controls'>";
		 
		html=trStartHtml+termCheckHtml+htmlinfo+trEndHtml;
		
		htmllist+=html;
		
	}
	return tokenStartHtml+htmllist+tokenEndHtml;
}

function optionVerify(byid=null,name=null,type=null){
	  
	var count=$("#"+byid+" option").length;
	
	var name =name+'[]';
	
	for(var i=0;i<count;i++)  
	{ 
		  var optionstr = $("#"+byid).find('select[name="'+name+'"]').get(0).options[i].value;

		  optionstr = parseInt(optionstr);
		  
		  if(optionstr == type)  
	      {  
			$("#"+byid).find('select[name="'+name+'"]').get(0).options[i].selected = true; 
	        break;  
	      } 
	 }	  
}

function optionVerifySet(byid=null,type=null,display=false,serverId=null)
{ 
  var count=$("#"+byid+" option").length;
  for(var i=0;i<count;i++)  
  {     
	  var optionstr = $("#"+byid).get(0).options[i].value;
	  
	  if(optionstr == type)  
      {  
        $("#"+byid).get(0).options[i].selected = true; 
        break;  
      } 
  }
   
  if(byid=='ServerId')
  {
	  $("#ServerId").prepend("<option value="+type+">"+type+"区</option>");
  }
}