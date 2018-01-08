<?php

class ActivePageOtherController extends Controller
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
//            array(
//                'application.components.ActionLog'
//            ),
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
				'actions'=>array('push','getWxActivePageForQA'),  //'users'=>array('@'), // 这个为啥不生效？
                'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * 获取字段信息
	 * @return array
	 */
	private function getData()
	{
		return self::getField([]);
	}
	/**
	 * 判断字段，
	 */
	private function getField($arrData){
		$field = [
			'sName',
			'iActivePageID',
			'sNotice',
		];
		foreach($field as $val){
			if(isset($_POST[$val])){
				$arrData[$val] = $_POST[$val];
			}
		}
		if(empty($arrData['sName'])){
			$arrData['sName'] = '1';
		}
        $arrData['sContent'] = 'a1';
        $arrData['iType'] = '1';
        $arrData['iChannel'] = '2';
        $arrData['iAppWill'] = '0';
        $arrData['sDirector'] = $arrData['sDirectorPhone'] = '123';
        $arrData['iShowStartTime'] = $arrData['iShowEndTime'] = 1;
        $arrData['sUpdatedName'] ='show';
        $arrData['sUpdatedName'] = time();
        unset($arrData['sFooterText']);
        unset($arrData['sNotice']);
		return self::isMaster($arrData);
	}

	/**
	 * 判断必须字段
	 * @param array $arrData
	 * @return array
	 */
	private function isMaster($arrData = []){
		$field = [
			'sName',
			'sTitle',
			'iTime',
			'iPreheatEndTime',
			'iEndTime',
			'sRule',
			'iMovieId',
			'sShareTitle',
			'sShareContent',
			'iwx',
			'iqq',
			'imobile',
			'sPic',
			'sSharePic',
			'iResourceID',
            'iShowStartTime',
            'iShowEndTime',
		];
		foreach($field as $val){
			if(isset($_POST[$val])){
				$arrData[$val] = $_POST[$val];
			}else{
				$arrData = [];
				break;
			}
		}
		//处理活动
		$arrData['iResourceID'] = explode(',',$arrData['iResourceID']);
		$arrData['iResourceID'] = array_filter($arrData['iResourceID']);
		if(empty($arrData['iResourceID'])){
			$arrData=[];
		}
		return $arrData;
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionPush()
	{
		Log::model()->logFile('active_other',json_encode($_POST));
		$arrData = self::getData();
		$res = ['ret'=>-1,'sub'=>-1,'msg'=>'参数不全，请重新提交参数','data'=>[]];
		if(empty($arrData)){
			echo json_encode($res);
			exit;
		}
		if(!empty($arrData['iActivePageID'])){
			$model=$this->loadModel($arrData['iActivePageID']);
			//if(empty($model) || $model->iCchannel == '2'){
			if(empty($model)){
				$res['sub'] = -2;
				$res['msg'] = '错误的活动ID';
				echo json_encode($res);
				exit;
			}
		}else{
			unset($arrData['iActivePageID']);
			$model = new ActivePage;
		}
		//图片处理
		$sPic = base64_decode($arrData['sPic']);
		$sSharePic = base64_decode($arrData['sSharePic']);
		$iResourceID = $arrData['iResourceID'];
		unset($arrData['iResourceID']);
        $arrData['sPic'] = 'banner.jpg';
        $arrData['sSharePic'] = 'share.jpg';
//        $arrData['sPic'] = $arrData['sSharePic'] = '';
//        unset($arrData['sPic']);
//        unset($arrData['sSharePic']);
        foreach ($arrData as $k=>$v){
            $model->$k = $v;
        }
		if($model->validate() && $model->save()){
			//保存关联活动
			ActivePageBonusResource::model()->deleteAllByAttributes(array('iActivePageID' => $model->iActivePageID));
			foreach($iResourceID as $val){
				if(empty($val)){
					continue;
				}
				$ab = new ActivePageBonusResource();
				$ab->iResourceID   = $val;
				$ab->iActivePageID = $model->iActivePageID;
				$ab->save();
			}
			//处理生成h5
			$targetDir = Yii::app()->params['active_page_new']['target_dir'] . '/' . $model->iActivePageID;
			$arrDataInfo =["active_page_new"];
            $model->makeFile();
			//图片处理
			if ($sPic) {
				//循环拷贝其他地址的图片
				foreach ($arrDataInfo as $value)
				{
					UploadFiles::createPath( Yii::app()->params[$value]['target_dir'] . '/' . $model->iActivePageID.'/images/');
					file_put_contents(Yii::app()->params[$value]['target_dir'] . '/' . $model->iActivePageID.'/images/' . $model->sPic,$sPic);
					file_put_contents(Yii::app()->params[$value]['target_dir'] . '/' . $model->iActivePageID.'/images/' . $model->sSharePic,$sSharePic);
				}
			}
			$arrData = [];
			if($model->iwx){
				$arrData['weixin'] = [
					'url'=>Yii::app()->params['active_page_new']['final_url']. '/' . $model->iActivePageID.'/index.html',
				];
			}
			if($model->iqq){
				$arrData['qq'] = [
					'url'=>Yii::app()->params['active_page_new']['final_url']. '/' . $model->iActivePageID.'/index.html',
				];
			}
			if($model->imobile){
				$arrData['app'] = [
					'url'=>Yii::app()->params['active_page_new']['final_url']. '/' . $model->iActivePageID.'/index.html',
				];
			}
            FlushCdn::setUrlToRedis(Yii::app()->params['active_page_new']['final_url']. '/' . $model->iActivePageID.'/index.html');
			$arrData['iActivePageID'] = $model->iActivePageID;
			$res['ret'] = 0;
			$res['sub'] = 0;
			$res['msg'] = 'success';
			$res['data'] = $arrData;
		}else{
			$res['sub'] = -3;
			$res['msg'] = '写入DB失败';

		}
		Log::model()->logFile('active_other',json_encode($res));
		echo json_encode($res);
		exit;
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ActivePage the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ActivePage::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


    /**
     * 给qa 自动化测试用的接口，to 樊亮亮
     * 开发：刘龙
     * 备注：仅限内网访问
     */
	public function ActionGetWxActivePageForQA()
    {
        $time = time();
        $sql = "select t_weixin_active_page.iActivePageID,sName,iMovieId,t_weixin_active_page_bonus_resource.iResourceID,iChannel from t_weixin_active_page left join t_weixin_active_page_bonus_resource on t_weixin_active_page.iActivePageID = t_weixin_active_page_bonus_resource.iActivePageID  where t_weixin_active_page.iPreheatEndTime <= $time and t_weixin_active_page.iEndTime >= $time and t_weixin_active_page.iType = 1 and t_weixin_active_page.iDeleted = '0'";
        $arrData = ActivePage::model()->getInfoBySql($sql);
        $arrRet = [];
        if(!empty($arrData)){
            foreach ($arrData as $val){
                if(isset($arrRet[$val['iActivePageID']])){
                    $arrRet[$val['iActivePageID']]['actives'][] = $val['iResourceID'];
                }else{
                    $val['actives'] = [$val['iResourceID']];
                    unset($val['iResourceID']);
                    $arrRet[$val['iActivePageID']] = $val;
                }
            }
        }
        $arrRet = array_values($arrRet);
        echo json_encode($arrRet);
        exit;
    }

}
