<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
 */
class AuthorController extends Controller{
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
                'actions'=>array('update','delete','create','index','_status_update','getAuthorInfo'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * 银行信息列表
     */
    public function actionIndex(){
        $model=new Author('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Author'])){
            $model->attributes=$_GET['Author'];
        }
        $this->render('index',array(
            'model'=>$model,
        ));
    }
    /**
     */
    public function actionCreate(){
        $model=new Author();
        if(isset($_POST['Author'])){
            $_POST['Author']['created'] = $_POST['Author']['updated'] = time();
            //生成二维码
            if(!empty($_POST['Author']['head_img']) && !empty($_POST['Author']['qr_img'])){
                $url = Author::model()->saveQrCodeImg($_POST['Author']['head_img'],$_POST['Author']['qr_img']);
                if(!empty($url)){
                    $_POST['Author']['qr_img'] = $url;
                }else{
                    $_POST['Author']['qr_img'] = '';
                }
            }
            $model->attributes=$_POST['Author'];
            if($model->save()){
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
        if (isset($_POST['Author'])) {
            $_POST['Author']['updated'] = time();
            //生成二维码
            if(!empty($_POST['Author']['head_img']) && !empty($_POST['Author']['qr_img'])){
                $url = Author::model()->saveQrCodeImg($_POST['Author']['head_img'],$_POST['Author']['qr_img']);
                if(!empty($url)){
                    $_POST['Author']['qr_img'] = $url;
                }else{
                    $_POST['Author']['qr_img'] = '';
                }
            }
            $model->attributes = $_POST['Author'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->id));
            }
        }
        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * 获取作者信息
     */
    public function actionGetAuthorInfo()
    {
        $id = isset($_POST['id'])?$_POST['id']:0;
        $arrData = [];
        $arrData['msg'] = '错误的作者,请重试';
        $arrData['succ'] = 0;
        if(!empty($id)){
            $info = Author::model()->findByPk($id);
            if(!empty($info)){
                $info = $info->attributes;
                $info['name'] = $info['name_author'];
                $arrData['msg'] = $info;
                $arrData['succ'] = 1;
            }
        }
        echo json_encode($arrData);
    }

    /**
     * 删除
     * @param $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        $model=Author::model()->findByPk($id);
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