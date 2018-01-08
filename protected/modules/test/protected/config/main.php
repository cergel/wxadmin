<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
/*
define('CHANNEL_IOS', '8');
define('CHANNEL_ANDROID', '9');
define('CHANNEL_PC', '10');
*/
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'env.php');
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
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
		'srbac' => array(
            'userclass'=>'User', //default: User
            'userid'=>'iUserID', //default: userid
            'username'=>'sUserName', //default:username
            'delimeter'=>'@', //default:-
            'debug'=>true, //default :false
            'pageSize'=>10, // default : 15
            'superUser' =>'Authority', //default: Authorizer
            'css'=>'srbac.css', //default: srbac.css
            'layout'=> 'application.views.layouts.main', //default: application.views.layouts.main,
            //must be an existing alias
            'notAuthorizedView'=> 'srbac.views.authitem.unauthorized', // default:
            //srbac.views.authitem.unauthorized, must be an existing alias
            'alwaysAllowed'=>array( //default: array()
                'SiteLogin','SiteLogout','SiteIndex','SiteAdmin',
                'SiteError', 'SiteContact'),
            'userActions'=>array('Show','View','List'), //default: array()
            'listBoxNumberOfLines' => 15, //default : 10 'imagesPath' => 'srbac.images', // default: srbac.images 'imagesPack'=>'noia', //default: noia 'iconText'=>true, // default : false 'header'=>'srbac.views.authitem.header', //default : srbac.views.authitem.header,
            //must be an existing alias 'footer'=>'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
            //must be an existing alias 'showHeader'=>true, // default: false 'showFooter'=>true, // default: false
            'alwaysAllowedPath'=>'srbac.components', // default: srbac.components
            // must be an existing alias
        ),
        'weixin',
        'pc',
        'app',
    ),
    // application components
    'components' => array_merge(array(
	    'authManager'=>array(
            // Path to SDbAuthManager in srbac module if you want to use case insensitive
            //access checking (or CDbAuthManager for case sensitive access checking)
            'class'=>'application.modules.srbac.components.SDbAuthManager',
            // The database component used
            'connectionID'=>'db',
            // The itemTable name (default:authitem)
            'itemTable'=>'t_rbac_items',
            // The assignmentTable name (default:authassignment)
            'assignmentTable'=>'t_rbac_assignments',
            // The itemChildTable name (default:authitemchild)
            'itemChildTable'=>'t_rbac_itemchildren',
        ),
        'cache' => require(dirname(__FILE__) . '/memcache.php'),
    	'cache_app' => require(dirname(__FILE__) . '/memcache_app.php'),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
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
                    'levels' => 'error, warning, profile',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
        'clientScript' => array(
        ),

    ), require(dirname(__FILE__) . '/database.php')),
    // using Yii::app()->params['paramName']
    'params' => array(
        'company' => '北京微影时代科技有限公司',
        'wechat' => array(
            'app_id' => 'wx92cf60f7577e2d48',
            'app_secret' => '6347543223aa409d36108565b51edd9a',
            'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
        ),
        'active_page' => array(
            'template' => 'modules/weixin/active_page_template', // 模板目录
            'target_dir' => "D:\\xampp\\htdocs\\WeiYing\\WxMovieAdmin\\trunk\\assets\\activepage", // 生成目录
            'target_url' => 'http://admin.wepiao.com/assets/activepage',
            'final_url' => 'http://admin.wepiao.com/assets/activepage'
        ),
    	'redis'=>array('host'=>'192.168.200.85','port'=>6379,'password'=>'123456'),
    	//'jpush'=>array('appkey'=>'c80e17806685319e4b06ceb3','masterSecret'=>'55de62bbb291eec77085d049'), 正式环境
    	'jpush'=>array('appkey'=>'2715d277a52105949776f2b3','masterSecret'=>'158f323deae79c5c24341fa6'), //测试环境
        'weixin_discovery_banner' => array(
            'target_dir' => "D:\php\www\addAdmin\uploads\weixin_discovery_banner", // 生成目录
            'final_url' => 'https://appnfs.wepiao.com/uploads/weixin_discovery_banner'
        ),
        'ad' => array(
            'target_dir' => "/data/wxadmin/uploads/ad", // 生成目录
            'final_url' => 'https://appnfs.wepiao.com/uploads/ad'
        ),
        'weixin_service_record' => array(
            'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
            'app_id' => 'wx92cf60f7577e2d48',
            'app_secret' => '6347543223aa409d36108565b51edd9a',
        ),
    ),
);
