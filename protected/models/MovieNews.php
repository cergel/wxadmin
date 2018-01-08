<?php
class MovieNews extends CActiveRecord
{


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_movie_news';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id,  movie_id, start_time, new_type,end_time,status,order,created,updated', 'numerical', 'integerOnly'=>true),
			array('share_img', 'required'),
			// The following rule is used by search().
			//限制上传大小
			CUploadedFile::getInstance($this, 'share_img') || $this->isNewRecord ? array('share_img',
				'file',
				'allowEmpty'=>!$this->isNewRecord,
				'types'=>'jpeg,jpg,png,gif',
				'maxSize'=>1024*32,    // 200kb
				'tooLarge'=>'分享图最大为32kb，上传失败！请上传小于32kb的图片！'
			) : array('share_img', 'length', 'max'=>2000),
			array('id,  movie_id, start_time, new_type,end_time,status,order,created,updated,movie_name,tag,title,share_show_title,share_title,share_second_title,share_url,share_img', 'safe'),
			array('id,  movie_id, start_time, new_type,end_time,status,order,created,updated,movie_name,tag,title,share_show_title,share_title,share_second_title,share_url,share_img', 'safe', 'on'=>'search'),
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
			'cities'=>array(self::HAS_MANY, 'MovieNewsCitys', 'n_id'),
			'channel'=>array(self::HAS_MANY, 'MovieNewsChannels', 'n_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'movie_id' =>'关联影片',
			'movie_name' =>'影片名称',

			'new_type'=>'推荐类型',
			'tag' => '标签名称',
			'title' => '标题',
			'score' => '当前评分',
			'start_time' => '上线时间',
			'end_time' => '下线时间',
            'status' => '状态',
			'order' => '权重值',
			'created' => '创建时间',
			'updated' => '更新时间',
			'channel' =>'平台',

			'share_show_title' =>'分享显示标题',
			'share_title' =>'分享标题',
			'share_second_title' =>'分享副标题',
			'share_url' =>'分享链接',
			'share_img' =>'分享图标',
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
        $criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('movie_name',$this->movie_name,true);
		$criteria->compare('new_type',$this->new_type);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
        $criteria->compare('order',$this->order);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>20,
				),
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
					));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
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

	public static function getNewTypeList($type='')
	{
		$arrData = [''=>'全部','1'=>'优惠','2'=>'周边','3'=>'策划'];
		if($type != 'all') unset($arrData['']);
		return empty($arrData[$type])?$arrData:$arrData[$type];
	}
	public function getChannelList($type='')
	{
		$arrData = [''=>'全部','8'=>'IOS客户端','9'=>'Android客户端','3'=>'微信电影票','28'=>'手Q电影票'];
		if($type != 'all') unset($arrData['']);
		return empty($arrData[$type])?$arrData:$arrData[$type];
	}
	public function getChannelStr($id)
	{
		$str = '';
		$arrData = $this->getChannelList('all');
		$model = MovieNews::model()->findByPk($id);
		if (is_array($model->channel)){
			foreach($model->channel as $result) {
				$str .= empty($arrData[$result['channel_id']])?'':$arrData[$result['channel_id']].'  ';
			}
		}
		return $str;
	}

	public function saveStatus()
	{
		$sql = "update t_movie_news set `status` = '0' where `status`='1' and  end_time < ".time();
		$arrBannerData = yii::app()->db->createCommand($sql)->execute();
	}


}
