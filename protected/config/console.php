<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
define("BASE_PRO_URL", dirname(dirname(__FILE__)));
require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR.'otherdb.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'env.php');
//接口地址
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'apiUrl.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.weixin.models.*',
    ),


	// application components
	'components'=>array_merge(array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
//	    require(dirname(__FILE__).'/database.php')
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'database.php'),
	),
//        require(dirname(__FILE__).'/database.php'),require(dirname(__FILE__) . '/memcache.php')
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'database.php'),
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'memcache.php')
    ),
//	'params' =>array_merge(require(dirname(__FILE__) . '/params.php'),$otherDb,require(dirname(__FILE__) . '/memcache.php')),//
    'params' =>array_merge(
        require(dirname(__FILE__) . '/params.php'),
        ['redis_data'=>require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE. DIRECTORY_SEPARATOR.'redisManage.php')],
        ['redis_tool'=>require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'redisTool.php')],
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'api.php'),
        require(dirname(__FILE__) .DIRECTORY_SEPARATOR.SERVICE_TYPE.DIRECTORY_SEPARATOR. 'memcache.php')
    ),//
);
