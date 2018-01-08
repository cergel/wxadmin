<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 17/3/17
 * Time: 15:05
 */

return [
    //缓存使用的redis
    'cache' => [
        'write' => [
            "host" => "192.168.101.47",
            "port" => 6379,
            "database" => 6,
            "prefix" => "baymax_",
            'timeout' => 10,
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
    'version' => [
        'write' => [
            "host" => "pre.rdb.grammy.wxmovie.com",
            "port" => 8003,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
        'read' => [
            "host" => "pre.rdb.grammy.wxmovie.com",
            "port" => 8003,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
    ],
    'AppModule' => [
        'write' => [
            "host" => "pre.rdb.grammy.wxmovie.com",
            "port" => 8003,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
        'read' => [
            "host" => "pre.rdb.grammy.wxmovie.com",
            "port" => 8003,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
    ],
];