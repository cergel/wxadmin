<?php
class CinemaGroup extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cinema_group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iStatus, iDeleted', 'numerical', 'integerOnly'=>true),
			array('sName', 'length', 'max'=>255),
			array('iCreated, iUpdated', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iGroupID, sName, iCreated, iUpdated, iStatus, iDeleted', 'safe', 'on'=>'search'),
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
			'cinemas'=>array(self::HAS_MANY, 'CinemaGroupCinema', 'iGroupID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iGroupID' => '分组编号',
			'sName' => '分组名称',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
			'iStatus' => '状态',
			'iDeleted' => '是否已删除',
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

		$criteria->compare('iGroupID',$this->iGroupID,true);
		$criteria->compare('sName',$this->sName,true);
		$criteria->compare('iCreated',$this->iCreated,true);
		$criteria->compare('iUpdated',$this->iUpdated,true);
		$criteria->compare('iStatus',$this->iStatus);
		$criteria->compare('iDeleted',$this->iDeleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iGroupID DESC',
            ),
					));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CinemaGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function afterFind()
	{
		if($this->iCreated)
			$this->iCreated = date('Y-m-d H:i:s', $this->iCreated);

		if($this->iUpdated)
			$this->iUpdated = date('Y-m-d H:i:s', $this->iUpdated);
	}

	public function afterDelete()
	{
		parent::afterDelete();
		CinemaGroupCinema::model()->deleteAllByAttributes( array('iGroupID'=>$this->iGroupID) );
	}

	/**
	 * 自动更新时间
	 */
    public function beforeSave()
    {
        $this->iUpdated = time();
        if ($this->isNewRecord)
            $this->iCreated = time();
        return true;
    }
}
