<?php

/**
 * This is the model class for table "{{cinema_notification_cinema}}".
 *
 * The followings are the available columns in table '{{cinema_notification_cinema}}':
 * @property integer $iNotificationID
 * @property integer $iCinemaID
 */
class CinemaNotificationCinema extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cinema_notification_cinema}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iNotificationID, iCinemaID', 'required'),
			array('iNotificationID, iCinemaID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iNotificationID, iCinemaID', 'safe', 'on'=>'search'),
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
            'notifacation'=>array(self::BELONGS_TO, 'CinemaNotification', 'iNotificationID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iNotificationID' => '公告ID',
			'iCinemaID' => '影城ID',
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

		$criteria->compare('iNotificationID',$this->iNotificationID);
		$criteria->compare('iCinemaID',$this->iCinemaID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CinemaNotificationCinema the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
