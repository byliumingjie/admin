<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();

class Page_Lib {
    private static $css_ver ="20150413";
    private static $js_ver = "20150413";

    public static function head($insert_html = '',$display='',$lodingjs='') {
    	 
    	$display = empty($display)?'':"style=\"display:none\""; 
    
        //获得js,css路径和版本号
	$img = self::getStaticHost();
        $js_path = self::getJsHost();
        $css_path = self::getCssHost();
         
        $css_ver = self::$css_ver ? self::$css_ver : CSS_VERSION;
        $js_ver = self::$js_ver ? self::$js_ver : JS_VERSION;
        $page = Config::get("common.page");
		
        if ($_SESSION['account']) {
            //已经登录		
          $nav_right = '<li class="btn btn-inverse"><a title="" href="#">
          <i class="icon icon-user"></i> <span class="text">' . $_SESSION['account'] . '</span></a></li>
		  <li class="btn btn-inverse"><a title="" href="'.$page['host'].'/user/loginout">
		  <i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>';

        $platform_html = '</select></li>';
        } else {
            $nav_left = '';
            $nav_right = '<!--<li><a href="'.$page['host'].'/user/login">请登录</a></li> -->';
        }
  
        if (!empty($_SESSION['platformInfo']))
        {	        
        	 
	        $platformCont = count($_SESSION['platformInfo']);
	        $platformANav = '';	        
	        $platformA='<li class="btn btn-inverse dropdown" >
	        <a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
			<i class="icon icon-signal"></i> 
			<span class="text">平台</span> 
			<span class="label label-important">'
	        .$platformCont.'</span> <b class="caret"></b></a>
            <ul class="dropdown-menu" id="zoneiddtt">';
	        
	        foreach ( $_SESSION['platformInfo'] as $var )
	        {        
	        	$type = (int)$var['type'];	
	        	$platformName = $var['platformname']; 
	         	                 
	        	$platformA.= "<li><a class='sAdd'linkid='{$type}'
	        	title='' href='#'>$platformName</a></li>";	        	
	        } 	        
	        $platformANav = $platformA.'</ul></li>';
	       
	        if(!empty($_COOKIE['gzoneid']))
			{ 
				Session::set('platformPermit',$_COOKIE['gzoneid']); 
			}
        } else{ 

        	$platformNav='';
        	$platformNav='';
        }      
        
       // '.$platformNav.' 
        if ($_SESSION['isAdmin']) {
            //如果是Admin			
            $nav_right = '<li class="btn btn-inverse"><a title="" href="#">
            <i class="icon icon-user"></i> <span class="text">' . $_SESSION['account'] . '</span></a></li>
			<li class="btn btn-inverse dropdown" id="settings">
			<a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle">
			<i class="icon icon-cog"></i> 
				<span class="text">Settings</span> 
				<span class="label label-important">2</span> 
			<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a class="sAdd" title="" href="/user/lists">账户管理</a></li>
                        <li><a class="sInbox" title="" href="/menu/menus">菜单管理</a></li>                       
                    </ul>
            </li>'.$platformANav.
            '<li class="btn btn-inverse">
            	<a title="" href="'.$page['host'].'/user/loginout">
            	<i class="icon icon-share-alt"></i> 
           		<span class="text">Logout</span></a>
            </li> 	
			 
		 ';
            
        } else {
            $nav_right = '
            <li class="btn btn-inverse"><a title="" href="#">
            <i class="icon icon-user"></i> 
            <span class="text">' . $_SESSION['account'] . '</span></a></li>
			<li class="btn btn-inverse"><a title="" href="'.$page['host'].'/user/loginout">
			<i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>';
        }		
		/*loading Getdata Start*/  
		$lodingjs = !empty($lodingjs)?"      
		<link rel='stylesheet' href='{$js_path}/loading/ladda-themeless.min.css' />
		<link rel='stylesheet' href='{$js_path}/loading/prism.css' />		
        <script src='{$js_path}/loading/spin.js'></script>
        <script src='{$js_path}/loading/ladda.js'></script>
        <script src='{$js_path}/loading/prism.js'></script> ":"";		
        $leftMenu = self::left();        
        $head = <<<EOF
          <!DOCTYPE html>
        <html lang="zh-cn">
	<!-- container-fluid -->
	<head>
		<title>Unicorn Games  Admin</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="shortcut icon" href="{$img}/img/log.ico"/>
		<link rel="stylesheet" href="{$css_path}/bootstrap.min.css" />
		<link rel="stylesheet" href="{$css_path}/bootstrap-responsive.min.css" />	
		<link rel="stylesheet" href="{$css_path}/unicorn.main.css" />
		<link rel="stylesheet" href="{$css_path}/unicorn.grey.css" class="skin-color" />
	    <link rel="stylesheet" href="{$css_path}/common.css"/>
        <link rel="stylesheet" href="{$css_path}/bootstrap-datetimepicker.min.css" />  
        <link rel="stylesheet" href="{$css_path}/jquery.multiselect2side.css"/>
        <!--loading Getdata begin-->
        {$lodingjs}
        <!--loading Getdata end-->        
        <!--Statistical verification begin-->
        <!--<script src="{$js_path}/statdata.js"></script>
        Statistical verification end -->        
		<script src="{$js_path}/jquery.min.js"></script>
        <script src="{$js_path}/bootstrap.min.js"></script>
        <script src="{$js_path}/bootstrap-datetimepicker.min.js"></script>
        <script src="{$js_path}/bootstrap-datetimepicker.zh-CN.js"></script>
        <script src="{$js_path}/common.js"></script>	
        <script src="{$js_path}/jquery.multiselect2side.js"></script>
        <script src="{$js_path}/jquery.ui.custom.js"></script>
        <script src="{$js_path}/unicorn.js"></script>
        
        <!---loading End--->  
	{$insert_html}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	         
	</head>
	<body>	
	 
		<div id="header">
			<h1><a href="#">Unicorn Games </a></h1>		
		</div>	
		<div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav btn-group">
			<!-- 调用登陆登出nav -->
			{$nav_right}
            </ul>
        </div>
			<!-- 正文 --> 
			<div id="sidebar"   $display>
				<a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
				<ul>
					<li><a href="/index/index"><i class="icon icon-home"></i> <span>Dashboard</span></a></li>
					<!-- 左侧菜单分栏 -->
					{$leftMenu} 
				</ul>		
			</div>
		 
				<!-- 正文 -->
			<div id="content">
			 
		<!-- 此处接页面标题 -->

   

EOF;
        return $head;
    }
	/*
	 * 加载平台属性
	 * */
    public static function  getplatformInfo($cookie,$Inform='')
    {
    	
    	if(empty($Inform))
    	{
    		
    		$platformOut = session::get("AllplatformInfo");
    		 
    		foreach($platformOut as $platvar)
    		{
    			if($platvar['type']==0)
    			{
    				continue;
    			}
    			$Inform[] = $platvar;    			
    		}
    	}
    	 
   	 	if(!empty($cookie) && !empty($Inform))
    	{
    		foreach($Inform as $var){
    			 if ($cookie == $var['type'])
    			 {
    			 	//echo $var['platformname'];
    			 //	__log_message("cookiEPlatfomr nonononono".$var['platformname']);
    			 	return $var['platformname'];	
    			 }else{
    			// 	__log_message("cookiEPlatfomr nonononono");
    			 	
    			 }
    		}	
    	}	 
    	//__log_message("为空的加载平台！");
    	return false; 
    }
 
