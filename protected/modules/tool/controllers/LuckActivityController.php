<?php
class LuckActivityController extends Controller
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
				'actions'=>array('index','create','update','Physical','detail','placepush','delete','uploadimage','_status_update'),
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
		$model=new LuckActivity;

		if(isset($_POST['LuckActivity']))
		{
			$model->attributes=$_POST['LuckActivity'];
			if($model->save()) {
				//$this->updatefrontCover($model);
				$this->updateGoods($model);
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$model->iId));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * @tudo 冗余太多、以后处理
	 * 奖品更新
	 * 默认不删除
	 */
	private function updateGoods($model,$isDel=0){
		$isDel=0;
		////先删除、再插入
		//LuckGoods::model()->deleteAll('iActivityId = :id',array(':id'=>$model->iId));
		//插入奖品表
		$goodsCModel = new LuckGoods;
		if(!empty($model)){
			if(!empty($_POST['goods'])){
				//普奖暂时制空
				$ids = array();
				$goodsCModel->updateiGeneral($model->iId);
					foreach($_POST['goods'] as $key=>$info){
						if(empty($info)) continue;
						if(!empty($info['iId'])){
							$goodsModel = LuckGoods::model()->findByPk($info['iId']);
							$ids[] = $info['iId'];
						}else{
							$goodsModelClone = clone $goodsCModel;
						}
						$goodsData['iActivityId'] 		= $model->iId;
						$goodsData['sPrizeName']  		= $info['sPrizeName'];
						$goodsData['sImages'] 	  		= $info['sImages'];
						$goodsData['sFrontcoverImage']	= $info['sFrontcoverImage'];
						$goodsData['iType']       		= $info['iType'];
						$goodsData['sKudoName']   		= $info['sKudoName'];
						$goodsData['iMoney']	  		= $info['iMoney'];
						$goodsData['iBonusId']    		= $info['iBonusId'];
						$goodsData['iProbability']		= $info['iProbability'];
						$goodsData['iPeopleStint']		= $info['iPeopleStint'];
						$goodsData['iDayNum'] 	  		= $info['iDayNum'];
						$goodsData['iGoodsCount'] 		= $info['iGoodsCount'];
						$goodsData['iGalleryNum']		= $info['iGalleryNum'];
						$goodsData['iCreated']    		= time();
						//查询普奖
						if($_POST['LuckActivity']['iGeneral']==1){
							if($key==$_POST['iGeneralCheck']){
								$goodsData['iGeneral'] = 1;
							}else{
								$goodsData['iGeneral'] = 0;
							}
						}

						if(!empty($info['iId'])){
							$goodsModel->setAttributes($goodsData);
							$goodsModel->save();
						}else{
							$goodsModelClone->setAttributes($goodsData);
							$goodsModelClone->save();
							$ids[] = $goodsModelClone->attributes['iId'];
						}
						unset($goodsData); //重置变量
					}
					
					//删除
					$goodsCModel->deleteGoods($model->iId, $ids);
			}
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['LuckActivity']))
		{
			$model->attributes=$_POST['LuckActivity'];
			if($model->save()) {
				$this->updateGoods($model,1);
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->iId));
			}
		}
		//查询奖项设置
		$goodsList = LuckGoods::model()->findAll ('iActivityId = :id',array(':id'=>$model->iId));
		$this->render('update',array(
			'model'     =>$model,
			//'imagesList'=>$imagesList,
			'goodsList' =>$goodsList,
		));
	}
	
	/**
	 * 中奖统计页面
	 * @param unknown_type $id
	 */
	public function actionDetail($id){
		$id = intval($id);
		$model=new LuckActivity;
		$goodsModel = new LuckGoods;
		$userGoodsModel = new GoodsUser;
		$goodsList = $goodsModel->getActivityGoods($id);
		$userGoodsList = $userGoodsModel->getActivityUserGoodsList($id);
		$dateArr = array();
		foreach($userGoodsList as $info){
			$dateArr[$info['year_day']][$info['iGoodsId']] = $info['num']; 
		}
		
		$this->render('detail',
				array('model'=>$model,'goodsList'=>$goodsList,'dateArr'=>$dateArr)
		);
	}

	/**
	 * 查看实物中奖
	 */
	public function actionPhysical($id){
		$goodsModel = new GoodsUser;
		$time       = intval($_GET['time']);
		$goodsId    = intval($_GET['goodsId']); 
		$list = $goodsModel->getGoodsPhysical($id,$time,$goodsId);
		$this->render('Physicaldetail',array('list'=>$list
		));
	}
	
	/**
	 * 查看地推中奖
	 */
	public function actionPlacepush($id){
		$goodsModel = new GoodsUser;
		$time       = intval($_GET['time']);
		$goodsId    = intval($_GET['goodsId']); 
		$list = $goodsModel->getGoodsPlacepush($id,$time,$goodsId);
		$this->render('placepush',array('list'=>$list
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
		$model=new LuckActivity('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LuckActivity']))
			$model->attributes=$_GET['LuckActivity'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LuckActivity the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LuckActivity::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LuckActivity $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='luck-activity-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * 上传代码
	 */
	public function actionUploadimage(){
		$return = array('ret'=>0,'sub'=>0,'date'=>date('Y-m-d',time()),'name'=>'');
		$model = new LuckActivity;
		// 处理封面上传
		$sPicture=CUploadedFile::getInstanceByName('Filedata');
		if($sPicture) {
			$name=time().'.'.$sPicture->getExtensionName();
			$uploadDir = Yii::app()->basePath . '/../uploads/luck/'.date("Y-m-d").'/';
			if (!file_exists($uploadDir))
				mkdir($uploadDir, 0777, true);
			$save = $sPicture->saveAs($uploadDir . '/' . $name, true);
			if($save){
				$return['name'] = date("Y-m-d",time()).'/'.$name;
			}else{
				$return['ret'] = -1;
				$return['sub'] = -11;
			}
			echo json_encode($return);
		}
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
		$bannerModel = new LuckActivity();
		echo $model->iStatus;
	}
}
