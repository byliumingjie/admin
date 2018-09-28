/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *  <script>

$(".peity_bar_good").click(function(){

	//alert('123');
});
function update() {
    $.getJSON("number.php?jsonp=?", function(data) {
		magic_number(data.n);
    });
};

setInterval(update, 3000);
update();
</script>
 */
// DEV 组件
$(function(){  
	$("#remain5").click(function(){
	 
		   var value = $("#remain5").val();		   
		   if(value=='NickName'){			 
			   $("#like").css('display','block');
			   $(".like").html("<B>模糊</B> <input name='like' " +
			   "title='模糊' id='like' type='checkbox' style='margin:0px;padding:0px' value='1'/>&nbsp&nbsp");
		   }else{
			   //$("#like").css('display','none');
			   $(".like").html('');
		   }
	});  
	
	  $("#tableExcel tr").each(function() {
		  var _this = $(this);
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
});


function statAccountVerify(obj){
    var platId = $(obj).find('select[name=platId]').val();//   
    if(platId === '' || platId === null || platId ==0){
        	alert("请输入平台！");
        	location.reload( true );
        	return false;
    } 
	var sid = $(obj).find('select[name=sid]').val();//   
    if(sid === '' || sid === null){
        	alert("请输入区服ID！");
        	location.reload( true );
        	return false;
    } 
    var userid = $(obj).find('input[name=userid]').val();//
    var name = $(obj).find('input[name=name]').val();//
    if((userid === '' || userid === null) && (name==='' || name===null)){
        	alert("角色ID或昵称至少填写一种,请重试!");
        	location.reload( true );
        	return false;
    } 
}
// log
function statLogVerify(obj){
	 
	var channelId = $(obj).find('select[name=channelId]').val();//   
    if(channelId === '' || channelId === null || channelId == 0){
        	alert("请选择渠道！");
        	location.reload( true );
        	return false;
    } //endtime account
   /* var account = $(obj).find('input[name=account]').val();//   
    if(account === '' || account === null){
        	alert("请输入操作账号");
        	location.reload( true );
        	return false;
    } */
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endtime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }  
}
//在线人数
$(".btnn").click(function(){		   
		update(); 
});
function update() 
{
	 $.getJSON("number.php?jsonp=?", function(data) {
			magic_number(data.n);
	 });
	 
	/*var param = $("#onlineRoleForm").serializeArray(); 
	$.ajax({
        type:'POST',
        url:'/lottery/createActivity',
        data:param,
        dataType:'json',
        success:function(result){
            alert(result.msg);
            if(result.errcode == 0){
            	//获取data 
            	magic_number();
                window.location.href = window.location.href;
            }
        }
    });*/ 
};
function magic_number(value) {
	var num = $("#number");
    num.animate({count: value}, {
        duration: 50,
        step: function() {
            num.text(String(parseInt(this.count)));
		}
	});
    setInterval(update,3000);
};

//
function StatOnlineRoleVerify(obj){
	 
	 //endtime account
    var server = $(obj).find('input[name=server]').val();//   
    if(server === '' || server === null || server ==0){
        	alert("区服不能为空!");
        	location.reload( true );
        	return false;
    } 
    var sequence = $(obj).find('select[name=sequence]').val();//   
    if(sequence === '' || sequence === null || sequence == 0){
        	alert("请选择排序类型！");
        	location.reload( true );
        	return false;
    }
}
//综合日常数据汇总
function statComplexVerify(obj){
	
	var platfrominfo = '';
	 
	var arr = [];
	 	
	$('#container li .itemsChoice').each(function()
	{    			 
		arr.push( $(this).val());
	})	
	for(var i=0;i<arr.length;i++)
	{ 
		platfrominfo+=platfrominfo!=''?','+arr[i]:arr[i];
	}
	$("#platfrominfo").attr("value",platfrominfo);  
 	
	var server = $(obj).find('input[name=platfrominfo]').val();
	
	if(server === '' || server === null)
	{
      	alert("请输入区服ID！");
      	location.reload( true );
      	return false;
	} 
	var startTime = $(obj).find('input[name=startTime]').val();  
	if(startTime === '' || startTime === null)
	{
	    alert("请输入开始时间");
	    location.reload( true );
	    return false;
	} 
	var endtime = $(obj).find('input[name=endtime]').val();  
	if(endtime === '' || endtime === null){
	    alert("请输入结束时间");
	    location.reload( true );
	    return false;
	 }  
} 
//系统缓存
function statCacheVerify(obj){
  
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endtime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    } 
    var account = $(obj).find('input[name=account]').val();//   
    if(account === '' || account === null){
        	alert("请输入操作账号");
        	location.reload( true );
        	return false;
    } 
	
}
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// ________________________________________________________
// 水果机日常
function statfruitDailyVerify(obj){
	  
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endTime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }  
}
// 等级分布
function statLevelInfoVerify(obj){
	  
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endTime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }  
    var platType = $(obj).find('select[name=platId]').val(); 
	 
    if(platType === '' || platType === null || platType==0){
        	alert("请选择平台类型！");
        	location.reload( true );
        	return false;
	} 	
}

function HighestOnlineVerify(obj){
	var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endTime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }  
    var platType = $(obj).find('select[name=platId]').val(); 
	 
    if(platType === '' || platType === null || platType==0){
        	alert("请选择平台类型！");
        	location.reload( true );
        	return false;
	}  
}
function onlineTimeLengthVerify(obj)
{
	var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endTime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    }  
    var platType = $(obj).find('select[name=platId]').val(); 
	 
    if(platType === '' || platType === null || platType==0){
        	alert("请选择平台类型！");
        	location.reload( true );
        	return false;
	} 
    var server = $(obj).find('input[name=serverId]').val();
	
	if(server === '' || server === null)
	{
      	alert("请输入区服ID！");
      	location.reload( true );
      	return false;
	} 
}
function StatMailAnnalVerify(obj)
{
	 
	var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        	alert("请输入开始时间");
        	location.reload( true );
        	return false;
    } 
    var endtime = $(obj).find('input[name=endTime]').val();//   
    if(endtime === '' || endtime === null){
        	alert("请输入结束时间");
        	location.reload( true );
        	return false;
    } 
    var server = $(obj).find('input[name=serverId]').val();
	
	if(server === '' || server === null)
	{
      	alert("请输入区服ID！");
      	location.reload( true );
      	return false;
	} 
	
	var RoleIndex = $(obj).find('input[name=RoleIndex]').val();
	var nikeName  = $(obj).find('input[name=nikeName]').val();
	if((RoleIndex === '' ||  RoleIndex === null) && (nikeName === '' ||  nikeName === null))
	{
      	alert("请输入角色编号或用户昵称！");
      	location.reload( true );
      	return false;
	} 
}
