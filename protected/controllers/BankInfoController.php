<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 11:21
 */
class BankInfoController extends Controller{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/main';

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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('update','delete','create','index','_status_update'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * ajax上下线
     */
    public function action_status_update($id){
        $model=$this->loadModel($id);
        if ($model->status == '1')
            $model->status = '0';
        else
            $model->status = '1';
        $model->save();
        echo $model->status;
    }
    /**
     * 新建银行信息
     */
    public function actionCreate(){
        $model=new BankInfo();
        if(isset($_POST['BankInfo'])){
            if(!empty($_FILES['BankInfo']['name']['image'])){
                $_POST['BankInfo']['image'] = $_FILES['BankInfo']['name']['image']; //过验证用的
            }
            $model->attributes=$_POST['BankInfo'];
            if($model->save()){
                $image=CUploadedFile::getInstance($model,'image');
                if($image) {
                    $model->image= '/'.$model->id.'.'.$image->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/bankInfo';
                    if (!file_exists(dirname($uploadDir . $model->image)))
                        mkdir(dirname($uploadDir . $model->image), 0777, true);
                    $image->saveAs($uploadDir  . $model->image, true);
                    $model->save();
                }
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
            }
        }
        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * 更新银行信息
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['BankInfo'])) {
            if(!empty($_FILES['BankInfo']['name']['image'])){
                $_POST['BankInfo']['image'] = '1'; //过验证用的
            }
            $image = CUploadedFile::getInstance($model, 'image');
            if (!$image) {
                unset($_POST['BankInfo']['image']);
            }
            $model->attributes = $_POST['BankInfo'];
            if ($model->save()) {
                if ($image) {
                    $model->image = '/' . $model->id . '.' . $image->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/bankInfo';
                    if (!file_exists(dirname($uploadDir . $model->image)))
                        mkdir(dirname($uploadDir . $model->image), 0777, true);
                    $image->saveAs($uploadDir  . $model->image, true);
                    $model->save();
                }
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->id));
            }
        }
        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * 银行信息列表
     */
    public function actionIndex(){
        $model=new BankInfo('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['BankInfo'])){
            $model->attributes=$_GET['BankInfo'];
        }

        $this->render('index',array(
            'model'=>$model,
        ));
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DiscoveryBanner the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=BankInfo::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DiscoveryBanner $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='bank-privilege-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}