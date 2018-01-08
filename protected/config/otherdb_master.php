<?php
define("API_MOVIEDATABASE", "http://commoncgi.wepiao.com"); //媒资库
define("SERVICE_TYPE", 'master');
//return [
//    'redis' => array('host' => '119.29.5.173', 'port' => 6379),
//    'wechat' => array(
//        'app_id' => 'wx92cf60f7577e2d48',
//        'app_secret' => '6347543223aa409d36108565b51edd9a',
//        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
//    ),
//    'weixin_service_record' => array(
//        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
//        'app_id' => 'wx92cf60f7577e2d48',
//        'app_secret' => '6347543223aa409d36108565b51edd9a',
//    ),
//    'baymax_active_java' => 'http://10.66.150.98/baymaxqry?resId=',// java active -- master
//
//    'uctent_uid_openid' => '10.66.154.209/uc/v1/getopenidbyuid4dragon',// java ucent
//    'ucenter_api'=>array(//用户中心接口
//        'user_info'=>'http://10.66.154.209/uc/v1/getuserinfobyopenid',//通过openid获取用户信息
//        'mobileNo_openid'=>'http://10.66.154.209/uc/v1/getopenidlistbymobile',//根据手机号获取openid列表
//        'user_tag_add'=>'http://10.66.106.195/ucopen/v1/addusertag/static',  //添加用户静态标签
//        'user_tag_get'=>'http://10.66.106.195/ucopen/v1/getstatictag',  //获取用户静态标签
//    ),
//    'om_base_url'=>[//调用om数据--改为接口
//        'city_list'=>'http://data-core.wp.wepiao.com/datacore/city/list',
//        'cinema_list'=>'http://data-core.wp.wepiao.com/datacore/baseCinema',
//    ],
//    //redis
//    'redis_data' => [
//        //缓存使用的redis
//        'cache' => [
//            'write' => [
//                "host" => "10.104.27.136",
//                "port" => 23032,
//                "database" => 6,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//            'read' => [
//                "host" => "10.104.27.136",
//                "port" => 23032,
//                "database" => 6,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//        ],
//        //活动cms使用的redis
//        'cms_active' => [
//            'write' => [
//                //"class" => "ext.YiiRedis.ARedisConnection",
//                "host" => "10.66.125.68",
//                "port" => 23031,
//                "database" => 2,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//            'read' => [
//                //"class" => "ext.YiiRedis.ARedisConnection",
//                "host" => "10.66.162.49",
//                "port" => 23031,
//                "database" => 2,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//        ],
//        'cms_active_new' => [
//            'write' => ['host' => '10.66.125.68', 'port' => 23031, 'timeout' => 3, "prefix" => "mdb_", "database" => 1,],
//            'read' => ['host' => '10.66.162.49', 'port' => 23031, 'timeout' => 3, "prefix" => "mdb_", "database" => 1,],
//        ],
////movieOrder使碌edis
//
//        'movie_order' => [
//
//            'write' => [
//
//                "host" => "10.66.125.68",
//
//                "port" => 23031,
//
//                "database" => 3,
//
//                "prefix" => "wx_3_",
//
//                'timeout' => 10,
//
//            ],
//
//            'read' => [
//
//                "host" => "10.66.162.49",
//
//                "port" => 23031,
//
//                "database" => 3,
//
//                "prefix" => "wx_3_",
//
//                'timeout' => 10,
//
//            ],
//
//        ],
//        'pee' => [
//            'write' => [
//                "host" => "10.66.142.195",
//                "port" => 8000,
//                "database" => 1,
//                "prefix" => "pee_",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.66.142.194",
//                "port" => 8000,
//                "database" => 1,
//                "prefix" => "pee_",
//                'timeout' => 10,
//            ],
//        ],
//        'liveshow' => [
//            'write' => [
//                "host" => "10.66.142.195",
//                "port" => 8000,
//                "database" => 6,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.66.142.194",
//                "port" => 8000,
//                "database" => 6,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//        ],
//        'star_active' => [
//            'write' => [
//                "host" => "10.66.142.195",
//                "port" => 8000,
//                "database" => 2,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.66.142.194",
//                "port" => 8000,
//                "database" => 2,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//        ],
//        //评分评论
//        'comment' => [
//            'write' => [
//                "host" => "10.66.143.220",
//                "port" => 8000,
//                "prefix" => "wx_ucc_",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.66.143.220",
//                "port" => 8000,
//                "prefix" => "wx_ucc_",
//                'timeout' => 10,
//            ],
//        ],
//        'version' => [
//            'write' => [
//                "host" => "10.66.163.195",
//                "port" => 6379,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//            'read' => [
//                "host" => "10.66.122.10",
//                "port" => 6379,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//        ],
//        'AppModule' => [
//            'write' => [
//                "host" => "10.66.163.195",
//                "port" => 6379,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//            'read' => [
//                "host" => "10.66.122.10",
//                "port" => 6379,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//        ],
//    ]
//];
