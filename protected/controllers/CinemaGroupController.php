<?php
class CinemaGroupController extends Controller
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
				'actions'=>array('index','create','update','admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model           = new CinemaGroup;
		$selectedCinemas = array();

		if ( isset( $_POST['CinemaGroup'] ) ) {
			$model->attributes = $_POST['CinemaGroup'];
			if ( isset( $_POST['cinemas'] ) ) {
				$selectedCinemas = $_POST['cinemas'];
			}
			if ( $model->save() ) {
				foreach ( $_POST['cinemas'] as $k => $cinema )
				{
					$cnc = new CinemaGroupCinema();
					$cnc->iGroupID  = $model->iGroupID;
					$cnc->iCinemaID = $cinema;
					$cnc->save();
				}
				Yii::app()->user->setFlash( 'success', '创建影院分组成功' );
                $this->redirect(array('index'));
				//$this->redirect( array( 'index', 'id' => $model->iGroupID ) );
			}
		}
		$this->render( 'create', array(
			'model'           => $model,
			'selectedCinemas' => $selectedCinemas
		) );
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$selectedCinemas=array();

		if (is_array($model->cinemas))
			foreach($model->cinemas as $result) {
				$selectedCinemas[] = $result['iCinemaID'];
			}
		if(isset($_POST['CinemaGroup']))
		{
			$model->attributes=$_POST['CinemaGroup'];
			if (isset($_POST['cinemas']))
				$selectedCinemas = $_POST['cinemas'];
			if($model->save()) {
				CinemaGroupCinema::model()->deleteAllByAttributes(array('iGroupID'=>$model->iGroupID));
				if (isset($_POST['cinemas'])) {
					foreach ($_POST['cinemas'] as $k=>$cinema) {
						$cnc = new CinemaGroupCinema();
						$cnc->iGroupID = $model->iGroupID;
						$cnc->iCinemaID = $cinema;
						$cnc->save();
					}
				}
				Yii::app()->user->setFlash('success','更新影院分组成功');
                $this->redirect(array('index'));
				//$this->redirect(array('update','id'=>$model->iGroupID));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'selectedCinemas' => $selectedCinemas
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new CinemaGroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CinemaGroup']))
			$model->attributes=$_GET['CinemaGroup'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CinemaGroup the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CinemaGroup::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CinemaGroup $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cinema-group-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
