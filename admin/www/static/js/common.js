/*
 *  
 */

$(function() {

    $("#loginForm").submit(function() {
        var param = $("#loginForm").serialize();
        $.ajax({
            type: 'post',
            url: '/user/userLogin',
            data: param,
            dataType: 'json',
            success: function(result) {
				//alert('localhsot');
            	if(result.errcode == 1) {
            		//alert(result.msg)
            		//var title = result.msg;
            		if (confirm("该账号已经在其他地点登录,确认是否顶替!")) {
            			//alert(123);
            			editAccountStatus();
            			//location.href = 'user/login';
            		}
            		 
            	}
                if (result.errcode == 0) {
					//alert('localhsot');
                	//Session["compare"] = 23123;
                	
                    location.href = '/';
                } else if(result.errcode < 0) {
                    alert(result.msg);
                    return false;
                }
            }
        });
        return false;
    });
    
    // 模板 del
    $("#closemode").click(function() {
    	//alert('123');
    	$("#showMenuModal").css('display','none');
        $("#showScreenMode").css('display','none');
    });
    //注册
    $("#regButton").click(function() {
        var param = $("#regForm").serialize();
        $.ajax({
            type: 'post',
            url: '/user/userRegister',
            data: param,
            dataType: 'json',
            success: function(result) {
                if (result.errcode == 0) {
                    alert(result.msg);
                    location.href = '/';
                } else {
                    alert(result.msg);
                }
            }
        });
        return false;
    });

    //时间插件
    if ($(".datetimepicker").length > 0) {
        $(".datetimepicker").datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            minuteStep: 1,
            todayBtn: true,
            language: 'zh_cn',
            autoclose: true
        });
    }

    //菜单点击
    /*
     $("#nav-left li").each(function() {
     var _this = $(this);
     _this.click(function() {
     $("#nav-left li").removeClass('active');
     _this.addClass('active');
     var postion = _this.attr('data-postion');
     //种cookie
     setCookie('postion',postion);
     window.location.href = window.location.href;
     });
     });
     */
   
    // 平台切换刷新页面数据更新cookie
    $("#zoneiddtt li a").click(function()
    { 
    	var id = $(this).attr("linkid");
    	var Name = $(this).text();     
    	id= parseInt(id); 
    	
    	document.cookie="gzoneid="+id;
    	var pagehtml = Name;
    	$('#signalNavigation').html(pagehtml);
        history.go(0);
    	//$('#_ZONEID').val(id);
    }); 
});

//写cookies
function editAccountStatus(){
	//alert('editAccountStatus');
	var param = null;
	$.ajax({
        type: 'post',
        url: '/user/edituserstatus',
        data: param,
        dataType: 'json',
        success: function(result) {
            if (result.errcode == 0) {
                alert(result.msg);
                location.href = '/';
            } else {
                alert(result.msg);
                return false;
                //location.href = '/';
            }
        }
    });
	
}
function setCookie(name, value)
{
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
    ;

    /*
     var strsec = getsec(time);
     var exp = new Date();
     exp.setTime(exp.getTime() + strsec * 1);
     document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
     */
}

//读取cookies
function getCookie(name)
{
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

    if (arr = document.cookie.match(reg))
        return (arr[2]);
    else
        return null;
}

//删除cookies
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = getCookie(name);
    if (cval != null)
        document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}


function checkServerForm(obj) {
        var server = $(obj).find('select[name=server]').val();
        
        if(server == 0||server === '' || server === 'undefined' || server === null  ){
            alert("请选择区服！");
            return false;
        }
        var select = $(obj).find('select[name=select]').val();
        if(select === '' || select === null){
            alert("请选择查询条件！");
            return false;
        }
        
        var value = $(obj).find('input[name=value]').val();
        if(value === '' || value === null){
            alert("请添加查询值！");
            return false;
        }
        //$(obj).attr('action',location.href).submit();
        //return false;
    }

