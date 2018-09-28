/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
$(function () {	  
    $("#platById").click(function() {
    		
    		$("#ServerId").html('');
    		$("#ServerId").empty();
    		
    		var platId = $("#platById").val();
    		var platIdw = $("#platById").text();
    		var platIde = $("#platById select[data-plat=platId]").val();
    		platId = parseInt(platId);
    		if(platId!="" && platId != null && platId!='undefined')
    		{ 
    			setCdkRuleVerify(platId,'ServerId');
    		}else{
    			$('#ServerId').css('display','none');
    		} 
    });
    
    $("#PlatUserPhotosId").click(function() {
		
		$("#allclickserver").html('');
		$("#allclickserver").empty();
		
		var platId  = $("#PlatUserPhotosId").val();
		var platIdw = $("#PlatUserPhotosId").text();
		var platIde = $("#PlatUserPhotosId select[data-plat=platId]").val();
		platId = parseInt(platId);
		if(platId!="" && platId != null && platId!='undefined')
		{ 
			setCdkRuleVerify(platId,'allclickserver');
		}else{
			$('#allclickserver').css('display','none');
		} 
    });
    
    $("#platById2").click(function() { 
    	
		$("#ServerId2").html('');
		$("#ServerId2").empty();
		  
		var platId = $("#platById2").val();
		var platIdw = $("#platById2").text();
		var platIde = $("#platById2 select[data-plat=platId2]").val();
		platId = parseInt(platId);
		if(platId!="" && platId != null && platId!='undefined')
		{ 
			setCdkRuleVerify(platId,'ServerId2');
			$('#serverGroup').css('display','block');
		}else{
			$('#serverGroup').css('display','none');
			$('#ServerId2').css('display','none');
		} 
});
});

 
function setCdkRuleVerify(platId,serverId='serverId')
{
	if(platId>0) 
	{   
		var param ='platid='+platId;        	 
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
             sidSelectList(result.serverlist,serverId);
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
		return false; 
	}
}
 
function sidSelectList(serverlist,serverId='ServerId'){ 
	 
	$("#"+serverId).empty();
	
	var serverlistHtml= "";//平台区服html
	
	
	$('#'+serverId).css('display','none');
	
	if(serverId == 'allclickserver')
	{
		serverlistHtml +="<option value=0>获取全服内容</option>"; 
	}
	
	// 已经选的 + 初始默认区更改状态的
	$.each(serverlist, function(index, value) { 
		serverlistHtml +="<option value="+value+">"+value+"区</option>"; 
	}); 	
	 
 	$("#"+serverId).append(serverlistHtml); 
 	$('#'+serverId).css('display','block'); 
}

 
