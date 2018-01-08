<?php
class WeixinCommentController extends Controller
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
				'actions'=>array('index','update','delete','create','_status_update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * @tutorial 增加微信影评
	 */
	public function actionCreate($id)
	{
		$model = new WeixinComment;
		if(isset($_POST['WeixinComment']))
		{
			 $model->attributes = $_POST['WeixinComment'];
			 if($model->save()) {
			 	Yii::app()->user->setFlash('success','创建成功');
			 	$this->redirect(array('index'));
			 }
		} else {
			//评论是否存在
			if (empty($id)){
				$this->redirect(array('index'));
			}
			//是否已经添加过
			$commitInfo = $model->getInfo(['commentId'=>$id]);
			if ($commitInfo){
				$this->redirect(array('update',"id"=>$commitInfo['id']));
			}
			//获取评论内容
			$commentModel = Comment::model()->findByPk($id);
			if (empty($commentModel->id)){
				$this->redirect(array('index'));
			}
			$model->commentId = $commentModel->id;
			$model->content = $commentModel->content;
			$model->movieId = $commentModel->movieId;
			$model->uid = $commentModel->uid;
			//获取影片内容
			$movieData = Comment::model()->getFilmInfo($commentModel->movieId);
			if (empty($movieData)){
				$this->redirect(array('index'));
			}
			$model->movieName =@$movieData['name'];
			$model->movieImg = @$movieData['poster_url_size21'];
			//获取用户内容
			$userInfo = Comment::model()->getUserInfo($commentModel->uid);
			if (empty($userInfo)){
				$this->redirect(array('index'));
			}
			$model->uname =@$userInfo['nickName'];
			
		}
		$this->render('create',array(
				'model'=>$model
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
// 		if (empty($model->movieImg) || empty($model->movieName)){
			$movieData = Comment::model()->getFilmInfo($model->movieId);
			if (!empty($movieData)){
				$model->movieName =@$movieData['name'];
				$model->movieImg = @$movieData['poster_url_size21'];
			}
// 		}
// 		if (empty($model->uname)){
			$userInfo = Comment::model()->getUserInfo($model->uid);
			if (!empty($userInfo)){
				$model->uname =@$userInfo['nickName'];
			}
// 		}
		if(isset($_POST['WeixinComment']))
		{
			$model->attributes=$_POST['WeixinComment'];
			if($model->save()) {
				WeixinComment::model()->saveJson();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}
		$model->showTime = date('Y-m-d H:i:s',$model->showTime);
		$this->render('update',array(
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
		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new WeixinComment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['WeixinComment']))
			$model->attributes=$_GET['WeixinComment'];
		$this->render('index',array(
			'model'=>$model,
		));
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
// 		DiscoveryBanner::createJson();
		echo $model->status;
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
		$model=WeixinComment::model()->findByPk($id);
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
