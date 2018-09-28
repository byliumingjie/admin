$(function(){ 
	
	//改变服务器状态
	$("#serverTable tr").each(function() {
        var _this = $(this);
        _this.find(".severState").click(function() {
            var form = $("#serverStateForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                if(name ==null ||name =="")
                {
                    continue;
                }
                 var value = $(this).parent().parent().find('[data-name='+name+']').text();
                  $("#serverStateForm [name="+name+"]").val(value);
                
            }
            $("#serverstateModal [name=id]").val(_this.attr("id"));
            $("#serverstateModal").modal({backdrop:"static"}).modal('show');
        });
    });
	
	//请求状态信息
	$("#tableExcel tr").each(function() {
        var _this = $(this);
        _this.find(".serverInfo").click(function() {
            var form = $("#logInfoForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                
                if(name ==null ||name =="")
                {
                    continue;
                }
                 
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#logInfoForm [name="+name+"]").val(value);
        
            }
            //$("#serverinfoModal input[name=id]").val(_this.attr("id"));
            $("#loginfoModal").modal({backdrop:"static"}).modal('show');
        });
        // 
        _this.find("#originalSwap").click(function() {
            var form = $("#logInfoForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                
                if(name ==null ||name =="")
                {
                    continue;
                }
                 
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#logInfoForm [name="+name+"]").val(value);
        
            }
            //$("#serverinfoModal input[name=id]").val(_this.attr("id"));
            $("#loginfoModal").modal({backdrop:"static"}).modal('show');
        });
        //响应失败查看结果
        _this.find(".ponsefailureInfo").click(function() {
            var form = $("#logInfoForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                
                if(name ==null ||name =="")
                {
                    continue;
                }
                 
                var value = $(this).parent().parent().find('[data-name=ResponseData]').text();
                
                $("#logInfoForm [name=RequestData]").val(value);
        
            } 
            $("#loginfoModal").modal({backdrop:"static"}).modal('show');
        });  
        //Ip Map
        _this.find(".Ippoint").click(function() {
            var form = $("#logInfoForm").serializeArray();
            var value = '';
            var marker ='';
            var point='';
            var map ='';
            var gc ='';
            map = new BMap.Map("map_canvas");//初始化地图                    
	        //var optsd = {type: BMAP_NAVIGATION_CONTROL_SMALL}   
	        map.addControl(new BMap.NavigationControl());  
	        //初始化地图控件              
	        map.addControl(new BMap.ScaleControl());                   
	        map.addControl(new BMap.OverviewMapControl());  
	        map.addControl(new BMap.MapTypeControl()); 	
	        
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                
                if(name ==null ||name =="")
                {
                    continue;
                }                 
                value = $(this).parent().parent().find('[data-name=pointxy]').text();
		       
		        var pintX = '';
		        var pintY = '';
		        var splitArray = new Array();
		       // var string="太平洋、大西洋、印度洋、北冰洋";
		        var regex = /,/;
		        splitArray=value.split(regex); 
		        pintX = splitArray[0];
		        pintY = splitArray[1]; 
            } 
            point=new BMap.Point(pintX,pintY);
	        //初始化地图中心点
	        map.centerAndZoom(point, 15);
	        //初始化地图标记
	        marker = new BMap.Marker(point); 
	         
	        marker.enableDragging(); //标记开启拖拽
	        gc = new BMap.Geocoder();//地址解析类
	         
	        
	        marker.addEventListener("dragend", function(e){ 
	            //获取地址信息
	            gc.getLocation(e.point, function(rs){
	            	 
	            	 //var addComp = rs.addressComponents;
	            	 //alert(addComp.city); 
	                showLocationInfo(e.point, rs);
	            });
	        });
	      //添加标记点击监听
	        marker.addEventListener("click", function(e){
	           gc.getLocation(e.point, function(rs){ 
	        	    
	                showLocationInfo(e.point, rs);
	            });
	        });
	        map.centerAndZoom(point, 15); //设置中心点坐标和地图级别
	        map.addOverlay(marker); //将标记添加到地图中
          //$("#logInfoForm [name=RequestData]").val(value);
	        var addComp ='';
	        var addr ='';
	        var opts =''
	        function showLocationInfo(pt, rs)
	        {
	            var opts = {
	              width : 250,     //信息窗口宽度
	              height: 100,     //信息窗口高度
	              title : ""  //信息窗口标题
	            }    
	             addComp = rs.addressComponents;
	             addr = "当前位置：" + addComp.province + ", " 
	        	+ addComp.city + ", " + addComp.district + ", " +
	        	 addComp.street + ", " + addComp.streetNumber + "<br/>";
	            addr += "纬度: " + pt.lat + ", " + "经度：" + pt.lng;
	             
	            var infoWindow = new BMap.InfoWindow(addr, opts);  //创建信息窗口对象
	            
	            marker.openInfoWindow(infoWindow);
	        }
	        
            $("#pointModal").modal({backdrop:"static"}).modal('show');
        });   
    });
	
	//
	$("#cancel").click(function(){
		 
		top.location.reload();
		//window.location.href = window.location.href;
    }); 
	
	$("#suu").click(function(){
		 
        var param = $("#editServerForm").serializeArray();
        $.ajax({
            type:'POST',
            url:'/lottery/ajaxt',
            data:param,
            dataType:'json',
            success:function(result){
                alert(result.msg);
                if(result.errcode == 0){
                    window.location.href = window.location.href;
                }
            }
        });
    }); 
	 
	
	$("#cachetableExcel tr").each(function() {
        var _this = $(this);
        _this.find("#delcache").click(function() {             
            var id = $(this).parent().parent().find('[data-name=cacheid]').text();
            if (confirm("你确定要把Id为"+id+"的缓存数据删除么?")) {
            var param = '{"id":'+id+'}'; 
            $.ajax({
                type:'POST',
                url:'/CacheLog/delcache',
                data:param,
                dataType:'json',
                success:function(result){
                    alert(result.msg);
                    if(result.errcode == 0){
                        window.location.href = window.location.href;
                    }
                }
            });
            }
        });
        
        // 请求数据
        _this.find(".serverInfo").click(function() {
        	 
            var form = $("#logInfoForm").serializeArray();
            for(var i=0;i<form.length;i++){
                var name = form[i].name;
                
                if(name ==null ||name =="")
                {
                    continue;
                }
                 
                var value = $(this).parent().parent().find('[data-name='+name+']').text();
                $("#logInfoForm [name="+name+"]").val(value);
        
            }
            //$("#serverinfoModal input[name=id]").val(_this.attr("id"));
            $("#cacheloginfoModal").modal({backdrop:"static"}).modal('show');
        });
    });
        
});


