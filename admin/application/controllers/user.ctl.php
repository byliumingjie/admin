<?php

/*
 * 用户信息相关
 */

class User_Controller extends Module_Lib {

    /**
     * 检查是否登录
     */
    public function checkLogin() {
        return User_Service::checkLogin();
    }

    /**
     * 登录
     */
    public function login() { 

        load_view('login');
    }
    
    public function setzoneid($id){
            $this->prompt("设置成功~！",true);	
    }
    /**
     * 登录
     */
    public function userLogin() {
        //判断参数
        if (empty($_POST['account'])) {
            $this->outputJson(-1, '登录帐号不能为空');
        }
        if (empty($_POST['pass'])) {
            $this->outputJson(-1, '登录密码不能为空');
        }
        
        $account = $_POST['account'];
        $pass = $_POST['pass'];
        $remember = empty($_POST['remember']) ? 0 : $_POST['remember'];
        
        //验证用户是否存在        
        $user = User_Service::getUserByAccount($account);
        
        if (empty($user)) {
            $this->outputJson(-2, '用户不存在');
        } 
       	$pdd = Helper_Lib::encodePass($pass);        
       	//__log_message("user loghin userLogin11",'user');
        //验证密码是否正确
        if (empty($user['t_password']) || $user['t_password'] != Helper_Lib::encodePass($pass)) {
            $this->outputJson(-3, '用户密码为空或者错误');
        }
        // 把账户存入sessin
        $_SESSION['account'] = $account;
        // 把用户的平台权限id列字符创存入session
        Session::set('platformPermit',$user['t_usepermission']);
	// 用户的平台权限id 存入cookie
        Helper_Lib::setCookie("gzoneid",intval(Session::get('platformPermit')));
       	// 获取用户该有权限的平台配置数据
        $g_platformInfo = User_Service::getuser_platform($user['t_usepermission']);
		
        
		foreach($g_platformInfo as $var)
		{
			$_SESSION['platformInfo'][$var['type']] = $var;
		}
		 
     	$AllplatformInfo  = User_Service::getuser_platform(0,true);
     	 
     	if(!empty($AllplatformInfo) && is_array($AllplatformInfo))
     	{
     		foreach ($AllplatformInfo as $Inallvar)
     		{
     			if ($Inallvar['type']==0 ){continue;}
     			$platformList[(int)$Inallvar['type']] = $Inallvar;
     		}
     		ksort($platformList);
     		 
     		
     		session::set("AllplatformInfo",$AllplatformInfo);
     	}
     	 
        if ($remember) {
            setcookie("accountCookie", $account);
        }
         
        $aRoleIds = !empty($user['t_roleid'])?explode(',', $user['t_roleid']):array();
        //获得平台  
        //判断是否是管理员ged
        if(in_array(1, $aRoleIds) || $account == 'admin'){
            $_SESSION['isAdmin'] = true;
        }else{
            $_SESSION['isAdmin'] = false;
        }
        //获取用户权限
        $aPermission = Role_Service::getPermission($aRoleIds);
        //存储权限
        $_SESSION['udata']['permission'] = $aPermission['permission'];
        $_SESSION['udata']['navbar_permission'] = $aPermission['navbar_permission'];
        __log_message("user loghin userLogin22",'user');
        $session_account_statu = Session::sessionid_verif(false);         
        $session_account_statu2 = Session::sessionid_verif(true);
        if ((int)$session_account_statu['status']==1 &&
        		$session_account_statu2['status']==1
        ){ 
        	$this->outputJson(1,'已在其他地点登录，不能重复登录');
        }
        __log_message("user loghin userLogin33",'user');
        $this->outputJson(0, '登录成功');
    }

