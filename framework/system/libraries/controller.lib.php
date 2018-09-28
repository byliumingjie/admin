<?php

/**
 * 控制器基类
 * 
 *
 */
abstract class Controller {
    /* 事件注册器 */
    protected $events = array('start' => array('Onload_Device'), 'shutdown' => array('Unload_Device'));

    /* 调试开关, 这个参数在 onload 里面用到, 暂不改动 */
    public $__debug = false;

    /*
     * 控制器构造函数
     *
     * 当子类实现自己的控制器构造函数时，必须在构造函数体内第一行调用： parent::__construct();
     *
     * @param void
     * @reutrn object
     *
     */

    public function __construct() {
        //Debug::debug_set_time('controller');
        $this->runevents('start');
    }

    /*
     * 控制器构造函数
     *
     * 当子类实现自己的控制器析构函数时，必须在析构函数体内最后一行调用： parent::__destruct();
     *
     * @param void
     * @return void
     *
     */

    public function __destruct() {
        $this->runevents('shutdown');
        //Debug::debug_set_time('controller');
        //$this->unload_show_debug();
    }

    /**
     * 加载视图模板
     * 
     * @param string $template_file 模板文件 以.php结尾，默认为templates/default/下
     * @param array $value_array 传到视图的数据
     * @param bool $output 是否输出，不输出则返回模板解析后的 html
     * @param string $layout 视图目录
     */
    public function load_view($template_file, $value_array = array(), $output = true, $layout = 'default') {
        return load_view($template_file, $value_array, $output, $layout);
    }

    private function unload_show_debug() {
        if ($this->__debug || (isset($_GET['__debug']) && $_GET['__debug'] == 'true')) {
            $this->load_view('sys:debug');
        }
    }

