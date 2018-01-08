<?php
class BlackListController extends Controller
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
				'actions'=>array('index','update','delete','create','deleteAll'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate($id='')
	{
		$model = new BlackList;
		if(isset($_POST['BlackList']))
		{
			$uid = htmlspecialchars($_POST['BlackList']['uid']);
			$uid = explode("、",$uid);
// 			$model->attributes = $_POST['BlackList'];
			foreach ($uid as $val){
				$objModel = BlackList::model()->getOne($val);
				if ($objModel == true){
					continue;
				}
				$model = new BlackList;
				$model->attributes = $_POST['BlackList'];
				$model->stype = $_POST['BlackList']['stype'];
				$model->uid = $val;
				$model->created = time();
				$model->create_uid = Yii::app()->getUser()->getId();
				$model->create_name = Yii::app()->getUser()->getName();
				$model->save();
			}
			BlackList::model()->saveRedis();
			Yii::app()->user->setFlash('success','创建成功');
			$this->redirect(array('index'));
		} else {
			$model->stype = 1;
			$model->uid=empty($_GET['uid'])?$id:$_GET['uid'];
		}
		$this->render('create',array(
			'model'=>$model
		));
	}


	/**
	 */
	public function actionDeleteAll()
	{
		if ($_POST['uid']){
			foreach ($_POST['uid'] as $key){
				$this->loadModel($key)->delete();
			}
		}
		echo 'ok';exit;
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		BlackList::model()->saveRedis();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new BlackList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BlackList']))
			$model->attributes=$_GET['BlackList'];
		$this->render('index',array(
			'model'=>$model,
		));
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
		$model=BlackList::model()->findByPk($id);
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
