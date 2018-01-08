<?php
class PeeController extends Controller
{
	/**
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
		//	'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index','create','update','delete','delPee','getMovieNameByMovieId'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Pee('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pee']))
			$model->attributes=$_GET['Pee'];
		$this->render('index',array(
			'model'=>$model,
		));
	}



    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Pee();
        if(isset($_POST['Pee']))
        {
			$id = $this->savePee();
			$sql = "UPDATE t_pee SET t_pee.pee_num = (SELECT count(*) FROM t_pee_info WHERE t_pee.id = t_pee_info.t_id) where id=$id  ";
			PeeInfo::saveSql($sql);
			$this->loadModel($id)->saveCache($id);
            if($id) {
                Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$id));
            }
        }
		$peeInfo = '';
        $this->render('create',array(
            'model'=>$model,'peeInfo'=>$peeInfo
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
		if(isset($_POST['Pee']))
		{
			$id = $this->savePee($id);
			if($id) {
				$sql = "UPDATE t_pee SET t_pee.pee_num = (SELECT count(*) FROM t_pee_info WHERE t_pee.id = t_pee_info.t_id) where id=$id  ";
				PeeInfo::saveSql($sql);
				$this->loadModel($id)->saveCache($id);
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$id));
			}
		}
		$peeInfo = PeeInfo::model()->findAll("t_id=:t_id",['t_id'=>$model->id]);
		$this->render('update',array(
			'model'=>$model,'peeInfo'=>$peeInfo
		));
	}

	function savePee($id='')
	{
		if(!empty($id)){
			$arrPeeModel = Pee::model()->findByPk($id);
			if($arrPeeModel){
				$_POST['Pee']['updated'] = time();
			}
		}else{
			$arrPeeModel = new Pee();
			$_POST['Pee']['created'] = $_POST['Pee']['updated'] = time();
		}
		$arrPeeModel->attributes = $_POST['Pee'];
		$arrPeeModel->save();
		$pee_id = $_POST['pid'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$pee_start_info = $_POST['pee_start_info'];
		$pee_reason = $_POST['pee_reason'];
		$pee_error_info = $_POST['pee_error_info'];
		$base_pee_count = $_POST['base_pee_count'];
		$i = 0;
		foreach($pee_reason as $key=>$val){
		    if(empty($val))
		        continue;

			$arrPeeInfoData = [
				't_id'	=> $arrPeeModel->id,
				'start_time' =>$start_time[$key],
				'end_time' =>$end_time[$key],
				'pee_start_info' =>$pee_start_info[$key],
				'pee_reason' =>$pee_reason[$key],
				'pee_error_info' =>$pee_error_info[$key],
				'base_pee_count' =>$base_pee_count[$key],
				'updated'=>time(),
			];
			$peeInfoModel = PeeInfo::model()->findByPk($pee_id[$key]);
			if(!empty($pee_id[$key]) && !empty($peeInfoModel)){//更新
				$peeInfoModel->setAttributes($arrPeeInfoData);
			}else{//插入
				$arrPeeInfoData['created'] = $arrPeeInfoData['updated'];
				$peeInfoModel = new PeeInfo();
				//if($arrPeeInfoData['start_time'] == ''){
				//	continue;
				//}
				$peeInfoModel->setAttributes($arrPeeInfoData);
			}
			if($peeInfoModel->save()){
				$i++;
			}
		}
		$arrPeeModel->pee_num = $i;
		$arrPeeModel->save();
		return $arrPeeModel->id;
	}




	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Movie the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Pee::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * ajax接口，通过movieId获取影片信息
	 */
	public function actionGetMovieNameByMovieId(){
		$movieId = $_POST['movieId'];
		$url = API_MOVIEDATABASE.'/msdb/getMovieInfo.php';
		$sendData=[
			'movieId'=>$movieId,
			'from'=>7000100003,
			'channelId'=>'99'
		];
		$strJson = Https::getPost($sendData,$url);
		$obj = json_decode($strJson);
		if($obj->ret==0 && $obj->sub==0){
			if(!empty($obj->data->MovieNameChs)){
				$jsonOut = [
					'succ'=>1,
					'msg'=>$obj->data->MovieNameChs,
				];
			}else{
				$jsonOut = [
					'succ'=>0,
					'msg'=>'无此id对应的影片',
				];
			}
		}else{
			$jsonOut = [
				'succ'=>0,
				'msg'=>'媒资库请求失败请重试',
			];
		}
		echo json_encode($jsonOut);
	}

	/**
	 * 删除指定的
	 * @throws CDbException
	 */
	public function actionDelPee()
	{
		$pid = !empty($_POST['pid'])?$_POST['pid']:'';
		//$pid = !empty($_GET['pid'])?$_GET['pid']:'';
		$pid = intval($pid);
		$res= 1;
		if(!empty($pid)){
			$peeInfoModel = PeeInfo::model()->findByPk($pid);
			//更新缓存
			$movieId = $peeInfoModel->t_id;
			if($peeInfoModel){
				if(!$peeInfoModel->delete()){
					$res ='删除失败';
				}else {
					$sql = "UPDATE t_pee SET t_pee.pee_num = (SELECT count(*) FROM t_pee_info WHERE t_pee.id = t_pee_info.t_id) where id=$movieId  ";
					$peeInfo = PeeInfo::saveSql($sql);
					$this->loadModel($movieId)->saveCache($movieId);
				}
			}else{
				$res = '不存在的数据';
			}

		}else $res ='参数无效';
		echo $res;exit;
	}

	/**
	 * 删除尿点管理，同时删除尿点相关的信息
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		if($model){
			$peeInfo = PeeInfo::model()->findAll("t_id=:t_id",['t_id'=>$model->id]);
			foreach($peeInfo as $val){
				PeeInfo::model()->findByPk($val->p_id)->delete();
			}
			$model->saveCache($id,'del');
			$model->delete();
		}
		// if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	/**
	 * Performs the AJAX validation.
	 * @param Movie $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='movie-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
