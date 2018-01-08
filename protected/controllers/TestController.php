<?php

class TestController extends Controller
{
    public $layout = '//layouts/main';

    public function filters()
    {
        return array(
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
        
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $test = new Test();
//        $res = $test->setRedisInfo('aa','test001');
        $res = $test->getRedisInfo('aa');
        var_dump($res);exit;
    }

}
