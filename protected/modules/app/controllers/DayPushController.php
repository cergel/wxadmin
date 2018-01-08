<?php
class DayPushController extends Controller
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
				'actions'=>array('index','create','update','delete','getday'),
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
		$model=new AppDayPush;
		if(isset($_POST['AppDayPush']))
		{
			if(!empty($_FILES['AppDayPush']['name']['sImages']))
				$_POST['AppDayPush']['sImages'] = '2'; //过验证用的
			if(empty($_POST['AppDayPush']['iId']))
				$_POST['AppDayPush']['iId'] = date("Ymd",time());
			$model->attributes=$_POST['AppDayPush'];
			if($model->save()) {
				// 处理资源上传
				$sPath=CUploadedFile::getInstance($model,'sImages');
				if($sPath) {
					$model->sImages= '/'.$model->iId.'.'.$sPath->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/app_dayPush';
					if (!file_exists(dirname($uploadDir . '/' . $model->sImages)))
						mkdir(dirname($uploadDir . '/' . $model->sImages), 0777, true);
					$sPath->saveAs($uploadDir . '/' . $model->sImages, true);
				}
				$sSharePic=CUploadedFile::getInstance($model,'sSharePic');
				if($sSharePic) {
					$model->sSharePic= '/sSharePic'.$model->iId.'.'.$sSharePic->getExtensionName();
					$uploadDirSharePic = Yii::app()->basePath . '/../uploads/app_dayPush';
					if (!file_exists(dirname($uploadDirSharePic . '/' . $model->sSharePic)))
						mkdir(dirname($uploadDirSharePic . '/' . $model->sSharePic), 0777, true);
					$sSharePic->saveAs($uploadDirSharePic . '/' . $model->sSharePic, true);
				}
				$model->save();
				//更新缓存
				$model->getMemcachePush();
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('index','id'=>$model->iId));
			}
		}

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['AppDayPush']))
		{
			if(!empty($_FILES['AppDayPush']['name']['sImages']))
				$_POST['AppDayPush']['sImages'] = '2'; //过验证用的
			$sPath=CUploadedFile::getInstance($model,'sImages');
			if (!$sPath)
				unset($_POST['AppDayPush']['sImages']);
			$sSharePic=CUploadedFile::getInstance($model,'sSharePic');
			if (!$sSharePic)
				unset($_POST['AppDayPush']['sSharePic']);
			$model->attributes=$_POST['AppDayPush'];
			if($model->save()) {
				// 处理资源上传
				if($sPath) {
					$model->sImages= '/'.$model->iId.rand(1000,9999).'.'.$sPath->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/app_dayPush';
					if (!file_exists(dirname($uploadDir . '/' . $model->sImages)))
						mkdir(dirname($uploadDir . '/' . $model->sImages), 0777, true);
					$sPath->saveAs($uploadDir . '/' . $model->sImages, true);
					$model->save();
				}
				if($sSharePic) {
					$model->sSharePic= '/sSharePic'.$model->iId.rand(1000,9999).'.'.$sSharePic->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/app_dayPush';
					if (!file_exists(dirname($uploadDir . '/' . $model->sSharePic)))
						mkdir(dirname($uploadDir . '/' . $model->sSharePic), 0777, true);
					$sSharePic->saveAs($uploadDir . '/' . $model->sSharePic, true);
					$model->save();
				}
				//更新缓存
				$model->getMemcachePush();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->iId));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$id = intval($_GET['iId']);
		$this->loadModel($id)->delete();
		$this->redirect('/app/DayPush/index');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new AppDayPush();
		$list = $model->getAll();
		$this->render('index',array(
			'model'=>$model,
			'list'=>$list,
		));
	}
	
	/**
	 * 获取当天主推
	 */
	public function actionGetday(){
		$return = array('ret'=>0,'sub'=>0,'msg'=>'Success');
		$iId = $_POST['iId'];
		$model = new AppDayPush();
		$list = $model->getDay($iId);
		if(!empty($list)){
			$return['data'] = $list; 
		}else{
			$return['empty'] = 1;
		}
		echo json_encode($return);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AppDayPush the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AppDayPush::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AppDayPush $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='app-day-push-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}