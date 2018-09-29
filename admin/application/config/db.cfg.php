<?php

/**
 * Description of db
 *
 * @author Marco
 */
class Db_Config {

    //put your code here

    public static function mydb_callback_server($params) {

        if (!is_array($params))
            return false;

        $const_table = $params['0'];
        $hash_param = $params['1'];
        $need_hash = $params['2'] ? $params['2'] : true;
        $hash = ($hash_param && $need_hash) ? md5($hash_param) : null;
	
        if (SETDB === 1) {

            $host = '192.168.0.140';
            $user = "liumj";
            $pass = "liumj!@#2018";
			
            switch ($const_table) {
            	case 'session':
            		$database || $database = "admin";
            		$table = 'tb_sessions';
            		$table_alias = 'session';
            		break;
            	case 'server_email':
            		$database || $database = "minosdb";
            		$table = 'game_server_email';
            		$table_alias = 'server_email';
            		break;
                default:
                    $database || $database = "admin";
                    $table = 'tb_' . $const_table;
                    $table_alias = $const_table;
                    break;
            }
        }
        if (SETDB === 0 || SETDB === 2) {

            $host = '192.168.0.140';
            $user = "liumj";
            $pass = "liumj!@#2018";
        		
        	switch ($const_table) {
        		case 'session':
        			$database || $database = "admin"	;
        			$table = 'tb_sessions';
        			$table_alias = 'session';
        			break;
        		case 'server_email':
        			$database || $database = "minosdb";
        			$table = 'game_server_email';
        			$table_alias = 'server_email';
        			break;
        		default:
        			$database || $database = "admin";
        			$table = 'tb_' . $const_table;
        			$table_alias = $const_table;
        			break;
        	}
        }

        $port || $port = 3306;
        
        $ret = array
            (
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pass' => $pass,
            'dbname' => $database,
            'table' => $table,
            'table_alias' => $table_alias
        );
         
        return $ret;
    }

}

?>
