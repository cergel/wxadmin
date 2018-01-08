<?php
class MovieData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'movie';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('movie_id,movie_name_chs,movie_name_pinyin,movie_name_init_pinyin', 'required'),
            array('movie_id', 'unique'),
			array('movie_id,peomax', 'numerical', 'integerOnly'=>true),
			array('score', 'type', 'type'=>'float'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('movie_id,coverid,movie_name_more, movie_name_chs,movie_name_eng, movie_name_pinyin, movie_name_init_pinyin, age, longs, score, first_show,first_time, country, color,peomax, in_short_remark, tags,actor,director,version,language,detail', 'safe'),
			array('id,movie_id, movie_name_chs,movie_name_eng, movie_name_pinyin, movie_name_more,movie_name_init_pinyin, age, longs, score, first_show, country, color, in_short_remark, tags,actor,director,version,language,detail', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '表ID',
			'movie_id' =>'影片ID',
			'movie_name_chs'=>'影片中文名',
			'movie_name_eng' => '影片英文名',
			'movie_name_more'=>'更多名称',
			'movie_name_pinyin' => '影片名拼音',
			'movie_name_init_pinyin' => '影片名拼音首字母',
			'age' => '年代',
			'longs' => '时长',
            'score' => '评分',
			'first_show' => '上映时间',
			'first_time' => '上映时间',
			'country' => '出品地区',
			'peomax' => '评分人数',
			'color' => '色彩',
			'in_short_remark' => '一句话点评',
			'tags' => '影片类型',
			'actor' => '主演',
			'director' => '导演',
			'version' => '版本',
			'language' => '语言',
			'detail' => '剧情',
			'coverid'=>'第三方预告片',
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
// 		UploadFiles::movieFile('123123');exit;
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('movie_name_chs',$this->movie_name_chs,true);
		$criteria->compare('movie_name_eng',$this->movie_name_eng,true);
		$criteria->compare('movie_name_pinyin',$this->movie_name_pinyin,true);
		$criteria->compare('movie_name_init_pinyin',$this->movie_name_init_pinyin);
		$criteria->compare('age',$this->age,true);
		$criteria->compare('longs',$this->longs,true);
        $criteria->compare('first_show',$this->first_show,true);
        $criteria->compare('country',$this->country,true);
		$criteria->compare('in_short_remark',$this->in_short_remark,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('actor',$this->actor,true);
		$criteria->compare('director',$this->director,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('language',$this->language,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>50,
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
		return Yii::app()->db_movie;
	}
	/**
	 * 获取一条,若不传movieid，则返回最后一条数据
	 * @param string $movie_id
	 * @return boolean|Ambigous <boolean, mixed>
	 */
	public function getOne($movie_id = 0) {
		if (empty($movie_id)){
			$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} GROUP BY movie_id desc")->queryRow();
		}else 
			$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE `movie_id` = '$movie_id'")->queryRow();
		return !empty($info)?$info:false;
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
