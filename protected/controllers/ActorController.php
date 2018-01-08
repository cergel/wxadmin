<?php

class ActorController extends Controller
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
                'actions' => array('index', 'create', 'update'),
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
        $ActorModel = new Actor();
        if (isset($_POST['Actor'])) {
            $actor_id = $_POST['Actor']['id'];
            $base_like = $_POST['Actor']['base_like'];
            $api_actor_info = $ActorModel->getActorInfo($actor_id);
            $model = Actor::model()->findByPk($actor_id);
            if (empty($api_actor_info)) {
                Yii::app()->user->setFlash('error', '未找到相应的影人请确保ID正确');
                $this->render('create', array(
                    'model' => $ActorModel,
                ));
            }
            if (empty($model)) {
                $model = new Actor();
                $model->id = (int)$api_actor_info['id'];
            }
            $model->name_chs = $api_actor_info['actorNameChs'];
            $model->name_eng = $api_actor_info['actorNameEng'];
            $model->sex = $api_actor_info['gender'] == '男' ? 1 : 2;
            $model->like = 0;
            $model->created = time();
            $model->updated = time();
            $model->base_like = $base_like ? $base_like : 0;
            $model->save();
            $ActorModel->pushActorBaseLike($actor_id, $base_like);
            Yii::app()->user->setFlash('success', '创建成功');
            $this->redirect(array('index'));
        }
        $this->render('create', array(
            'model' => $ActorModel,
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
        $actor_info = $model->getActorInfoByDb($id, true);
        if ($actor_info === false) {
            Yii::app()->user->setFlash('error', '未找到相应的影人请确保ID正确');
        }
        if (isset($_POST['Actor'])) {
            $base_like = $_POST['Actor']['base_like'] ? $_POST['Actor']['base_like'] : 0;
            $model->base_like = $base_like;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
            $this->render('index', array(
                'model' => $model,
            ));
        }
        $this->render('update', array(
            'model' => $model,
            'actor_head' => $actor_info['headImage'],
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Actor('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Actor']))
            $model->attributes = $_GET['Actor'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Actor the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Actor::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Actor $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'actor-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
