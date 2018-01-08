<?php

/**
 * This is the model class for table "{{customization_seat}}".
 *
 * The followings are the available columns in table '{{customization_seat}}':
 * @property integer $SeatId
 * @property integer $MovieId
 * @property string $Created_time
 * @property string $Updated_time
 * @property integer $Status
 * @property string $Start
 * @property string $End
 */
class CustomizationSeat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{customization_seat}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MovieId, Status', 'required'),
			array('MovieId, Status', 'numerical', 'integerOnly'=>true),
			array('Created_time, Updated_time, Start, End', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('SeatId, MovieId, Created_time, Updated_time, Status, Start, End', 'safe', 'on'=>'search'),
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
			'SeatId' => 'ID主键',
			'MovieId' => '影片ID',
			'Created_time' => '创建时间',
			'Updated_time' => '更新时间',
			'Status' => '是否启用',
			'Start' => '投放开始时间',
			'End' => '投放结束时间',
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

		$criteria->compare('SeatId',$this->SeatId);
		$criteria->compare('MovieId',$this->MovieId);
		$criteria->compare('Created_time',$this->Created_time,true);
		$criteria->compare('Updated_time',$this->Updated_time,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Start',$this->Start,true);
		$criteria->compare('End',$this->End,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomizationSeat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
