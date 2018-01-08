<?php
class FindController extends Controller
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
				'actions'=>array('index','create','update','delete','checkTime'),
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
		$model=new Find;
		if(isset($_POST['Find']))
		{
			$model->attributes=$_POST['Find'];
			//print_r($model->attributes);exit;
			if($model->save()) {
				$sPicture=CUploadedFile::getInstance($model,'picture');
				if($sPicture) {
					$model->picture=$model->id.rand(100,999).'.'.$sPicture->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/weixin_find/' . $model->id;
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$sPicture->saveAs($uploadDir . '/' . $model->picture, true);
					$model->save();
				}
				$model->saveJson();
				Find::saveJsonS();
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$model->id));
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
		if(isset($_POST['Find']))
		{
			$sPicture = CUploadedFile::getInstance($model, 'picture');
			if (!$sPicture)
				unset($_POST['Find']['picture']);
			$model->attributes=$_POST['Find'];
			if($model->save()) {
				// 处理封面上传
				if($sPicture) {
					$model->picture=$model->id.rand(100,999).'.'.$sPicture->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/weixin_find/' . $model->id;
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$sPicture->saveAs($uploadDir . '/' . $model->picture, true);
					$model->save();
				}
				Find::saveJsonS();
				$model->saveJson();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
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
	    $model=$this->loadModel($id);
		$this->loadModel($id)->delete();
		Find::saveJsonS();
		$model->saveJson();
		// if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Find('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Find']))
			$model->attributes=$_GET['Find'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MovieOrder the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Find::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param MovieOrder $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='movie-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    //ajax接口，
    public function actionCheckTime(){
        $iStartTime = strtotime($_POST['startTime']);
        $iEndTime = strtotime($_POST['endTime']);
        if ($iStartTime >= $iEndTime){
            $return = ['succ'=>0,'msg'=>'开始时间不能大于结束时间'];
        }else{
            $return = ['succ'=>1,'msg'=>'成功'];
        }
        echo json_encode($return);
    }
}
