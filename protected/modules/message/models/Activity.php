<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property string $id
 * @property string $push_id
 * @property integer $push_type
 * @property integer $content_type
 * @property string $cover_pic
 * @property string $link
 * @property string $content
 * @property string $channel
 * @property string $push_date
 * @property integer $status
 * @property integer $is_all
 * @property string $c_t
 * @property string $u_t
 * @property string $title
 */
class Activity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('push_id, push_type, content_type, cover_pic, link, push_date,title', 'required'),
			array('push_type, content_type, status, is_all', 'numerical', 'integerOnly'=>true),
			array('push_id, cover_pic', 'length', 'max'=>20),
			array('link, content,tag', 'length', 'max'=>255),
			array('channel,title', 'length', 'max'=>32),
			array('c_t, u_t', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, push_id, push_type, content_type, cover_pic, link, content, channel, push_date, status, is_all, c_t, u_t', 'safe', 'on'=>'search'),
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
			'id' => '主键ID',
			'push_id' => '信鸽推送ID',
			'push_type' => '推送类型：0主动1被动',
			'content_type' => '内容类型：0文本1图文',
			'cover_pic' => '封面图片',
			'link' => '消息内容链接',
			'content' => '消息内容',
			'channel' => '推送渠道',
			'push_date' => '推送日期',
			'status' => '推送状态',
			'is_all' => '是否全量推送 0 否 1 是',
			'c_t' => '创建时间',
			'u_t' => '更新时间',
			'title' => '标题',
			'tag' => '筛选标签',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('push_id',$this->push_id,true);
		$criteria->compare('push_type',$this->push_type);
		$criteria->compare('content_type',$this->content_type);
		$criteria->compare('cover_pic',$this->cover_pic,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('push_date',$this->push_date,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_all',$this->is_all);
		$criteria->compare('c_t',$this->c_t,true);
		$criteria->compare('u_t',$this->u_t,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Activity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
