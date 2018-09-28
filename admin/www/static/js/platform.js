$(function() {
	     
    
	//添加平台
    $("#addPlatformBtn").click(function() {
        var param = $("#addForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/user/addPlatform',
            data: param,
            dataType: 'json',
            success: function(result) {
        	
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                     
                }
            }
        });
    });
    // 游戏平台删除
    $(".delplatfomr").each(function() {
    	 
        var _this = $(this);
        _this.click(function() {
            if (confirm("确定删除此平台么？")) {
                var uid = $(this).attr('data-value');
                if (uid == '' || uid == 'undefined') {
                    alert("id不能为空!");
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '/user/delplatfomr',
                    data: 'id='+uid,
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
    $("#paltformcofTable tr").each(function() {
    	
        var _this = $(this);
        _this.find(".editPlatform").click(function() {
        	 
            var form = $("#editserverForm").serializeArray();
             
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#editserverModal [name="+name+"]").val(value);
            }
            //$("#editPlatformModal input[name=id]").val(_this.attr("id"));
            $("#editserverModal").modal({backdrop:"static"}).modal('show');
        });
      
    });
    // edit
    $("#editPlatformBtn").click(function() {
  	  var param = $("#editserverForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/user/editPlatform',
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                }
            }
        });
  });
    /*
    $("#addPlatformBtn").click(function() {
        var param = $("#addPlatformForm").serialize();
        $.ajax({
            type: 'POST',
            url: '/platform/addPlatform',
            data: param,
            dataType: 'json',
            success: function(result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.href = window.location.href;
                }
            }
        });
    });
	
	$("#paltformTable tr").each(function() {
        var _this = $(this);
        _this.find(".editPlatform").click(function() {
            var form = $("#editPlatformForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#editPlatformModal [name="+name+"]").val(value);
            }
            $("#editPlatformModal input[name=id]").val(_this.attr("id"));
            $("#editPlatformModal").modal({backdrop:"static"}).modal('show');
        });
    });
	
	$("#editPlatformBtn").click(function(){
        var param = $("#editPlatformForm").serialize();
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
        });
    }); 
	*/
});

