var fullName ='Contains';
curSelectMap={};
var g_allSelect = false;


$.expr[":"][fullName] = function(a,i,m)
{		 
	return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};	   
	   
$(function() { 
	   // item search set search buttion
	   filterList($("#form"), $("#iteminfosetId"));
	   
	 /*  $('#addIteminfocfg').click(function(){
		   
		   var Initemconfig = $('#itemInfo').val();
		   
		   if(Initemconfig==null || Initemconfig=='' || Initemconfig =='undefined')
		   {
			   alert('道具配置信息为空!');
			   return false;
		   }else{
			   alert(Initemconfig);
		   } 
	   });*/
	   
	   $("#addIteminfocfg").click(function(){
		   
	   var iteminfo = $("#itemInfo").val();
		   
	   if(iteminfo==null || iteminfo=='' || iteminfo =='undefined')
	   {
		   alert('道具配置信息为空!');		   
		   return false;
	   }
   		var  GetItemById= $("#ItemListForm [name=itemOptionBtn]").val();
   		
   		
   		 $.ajax({
   	           type: 'POST',
   	           url: "setIteminfo",
   	           data: 'iteminfo='+iteminfo+'&itemId='+GetItemById,
   	           dataType: 'json',
   	           success: function(result) {
   	               
   	               if (result.errcode == 0) 
   	               { 
   	            	   
   	                  $("#"+result.itemhtmlid).val(result.IitemjsonList);
   	                  curSelectMap = {};
   	                  curSelectMap.length = 0;
   	                  curSelectMap = [];
   	              	  $("#itemInfo").val(null);
   	              	  $("#iteminfoSetUl input ").removeAttr("checked");   	              	  
   	                  $("#addloginNoticeModal").modal({backdrop:"static"}).modal('hide');
   	               }
   	               else{
   	            	   $("#addloginNoticeModal").modal({backdrop:"static"}).modal('hide');
   	            	   alert(result.msg);
   	            	   return false;
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
	   
	   //--------------- get item type -> ajax loding item config info 
	   $("#ItemtypelistId").click(function(){
	   $("#itemresetid2").html('');
	   $("#iteminfosetId").html('');
	   
	   var itemresetid2 = getjslist();
	   
	   var ItemtypelistId = $("#ItemtypelistId").val();
	   var iteminfoList = $("#itemInfo").val();
	   
	   
	   ItemtypelistId = parseInt(ItemtypelistId);
	   if(ItemtypelistId!=0)
	   {   
		   $.ajax({
	           type:'POST',
	           url:'getItemInfo',
	           data:'itemtype='+ItemtypelistId+'&itemlist='+iteminfoList,
	           dataType:'json',
	           success:function(result){
	                
	               if(result.errcode == 0){
	            	    
	            	   $("#iteminfosetId").html(result.itemlistinfohtml); 
	            	   $("#itemresetid2").html(itemresetid2);	            	 
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
	// default js 
   $("#iteminfoSetUl ul li").each(function() {
	     var _this = $(this);
	     var btntype =null;
	     _this.find("#editUser").click(function() 
	     {  
	    	var itemName = $(this).attr("data-name");
	    	var itemID = $(this).attr("value");  
	    	
	    	var dataname= 'itemnumbers'+itemID;
	    	var number = $(this).parent().parent().find("[data-name="+dataname).val(); 
	    	
	    	if(number=="" || number=="undefined" || number<0)
	        {
				alert('请您输入道具数量!');
				return false;
	        }
	    	if(isNaN(number))
	    	{	    		
	    		alert('数量格式有误!');
	    		return false;
	    	}	    	
	    	var type = $(this).parent().parent().find("[data-name="+itemName).is(":checked");    	
	    	var btntype = $("#editUser").is(":checked");
	    	  
	    	if(type==true)
	    	{	  
	    		curSelectMap[itemID] = itemName+","+itemID+","+number+";"; 
	    	}
	    	else
	        {
	    		curSelectMap[itemID] = null;
	    	}
	    	showSelect(); 
	     });
	 });

 }); 
// item search
function filterList(header, list) 
{
	  input = $("<input>").attr({"class":"filterinput","type":"text","placeholder":"Item Search..."});
	  $('#ItemListForm').append(input).appendTo(header);
	  $(input)
	      .change( function () {
	        var filter = $(this).val();
	         
	        if(filter) {
		        
			  $matches = $(list).find('span:Contains('+filter+')').parent();
			 
			  $('li', list).not($matches).slideUp();
			 $matches.slideDown();
	        } else {
	          $(list).find("li").slideDown();
	        }
	        return false;
	      })
	    .keyup( function () {
	        $(this).change();
	    });
 }
	  
//////////


function showSelect()
{
	$("#itemInfo").val("");
	for(var itemID in curSelectMap)
	{
		if(curSelectMap[itemID] != null)
		{
		$("#itemInfo").val($("#itemInfo").val()+curSelectMap[itemID]+"\r\n");
		}
	}
}

function onSelect(domEle)
{
	var itemName = $(domEle).attr("data");
	var itemID = $(domEle).attr("value");
	if(domEle.checked==true)
	{
	curSelectMap[itemID] = itemName+","+itemID+";";
	}
	else
	{
	curSelectMap[itemID] = null;
	//alert("未选中");
	}
	showSelect();
}

function selAllSites()
{

	var a = document.getElementsByTagName("input");

	for (var i=0;i<a.length;i++)
	{
		if (a[i].type == "checkbox")
		{
			a[i].checked = !g_allSelect;
	
			var itemName = $(a[i]).attr("data");
			var itemID = $(a[i]).attr("value");
	
			if(a[i].checked)
			{
	
			curSelectMap[itemID] = itemName+","+itemID+";";
			}
			else
			{
			curSelectMap[itemID] = null;
			}
		}
	}
}

function getjslist(){
	var htmljs= "<script>" +
			"var g_allSelect = false;" +
			"function showSelect(){" +
			"$('#itemInfo').val('');" +
			"for(var itemID in curSelectMap){" +
			   "if(curSelectMap[itemID] != null)" +
			   "{" +
			   "$('#itemInfo').val($('#itemInfo').val()+curSelectMap[itemID]+"+"\"\\n\""+");" +
			   "}}}" +
			"$('#iteminfoSetUl ul li').each(function() { " +
			"var _this = $(this);" +
			"var btntype =null;" +
			"_this.find('#edituseropt').click(function() { " +			
			"var itemName = $(this).attr('data-name');" +
			"var itemID = $(this).attr('value'); " +
			"var dataname = 'itemnumbers'+itemID;" +
			"var number = $(this).parent().parent().find('[data-name='+dataname).val();" +			 
			"if(number=='' || number=='undefined' || number<0){" +
			"alert('请您输入道具数量!');" +
			"return false;}" +
			"if(isNaN(number)){" +
			"alert('数量格式有误!'); return false;" +
			"}" +
			"var type = $(this).parent().parent().find('[data-name='+itemName).is(':checked');" +
			"var btntype = $('#edituseropt').is(':checked');" +			 
			"if(type==true){" +
			"curSelectMap[itemID] = itemName+','+itemID+','+number+';';}" +
			"else{" +
			"curSelectMap[itemID] = null;"+
			"}" +
			"showSelect(); " +
			"});});" + "showSelect();"+"" +			 
			"</script>";  
	     
		return htmljs;
	}
