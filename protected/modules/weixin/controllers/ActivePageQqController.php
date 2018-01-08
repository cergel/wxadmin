<?php

class ActivePageQqController extends Controller
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
            array(
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'index', 'delete'),  //'users'=>array('@'), // 这个为啥不生效？
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
        $model = new ActivePageForQq;

        // $this->performAjaxValidation($model);
        if (isset($_POST['ActivePageForQq'])) {
            $model->attributes = $_POST['ActivePageForQq'];
            $pic = CUploadedFile::getInstance($model, 'pic');
            if ($pic) {
                $model->pic = 'title.' . $pic->getExtensionName();//获取文件名
            } else {
                $model->pic = 'title.png';
            }
            $sharePic = CUploadedFile::getInstance($model, 'sharePic'); //获取表单上传信息
            if ($sharePic) {
                $model->sharePic = 'share.' . $sharePic->getExtensionName();//获取文件名
            } else {
                $model->sharePic = 'logo.png';
            }
            if ($model->save()) {
                //生成目录地址
                $targetDir = Yii::app()->params['active_page_for_QQ']['target_dir'] . '/' . $model->id;
                //拷贝文件，目录判断
                $model->makeFile();

                //图片处理
                if ($pic) {
                    $pic->saveAs($targetDir . '/images/' . $model->pic, true);
                }
                if ($sharePic) {
                    $sharePic->saveAs($targetDir . '/images/' . $model->sharePic, true);
                }
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $model->title = 'QQ电影票红包';
        $model->shareTitle = 'QQ电影票红包';
        $model->pic = 'title.png';
        $model->sharePic = 'logo.png';
        $this->render('create', array(
            'model' => $model,
        ));

    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['ActivePageForQq'])) {
            $pic = CUploadedFile::getInstance($model, 'pic');
            if (!$pic)
                unset($_POST['ActivePageForQq']['pic']);
            $sharePic = CUploadedFile::getInstance($model, 'sharePic');
            if (!$sharePic)
                unset($_POST['ActivePageForQq']['sharePic']);
            $model->setAttributes($_POST['ActivePageForQq']);
            if ($model->validate()) {
                //图片
                if ($pic) {
                    $filename = 'title' . rand(1000, 9999) . '.' . $pic->getExtensionName();
                    $model->pic = $filename;
                }
                if ($sharePic) {
                    $filename = 'share.' . rand(1000, 9999) . '.' . $sharePic->getExtensionName();
                    $model->sharePic = $filename;
                }
                if ($model->save()) {
                    //生成图片
                    $targetDir = Yii::app()->params['active_page_for_QQ']['target_dir'] . '/' . $model->id;
                    if ($pic) {
                        $pic->saveAs($targetDir . '/images/' . $model->pic, true);
                    }
                    if ($sharePic) {
                        $sharePic->saveAs($targetDir . '/images/' . $model->sharePic, true);
                    }
                    $model->makeFile();
//                    FlushCdn::setUrlToRedis(Yii::app()->params['active_page_for_QQ']['final_url'] . '/' . $model->id, Yii::app()->params['active_page_for_QQ']['target_dir'] . '/' . $model->id);
                    //cdn 刷新
                    FlushCdn::setUrlToRedis(Yii::app()->params['active_page_for_QQ']['final_url'] . '/' . $model->id.'/index.html');

                    Yii::app()->user->setFlash('success', '活动模板更新成功');
                    $this->redirect(array('update', 'id' => $model->id));
                }
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new ActivePageForQq('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivePage']))
            $model->attributes = $_GET['ActivePage'];
        $this->render('index', array(
            'model' => $model,
        ));
    }


    /**
     * @param integer $id the ID of the model to be loaded
     * @return ActivePage the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ActivePageForQq::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ActivePage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-page-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
