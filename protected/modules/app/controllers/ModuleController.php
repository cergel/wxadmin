<?php

/**
 * 一些app的杂项比如模块管理可退票影院管理
 * Class ModuleController
 */
class ModuleController extends Controller
{


    public function actionIndex()
    {
        $this->render('index');
    }


    public function actionGet()
    {
        $element = htmlspecialchars($_GET['element']);
        $conf = Yii::app()->params->redis_data['AppModule'];
        Yii::import('ext.RedisManager', true);
        $redis = RedisManager::getInstance($conf['read']);
        $value = $redis->hashGet("app_module_switch", $element);
        if ($value === false) {
            $value = 1;
        }
        $return = ['ret' => 0, 'data' => $value];
        echo json_encode($return);
    }

    public function actionSet()
    {
        $element = htmlspecialchars($_GET['element']);
        $setValue = (int)htmlspecialchars($_GET['value']);
        $conf = Yii::app()->params->redis_data['AppModule'];
        Yii::import('ext.RedisManager', true);
        $redis = RedisManager::getInstance($conf['write']);
        $redis->setObjectInfo("app_module_switch", $element, $setValue);
        $return = ['ret' => 0];
        echo json_encode($return);
    }

    //无偿退改签影院列表维护
    public function actionRefund()
    {
        $this->render('refund');
    }

    //获取无偿改签影院列表
    public function actionGetRefund()
    {
        $conf = Yii::app()->params->redis_data['AppModule'];
        Yii::import('ext.RedisManager', true);
        $redis = RedisManager::getInstance($conf['write']);
        $ret = $redis->hashFindAll("app_refund_cinema");
        $arr = [];
        if ($ret) {
            foreach ($ret as $key => $value) {
                $obj = new stdClass();
                $obj->cinemaId = $key;
                $obj->name = $value;
                $arr[] = $obj;
            }
        }
        echo json_encode($arr);

    }

    //设置无偿改签影院列表
    public function actionSetRefund()
    {
        $allData = explode("#$#", ($_REQUEST['data']));
        if ($allData) {
            $conf = Yii::app()->params->redis_data['AppModule'];
            Yii::import('ext.RedisManager', true);
            $redis = RedisManager::getInstance($conf['write']);
            $redis->del("app_refund_cinema");
            foreach ($allData as $value) {
                $arr = explode("@$@", $value);
                if (count($arr) == 2) {
                    $redis->setObjectInfo("app_refund_cinema", $arr[0], $arr[1]);
                }

            }
        }
        $return = ['ret' => 0];
        echo json_encode($return);
    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}