<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
define("BASE_PRO_URL", dirname(dirname(__FILE__)));
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR.'otherdb.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'env.php');
//接口地址
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'apiUrl.php');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'language' => 'zh_cn',
    'sourceLanguage' => 'zh_cn',
    'name' => 'Baymax',
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMongoDbSuite.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('192.168.56.1', '::1','127.0.0.1'),
        ),
        'weixin',
        'mqq',
        'app',
        'tool',
        'message',
        'liveshow',
        'show',
        'weixinsp'
    ),
    // application components
    'components' => array_merge(array(
        'user' => array(
            'allowAutoLogin' => true,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'trace,log,error, warning, profile,info',
                    'categories' => 'system.db.CDbCommand',
                    'logFile' => date("Y-m-d").'_db.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'trace,log,error,warning, profile,info',
                    'categories' => 'other.*',
                    'logFile' =>  date("Y-m-d").'_other.log',
                   
                ),
                array( // 将日志写入firbug的console中 
                    'class' => 'CWebLogRoute',
                    'categories' => 'debug',
                    'levels' => 'info,trace',
                    'showInFireBug' => true,
                ),
            ),
        ),
        'clientScript' => array(
        ),
    	'assetManager'=>[
    			'basePath'=>dirname(dirname(dirname(__FILE__))).'/cache_tmp',
    			'baseUrl'=>'http://'.$_SERVER["HTTP_HOST"]."/cache_tmp/",
    	]

    ),
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'database.php'),
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'memcache.php')
    ),
    'params' =>array_merge(
        require(dirname(__FILE__) . '/params.php'),
        ['redis_data'=>require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE. DIRECTORY_SEPARATOR.'redisManage.php')],
        ['redis_tool'=>require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'redisTool.php')],
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'api.php'),
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'memcache.php')
    ),//
);
