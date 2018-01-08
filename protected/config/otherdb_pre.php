<?php
define("API_MOVIEDATABASE", "http://commoncgi.pre.wepiao.com");
define("SERVICE_TYPE", 'pre');
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
//    //'baymax_active_java'=>'http://10.66.150.98/baymaxqry?resId=',// java active -- master
//    'baymax_active_java' => 'http://10.104.0.247:8080/baymaxqry?resId=',// java active -- pre
//    'uctent_uid_openid' => '119.29.117.90:8080/uc/v1/getopenidbyuid4dragon',// java接口-用户中心-根据uid获取openid，预上线版本
//    'ucenter_api'=>array(//用户中心接口
//        'user_info'=>'http://10.104.140.34:8080/uc/v1/getuserinfobyopenid',//通过openid获取用户信息
//        'mobileNo_openid'=>'http://10.104.140.34:8080/uc/v1/getopenidlistbymobile',//根据手机号获取openid列表
//        'user_tag_add'=>'http://10.104.140.34:8101/ucopen/v1/addusertag/static',  //添加用户静态标签
//        'user_tag_get'=>'http://10.104.140.34:8101/ucopen/v1/getstatictag',  //获取用户静态标签
//    ),
//    'om_base_url'=>[//调用om数据--改为接口
//        'city_list'=>'http://10.251.209.50:8083/datacore/city/list',
//        'cinema_list'=>'http://10.251.209.50:8083/datacore/baseCinema',
//    ],
//
////redis
//    'redis_data' => [
//        //缓存使用的redis
//        'cache' => [
//            'write' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 7,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//            'read' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 7,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//        ],
//        //活动cms使用的redis
//        'cms_active' => [
//            'write' => [
//                //"class" => "ext.YiiRedis.ARedisConnection",
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 5,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//            'read' => [
//                //"class" => "ext.YiiRedis.ARedisConnection",
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 5,
//                "prefix" => "baymax_",
//                'timeout' => 10,
//                'password' => ''
//            ],
//        ],
//        'cms_active_new' => [
//            'write' => [
//                'host' => '10.104.34.91',
//                'port' => 8008,
//                'timeout' => 10,
//                "prefix" => "mdb_",
//            ],
//            'read' => [
//                'host' => '10.104.34.91',
//                'port' => 8008,
//                'timeout' => 10,
//                "prefix" => "mdb_",
//            ],
//        ],
////movieOrder使碌Redis
//
//        'movie_order' => [
//
//            'write' => [
//
//                "host" => "10.104.10.206",
//
//                "port" => 8005,
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
//                "host" => "10.104.10.206",
//
//                "port" => 8005,
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
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 4,
//                "prefix" => "pee_",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 4,
//                "prefix" => "pee_",
//                'timeout' => 10,
//            ],
//        ],
//        'liveshow' => [
//            'write' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 6,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 6,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//        ],
//        'star_active' => [
//            'write' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 5,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.104.10.206",
//                "port" => 8005,
//                "database" => 5,
//                "prefix" => "",
//                'timeout' => 10,
//            ],
//        ],
//        //评分评论
//        'comment' => [
//            'write' => [
//                "host" => "10.104.10.206",
//                "port" => 8008,
//                "prefix" => "wx_ucc_",
//                'timeout' => 10,
//            ],
//            'read' => [
//                "host" => "10.104.10.206",
//                "port" => 8008,
//                "prefix" => "wx_ucc_",
//                'timeout' => 10,
//            ],
//        ],
//        'version' => [
//            'write' => [
//                "host" => "pre.rdb.grammy.wxmovie.com",
//                "port" => 8003,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//            'read' => [
//                "host" => "pre.rdb.grammy.wxmovie.com",
//                "port" => 8003,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//        ],
//        'AppModule' => [
//            'write' => [
//                "host" => "pre.rdb.grammy.wxmovie.com",
//                "port" => 8003,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//            'read' => [
//                "host" => "pre.rdb.grammy.wxmovie.com",
//                "port" => 8003,
//                "database" => 1,
//                "prefix" => "wy_",
//                'timeout' => 1,
//            ],
//        ],
//    ]
//];
	
