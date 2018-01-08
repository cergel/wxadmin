<?php

class RecommendWillController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete'),
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
        $model = new RecommendWill;
        if (isset($_POST['RecommendWill'])) {
            $model->attributes = $_POST['RecommendWill'];
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);

            if ($model->end_time <= $model->start_time) {
                $model->addError("错误", "下线时间必须晚于上线时间");
            } else {
                $path = "/uploads/userRecommend";
                $uploadRe = $this->commonUploadFile($path, $_FILES['UpLoadFile']['tmp_name'], $_FILES['UpLoadFile']['name']);
                $model->pic = $uploadRe;
                if ($model->save()) {
                    //保存上传的图片
                    //更新缓存
                    $sendData = [
                        'movieId' => $model->movie_id,
                        'movieName' => $model->movie_name,
                        'startTime' => $model->start_time,
                        'endTime' => $model->end_time,
                        'link' => $model->link,
                        'order' => $model->order,
                        'pic' => $model->pic,
                    ];
                    $this->requestCommoncgi('mqq_recommmend_add', $sendData);
                    Yii::app()->user->setFlash('success', '创建成功');
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['RecommendWill'])) {
            //检查order是否有变化
            $oldOrder = $model->order;
            $model->attributes = $_POST['RecommendWill'];
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            if ($model->end_time <= $model->start_time) {
                $model->addError("错误", "下线时间必须晚于上线时间");
            } else {
                if (!empty($_FILES['UpLoadFile']['tmp_name'])) {
                    $path = "/uploads/userRecommend";
                    $uploadRe = $this->commonUploadFile($path, $_FILES['UpLoadFile']['tmp_name'], $_FILES['UpLoadFile']['name']);
                    $model->pic = $uploadRe;
                }
                if ($model->save()) {
                    //更新缓存
                    $sendData = [
                        'movieId' => $model->movie_id,
                        'movieName' => $model->movie_name,
                        'pic' => $model->pic,
                        'startTime' => $model->start_time,
                        'endTime' => $model->end_time,
                        'link' => $model->link,
                        'order' => $model->order,
                        'oldOrder' => $oldOrder,
                    ];

                    $this->requestCommoncgi('mqq_recommmend_add', $sendData);
                    Yii::app()->user->setFlash('success', '更新成功');
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $model->start_time = date("Y-m-d H:i:s", $model->start_time);
        $model->end_time = date("Y-m-d H:i:s", $model->end_time);
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
        $model = RecommendWill::model()->findByPk($id);
        $delRe = $this->loadModel($id)->delete();
        if ($delRe) {
            $sendData = [
                'order' => $model->order,
            ];
            $this->requestCommoncgi('mqq_recommmend_del', $sendData);
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new RecommendWill('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RecommendWill']))
            $model->attributes = $_GET['RecommendWill'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RecommendWill the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = RecommendWill::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param RecommendWill $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recommend-will-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
