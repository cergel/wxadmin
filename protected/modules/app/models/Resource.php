<?php
class Resource extends CActiveRecord
{
    const CHANNEL_IOS     = 8;
    const CHANNEL_ANDROID = 9;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_resource}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sName, iChannelID', 'required'),
			array('iCreated, iChannelID, iUpdated, iLastModified', 'numerical', 'integerOnly'=>true),
            /*
            array('iChannelID', 'unique', 'criteria'=>array(
                'condition'=>'`sName`=:sName',
                'params'=>array(
                    ':sName'=>$this->sName
                )
            )),
            */
			array('sName', 'length', 'max'=>40),
			array('sPath', 'length', 'max'=>100),
			array('sNote,iIsLogo', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iResourceID, sName, sPath, sNote, iCreated, iUpdated, iLastModified', 'safe', 'on'=>'search'),
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
			'iResourceID' => 'ID',
            'iChannelID' => '来源',
			'sName' => '资源名称',
			'sPath' => '路径',
			'sNote' => '备注',
			'iIsLogo'=>'是否显示渠道logo',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
			'iLastModified' => '资源更新时间',
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

        $criteria->compare('iResourceID',$this->iResourceID);
        $criteria->compare('iChannelID',$this->iChannelID);
		$criteria->compare('sName',$this->sName,true);
		$criteria->compare('sPath',$this->sPath,true);
		$criteria->compare('sNote',$this->sNote,true);
		$criteria->compare('iCreated',$this->iCreated);
		$criteria->compare('iUpdated',$this->iUpdated);
		$criteria->compare('iLastModified',$this->iLastModified);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iResourceID DESC',
            ),
					));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Resource the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
