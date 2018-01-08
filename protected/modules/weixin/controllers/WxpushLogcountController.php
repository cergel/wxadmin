<?php

/**
 * wxpush评论推送每日发送量统计
 */

class WxpushLogcountController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'export', 'download'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        //读取之前数据
        $info = $this->getWxpushLogcount();
        krsort($info);
        $info = array_slice($info, 0, 60);
        $this->render('index', ['data' => $info]);
    }

    /**
     * 读取发送统计量
     */
    public function getWxpushLogcount()
    {
        $strKey = 'wx_3_wxpush_logcount';
        $redis = new Redis();
        $redisConfig = $this->getRedisConfig($strKey);
        $redis->connect($redisConfig, 28006);
        $logcount = $redis->hGetAll($strKey);
        $logcount = !empty($logcount) ? $logcount : [];
        return $logcount;
    }

    /**
     * 获取key对应的redis地址
     * 注：由于commoncgi正在重构，因此临时写成直接读取redis写死在这里，后续需要进行修改！！
     * @param $strKey
     * @return string
     */
    public function getRedisConfig($strKey)
    {
        $config = [
            'wx_3_wxpush_logcount' => '10.66.183.246',
        ];
        $redisConfig = isset($config[$strKey]) ? $config[$strKey] : '';
        return $redisConfig;
    }
}
