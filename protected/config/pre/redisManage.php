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
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 7,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
        'read' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 7,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
    ],
    //活动cms使用的redis
    'cms_active' => [
        'write' => [
            //"class" => "ext.YiiRedis.ARedisConnection",
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 5,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
        'read' => [
            //"class" => "ext.YiiRedis.ARedisConnection",
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 5,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
    ],
    'cms_active_new' => [
        'write' => [
            'host' => '10.104.34.91',
            'port' => 8008,
            'timeout' => 10,
            "prefix" => "mdb_",
        ],
        'read' => [
            'host' => '10.104.34.91',
            'port' => 8008,
            'timeout' => 10,
            "prefix" => "mdb_",
        ],
    ],
//movieOrder使碌Redis

    'movie_order' => [

        'write' => [

            "host" => "10.104.10.206",

            "port" => 8005,

            "database" => 3,

            "prefix" => "wx_3_",

            'timeout' => 10,

        ],

        'read' => [

            "host" => "10.104.10.206",

            "port" => 8005,

            "database" => 3,

            "prefix" => "wx_3_",

            'timeout' => 10,

        ],

    ],
    'pee' => [
        'write' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 4,
            "prefix" => "pee_",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 4,
            "prefix" => "pee_",
            'timeout' => 10,
        ],
    ],
    'liveshow' => [
        'write' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 6,
            "prefix" => "",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 6,
            "prefix" => "",
            'timeout' => 10,
        ],
    ],
    'star_active' => [
        'write' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 5,
            "prefix" => "",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.104.10.206",
            "port" => 8005,
            "database" => 5,
            "prefix" => "",
            'timeout' => 10,
        ],
    ],
    //评分评论
    'comment' => [
        'write' => [
            "host" => "10.104.10.206",
            "port" => 8008,
            "prefix" => "wx_ucc_",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.104.10.206",
            "port" => 8008,
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