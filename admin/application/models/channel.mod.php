<?php

/*
 * 平台版本更新 model
 */

class Channel_Model extends Model
{

    private $table = 'tb_channel';
    private $db = null;

    public function __construct($region = null)
    {
        parent::__construct();
        if (!empty($region)) {
            $this->db = Mysql::database('', $region);
        }
    }

    /**
     * 活动删除
     * **/
    public function delActivity($id)
    {
        //$db = Mysql::load('platform');
        $where = "id=:id";
        $array = array('id' => $id);
        return $this->db->delete('game_activity', $where, $array);
    }

    /**
     * 记录活动
     * **/
    public function addConfigInfo($data, $setBatch = false)
    {
        $fields = 'channel_name,channel_token';
        if ($this->db->insert($this->table, $data)) {
            return true;
        }
        return false;
    }

    public function delConfig($id)
    {
        $where = "id=:id";
        $array = array('id' => $id);
        return $this->db->delete($this->table, $where, $array);
    }

    public function editConfig($id, $data)
    {
        $where = 'id=:id';
        $ret = $this->db->update2($this->table, $data, $where, array('id' => $id));
        if ($ret == false) {
            return false;
        }
        return true;
    }

    /**
     * 获取活JSOn配置信息
     * **/
    public function getChannelInfo()
    {
        $sql = 'SELECT * FROM ' . $this->table;

        if ($this->db->query($sql) && $this->db->rowcount() > 0) {
            $rows = $this->db->fetch_all();
            return $rows;
        }
        return false;
    }

    public function byConfigIdInfo($id)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' where id=' . $id;

        if ($this->db->query($sql) && $this->db->rowcount() > 0) {
            $rows = $this->db->fetch_row();
            return $rows;
        }
        return false;
    }
}

