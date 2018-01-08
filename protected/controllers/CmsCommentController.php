<?php
class CmsCommentController extends Controller
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
			//'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('index','update','delete','deleteAll','statusAll','addMovieName'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * @tutorial 首页：查询页面
	 * @author liulong
	 */
	public function actionIndex()
	{
		$model=new CmsComment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CmsComment'])){
			$model->attributes=$_GET['CmsComment'];
		}else {
			$model->checkstatus = 0;
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}
	/**
	 * @tutorial 删除评论：（修改标记为）
	 * @param
	 * @author liulong
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if(empty($model->status)){
			echo '已经屏蔽过了';
			exit;
		}
		//$res = $this->loadModel($id)->delete(); 
        list($commentId,$aid) = explode(",",$id);
        $res = CmsComment::model()->delete($commentId);
		if(empty($res['ret'])){
			echo 1;
		}else
			echo $res['msg'];
	}
	/**
	 * @tutorial 批量删除功能
	 * @author liulong
	 */
	public function actionDeleteAll()
	{
		if ($_POST['commentId']){
			foreach ($_POST['commentId'] as $key){
                list($commentId,$aid) = explode(",",$key);
                $model = CmsComment::model()->findByAttributes(array('id'=>$commentId,'a_id'=>$aid));
                $model->delete($commentId);
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
			foreach ($_POST['commentId'] as $key){
                list($commentId,$aid) = explode(",",$key);
                $model = CmsComment::model()->findByAttributes(array('id'=>$commentId,'a_id'=>$aid));
                if($model){
                    $model->checkstatus();
                }else{
                    throw new CHttpException(404,'The requested page does not exist.');
                }
                //$this->loadModel($key)->checkstatus();
			}
		}
		echo "ok";exit;
	}
	/**
	 * @tutorial 更新
	 * @author liulong
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['CmsComment']))
		{
			$model->attributes=$_POST['CmsComment'];

				$res = $model->saveComment();
				if(empty($res['ret']))
					Yii::app()->user->setFlash('success','更新成功');
				else
					Yii::app()->user->setFlash('error',$res['msg']);
				$this->redirect(array('update','id'=>$model->id));
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function loadModel($id)
	{
		$model=CmsComment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}



	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
