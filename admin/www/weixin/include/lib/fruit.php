<?php

/**
 * Created by PhpStorm.
 * User: huangxiufeng
 * Date: 16/8/18
 * Time: 下午4:04
 */
class FruitMachine extends BaseMachine
{
    protected static $_instance;

    /**
     * @var gamer
     */
    public $gamer = null;


    public $user = [];

    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * @var redis
     */
    public $redis = null;

    /**
     * @return null
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * @param null $redis
     */
    public function setRedis($redis)
    {
        $this->redis = $redis;
        return $this;
    }


    public static function GetInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new FruitMachine();
        }
        return self::$_instance;
    }


    public function printf($msg)
    {
//        $pid = isset($this->player) ? $this->player->getId() : '0';
//        Gamer::GetInstance()->log("FRUIT $pid $msg");

        $uid = $this->user['uid'];
        _LOG("FRUIT $uid $msg");
    }

    public function getUserKey()
    {
        return 'userinfo:' . $this->user['uid'];
    }

    public function setConfig()
    {
        // Gamer::GetInstance()->getDdzRedis()->get('fruit:config');
        $this->asd();
    }

    public function asd()
    {

        //define('fruit_test', true);

        $this->newbie_round_min = 2;//新手随机轮次
        $this->newbie_round_max = 5;//新手随机轮次
        $this->interference_round_min = 20;//干涉随机轮次
        $this->interference_round_max = 30;//干涉随机轮次

        $this->interference_func = function ($recovery) {
            $pool = [];
            if ($recovery > 100 && $recovery <= 400) {
                $pool = [1, 2, 3, 4, 5, 6];
            } elseif ($recovery > 500 && $recovery <= 900) {
                $pool = [1, 2, 3, 4, 5, 6];
            } elseif ($recovery > 1000 && $recovery <= 4999) {
                $pool = [1, 2, 3, 4, 5, 6, 8, 9];
            } elseif ($recovery > 5000 && $recovery <= 10000) {
                $pool = [1, 2, 3, 4, 5, 6, 8, 9, 10];
            } elseif ($recovery > 10000) {
                $pool = [1, 2, 3, 4, 5, 6, 8, 9, 10, 12];
            }
            return $pool;
        };

        $difficulties_config = [
            0.3, 0.25, 0.20, 0.15, 0.10, 0.05, 0, -0.05, -0.10, -0.15, -0.20, -0.25, -0.30 - 0.35, -0.4, -0.45
        ];
        //下分配置
        $bets_config = [
            0 => ['name' => 'BAR', 'cells' => [4, 3], 'rates' => [4 => 50, 3 => 25]],
            1 => ['name' => '77', 'cells' => [16, 15], 'rates' => [16 => 20, 15 => 2]],
            2 => ['name' => '双星', 'cells' => [20, 21], 'rates' => [20 => 20, 21 => 2]],
            3 => ['name' => '西瓜', 'cells' => [8, 9], 'rates' => [8 => 20, 9 => 2]],
            4 => ['name' => '铃铛', 'cells' => [2, 14, 24], 'rates' => [2 => 10, 14 => 10, 24 => 2]],
            5 => ['name' => '芒果', 'cells' => [7, 19, 18], 'rates' => [7 => 10, 19 => 10, 18 => 2]],
            6 => ['name' => '橘子', 'cells' => [1, 13, 12], 'rates' => [1 => 10, 13 => 10, 12 => 2]],
            7 => ['name' => '苹果', 'cells' => [5, 11, 17, 23, 6], 'rates' => [5 => 5, 11 => 5, 17 => 5, 23 => 5, 6 => 2]]
        ];
        //格子配置
        $cells_config = [
            ['index' => 1, 'name' => '桔子_1', 'weight' => 10],
            ['index' => 2, 'name' => '铃铛_2', 'weight' => 10],
            ['index' => 3, 'name' => '小BAR_3', 'weight' => 4],
            ['index' => 4, 'name' => 'BAR_4', 'weight' => 1],
            ['index' => 5, 'name' => '苹果_5', 'weight' => 10],
            ['index' => 6, 'name' => '小苹果_6', 'weight' => 50],
            ['index' => 7, 'name' => '芒果_7', 'weight' => 10],
            ['index' => 8, 'name' => '西瓜_8', 'weight' => 10],
            ['index' => 9, 'name' => '小西瓜_9', 'weight' => 50],
            ['index' => 10, 'name' => 'LUCK_10', 'weight' => 35],
            ['index' => 11, 'name' => '苹果_11', 'weight' => 10],
            ['index' => 12, 'name' => '小桔子_12', 'weight' => 50],
            ['index' => 13, 'name' => '桔子_13', 'weight' => 10],
            ['index' => 14, 'name' => '铃铛_14', 'weight' => 10],
            ['index' => 15, 'name' => '小77_15', 'weight' => 50],
            ['index' => 16, 'name' => '77_16', 'weight' => 10],
            ['index' => 17, 'name' => '苹果_17', 'weight' => 10],
            ['index' => 18, 'name' => '小芒果_18', 'weight' => 50],
            ['index' => 19, 'name' => '芒果_19', 'weight' => 10],
            ['index' => 20, 'name' => '双星_20', 'weight' => 10],
            ['index' => 21, 'name' => '小双星_21', 'weight' => 50],
            ['index' => 22, 'name' => 'LUCK_22', 'weight' => 35],
            ['index' => 23, 'name' => '苹果_23', 'weight' => 10],
            ['index' => 24, 'name' => '小铃铛_24', 'weight' => 50],
            ['index' => 25, 'name' => '幸运灯_25', 'weight' => 35]
        ];

        $big_gift_str = 'type	name	stopid	cells	weight	难度0-16	动画1-5
10	ZHSH_D7a	1	"2,3,4,5,6,7"	89	0	4
10	ZHSH_D7c	13	"14,15,16,17,18,19"	169	0	4
1	DSY	8	"16,20"	167	0	1
2	XSY_a	1	"7,14"	333	0	1
2	XSY_b	7	"2,1"	333	0	1
2	XSY_c	1	"19,14"	333	0	1
2	XSY_d	19	"2,1"	333	0	1
2	XSY_e	7	"14,13"	333	0	1
2	XSY_f	13	"7,2"	333	0	1
2	XSY_g	19	"14,13"	333	0	1
2	XSY_h	2	"13,19"	333	0	1
3	DSX	5	"11,17,23"	500	0	1
4	XSX	12	"18,21,24"	1250	0	1
5	SD_D101a	0	"1,3"	286	0	1
5	SD_D101b	0	"1,4"	167	0	1
5	SD_D101c	0	"1,6"	833	0	1
5	SD_D101d	0	"1,8"	333	0	1
5	SD_D101e	0	"1,9"	833	0	1
5	SD_D101f	0	"1,12"	833	0	1
5	SD_D101g	0	"1,13"	500	0	1
5	SD_D101h	0	"1,14"	500	0	1
5	SD_D101i	0	"1,15"	833	0	1
5	SD_D101j	0	"1,16"	333	0	1
5	SD_D101k	0	"1,17"	667	0	1
5	SD_D101l	0	"1,18"	833	0	1
5	SD_D101m	0	"1,19"	500	0	1
5	SD_D101n	0	"1,20"	333	0	1
5	SD_D101o	0	"1,21"	833	0	1
5	SD_D102a	0	"2,4"	167	0	1
5	SD_D102b	0	"2,6"	833	0	1
5	SD_D102c	0	"2,8"	333	0	1
5	SD_D102d	0	"2,9"	833	0	1
5	SD_D102e	0	"2,11"	667	0	1
5	SD_D102f	0	"2,12"	833	0	1
5	SD_D102g	0	"2,13"	500	0	1
5	SD_D102h	0	"2,14"	500	0	1
5	SD_D102i	0	"2,15"	833	0	1
5	SD_D102j	0	"2,16"	333	0	1
5	SD_D102k	0	"2,18"	833	0	1
5	SD_D102l	0	"2,19"	500	0	1
5	SD_D102m	0	"2,20"	333	0	1
5	SD_D102n	0	"2,21"	833	0	1
5	SD_D102o	0	"2,23"	667	0	1
5	SD_D102p	0	"2,24"	833	0	1
5	SD_D103a	0	"3,6"	370	0	1
5	SD_D103b	0	"3,8"	222	0	1
5	SD_D103c	0	"3,9"	370	0	1
5	SD_D103d	0	"3,11"	333	0	1
5	SD_D103e	0	"3,12"	370	0	1
5	SD_D103f	0	"3,13"	286	0	1
5	SD_D103g	0	"3,14"	286	0	1
5	SD_D103h	0	"3,15"	370	0	1
5	SD_D103i	0	"3,16"	222	0	1
5	SD_D103j	0	"3,17"	333	0	1
5	SD_D103k	0	"3,18"	370	0	1
5	SD_D103l	0	"3,19"	286	0	1
5	SD_D103m	0	"3,20"	222	0	1
5	SD_D103n	0	"3,21"	370	0	1
5	SD_D103o	0	"3,23"	333	0	1
5	SD_D103p	0	"3,24"	370	0	1
5	SD_D104a	0	"4,6"	192	0	1
5	SD_D104b	0	"4,8"	143	0	1
5	SD_D104c	0	"4,9"	192	0	1
5	SD_D104d	0	"4,11"	182	0	1
5	SD_D104e	0	"4,12"	192	0	1
5	SD_D104f	0	"4,13"	167	0	1
5	SD_D104g	0	"4,14"	167	0	1
5	SD_D104h	0	"4,15"	192	0	1
5	SD_D104i	0	"4,16"	143	0	1
5	SD_D104j	0	"4,18"	192	0	1
5	SD_D104k	0	"4,19"	167	0	1
5	SD_D104l	0	"4,20"	143	0	1
5	SD_D104m	0	"4,21"	192	0	1
5	SD_D104n	0	"4,23"	182	0	1
5	SD_D104o	0	"4,24"	192	0	1
5	SD_D105a	0	"5,8"	400	0	1
5	SD_D105b	0	"5,9"	1429	0	1
5	SD_D105c	0	"5,11"	1000	0	1
5	SD_D105d	0	"5,12"	1429	0	1
5	SD_D105e	0	"5,13"	667	0	1
5	SD_D105f	0	"5,14"	667	0	1
5	SD_D105g	0	"5,15"	1429	0	1
5	SD_D105h	0	"5,16"	400	0	1
5	SD_D105i	0	"5,17"	1000	0	1
5	SD_D105j	0	"5,18"	1429	0	1
5	SD_D105k	0	"5,19"	667	0	1
5	SD_D105l	0	"5,20"	400	0	1
5	SD_D105m	0	"5,21"	1429	0	1
5	SD_D105n	0	"5,23"	1000	0	1
5	SD_D105o	0	"5,24"	1429	0	1
5	SD_D106a	0	"6,8"	455	0	1
5	SD_D106b	0	"6,9"	2500	0	1
5	SD_D106c	0	"6,11"	1429	0	1
5	SD_D106d	0	"6,12"	2500	0	1
5	SD_D106e	0	"6,13"	833	0	1
5	SD_D106f	0	"6,14"	833	0	1
5	SD_D106g	0	"6,15"	2500	0	1
5	SD_D106h	0	"6,16"	455	0	1
5	SD_D106i	0	"6,17"	1429	0	1
5	SD_D106j	0	"6,18"	2500	0	1
5	SD_D106k	0	"6,19"	833	0	1
5	SD_D106l	0	"6,20"	455	0	1
5	SD_D106m	0	"6,21"	2500	0	1
5	SD_D106n	0	"6,23"	1429	0	1
5	SD_D106o	0	"6,24"	2500	0	1
5	SD_D107a	0	"7,9"	833	0	1
5	SD_D107b	0	"7,11"	667	0	1
5	SD_D107c	0	"7,12"	833	0	1
5	SD_D107d	0	"7,13"	500	0	1
5	SD_D107e	0	"7,14"	500	0	1
5	SD_D107f	0	"7,15"	833	0	1
5	SD_D107g	0	"7,16"	333	0	1
5	SD_D107h	0	"7,17"	667	0	1
5	SD_D107i	0	"7,18"	833	0	1
5	SD_D107j	0	"7,19"	500	0	1
5	SD_D107k	0	"7,20"	333	0	1
5	SD_D107l	0	"7,21"	833	0	1
5	SD_D107m	0	"7,23"	667	0	1
5	SD_D107n	0	"7,24"	833	0	1
5	SD_D108a	0	"8,11"	400	0	1
5	SD_D108b	0	"8,12"	455	0	1
5	SD_D108c	0	"8,13"	333	0	1
5	SD_D108d	0	"8,14"	333	0	1
5	SD_D108e	0	"8,15"	455	0	1
5	SD_D108f	0	"8,16"	250	0	1
5	SD_D108g	0	"8,17"	400	0	1
5	SD_D108h	0	"8,18"	455	0	1
5	SD_D108i	0	"8,19"	333	0	1
5	SD_D108j	0	"8,20"	250	0	1
5	SD_D108k	0	"8,21"	455	0	1
5	SD_D108l	0	"8,23"	400	0	1
5	SD_D108m	0	"8,24"	455	0	1
5	SD_D109a	0	"9,11"	1429	0	1
5	SD_D109b	0	"9,12"	2500	0	1
5	SD_D109c	0	"9,13"	833	0	1
5	SD_D109d	0	"9,14"	833	0	1
5	SD_D109e	0	"9,15"	2500	0	1
5	SD_D109f	0	"9,16"	455	0	1
5	SD_D109g	0	"9,17"	1429	0	1
5	SD_D109h	0	"9,18"	2500	0	1
5	SD_D109i	0	"9,19"	833	0	1
5	SD_D109j	0	"9,20"	455	0	1
5	SD_D109k	0	"9,21"	2500	0	1
5	SD_D109l	0	"9,23"	1429	0	1
5	SD_D109m	0	"9,24"	2500	0	1
5	SD_D112a	0	"12,14"	833	0	1
5	SD_D112b	0	"12,15"	2500	0	1
5	SD_D112c	0	"12,16"	455	0	1
5	SD_D112d	0	"12,17"	1429	0	1
5	SD_D112e	0	"12,18"	2500	0	1
5	SD_D112f	0	"12,19"	833	0	1
5	SD_D112g	0	"12,20"	455	0	1
5	SD_D112h	0	"12,21"	2500	0	1
5	SD_D112i	0	"12,23"	1429	0	1
5	SD_D112j	0	"12,24"	2500	0	1
5	SD_D115a	0	"15,17"	1429	0	1
5	SD_D115b	0	"15,18"	2500	0	1
5	SD_D115c	0	"15,19"	833	0	1
5	SD_D115d	0	"15,20"	455	0	1
5	SD_D115e	0	"15,21"	2500	0	1
5	SD_D115f	0	"15,23"	1429	0	1
5	SD_D115g	0	"15,24"	2500	0	1
5	SD_D116a	0	"16,18"	455	0	1
5	SD_D116b	0	"16,19"	333	0	1
5	SD_D116c	0	"16,20"	250	0	1
5	SD_D116d	0	"16,21"	455	0	1
5	SD_D116e	0	"16,23"	400	0	1
5	SD_D116f	0	"16,24"	455	0	1
5	SD_D118a	0	"18,20"	455	0	1
5	SD_D118b	0	"18,21"	2500	0	1
5	SD_D118c	0	"18,23"	1429	0	1
5	SD_D118d	0	"18,24"	2500	0	1
5	SD_D120a	0	"20,23"	400	0	1
5	SD_D120b	0	"20,24"	455	0	1
5	SD_D121a	0	"21,23"	1429	0	1
5	SD_D121b	0	"21,24"	2500	0	1
5	SD_D201a	0	"1,8,14"	250	0	1
5	SD_D201b	0	"1,9,15"	714	0	1
5	SD_D201c	0	"1,12,16"	313	0	1
5	SD_D201d	0	"1,13,20"	250	0	1
5	SD_D201e	0	"1,14,21"	455	0	1
5	SD_D201f	0	"1,15,23"	588	0	1
5	SD_D201g	0	"1,16,24"	313	0	1
5	SD_D202a	0	"2,9,15"	714	0	1
5	SD_D202b	0	"2,11,16"	286	0	1
5	SD_D202c	0	"2,12,17"	588	0	1
5	SD_D202d	0	"2,13,18"	455	0	1
5	SD_D202e	0	"2,14,19"	333	0	1
5	SD_D202f	0	"2,15,23"	588	0	1
5	SD_D202g	0	"2,16,24"	313	0	1
5	SD_D203a	0	"3,9,13"	270	0	1
5	SD_D203b	0	"3,11,14"	250	0	1
5	SD_D203c	0	"3,12,17"	313	0	1
5	SD_D203d	0	"3,13,18"	270	0	1
5	SD_D203e	0	"3,14,19"	222	0	1
5	SD_D204a	0	"4,9,15"	185	0	1
5	SD_D204b	0	"4,12,18"	185	0	1
5	SD_D204c	0	"4,14,21"	161	0	1
5	SD_D204d	0	"4,17,24"	175	0	1
5	SD_D205a	0	"5,8,14"	286	0	1
5	SD_D205b	0	"5,9,15"	1111	0	1
5	SD_D205c	0	"5,12,16"	370	0	1
5	SD_D205d	0	"5,14,20"	286	0	1
5	SD_D205e	0	"5,18,24"	1111	0	1
5	SD_D206a	0	"6,9,14"	714	0	1
5	SD_D206b	0	"6,11,15"	1111	0	1
5	SD_D206c	0	"6,12,16"	417	0	1
5	SD_D206d	0	"6,14,21"	714	0	1
5	SD_D206e	0	"6,18,24"	1667	0	1
5	SD_D207a	0	"7,9,15"	714	0	1
5	SD_D207b	0	"7,11,16"	286	0	1
5	SD_D207c	0	"7,12,21"	714	0	1
5	SD_D207d	0	"7,14,23"	400	0	1
5	SD_D207e	0	"7,17,24"	588	0	1
5	SD_D208a	0	"8,11,15"	370	0	1
5	SD_D208b	0	"8,12,16"	238	0	1
5	SD_D208c	0	"8,13,20"	200	0	1
5	SD_D208d	0	"8,14,21"	313	0	1
5	SD_D208e	0	"8,17,24"	370	0	1
5	SD_D209a	0	"9,12,17"	1111	0	1
5	SD_D209b	0	"9,13,18"	714	0	1
5	SD_D209c	0	"9,14,19"	455	0	1
5	SD_D209d	0	"9,17,21"	1111	0	1
5	SD_D209e	0	"9,18,23"	1111	0	1
5	SD_D209f	0	"9,19,24"	714	0	1
5	SD_D212a	0	"12,17,21"	1111	0	1
5	SD_D212b	0	"12,18,23"	1111	0	1
5	SD_D212c	0	"12,19,24"	714	0	1
5	SD_D213a	0	"13,18,21"	714	0	1
5	SD_D213b	0	"13,19,23"	400	0	1
5	SD_D213c	0	"13,20,24"	313	0	1
5	SD_D214a	0	"14,18,21"	714	0	1
5	SD_D214b	0	"14,19,23"	400	0	1
5	SD_D214c	0	"14,20,24"	313	0	1
5	SD_D217a	0	"17,19,23"	500	0	1
5	SD_D217b	0	"17,21,24"	1111	0	1
5	SD_D218a	0	"18,20,23"	370	0	1
5	SD_D218b	0	"18,21,24"	1667	0	1
5	SD_D219a	0	"19,21,23"	588	0	1
5	SD_D219b	0	"19,21,24"	714	0	1
5	SD_D301a	0	"1,5,9,15"	526	0	1
5	SD_D301b	0	"1,7,11,16"	222	0	1
5	SD_D301c	0	"1,8,12,18"	294	0	1
5	SD_D301d	0	"1,9,14,20"	238	0	1
5	SD_D301e	0	"1,11,15,21"	526	0	1
5	SD_D301f	0	"1,12,17,24"	526	0	1
5	SD_D302a	0	"2,6,11,16"	270	0	1
5	SD_D302b	0	"2,7,12,17"	370	0	1
5	SD_D302c	0	"2,8,13,18"	238	0	1
5	SD_D302d	0	"2,9,14,20"	238	0	1
5	SD_D302e	0	"2,11,15,21"	526	0	1
5	SD_D302f	0	"2,12,16,23"	270	0	1
5	SD_D302g	0	"2,13,17,24"	370	0	1
5	SD_D303a	0	"3,7,12,17"	238	0	1
5	SD_D303b	0	"3,8,13,18"	175	0	1
5	SD_D303c	0	"3,9,14,19"	213	0	1
5	SD_D303d	0	"3,11,15,20"	192	0	1
5	SD_D303e	0	"3,12,16,21"	204	0	1
5	SD_D303f	0	"3,13,17,24"	238	0	1
5	SD_D304a	0	"4,8,12,18"	135	0	1
5	SD_D304b	0	"4,9,13,19"	139	0	1
5	SD_D304c	0	"4,11,14,20"	118	0	1
5	SD_D304d	0	"4,12,15,21"	179	0	1
5	SD_D304e	0	"4,13,17,24"	149	0	1
5	SD_D305a	0	"5,8,13,18"	270	0	1
5	SD_D305b	0	"5,9,14,19"	370	0	1
5	SD_D305c	0	"5,11,15,20"	313	0	1
5	SD_D305d	0	"5,12,16,21"	345	0	1
5	SD_D305e	0	"5,13,17,24"	455	0	1
5	SD_D306a	0	"6,9,13,19"	417	0	1
5	SD_D306b	0	"6,11,14,20"	270	0	1
5	SD_D306c	0	"6,12,15,21"	1250	0	1
5	SD_D306d	0	"6,13,16,24"	294	0	1
5	SD_D307a	0	"7,9,14,19"	313	0	1
5	SD_D307b	0	"7,11,15,20"	270	0	1
5	SD_D307c	0	"7,12,16,21"	294	0	1
5	SD_D307d	0	"7,13,17,24"	370	0	1
5	SD_D308a	0	"8,11,14,20"	182	0	1
5	SD_D308b	0	"8,12,15,21"	385	0	1
5	SD_D308c	0	"8,13,16,24"	192	0	1
5	SD_D309a	0	"9,12,15,20"	385	0	1
5	SD_D309b	0	"9,13,16,21"	294	0	1
5	SD_D309c	0	"9,14,17,24"	526	0	1
5	SD_D312a	0	"12,15,18,20"	385	0	1
5	SD_D312b	0	"12,16,19,21"	294	0	1
5	SD_D312c	0	"12,17,20,24"	345	0	1
5	SD_D401a	0	"1,5,9,15,20"	256	0	11
5	SD_D401b	0	"1,7,11,16,21"	213	0	11
5	SD_D401c	0	"1,8,12,18,23"	256	0	11
5	SD_D401d	0	"1,9,14,20,24"	227	0	11
5	SD_D402a	0	"2,5,9,15,20"	256	0	11
5	SD_D402b	0	"2,7,11,16,21"	213	0	11
5	SD_D402c	0	"2,8,12,18,23"	256	0	11
5	SD_D402d	0	"2,9,13,20,24"	227	0	11
5	SD_D403a	0	"3,5,8,14,18"	161	0	11
5	SD_D403b	0	"3,7,11,15,19"	192	0	11
5	SD_D403c	0	"3,8,12,18,21"	196	0	11
5	SD_D403d	0	"3,9,13,20,23"	161	0	11
5	SD_D403e	0	"3,11,14,21,24"	227	0	11
5	SD_D404a	0	"4,6,9,13,17"	145	0	11
5	SD_D404b	0	"4,7,11,15,19"	130	0	11
5	SD_D404c	0	"4,8,12,18,21"	132	0	11
5	SD_D404d	0	"4,9,13,20,23"	115	0	11
5	SD_D404e	0	"4,11,14,21,24"	145	0	11
5	SD_D405a	0	"5,7,11,14,18"	313	0	11
5	SD_D405b	0	"5,8,12,15,19"	256	0	11
5	SD_D405c	0	"5,9,13,18,21"	476	0	11
5	SD_D405d	0	"5,11,15,20,23"	270	0	11
5	SD_D405e	0	"5,12,16,21,24"	323	0	11
5	SD_D406a	0	"6,8,11,14,18"	256	0	11
5	SD_D406b	0	"6,9,12,15,19"	556	0	11
5	SD_D406c	0	"6,11,13,16,21"	256	0	11
5	SD_D406d	0	"6,12,17,21,24"	769	0	11
5	SD_D406e	0	"6,13,18,20,23"	256	0	11
5	SD_D407a	0	"7,9,12,15,19"	385	0	11
5	SD_D407b	0	"7,11,13,16,21"	213	0	11
5	SD_D407c	0	"7,12,17,21,24"	476	0	11
5	SD_D407d	0	"7,13,18,20,23"	213	0	11
5	SD_D408a	0	"8,11,13,16,21"	175	0	11
5	SD_D408b	0	"8,12,17,21,24"	323	0	11
5	SD_D408c	0	"8,13,18,20,23"	175	0	11
5	SD_D409a	0	"9,11,13,15,20"	256	0	11
5	SD_D409b	0	"9,12,16,21,24"	357	0	11
5	SD_D409c	0	"9,13,18,20,23"	256	0	11
5	SD_D409d	0	"9,14,19,21,24"	385	0	11
5	SD_D412a	0	"12,14,16,19,23"	213	0	11
5	SD_D412b	0	"12,15,18,20,24"	357	0	11
5	SD_D412c	0	"12,16,18,21,23"	323	0	11
5	SD_D412d	0	"12,17,19,21,24"	476	0	11
5	SD_D413a	0	"13,15,18,20,24"	278	0	11
5	SD_D413b	0	"13,16,18,21,23"	256	0	11
5	SD_D413c	0	"13,17,19,21,24"	345	0	11
5	SD_D414a	0	"14,16,18,20,23"	175	0	11
5	SD_D414b	0	"14,17,19,21,24"	345	0	11
5	SD_D415a	0	"15,17,19,21,24"	476	0	11
6	LLDS_D501a	0	"1,5,9,12,15,20"	244	0	11
6	LLDS_D501b	0	"1,7,11,16,18,21"	204	0	11
6	LLDS_D501c	0	"1,8,12,14,18,23"	204	0	11
6	LLDS_D501d	0	"1,9,14,17,20,24"	204	0	11
6	LLDS_D502a	0	"2,6,8,11,15,18"	244	0	11
6	LLDS_D502b	0	"2,7,9,12,14,20"	185	0	11
6	LLDS_D502c	0	"2,8,11,13,18,21"	204	0	11
6	LLDS_D502d	0	"2,9,14,19,20,24"	185	0	11
6	LLDS_D503a	0	"3,5,9,12,15,20"	179	0	11
6	LLDS_D503b	0	"3,7,11,16,18,21"	156	0	11
6	LLDS_D503c	0	"3,8,12,14,18,23"	156	0	11
6	LLDS_D503d	0	"3,9,14,17,20,24"	156	0	11
6	LLDS_D504a	0	"4,6,8,11,15,18"	123	0	11
6	LLDS_D504b	0	"4,7,9,12,14,20"	106	0	11
6	LLDS_D504c	0	"4,8,11,13,18,21"	112	0	11
6	LLDS_D504d	0	"4,9,14,19,20,24"	106	0	11
11	KHC_301a	0	"3,2,1"	222	0	3
11	KHC_301b	0	"4,3,2"	118	0	3
11	KHC_301c	0	"5,4,3"	125	0	3
11	KHC_301d	0	"6,5,4"	175	0	3
11	KHC_301e	0	"7,6,5"	588	0	3
11	KHC_301f	0	"8,7,6"	313	0	3
11	KHC_301g	0	"9,8,7"	313	0	3
11	KHC_301h	0	"13,12,11"	588	0	3
11	KHC_301i	0	"14,13,12"	455	0	3
11	KHC_301j	0	"15,14,13"	455	0	3
11	KHC_301k	0	"16,15,14"	313	0	3
11	KHC_301l	0	"17,16,15"	370	0	3
11	KHC_301m	0	"18,17,16"	370	0	3
11	KHC_301n	0	"19,18,17"	588	0	3
11	KHC_301o	0	"20,19,18"	313	0	3
11	KHC_301p	0	"21,20,19"	313	0	3
11	KHC_301q	0	"1,24,23"	588	0	3
11	KHC_301r	0	"2,1,24"	455	0	3
11	KHC_401a	0	"4,3,2,1"	105	0	3
11	KHC_401b	0	"5,4,3,2"	111	0	3
11	KHC_401c	0	"6,5,4,3"	122	0	3
11	KHC_401d	0	"7,6,5,4"	149	0	3
11	KHC_401e	0	"8,7,6,5"	270	0	3
11	KHC_401f	0	"9,8,7,6"	294	0	3
11	KHC_401g	0	"14,13,12,11"	370	0	3
11	KHC_401h	0	"15,14,13,12"	417	0	3
11	KHC_401i	0	"16,15,14,13"	238	0	3
11	KHC_401j	0	"17,16,15,14"	270	0	3
11	KHC_401k	0	"18,17,16,15"	345	0	3
11	KHC_401l	0	"19,18,17,16"	270	0	3
11	KHC_401m	0	"20,19,18,17"	270	0	3
11	KHC_401n	0	"21,20,19,18"	294	0	3
11	KHC_401o	0	"2,1,24,23"	370	0	3
11	KHC_401p	0	"3,2,1,24"	213	0	3
11	KHC_501a	0	"5,4,3,2,1"	100	0	3
11	KHC_501b	0	"6,5,4,3,2"	109	0	3
11	KHC_501c	0	"7,6,5,4,3"	109	0	3
11	KHC_501d	0	"8,7,6,5,4"	115	0	3
11	KHC_501e	0	"9,8,7,6,5"	256	0	3
11	KHC_501f	0	"15,14,13,12,11"	345	0	3
11	KHC_501g	0	"16,15,14,13,12"	227	0	3
11	KHC_501h	0	"17,16,15,14,13"	213	0	3
11	KHC_501i	0	"18,17,16,15,14"	256	0	3
11	KHC_501j	0	"19,18,17,16,15"	256	0	3
11	KHC_501k	0	"20,19,18,17,16"	175	0	3
11	KHC_501l	0	"21,20,19,18,17"	256	0	3
11	KHC_501m	0	"3,2,1,24,23"	192	0	3
11	KHC_501n	0	"4,3,2,1,24"	103	0	3
13	CHID10	10		13000	0	7
13	CHID22	22		13000	0	7
7	TNSH_D601a	0	"1,5,7,9,12,15,20"	196	0	2
7	TNSH_D601b	0	"1,6,8,11,13,16,21"	145	0	2
7	TNSH_D601c	0	"1,7,9,12,17,20,23"	185	0	2
7	TNSH_D601d	0	"1,8,11,13,18,21,24"	196	0	2
7	TNSH_D602a	0	"2,5,7,9,12,15,20"	196	0	2
7	TNSH_D602b	0	"2,6,8,11,13,16,21"	145	0	2
7	TNSH_D602c	0	"2,7,9,12,14,20,23"	169	0	2
7	TNSH_D602d	0	"2,8,11,13,18,21,24"	196	0	2
7	TNSH_D603a	0	"3,5,7,9,12,15,20"	152	0	2
7	TNSH_D603b	0	"3,6,8,11,13,16,21"	119	0	2
7	TNSH_D603c	0	"3,7,9,12,14,20,23"	135	0	2
7	TNSH_D603d	0	"3,8,11,13,18,21,24"	152	0	2
7	TNSH_D604a	0	"4,6,8,11,13,16,21"	92	0	2
7	TNSH_D604b	0	"4,7,9,12,17,20,23"	106	0	2
7	TNSH_D604c	0	"4,8,11,13,18,21,24"	110	0	2
8	TLBB_D701a	0	"1,5,7,9,12,15,17,20"	179	0	11
8	TLBB_D701b	0	"1,6,8,11,13,16,18,21"	141	0	11
8	TLBB_D701c	0	"1,7,9,12,15,17,20,23"	179	0	11
8	TLBB_D701d	0	"1,8,11,13,16,18,21,24"	141	0	11
8	TLBB_D702a	0	"2,5,7,9,12,15,17,20"	179	0	11
8	TLBB_D702b	0	"2,6,8,11,13,16,18,21"	141	0	11
8	TLBB_D702c	0	"2,7,9,12,15,17,20,23"	179	0	11
8	TLBB_D702d	0	"2,8,11,13,16,18,21,24"	141	0	11
8	TLBB_D703a	0	"3,5,7,9,12,15,17,20"	141	0	11
8	TLBB_D703b	0	"3,6,8,11,13,16,18,21"	116	0	11
8	TLBB_D703c	0	"3,7,9,12,15,17,20,23"	141	0	11
8	TLBB_D703d	0	"3,8,11,13,16,18,21,24"	116	0	11
8	TLBB_D703a	0	"4,6,8,11,13,16,18,21"	90	0	11
8	TLBB_D703b	0	"4,7,9,12,15,17,20,23"	104	0	11
8	TLBB_D703c	0	"4,8,11,13,16,18,21,24"	90	0	11
9	JLBD_D801a	0	"1,5,7,9,11,13,15,17,20"	145	0	11
9	JLBD_D801b	0	"1,6,8,11,13,16,18,21,24"	137	0	11
9	JLBD_D801c	0	"1,7,9,12,15,17,18,20,23"	172	0	11
9	JLBD_D802a	0	"2,5,7,9,11,14,15,17,20"	145	0	11
9	JLBD_D802b	0	"2,6,8,11,13,16,18,21,24"	137	0	11
9	JLBD_D802c	0	"2,7,9,12,15,17,18,20,23"	172	0	11
9	JLBD_D803a	0	"3,5,7,9,11,13,15,17,20"	119	0	11
9	JLBD_D803b	0	"3,6,8,11,13,16,18,21,24"	114	0	11
9	JLBD_D803c	0	"3,7,9,12,15,17,18,20,23"	137	0	11
9	JLBD_D804a	0	"4,6,8,11,13,16,18,21,24"	88	0	11
9	JLBD_D804b	0	"4,7,9,12,15,17,18,20,23"	102	0	11
12	DMG	0	"1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24"	44	0	5
';
        $this->formatConfig($bets_config, $cells_config, $difficulties_config, $big_gift_str);
    }

    public function log($table, $data)
    {
//        $data['time'] = time();
//        $data['logday'] = date('Y-m-d');
//        $data['logdate'] = date('Y-m-d H:i:s');
//        $data['playerId'] = isset($this->player) ? $this->player->getId() : 0;
//        $data['playerName'] = isset($this->player) ? $this->player->getNickname() : '';
//        $data['ip'] = isset($this->player) ? $this->player->getIp() : '0.0.0.0';
//        $data['firstday'] = isset($this->player) ? date('Y-m-d', $this->player->getFruitFirstTime()) : '1970-01-01';
//
//        if ($table == 'log_fruit_bet') {
//            $data['persisRound'] = $this->player->getFruitPersistRound();
//            $data['profit'] = $this->player->getFruitTotalBet() - $this->player->getFruitTotalWin();
//        }
//
//        return Gamer::GetInstance()->getServer()->task(json_encode([
//            "task" => "MysqlInsert",
//            "arg"  => [
//                "table_name" => $table,
//                "fields"     => $data
//            ]
//        ]));
    }

    public function getSystemProfit()
    {
        return $this->redis->get('fruit:system:profit'); //Gamer::GetInstance()->getDdzRedis()->get('fruit:system:profit');
    }

    public function getSystemLose()
    {
        return $this->redis->get('fruit:system:lose');//Gamer::GetInstance()->getDdzRedis()->get('fruit:system:lose');
    }

    public function getSystemPool()
    {
        return $this->redis->get('fruit:system:pool');//Gamer::GetInstance()->getDdzRedis()->get('fruit:system:pool');
    }

    public function getSystemPoolExt()
    {
        return $this->redis->get('fruit:system:pool_ext');// Gamer::GetInstance()->getDdzRedis()->get('fruit:system:pool_ext');
    }

    public function changeSystemProfit($value)
    {
        return floatval($this->redis->get('fruit:system:profit'));// floatval(Gamer::GetInstance()->getDdzRedis()->incrbyfloat('fruit:system:profit', $value));
    }

    public function changeSystemLose($value)
    {
        return floatval($this->redis->get('fruit:system:lose')); //floatval(Gamer::GetInstance()->getDdzRedis()->incrbyfloat('fruit:system:lose', $value));
    }

    public function changeSystemPool($value)
    {
        return floatval($this->redis->incrByFloat('fruit:system:pool', $value)); //floatval(Gamer::GetInstance()->getDdzRedis()->incrbyfloat('fruit:system:pool', $value));
    }

    public function changeSystemPoolExt($value)
    {
        return floatval($this->redis->incrByFloat('fruit:system:pool_ext', $value)); //floatval(Gamer::GetInstance()->getDdzRedis()->incrbyfloat('fruit:system:pool_ext', $value));
    }

    public function getRound()
    {
        return intval($this->redis->hGet($this->getUserKey(), 'round'));
    }

    public function incrRound()
    {
//        $this->player->setFruitRound($this->player->getFruitRound() + 1);
//        Gamer::GetInstance()->em->persist($this->player)->flush();
        return intval($this->redis->hIncrBy($this->getUserKey(), 'round', 1));

    }

    public function clearRound()
    {
        /* $this->player->setFruitRound(0);
         Gamer::GetInstance()->em->persist($this->player)->flush();*/
        $this->redis->hSet($this->getUserKey(), 'round', 0);
        return 0;
    }

    public function getCredit()
    {
        return intval($this->redis->hGet($this->getUserKey(), 'gold'));

        //return $this->player->getFruitBet();
    }

    public function changeCredit($value)
    {
//        $this->player->setFruitBet($this->player->getFruitBet() + $value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();

        return intval($this->redis->hIncrBy($this->getUserKey(), 'gold', $value));

    }

    public function getCurrRoundWin()
    {
        ///return $this->player->getFruitWin();
        return intval($this->redis->hGet($this->getUserKey(), 'curr_win'));
    }

    public function setCurrRoundWin($value)
    {
//        $this->player->setFruitWin($this->player->getFruitWin() + $value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();
        return $this->redis->hIncrBy($this->getUserKey(), 'curr_win', $value);
    }


    public function sendToFd($type, array $value)
    {
        switch ($type) {
            case 'animation':
                $opcode = 100078;

                $roomId = $this->user['roomId'];
                //$this->printf('roomId' . $roomId);

                $config = gamer::$rooms[$roomId];

                if (in_array($value['result']['index'], [10, 22, 25]) || in_array($value['result']['stop_id'], [10, 22, 25])) {

                    $win = $value['result']['win'];

                    switch ($roomId) {
                        case 1:
                            if ($win >= 1 && $win <= 49) {
                                $ticket = 1;
                            }
                            break;
                        case 2:
                            if ($win >= 10 && $win <= 499) {
                                $ticket = 2;
                            }
                            break;
                        case 3:
                            if ($win >= 100 && $win <= 4999) {
                                $ticket = 10;
                            }
                            break;
                    }

                    if (!isset($ticket)) {
                        $ticket = intval($win * ($config['percent'] / 100));
                    }

                    $ticket_now = $this->redis->hIncrBy("userinfo:" . $this->user['uid'], 'ticket', $ticket);
                    $this->redis->hIncrBy('ticket:' . $this->user['uid'], date('Ymd'), $ticket);

                    $value['result']['ticket'] = $ticket;
                    $value['result']['ticket_now'] = $ticket_now;
                } else {
                    $value['result']['ticket'] = 0;
                    $value['result']['ticket_now'] = 0;
                }

                break;
            case 'merge':
                $opcode = 100080;
                break;
            case 'enter':
                $opcode = 100077;
//                $pay = $this->player->getPayed();
//
//                //if (in_array($this->player->getId(), [10046, 10151, 10085, 10000, 10042, 10083, 10132, 10021, 10155])) {
//                //Gamer::GetInstance()->log('IP:' . $this->player->getIp());
//                if ($this->player->getIp() == '140.207.91.206') {
//                    $pay = 100;
//                }
                $value['result']['pay_amount'] = 100;//$pay;//$this->player->getPayed();
                $value['result']['msg'] = '';//'已帮您把金币1:1兑换成分。若报名其他比赛，报名费将以邮件形式返还。';
                break;
            case 'exit':
                $opcode = 100079;
                break;

        }

        if (!isset($opcode)) {
            throw new \Exception('异常的水果机协议类型');
        }

        // Gamer::GetInstance()->SendToPlayer($this->player, $opcode, $value);
        $this->gamer->server->sendToFd($this->user['fd'], $opcode, $value);
    }

    public function filling($gold)
    {
        parent::filling($gold);
//        if ($this->player->getFruitFirstTime() == 0) {
//            $this->player->setFruitFirstTime(time());
//            Gamer::GetInstance()->em->persist($this->player)->flush();
//        }
        $this->redis->hSet($this->getUserKey(), 'first_time', time());
    }

    public function changePlayerTotalBet($value)
    {
//        $this->player->setFruitTotalBet($this->player->getFruitTotalBet() + $value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();

        return $this->redis->hIncrBy($this->getUserKey(), 'total_bet', $value);
    }

    public function changePlayerTotalWin($value)
    {
//        $this->player->setFruitTotalWin($this->player->getFruitTotalWin() + $value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();

        return $this->redis->hIncrBy($this->getUserKey(), 'total_win', $value);
    }

    public function getPlayerTotalBet()
    {
        //  return $this->player->getFruitTotalBet();
        return intval($this->redis->hGet($this->getUserKey(), 'total_bet'));
    }

    public function getPlayerTotalWin()
    {
        //return $this->player->getFruitTotalWin();
        return intval($this->redis->hGet($this->getUserKey(), 'total_win'));
    }

    public function getPlayerRand()
    {
        //return $this->player->getFruitRand();
        return intval($this->redis->hGet($this->getUserKey(), 'rand'));
    }

    public function setPlayerRand($value)
    {
//        $this->player->setFruitRound($value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();
        return intval($this->redis->hSet($this->getUserKey(), 'rand', $value));
    }

    public function escape()
    {

        $bet = $gold = $this->getCredit();
        $curr_win = $this->getCurrRoundWin();
        if ($curr_win+$this->getCredit() > 0) {
            $bet = $gold = $this->getCurrRoundWin() + $this->getCredit();
            //  $this->changeCredit($this->getCredit() * -1);
            $this->changeCredit($curr_win);
            $this->setCurrRoundWin($curr_win * -1);
        }

        $this->log('log_fruit_exit', [
            'gold' => $gold,
            'bet'  => $bet
        ]);

        $this->sendToFd('exit', [
            "state"  => 0,
            "result" => [
                'gold' => $gold,
                'bet'  => $bet
            ]
        ]);

        $this->clearRound();

        return $gold;


        // $rst = parent::escape();
//
//        Gamer::GetInstance()->task("Landing", [
//            "type"     => LandingTask::PLAYER_UPDATE,
//            "data"     => [
//                'gold'                => $this->player->getGold(),
//                'fruit_total_win'     => $this->player->getFruitTotalWin(),
//                'fruit_total_bet'     => $this->player->getFruitTotalBet(),
//                'fruit_start_time'    => $this->player->getFruitStartTime(),
//                'fruit_first_time'    => $this->player->getFruitFirstTime(),
//                'payed'               => $this->player->getPayed(),
//                'fruit_persist_round' => $this->player->getFruitPersistRound(),
//            ],
//            "playerId" => $this->player->getId()
//        ]);

        //return $rst;
    }

    public function incrPersistRound()
    {
//        $this->player->setFruitPersistRound($this->player->getFruitPersistRound() + 1);
//        Gamer::GetInstance()->em->persist($this->player)->flush();

        return intval($this->redis->hIncrBy($this->getUserKey(), 'persis_round', 1));
    }

    public function getPersistRound()
    {
        //return $this->player->getFruitPersistRound();
        return intval($this->redis->hGet($this->getUserKey(), 'persis_round'));
    }

    public function setInterveneRound($value)
    {
//        $this->printf("设置干预轮次:" . $value);
//        $this->player->setFruitInterveneRound($value);
//        Gamer::GetInstance()->em->persist($this->player)->flush();
        return $this->redis->hSet($this->getUserKey(), 'intervene_round', $value);
    }

    public function getInterveneRound()
    {
//        if ($this->player->getFruitInterveneRound() == 0) {
//            $this->setInterveneRound(rand($this->newbie_round_min, $this->newbie_round_max));
//        }
//        return $this->player->getFruitInterveneRound();

        return intval($this->redis->hGet($this->getUserKey(), 'intervene_round'));
    }

    public function getNewInterveneRound()
    {
        $i = $this->redis->hGet($this->getUserKey(), 'intervene');
        $i = json_decode($i, true);
        if (!is_array($i)) {
            return [];
        } else {
            return $i;
        }
    }

}