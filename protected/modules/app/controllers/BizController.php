<?php

/**
 * Created by PhpStorm.
 * User: panyuanxin
 * Date: 16/8/23
 * Time: 上午10:23
 */
class BizController extends Controller
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
//            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'create', 'update', 'delete', 'flushapp', 'switch'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionIndex()
    {
        $model = new MovieBiz("search");
        $model->unsetAttributes();
        $this->render('index', array('model' => $model,));
    }

    public function actionCreate()
    {
        if (isset($_POST['movieId'])) {
            $model = new MovieBiz;
            $modelExist = $model->find('movieId=:id', array(':id' => $_POST['movieId']));
            if ($modelExist) {
                echo json_encode(['ret' => -1, 'msg' => "该片已经建立过商业化详情页"]);
                exit;
            }
            $arrData = [];
            $arrData['status'] = 1;//初始给一个1，如果有值再赋值
            $arrData['start'] = $_POST['start'];
            $arrData['end'] = $_POST['end'];
            $arrData['movieId'] = $_POST['movieId'];
            $arrData['platform'] = implode(",", $_POST['platform']);
            $arrData['config'] = json_encode($_POST);
            $arrData['status'] = 0;
            $model->attributes = $arrData;
            $model->created_at = date("Y-m-d H:i:s", time());
            $model->updated_at = date("Y-m-d H:i:s", time());
            if ($model->save()) {
                echo json_encode(['ret' => 0, 'msg' => "商业化影片详情页建立成功"]);
                exit;
            }
            exit;
        }

        $model = new MovieBiz;
        $this->render('create', array('model' => $model,));
    }

    /**
     * 上下架观影秘笈
     * @param $id
     */
    public function actionSwitch($id)
    {
        $model = MovieBiz::model()->findByPk($id);
        if ($model->status) {
            $model->status = 0;
        } else {
            $model->status = 1;
        }
        $data = [];
        $data['ret'] = $model->save();
        echo json_encode($data);
        exit;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = MovieBiz::model()->findByPk($id);
        $model->delete();
        //刷新缓存
    }

    public function actionUpdate($id)
    {

        if (isset($_POST['movieId'])) {
            $model = new MovieBiz;
            $modelExist = $model->find('movieId=:id', array(':id' => $_POST['movieId']));
            if ($modelExist) {
                $arrData = [];
                $arrData['status'] = 1;//初始给一个1，如果有值再赋值
                $arrData['start'] = $_POST['start'];
                $arrData['end'] = $_POST['end'];
                $arrData['movieId'] = $_POST['movieId'];
                $arrData['platform'] = implode(",", $_POST['platform']);
                $arrData['config'] = json_encode($_POST);
                $modelExist->start = $_POST['start'];
                $modelExist->end = $_POST['end'];
                $modelExist->platform = implode(",", $_POST['platform']);
                $modelExist->config = json_encode($_POST);
                $modelExist->updated_at = date("Y-m-d H:i:s", time());
                if ($modelExist->save()) {
                    echo json_encode(['ret' => 0, 'msg' => "商业化影片详情页更新成功"]);
                } else {
                    echo json_encode(['ret' => -1, 'msg' => "商业化影片详情页更新失败"]);
                }
                exit;
            }
        } else {
            $model = MovieBiz::model()->findByPk($id);
        }
        $this->render('update', array(
            'model' => $model,
        ));

    }

}