<?php
class AdController extends Controller
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
				'actions'=>array('index','create','update','delete','_status_update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * ajax上下线
     */
    public function action_status_update($id){
        $model=$this->loadModel($id);
        if ($model->iStatus == '1')
            $model->iStatus = '0';
        else
            $model->iStatus = '1';
        $model->save();
        Ad::createJson();
        echo $model->iStatus;
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ad;
        $selectedCinemas = $selectedMovies = array();

		if(isset($_POST['Ad']))
		{
			$model->attributes=$_POST['Ad'];
            if ( isset( $_POST['cinemas'] ) ) {
                $selectedCinemas = $_POST['cinemas'];
            }
            if ( isset( $_POST['movies'] ) ) {
                $selectedMovies = $_POST['movies'];
            }
            if ($hasConflict = $model->checkConflict()) {
                $model->addError('iStartAt', '当前广告与ID为'.$hasConflict.'的广告冲突,请查验后再创建。');
            }
			if(!$hasConflict && $model->save()) {
                // 处理影城关联
                if ( isset( $_POST['cinemas'] ) )
                foreach ( $_POST['cinemas'] as $k => $cinema )
                {
                    $ac = new AdCinema();
                    $ac->iAdID     = $model->iAdID;
                    $ac->iCinemaID = $cinema;
                    $ac->save();
                }
                // 处理影片关联
                if ( isset( $_POST['movies'] ) )
                foreach ( $_POST['movies'] as $k => $movie )
                {
                    $am = new AdMovie();
                    $am->iAdID    = $model->iAdID;
                    $am->iMovieID = $movie;
                    $am->save();
                }
                // 处理资源上传
                $sPath=CUploadedFile::getInstance($model,'sPath');
                if($sPath) {
                    $model->sPath=$model->iAdID . '.' . $sPath->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/ad/'.date('Y-m-d', $model->iCreated);
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $sPath->saveAs($uploadDir . '/' . $model->sPath, true);
                    $model->save();
                }
                Ad::createJson();
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$model->iAdID));
			}
		}

		$this->render('create',array(
			'model'=>$model,
            'selectedCinemas' => $selectedCinemas,
            'selectedMovies' => $selectedMovies
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
        $selectedCinemas=$selectedMovies=array();
        if (is_array($model->cinemas))
            foreach($model->cinemas as $result) {
                $selectedCinemas[] = $result['iCinemaID'];
            }
        if (is_array($model->movies))
            foreach($model->movies as $result) {
                $selectedMovies[] = $result['iMovieID'];
            }
		if(isset($_POST['Ad']))
		{
            $sPath = CUploadedFile::getInstance($model, 'sPath');
            if (!$sPath)
                unset($_POST['Ad']['sPath']);
			$model->attributes=$_POST['Ad'];
            if (isset($_POST['cinemas']))
                $selectedCinemas = $_POST['cinemas'];
            if (isset($_POST['movies']))
                $selectedCinemas = $_POST['movies'];
            if ($hasConflict = $model->checkConflict()) {
                $model->addError('iStartAt', '当前广告与ID为'.$hasConflict.'的广告冲突,请查验后再保存。');
            }
			if(!$hasConflict && $model->save()) {
                // 处理影城关联
                AdCinema::model()->deleteAllByAttributes(array('iAdID'=>$model->iAdID));
                if (isset($_POST['cinemas'])) {
                    foreach ($_POST['cinemas'] as $k=>$cinema) {
                        $ac = new AdCinema();
                        $ac->iAdID     = $model->iAdID;
                        $ac->iCinemaID = $cinema;
                        $ac->save();
                    }
                }
                // 处理影片关联
                AdMovie::model()->deleteAllByAttributes(array('iAdID'=>$model->iAdID));
                if (isset($_POST['movies'])) {
                    foreach ($_POST['movies'] as $k=>$movie) {
                        $am = new AdMovie();
                        $am->iAdID    = $model->iAdID;
                        $am->iMovieID = $movie;
                        $am->save();
                    }
                }
                // 处理资源上传
                if($sPath) {
                    $model->sPath = $model->iAdID.'.'.$sPath->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/ad/'.date('Y-m-d', $model->iCreated);
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $sPath->saveAs($uploadDir . '/' . $model->sPath, true);
                    $model->save();
                }
                Ad::createJson();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->iAdID));
			}
		}

		$this->render('update',array(
			'model'=>$model,
            'selectedCinemas' => $selectedCinemas,
            'selectedMovies' => $selectedMovies
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
        Ad::createJson();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Ad('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ad']))
			$model->attributes=$_GET['Ad'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ad the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ad::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ad $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ad-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
