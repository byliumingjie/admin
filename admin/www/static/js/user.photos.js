$(function() {
 
	/** 
	 * by id menu menuRefuse  menuSuccess menuFailure
	 * 当拒绝按钮被点击则执行显示全选按钮以及审核按钮
	 * **/ 
    $(window).load(function() 
    {
    	$('#photosAllBtn').html("");
    	var tabaparent = null;
    	var i = 1;
    	var total = 0;
    	var sum = 0 ;
    	var menuType = null;
    	
    	//$("#menuUntreated").click();
    	
    	$(".tabaparent li").click(function(e){
    		 
    		 // 待审核未处理
    		if(e.target == $("#menuUntreated")[0])
    		{ 	
    			setCookie('tab2pages',0);
				setCookie('tab3pages',0);
				setCookie('tab4pages',0);
    			setCookie('menuType',"menuUntreated"); 
    			var userPsAllBtn = 
    			"<span class='icon'>" +
    			"<i><input style='margin: 0px' type='checkbox' id='allbtn' title='全选/反选'/></i>" +
    			"</span>" 
    			; 
    			$('#photosAllBtn').html(userPsAllBtn);
    			//$(".tabaparent li")
    			$("#menuLocktype").css('display','block');
    			 
    			// 全选 + 反选
    			$("#allbtn").click(function()
    			{ 
    				var btntype = $("#allbtn").is(":checked");
    				 
    				if(btntype==true)
    				{	  
    					$("[data-name=images]").css({"background-color":"#0af"});
    					$("#showIconListForm ul input[name='checkbox[]'").attr("checked","true"); 
    				}
    				else{
    					$("[data-name=images]").css({"background-color":""}); 
    					$("#showIconListForm ul input[name='checkbox[]'").removeAttr("checked"); 
    				}
    			});
    			
    			// 列表还是 图标
    			//alert('待审核');
    			
    			if(getCookie('tab1pages')>0)
    			{
    				total = parseInt(getCookie('tab1pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab1pages',sum);
    			
    			i++;
    			
    		} // 通过
    		else if(e.target == $("#menuAdopt")[0])
    		{
    			setCookie('tab1pages',0);
    			setCookie('tab3pages',0);
    			setCookie('tab4pages',0);
    			setCookie('menuType','menuAdopt');
    			$("#menuLocktype").css('display','none');
    			$('#photosAllBtn').html(""); 
    			$("[data-name=images]").css({"background-color":""}); 
				$("input[name='checkbox[]'").removeAttr("checked");
				
    			//alert('通过');
    			
    			setCookie('tab2pages',0);
    			
    			if(getCookie('tab2pages')>0)
    			{
    				total = parseInt(getCookie('tab2pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab2pages',sum);
    			
    			i++;
    			
    		} // 拒绝
    		else if(e.target == $("#menuRefuse")[0])
    		{ 
    			setCookie('tab1pages',0);
    			setCookie('tab2pages',0);
    			setCookie('tab4pages',0);
    			setCookie('menuType','menuRefuse');
    			$("#menuLocktype").css('display','none');
    			$('#photosAllBtn').html("");
    			$("[data-name=images]").css({"background-color":""}); 
				$("input[name='checkbox[]'").removeAttr("checked");
    			//alert('拒绝');
    			
    			setCookie('tab3pages',0);
    			
    			if(getCookie('tab3pages')>0)
    			{
    				total = parseInt(getCookie('tab3pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab3pages',sum);
    			
    			i++; 
    		}
    		else if(e.target == $("#menuUntreatedDetail")[0])
    		{
    			setCookie('tab1pages',0);
    			setCookie('tab2pages',0);
    			setCookie('tab3pages',0);
    			setCookie('menuType','menuUntreatedDetail');
    			$("#menuLocktype").css('display','none');
    			$('#photosAllBtn').html("");
    			$("[data-name=images]").css({"background-color":""}); 
				$("input[name='checkbox[]'").removeAttr("checked");
    			//alert('待审核详情'); 
    			setCookie('tab4pages',0);
    			
    			if(getCookie('tab4pages')>0)
    			{
    				total = parseInt(getCookie('tab4pages'));
    			}
    			sum = i + total;
    			 
    			setCookie('tab4pages',sum);
    			
    			i++;
    		}
    		 
    	}); 
    	
    	tabaparent = getCookie('menuType'); 
    	
    	if(tabaparent)
    	{
    		$("#"+tabaparent).click(); 
    	}  
   });
    
    // 待审核 图标 初始 page
    $("#menuUntreated").click(function()
    {
    	var pageTota = getCookie('tab1pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "index?p="+1; 
    	}
    });
    // 通过 图标 初始 page
    $("#menuAdopt").click(function()
    {
    	var pageTota = getCookie('tab2pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "index?p="+1; 
    	}
    });
 	// 拒绝 图标 初始 page
    $("#menuRefuse").click(function()
    {
    	var pageTota = getCookie('tab3pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "index?p="+1; 
    	}
    });
    // 待审核列表 初始 page
    $("#menuUntreatedDetail").click(function()
    {
    	var pageTota = getCookie('tab4pages');
    	//alert(pageTotal2);
    	if(pageTota==0)
    	{
    		location.href = "index?p="+1; 
    	}
    });
    
    /** 
	 * 列表详情显示还是图标展示						 
	 * **/
	// 列表
	$("#showDetailList").click(function()
	{
		$("#showIconListForm").css('display','none');
		$("#showDetailListForm").css('display','block');
	});
	// 图标
	$("#showIconList").click(function()
	{
		$("#showDetailListForm").css('display','none');
		$("#showIconListForm").css('display','block');
	});
	
    $("#allbtn2").click(function()
	 {  
				var btntype2 = $("#allbtn2").is(":checked");
				
				//alert(btntype2);
				
				if(btntype2==true)
				{	  
					//$("[data-name=images]").css({"background-color":"#0af"});
					$("#showDetailListForm table tr input[name='checkbox[]'").attr("checked","true"); 
				}
				else{
					//$("[data-name=images]").css({"background-color":""}); 
					$("#showDetailListForm table tr input[name='checkbox[]'").removeAttr("checked"); 
				}
	 });
	// 单选
	$("#tab1 ul li ").each(function() {
		var _this = $(this);
		var imgCss =null;
		 
		_this.find(".editUserPhotos").click(function() 
		{ 
			 var id = $(this).parent().parent().find('[data-name=id]').text();					 
			 var type = $(this).parent().parent().find("[data-name=checkbox").is(":checked");					  
			 if(type==false)
			 { 
				$(this).parent().parent().find("a").css({"background-color":"#0af"});
				$(this).parent().parent().find("[data-name=checkbox]").attr("checked", true);   
			 }
			 else
			 {   
				$(this).parent().parent().find("a").css({"background-color":""});					 
				$(this).parent().parent().find("[data-name=checkbox]").attr("checked", false); 
			 }  
		});
	});
	// 图标通过
	$("#adoptBtn").click(function(){
		var data = $("#showIconListForm").serializeArray();
	    var param = $("#showIconListForm").serialize();

		$checkdataOut = checkbox_data_verify(data);		

		if($checkdataOut==false)
		{
			alert('请选择需要审核的头像!');
			return false;
		}
		
	    if (confirm("确定要审核通过以上头像么？")) {
	        $.ajax({
	            type: 'POST',
	            url: '/UserPhotosVerif/PhotosAdopt',
	            data: param,
	            dataType: 'json',
	            success: function(result) {        	
	                alert(result.msg);
	                if (result.errcode == 0) {
	                	location.reload();
	                    //window.location.href = window.location.href; 
	                }
	            }
	        }); 
	    }
	});
	// 详情通过
	
	$("#adoptDetaiBtn").click(function(){
	    var param = $("#showDetailListForm").serialize();
	    var data = $("#showDetailListForm").serializeArray();
		$checkdataOut = checkbox_data_verify(data);		

		if($checkdataOut==false)
		{
			alert('请选择需要审核的头像!');
			return false;
		}
	    if (confirm("确定要审核通过以上头像么？")) {
	        $.ajax({
	            type: 'POST',
	            url: '/UserPhotosVerif/PhotosAdopt',
	            data: param,
	            dataType: 'json',
	            success: function(result) {        	
	                alert(result.msg);
	                if (result.errcode == 0) {
	                	location.reload();
	                    //window.location.href = window.location.href; 
	                }
	            }
	        }); 
	    }
	});
	// 图标拒绝
	$("#refuseBtn").click(function(){ 
		var param = $("#showIconListForm").serialize();
		var data = $("#showIconListForm").serializeArray();
		$checkdataOut = checkbox_data_verify(data);		

		if($checkdataOut==false)
		{
			alert('请选择需要审核的头像!');
			return false;
		}
		if (confirm("确定要审核拒绝以上头像么？")) {
		 $.ajax({
	            type: 'POST',
	            url: '/UserPhotosVerif/Photosrefuse',
	            data: param,
	            dataType: 'json',
	            success: function(result) {        	
	                alert(result.msg);
	                if (result.errcode == 0) {
	                	location.reload();
	                    //window.location.href = window.location.href; 
	                }
	            }
	        }); 
		}
	});
	// 详情列表拒绝
	$("#refuseDetaiBtn").click(function(){ 
		var param = $("#showDetailListForm").serialize();
		var data = $("#showDetailListForm").serializeArray();
		$checkdataOut = checkbox_data_verify(data);		

		if($checkdataOut==false)
		{
			alert('请选择需要审核的头像!');
			return false;
		}
		if (confirm("确定要审核拒绝以上头像么？")) {
		 $.ajax({
	            type: 'POST',
	            url: '/UserPhotosVerif/Photosrefuse',
	            data: param,
	            dataType: 'json',
	            success: function(result) {        	
	                alert(result.msg);
	                if (result.errcode == 0) {
	                	location.reload();
	                    //window.location.href = window.location.href; 
	                }
	            }
	        }); 
		}
	});
	//~:	
});
function checkbox_data_verify(data)
{
	var checkboxData = null;
	for(var i=0,l=data.length;i<l;i++)
	{
		for(var key in data[i])
		{  
			if(data[i][key]=='checkbox[]')
			{
				checkboxData = data[i][key];
				return true;
				break;
			} 
		}
	 }
	if(checkboxData==null)
	{
		//alert('请选择需要审核的头像!');
		return false;
	} 
}
