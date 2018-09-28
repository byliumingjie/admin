$(function() {
	 
     setInterval(function() {
         var now = (new Date()).toLocaleString(); 
         var datetime = getTime();     
         $('#time').text(datetime);
          
     }, 1000);
	  // 定时对基本信息异步更新
     setInterval(function() {          
         UpReamlData();
     }, 1000*60*1);
});
function getTime(){  
    var t=new Date();  
    with(t){  
        var _time=(getHours()<10 ? "0" :"") + 
        getHours() + ":" + (getMinutes()<10 ? "0" :"") 
        + getMinutes() + ":" + (getSeconds()<10 ? "0" :"") 
        + getSeconds() + " " ;  
    }  
    return _time;
}

function UpReamlData(){
	
	// 获取数据
	var serverId = $("#getserverId").text();	
	var PlatId = $("#getPlatId").text();
	var startTime = $("#getstartTime").text();
	/*
 		'loginRole'=> $CurrentLoginOut['RoleNum'], // dau
		'loginFrequency'=> $CurrentLoginOut['loginNum'], //登录次数
		'AvglogingTime'=> $CurrentLoginOut['AvglogingTime'], // 平均登录时长
		'payfee'=> $CurrentPayOut['fee'], // 累计收入
		'payfrequency'=> $CurrentPayOut['frequency'], // 付费次数
		'Payrolenum'=> $CurrentPayOut['rolenum'], // 付费玩家
		'PayTotal'=>(int)$TotalPay['Totalfee'],	// 累计付费
		'onlineRole'=> $CurrentRegOut['cont'],//新增人数
		'onlinedays'=> $CurrentRegOut['onlinedays'],// 老玩家	
	 * */
	 $.ajax({
         type:'POST',
         url:'/Statdata/Stat_real_time_list',
         data:{platId:PlatId,serverId:serverId,startTime:startTime},
         dataType:'json',
         success:function(result){
        	// alert(serverId);
             //alert(result.msg);
             
             if(result.errcode == 0)
             {
            	 if(result.onlineRole)
            	 {            		 
            		 $("#newregistered").text(result.onlineRole);
            	 }
            	 // dau
            	 if(result.loginRole)
            	 {            		 
            		 $("#dau").text(result.loginRole);
            	 }
            	 else{
            		 $("#dau").text(0); 
            	 }
            	 // 老玩家
            	 if(result.onlinedays)
            	 {            		 
            		 $("#onlinedaysId").text(result.onlinedays);
            	 }
            	 // 付费玩家
            	 if(result.Payrolenum)
            	 {            		 
            		 $("#payUserNumId").text(result.Payrolenum);
            	 }else{
            		 $("#payUserNumId").text(0);
            	 }
            	 // 付费次数 id="payfrequencyId"><?
            	 if(result.payfrequency)
            	 {            		 
            		 $("#payfrequencyId").text(result.payfrequency);
            	 }
            	 else{
            		 $("#payfrequencyId").text(0);
            	 }
            	 // 累计收入
            	 if(result.PayTotal)
            	 {            		 
            		 $("#PayTotal").text(result.PayTotal);
            	 }
            	 else{
            		 $("#PayTotal").text(0);
            	 }
            	 // 游戏次数 id="loginFrequencyId"><?
            	 if(result.loginFrequency)
            	 {            		 
            		 $("#loginFrequencyId").text(result.loginFrequency);
            	 }
            	 else{
            		 $("#loginFrequencyId").text(0);
            	 }
            	 // 平均游戏时长id="avglogingTimeId"><?
            	 if(result.AvglogingTime)
            	 {            		 
            		 $("#avglogingTimeId").text(result.AvglogingTime);
            	 }
            	 // 今日收入	
            	 if(result.payfee)
            	 {            		 
            		 $("#payfeeId").text(result.payfee);
            	 }
            	 
                 //window.location.href = window.location.href;
             }
         }
     });
}