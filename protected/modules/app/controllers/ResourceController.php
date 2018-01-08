<?php

class ResourceController extends Controller
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
        $model = new Resource;

        if (isset($_POST['Resource'])) {
            $model->attributes = $_POST['Resource'];
            if ($model->save()) {
                // 处理资源上传
                $sPath = CUploadedFile::getInstance($model, 'sPath');
                if ($sPath) {
                    $model->sPath = $model->iChannelID . '/' . $model->sName . '.' . $sPath->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/app_resource';
                    if (!file_exists(dirname($uploadDir . '/' . $model->sPath)))
                        mkdir(dirname($uploadDir . '/' . $model->sPath), 0777, true);
                    $sPath->saveAs($uploadDir . '/' . $model->sPath, true);
                    $model->iLastModified = time();
                    $model->save();
                }
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index'));
            }
        }
        $model->iChannelID = '9';

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

        if (isset($_POST['Resource'])) {
            $sPath = CUploadedFile::getInstance($model, 'sPath');
            if (!$sPath)
                unset($_POST['Resource']['sPath']);
            $model->attributes = $_POST['Resource'];
            if ($model->save()) {
                // 处理资源上传
                if ($sPath) {
                    $model->sPath = $model->iChannelID . '/' . $model->sName . '.' . $sPath->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/app_resource';
                    if (!file_exists(dirname($uploadDir . '/' . $model->sPath)))
                        mkdir(dirname($uploadDir . '/' . $model->sPath), 0777, true);
                    $sPath->saveAs($uploadDir . '/' . $model->sPath, true);
                    $model->iLastModified = time();
                    $model->save();
                }
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('index'));
            }
        }

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
        $model = new Resource('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Resource']))
            $model->attributes = $_GET['Resource'];

        $this->render('index', array(
            'model' => $model,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Resource the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Resource::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Resource $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'resource-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
