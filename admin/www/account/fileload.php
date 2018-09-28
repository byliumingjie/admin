<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once '../include/lib/log.php';
include '../xxtea.lib.php';
//$postStr = file_get_contents("php://input");
$username = isset($_GET['userName'])?rawurldecode($_GET['userName']):0;
//$wechatName =0;

$ciphertext = new xxTea();
/*if(!empty($username))
{
	$wechatName = xxTea::decrypt($username);
}*/
?>
<!DOCTYPE html>
<html lang="en" class="no-js"> 
    <head> 
        <meta charset="utf-8">
        <title>有奖投稿</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/supersized.css">
        <link rel="stylesheet" href="css/style.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>

    <body>

        <div class="page-container">
            <h1>有奖投稿</h1>
            <form action="fileload.core.php" method="post" id="UserForm" enctype="multipart/form-data">
				<!---<div> 
					后期如果有多服务区可对于区服信息开启此字段关闭一下serverId表单字段
					<select name='serverId-' class='serverId-' autocomplete="off" style="display: none">
						<option value=''>--选择区服地址--</option>
						<option value=1>1区</option>
						<option value=2>2区</option>
					</select>
				</div>-->
				<div>
					<input type="hidden" name="serverId"  value=1/>
					<input type="hidden" name="username" class="username" placeholder="Username" readonly="readonly" autocomplete="off" value="<?php echo $username;?>"/>
				</div>
                <div>
                	<input type="file" name='file'/>
					<!-- <input type="text" name="PlayerId" class="password" placeholder="请输入角色编号"/> -->
                </div>
                <button id="submit-" type="submit" name='sut'>提交</button>
            </form>
            <div class="connect">
               
            </div>
        </div>
		<div class="alert" style="display:none">
			<h2>消息</h2>
			<div class="alert_con">
				<p id="ts"></p>
				<p style="line-height:70px"><a class="btn">确定</a></p>
			</div>
		</div>

        <!-- Javascript -->
		<script src="http://apps.bdimg.com/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
        <script src="js/supersized.3.2.7.min.js"></script>
        <script src="js/supersized-init.js"></script>
		<script>
		// 提交至服务端进行处理
		
		$(".btn").click(function(){
			is_hide();
		})
		var u = $("input[name=username]");
		var p = $("input[name=PlayerId]");
		$("#submit").live('click',function(){
			if(u.val() == '' || p.val() =='')
			{
				$("#ts").html("用户名或角色编号不能为空~");
				is_show();
				return false;
			}
			else{
				/*var reg = /^[0-9A-Za-z]+$/;
				if(!reg.exec(u.val()))
				{
					$("#ts").html("用户名错误");
					is_show();
					return false;
				}*/
			}
		});
		window.onload = function()
		{
			$(".connect p").eq(0).animate({"left":"0%"}, 600);
			$(".connect p").eq(1).animate({"left":"0%"}, 400);
		}
		function is_hide(){ $(".alert").animate({"top":"-40%"}, 300) }
		function is_show(){ $(".alert").show().animate({"top":"45%"}, 300) }
		$("#submit").click(function() 
		{
			 
				var param = $("#UserForm").serialize();
				 
				$.ajax({
					type: 'POST',
					url: 'fileload.core.php',
					data: param,
					dataType:'json',
					success: function(result) {
						
						alert(result.msg);
						
						if (result.errcode == 0) 
						{	
							/*$("#ts").html(result.msg);
							is_show();
							return true;*/
							window.location.reload();
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

		</script>
    </body>

</html>

