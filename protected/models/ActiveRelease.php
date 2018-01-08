<?php

/**
 * 活动-发布平台关联表
 * @author liulong
 *
 */
class ActiveRelease extends CActiveRecord
{
	/**
	 * @return tableName
	 */
	public function tableName()
	{
		return 't_active_release';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('iActive_id, release', 'required'),
			array('iActive_id, release', 'numerical', 'integerOnly'=>true),
			array('iActive_id, release', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iActive_id' => '活动id',
			'release' => '平台ID',
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

		$criteria->compare('iActive_id',$this->iActive_id);
		$criteria->compare('release',$this->realse);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			//'sort'=>array(
                //'defaultOrder'=>'city_id DESC',
            //),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActiveCity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getDbConnection()
	{
		return Yii::app()->db_active;
	}
	
}