    // 编辑 by liumingjie add 2016-01-18    
    public function edit()
    {  
        $updatAccount = $_POST['updatAccount'];
        $updatRoleid = $_POST['updatRoleid'];   
        $upUidtype = (int)$_POST['upUidtype'];
        $updatPermission = !empty($_POST['updatPermission'])        
        ?implode(",",$_POST['updatPermission']):NUll;
        //  encodePass
        __log_message('password 1 :: '.$_POST['updatPassword'],'user-log');
       // xxtea_lib::decode($value)
       
        $updatPassword = $_POST['updatPassword'];
        
        if ($upUidtype==1)
        {
        	$updatPassword =  Helper_Lib::encodePass($_POST['updatPassword']);
        }
         __log_message('password::'.$updatPassword,'user-log');
        $data = array(                     
        't_usepermission'=>$updatPermission,
        't_roleid'=>$updatRoleid,
        't_password'=>$updatPassword,
        );
        //__log_message("accountName:::".$_POST['updatRoleid']);
        $ret = User_Service::updateUser($updatAccount,$data);
        if (!$ret) {
        	log_message(104, $data, "编辑用户失败！", -104);
            $this->outputJson(-3, '编辑用户失败！网络原因，请稍后重试！');
        }
        log_message(104, $data, "编辑成功");
        $this->outputJson(0, '编辑用户成功！');
    
    }
    
    /*
     * 注册
     */
    public function register() {
        $this->load_view('reg');
    }
	
    /**
     * 注册
     */
    public function userRegister() {
        if (empty($_POST['account'])) {
            $this->outputJson(-1, '注册帐号不能为空');
        }
        if (empty($_POST['pass'])) {
            $this->outputJson(-1, '注册密码不能为空');
        }

        $account = $_POST['account'];
        $pass = $_POST['pass'];

        $user = User_Service::getUserByAccount($account);
        if (!empty($user)) {
            $this->outputJson(-2, "用户{$account}已存在！");
        }
	
        $ret = User_Service::register($account, $pass);
        if (!$ret) {
            $this->outputJson(-3, '注册失败！网络原因，请稍后重试！');
        }
        $this->outputJson(0, '注册成功！');
    }

    public function loginout() 
    {
    	if(Session::session_last_time_up()){
    		
    		__log_message("最后时间更新成功",'lasttimeup');
    	}
    	else{
    		__log_message("最后时间更新失败",'lasttimeup');
    	}
        @session_destroy();
        setcookie('search','',time()-1,'/');
        setcookie('zoneid','',time()-1,'/');
        setcookie('accountCookie','',time()-1,'/');
        setcookie('filepath','',  time()-1 ,'/');
        Header("Location:/user/login");

        exit();
    }
    
    /**
     * 用户列表
     */
    public function lists(){
    	//$this->outputJson(-1,'test');
        $data = array();
        $users = User_Service::getUsers(1,100);
        $roles = Role_Service::getAllRoles();
        $data['roles'] = $roles;
        $data['users'] = $users;
        $data['addUser'] = $this->checkPermission('user/addUser');
        $data['editUser'] = $this->checkPermission('user/editUser');
        $data['delUser'] = $this->checkPermission('user/delUser');
        $this->load_view('user',$data);        
    }
    
    public function addUser() 
    {    	 
    	if (!empty($_POST['platformid'])){
    		$usepermission= implode(",",$_POST['platformid']);
    	}
    	else{
    		$usepermission = 0;
    	}
        if (empty($_POST['account'])) {
            $this->outputJson(-1, '帐号不能为空');
        }
        if (empty($_POST['roleid'])) {
            $this->outputJson(-1, '所属组不能为空');
        }

        $account = $_POST['account'];
        $roleid = $_POST['roleid'];

        $user = User_Service::getUserByAccount($account);
        if (!empty($user)) {
            $this->outputJson(-2, "用户{$account}已存在！");
        }
        if($_POST['password']){
        	$password = $_POST['password'];
        }
        $data = array(
        't_account'=>$account,
        't_roleid'=>$roleid,
        't_usepermission'=>$usepermission,
        't_password'=>$password
        );
        
        $ret = User_Service::addUser($data);
        if (!$ret) {
        	$Tips ='添加用户失败！网络原因，请稍后重试！';
        	
        	log_message(103, $data, "$Tips",-103);
        	
            $this->outputJson(-3, "$Tips");
        }
       	log_message(103, $data, "查询成功",0);
                 	
        $this->outputJson(0, '添加用户成功！');
    }
    
