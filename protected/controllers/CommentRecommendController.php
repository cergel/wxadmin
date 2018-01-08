<?php
class CommentRecommendController extends Controller
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
		//	'postOnly + delete', // we only allow deletion via POST request
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
		$model=new CommentRecommend('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CommentRecommend'])){
			$model->attributes=$_GET['CommentRecommend'];
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * @tutorial 更新
	 * @author liulong
	 */
	public function actionUpdate($id)
	{
		$model = CommentRecommend::model()->findByPk($id);
		$commentModel = Comment::model()->findByPk($id);
		if(empty($commentModel) || empty($commentModel->status) || $commentModel->status == 2){
			Yii::app()->user->setFlash('error','不合法的参数');
			$this->redirect(array('index'));
			return '';
		}
		if(empty($model)){
			$model = new CommentRecommend();
			$model->id = $id;
			$model->movie_id = $commentModel->movieId;
			$model->ucid = $commentModel->ucid;
		}
		if(isset($_POST['CommentRecommend']))
		{
			$endTime = empty($_POST['CommentRecommend']['end_time'])?0:strtotime($_POST['CommentRecommend']['end_time']);
			$model->created = time();
			$model->end_time = $endTime;
			$model->status = $model->end_time > time()?1:0;
			if($model->save()) {
				Yii::app()->user->setFlash('success','编辑成功');
				$this->redirect(array('update','id'=>$model->id));
			}else{
				Yii::app()->user->setFlash('error','插入失败');
				$this->redirect(array('index'));
			}
		}
		$model->end_time = $model->end_time ?date('Y-m-d H:i:s',$model->end_time):'';
		$this->render('update',array('model'=>$model,'info'=>$commentModel->content,));
	}
	


	/**
	 * @tutorial 删除评论：（修改标记为）
	 * @param 
	 * @author liulong
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
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
			foreach ($_POST['commentId'] as $key){
				$this->loadModel($key)->delete();
			}
		}
		echo "ok";exit;
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CommentRecommend::model()->findByPk($id);
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
