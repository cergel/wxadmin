<?php

class PromotionSharingController extends Controller
{
    use AlertMsg;
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
                'actions' => array('index', 'create', 'update', 'delete', 'AjaxUpload'),
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
        $model = new PromotionSharing;
        if (isset($_POST['PromotionSharing'])) {
            $id = $_POST['id'];
            //判断渠道交集
            if ($id > 0) {
                $model = $this->loadModel($id);
            }
            $channels = $_POST['channels'];
            if (empty($channels)) {
                $this->json_alert(1, '请选择渠道平台');
            }
            $userName = Yii::app()->getUser()->getName();
            $model->attributes = $_POST['PromotionSharing'];
            $model->start_time = strtotime($_POST['PromotionSharing']['start_time']);
            $model->end_time = strtotime($_POST['PromotionSharing']['end_time']);
            if (empty($id)) {
                $model->create_user = $userName;
                $model->created = time();
            }
            $model->update_user = $userName;
            $model->updated = time();
            if ($model->save()) {
                $channelModel = new PromotionSharingChannel;
                PromotionSharingChannel::model()->deleteAll(['condition' => 'promotion_sharing_id=:promotion_sharing_id',
                    'params' => array(':promotion_sharing_id' => $id)]);
                foreach ($channels as $channel) {
                    $_channelModel = clone $channelModel;
                    $_channelModel->promotion_sharing_id = $model->id;
                    $_channelModel->channel_id = $channel;
                    $_channelModel->save();
                }
                $this->json_alert(0, '创建成功');
            } else {
                $error = $model->getErrors();
                $this->json_alert(1, current(current($error)));
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
        $channelModel = new PromotionSharingChannel;
        $channelDetails = $channelModel->findAll(['condition' => 'promotion_sharing_id=:promotion_sharing_id',
            'params' => array(':promotion_sharing_id' => $id)]);
        $channels = [];
        foreach ($channelDetails as $channelDetail) {
            $channels[] = $channelDetail->channel_id;
        }
        $this->render('update', compact('model', 'channels'));
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
        $model = new PromotionSharing('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PromotionSharing']))
            $model->attributes = $_GET['PromotionSharing'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PromotionSharing the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PromotionSharing::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PromotionSharing $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'promotion-sharing-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 异步图片上传
     */
    public function actionAjaxUpload()
    {
        $tmpFile = CUploadedFile::getInstanceByName('file');
        if (!in_array(strtolower($tmpFile->getExtensionName()), ["gif", "jpeg", "jpg", "bmp", "png"])) {
            return $this->alert_info(1, '请确图片文件格式正确');
        }
        //保存在数据库里的路径，/uploads开头
        $path = '/PromotionSharing/uploads/';
        $fileName = date('YmdHis') . rand(1000, 9999) . uniqid() . '.' . $tmpFile->getExtensionName();
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFile->tempName, $path . $fileName);
        if (!empty($url)) {
            $this->json_alert(0, '上传成功', $url);
        } else {
            $this->json_alert(1, '失败');
        }
    }
}