function checkServerForm(obj) {
        var server = $(obj).find('select[name=server]').val();
        
        if(server == 0||server === '' || server === 'undefined' || server === null  ){
            alert("请选择区服！");
            return false;
        }
        var select = $(obj).find('select[name=select]').val();
        if(select === '' || select === null){
            alert("请选择查询条件！");
            return false;
        }
        
        var value = $(obj).find('input[name=value]').val();
        if(value === '' || value === null){
            alert("请添加查询值！");
            return false;
        }
        //$(obj).attr('action',location.href).submit();
        //return false;
    }
//账号挂载
function AccountFormVerify(obj) { 
 
	var type = $(obj).find('select[name=type]').val();//
	
	if(type == 0 || type === null){
        alert("请选择设置条件类型！");

        return false;
    } 

    var accountname = $(obj).find('input[name=accountname]').val();//   
    if(accountname === '' || accountname === null){
        alert("请输入账号！");
        return false;
    }
    
    var accounttype = $(obj).find('select[name=accounttype]').val();//
    if(accounttype === '' || accounttype === null){
        alert("请输入账号类型！");
        return false;
    } 
    var type = $(obj).find('select[name=type]').val();// 
    var replaceuin  = $(obj).find('input[name=replaceuin]').val();
    if(type === "1"){
    	if(replaceuin ==='' || replaceuin ===null ){
  		  alert('替换账号UIN不能为空');
  		  return false;
  	 }
    	 
    }
    var replacetype = $(obj).find('select[name=replacetype]').val();    
    if(type === "2"){ 
    	 if(replacetype ==='' || replacetype ===  null ){
    		  alert('替换账号类型不能为空');
    		  return false;
    	 }
    } 
}
//日常统计
 function StatdataPutVerify(obj)
 {   
	var servertype = $(obj).find('select[name=type]').val();//	 
	if( servertype == 0 || servertype === null ){
		    alert("请选择平台类型！");
			location.reload( true );
		    return false;
	}
	var type = $(obj).find('select[name=servertype]').val();//	 
	if( type == 0 || type === null ){
	        alert("请选择查询区间！");
			location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
	if( regionid === '' || regionid === null ){
	        alert("请输入区服！");
			location.reload( true );
	        return false;
	}	    
	if( type == 2 )
	{	
		var startTime = $(obj).find('input[name=startTime]').val();//   
	    if( startTime === '' || startTime === null ){
	        alert("请输入开始时间！");
			location.reload( true );
	        return false;
	    }
	    var endtime = $(obj).find('input[name=endtime]').val();// 
	    if( endtime === '' || endtime === null )
	    {
	        alert("请输入截止时间！");
			location.reload( true );
	        return false;
	    }		
	}  
}
 //充值比例
function statdataVerify(obj)
{   
	var type = $(obj).find('select[id=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	}
	/*var regionid = $(obj).find('textarea[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
        	return false;
    }*/
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        alert("请输入开始时间！");
		location.reload( true );
        return false;
    }
    var endtime = $(obj).find('input[name=endtime]').val();// 
    if(endtime === '' || endtime === null)
    {
        alert("请输入截止时间！");
		location.reload( true );
        return false;
    }	
    
}
// 等级滞留
function statGradeRemainVerify(obj)
{
	var type = $(obj).find('select[id=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
			location.reload( true );
        	return false;
    }
    var remainnterval = $(obj).find('input[name=remainnterval]').val();//   
    if(remainnterval === '' || remainnterval === null){
        alert("请输入滞留条件！");
		location.reload( true );
        return false;
    } 
   /* var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        alert("请输入开始时间！");
        return false;
    }
    var endtime = $(obj).find('input[name=endtime]').val();// 
    if(endtime === '' || endtime === null)
    {
        alert("请输入截止时间！");
        return false;
    }
    var statInterval = $(obj).find('input[name=statInterval]').val();//   
    if(statInterval === '' || statInterval === null){
        alert("请输入统计时间！");
        return false;
    }*/
	
}
 
//货币滞留
function statMoneryRemainVerify(obj)
{
	var type = $(obj).find('select[id=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
			location.reload( true );
        	return false;
    }
    var remainnterval = $(obj).find('input[name=remainnterval]').val();//   
    if(remainnterval === '' || remainnterval === null){
        alert("请输入滞留条件！");
		location.reload( true );
        return false;
    }  
}
// 登录流水
function statloginInfoVerify(obj)
{
	var type = $(obj).find('select[id=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
			location.reload( true );
        	return false;
    }
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        alert("请输入开始时间！");
		location.reload( true );
        return false;
    }
    var endtime = $(obj).find('input[name=endtime]').val();// 
    if(endtime === '' || endtime === null)
    {
        alert("请输入截止时间！");
		location.reload( true );
        return false;
    }
}
//货币流水
function statmoneyInfoVerify(obj){
	
	var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
	        location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
        	location.reload( true );
        	return false;
    } 
	var remainType = $(obj).find('select[name=remainType]').val();//	 
	if(remainType == 0 || remainType === null)
	{
	        alert("请选择消耗类型！");
	        location.reload( true );
	        return false;
	}
	
}
//货币消耗
function statmoneyDataVerify(obj)
{
	var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	}
	var regionid = $(obj).find('input[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
			location.reload( true );
        	return false;
    } 
  
	var remainType = $(obj).find('select[name=remainType]').val();//	 
	if(remainType == 0 || remainType === null)
	{
	        alert("请选择消耗类型！");
			location.reload( true );
	        return false;
	} 	
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        alert("请输入开始时间！");
		location.reload( true );
        return false;
    }
    var endtime = $(obj).find('input[name=endTime]').val();// 
    if(endtime === '' || endtime === null)
    {
        alert("请输入截止时间！");
		location.reload( true );
        return false;
    }
    var exctype = $(obj).find('select[name=exctype]').val();//	 
	if(exctype == 0 || exctype === null){
	        alert("请选择流水类型！");
			location.reload( true );
	        return false;
	} 
	
}
//留存
function statretainedDataVerify(obj){
	
	var type = $(obj).find('select[id=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
			location.reload( true );
	        return false;
	} 
	var space = $(obj).find('input[name=space]').val();//   
    if(space === '' || space === null){
        	alert("请输入留存区间！");
			location.reload( true );
        	return false;
    }   	
    var startTime = $(obj).find('input[name=startTime]').val();//   
    if(startTime === '' || startTime === null){
        alert("请输入开始时间！");
		location.reload( true );
        return false;
    }
    var endtime = $(obj).find('input[name=endtime]').val();// 
    if(endtime === '' || endtime === null)
    {
        alert("请输入截止时间！");
		location.reload( true );
        return false;
    } 
}
// 邮箱记录
function statMailRecordingatVerify(obj){
	
	var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
	        return false;
	}
	var regionid = $(obj).find('textarea[name=regionid]').val();//   
    if(regionid === '' || regionid === null){
        	alert("请输入区服编号！");
        	return false;
    } 
}
// 
function statAllserverRetainedVerify(obj)
{
	var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
	        location.reload( true );
	        return false;
	}
	var datetime = $(obj).find('input[name=datetime]').val();//   
    if(datetime === '' || datetime === null){
        	alert("请输入时间点！");
        	location.reload( true );
        	return false;
    } 
    var space = $(obj).find('input[name=space]').val();//   
    if(space === '' || space === null){
        	alert("请输入留存天");
        	location.reload( true );
        	return false;
    } 
}
// LTV值
function statltvVerify(obj){
	
	var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
	        location.reload( true );
	        return false;
	}
	 var regionid = $(obj).find('textarea[name=regionid]').val();//   
	    if(regionid === '' || regionid === null){
	        	alert("请输入区服编号！");
	        	location.reload( true );
	        	return false;
	    } 
	var datetime = $(obj).find('input[name=datetime]').val();//   
    if(datetime === '' || datetime === null){
        	alert("请输入时间点！");
        	location.reload( true );
        	return false;
    } 
    var space = $(obj).find('input[name=space]').val();//   
    if(space === '' || space === null){
        	alert("请输入留存天");
        	location.reload( true );
        	return false;
    }  
}
	
