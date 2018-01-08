<?php
class CommentRecommend extends CActiveRecord
{
	const NEWCOMMENT_COMMENT_SORT_NEW ='newcomment_comment_recommend_new_movie:';  //推荐的评论
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_comment_recommend';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, movie_id,ucid', 'required'),
			array('id, movie_id,ucid,end_time, created,status', 'safe'),
			//array('content','HtmlEncode'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movie_id,ucid,end_time, created,status', 'safe', 'on'=>'search'),
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
			'id' => '评论ID',
			'movie_id' => '影片ID',
			'ucid'=>'ucid',
			'created' => '操作时间',
			'end_time' => '下线时间',
			'content'  => '内容',
			'status'  =>'状态',
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
		$criteria->compare('movie_id',$this->movie_id,true);
		$criteria->compare('ucid',$this->ucid);
		$criteria->compare('status',$this->status);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>100,
				),
						'sort'=>array(
                'defaultOrder'=>'created DESC',
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
		return Yii::app()->db_app;
	}


	public function saveCache()
	{

	}



}
