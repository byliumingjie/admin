
$(function() { 
 
 
   $("#activityType").click(function() {
	   
	   $("#configRules").empty();
    	//var activityType = $("#CreateActivityForm input[name='activityType']").val();
    	var activityType = $("#activityType").val();
     
    	activityType = parseInt(activityType);
    	
    	var itemjs  = "";
    	 
    	var activityHtml = null;
    	var activityTotalHtml = null;
    	
    	var tokenStartHtml = "<table class='table  table-striped' id='activityconfigbyid'><tbody>";
    	var tokenEndHtml = "</tbody></table>";
    	// 消费/首冲数额
    	var number = "";
    	
    	// 道具奖励 --- 后期完善 <textarea rows="" cols=""></textarea>
    /*	var itemlistHtml = "<textarea  rows=1 cols=1  placeholder='奖励配置,格式:道具Id,数量&道具Id,数量'例:123,50&456,10" +
    	"class='form-control' style='width:25%'" +
    	"id='itemList' name='itemList[]'></textarea>";*/
    	var itemlistHtml = "";
    	// 领取次数
    	var frequency = "<input type='text' " +
    	"class='form-control' name='frequency[]' style='width:15%' placeholder='领取次数'>";
    	// 排行榜类型
    	var rankingTypeHtml = "<select name='type[]'>" +
    	"<option value=0>--请选择排行榜类型--</option>" +
    	"<option value=1>通关指定副本一定次数</option>" +
		"<option value=2>竞技场排名达到多少名</option>" +
		"<option value=3>指定小游戏参与一定次数</option>" +
		"<option value=4>石中剑抽取一定次数</option>" +
		"<option value=5>技能升级一定次数 </option>" +
		"<option value=6>拥有一定数量好友</option>" +
		"<option value=7>某个品质装备达到一定星数有一定数量 </option>" +
		"<option value=8>表情包拥有一定数量 </option>" +
		"<option value=9>扭蛋机抽取一定次数</option>" +
		"<option value=10>整蛊玩家一定次数</option></select>";	
		// 区间
    	var IntervalHtml = "<input type='text' " +
    	"class='form-control' name='IntervalStart[]' style='width:15%'>&nbsp至 &nbsp" +
    	"<input type='text' class='form-control' " +
    	"name='IntervalEnd[]' style='width:15%'>";
    	
    	// 描述
    	var bewriteHtml = "<input type='text'" +
    	"class='form-control' name='NodeBewrite[]' style='width:15%' placeholder='描述'>";
    	
    	// 小游戏类型
    	var gameTypeHtml = "<select name='type[]'>" +
		"<option value=0>--请选择--</option>" +
		"<option value=1>无下限</option>" +
		"<option value=2>跳楼侠</option>" +
		"<option value=3>大战三侠镇</option>" +
		"</select> "
		
    	// 任务类型 get ajax 返回获取类型    	
    	var taskTypeHtml ="<select name='type[]'>" +
		"<option value=0>--请选择--</option>" +
		"<option value=101>等级排行榜</option>" +
		"<option value=102>杀人排行榜</option>" +
		"<option value=103>富豪排行榜</option>" +
		"<option value=104>竞技场排行榜</option>" +
		"</select>"
    	var trStartHtml = "<tr>" +
		"<td><div class='control-group'>" +
		"<label class='control-label'>活动有效期时间/关闭时间</label>" +
		"<label class='checkbox-inline'><div class='controls'>";
    	
    	var trEndHtml = "</div></label></div></td><td></td></tr>";
    	switch(activityType)
    	{
    		case 1:
    			// 	首冲
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='首冲条件数额'>";
    			
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			   
    			$("#configRules").append(activityTotalHtml);
    		break;
    		case 2:
    			// 每日单笔充值类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='每日单笔充值类数额'>";
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 3:
    			// 每日累积充值类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='每日累积充值类数额'>";
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;";
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 4:
    			// 多日累积充值类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='多日累积充值类数额'>";
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 5:
    			// 每日累积消费类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='每日累积消费类数额'>";
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 6:
    			// 多日累积消费类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='多日累积消费类数额'>";
    			
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 7:
    			IntervalHtml = "<input type='text' " +
    	    	"class='form-control' name='IntervalStart[]' style='width:15%' placeholder='榜单开始区间'>&nbsp至 &nbsp" +
    	    	"<input type='text' class='form-control' " +
    	    	"name='IntervalEnd[]' style='width:15%' placeholder='榜单截止区间'>";
    			
    			// 冲榜类
    			activityHtml=taskTypeHtml+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+IntervalHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 8:
    			// 等级类
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='所到达等级'>";   			
    			 
    			activityHtml=number+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 9:
    			// 任务目标类
    			IntervalHtml = "<input type='text' " +
    	    	"class='form-control' name='IntervalStart[]' style='width:15%' placeholder='等级开始区间'>&nbsp至 &nbsp" +
    	    	"<input type='text' class='form-control' " +
    	    	"name='IntervalEnd[]' style='width:15%' placeholder='等级截止区间'>";    			
    			 
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='任务目标数额'>";
    			
    			activityHtml=rankingTypeHtml+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+number+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 10:
    			// 小游戏榜单 
    			IntervalHtml = "<input type='text' " +
    	    	"class='form-control' name='IntervalStart[]' style='width:15%' placeholder='小游戏榜单开始区间'>&nbsp至 &nbsp" +
    	    	"<input type='text' class='form-control' " +
    	    	"name='IntervalEnd[]' style='width:15%' placeholder='小游戏榜单截止区间'>";    			
    			 
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='任务目标数额'>";
    			
    			activityHtml=gameTypeHtml+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+IntervalHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    		case 11:
    			// 日累积整蛊类
    			IntervalHtml = "<input type='text' " +
    	    	"class='form-control' name='IntervalStart[]' style='width:15%' placeholder='小游戏榜单开始区间'>&nbsp至 &nbsp" +
    	    	"<input type='text' class='form-control' " +
    	    	"name='IntervalEnd[]' style='width:15%' placeholder='小游戏榜单截止区间'>";    			
    			 
    			number = "<input type='text' " +
    	    	"class='form-control' name='number[]' style='width:15%' placeholder='任务目标数额'>";
    			
    			activityHtml=gameTypeHtml+"&nbsp&nbsp"+bewriteHtml+"&nbsp&nbsp"+IntervalHtml+"&nbsp&nbsp"+
    			itemlistHtml+"&nbsp;&nbsp;"; 
    			activityTotalHtml = setConditionalRules(activityHtml,12,frequency);
    			//alert(activityTotalHtml);
    			$("#configRules").append(activityTotalHtml);
    			break;
    			
    		default:break;return false;
    	}    
    	$("#activityconfigbyid tr td ").each(function(){
    		var _this = $(this);
    		_this.find("#edititeminfo").click(function() 
    		{    			
    			var  itemTypeVal = $(this).attr("add-data-item-val");
    			 
            	$("#ItemListForm [name=itemOptionBtn]").val(itemTypeVal);
            	
            	$("#addloginNoticeModal").modal({backdrop:"static"}).modal('show');
            });
        });
    	 
    });
    
   $("#Globactivitybtn").click(function() 
   {
   	var form = $("#CreateActivityForm").serializeArray();
       $.ajax({
           type: 'POST',
           url: "SetActivity",
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
       _this.find(".delactivity").click(function() {  
    	   var id = $(this).attr('data-value');
    	   var serverId = $(this).attr('data-server-id');
    	   if (confirm("你确定要把活动编号为"+id+"删除么?")) {
    		  // var id = '{"id":'+id+'}';
    		   $.ajax({
                   type:'POST',
                   url:'delActivity',
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
    	   alert(id);
    	   var form = $("#activityEditForm").serializeArray();
           for(var i=0;i<form.length;i++){
               var name = form[i].name;
               
               if(name ==null ||name =="")
               {
                   continue;
               } 
               $("#activityEditForm [name=activityId]").val(id);
           }
           $("#activityEditForm").submit();            
         });
       // 发布
       _this.find(".ReleaseActivityBtn").click(function() {  
    	   var id = $(this).attr('data-value');
    	   var serverId = $(this).attr('data-server-id');
    	   if (confirm("你确定要把活动编号为"+id+"进行发布么?")) {
    		  // var id = '{"id":'+id+'}';
    		   $.ajax({
                   type:'POST',
                   url:'ReleaseActivity',
                   data:'id='+id,
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
           $("#serverinfoModal").modal({backdrop:"static"}).modal('show');
       });
    });
   
       // 批量活动发布
    $("#addactivityBtn").click(function(){      		
  		var form = $("#ReleaseAvtivityForm").serializeArray();
  		if (confirm("你确定要发布已选的活动么?")) { 
 		   $.ajax({
                type:'POST',
                url:'ReleaseActivity',
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
});});
  
function setConditionalRules(htmlinfo,amount,itemjs)
{
	var trStartHtml = "";
	var htmllist = "";
	var tokenStartHtml = "<table class='table  table-striped' id='activityconfigbyid'><tbody>";
	var tokenEndHtml = "</tbody></table>";
	var trEndHtml = "</div></label></div></td></tr>";
	var html = "";
	var termCheckHtml ="";
	var addItemHtml = "";
	
	
	for(var i=1;i<=amount;i++)
	{
		termCheckHtml = '<input type="checkbox" id="term'+i+'"value="term'+i+'" name="checkbox[]" style="margin:0px;padding:0px"/>&nbsp;';
		
		trStartHtml = "<tr id='activityBytr"+i+"'>" +
		"<td><div class='control-group' style='margin:0px;padding:0px'>" +
		"<label class='control-label'>条件"+i+"&nbsp;&nbsp;"+termCheckHtml+"</label>" +
		"<label class='checkbox-inline'><div class='controls'>";
		 
		addItemHtml = "<a class='btn' title='添加道具' " +
		"id='edititeminfo' add-data-item-val='itemList"+i+"'>添加道具</a>&nbsp;&nbsp;";
		
		var itemlistHtml = "<textarea  rows=1 cols=1  " +
		"placeholder='奖励配置,格式:道具Id,数量&道具Id,数量'例:123,50&456,10" +
    	"class='form-control' style='width:15%'" +
    	"id='itemList"+i+"' name='itemList[]'></textarea>&nbsp;&nbsp;";
			
		html=trStartHtml+htmlinfo+addItemHtml+itemlistHtml+itemjs+trEndHtml;
		
		htmllist+=html;
		
	}
	return tokenStartHtml+htmllist+tokenEndHtml;
}

function optionVerify(byid,name,type){

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
