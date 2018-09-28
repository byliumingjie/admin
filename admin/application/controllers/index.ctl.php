<?php

/* 
 * 首页
 */

class Index_Controller extends Module_Lib{
 

    public function index(){
        if(!User_Service::checkLogin()){
            header("Location:/user/login");
            exit();
        }
        
        $this->load_view("index");
    }
    	
    
    public function error(){
        $this->load_view('error');
    }
   
    public function socketerror() {
        $this->load_view('socket_error');
    }
    
    //游戏工具箱快捷入口
    public function toolsindex() {
        $this->load_view('toolsindex');
    }
    
    // gm工具箱快捷入口
    public function gmindex() {
        $this->load_view('gmindex');
    }
	// 重复登录错误
	public function repeatloginerror()
	{
		$data = array(); 
		__log_message("repeatloginerror",'index');
		$this->load_view('repeat_login_error');
	}
	/* // 替换顶替页面
	public function  replacelogin() {
		;
	} */
	
}

