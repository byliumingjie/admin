<?php  
    /** 
    * 作用：FTP操作类( 拷贝、移动、删除文件/创建目录 ) 
    * QQ交流群：136112330 
    */  
    class class_ftp  
    {  
        public $off; // 返回操作状态(成功/失败)  
        public $conn_id; // FTP连接  
        /** 
         * 方法：FTP连接 
         * @FTP_HOST -- FTP主机 
         * @FTP_PORT -- 端口 
         * @FTP_USER -- 用户名 
         * @FTP_PASS -- 密码 
         */  
        function __construct($FTP_HOST,$FTP_PORT,$FTP_USER,$FTP_PASS)  
        {  
            $this->conn_id = @ftp_connect($FTP_HOST,$FTP_PORT) or die("FTP服务器连接失败");  
            @ftp_login($this->conn_id,$FTP_USER,$FTP_PASS) or die("FTP服务器登陆失败");  
            @ftp_pasv($this->conn_id,1); // 打开被动模拟  
        }  
        /** 
         * 方法：上传文件 
         * @path -- 本地路径 
         * @newpath -- 上传路径 
         * @type -- 若目标目录不存在则新建 
         */  
        function up_file($path,$newpath,$type=true)  
        {  
            if($type) $this->dir_mkdirs($newpath);  
            $this->off = @ftp_put($this->conn_id,$newpath,$path,FTP_BINARY);  
            if(!$this->off) {
            	echo "文件上传失败，请检查权限及路径是否正确！";
            	return false;
            }  
            echo "文件上传成功!";
        }  
        /** 
         * 方法：移动文件 
         * @path -- 原路径 
         * @newpath -- 新路径 
         * @type -- 若目标目录不存在则新建 
         */  
        function move_file($path,$newpath,$type=true)  
        {  
            if($type) $this->dir_mkdirs($newpath);  
            $this->off = @ftp_rename($this->conn_id,$path,$newpath);  
            if(!$this->off) echo "文件移动失败，请检查权限及原路径是否正确！";  
        }  
        /** 
         * 方法：复制文件 
         * 说明：由于FTP无复制命令,本方法变通操作为：下载后再上传到新的路径 
         * @path -- 原路径 
         * @newpath -- 新路径 
         * @type -- 若目标目录不存在则新建 
         */  
        function copy_file($path,$newpath,$type=true)  
        {  
            $downpath = "c:/tmp.dat";  
            $this->off = @ftp_get($this->conn_id,$downpath,$path,FTP_BINARY);// 下载  
            if(!$this->off) echo "文件复制失败，请检查权限及原路径是否正确！";  
            $this->up_file($downpath,$newpath,$type);  
        }  
        /** 
         * 方法：删除文件 
         * @path -- 路径 
         */  
        function del_file($path)  
        {  
            $this->off = ftp_delete($this->conn_id,$path);  
            if(!$this->off) echo "文件删除失败，请检查权限及路径是否正确！";  
        }  
        /** 
         * 方法：生成目录 
         * @path -- 路径 
         */  
        function dir_mkdirs($path)  
        {  
            $path_arr = explode('/',$path); // 取目录数组  
            $file_name = array_pop($path_arr); // 弹出文件名  
            $path_div = count($path_arr); // 取层数  
            foreach($path_arr as $val) // 创建目录  
            {   
                if(@ftp_chdir($this->conn_id,$val) == FALSE)  
                {  
                    $tmp = ftp_mkdir($this->conn_id,$val);  
                    if($tmp == FALSE)  
                    {  
                        echo "目录创建失败，请检查权限及路径是否正确！";  
                        exit;  
                    }  
                    ftp_chdir($this->conn_id,$val);  
                }  
            }  
            if( ftp_cdup($this->conn_id)==false )
            {
            	echo "回退失败";
            }
            
            /*
            // 死循环
            for($i=1;$i=$path_div;$i++) // 回退到根  
            {  
                @ftp_cdup($this->conn_id);  
            }
            */ 
        }  
        /** 
         * 方法：关闭FTP连接 
         */  
        function close()  
        {  
            @ftp_close($this->conn_id);  
        }  
    }// class class_ftp end  
    /************************************** 测试 ***********************************/ 
    //$ftp = new class_ftp('172.168.100.202',null,'administrator','jjj'); // 打开FTP连接
    $ftp = new class_ftp('172.168.100.185',21,'minospic','minospic'); // 打开FTP连接
    //ftp_chdir($ftp->conn_id,'/minos/intest/images/');
    
    //$ftp->up_file('D:/xampp/htdocs/test/userphotos.png','minos/userphotos4.png'); // 上传文件
    
    //删除目录
    //ftp_rmdir($ftp->conn_id,'/minos/intest/failure/');
    //echo $ret;
    
    //$ftp->up_file('D:/xampp/htdocs/test/test.txt','/minos/intest/images/success/test6.txt'); // 上传文件 
    //$ftp->move_file('minos/userphotos4.png','111/userphotos4.png'); // 移动文件 
    //$ftp->copy_file('a/cc.txt','a/b/dd.txt'); // 复制文件 
    //$ftp->del_file('minos/userphotos2.png'); // 删除文件 
    
    
    $ftp->close(); // 关闭FTP连接 
    
      
    ?>   