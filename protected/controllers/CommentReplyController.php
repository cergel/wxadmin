<?php
class CommentReplyController extends Controller
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
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
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
                'actions'=>array('index','update','delete','deleteAll','statusAll'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * @tutorial 更新
     * @author liulong
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        if(isset($_POST['CommentReply']))
        {
            $model->attributes=$_POST['CommentReply'];
            if($model->save()) {
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->id));
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * @tutorial 删除（修改标记为）
     * @param
     * @author liulong
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    /**
     * @tutorial 批量删除功能
     * @author liulong
     */
    public function actionDeleteAll()
    {
        if ($_POST['commentId']){
            foreach ($_POST['commentId'] as $key)
            {
                $key = explode(',',$key);
                if(!empty($key[0]))
                    $str = $key[0];
                $this->loadModel($str)->delete();
            }
        }
        echo "ok";exit;
    }

    /**
     * @tutorial 批量审核通过
     * @author liulong
     */
    public function actionStatusAll()
    {
        if ($_POST['commentId']){
            $str = "";
            foreach ($_POST['commentId'] as $key)
            {
                $key = explode(',',$key);
                if(!empty($key[0]))
                    $str = $key[0];
                else $str .= ','.$key[0];
            }
            if(!empty($str)){
                $sql = "update t_comment_reply set checkstatus=1 where id IN ($str)";
                CommentReply::model()->saveSql($sql);
            }
        }
        echo "ok";exit;
    }

    /**
     * @tutorial 首页：查询页面
     * @author liulong
     */
    public function actionIndex()
    {
        $model=new CommentReply('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['CommentReply'])){
            $model->attributes=$_GET['CommentReply'];
        }else {
            $model->checkstatus = 0;
        }

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CommentReply the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=CommentReply::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CommentReply $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-reply-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}