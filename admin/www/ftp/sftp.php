<?php
class sftp{


	// 初始配置为NULL
	private $config =NULL ;
	// 连接为NULL
	private $conn = NULL;

	private $conn_ = NULL;
	// 是否使用秘钥登陆
	private $use_pubkey_file= false;

	// 初始化
	public function init($config){
		$this->config = $config ;
	}
	//$FTP_HOST,$FTP_PORT,$FTP_USER,$FTP_PASS
	function __construct()
	{
		//$this->conn_id = @ftp_connect($FTP_HOST,$FTP_PORT) or die("FTP服务器连接失败");
		//@ftp_login($this->conn_id,$FTP_USER,$FTP_PASS) or die("FTP服务器登陆失败");
		//@ftp_pasv($this->conn_id,1); // 打开被动模拟
	}
	// 连接ssh ,连接有两种方式(1) 使用密码
	// (2) 使用秘钥
	public function connect(){

		$methods['hostkey'] = isset($use_pubkey_file) ? 'ssh-rsa' : [] ;
		$conn = ssh2_connect($this->config['host'], $this->config['port'], $methods);
		//(1) 使用秘钥的时候
		if($use_pubkey_file){
			// 用户认证协议
			$rc = ssh2_auth_pubkey_file(
					$conn,
					$this->config['user'],
					$this->config['pubkey_file'],
					$this->config['privkey_file'],
					$this->config['passphrase']
					)
					;
			//(2) 使用登陆用户名字和登陆密码
		}else{
			$rc = ssh2_auth_password( $conn, $this->config['user'],$this->config['passwd']);

		}
		$this->conn_= $rc;
		return $rc ;
	}


	// 传输数据 传输层协议,获得数据
	public function download($remote, $local){

		return ssh2_scp_recv($this->conn_, $remote, $local);
	}
	 
	//传输数据 传输层协议,写入ftp服务器数据
	public function upload($remote, $local,$file_mode=0664){
		return ssh2_scp_send($this->conn_, $local, $remote, $file_mode);

	}

	// 删除文件
	public function remove($remote){
		$sftp = ssh2_sftp($this->conn_);
		$rc  = false;

		if (is_dir("ssh2.sftp://{$sftp}/{$remote}")) {
			$rc = false ;
				
			// ssh 删除文件夹
			$rc = ssh2_sftp_rmdir($sftp, $remote);
		} else {
			// 删除文件
			$rc = ssh2_sftp_unlink($sftp, $remote);
		}
		return $rc;
			
	}
		



}


$config = [
		"host"     => "172.168.100.185",   // ftp地址
		"user"     => "minospic",
		"port"     => "22",
		//"pubkey_path" =>"/root/.ssh/id_rsa.pub",  // 公钥的存储地址
		//"privkey_path" =>"/root/.ssh/id_rsa",     // 私钥的存储地址
];

$handle = new sftp();
$handle->init($config);
$rc = $handle->connect();
var_dump($rc);

$ret = $handle->upload('/minos/intest/images/userphotos1.png','D:/xampp/htdocs/test/userphotos1.png');

var_dump($ret);
?>