function cdkPutVerify(obj){
	 
	if(obj){
		var j= 0;
		
		var times = 0;
		var chckedstat='';
		var name='';
		var cdkname = $(obj).find('input[name=cdkname]').val();
		 
	 
		var verifynum= /^[0-9]*$/;  	
		
		if(cdkname === '' || cdkname===null){
			alert("请输入礼包名称!");
        	location.reload( true );
        	return false;
		}
		for(var i=0;i<=5;i++)
		{ 
			name = 'checkbox'+i; 
			chckedstat = $(obj).find('input[name='+name+']:checked').val();	
			var chcknet  = $(obj).find('input[name='+name+']').next();
			var  objtitle = chcknet.attr("title");
			 
			
			if(chcknet.val()){
				j++;
				if(j>4){
					alert('附件最多不能超过4个');
					break;
					return false;
				} 
				if(verifynum.test(chcknet.val())!=true){
					objtitle+="类型不是有效的整形数字";
					 //$('#titl').html('<h6>类型不是有效的整形数字</h6>'); 
					alert(objtitle);
				} 
			} 
			if(chcknet.val()==='' || chcknet.val() ===null){				
				times++;
			}
			
			/*if(chckedstat >0)
			{  	 
				
				j++; 				
				if(j>4){
					alert('附件最多不能超过4个');
					break;
					return false;
				} 
				if(chcknet.val()==''){
					
					objtitle+="不能为空！";
					alert(objtitle);
				}
				 
			} */
			
		}  
		if(times>=6){
			 
			alert('道具不能为空至少要选填一种'); 
			return false;
		}
	 
	}
	 //return false;
}
function statloginVerify(obj){
	/*var type = $(obj).find('select[name=type]').val();//	 
	if(type == 0 || type === null){
	        alert("请选择服务器类型！");
	        location.reload( true );
	        return false;
	}
	 var regionid = $(obj).find('textarea[name=regionid]').val();//   
	    if(regionid === '' || regionid === null){
	        	alert("请输入区服编号！");
	        	location.reload( true );
	        	return false;
	    } 
	var datetime = $(obj).find('input[name=datetime]').val();//   
    if(datetime === '' || datetime === null){
        	alert("请输入时间点！");
        	location.reload( true );
        	return false;
    } 
    var space = $(obj).find('input[name=space]').val();//   
    if(space === '' || space === null){
        	alert("请输入留存天");
        	location.reload( true );
        	return false;
    } */
}

function noticeVerify(obj)
{
	 var sid = $(obj).find('input[name=server]').val();	 
	 if(sid == 0 || sid === null)
	 {
	        alert("区服为空！");
	        location.reload( true );
	        return false;
	 }
	 var sendType = $(obj).find('select[name=sendType]').val(); 
	 
	 if(sendType === '' || sendType === null || sendType==0){
	        	alert("请选中发布类型！");
	        	location.reload( true );
	        	return false;
	} 	 
	//alert(sendType);
	 
	if( sendType ==1 )
	{
		var userid = $(obj).find('input[name=userid]').val();  
	    if(userid === '' || userid === null){
	        	alert("角色Id为空！");
	        	location.reload( true );
	        	return false;
	    }  
    }
}
function statChartDailyVerify(obj){
	 var serverId = $(obj).find('input[name=serverId]').val();	 
	 if(serverId == 0 || serverId === null)
	 {
	        alert("区服为空！");
	        location.reload( true );
	        return false;
	 }
	
}
 
