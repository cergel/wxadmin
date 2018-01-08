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
            "host" => "10.104.27.136",
            "port" => 23032,
            "database" => 6,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
        'read' => [
            "host" => "10.104.27.136",
            "port" => 23032,
            "database" => 6,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
    ],
    //活动cms使用的redis
    'cms_active' => [
        'write' => [
            //"class" => "ext.YiiRedis.ARedisConnection",
            "host" => "10.66.125.68",
            "port" => 23031,
            "database" => 2,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
        'read' => [
            //"class" => "ext.YiiRedis.ARedisConnection",
            "host" => "10.66.162.49",
            "port" => 23031,
            "database" => 2,
            "prefix" => "baymax_",
            'timeout' => 10,
            'password' => ''
        ],
    ],
    'cms_active_new' => [
        'write' => ['host' => '10.66.125.68', 'port' => 23031, 'timeout' => 3, "prefix" => "mdb_", "database" => 1,],
        'read' => ['host' => '10.66.162.49', 'port' => 23031, 'timeout' => 3, "prefix" => "mdb_", "database" => 1,],
    ],
//movieOrder使碌edis

    'movie_order' => [

        'write' => [

            "host" => "10.66.125.68",

            "port" => 23031,

            "database" => 3,

            "prefix" => "wx_3_",

            'timeout' => 10,

        ],
        'read' => [

            "host" => "10.66.162.49",

            "port" => 23031,

            "database" => 3,

            "prefix" => "wx_3_",

            'timeout' => 10,

        ],

    ],
    'pee' => [
        'write' => [
            "host" => "10.66.142.195",
            "port" => 8000,
            "database" => 1,
            "prefix" => "pee_",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.66.142.194",
            "port" => 8000,
            "database" => 1,
            "prefix" => "pee_",
            'timeout' => 10,
        ],
    ],
    'liveshow' => [
        'write' => [
            "host" => "10.66.142.195",
            "port" => 8000,
            "database" => 6,
            "prefix" => "",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.66.142.194",
            "port" => 8000,
            "database" => 6,
            "prefix" => "",
            'timeout' => 10,
        ],
    ],
    'star_active' => [
        'write' => [
            "host" => "10.66.142.195",
            "port" => 8000,
            "database" => 2,
            "prefix" => "",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.66.142.194",
            "port" => 8000,
            "database" => 2,
            "prefix" => "",
            'timeout' => 10,
        ],
    ],
    //评分评论
    'comment' => [
        'write' => [
            "host" => "10.66.143.220",
            "port" => 8000,
            "prefix" => "wx_ucc_",
            'timeout' => 10,
        ],
        'read' => [
            "host" => "10.66.143.220",
            "port" => 8000,
            "prefix" => "wx_ucc_",
            'timeout' => 10,
        ],
    ],
    'version' => [
        'write' => [
            "host" => "10.66.163.195",
            "port" => 6379,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
        'read' => [
            "host" => "10.66.122.10",
            "port" => 6379,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
    ],
    'AppModule' => [
        'write' => [
            "host" => "10.66.163.195",
            "port" => 6379,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
        'read' => [
            "host" => "10.66.122.10",
            "port" => 6379,
            "database" => 1,
            "prefix" => "wy_",
            'timeout' => 1,
        ],
    ],
];