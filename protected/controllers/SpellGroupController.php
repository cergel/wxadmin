<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/8/11
 * Time: 16:18
 */
class SpellGroupController extends Controller
{
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
     * 新建拼团活动
     */
    public function actionCreate(){
        $model=new SpellGroup();
        if(isset($_POST['SpellGroup'])){
            $model->movie_name=$_POST['SpellGroup']['movie_name'];
            $model->attributes=$_POST['SpellGroup'];
            if($model->save()){
                $this->request_common_cgi($model->active_id,'create');
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
            }
        }
        $model->status = 1;
        $model->start_time=$model->end_time='';
        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * 更新拼团活动
     */
    public function actionUpdate($id){
        $model=$this->loadModel($id);
        if(isset($_POST['SpellGroup'])){
            $model->movie_name=$_POST['SpellGroup']['movie_name'];
            $model->attributes=$_POST['SpellGroup'];
            if(($_POST['SpellGroup']['status']==1) && strtotime($_POST['SpellGroup']['end_time'])<time()){
                Yii::app()->user->setFlash('error','设置的活动结束时间已经过期');
            }
            else{
                if($model->save()){
                    $this->request_common_cgi($id,'update');
                    Yii::app()->user->setFlash('success','更新成功');
                    $this->redirect(array('update','id'=>$model->active_id));
                }
            }
        }
        $this->render('update',array(
            'model'=>$model,
        ));
    }
    /**
     * 拼团活动列表
     */
    public function actionIndex(){
        $model=new SpellGroup('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['SpellGroup'])){
            $model->attributes=$_GET['SpellGroup'];
        }

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
        $this->request_common_cgi($id,'delete');
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
        $model=SpellGroup::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='spell-group-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //对commoncgi发起请求更新缓存
    protected function request_common_cgi($active_id,$type)
    {
        $commonCgiKey = 'save_spell_group_cache';
        $commonCgiUrl = Yii::app()->params['commoncgi']['save_pintuan_cache'];
        $commonCgiData = ['active_id'=>$active_id,'type'=>$type];
        Log::model()->logFile($commonCgiKey,$commonCgiUrl);
        Log::model()->logFile($commonCgiKey,json_encode($commonCgiData));
        $r=Https::getPost($commonCgiData, $commonCgiUrl);
        //var_dump($r);die;
        Log::model()->logFile($commonCgiKey,json_encode($r));
        $r=json_decode($r,true);
        if($r['ret']==0){
            return true;
        }else{
            return false;
        }
    }
}