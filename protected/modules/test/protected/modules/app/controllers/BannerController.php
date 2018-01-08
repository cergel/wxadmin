<?php
class BannerController extends Controller
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
				'actions'=>array('index','create','update','delete','_sort_update','_status_update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * ajax编辑排序
     */
    public function action_sort_update($id){
        $model=$this->loadModel($id);
        $model->iSort = intval($_POST['sort']);
        $model->save();
        //更新缓存
        $bannerModel = new Banner();
        $bannerModel->saveMemcache();
        //Banner::createJson();
        echo $model->iSort;
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
        //更新缓存
        $bannerModel = new Banner();
        $bannerModel->saveMemcache();
        //Banner::createJson();
        echo $model->iStatus;
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Banner;
        $selectedCities = array();
		if(isset($_POST['Banner']))
		{
			$model->attributes = $_POST['Banner'];
            if (isset($_POST['cities']))
                $selectedCities = $_POST['cities'];
			if($model->save()) {
                // 处理城市关联
                $model->saveCities();
                // 处理封面上传
                $sPicture=CUploadedFile::getInstance($model,'sPicture');
                if($sPicture) {
                    $model->sPicture=$model->iBannerID.'.'.$sPicture->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/app_banner/' . date('Y-m-d', $model->iCreated);
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $sPicture->saveAs($uploadDir . '/' . $model->sPicture, true);
                    $model->save();
                }
                //更新缓存
		        $bannerModel = new Banner();
		        $bannerModel->saveMemcache();
                //Banner::createJson();
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
            'selectedCities' => $selectedCities
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
        $selectedCities=array();
        if (is_array($model->cities))
            foreach($model->cities as $result) {
                $selectedCities[] = $result['iRegionNum'];
            }
		if(isset($_POST['Banner']))
		{
            $sPicture = CUploadedFile::getInstance($model, 'sPicture');
            if (!$sPicture)
                unset($_POST['Banner']['sPicture']);
			$model->attributes=$_POST['Banner'];
			if($model->save()) {
                // 处理城市关联
                $model->saveCities();
                // 处理封面上传
                if($sPicture) {
                    $model->sPicture=$model->iBannerID.'.'.$sPicture->getExtensionName();
                    $uploadDir = Yii::app()->basePath . '/../uploads/app_banner/' . date('Y-m-d', $model->iCreated);
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $sPicture->saveAs($uploadDir . '/' . $model->sPicture, true);
                    $model->save();
                }
                //Banner::createJson();
                //更新缓存
		        $bannerModel = new Banner();
		        $bannerModel->saveMemcache();
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->iBannerID));
			}
		}

		$this->render('update',array(
			'model'=>$model,
            'selectedCities' => $selectedCities
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
		//更新缓存
        $bannerModel = new Banner();
        $bannerModel->saveMemcache();
        //Banner::createJson();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Banner('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Banner']))
			$model->attributes=$_GET['Banner'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DiscoveryBanner the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Banner::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DiscoveryBanner $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='discovery-banner-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
