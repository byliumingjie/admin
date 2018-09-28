<?php

/**
 * 
 */
class Module_Lib extends Controller {

    public function __construct() {
        parent::__construct();
        if ( !$this->checkPermission() ) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            	
                //ajax请求
                $this->outputJson(-1000, '对不起，您没有此权限，请联系管理员开通权限！');
            } else {
            	 
                header("Location:/index/error");
            }
            exit();
        }
        $argvs = parse_url($_SERVER['REQUEST_URI']);
        $menu = Menu_Service::getMenusByCA($_GET['c'], $_GET['a'],$argvs['query']);
        $postion = !empty($menu['t_postion']) ? intval($menu['t_postion']) : 0;
        Helper_Lib::setCookie('postion',$postion);
    }

    /**
     * 返回检查结果
     * @param type $url 需要检查的权限
     * @return boolean
     */
    public function checkPermission($url = '') {
        if (empty($url)) {
            //白名单链接，所有人都可以通过
            $c = !empty($_GET['c']) ? strtolower($_GET['c']) : 'index';
            $a = !empty($_GET['a']) ? strtolower($_GET['a']) : 'index';
            $argvs = parse_url($_SERVER['REQUEST_URI']); 
            $argv = $argvs['query']; 
        } else {
            list($_url,$argv) = explode('?', $url);
            list($c, $a) = explode('/', $_url);
        }
        
        $c = strtolower($c);
        $a = strtolower($a);
        if (in_array($c . '/' . $a, $this->getWhitelist())) {
            return true;
        }
         
        $accountstat = Session::sessionid_verif(true);
        $all_accountstat = Session::sessionid_verif(false);
        
        if ($accountstat['status'] == 2)
        {
        	//$_SESSION['AccountVerifType']=2; 
        	//if is  ajax 请求
        	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        	&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') { 
                
                $this->outputJson(-2, '对不起，您此账号已被登录！');
            } else {
            	$data = [
            	'AccountStatuInfo'=>'您账号的已被顶替登录!',	
            	'action'=>'/user/repeatlogin'
            	];
            	$this->load_view('repeat_login_error',$data);
            	exit();
                //header("Location:/index/repeatloginerror");
            }
        } 
        
        if ((int)$accountstat['status']==1 && (int)$all_accountstat['status']==1)
        {
        	 
        	//$_SESSION['AccountVerifType']=1;
        	header("Location:/user/edituserstatus"); 
        }
        
        //管理员 拥有所有权限
        if (!empty($_SESSION['isAdmin'])) {
            return true;
        }
        //用户权限
        if (!User_Service::checkLogin() || empty($_SESSION['udata'])) {
        	__log_message("User11",'checkLogin');
            return false;
        }
        $permission = $_SESSION['udata']['permission'];
        
        foreach ($permission as $menu) {

            if (strtolower($menu['t_controller']) == $c && strtolower($menu['t_method']) == $a) {
               
                if(!$argv || !$menu['t_argv'] || $menu['t_argv'] == $argv){
                    return true;
                }
            }
        }
        __log_message("User22",'checkLogin');
        return false;
    }

    private function getWhitelist() {
    	
        return array
        (
        	'index/index',
        	'index/socketerror',        	 
        	'index/error', 
        	'index/repeatloginerror',
        	'user/repeatlogin',
        	'user/edituserstatus',
        	'user/login', 
        	'user/userlogin', 
        	'user/register', 
        	'user/userregister',        		
        	'user/loginout'        		
        );
    }

}
