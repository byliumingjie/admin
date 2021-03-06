<?php

class Channel_Controller extends Module_Lib
{
    private $mode = null;

    public function __construct($conn = '')
    {
        parent::__construct();
        $conn = Platfrom_Service::getServer(true, 'gank_globaldb');
        $this->mode = new  Channel_Model($conn);
    }

    public function index($loadget = false)
    {
        $info['config_data'] = $this->mode->getChannelInfo();

        if (isBlank($info)) {
            $info = [];
        }

        if (!isBlank($loadget)) {
            return $info['config_data'];
        }

        $this->load_view("dev/channel/list", $info);
    }

    public function addConfig()
    {
        $time = time();
        $channel_name = $_POST['channel_name'];
        $channel_token = pinyin::getPinyin($channel_name);

        // $c = xxtea_lib::Encrypt($channel_token);

        $config_out = [
            'channel_name' => $channel_name,
            'channel_token' => xxtea_lib::Encrypt($channel_token),
            'channel_code'=>pinyin::getPinyin($channel_name)
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
            'channel_name' => $_POST['channel_name'],
        );

        $ret = $this->mode->editConfig($id, $data);

        if ($ret == true) {
            $this->outputJson(SUCCESS, '编辑成功');
        } else {
            $this->outputJson(FAILURE, '编辑失败');
        }
    }

    /**
     * 加密验证
     */
    public function Verify()
    {
        $id = $_POST['id'];
        $list_ary = $this->mode->byConfigIdInfo($id);
        $string = $list_ary['channel_token'];
        $name = pinyin::getPinyin($_POST['channel_name']);
        $res = xxtea_lib::Verify($name, $string, false);

        if ($res) {
            $this->outputJson(SUCCESS, '验证通过' . $name);
        } else {
            $this->outputJson(SUCCESS, '验证失败' . $name);
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
        $pc = $list_ary['pc'];
        $res = $this->request_upload_curls();
        $pi1 = pinyin::getPinyin($pc);
        $pi2 = pinyin::getShortPinyin($pc);

        log_message::info($res . $pi1 . $pi2);
    }
}
