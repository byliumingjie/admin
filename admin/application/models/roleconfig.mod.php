<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RoleConfig_Model extends Model{
    
    private $table = 'roledbconfig';
    private $db = null;
    public function __construct() {
        parent::__construct();
        $this->db = Mysql::load($this->table);
    }
    //获取对应区服的角色数据库配置
    public function getRoleConfigbyId($platformid,$sid) {
        $sql = 'SELECT * FROM  '.$this->table ."  where type = ".$platformid." and sid = ".$sid;       
        if($this->db->query($sql) && $this->db->rowcount() > 0){
            $rows =$this->db->fetch_row();           
            return $rows;
        }
        return false;
    }
    
    //获取对应平台的所有角色数据库配置
    public function getRoleConfig($platformid) {
        
        $sql = 'SELECT * FROM  '.$this->table ."  where type = ".$platformid;       
        if($this->db->query($sql) && $this->db->rowcount() > 0){
            $rows =$this->db->fetch_all();           
            return $rows;
        }
        return false;
    }
    
    
    
}