        //按钮绑定动态按钮内侧加载内存加载流动（动态效果）....
    public static function Ladda_bind_js($loading=NULL){
    	 
    	$loading = ($loading!=NULL)?",{timeout:200 }":NULL;
    	$html = "
    	<script> 
			// Bind normal buttons
			Ladda.bind( 'div:not(.progress-demo) .ladda-button'{$loading});
			// Bind progress buttons and simulate loading progress
			
			Ladda.bind( '.progress-demo button',{				
				callback: function( instance ) {					 
					var progress = 0;	
					var interval = setInterval( function() {
						progress = Math.min( progress + Math.random() * 0.1, 1 );
						instance.setProgress( progress );
						
						if( progress === 1 ) { 
							instance.stop();
							clearInterval( interval );
						}
					}, 200 );
				}
			} );

			// You can control loading explicitly using the JavaScript API
			// as outlined below:
			// var l = Ladda.create( document.querySelector( 'button' ) );
			// l.start();
			// l.stop();
			// l.toggle();
			// l.isLoading();
			// l.setProgress( 0-1 );
		</script>";
    	return $html;
    }
	#hmlt 版权信息
	
    public static function footer($insert_html = '',$format = '') {
    	$laddaHtml =self::Ladda_bind_js($format);  
        $footer = <<<EOF
                <div class="span12" >
				</div>	 
			</div>
		</div>
	   {$insert_html}
	   {$laddaHtml}
	   <br>
	   	<div class="row-fluid">
			<div id="footer" class="span12">             
			Copyright &copy 2018 Unicorn Games
			</div>
		</div>
	</body>
</html>
EOF;
        return $footer;
    }

