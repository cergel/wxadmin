<?php

/**
 * This is the model class for table "{{recommend_will}}".
 *
 * The followings are the available columns in table '{{recommend_will}}':
 * @property string $id
 * @property integer $movie_id
 * @property string $movie_name
 * @property string $remark
 * @property integer $start_time
 * @property integer $end_time
 * @property string $link
 * @property string $order
 */
class RecommendWill extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{recommend_will}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('movie_id, start_time, end_time', 'numerical', 'integerOnly'=>true),
			array('movie_name, remark, link, order', 'length', 'max'=>255),
            array('order,movie_id','unique','message'=>'排序重复'),
            array('movie_id,movie_name,pic,start_time,end_time,link,order','required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movie_id, movie_name, remark, start_time, end_time, link, order', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'movie_id' => '影片ID',
			'movie_name' => '影片名称',
            'pic' => '图片地址',
			'remark' => '备注',
			'start_time' => '上线时间',
			'end_time' => '下线时间',
			'link' => '链接地址',
			'order' => '排序',
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
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('movie_name',$this->movie_name,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('order',$this->order,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'`order` asc'
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RecommendWill the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
