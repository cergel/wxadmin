<?php

class FulisheController extends Controller
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
            array(
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','index','delete'),  //'users'=>array('@'), // 这个为啥不生效？
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
        $model=new Fulishe;

		if(isset($_POST['Fulishe']))
		{
			$model->attributes=$_POST['Fulishe'];
            $pic=CUploadedFile::getInstance($model,'sImages');
            if($pic)
                $model->sImages=rand(1000, 9999).'sImages.'.$pic->getExtensionName();//获取文件名
            else
            	$model->sImages ='';
			$model->iStatus = 1;
            if($model->save()) {
            	//生成目录地址
            	$targetDir = Yii::app()->params['Fulishe']['target_dir'] . '/' . $model->iId;
            	//图片处理
            	if ($pic) {
            	    if (!file_exists($targetDir))
            	        mkdir($targetDir, 0777, true);
            		$pic->saveAs($targetDir . '/' . $model->sImages, true);
            	}
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('update','id'=>$model->iId));
            }
		}
		$model->iStatus = 1;
		$model->iTag = 1;
		$model->iOrder = 100;
        $this->render('create', array(
            'model' => $model,
        ));

	}

	/**
	 * Updates a particular model.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Fulishe'])) {
			$pic=CUploadedFile::getInstance($model,'sImages');
			if (!$pic)
				unset($_POST['Fulishe']['sImages']);
            $model->setAttributes($_POST['Fulishe']);
            if($model->validate()){
            	//图片
                if ($pic) {
                    $model->sImages = 'sImages'.rand(1000,9999).'.' . $pic->getExtensionName();;
                }
				$model->iStatus = 1;
                if ($model->save()) {
                	//生成图片
                    $targetDir = Yii::app()->params['Fulishe']['target_dir'] . '/' . $model->iId;
                	if ($pic) {
                		$pic->saveAs($targetDir . '/' . $model->sImages, true);
                	}
                    Yii::app()->user->setFlash('success', '活动模板更新成功');
                    $this->redirect(array('update', 'id' => $model->iId));
                }
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Fulishe('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Fulishe']))
			$model->attributes=$_GET['Fulishe'];
		$this->render('index',array(
			'model'=>$model,
		));
	}


	/**
	 * @param integer $id the ID of the model to be loaded
	 * @return ActivePage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Fulishe::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ActivePage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='active-page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
