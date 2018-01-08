<?php

/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/24
 * Time: 14:39
 */
class MovieguideController extends Controller
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
    public function actionCreate()
    {

        $model = new Movieguide;
        if (isset($_POST['movieId'])) {
            //判断是否存在如果存在这个影片ID就走更新流程
            $modelExist = $model->find('movieId=:id', array(':id' => $_POST['movieId']));
            if ($modelExist) {
                $modelExist->basePvCount = $_POST['basePvCount'];
                $modelExist->baseGetCount = $_POST['baseGetCount'];
                $modelExist->title = $_POST['title'];
                $modelExist->is_index = $_POST['is_index'];
                $modelExist->movieId = $_POST['movieId'];
                $modelExist->config = json_encode($_POST);
                $modelExist->updated_at = date("Y-m-d H:i:s", time());
                if ($modelExist->save()) {
                    echo json_encode(['ret' => 0, 'msg' => "观影秘籍更新成功"]);
                } else {
                    echo json_encode(['ret' => -1, 'msg' => "观影秘籍更新失败"]);
                }
                exit;
            }
            //正常添加流程
            $arrData = [];
            $arrData['status'] = 1;//初始给一个1，如果有值再赋值
            $arrData['basePvCount'] = $_POST['basePvCount'];
            $arrData['baseGetCount'] = $_POST['baseGetCount'];
            $arrData['is_index'] = $_POST['is_index'];
            $arrData['title'] = $_POST['title'];
            $arrData['movieId'] = $_POST['movieId'];
            $arrData['config'] = json_encode($_POST);
            $model->attributes = $arrData;
            $model->created_at = date("Y-m-d H:i:s", time());
            $model->updated_at = date("Y-m-d H:i:s", time());
            if ($model->save()) {
                echo json_encode(['ret' => 0, 'msg' => "观影秘籍添加成功"]);
            } else {
                echo json_encode(['ret' => -1, 'msg' => "观影秘籍添加失败"]);
            }
            exit;
        }
        $this->render('create', array('model' => $model,));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = Movieguide::model()->findByPk($id);
        $model->status = 2;
        $data = [];
        $data['ret'] = $model->save();
        echo json_encode($data);
        exit;
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Movieguide('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Movieguide']))
        $model->attributes=$_GET['Movieguide'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Active the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Movieguide::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 上下架观影秘笈
     * @param $id
     */
    public function actionSwitch($id)
    {
        $model = Movieguide::model()->findByPk($id);

        //如果状态为待删除那么就不调整了
        if ($model->status == 2) {
            return false;
        }
        if ($model->status) {
            $model->status = 0;
        } elseif ($model->status == 0) {
            $model->status = 1;
        }
        $data = [];
        $data['ret'] = $model->save();
        echo json_encode($data);
        exit;
    }

}