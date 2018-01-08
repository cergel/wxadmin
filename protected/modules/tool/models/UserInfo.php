<?php
class UserInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'luck_user_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iActivityId, iGoodsId, iGoodsUserId, iTime', 'required'),
			array('iActivityId, iGoodsId, iGoodsUserId, iTime', 'numerical', 'integerOnly'=>true),
			array('sOpenId, sCode', 'length', 'max'=>100),
			array('sUserName', 'length', 'max'=>300),
			array('sMobile', 'length', 'max'=>30),
			array('sAddress', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, iActivityId, sOpenId, iGoodsId, iGoodsUserId, sUserName, sMobile, sAddress, sCode, iTime', 'safe', 'on'=>'search'),
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
			'iId' => 'I',
			'iActivityId' => 'I Activity',
			'sOpenId' => 'S Open',
			'iGoodsId' => 'I Goods',
			'iGoodsUserId' => 'I Goods User',
			'sUserName' => 'S User Name',
			'sMobile' => 'S Mobile',
			'sAddress' => 'S Address',
			'sCode' => 'S Code',
			'iTime' => 'I Time',
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

		$criteria->compare('iId',$this->iId);
		$criteria->compare('iActivityId',$this->iActivityId);
		$criteria->compare('sOpenId',$this->sOpenId,true);
		$criteria->compare('iGoodsId',$this->iGoodsId);
		$criteria->compare('iGoodsUserId',$this->iGoodsUserId);
		$criteria->compare('sUserName',$this->sUserName,true);
		$criteria->compare('sMobile',$this->sMobile,true);
		$criteria->compare('sAddress',$this->sAddress,true);
		$criteria->compare('sCode',$this->sCode,true);
		$criteria->compare('iTime',$this->iTime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iId DESC',
            ),
					));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_luck;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
