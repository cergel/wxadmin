<?php
class VersionController extends Controller
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
		$model=new Version;
		if(isset($_POST['Version']))
		{
			$model->attributes=$_POST['Version'];
			if($model->save()) {
				// 处理图片上传
				$img=CUploadedFile::getInstance($model,'img');
				if($img) {
					$f_name = $model->id.'.'.$img->getExtensionName();
					$model->img = $f_name;
					$uploadDir = Yii::app()->basePath . '/../uploads/app_version/'.$model->id;
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$img->saveAs($uploadDir . '/' . $model->img, true);
					$model->save();
				}
				
				if ($model->itype == 9){
	                // 处理资源上传
	                $path=CUploadedFile::getInstance($model,'path');
	                if (!empty($_FILES['Version']['name']['path'])){
	                	$f_name = $_FILES['Version']['name']['path'];
	                }
	                if($path) {
	                	$f_name = !empty($f_name)?$f_name:$model->id.'.'.$path->getExtensionName();
	                	$model->path = $f_name;
	                    $uploadDir = Yii::app()->basePath . '/../uploads/app_version/'.$model->id;
	                    if (!file_exists($uploadDir))
	                        mkdir($uploadDir, 0777, true);
	                    $path->saveAs($uploadDir . '/' . $model->path, true);
	                    $model->md5 = md5_file($uploadDir . '/' . $model->path);
	                    $model->path = $model->id."/".$model->path;
	                    $model->save();
	                }
				}else {
					$model->path = $_POST['Version']['path'];
					$model->save();
				}
//				Version::model()->saveMemcache();
                Version::model()->saveRedis($model->itype, $model->version);
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('index'));
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
		if(isset($_POST['Version']))
		{
			
            $path = CUploadedFile::getInstance($model, 'path');
            if (!empty($_FILES['Version']['name']['path'])){
            	$f_name = $_FILES['Version']['name']['path'];
            }
			// 处理图片上传
			$img=CUploadedFile::getInstance($model,'img');
			if (!$img){
				unset($_POST['Version']['img']);
			}
			$model->attributes=$_POST['Version'];
			if($img) {
				$model->img = $model->id.rand(1000,9999).'.'.$img->getExtensionName();
				$uploadDirImg = Yii::app()->basePath . '/../uploads/app_version/'.$model->id;
				if (!file_exists($uploadDirImg))
					mkdir($uploadDirImg, 0777, true);
				$img->saveAs($uploadDirImg . '/' . $model->img, true);
				$model->save();
			}
			if($model->save()) {
				if ($model->itype ==9){
	                // 处理资源上传
	                if($path) {
	                	$f_name = !empty($f_name)?$f_name:$model->id.'.'.$path->getExtensionName();
	                    $model->path = $f_name;
	                    $uploadDir = Yii::app()->basePath . '/../uploads/app_version/'.$model->id;
	                    if (!file_exists($uploadDir))
	                        mkdir($uploadDir, 0777, true);
	                  //  echo $uploadDir . '/' . $model->path;exit;
	                    $path->saveAs($uploadDir . '/' . $model->path, true);
	                    $model->md5 = md5_file($uploadDir . '/' . $model->path);
	                    $model->path = $model->id."/".$model->path;
	                    $model->save();
	                }
				}else {
					$model->path = $_POST['Version']['path'];
					$model->save();
				}
//				Version::model()->saveMemcache();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('index'));
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
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
//		Version::model()->saveMemcache();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Version('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Version']))
			$model->attributes=$_GET['Version'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Version the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Version::model()->findByPk($id);
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
