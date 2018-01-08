<?php
Yii::import('ext.RedisManager', true);
class CommentStar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	const STARREDISKEY ='comment_star:';
	private $redis;


	//redis初始化逻辑
//	public function setRedis()
//	{
//		//初始化redis逻辑
//		$config = Yii::app()->params->redis_data['comment']['write'];
//		$this->redis = RedisManager::getInstance($config);
//	}
	public function tableName()
	{
		return 't_comment_star';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nickName,ucid,summary', 'required'),
			array('ucid', 'unique'),
			//限制上传大小
			CUploadedFile::getInstance($this, 'photo') || $this->isNewRecord ? array('photo',
				'file',
				'allowEmpty'=>!$this->isNewRecord,
				'types'=>'jpeg,jpg,png,gif',
				'maxSize'=>1024*32,    // 200kb
				'tooLarge'=>'分享图最大为32kb，上传失败！请上传小于32kb的图片！'
			) : array('photo', 'length', 'max'=>2000),
			array('id, nickName,ucid,summary, created,updated,photo', 'safe'),
			//array('content','HtmlEncode'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nickName,ucid,summary, created,updated,photo', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tag'=>array(self::HAS_MANY, 'CommentStarTag', 's_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nickName' => '昵称',
			'ucid'=>'ucid',
			'created' => '创建时间',
			'summary' => '简介',
			'updated'  => '修改时间',
			'photo'   =>'头像',
			'tag'    =>'标签',
		);
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
		$criteria->compare('nickName',$this->nickName,true);
		$criteria->compare('ucid',$this->ucid);
		$criteria->compare('summary',$this->summary,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>100,
				),
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
	}

	public function getStarTag($type='')
	{
		$arrData = [''=>'全部','1'=>'导演','2'=>'演员','3'=>'编辑','4'=>'歌手','5'=>'出品人','6'=>'制片人',
			'7'=>'监制','8'=>'摄影师','9'=>'配乐师','10'=>'制作人','11'=>'电影策划','12'=>'录音师','13'=>'灯光师',
			'14'=>'道具师','15'=>'化妆师','16'=>'服装师','17'=>'设计师','18'=>'剪辑师','19'=>'放映员',];
		if($type != 'all') unset($arrData['']);
		return empty($arrData[$type])?$arrData:$arrData[$type];
	}
	public function getTagInfo($id)
	{
		$str = '';
		$arrData = $this->getStarTag('all');
		$model = CommentStar::model()->findByPk($id);
		if (is_array($model->tag)){
			foreach($model->tag as $result) {
				$str .= empty($arrData[$result['t_id']])?'':$arrData[$result['t_id']].'  ';
			}
		}
		return $str;
	}
	public function saveCache($id,$status=1)
	{
		$model=CommentStar::model()->findByPk($id);
		if(empty($model))return false;
		$arrTag =[];
		$arrTagInfo = $this->getStarTag('all');
		if (is_array($model->tag)){
			foreach($model->tag as $result) {
				if(!empty($arrTagInfo[$result['t_id']])){
					$arrTag [] = $arrTagInfo[$result['t_id']];
				}
			}
		}
		$arrData = [];
		$arrData['tag'] = $arrTag;
		$arrData['ucid'] = $model->ucid;
		$arrData['nickName'] = $model->nickName;
		$arrData['summary'] = $model->summary;
		$arrData['photo'] = empty($model->photo)?'':Yii::app()->params['AppnfsPath']['cdn'].$model->photo;
		$arrPostData = [];
		$arrPostData['ucid'] = $arrData['ucid'];
		$arrPostData['channelId'] = 8;
		$arrPostData['status'] = $status;
		$arrPostData['from'] = '123123123';
		$arrPostData['content'] = json_encode($arrData);
		Log::model()->logFile('comment_star',json_encode($arrPostData));
		$arrData = Https::getPost($arrPostData,Yii::app()->params['comment']['save_comment_star']);
		Log::model()->logFile('comment_star',json_encode($arrData));
	}
//	public function delCache($id)
//	{
//		$model=CommentStar::model()->findByPk($id);
//		if($model)
//			$this->redis->del(self::STARREDISKEY.$model->ucid);
//	}





}
