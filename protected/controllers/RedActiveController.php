<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 15:15
 */
class RedActiveController extends Controller{
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
        if ($model->a_status == '1')
            $model->a_status = '0';
        else
            $model->a_status = '1';
        $model->save();
        RedActive::model()->saveCache();
        echo $model->a_status;
    }

    public function actionCreate(){
        $model=new RedActive();
        if(isset($_POST['RedActive'])){
            $local=$_POST['RedActive']['local'];
            $channel=$_POST['RedActive']['channel'];
            $model->attributes=$_POST['RedActive'];
            if($model->save()){
                //处理关联渠道
                if (!empty($channel))
                    foreach ($channel as $c_channelId) {
                        if (empty($c_channelId)) continue;
                        $rac = new RedActiveChannel();
                        $rac->a_id = $model->a_id;
                        $rac->c_channelId = $c_channelId;
                        $rac->save();
                    }
                //处理关联位置
                if(!empty($local)){
                    foreach($local as $l_local_id){
                        if(empty($l_local_id)) continue;
                        $ral=new RedActiveLocal();
                        $ral->a_id=$model->a_id;
                        $ral->l_local_id=$l_local_id;
                        $ral->save();
                    }
                }
                RedActive::model()->saveCache();
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
            }
        }
        $this->render('create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id){
        $model=$this->loadModel($id);
        $arrLocal = [];
        if (is_array($model->local)){
            foreach($model->local as $local) {
                $arrLocal[] = $local['l_local_id'];
            }
            if (count($arrLocal) == count($model->getLocalkey())-1)
                $arrLocal[] ='0';
        }
        $model->local = $arrLocal;
        $arrChannel = [];
        if (is_array($model->channel)){
            foreach($model->channel as $channel) {
                $arrChannel[] = $channel['c_channelId'];
            }
            if (count($arrChannel) == count($model->getChannelkey())-1)
                $arrChannel[] ='0';
        }
        $model->channel = $arrChannel;
        if(isset($_POST['RedActive'])){
            $local=$_POST['RedActive']['local'];
            $channel=$_POST['RedActive']['channel'];
            $model->attributes=$_POST['RedActive'];
            if($model->save()){
                RedActiveChannel::model()->deleteAllByAttributes(array(
                    'a_id'=>$model->a_id
                ));
                //处理关联渠道
                if (!empty($channel)) {
                    foreach ($channel as $c_channelId) {
                        if (empty($c_channelId)) continue;
                        $rac = new RedActiveChannel();
                        $rac->a_id = $model->a_id;
                        $rac->c_channelId = $c_channelId;
                        $rac->save();
                    }
                }
                RedActiveLocal::model()->deleteAllByAttributes(array(
                    'a_id'=>$model->a_id
                ));
                //处理关联位置
                if(!empty($local)){
                    foreach($local as $l_local_id){
                        if(empty($l_local_id)) continue;
                        $ral=new RedActiveLocal();
                        $ral->a_id=$model->a_id;
                        $ral->l_local_id=$l_local_id;
                        $ral->save();
                    }
                }
                RedActive::model()->saveCache();
                Yii::app()->user->setFlash('success','修改成功');
                $this->redirect(array('update','id'=>$id));
            }

        }
        $this->render('update',array(
            'model'=>$model,
        ));
    }
    public function actionIndex()
    {
        $model=new RedActive('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['RedActive']))
            $model->attributes=$_GET['RedActive'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);
        $model->delete();
        RedActive::model()->saveCache();
        // if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
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
        $model=RedActive::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='red-active-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}