    //获得当前microtime
    private function debug_get_microtime() {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float) $usec + (float) $sec), 5);
    }
    
    /**
     * JSON输出
     * $0 errcode
     * $1 msg
     * $2 data
     */
    protected function outputJson() {
        $args = func_get_args();
        $rs['errcode'] = $args[0];
        $rs['msg'] = $args[1];
        $args[2]&& $rs['data'] = $args[2];
        echo json_encode($rs);
        exit;
    }

    /**
     * return bool
     */
    private function runevents($eventname) {
        if ($this->events[$eventname]) {
            foreach ($this->events[$eventname] as $eventname) {
                $class_name = $eventname . '_Event';
                $res = load_event(strtolower($eventname));
                if (class_exists($class_name, false)) {
                    $object = new $class_name;
                    if (!call_user_func(array($object, 'run'))) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    /**
     * 	prompt status 
     * 	@param  $parm   alert Contents
     *  @param  $status true or false
     *  @param  $url 	add to url
     * **/
	public function prompt($parm="",$status=false,$dbugstatus=array(),$url=NULL)
	{ 	  
		 $gotourl = "<script>alert('$parm');window.history.go(-1);</script>";	
		 $gotourlfalse = "<script>alert('$parm');window.history.go(-1);</script>";			 
		 $dbugaction = "<script>alert('$parm');</script>"; 
		 switch($status)
		 {
		 	case true: echo $gotourl;exit;break ;
		 	case false: echo $gotourlfalse;exit;break; 
		 	default: return 0;break;  
		 }  
	}
	/**
     * 	prompt status 
     * 	@param  $parm   alert Contents
     *  @param  $status true or false
     *  @param  $url 	add to url
     * **/
	public function prompt2($parm="",$status,$url=NULL,$dbugstatus=array(),$dir='')
	{ 	  
		 
		 $gotourl = "<script>alert('$parm');</script>";	
		 
		 $gotourlfalse = "<script>alert('$parm');window.history.go(-1);</script>";
		 
		 $goajax = "<script>alert('$parm');window.history.go(-2);</script>";
		 
		 $page = Config::get("common.page");
		 
		 $acction = !empty($url)?$url:$page['host'].'/'.$dir; 
		 __log_message("acction:::".$acction);
		 
		 switch($status)
		 {
		 	//header('Location: http://www.baidu.com/')
		 	//隔离
		 	case 'off': echo $gotourlfalse;exit();break ;
		 	
		 	case 'false':echo $goajax;exit();break ;
		 	//正常
		 	case 'on': echo $gotourl;return false;break;
		 	
		 	case 'alert': echo $gotourl;break;
		 	
		 	case 'header':echo $gotourl;header("Location: $acction");break;
		 	
		 	default: return 0;break;  
		 }
	}
 
	
		
	/**
	 *  @param string $startTime 指定的一个日期
	 *  @param int 	$day_number 到某一个日期的天数
	 *  Pass a beginning and ending dates of the beginning and ending date 
	 *  return a list of all the information on the number of days
	 ***/
	public function Timelist($startTime,$dayNumber,$sequenc='')
	{ 
		$date=array();
		for($i=0;$i <= $dayNumber;$i++)
		{
			$strtodate=empty($sequenc)
			?
			strtotime($startTime)-($i)*24*3600
			:
			strtotime($startTime)+($i)*24*3600;
			 
			$date[$i]=date('Y-m-d',$strtodate); 
		}
		return $date;
	}  
#output_file 输出文件根据以下制定规则生成输出 
    public function output_file($time,$platId,$data=array(),
    $url="",$sid='')
	{    
		$fileName = date('Y-m-d h-i',time()).".xls";	 // Name xls or set up their own
		$url  = $url==""?PROJECT_ROOT."www//statfile//":$url;// Default(www\\file\\)  
		$file = fopen($url.$fileName,'w'); 				 // Fopen create xls 
		$platName = null;
		if(!empty($time) && is_array($time))
		{
			$timecnt = count($time)-1;
			$statTime = $time[$timecnt];			 // Start time
			$endTime  = "至".$time[0];				// End time
		}
		$sid =empty($sid)?'':' '.$sid.'区　'; 
		$platOut  = System_Service::getplatformInfo();
		foreach ($platOut as $var)
		{ 
			if ($platId == $var['id'])
			{
				$platName = $var['name'];
				break;
			}
			//$platinfo[$var['platformId']] = $var['platformname'];
		}
		fwrite($file,iconv("UTF-8","GBK",$platName.
		$sid.$statTime.$endTime)."\n"); 
		
		foreach($data as $key => $Indata)
		{
 			$key = iconv("UTF-8","GBK",$key);
			fwrite($file,$key."\n");
			
			foreach($Indata as $var)
			{
				$cont = count($var);
				$dataInfo = "";
				
				for ($i=0;$i<$cont;$i++)
				{ 
					$Indatastr = '';
					$encode = mb_detect_encoding($var[$i], 
					array("ASCII","UTF-8","GBK","GB2312","GBK","BIG5")); 
					//echo $encode;iconv("UTF-8","GBK",)
					
					if($encode==='ASCII'){
						//__log_message("编码:".$encode);
						$Indatastr = $var[$i];	
					}
					else
					{ 
						//__log_message("编码:".$encode);
						$Indatastr =iconv("UTF-8","GBK",$var[$i]);
					}
					$dataInfo.=$Indatastr."\t";				
				}				
				fwrite($file,$dataInfo."\n");
			}
		}
		fclose($file);
						
		if($file != true)
		{
			echo "
			<script>
			    alert('文件导出文件失败 file=false!');window.history.go(-2);
			</script>";
			return false;
			exit();
		}
		unset($time);
		unset($data);
		unset($platId);
		unset($platOut);
		return $fileName; 
	}
	
	/** 
	 * 区间范围 (取出两个数值范围内的所有数子) 
	 * @param $begin //数子开始区间
	 * @param $end	 //数子截止区间
	 * return array
	 **/
	public function range_number($begin,$end)
	{
		!is_numeric($begin)?$this->prompt("开始区间非有效整形数子",false):"";
		!is_numeric($end)?$this->prompt("截止区间非有效整形数子",false):"";
		$begin>$end?$this->prompt("开始区间不能大于截止区间",false):"";
		$begin==$end?$this->prompt("无效区间非法相等！",false):"";
		$rand_array = range($begin,$end); 
		Sort($rand_array);
		return 	$rand_array;
	}
	
	
	/** 时差天数
	 * @param $startTime开始时间
	 * @param $endtime  截止时间
	 * @param $limit	自定义时间范围
	 * @param $list type 如果该值不为空则返回列表数据
	 * @param $prompt 提示数据信息类型 默认会以js提示
	 **/
	public function jet_lag_day($startTime,$endtime,$limit="",$list='',$Excluding='')
	{  
		$preg = '/^(0[1-9]|1[0-2])-(3[01]|[12]\d|0[1-9]) ([0-5]\d):([0-5]\d)$/'; 
		
		if(preg_match($preg, $startTime) || preg_match($preg, $endtime))
		{			
			$this->prompt("时间格式有误",false);
		}			  
		$endtime = strtotime(date('Y-m-d',strtotime($endtime)));
		$startTime = strtotime(date('Y-m-d',strtotime($startTime)));			 
		$IntervalDays = $endtime - $startTime;																
		$DayNumber = ceil($IntervalDays/3600/24);									
		$DayNumber<0?$this->prompt("开始时间与截止时间格式不正确！",false):"";
		
		if(empty($limit) && empty($Excluding)){			
		  $DayNumber>60?$this->prompt("起止时间溢出最大为60天",false):"";
		}elseif (!empty($limit))
		{ 	
		  	$DayNumber>$limit?$this->prompt("起止时间溢出最大为{$limit}天",false):"";
		}
		if(!empty($list)){ 
			$endtime  = date('Y-m-d',$endtime);
			$DayNumber = $this->Timelist($endtime,$DayNumber);	
		}			
		return $DayNumber;		
	} 
	
	//获取浏览器
	public function getBrowse()
	{
	    global $_SERVER;
	    $Agent = $_SERVER['HTTP_USER_AGENT'];
	    $browseinfo='';
	    if(ereg('Mozilla', $Agent) && !ereg('MSIE', $Agent)){
	        $browseinfo = 'Netscape Navigator';
	    }
	    if(ereg('Opera', $Agent)) {
	        $browseinfo = 'Opera';
	    }
	    if(ereg('Mozilla', $Agent) && ereg('MSIE', $Agent)){
	 
	        $browseinfo = 'Internet Explorer';
	    }
	    if(ereg('Chrome', $Agent)){
	        $browseinfo="Chrome";
	    }
	    if(ereg('Safari', $Agent)){
	        $browseinfo="Safari";
	    }
	    if(ereg('Firefox', $Agent)){
	        $browseinfo="Firefox";
	    }
	 
	    return $browseinfo;
	}
	//获取ip
	public function getIP ()
	{
	    global $_SERVER;
	    if (getenv('HTTP_CLIENT_IP')) {
	        $ip = getenv('HTTP_CLIENT_IP');
	    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
	        $ip = getenv('HTTP_X_FORWARDED_FOR');
	    } else if (getenv('REMOTE_ADDR')) {
	        $ip = getenv('REMOTE_ADDR');
	    } else {
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
	//获取用户系统
	public function getOS ()
	{
	    $user_OSagent = $_SERVER['HTTP_USER_AGENT'];  
	    if(strpos($user_OSagent,"NT 6.1")){  
	        $visitor_os ="Windows 7";   
	    } elseif(strpos($user_OSagent,"NT 5.1")) {   
	        $visitor_os ="Windows XP (SP2)";   
	    } elseif(strpos($user_OSagent,"NT 5.2") && strpos($user_OSagent,"WOW64")){   
	        $visitor_os ="Windows XP 64-bit Edition";   
	    } elseif(strpos($user_OSagent,"NT 5.2")) {  
	        $visitor_os ="Windows 2003";   
	    } elseif(strpos($user_OSagent,"NT 6.0")) {  
	        $visitor_os ="Windows Vista";   
	    } elseif(strpos($user_OSagent,"NT 5.0")) {  
	        $visitor_os ="Windows 2000";   
	    } elseif(strpos($user_OSagent,"4.9")) {  
	        $visitor_os ="Windows ME";  
	    } elseif(strpos($user_OSagent,"NT 4")) {  
	        $visitor_os ="Windows NT 4.0";  
	    } elseif(strpos($user_OSagent,"98")) {  
	        $visitor_os ="Windows 98";  
	    } elseif(strpos($user_OSagent,"95")) {  
	        $visitor_os ="Windows 95";  
	    }elseif(strpos($user_OSagent,"NT")) {  
	        $visitor_os ="Windows 较高版本";  
	    }elseif(strpos($user_OSagent,"Mac")) {  
	        $visitor_os ="Mac";  
	    } elseif(strpos($user_OSagent,"Linux")) {   
	        $visitor_os ="Linux";  
	    } elseif(strpos($user_OSagent,"Unix")) {  
	        $visitor_os ="Unix";  
	    } elseif(strpos($user_OSagent,"FreeBSD")) {  
	        $visitor_os ="FreeBSD";  
	    } elseif(strpos($user_OSagent,"SunOS")) {  
	        $visitor_os ="SunOS";   
	    } elseif(strpos($user_OSagent,"BeOS")) {  
	        $visitor_os ="BeOS";   
	    } elseif(strpos($user_OSagent,"OS/2")) {  
	        $visitor_os ="OS/2";  
	    } elseif(strpos($user_OSagent,"PC")) {  
	        $visitor_os ="Macintosh";  
	    } elseif(strpos($user_OSagent,"AIX")) {  
	        $visitor_os ="AIX";  
	    } elseif(strpos($user_OSagent,"IBM OS/2")) {  
	        $visitor_os ="IBM OS/2";  
	    } elseif(strpos($user_OSagent,"BSD")) {  
	        $visitor_os ="BSD";  
	    } elseif(strpos($user_OSagent,"NetBSD")) {  
	        $visitor_os ="NetBSD";  
	    } else {  
	        //$visitor_os ="其它操作系统";  
	        $visitor_os =$user_OSagent;
	    }  
	    return $visitor_os;   
	}
	// INPUT POST INFO
	public function GET_SUBMIT()
	{
		if(empty($_POST)) return $_POST;
		//判断提交类型
		if($_SERVER["HTTP_CONTENT_TYPE"] != 'application/x-www-form-urlencoded'){
			return $_POST;
		}
		//获取POST原始值
		$data= file_get_contents("php://input");
		if(empty($data))	return $_POST;
		//开始处理
		$POST=array();
		$list=explode('&',$data);
		foreach($list as $key=>$value){
			//获取POST的KEY和Value值
			$postname=urldecode(substr($value,0, stripos($value,"=")));
			$postvalue=urldecode(substr($value,(stripos($value,"=")+1)));			
			//对KEY值和Value值进行处理
			//去空格和[]
			$postname=trim($postname,' ,[,]');
			$postvalue=trim($postvalue);
			
			if(array_key_exists($postname,$POST))
			{
				$POST[$postname]=$POST[$postname]." ,".$postvalue;
			}else{
				$POST[$postname] = $postvalue;
			}
		}
		return $POST;
	}
	/**
	 * Send 
	 * **/
	public function send($data,$code,$serverId=NULL,$getRet=false,$timeOut=NULL)
	{
		//$code = 'AskSendMail';
		$inHeader =  $this->VerifyToken($data,null,$code,$serverId);
		
		if(empty($timeOut))
		{
			$ret = $this->send_request($inHeader['url'],$inHeader['request'],'gbk');
		}
		else
		{
			$ret = $this->send_request($inHeader['url'],
			$inHeader['request'],'gbk',NULL,'POST','application/json;',$timeOut);
		}
		$retOut = json_decode(trim($ret),true);
		
		if ($getRet==true)
		{
			return $retOut;	
		} 
		if ( isset($retOut['status']) && $retOut['status']==0)
		{
			return true;
		}
		return false;
	}
	
	/*
	 * CURL 
	 * */
	public function send_request($url,$data,$coding='gbk', $refererUrl = '', $method = 'POST',
	$contentType ='application/json;', $timeout = 30, $proxy = false)
	{
		$ch = $responseData = null;
		$data = trim(mb_convert_encoding($data,"gbk","utf-8"));
		//$contentType.='charset='.$coding;
		
		if('POST' === strtoupper($method))
		{
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER,0 );
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			$info = curl_getinfo($ch);
			if ($refererUrl) {
				curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
			}
			$contentType = '';
			 
			if($contentType) {
				curl_setopt($ch, CURLOPT_HTTPHEADER,$contentType);
			}
			if(is_string($data)){
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}
		}
		else if('GET' === strtoupper($method))
		{
			if(is_string($data)) {
				$real_url = $url. rawurlencode($data);
			} else{
				$real_url = $url. http_build_query($data);
			}
			$urldata = rawurlencode($data); 
			
			__log_message("send_request url::".$real_url. urldecode($urldata),'mail');
			$ch = curl_init($real_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:'.$contentType));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			if ($refererUrl) {curl_setopt($ch, CURLOPT_REFERER, $refererUrl);}
		}
		else{
			$args = func_get_args();
			return false;
		}
		if($proxy) {
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		$ret = curl_exec($ch);
		curl_close($ch);
		$responseData = mb_convert_encoding($ret,"utf-8","gbk"); 
		
		__log_message('server Response data :'.$responseData,'VerifyToken');
		
		return   $responseData;
	}
	
	/**
	 * @param 
	 * 
	 * **/
	public function RequestHeader($cmd,$serverid,$prepare = array())
	{ 
		// 协议格式 定义后期可以去除进行多定义形式
		$request = array
		(
 			"cmd"=>$cmd,
 			"operator"=>$_SESSION['account'],
 			"source"=>2, 			
 			"serverid"=>$sid
 		); 
		// 后期处理定义与服务器格式
		$Header['postdata'] =json_encode(($request + $prepare)); 
		 
		switch ( SETSERVER )
		{
			case 0:
				$page = Config::get("common.page");
				$Header['url'] = $page['apihost'];
				break;
			case 1:
				$platform = $_SESSION['platformInfo'][$sid];
				$Header['url'] = 
				$platform['platformhost'].':'.$platform['platformport'];
				break;
			default:
				$this->prompt("没有找到通讯地址,服务器类型值为:".SETSERVER,false);
				break;
		}
		 		
		return $Header;		
	}
	/** 
	 * 验证加密 
	 * @param $data = array >json 
	 * 
	 **/
	public function VerifyToken($value,$manager=NULL,$action,$serverId =NULL)
	{ 
		$key = 'a329cf9547facb1cdac1b206f2432c48';
				 
		$value.$md5data  = array();
		if ((empty($value) || count($value)<=0) && empty($action))
		{
			return false;
		}   
		if (is_array($value))
		{
			$Inrequestjson = json_encode($value);
			__log_message("json data::".$Inrequestjson,'VerifyToken');
			$request = rawurlencode($Inrequestjson);
		} 
		
		__log_message('str info encode ::'.rawurlencode($request),'notice-log');
		
		// 打印
		$datastr = $key.$manager.$action.$request.$key;		
		__log_message('str info data'.$datastr);
		
		$md5data = md5(strtoupper($key.$request.$key));
		
		__log_message("md5 data::".$md5data,'VerifyToken');
		 
		if (isset($value['ServerId'])){ 
			$serverId = $value['ServerId'];
		}elseif (!empty($serverId)){
			$serverId = $serverId;
		}
		
		if ($manager=='getRequest')
		{
			$Header = [
					'request'=>$Inrequestjson.'/'.$md5data					
			];
			return $Header;
		}
		if (empty($serverId))
		{
			$this->prompt("没有找到通讯区服不能为空！",false);
			return false;
		}
		$host = $url = $port = NULL;
		
		switch ( SETSERVER )
		{
			// ----------------------------------------------------
			// 默认固定地址
			// ----------------------------------------------------			
			case 0:
				$page = Config::get("common.page");
				$host  = $page['apihost'];				 
				$url = $host.'/'.$manager.''.$action.'/';				
				break;
			// ----------------------------------------------------
			// 根据区服Id获取地址
			// ----------------------------------------------------
			case 1:	
				$platform = $_SESSION['platformInfo'][$serverId];
				$host  = $platform['platformhost'];
				$port  =  $platform['platformport'];
				
				if (empty($host))
				{
					if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
						 
						$this->outputJson(-1,'服务器地址读取失败,或没有服务器权限请联系管理员！');
					} else {
						$this->prompt("服务器地址读取失败,或没有服务器权限请联系管理员！",false);
						//header("Location:/index/error");
					}	
					//$this->prompt("服务器地址读取失败,或没有服务器权限请联系管理员！",false);
				}
				$url = 'http://'.$host.':'.
				
				$port.'/'.$manager.''.$action.'/';	
				
				__log_message("url data::".$url,'VerifyToken');
				break;			 
			default:
				$this->prompt("没有找到通讯地址,服务器类型值为:".SETSERVER,false);
				break;
		}
		 
		$Header = [
			'request'=>$Inrequestjson.'/'.$md5data,
			'host'=>$host,
			'port'=>$port,
			'sign'=>$md5data,
			'data'=>$Inrequestjson,
			'url' =>$url
		];
		// socke 数据组合 与之前的curl拼接方式分开保存		
		__log_message("request data::".
		$Inrequestjson.$md5data,'VerifyToken');		
		return  $Header;
	}
	public function log_message2($code,$request,
	$response,$statusCode,$source=3)
	{
		$Inrequest = array();
		$responseOut ='';
		if (is_array($request))
		{
			$Inrequest['code']=$code;
			$Inrequest['operator']=$_SESSION['account'];
			$Inrequest['source']=$source;
			if (!isset($request['result']))
			{
				$Inrequest['result'] = $request;
			}else {
				$Inrequest+= $request;
			}
			$requestOut = json_encode($Inrequest);
		}elseif (is_string($request))
		{		 
			$inPonseOut['result']=$response;
			$response = json_encode($inPonseOut);
		}
		
		if (is_string($response)){
			$inPonseOut['status']=$statusCode;
			$inPonseOut['result']=$response;			 
			$responseOut = json_encode($inPonseOut);
		}
		if (is_array($response))
		{
			$inPonseOut['status']=$statusCode;
			if (!isset($response['result']))
			{
			$inPonseOut['result']=$response;
			}else{
			$inPonseOut += $response;
			} 
			$responseOut = json_encode($inPonseOut);
		}
		$logdata = array(
				"source"=>$source,
				"protocolCode"=>$code,
				"sname"=>0,
				"playerId"=>0,
				"account"=>$_SESSION['account'],
				"RequestData"=>$requestOut,
				"ResponseData"=>$responseOut,
				"RequestIp"=>$this->getIP(),
				"ExecutionState"=>$statusCode,
				"create_time"=>time()
		);
		 if (!Apilog_Model::setlog($logdata)){
			 __log_message("后台日志记录失败!");	
		 }		
	}
	
	// GET POST DATA 
	public function get_contents()
	{
		$indata = '';
		$datOut = array();
		$Inrequest = array();
	
		$indata = file_get_contents("php://input");
		__log_message("php://input:::".$indata);
		$datOut = explode("&", $indata);	
		foreach ($datOut  as $var)
		{
			$datOut = explode("=", $var);
			$Inrequest[$datOut[0]] = $datOut[1];
		}
		return $Inrequest;
	}
	public function keystatus($retOut,$prompt='查询失败')
	{
		$cfg = Config::get("key.gm.error");
	
		$statinfo = $prompt;
	
		if(!empty($cfg[$retOut['status']]))
		{
			$statinfo = $cfg[$retOut['status']];			
		}		
		return  $statinfo;
	}
	public function responseCheck($ret,$success='',$failure='',$status='',$dir='',$type='',$server='')
	{
		//$_SESSION['LoteryStatus'] ='';
		//$_SESSION['Loteryprompt'] ='';
		$status = !empty($status)?$status:'off';
		__log_message("creatactoiv status".$status);
		
		if($ret)
		{
			$jsonOut = json_decode($ret,true);
			
			if(isset($jsonOut['status']) && $jsonOut['status']==0)
			{
				 
				if (!empty($type) && !empty($server) && !empty($dir))
				{
					$responseData = array(
						'LoteryType'=>	$type,
						'Loteryserver'=>$server,
						'LoteryStatus'=>0,
						'Loteryprompt'=>$success,
					);
					$keystatus = $this->keystatus($jsonOut,"{$failure}".$jsonOut['status']);
					
					$jsonOutstr = json_encode($responseData);	
					$this->prompt2($keystatus,"{$status}",'','',$dir.$jsonOutstr);
				}else {
					$this->prompt2("{$success}","on");
				} 
			}else 
			{
				$_SESSION['LoteryStatus'] =-1;
				
				$keystatus = $this->keystatus($jsonOut,"{$failure}".$jsonOut['status']);
				
				$_SESSION['Loteryprompt'] = !empty($keystatus)?$keystatus:$success;
				
				if (!empty($type) && !empty($server) && !empty($dir))
				{
					$responseData = array(
						'LoteryType'=>	$type,
						'Loteryserver'=>$server,
						'LoteryStatus'=>-1,
						'Loteryprompt'=>$success,
					);
					$jsonOutstr = json_encode($responseData);
					$this->prompt2($keystatus,"{$status}",'','',$dir.$jsonOutstr);
				}else {
					$this->prompt2($keystatus,"{$status}");
				} 
				 
			}
			
		}else{$this->prompt2("服务器无响应或数据为空!","{$status}");}
	}
	
	/*
	 function:二维数组按指定的键值排序
	 */
	public function array_sort($array,$keys,$type='asc')
	{
		if(!is_array($array)||empty($array)||!in_array(strtolower($type),array('asc','desc'))) return '';
		$keysvalue=array();
		foreach($array as $key=>$val){
			$val[$keys]=str_replace('-','',$val[$keys]);
			$val[$keys]=str_replace(' ','',$val[$keys]);
			$val[$keys]=str_replace(':','',$val[$keys]);
			$keysvalue[] =$val[$keys];
		}
		asort($keysvalue);//key值排序
		reset($keysvalue);//指针重新指向数组第一个
		foreach($keysvalue as $key=>$vals){
			$keysort[]=$key;
		}
		$keysvalue=array();
		$count=count($keysort);
		if(strtolower($type)!='asc'){
			for($i=$count-1;$i>=0;$i--){
				$keysvalue[]=$array[$keysort[$i]];
			}
		}else{
			for($i=0;$i<$count;$i++){
				$keysvalue[]=$array[$keysort[$i]];
			}
		}
		return $keysvalue;
	}
	/**
	 * memcaChe
	 * */
	public function connectMemcachee($action='localhsot',$prot=11211)
	{		
		$memcache = new Memcache();		 
		# You might need to set "localhost" to "127.0.0.1"
		$memcache->connect("{$action}",$prot) or die("cache链接失败!");; 
		
		return  $memcache;		
	}
	/**
	 * @global 分页
	 * @method 分页数组
	 * @param int $count is pagesize
	 * @param int $page is page
	 * @param array $array total data info is array
	 * @param $order
	 * @param array $array
	 * @param string orderkey array key
	 * @param $type asc or desc
	 * **/
	public function page_array($count,$page,$array,$orderkey,$type='asc')
	{
		global $countpage; #定全局变量
		$page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
		$start=($page-1)*$count; #计算每次分页的开始位置
		if(!empty($orderkey) && !empty($type)){
			$array=$this->array_sort($array,"{$orderkey}","{$type}");
		}
		$totals=count($array);
		$countpage=ceil($totals/$count); #计算总页面数
		$pagedata=array();
		$pagedata=array_slice($array,$start,$count);
		return $pagedata;  #返回查询数据
	}
	public function memcache(){
		
		$mem = new Memcache();
		 
		$mem->connect("127.0.0.1", 11211);
		
		return $mem;
		
		//保存数据
		$mem->set('key1', 'This is first value');
		$val = $mem->get('key1');
		echo "Get key1 value: " . $val ."";
		
	}
	/*public function redisConnector()
	{
		__log_message('redisConnector',"redis-log");
		$redis = new Redis();
	    $redis->connect('127.0.0.1', 6379);
		__log_message($redis->ping(),"redis-log");
		if( $redis->ping()!='PONG' )
		{
			return false;
		}
		return $redis;
	}*/
	//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
}

?>
