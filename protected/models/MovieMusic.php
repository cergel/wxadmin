<?php
Yii::import('ext.RedisManager', true);
class MovieMusic extends CActiveRecord
{
	const MOVIE_SONG_CACHE_KEY = "movie_music_info";//电影音乐key
	const MUSIC_APP_ID=100311325;//qq音乐appid
	const MUSIC_APP_KEY='b233c8c2c8a0fbee4f83781b4a04c595';//qq音乐appkey
	const API_GET_MUSIC_LIST='http://open.music.qq.com/fcgi-bin/fcg_music_search.fcg';//根据关键字查询音乐列表接口
	const API_GET_MUSIC_INFO='http://open.music.qq.com/fcgi-bin/fcg_music_get_song_info_batch.fcg';//根据逗号分隔的song_id批量查询音乐详情接口
	//const API_GET_MUSIC_PLAY='http://open.music.qq.com/fcgi-bin/fcg_music_get_playurl.fcg';//根据song_id获取单个歌曲的播放地址接口
	const API_GET_MUSIC_PLAY='http://open.music.qq.com/fcgi-bin/fcg_music_get_song_info_batch.fcg';//根据song_id获取单个歌曲的播放地址接口

	private $redis;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_movie_music';
	}
	public function init()
	{
		$this->setRedis();
	}
	//redis初始化逻辑
	public function setRedis()
	{
		//初始化redis逻辑
		$config = Yii::app()->params->redis_data['pee']['write'];
		$this->redis = RedisManager::getInstance($config);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,status', 'required'),
			array('id', 'unique'),
			array('id,  movie_name, cover , created,status, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,  movie_name, cover , created,status, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'songlists'=>array(self::HAS_MANY, 'MovieMusicInfo', 'm_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '影片ID',
			'movie_name' =>'影片名称',
			'cover'=>'专辑封面',
			'status' => '状态',
			'created' => '创建时间',
			'updated' => '更新时间',
			'status'  =>'状态',
		);
	}

	/**
	 *
	 * @param $id
	 */
	public function saveCache($id,$type='')
	{
		$arrData = self::model()->findByPk($id);
		if(!empty($arrData) && $arrData['status'] ==1 && $type !== 'del'){
			$songlist=$arrData->songlists;
			$song_ids='';
			foreach($songlist as $val){
				$song_ids=empty($song_ids)?$val['song_id']:$song_ids.','.$val['song_id'];
			}
			if($song_ids!=''){
				$return=$this->_getPlayurlBySongid($song_ids);
				foreach($songlist as $key=>$val){
					$model=MovieMusicInfo::model()->findByPk($val->id);
					$model->ws_play_url=isset($return[$model->song_id][0])?$return[$model->song_id][0]:'';
					$model->cc_play_url=isset($return[$model->song_id][1])?$return[$model->song_id][1]:'';
					$model->status=1;
					$model->save();
				}
				$res =  $this->_setMovieMusicCache($id);//通过cgi设置cache
				$arrData->updated=time();
				$arrData->save();
			}
			else{
				$res = $this->_delMovieMusicCache($id);//通过cgi删除cache
			}
		}else{
			$res = $this->_delMovieMusicCache($id);//通过cgi删除cache
		}
	}

	private function _delMovieMusicCache($movieId){
		$arrData = ['channelId'=>3,'movieId'=>$movieId];
		$arrData = Https::getPost($arrData,Yii::app()->params['commoncgi']['movie_music_del']);
		$arrData = json_decode($arrData,true);
		if(!empty($arrData['ret'])){
			return false;
		}
		return $arrData['data'];
	}

	private function _setMovieMusicCache($movieId){
		$arrData = ['channelId'=>3,'movieId'=>$movieId,'actorId'=>1];
		$arrData = Https::getPost($arrData,Yii::app()->params['commoncgi']['movie_music_set']);
		$arrData = json_decode($arrData,true);

		if(!empty($arrData['ret'])){
			return false;
		}
		return $arrData['data'];
	}

	/**
	 * 根据songid获取音乐的播放地址
	 * @param int $song_id
	 * @return bool
	 */
	private function _getPlayurlBySongid($song_ids){
		$url = self::API_GET_MUSIC_PLAY;
		$sendData=[
			'song_id'=>$song_ids,
			'format'=>'json',
			'app_id'=>self::MUSIC_APP_ID,
			'app_key'=>self::MUSIC_APP_KEY,
			'device_id'=>123456
		];
		$strJson = Https::getPost($sendData,$url);
		$strArray=json_decode($strJson,true);
		//出错后直接返回
		if($strArray['ret']!=0){
			return false;
		}
		$musicList=array();
		foreach($strArray['songlist'] as $val){
			$musicList[$val['song_id']]=[$val['song_play_url_ws'],$val['song_play_url_cc']];
		}
		return $musicList;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('movie_name',$this->movie_name,true);
		$criteria->compare('cover',$this->cover);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'created DESC',
			),
		));
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Movie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}