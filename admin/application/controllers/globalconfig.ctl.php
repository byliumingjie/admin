<?php

header('Cache-control: private, must-revalidate');

class GlobalConfig_Controller extends Module_Lib
{
    private $mode = null;

    public function __construct($conn = '')
    {
        parent::__construct();
        $conn = Platfrom_Service::getServer(true, 'gank_globaldb');
        $this->mode = new  GlobalConfig_Model($conn);
    }

    public function index($loadget = false)
    {
        $info['config_data'] = $this->mode->getConfigInfo();

        if (isBlank($info)) {
            $info = [];
        }

        if (!isBlank($loadget)) {
            return $info['config_data'];
        }

        $this->load_view("dev/global_config/list", $info);
    }

    public function addConfig()
    {
        $pc = $_POST['pc_name'];
        $channel = $_POST['channel'];
        $time = time();
        $sign = md5($channel . $pc . $time);

        $config_out = [
            'pc' => $pc,
            'channel_id' => $channel,
            'sign' => $sign,
            'create_at' => date('Y-m-d H:i:s', $time)
        ];

        log_message::info(json_encode($config_out));
        $ret = $this->mode->addConfigInfo($config_out);

        if (!isBlank($ret)) {
            $this->outputJson(SUCCESS, '添加成功');
        }

        $this->outputJson(FAILURE, '添加失败');
    }

    public function delConfig()
    {
        $id = $_POST['id'];

        $ret = $this->mode->delConfig($id);

        if (!$ret) {
            $this->outputJson(FAILURE, '删除失败！');
        }

        $this->outputJson(SUCCESS, '已成功删除！');
    }

    public function editConfig()
    {
        $id = $_POST['id'];

        $data = array(
            'pc' => $_POST['pc'],
            'channel_id' => $_POST['channel_id'],
        );
        $ret = $this->mode->editConfig($id, $data);

        if ($ret == true) {
            $this->outputJson(SUCCESS, '编辑成功');
        } else {
            $this->outputJson(FAILURE, '编辑失败');
        }
    }

    public function loadConfig()
    {
        $config_str = null;
        $id = $_POST['id'];
        $list_ary = $this->mode->byConfigIdInfo($id);

        if (!isBlank($list_ary)) {
            if (is_array($list_ary)) {
                $config_str = json_encode($list_ary);
            }
        }
        $pc =$list_ary['pc'];
        $res = $this->request_upload_curls();
        $pi1= pinyin::getPinyin($pc);
        $pi2= pinyin::getShortPinyin($pc);

        log_message::info($res.$pi1.$pi2);
    }
}
