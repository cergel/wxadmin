<?php
class VersionFromController extends Controller
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
				'actions'=>array('index','create','update','delete'),
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
		$model=new VersionFrom;
		if(isset($_POST['VersionFrom']))
		{
			$model->attributes=$_POST['VersionFrom'];
			if($model->save()) {
                // 处理资源上传
                $path=CUploadedFile::getInstance($model,'path');
                if (!empty($_FILES['VersionFrom']['name']['path'])){
                	$f_name = $_FILES['VersionFrom']['name']['path'];
                }
                if($path) {
                	$f_name = !empty($f_name)?$f_name:$model->id.'.'.$path->getExtensionName();
                	$model->path = $f_name;
                    $uploadDir = Yii::app()->params['app_version_from']['target_dir'].'/'.$model->id;
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $path->saveAs($uploadDir . '/' . $model->path, true);
                    $model->save();
                }
                VersionFrom::model()->saveJson();
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('index'));
			}
		}
		$model->fromId = '';
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

		if(isset($_POST['VersionFrom']))
		{
            $path = CUploadedFile::getInstance($model, 'path');
            if (!empty($_FILES['VersionFrom']['name']['path'])){
            	$f_name = $_FILES['VersionFrom']['name']['path'];
            }
            unset($_POST['VersionFrom']['path']);
			$model->attributes=$_POST['VersionFrom'];
			if($model->save()) {
                // 处理资源上传
                if($path) {
                	if (is_file(Yii::app()->params['app_version_from']['target_dir'].'/'.$model->id.'/'.$model->path)){
                		unlink(Yii::app()->params['app_version_from']['target_dir'].'/'.$model->id.'/'.$model->path);
                	}
                	$f_name = !empty($f_name)?$f_name:$model->id.'.'.$path->getExtensionName();
                    $model->path = $f_name;
                    $uploadDir = Yii::app()->params['app_version_from']['target_dir'].'/'.$model->id;
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $path->saveAs($uploadDir . '/' . $model->path, true);
                    $model->save();
                }
				VersionFrom::model()->saveJson();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new VersionFrom('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Version']))
			$model->attributes=$_GET['Version'];

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
		$model = $this->loadModel($id);
		if (is_file(Yii::app()->basePath . '/../uploads/app_version_from/'.$model->id.'/'.$model->path)){
			unlink(Yii::app()->basePath . '/../uploads/app_version_from/'.$model->id.'/'.$model->path);
		}
		$this->loadModel($id)->delete();
		VersionFrom::model()->saveJson();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * @param integer $id the ID of the model to be loaded
	 * @return Version the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=VersionFrom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Version $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='version-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
