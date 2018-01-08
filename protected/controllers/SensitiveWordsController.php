<?php
class SensitiveWordsController extends Controller
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
				'actions'=>array('index','update','delete','create','addMovieName'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionCreate()
	{
		
		if(isset($_POST['SensitiveWords']))
		{
			$name = htmlspecialchars($_POST['SensitiveWords']['name']);
			$name = explode("、",$name);
			foreach ($name as $val){
				$model = new SensitiveWords;
				$val =trim($val);
				$nameOne = SensitiveWords::model()->getOne($val);
				if ($nameOne == true){
					continue;
				}
				$model->attributes = $_POST['SensitiveWords'];
				$model->name = $val;
				$model->created = time();
				$model->updated = time();
				$model->save();
			}
			SensitiveWords::model()->saveMemcache();
			Yii::app()->user->setFlash('success','创建成功');
			$this->redirect(array('index'));
		} else {
			$model = new SensitiveWords;
			$model->name = '';
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

		if(isset($_POST['SensitiveWords']))
		{
			$model->attributes=$_POST['SensitiveWords'];
			$nameOne = SensitiveWords::model()->getOne($model->name);
			if ($nameOne == false){
				$model->updated = time();
				if($model->save()) {
					Yii::app()->user->setFlash('success','更新成功');
					$this->redirect(array('update','id'=>$model->id));
				}
			}else {
				Yii::app()->user->setFlash('error','已经存在的关键字');
				$this->redirect(array('update','id'=>$model->id));
			}
			
		}
		SensitiveWords::model()->saveMemcache();
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
		$model=new SensitiveWords('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SensitiveWords']))
			$model->attributes=$_GET['SensitiveWords'];
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
		$model=SensitiveWords::model()->findByPk($id);
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
