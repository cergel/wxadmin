<?php

return array(
	'db' => array(
		'class' => 'CDbConnection',
		'connectionString' => 'mysql:host=192.168.101.77;dbname=db_wxmovieadmin',
		'emulatePrepare' => true,
		'username' => 'test',
		'password' => 'test',
		'charset' => 'utf8',
		'tablePrefix'=>'t_'
	),
	'db_luck' => array(
		'class' => 'CDbConnection',
		'connectionString' => 'mysql:host=192.168.101.77;dbname=db_luck',
		'emulatePrepare' => true,
		'username' => 'test',
		'password' => 'test',
		'charset' => 'utf8',
		'tablePrefix'=>'t_'
	),
    /* 读取活动信息 */
    'db2'=>array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=operation_platform',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
    ),
    'db_app'=>array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=db_app',
        'emulatePrepare' => true,
        'enableProfiling' => TRUE, // 显示每个sql与运行的时间 
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
    ),
//     'db_app'=>array(
//            'class'=>'CDbConnection',
//            'connectionString' => 'mysql:host=10.66.103.148:3326;dbname=db_app',
//            'emulatePrepare' => true,
//            'username' => 'db_app',
//            'password' => 'App_weiying',
//            'charset' => 'utf8',
//            ),
    'db_user'=>array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=weiying_user',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
    ),
	'db_movie'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.101.77;dbname=db_movie_data',
			'emulatePrepare' => true,
			'username' => 'test',
			'password' => 'test',
			'charset' => 'utf8',
	),
	'db_movie_temp'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=192.168.101.77;dbname=db_movie_data_temporary',
			'emulatePrepare' => true,
			'username' => 'test',
			'password' => 'test',
			'charset' => 'utf8',
	),
    /* 读取影城信息 */
    'mongodb' => array(
        'class'            => 'EMongoDB', //主文件
        //'connectionString' => 'mongodb://opensystem_service1:service1@10.104.4.127:27017,10.104.22.221:27017,10.104.27.170:27017/opensystem?readPreference=secondaryPreferred&replicaSet=repset',
        'connectionString' => 'mongodb://opensystem_service1:service1@192.168.200.220:27017/opensystem?readPreference=secondaryPreferred', //服务器地址 &replicaSet=repset
        'dbName'           => 'opensystem',//数据库名称
        'fsyncFlag'        => true, //mongodb的确保所有写入到数据库的安全存储到磁盘
        'safeFlag'         => true, //mongodb的等待检索的所有写操作的状态，并检查
        'useCursor'        => false,
    ),
    'db_gold_seat' => array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=operation_platform',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
    ),
    /**
     * 商品中心数据库配置
     */
    'db_goods_center' => array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=goods;',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
    ),
    'db_active'=>array(
        'class'=>'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=db_activeuser',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ),
    'db_pee' => array(
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=192.168.101.77;dbname=pee',
        'emulatePrepare' => true,
        'username' => 'test',
        'password' => 'test',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ),
    'db_opensystem' => array(
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.66.112.50;dbname=opensystem',
        'emulatePrepare' => true,
        'username' => 'opensystem',
        'password' => 'wxmovie.com',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    ),
    'db_opensystem' => array(
        'class' => 'CDbConnection',
        'connectionString' => 'mysql:host=10.66.112.50;dbname=opensystem',
        'emulatePrepare' => true,
        'username' => 'opensystem',
        'password' => 'wxmovie.com',
        'charset' => 'utf8',
        'tablePrefix'=>'t_'
    )
);