    public function RequeslocatHeader($code,$request,
    $response,$statusCode,$source=3)
    {
    	if (is_array($request))
    	{
    		$request['code']=$code;
    		$request['operator']=$_SESSION['acction'];
    		$request['source']=$source;
    		$request = json_encode($request);
    	}
    	if (is_string($response)){
    		$inPonseOut = array(
    			'status'=>$statusCode,
    			'result'=>$response
    		);   		 
    		$response = json_encode($response);
    	}    	
    	if (is_array($response))
    	{
    		$inPonseOut = array(
    			'status'=>$statusCode,
    			'result'=>$response
    		);
    		$response = json_encode($response);
    	}
    	$logdata = array(
    		"source"=>$source,
    		"protocolCode"=>$code,
    		"sname"=>0,
    		"playerId"=>0,
    		"account"=>$_SESSION['acction'],
    		"RequestData"=>$request,
    		"ResponseData"=>$response,
    		"RequestIp"=>$this->getIP(),
    		"ExecutionState"=>$statusCode,
    		"create_time"=>time()
    	);
    	//Apilog_Model::setlog($logdata);    	  	
    }
    public function editUser() {
        if (empty($_POST['account'])) {
            $this->outputJson(-1, '帐号不能为空');
        }
        if (empty($_POST['roleid'])) {
            $this->outputJson(-1, '所属组不能为空');
        }

        $account = $_POST['account'];
        $roleid = $_POST['roleid'];

        $user = User_Service::getUserByAccount($account);
        if (empty($user)) {
            $this->outputJson(-2, "用户{$account}不存在！");
        }
        $data = array('t_roleid'=>$roleid);
        $ret = User_Service::updateUser($account,$data);
        if (!$ret) {
            $this->outputJson(-3, '22编辑用户失败！网络原因，请稍后重试！');
        }
        $this->outputJson(0, '编辑用户成功！');
    }
    
