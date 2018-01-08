<?php
class LuckActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'luck_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sTitle,iActivityType,iStatusTime,iEndTime,iUpdateTime', 'required'),
			array('iActivityType, iDayNum, iUpdateTime, iLotteryNum, iGeneral, iTickets, iTicketsNum, iRequirement', 'numerical', 'integerOnly'=>true),
			array('sTitle', 'length', 'max'=>200),
			array('sRule', 'length', 'max'=>600),
			array('sDescription', 'length', 'max'=>1000),
			array('sGaCode', 'length', 'max'=>500),
			array('sShareTitle, sRingTitle', 'length', 'max'=>300),
			array('sShareDescript', 'length', 'max'=>2000),
			array('sShareImage,sEmptyAwardImages', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, iActivityType, sTitle, sRule, sDescription, iStatusTime, iEndTime, iDayNum, iUpdateTime, iLotteryNum, sGaCode, iGeneral, iTickets, iTicketsNum, iRequirement, sShareTitle, sShareDescript, sRingTitle, sShareImage, iStatus', 'safe', 'on'=>'search'),
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
			'iId' => 'ID',
			'iActivityType' => '活动类型',
			'sTitle' => '活动主题',
			'sRule' => '活动规则',
			'sDescription' => '查看详情',
			'iStatusTime' => '活动开始时间',
			'iEndTime' => '活动结束时间',
			'iDayNum' => '抽奖次数',
			'iUpdateTime' => '抽奖次数更新时间',
			'iLotteryNum' => '本次活动中奖次数',
			'sGaCode' => 'Ga统计代码',
			'iGeneral' => '是否开启普奖',
			'iTickets' => '购票增加抽奖机会',
			'iTicketsNum' => '增加次数',
			'iRequirement' => '参与条件',
			'sShareTitle' => '分享给用户的标题',
			'sShareDescript' => '分享给用户的文案',
			'sRingTitle' => '分享给朋友圈的标题',
			'sShareImage' => '分享的图片',
			'iStatus'	  =>'状态',
			'sEmptyAwardImages'=>'空奖封面图',
		);
	}
	
	/**
	 * 获取活动类型
	 */
	public static function getActivityType(){
		return array('0'=>'转盘类','1'=>'翻卡片');
	}
	
	/**
	 * 抽奖次数更新时间
	 */
	public static function getUpdateTime(){
		return array('0'=>'每日更新','1'=>'活动时间内不更新');
	}

	/**
	 * 是否开启普奖
	 */
	public static function getIgeneral(){
		return array('0'=>'不开启','1'=>'开启');
	}

	/**
	 * 参与条件
	 */
	public static function getiRequirement(){
		return array('0'=>'全部','1'=>'关注公众号用户','2'=>'未支付用户');
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
		$criteria->compare('iActivityType',$this->iActivityType);
		$criteria->compare('sTitle',$this->sTitle,true);
		$criteria->compare('sRule',$this->sRule,true);
		$criteria->compare('sDescription',$this->sDescription,true);
		$criteria->compare('iStatusTime',$this->iStatusTime);
		$criteria->compare('iEndTime',$this->iEndTime);
		$criteria->compare('iDayNum',$this->iDayNum);
		$criteria->compare('iUpdateTime',$this->iUpdateTime);
		$criteria->compare('iLotteryNum',$this->iLotteryNum);
		$criteria->compare('sGaCode',$this->sGaCode,true);
		$criteria->compare('iGeneral',$this->iGeneral);
		$criteria->compare('iTickets',$this->iTickets);
		$criteria->compare('iTicketsNum',$this->iTicketsNum);
		$criteria->compare('iRequirement',$this->iRequirement);
		$criteria->compare('sShareTitle',$this->sShareTitle,true);
		$criteria->compare('sShareDescript',$this->sShareDescript,true);
		$criteria->compare('sRingTitle',$this->sRingTitle,true);
		$criteria->compare('sShareImage',$this->sShareImage,true);
		$criteria->compare('iStatus',$this->iStatus,true);

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
	 * @return LuckActivity the static model class
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
		//更新时间为时间戳
		//if ($this->isNewRecord)
			
		if($this->iStatusTime && !is_numeric($this->iStatusTime))
			$this->iStatusTime = strtotime($this->iStatusTime);
		if($this->iEndTime && !is_numeric($this->iEndTime))
			$this->iEndTime = strtotime($this->iEndTime);
		return true;
	}
	
	public function afterSave()
	{
		$this->afterFind();
	}
	
	 public function afterFind()
    {
		 if($this->iStatusTime)
            $this->iStatusTime = date('Y-m-d H:i:s', $this->iStatusTime);
        if($this->iEndTime)
            $this->iEndTime = date('Y-m-d H:i:s', $this->iEndTime);
	}
	
	
}