    public static function left() {
      if (empty($_GET['c']) || $_GET['c'] == '#') {
            $postion = intval(Helper_Lib::getCookie('postion'));
        } else {
            $c = $_GET['c'];
            $a = empty($_GET['a']) ? 'index' : $_GET['a'];
            $argvs = parse_url($_SERVER['REQUEST_URI']);
            $menu = Menu_Service::getMenusByCA($c, $a,$argvs['query']);
            $postion = !empty($menu['t_postion']) ? intval($menu['t_postion']) : intval(Helper_Lib::getCookie('postion'));
            Helper_Lib::setCookie('postion', $postion);
        }
        $menulist = Menu_Service::getTree($_SESSION['udata']['navbar_permission']);
        $menuHtml = Menu_Service::generateLeftMenuStructure($menulist, $postion, intval($menu['t_menuId']));
        $html = '';
        if ($menuHtml) {
            $html = '' . $menuHtml . '';
        }

        return $html;
    }
    /**
     * 账号设置(视图accountreplace.view.php文件进行调用：)
     */
    public static function Account_replace() {
    	
    	$scriptjava = '
         <script>
        window.onload = function(){
			var obj_select = document.getElementById("type");
			var obj_div = document.getElementById("replaceuin");
			var obj_replacetype = document.getElementById("replacetype");			
			
			if(obj_select.value==1){
				obj_div.style.display = "block";
				obj_replacetype.style.display = "none";
			}else{
    			obj_div.style.display = "none";
				obj_replacetype.style.display = "block";
    		}
			
			obj_select.onchange = function(){
				obj_div.style.display = this.value==1? "block" : "none";
				obj_replacetype.style.display = this.value==2? "block" : "none";
			}
		}
        </script>';
    	
		$AllplatformInfo = Session::get("AllplatformInfo");
		
		foreach($AllplatformInfo as $var){
			if((int)$var['type'] ===0 && $var['platformname']==='admin')
			{
				$servertype = $var;
				$servertype['db'] = 'admin';
				break;
			} 
		}  
		 
		$accoutType = AccountReplace_Model::get_accoutType($servertype); //        
        //账号类型
        $accountTypeHtml = '<select name="accounttype"  
        class="form-control" style="width:110px"><option value="" >账号类型 </option>';
    	if (is_array($accoutType) && !empty($accoutType)) {
            foreach ($accoutType as $Type) {
                $selected = '';
                $sid = ""; 
                $name = $Type['name'];
                $TypeID = $Type['type'];                 
                $accountTypeHtml .= '<option value="'.$TypeID.'">'.$name.'</option>';
            }
        }
        $accountTypeHtml.='</select>';
        //替换类型
 		$replaceTypeHtml = '<select name="replacetype" id="replacetype" class="form-control" 
 		style="width:118px;"><option value="" >更改账号类型 </option>';
    	if (is_array($accoutType) && !empty($accoutType)) {
            foreach ($accoutType as $Type) {
            	 
                $selected = '';
                $sid = ""; 
                $name = $Type['name'];
                $TypeID = (int)$Type['type']; 
              
                $replaceTypeHtml .= '<option value="'.$TypeID.'">'.$name.'</option>';
            }
        }
        $replaceTypeHtml.='</select>';
        //选择条件 
	   $selecttype = '<select name="type" id ="type" class="form-control" style="width:120px">'; 
	          
       $selecttype.= '
       <option value="0">--请选择--</option>
       <option value="2">账号类型变更</option>
       <option value="1">挂载账号</option>
       </select>';         
        
        //账号
        $selectHtml .= '<input  size ="10" value="" name="accountname" placeholder="账号"
        class="col-xs-2 form-control" style="width:auto;">　';
        //uin
		$replaceuin  = '<input value="" size ="10" id ="replaceuin" name="replaceuin" 
		placeholder="替代uin" class="col-xs-2 form-control" style="width:auto;">';
        //组装
      	$html = <<<EOF
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>设置条件</h5>
							</div>
	    <div class="widget-content">			
            <form method="POST" class="form-horizontal" onsubmit="return AccountFormVerify(this);">
			<div class="control-group">
			<div class="controls"> 
			<table border=0>
				<tr>
				 <td>{$scriptjava}</td>				  
				 <td>{$selecttype}</td> 
                 <td>　{$selectHtml}</td>
                 <td>{$accountTypeHtml}</td>
                 <td><i class="icon-th "></i></td>
                 <td>{$replaceTypeHtml}</td>
                 <td>{$replaceuin}</td>                                         
                 <td>　<button class="btn btn-primary" type="submit"><i class=""></i> 提交</button></td>
				</tr>
			</table>
		</div>
            </form> 
</div>
</div>
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
}
    /**
     * 统一的区服，用户查询表单
     * @param type $aServer array('sid'=>选中的区服，0未选中,'server'=>区服列表)
     * @param type $aSelect array('selected'=>选中的类型，0未选中,'selects'=>查询类型列表)
     * @param type $value 查询的表单值 $value == false
     * @return type
     */
    public static function serverForm($aServer, $aSelect=false, $value=false, $insert_html = '', $insert_html2 = '') {
        $serversHtml = '
                <select name="server" class="  form-control" ><option value="0" >请选择区服</option>';
        if (!empty($aServer['server'])) {
            $servers = $aServer['server'];
        }  else {
            $stServer =  Platform_Model::getPlatformByID($_COOKIE['zoneid']); 
            $servers = Server_Service::getAllServers($_COOKIE['zoneid'], $stServer);
        }
        if (is_array($servers) && !empty($servers)) {
            foreach ($servers as $server) {
                $selected = '';
                if ($sid == $server['sid']) {
                    $selected = ' selected';
                }
                $serversHtml .= '<option value="' . $server['sid'] . '"' . $selected . '>'.$server['sid'].'服 ' . $server['sname'] . '</option>';
            }
        }
        $serversHtml .= '</select>';
        
        //选择条件
        $selectHtml = '';
        if($aSelect !== false){
            $selectHtml .= '
                            <select name="select" class="form-control"><option value="">请选择查询条件</option>';
            $selected = !empty($search['selected']) ? $search['selected'] : $aSelect['selected'];

            if (empty($aSelect['selects'])) {
                $selects = array( 'nickname' => '角色名','roleid' => '角色ID','uid' => 'UIN' );
            } else {
                $selects = $aSelect['selects'];
            }
            if (is_array($selects) && !empty($selects)) {
                foreach ($selects as $key => $name) {
                    $selectedHtml = '';
                    if ($selected == $key) {
                        $selectedHtml = ' selected';
                    }
                    $selectHtml .= '<option value="' . $key . '"' . $selectedHtml . '>' . $name . '</option>';
                }
            }
            $selectHtml .= '</select>';
        }
        //查询值
        $valueHtml = '';
        if($value !== false){
            $value = !empty($search['value']) ? $search['value'] : $value;
            $valueHtml = '
                    <input value="'.$value.'" name="value" placeholder="查询值" class="form-control" style="width:auto;">
                ';
        }
        $html = <<<EOF
            	<!-- 查询组件 begin-->
				   <div class="widget-box">
				   	<div class="widget-title">
								<span class="icon">
									<i class="icon-search"></i>
								</span>
								<h5>查询条件</h5>
							</div>
	    <div class="widget-content">			
            <form method="GET" class="form-horizontal" onsubmit="return checkServerForm(this);">
			<div class="control-group">
			<div class="controls">               
                 {$serversHtml}
                 {$selectHtml}
                 {$valueHtml}
                 {$insert_html2}
               <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i> 查询</button>
		</div>
            </form>
            {$insert_html}

</div>
</div>
</div>
                   <!-- 查询组件 end-->
		
EOF;
        return $html;
    }
	 
