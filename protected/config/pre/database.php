<?php

return [
    'db' => [
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;dbname=db_wxmovieadmin',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
        ],
    'db_luck' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=10.66.120.110;dbname=db_luck',
            'emulatePrepare' => true,
            'username' => 'db_luck',
            'password' => '20U7npdjTN7d4GeiiwrZnQWqS',
            'charset' => 'utf8',
            'tablePrefix'=>'t_'
        ],
    /* 读取活动信息 */
    'db2'=>[
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=10.66.112.5;dbname=db_bonus',
        'emulatePrepare' => true,
        'username' => 'db_bonus',
        'password' => 'wxmovie.com',
        'charset' => 'utf8',
    ],
    'db_app'=>[
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;dbname=db_app',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
    ],
    'db_user'=>[
            'class'=>'CDbConnection',
            'connectionString' => 'mysql:host=10.66.120.110;dbname=db_user',
            'emulatePrepare' => true,
            'username' => 'db_user',
            'password' => 'App_weiying',
            'charset' => 'utf8',
    ],
    /* 读取影城信息 */
    'mongodb' => [
            'class'            => 'EMongoDB', //主文件
            'dbName'           => 'opensystem',//数据库名称
            'connectionString' => 'mongodb://oss1:ej0gKGJ89v2xE4otNd9MsM1y4@10.104.4.127:27017,10.104.22.221:27017,10.104.27.170:27017/opensystem?readPreference=secondaryPreferred&replicaSet=repset',
            'fsyncFlag'        => true, //mongodb的确保所有写入到数据库的安全存储到磁盘
            'safeFlag'         => true, //mongodb的等待检索的所有写操作的状态，并检查
            'useCursor'        => false,
    ],
    /**
     * 黄金锁座数据库配置
 * @type {[type]}
 */
    'db_gold_seat' => [
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;port=3306;dbname=gold_seat',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
    ],

    /**
     * 商品中心数据库配置
     */
    'db_goods_center' => [
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88:3307;dbname=goods;',
        'emulatePrepare' => true,
        'username' => 'db_reader',
        'password' => 'lRodOl478Is1QdSR4fUuhx2we',
        'charset' => 'utf8',
    ],
    'db_active'=>[
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;dbname=db_activeuser',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
    ],
    'db_pee' => [
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;dbname=pee',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ],
    'db_opensystem' => [
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.66.112.50;dbname=opensystem',
        'emulatePrepare' => true,
        'username' => 'omservice',
        'password' => 'q6z94KdIO1eFU4WC9E3uPgVL2',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ],
    'db_message' => [
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.135.118.88;dbname=db_wxmovieadmin',
        'emulatePrepare' => true,
        'username' => 'data_user',
        'password' => '8kaWyeBLbcXLvzmIttQLoEbs2',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ],
];
