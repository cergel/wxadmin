<?php

define("API_MOVIEDATABASE", "http://commoncgi.pre.wepiao.com"); //媒资库接口
define("SERVICE_TYPE", 'dev');
return [
    'redis' => array('host' => '192.168.101.47', 'port' => 6379),
    'wechat' => array(
        'app_id' => 'wx92cf60f7577e2d48',
        'app_secret' => '6347543223aa409d36108565b51edd9a',
        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
    ),
    'weixin_service_record' => array(
        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
        'app_id' => 'wx92cf60f7577e2d48',
        'app_secret' => '6347543223aa409d36108565b51edd9a',
    ),
    // 'baymax_active_java'=>'http://10.66.150.98/baymaxqry?resId=',// java active -- master
    //'baymax_active_java'=>'http://10.104.0.247:8080/baymaxqry?resId=',// java active -- pre
    'baymax_active_java' => 'http://192.168.101.26:8080/baymaxqry?resId=',// java active  -- dev
    'uctent_uid_openid' => '119.29.117.90:8080/uc/v1/getopenidbyuid4dragon',// java接口-用户中心-根据uid获取openid，预上线版本
    'ucenter_api'=>array(//用户中心接口
        'user_info'=>'http://10.104.140.34:8080/uc/v1/getuserinfobyopenid'//通过openid获取用户信息
    ),
    //redis
    'redis_data' => [
        //缓存使用的redis
        'cache' => [
//            'write' => [
//                "host" => "192.168.101.47",
//                "port" => 6379,
//                "database" => 6,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//            ],
            'write' => [
                "host" => "10.104.27.136",
                "port" => 23032,
                "database" => 6,
                "prefix" => "baymax_",
                'timeout' => 10,
                'password' => ''
            ],
            'read' => [
                "host" => "192.168.101.47",
                "port" => 6379,
                "database" => 5,
                "prefix" => "baymax_",
                'timeout' => 10,
            ],
        ],
        //活动cms使用的redis
        'cms_active' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
//                "database" => 3,
                "prefix" => "wx_3_",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 3,
                "prefix" => "wx_3_",
                'timeout' => 10,
            ],
        ],
        'cms_active_new' => [
            'write' => [
                "host" => "192.168.101.47",
                "port" => 6379,
                "database" => 4,
                "prefix" => "mdb_",
                'timeout' => 3,

            ],
            'read' => [
                "host" => "192.168.101.47",
                "port" => 6379,
                "database" => 4,
                "prefix" => "mdb_",
                'timeout' => 3,
            ],
        ],
        'star_active2' => [
            'write' => [
                "host" => "192.168.101.47",
                "port" => 7002,
                "prefix" => "",
                'timeout' => 3,
            ],
            'read' => [
                "host" => "192.168.101.47",
                "port" => 7002,
                "prefix" => "",
                'timeout' => 3,
            ],
        ],
        //movieOrder使用的redis
        'movie_order' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 3,
                "prefix" => "wx_3_",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 3,
                "prefix" => "wx_3_",
                'timeout' => 10,
            ],
        ],
        //尿点
        'pee' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 4,
                "prefix" => "pee_",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 4,
                "prefix" => "pee_",
                'timeout' => 10,
            ],
        ],
        'liveshow' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 6,
                "prefix" => "",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 6,
                "prefix" => "",
                'timeout' => 10,
            ],
        ],
        //明星见面会等
        'star_active' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 5,
                "prefix" => "",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "database" => 5,
                "prefix" => "",
                'timeout' => 10,
            ],
        ],
        //评分评论
        'comment' => [
            'write' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "prefix" => "wx_ucc_",
                'timeout' => 10,
            ],
            'read' => [
                "host" => "192.168.200.253",
                "port" => 6379,
                "prefix" => "wx_ucc_",
                'timeout' => 10,
            ],
        ],
        'star_meet' => [
                [
                    'write' => [
                        "host" => "192.168.101.47",
                        "port" => 7002,
                        "prefix" => "starmeet_",
                        'timeout' => 3,
                    ],
                    'read' => [
                        "host" => "192.168.101.47",
                        "port" => 7002,
                        "prefix" => "starmeet_",
                        'timeout' => 3,
                    ],
                ],
            [
                'write' => [
                    "host" => "192.168.101.47",
                    "port" => 6379,
                    "prefix" => "starmeet_",
                    'timeout' => 3,
                ],
                'read' => [
                    "host" => "192.168.101.47",
                    "port" => 6379,
                    "prefix" => "starmeet_",
                    'timeout' => 3,
                ],
            ],
            ],
        ]

];