    public function delUser() {
        try{
            $uid = Helper_Lib::getPost('uid', 'string', '', false);
            $user = User_Service::getUserByUid($uid);
            if (empty($user)) {
                $this->outputJson(-2, "用户不存在！");
            }
            $ret = User_Service::delUser($uid);
            if (!$ret) {
            	log_message(105, array('uid'=>$uid,'user'=>$user),'删除平台失败',-105);
                $this->outputJson(-3, '33删除用户失败！网络原因，请稍后重试！');
            }
            log_message(105,array('uid'=>$uid,'user'=>$user),"删除成功");
            $this->outputJson(0, '删除用户成功！');
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(),$e->getMessage());
        }
       
    }
    /**
     * 游戏平台设置 整合的平台配置包含数据库配置信息&游戏服通信配置
     * @method GamePlatform 获取平台配置信息
     * @method addPlatform  添加平台配置
     * @method delplatfomr  平台删除配置
     **/
    public function GamePlatform()
    {
    	$data =array();
    	  
    	$InPlatform = User_Service::getuser_platform(0,TRUE);
    	 
    	if(!empty($InPlatform))
    	{
    		$data = ["InPlatform"=>$InPlatform]; 
    	}
    	 
    	$this->load_view("game_platform",$data);
    }
    public function addPlatform()
    {
     	// __log_message("addplatform");
    	// 之所以把变量名称更改为type是因为在type是区服ID的时候同时也关乎其他配置如果自定的配置则为0
    	// 后期优化点可以进行优化，自定义默认为0需要手动通过db填写一般会结合服务器名称或者后期增加字段也可以
    	$type = $_POST['type'];// 服务器id
    	$name = $_POST['platformName'];	
    	$desc = $_POST['desc']; 
    	$gamehost  = $_POST['platformAddr'];
    	$gameprot  = $_POST['platformPort'];
    	$mysqlhost = $_POST['platformDBAddr'];
    	$mysqlport = $_POST['mysqlport'];
    	$mysqldb   = $_POST['platformDBName'];
    	$mysqlpwd  = $_POST['platformDBPwd'];
    	$mysqluser = $_POST['platformDBUser'];
   	
    	$data = array
    	(
    	    "platformName"=>$name,
    	    "type"=>$type,
    	    "desc"=>$desc,
	    	'platformhost'=> $gamehost,
    		'platformport'=> $gameprot,
            'platformdb'  => $mysqldb,
	    	'platformuser'=> $mysqluser,    		
	    	'platformpwd' => xxtea_lib::encode($_POST['platformDBPwd']),
    		'mysqlhost'=>$mysqlhost,
    		'mysqlport'=>$mysqlport,
    		'db'=>$mysqldb,
    		'mysqluse'=>$mysqluser,
    		'mysqlpasswprd'=>$mysqlpwd,
        );     
    	$ret = User_Service::addPlatform($data);
    	
    	if (!$ret) 
    	{
    		log_message(6003, $data, '',-6003);
            $this->outputJson(-3, '添加平台失败！网络原因，请稍后重试！');
        }
        log_message(6003, $data, '',0);
        //log_message($code, $request, $response)
        $this->outputJson(0, '添加平台成功！');	 
    }
    // 平台删除
    public function delplatfomr()
    { 
     	try{
            $uid = Helper_Lib::getPost('id', 'string', '', false);
            
            $ret = User_Service::delPlatform($uid);
            if (!$ret) {
            	log_message(6005, $uid, '',-6005);
                $this->outputJson(-3, '删除平台失败！网络原因，请稍后重试！');
            }
            log_message(6005, $uid, '',0);
            $this->outputJson(0, '删除平台成功！');
        } catch (Exception_Lib $e) {
            $this->outputJson($e->getCode(),$e->getMessage());
        }
        
    }
    /**
     * 编辑平台
     */
    public function editPlatform(){
    	$id = $_POST['id'];
    	if (empty($_POST['platformname'])) {
    		$this->outputJson(-1, '服务器名称不能为空！');
    	}
    	if (empty($_POST['type'])) {
    		$this->outputJson(-1, '服务器ID不能为空！');
    	} 
    	 
    	$data = array
    	(
    		'platformname' => $_POST['platformname'],    			
    		'type' => $_POST['type'],
    		'mysqlhost' => $_POST['mysqlhost'],
    		'mysqlport' => $_POST['mysqlport'],
    		'db' => $_POST['db'],
    		'mysqluse' => $_POST['mysqluse'],
    		'platformhost' => $_POST['platformhost'],
    		'platformport' => $_POST['platformport'], 
    		'desc' => !empty($_POST['desc'])? $_POST['desc']:"未填写",
    		'totalNode' => !empty($_POST['totalNode'])? $_POST['totalNode']:0,
    		'openNode' => !empty($_POST['openNode'])? $_POST['openNode']:0,
    		'gameNodePort' => !empty($_POST['gameNodePort'])? $_POST['gameNodePort']:"",
    		'gameNodeHost' => !empty($_POST['gameNodeHost'])? $_POST['gameNodeHost']:"",
    		'gameInternalHost'=>!empty($_POST['gameInternalHost'])? $_POST['gameInternalHost']:"",
    	);
    
    	$ret = User_Service::editPlatform($id,$data);
    	// 通讯游戏服状态 
    	if(!$ret){
    		log_message(6004,$data,'',-6004);
    		$this->outputJson(-2,'网络原因，请稍后重试！');
    	}
    	log_message(6004,$data,'',0);
    	$this->outputJson(0,'修改成功');
    }
    /***
     * -------------------------------------------------
     * 				登录重复
     * -------------------------------------------------
     * ***/
    public function  edituserstatus(){ 
    	 
    	if (Session::session_clear_up(false)){
    		if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    		$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
    		{
    			$this->outputJson(0,'顶替成功!');
    		}else{
    			Header("Location:/index/repeatloginerror");
    		}
    	}
    	$this->outputJson(-1,'顶替失败'); 
    }
    /***/
    public function repeatlogin()
    { 
    	__log_message("repeatlogin session_clear_up",'session');
    	if(Session::session_clear_up()){
    		
    		/*@session_destroy();
    		 setcookie('search','',time()-1,'/');
    		 setcookie('zoneid','',time()-1,'/');
    		 setcookie('accountCookie','',time()-1,'/');
    		 setcookie('filepath','',  time()-1 ,'/');
    		 */
    		Header("Location:/index/index");
    
    		//$this->loginout();
    	}
    }
}
