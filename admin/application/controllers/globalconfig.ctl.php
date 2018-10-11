<?php

header('Cache-control: private, must-revalidate');

class GlobalConfig_Controller extends Module_Lib
{
    private $mode = null;
    private $channel_mode = null;
    private $ganksdb_mode = null;

    public function __construct($conn = '')
    {
        parent::__construct();
        $conn = Platfrom_Service::getServer(true, 'gank_globaldb');
        $this->mode = new  GlobalConfig_Model($conn);
        $this->channel_mode = new Channel_Model($conn);
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

    public function createConfig()
    {
        $this->load_view("dev/global_config/create");
    }

    public function addConfig()
    {
        $desc = $_POST['desc'];

        $channel_ary = $_POST['channel'];

        $pc = isBlank($_POST['pc_name'])
            ?
            $this->outputJson(FAILURE, '游戏昵称不能为空!')
            :
            $_POST['pc_name'];

        $channel_id = isBlank(implode(',', $channel_ary))
            ?
            $this->outputJson(FAILURE, '渠道不能为空!')
            :
            implode(',', $channel_ary);

        $server_prefix = isBlank($_POST['server_prefix'])
            ?
            $this->outputJson(FAILURE, '区服前缀不能为空!')
            :
            $_POST['server_prefix'];

        $server_min = isBlank($_POST['server_min'])
            ?
            $this->outputJson(FAILURE, '区服起始不能为空!')
            :
            $_POST['server_min'];

        $server_max = isBlank($_POST['server_max'])
            ?
            $this->outputJson(FAILURE, '区服上线不能为空!')
            :
            $_POST['server_max'];

        $activity = $this->byActVerify($_POST['checkbox']);

        log_message::info(json_encode($_POST));

        $time = time();
        $sign = md5($channel_id . $pc . $time);

        $config_out = [
            'pc' => $pc,
            'channel_id' => $channel_id,
            'sign' => $sign,
            'server_prefix' => $server_prefix,
            'server_min' => $server_min,
            'server_max' => $server_max,
            'act_rule' => json_encode($activity, JSON_UNESCAPED_UNICODE),
            'desc' => $desc,
            'create_at' => date('Y-m-d H:i:s', $time)
        ];
        $appid = pinyin::getPinyin($pc);

        $config_out['act_config'] = json_encode($config_out);

        $ret = $this->mode->addConfigInfo($config_out);

        if (!isBlank($ret)) {
            $queret = $this->mode->createAppTable('app_' . $appid . '_user');
            if ($queret == false) {
                $this->outputJson(SUCCESS, 'DB迁移失败!');
            }
            $this->outputJson(SUCCESS, '添加成功!');
        }
        $this->outputJson(FAILURE, '添加失败!');
    }

    public function byActVerify($checkbox = [])
    {
        $data = [];
        $activityAry = $checkbox;
        foreach ($activityAry as $act_key) {
            if ($act_key == 'ranking') {
                if (isBlank($_POST[$act_key . '_starttime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                if (isBlank($_POST[$act_key . '_endtime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                $data['ranking'] = ['start_at' => $_POST[$act_key . '_starttime'], 'end_at' => $_POST[$act_key . '_endtime']];
            }
            if ($act_key == 'lottery') {
                if (isBlank($_POST[$act_key . '_starttime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                if (isBlank($_POST[$act_key . '_endtime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                $data['lottery'] = ['start_at' => $_POST[$act_key . '_starttime'], 'end_at' => $_POST[$act_key . '_endtime']];
            }
            if ($act_key == 'signin') {
                if (isBlank($_POST[$act_key . '_starttime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                if (isBlank($_POST[$act_key . '_endtime'])) {
                    $this->outputJson(FAILURE, '排行榜活动开始时间不能为空!');
                }
                $data['signin'] = ['start_at' => $_POST[$act_key . '_starttime'], 'end_at' => $_POST[$act_key . '_endtime']];
            }
        }
        return $data;
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

    public function loadConfig($id = null)
    {
        $config_str = null;
        $id = isset($_POST['id']) ? $_POST['id'] : $id;
        $config_ary = $this->mode->byConfigIdInfo($id);

        $pc = pinyin::getPinyin($config_ary['pc']);
        $channel_id = explode(',', $config_ary['channel_id']);
        $channel_ary = $this->channel_mode->byChannelIdInfo($channel_id);

        if (isBlank($channel_ary) || isBlank($config_ary)) {
            $this->outputJson(FAILURE, '文件配置信息为空！');
        }
        $channel_name = $pc . '_' . pinyin::getPinyin($channel_ary['channel_name']) . '.json';
        // 写入文件 方案1
        $this->mkdirFile(RES_PATH, $channel_name, $config_ary);
        $pc = $config_ary['pc'];
        //方案2
        if (file_exists(RES_PATH)) {
            $res = $this->request_upload_curls($channel_name);
            $status = (!isBlank($res)) ? json_encode($res)['status'] : null;

            if (isset($status) && $status == SUCCESS) {
                $act_data = array(
                    'act_type' => 1,
                    'config_url' => RES_CONFIG_URL . DIR_SEPARATOR . $channel_name
                );
                $this->mode->editConfig($id, $act_data);

                $this->outputJson(SUCCESS, '上传成功！');
            } else {
                log_message::info(json_encode($res));
                $this->outputJson(SUCCESS, '上传失败！');
            }

        } else {
            log_message::info('file upload false');
            $this->outputJson(SUCCESS, '上传失败！不存在的路径');
        }

    }
}
