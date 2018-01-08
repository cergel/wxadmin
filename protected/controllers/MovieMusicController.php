<?php
class MovieMusicController extends Controller
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
				'actions'=>array('index','create','update','delete','delPee','musicSearch','musicKeyWords','getMovieNameByMovieId'),
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
		$model=new MovieMusic('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MovieMusic'])) $model->attributes=$_GET['MovieMusic'];
		$data = $model->findAll();
		$this->render('index',array('model'=>$model,'items'=>$data));
	}

	/**
	 * 创建音乐方案
	 * @throws CHttpException
	 */
    public function actionCreate()
    {
        $model = new MovieMusic();
        if(isset($_POST['MovieMusic']))
        {
			$id = $this->_saveMusic();//保存音乐方案
			if($id){
				$cover=CUploadedFile::getInstance($model,'cover');
				$model=$this->loadModel($id);
				if($cover) {
					$model->cover= '/'.$id.'.'.$cover->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/MovieMusic';
					if (!file_exists(dirname($uploadDir . '/' . $model->cover)))
						mkdir(dirname($uploadDir . '/' . $model->cover), 0777, true);
					$cover->saveAs($uploadDir . '/' . $model->cover, true);
					$model->save();
				}
				$this->_saveMusicInfo($id);//保存音乐列表详情
				$this->loadModel($id)->saveCache($id);//设置缓存
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('index'));
			}
        }
        $this->render('create',array(
            'model'=>$model
        ));
    }

	/**
	 * 修改音乐方案
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['MovieMusic']))
		{
			$id = $this->_saveMusic($id);
			if($id) {
				$cover=CUploadedFile::getInstance($model,'cover');
				if($cover) {
					$model->cover= '/'.$id.'.'.$cover->getExtensionName();
					$uploadDir = Yii::app()->basePath . '/../uploads/MovieMusic';
					if (!file_exists(dirname($uploadDir . '/' . $model->cover)))
						mkdir(dirname($uploadDir . '/' . $model->cover), 0777, true);
					$cover->saveAs($uploadDir . '/' . $model->cover, true);
					$model->save();
				}
				$this->_saveMusicInfo($id);
				$this->loadModel($id)->saveCache($id);//设置缓存
				Yii::app()->user->setFlash('success','修改成功');
				$this->redirect(array('update','id'=>$id));
			}
		}
		$this->render('update',array(
			'model'=>$model
		));
	}

	/**
	 * 保存音乐方案
	 * @param string $id
	 * @return mixed|null
	 */
	private function _saveMusic($id='')
	{
		if(!empty($id)){
			$model = MovieMusic::model()->findByPk($id);
			if($model){
				$_POST['MovieMusic']['cover'] = $model->cover;//避免不传图片被重置为空
				$_POST['MovieMusic']['updated'] = time();
			}
		}else{
			$model = new MovieMusic();
			$_POST['MovieMusic']['created'] = $_POST['MovieMusic']['updated'] = time();
		}
		$model->attributes = $_POST['MovieMusic'];
		$model->save();
		return $model->id;
	}

	/**
	 * 保存音乐列表
	 * @param $id
	 */
	private function _saveMusicInfo($id)
	{
		$this->_delMusicInfo($id);
		//插入
		if(isset($_POST['song_id'])){
			for($i=0;$i<count($_POST['song_id']);$i++){
				$arrMusicInfoData = [];
				$arrMusicInfoData['m_id']=$id;
				$arrMusicInfoData['song_id']=$_POST['song_id'][$i];
				$arrMusicInfoData['singer_pic']=$_POST['singer_pic'][$i];
				$arrMusicInfoData['song_name']=$_POST['song_name'][$i];
				$arrMusicInfoData['singer_name']=$_POST['singer_name'][$i];
				$arrMusicInfoData['album_name']=$_POST['album_name'][$i];
				$arrMusicInfoData['album_id']=$_POST['album_id'][$i];
				$model=new MovieMusicInfo();
				$arrMusicInfoData['created'] = time();
				$arrMusicInfoData['updated'] = time();
				$model->setAttributes($arrMusicInfoData);
				$model->save();
			}
		}
		return true;
	}

	/**
	 * 删除音乐列表
	 * @param $id
	 * @return int
	 */
	private function _delMusicInfo($id)
	{
		//先删除
		return MovieMusicInfo::model()->deleteAllByAttributes(array('m_id'=>$id));
	}



	/**
	 *	搜索音乐页面
	 *
	 */
	public function actionMusicSearch(){
		$this->render('musicsearch');
	}

	/**
	 *	根据关键词搜索程序
	 *
	 */
	public function actionMusicKeyWords(){
		$keywords=Yii::app()->request->getParam('keywords','');
		$p=Yii::app()->request->getParam('p',1);
		$pagesize=10;
		$url = MovieMusic::API_GET_MUSIC_LIST;
		$sendData=[
			'w'=>$keywords,
			'utf8'=>1,
			'num'=>$pagesize,
			'p'=>$p,
			'format'=>'json',
			't'=>0,//0：单曲； 8：专辑；9：歌手
			'app_id'=>MovieMusic::MUSIC_APP_ID,
			'app_key'=>MovieMusic::MUSIC_APP_KEY,
			'device_id'=>123456
		];
		$strJson = Https::getPost($sendData,$url);
		$strArray=json_decode($strJson,true);
		//出错后直接返回
		if($strArray['ret']!=0){
			die($strJson);
		}
		$songlist=$strArray['list'];
		//如果返回的为空
		if(empty($songlist)){
			$return=array(
				"cur_num"=>$strArray['cur_num'],
				"page_index"=>$strArray['page_index'],
				'total_num'=>$strArray['total_num'],
				'ret'=>0,
				'data'=>''
			);
			die(json_encode($return));
		}
		//php5.5以下不支持arrar_column
		//$song_value=array_column($songlist,'data');
		$song_value=array();
		foreach($songlist as $val){
			array_push($song_value,$val['data']);
		}
		$song_ids='';
		$songinfo=array();
		foreach($song_value as $val){
			$song=explode('|',$val);
			$songinfo[$song[0]]['song_size']=$song[6];
			$songinfo[$song[0]]['song_time']=$song[7];
			$song_ids=empty($song_ids)?$song[0]:$song_ids.','.$song[0];
		}
		$url=MovieMusic::API_GET_MUSIC_INFO;
		$sendData=[
			'song_id'=>$song_ids,
			'format'=>'json',
			'page_index'=>1,
			'num_per_page'=>20,
			'app_id'=>MovieMusic::MUSIC_APP_ID,
			'app_key'=>MovieMusic::MUSIC_APP_KEY,
		];
		$songJson = Https::getPost($sendData,$url);
		$songArray=json_decode($songJson,true);
		//出错后直接返回
		if($songArray['ret']!=0){
			die($songArray);
		}
		$song_info=array();
		foreach($songArray['songlist'] as $val){
			$arr=array();
			$arr['singer_pic']=$val['singer_pic'];
			$arr['album_name']=$val['album_name'];
			$arr['album_id']=$val['album_id'];
			$arr['album_pic']=$val['album_pic'];
			$arr['playable']=$val['playable'];
			$arr['song_id']=$val['song_id'];
			$arr['singer_name']=$val['singer_name'];
			$arr['song_name']=$val['song_name'];
			$arr['song_play_url_cc']=urlencode($val['song_play_url_cc']);
			$arr['song_play_url_ws']=urlencode($val['song_play_url_ws']);
			$arr['song_size']=$songinfo[$val['song_id']]['song_size'];
			$arr['song_time']=$songinfo[$val['song_id']]['song_time'];
			array_push($song_info,$arr);
		}
		$return=array(
			"cur_num"=>$pagesize,
			"page_index"=>$strArray['page_index'],
			'total_num'=>$strArray['total_num'],
			'ret'=>0,
			'data'=>$song_info
		);
		die(json_encode($return));
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
		$model=MovieMusic::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * ajax接口，通过movieId获取影片信息
	 */
	public function actionGetMovieNameByMovieId(){
		$movieId = $_POST['movieId'];
		$url = API_MOVIEDATABASE.'/movie/info';
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
	 * 删除尿点管理，同时删除尿点相关的信息
	 * @param $id
	 * @throws CDbException
	 * @throws CHttpException
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		if($model){
			$movieMusicInfo = MovieMusicInfo::model()->findAll("m_id=:m_id",['m_id'=>$model->id]);
			foreach($movieMusicInfo as $val){
				MovieMusicInfo::model()->findByPk($val->id)->delete();
			}
			//$model->saveCache($id,'del');
			$this->loadModel($id)->saveCache($id,'del');
			$model->delete();
		}
		// if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