    public static function loadCss($file, $ver = '',$url = '' ) {
    	
    	$url = !empty($url)?"/".$url:"";
    	
        $version = $ver ? $ver : (self::$css_ver ? self::$css_ver : CSS_VERSION);
        return '<link rel="stylesheet" href="' . self::getCssHost() .$url. '/' . $file . '.css?v=' . $version . '">';
    }

   /* public static function loadJs($file, $ver = '') {
        $version = $ver ? $ver : (self::$js_ver ? self::$js_ver : JS_VERSION);
        return '<script src="' . self::getJsHost() . '/' . $file . '.js?v=' . $version . '"></script>';
    }*/
    public static function loadJs($file, $ver = '',$url='') {
    	$url = !empty($url)?"/".$url:"";
        $version = $ver ? $ver : (self::$js_ver ? self::$js_ver : JS_VERSION);        
        return '<script src="' . self::getJsHost() .$url. '/' . $file . '.js?v=' . $version . '"></script>';
    }
    public static function getStaticHost() {
        $page = Config::get("common.page");
        return $page['static'];
    }

    public static function getJsHost() {
        return self::getStaticHost() . "/js";
    }

    public static function getCssHost() {
        return self::getStaticHost() . "/css";
    }

    public static function getImgHost() {
        return self::getStaticHost() . "/images";
    }

}

?>
