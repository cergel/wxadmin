<?php

class CinemaNotificationController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update','delete','create','index','_duplicate'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * ajax复制
     */
    public function action_duplicate($id){
        $model=$this->loadModel($id);
        $new = $model->duplicate();
        if ($new) {
            echo $new->iNotificationID;
        } else {
            echo 0;
        }
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionCreate()
	{
		$model=new CinemaNotification;
        $selectedCinemas=array();
		if(isset($_POST['CinemaNotification']))
		{
			if (isset($_POST['CinemaNotification']['channel'])){
				$selectedChannel = $_POST['CinemaNotification']['channel'];
				unset($_POST['CinemaNotification']['channel']);
			}
			$model->attributes=$_POST['CinemaNotification'];
            if (isset($_POST['cinemas'])){
                $selectedCinemas = $_POST['cinemas'];
                unset($_POST['cinemas']);
            }
			if($model->isTXKwyWord()){
				Yii::app()->user->setFlash('error','含有敏感词，请仔细检查后重新填写');
				$this->redirect(array('create'));
				return;
			}
			if($model->save()) {
                // 处理影城关联
                if (!empty($selectedCinemas)) {
                    foreach ($selectedCinemas as $k=>$cinema) {
                        $cnc = new CinemaNotificationCinema();
                        $cnc->iNotificationID = $model->iNotificationID;
                        $cnc->iCinemaID = $cinema;
                        $cnc->save();
                    }
                }
                //处理关联渠道
                if (!empty($selectedChannel))
                	foreach ($selectedChannel as $iChannelId) {
                		if (empty($iChannelId)) continue;
                		$cnc = new CinemaNotificationChannel();
                		$cnc->iNotificationID = $model->iNotificationID;
                		$cnc->iChannelID = $iChannelId;
                		$cnc->save();
                	}
                CinemaNotification::model()->saveCache();
                Yii::app()->user->setFlash('success','公告创建成功');
                $this->redirect(array('index'));
            }
		}
		$this->render('create',array(
			'model'=>$model,
            'selectedCinemas' => $selectedCinemas
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $selectedCinemas=array();
        //echo "<pre>";
        //var_dump(Yii::app()->cache->get('cinema_notification'));
        //echo "</pre>";
        if (is_array($model->cinemas))
            foreach($model->cinemas as $result) {
                $selectedCinemas[] = $result['iCinemaID'];
            }
        $selectedChannel = [];
        if (is_array($model->channel)){
        	foreach($model->channel as $results) {
        		$selectedChannel[] = $results['iChannelID'];
        	}
        	if (count($selectedChannel) == count($model->getAppkey())-1)
        		$selectedChannel[] ='0';
        }
        $model->channel = $selectedChannel;
		if(isset($_POST['CinemaNotification']))
		{
			if (isset($_POST['CinemaNotification']['channel'])){
				$selectedChannel = $_POST['CinemaNotification']['channel'];
				unset($_POST['CinemaNotification']['channel']);
			}
			$model->attributes=$_POST['CinemaNotification'];
            if (isset($_POST['cinemas']))
                $selectedCinemas = $_POST['cinemas'];
			if($model->isTXKwyWord()){
				Yii::app()->user->setFlash('error','含有敏感词，请仔细检查后重新填写');
				$this->redirect(array('update','id'=>$model->iNotificationID));
				return;
			}
			if($model->save()) {
                // 处理影城关联
                CinemaNotificationCinema::model()->deleteAllByAttributes(array(
                    'iNotificationID'=>$model->iNotificationID
                ));
                if (isset($_POST['cinemas'])) {
                    foreach ($_POST['cinemas'] as $k=>$cinema) {
                        $cnc = new CinemaNotificationCinema();
                        $cnc->iNotificationID = $model->iNotificationID;
                        $cnc->iCinemaID = $cinema;
                        $cnc->save();
                    }
                }
                //处理渠道关联
                CinemaNotificationChannel::model()->deleteAllByAttributes(array(
                		'iNotificationID'=>$model->iNotificationID
                ));
                if (!empty($selectedChannel))
	                foreach ($selectedChannel as $iChannelId) {
	                	if (empty($iChannelId)) continue;
	                	$cnc = new CinemaNotificationChannel();
	                	$cnc->iNotificationID = $model->iNotificationID;
	                	$cnc->iChannelID = $iChannelId;
	                	$cnc->save();
	                }
                CinemaNotification::model()->saveCache();
                Yii::app()->user->setFlash('success','公告更新成功');
                $this->redirect(array('index'));
            }
		}
		$this->render('update',array(
			'model'=>$model,
            'selectedCinemas' => $selectedCinemas,
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
        $model=new CinemaNotification('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['CinemaNotification']))
            $model->attributes=$_GET['CinemaNotification'];

        $this->render('index',array(
            'model'=>$model,
        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CinemaNotification the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CinemaNotification::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CinemaNotification $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cinema-notification-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
