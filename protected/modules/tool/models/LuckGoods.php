<?php
class LuckGoods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'luck_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iType, iActivityId,iCreated, iBonusId, iMoney, iProbability, iPeopleStint, iDayNum, iGoodsCount, iGeneral,iGalleryNum', 'numerical', 'integerOnly'=>true),
			array('sPrizeName, sFrontcoverImage, sKudoName', 'length', 'max'=>200),
			array('sImages', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, sPrizeName, sImages, iType, sKudoName, iBonusId, iProbability, iPeopleStint, iDayNum, iGoodsCount, iGeneral', 'safe', 'on'=>'search'),
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
			'sPrizeName' => 'S Prize Name',
			'sImages' => 'S Images',
			'iType' => 'I Type',
			'sKudoName' => 'S Kudo Name',
			'iBonusId' => 'I Bonus',
			'iProbability' => 'I Probability',
			'iPeopleStint' => 'I People Stint',
			'iDayNum' => 'I Day Num',
			'iGoodsCount' => 'I Goods Count',
			'iGeneral' => 'I General',
			'iGalleryNum'=>'i GalleryNum',
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
		$criteria->compare('sPrizeName',$this->sPrizeName,true);
		$criteria->compare('sImages',$this->sImages,true);
		$criteria->compare('iType',$this->iType);
		$criteria->compare('sKudoName',$this->sKudoName,true);
		$criteria->compare('iBonusId',$this->iBonusId);
		$criteria->compare('iProbability',$this->iProbability);
		$criteria->compare('iPeopleStint',$this->iPeopleStint);
		$criteria->compare('iDayNum',$this->iDayNum);
		$criteria->compare('iGoodsCount',$this->iGoodsCount);
		$criteria->compare('iGeneral',$this->iGeneral);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iId DESC',
            ),
					));
	}
	
	/**
	 * 普奖更新
	 */
	public function updateiGeneral($aid){
		Yii::app()->db_luck->createCommand()->update($this->tableName(),array('iGeneral' =>0),"iActivityId=".$aid);
	}
	
	/**
	 * 删除
	 */
	public function deleteGoods($aid,$ids){
		$aid = intval($aid);
		$ids = implode(' , ',$ids);
		$sql = "DELETE FROM {$this->tableName()} WHERE iActivityId=$aid AND iId NOT IN ($ids)";
		Yii::app()->db_luck->createCommand($sql)->query();
	}
	
	/**
	 * 根据活动id查询奖品
	 * @param unknown_type $aid
	 */
	public function getActivityGoods($aid){
		$list = $this::model()->findAll(array(
				'select'=>array('*'),
				'condition' => 'iActivityId=:aid',
				'params' => array(':aid' => $aid),
				'order'=>'iId ASC',
		));
		return $list;
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
	 * @return LuckGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
