<?php
class MovieNewsController extends Controller
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
        MovieNews::model()->saveStatus();
        $model=$this->loadModel($id);
        if ($model->status == '1'){
            $model->status = '0';
            if($model->end_time > time())
                $model->end_time = time() -1;
            $model->save();
        }
        echo $model->status;
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new MovieNews();
        $selectedCities = array();

		if(isset($_POST['MovieNews']))
		{
            $arrData = $this->getPostInfo();
            $arrData['created'] = time();
            $model->attributes = $arrData;
            $sShareImg=CUploadedFile::getInstance($model,'share_img'); //获取表单名为filename的上传信息
            if ($sShareImg) {
                $model->share_img='/uploads/movie_news/share_'.date('YmdHis').rand(1000,9999).'.'.$sShareImg->getExtensionName();//获取文件名
            }
			if($model->save()) {
                if($sShareImg){
                    $targetDir = Yii::app()->params['AppnfsPath']['target_dir'];
                    $sShareImg->saveAs($targetDir  . $model->share_img, true);
                }
                //渠道
                $this->saveChannelInfo($model->id);
                //城市
                $this->saveCityInfo($model->id);
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}else{
            $model->new_type =1;
            $model->status =1;
        }
        MovieNews::model()->saveStatus();
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

        if(isset($_POST['MovieNews']))
        {
            $arrData = $this->getPostInfo();

            $sShareImg=CUploadedFile::getInstance($model,'share_img'); //获取表单名为filename的上传信息
            if ($sShareImg) {
                $model->share_img='/uploads/movie_news/share_'.date('YmdHis').rand(1000,9999).'.'.$sShareImg->getExtensionName();//获取文件名
            }
            unset($_POST['MovieNews']['share_img']);
            $model->attributes=$arrData;
            if($model->save()) {
                if($sShareImg){
                    $targetDir = Yii::app()->params['AppnfsPath']['target_dir'];
                    $sShareImg->saveAs($targetDir  . $model->share_img, true);
                }
                //渠道
                $this->saveChannelInfo($model->id);
                //城市
                $this->saveCityInfo($model->id);
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->id));
            }
        }
        MovieNews::model()->saveStatus();
        $channelUrl = [];
        if (is_array($model->channel)){
            $channel = [];
            foreach($model->channel as $result) {
                $channelUrl[$result['channel_id']] = $result['channel_url'];
                $channel[] = $result['channel_id'];
            }
            $model->channel = $channel;
        }
        $selectedCities = [];
        if (is_array($model->cities)){
            $cities = [];
            foreach($model->cities as $result) {
                $cities[] = $result['city_id'];
                if(!empty($result['city_id']))
                    $selectedCities[] = $result['city_id'];
            }
            $model->cities = $cities;
        }
        $model->start_time = date('Y-m-d H:i:s',$model->start_time);
        $model->end_time = date('Y-m-d H:i:s',$model->end_time);
        $this->render('update',array(
            'model'=>$model,
            'selectedCities' => $selectedCities,
            'channelUrl'=>$channelUrl,
        ));
    }
    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        MovieNews::model()->saveStatus();
        $this->delChannelInfo($id);
        $this->delCityInfo($id);
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
        MovieNews::model()->saveStatus();
        $model=new MovieNews('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MovieNews']))
            $model->attributes=$_GET['MovieNews'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }
    /**
     * 基础数据整理
     * @return mixed
     */
    private function getPostInfo()
    {
        $arrData = $_POST['MovieNews'];
        $arrData['start_time'] = strtotime($arrData['start_time']);
        $arrData['end_time'] = strtotime($arrData['end_time']);
        $arrData['updated'] = time();
        unset($arrData['share_img']);
        if($arrData['status'] == '0'){
            $arrData['end_time'] = $arrData['end_time'] < time()?$arrData['end_time']:time() -1;
        }
        if($arrData['end_time'] < time()){
            $arrData['status'] = '0';
        }
        return $arrData;
    }

    /**
     * 处理渠道
     * @param $id
     */
    private function saveChannelInfo($id)
    {
        $this->delChannelInfo($id);
        //插入
        if(isset($_POST['MovieNews']['channel'])){
            foreach($_POST['MovieNews']['channel'] as $val){
                $arrInfo = [];
                $arrInfo['channel_url'] = isset($_POST['channelUrl'.$val])?$_POST['channelUrl'.$val]:'';
                $arrInfo['n_id'] = $id;
                $arrInfo['channel_id'] = $val;
                $mvieNewsChannelsModel=new MovieNewsChannels();
                $mvieNewsChannelsModel->attributes = $arrInfo;
                $mvieNewsChannelsModel->save();
            }
        }
        return true;
    }


    /**
     * 删除
     * @param $id
     * @return int
     */
    private function delChannelInfo($id)
    {
    //先删除
       return MovieNewsChannels::model()->deleteAllByAttributes(array(
            'n_id'=>$id
        ));
    }

    /**
     * 处理城市
     * @param $id
     */
    private function saveCityInfo($id)
    {
        $this->delCityInfo($id);
        //插入
        if(!empty($_POST['cities'])){
            foreach($_POST['cities'] as $val){
                $arrInfo = [];
                $arrInfo['n_id'] = $id;
                $arrInfo['city_id'] = $val;
                $movieNewsCitysModel=new MovieNewsCitys();
                $movieNewsCitysModel->attributes = $arrInfo;
                $movieNewsCitysModel->save();
            }
        }else{
            $arrInfo = [];
            $arrInfo['n_id'] = $id;
            $arrInfo['city_id'] = 0;
            $movieNewsCitysModel=new MovieNewsCitys();
            $movieNewsCitysModel->attributes = $arrInfo;
            $movieNewsCitysModel->save();
        }
        return true;
    }

    /**
     * 删除城市
     * @param $id
     * @return int
     */
    private function delCityInfo($id)
    {
        //先删除
        return MovieNewsCitys::model()->deleteAllByAttributes(array(
            'n_id'=>$id
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
		$model=MovieNews::model()->findByPk($id);
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
