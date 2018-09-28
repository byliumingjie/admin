$(function() {
    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
    // 添加道具配置
    $("#ItemconfigBtn").click(function() {
     
        var param = $(this).serialize();
        
        var form = new FormData(document.getElementById("itemForm"));
         
        $.ajax({
        	    url: 'addItemPrizes',
        	    type: 'POST',        	    
        	    data:  form, 
        	    processData:false,
                contentType:false,
                dataType: 'json',
                success: function(data) {                 
            	alert(data.msg);            	 
                if (data.errcode == 0) 
                {   
                	history.go(0);;
                } 
            },
			   error: function(XMLHttpRequest, textStatus, errorThrown) {
			 alert(XMLHttpRequest.status);
			 alert(XMLHttpRequest.readyState);
			 alert(textStatus);
			   }
        });
          
    });
    // 编辑道具配置
    $("#editMenuBtn").click(function() {
     
        var param = $(this).serialize();
        
        var form = new FormData(document.getElementById("editItemForm"));
         
        $.ajax({
        	    url: 'editItem',
        	    type: 'POST',        	    
        	    data:  form, 
        	    processData:false,
                contentType:false,
                dataType: 'json',
                success: function(data) {                 
            	alert(data.msg);            	 
                if (data.errcode == 0) 
                {   
                	history.go(0);;
                } 
            }
        });
          
    });
    $("#tableExcel tr").each(function() {
        var _this = $(this);
        _this.find(".editItem").click(function() {
            var form = $("#editItemForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                var value = _this.find("."+name).text();
                value = value.replace(/(^\s*)|(\s*$)/g, "");
                $("#editItemForm [name="+name+"]").val(value);
            }
            var obtain = _this.find(".obtain").text();
            var obtain = obtain.split(',');
            var itemObtainType = obtain[0];
            var itemObtainNum = obtain[1];      
            $("#editItemForm [name='itemoBtainType']").val(itemObtainType);
            $("#editItemForm [name='itemBtainNum']").val(itemObtainNum);
            
            var originalImg = _this.find(".image").text();
            $("#editItemForm [name='originalImg']").val(originalImg);
            
            $("#editItemModal").modal({backdrop:"static"}).modal('show');
        }); 
        
        _this.find(".delItem").click(function() {
        	var id = _this.find(".id").text();
        	
        	if (confirm("确定删除道具"+id+"么？")) 
        	{
        		$.ajax({
                    type: 'POST',
                    url: 'delItem',
                    data: 'id='+id,
                    dataType: 'json',
                    success: function(result) {
                        alert(result.msg);
                        if (result.errcode == 0) {
                        	history.go(0);
                            //window.location.href = window.location.href;
                        }
                    }
                });
            }
        });
       
    });
    
 
    
});
