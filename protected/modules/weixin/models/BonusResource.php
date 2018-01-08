<?php

/**
 * This is the model class for table "t_bonus_resource".
 *
 * The followings are the available columns in table 't_bonus_resource':
 * @property string $iResourceID
 * @property string $iResourceType
 * @property string $sBonusName
 * @property string $sBonusDesc
 * @property string $iCityID
 * @property string $sCity
 * @property string $iProvinceID
 * @property string $sProvince
 * @property integer $iState
 * @property string $iAppId
 * @property string $iSendStartTime
 * @property string $iSendEndTime
 * @property integer $iUseEndTime
 * @property integer $iUseStartTime
 * @property string $iBonusTotalCount
 * @property string $iBonusTotalValue
 * @property string $iReceiveMax
 * @property string $iPurchaseMax
 * @property string $iGetTimesLimit
 * @property string $iUseLimit
 * @property string $iCreateUserId
 * @property string $iCreateTime
 * @property string $iUpdateUserId
 * @property string $iUpdateTime
 * @property string $sResourceWord
 * @property string $sResourceURL
 * @property string $sResourceTicketURL
 * @property string $sReJectResourceID
 * @property string $sShareImgUrl
 * @property string $sShareTitle
 * @property string $sShareDesc
 * @property string $sRingShareImgUrl
 * @property string $sRingShareTitle
 * @property string $sShareLinkUrl
 * @property string $sBonusImageUrl
 * @property integer $iExt_1
 * @property integer $iExt_2
 * @property string $sExt_1
 * @property string $sExt_2
 * @property string $sChannelName
 * @property string $sChannelNo
 * @property string $iCinemaUseLimit
 * @property string $iSMpIdUseLimit
 */
class BonusResource extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_bonus_resource';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		return array(
			array('sBonusName, iCreateUserId', 'required'),
			array('iState, iUseEndTime, iUseStartTime, iExt_1, iExt_2', 'numerical', 'integerOnly'=>true),
			array('iResourceType, iCityID, iProvinceID, iAppId, iSendStartTime, iSendEndTime, iBonusTotalCount, iBonusTotalValue, iReceiveMax, iPurchaseMax, iGetTimesLimit, iUseLimit, iCreateUserId, iCreateTime, iUpdateUserId, iUpdateTime, iCinemaUseLimit, iSMpIdUseLimit', 'length', 'max'=>10),
			array('sBonusName, sBonusDesc, sResourceWord, sResourceURL, sResourceTicketURL, sReJectResourceID, sShareImgUrl, sShareDesc, sRingShareImgUrl, sShareLinkUrl, sBonusImageUrl', 'length', 'max'=>256),
			array('sCity, sProvince, sExt_1, sExt_2, sChannelNo', 'length', 'max'=>32),
			array('sShareTitle, sRingShareTitle, sChannelName', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iResourceID, iResourceType, sBonusName, sBonusDesc, iCityID, sCity, iProvinceID, sProvince, iState, iAppId, iSendStartTime, iSendEndTime, iUseEndTime, iUseStartTime, iBonusTotalCount, iBonusTotalValue, iReceiveMax, iPurchaseMax, iGetTimesLimit, iUseLimit, iCreateUserId, iCreateTime, iUpdateUserId, iUpdateTime, sResourceWord, sResourceURL, sResourceTicketURL, sReJectResourceID, sShareImgUrl, sShareTitle, sShareDesc, sRingShareImgUrl, sRingShareTitle, sShareLinkUrl, sBonusImageUrl, iExt_1, iExt_2, sExt_1, sExt_2, sChannelName, sChannelNo, iCinemaUseLimit, iSMpIdUseLimit', 'safe', 'on'=>'search'),
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
			'iResourceID' => '红包活动ID',
			'iResourceType' => '红包类型',
			'sBonusName' => '红包名称',
			'sBonusDesc' => '红包活动描述',
			'iCityID' => '城市ID',
			'sCity' => '城市',
			'iProvinceID' => '省份ID',
			'sProvince' => '省份',
			'iState' => '状态',
			'iAppId' => '红包应用平台',
			'iSendStartTime' => '发放开始时间',
			'iSendEndTime' => '发放结束时间',
			'iUseEndTime' => '红包下一波发放开始时间',
			'iUseStartTime' => '红包发放结束时间',
			'iBonusTotalCount' => '红包总数量',
			'iBonusTotalValue' => '红包总价值',
			'iReceiveMax' => '红包领取数量限制（默认1）',
			'iPurchaseMax' => '红包领取金额限制（0不限制）',
			'iGetTimesLimit' => '红包领取次数限制（默认1，应用于抽红包场景）',
			'iUseLimit' => '红包同时使用张数限制（默认1）',
			'iCreateUserId' => '创建人',
			'iCreateTime' => '创建时间',
			'iUpdateUserId' => '更新人',
			'iUpdateTime' => '更新时间',
			'sResourceWord' => '红包领取成功后提示语',
			'sResourceURL' => '红包活动URL',
			'sResourceTicketURL' => '红包购票跳转URL',
			'sReJectResourceID' => '红包不可重复领用ID',
			'sShareImgUrl' => '分享好友图片',
			'sShareTitle' => '分享好友标题',
			'sShareDesc' => '分享好友正文',
			'sRingShareImgUrl' => '分享朋友圈图片',
			'sRingShareTitle' => '分享朋友圈标题',
			'sShareLinkUrl' => 'sShareLinkUrl',
			'sBonusImageUrl' => 'sBonusImageUrl',
			'iExt_1' => 'iExt_1',
			'iExt_2' => 'iExt_2',
			'sExt_1' => 'sExt_1',
			'sExt_2' => 'sExt_2',
			'sChannelName' => 'sChannelName',
			'sChannelNo' => 'sChannelNo',
			'iCinemaUseLimit' => 'iCinemaUseLimit',
			'iSMpIdUseLimit' => 'iSMpIdUseLimit',
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
    /*
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('iResourceID',$this->iResourceID,true);
		$criteria->compare('iResourceType',$this->iResourceType,true);
		$criteria->compare('sBonusName',$this->sBonusName,true);
		$criteria->compare('sBonusDesc',$this->sBonusDesc,true);
		$criteria->compare('iCityID',$this->iCityID,true);
		$criteria->compare('sCity',$this->sCity,true);
		$criteria->compare('iProvinceID',$this->iProvinceID,true);
		$criteria->compare('sProvince',$this->sProvince,true);
		$criteria->compare('iState',$this->iState);
		$criteria->compare('iAppId',$this->iAppId,true);
		$criteria->compare('iSendStartTime',$this->iSendStartTime,true);
		$criteria->compare('iSendEndTime',$this->iSendEndTime,true);
		$criteria->compare('iUseEndTime',$this->iUseEndTime);
		$criteria->compare('iUseStartTime',$this->iUseStartTime);
		$criteria->compare('iBonusTotalCount',$this->iBonusTotalCount,true);
		$criteria->compare('iBonusTotalValue',$this->iBonusTotalValue,true);
		$criteria->compare('iReceiveMax',$this->iReceiveMax,true);
		$criteria->compare('iPurchaseMax',$this->iPurchaseMax,true);
		$criteria->compare('iGetTimesLimit',$this->iGetTimesLimit,true);
		$criteria->compare('iUseLimit',$this->iUseLimit,true);
		$criteria->compare('iCreateUserId',$this->iCreateUserId,true);
		$criteria->compare('iCreateTime',$this->iCreateTime,true);
		$criteria->compare('iUpdateUserId',$this->iUpdateUserId,true);
		$criteria->compare('iUpdateTime',$this->iUpdateTime,true);
		$criteria->compare('sResourceWord',$this->sResourceWord,true);
		$criteria->compare('sResourceURL',$this->sResourceURL,true);
		$criteria->compare('sResourceTicketURL',$this->sResourceTicketURL,true);
		$criteria->compare('sReJectResourceID',$this->sReJectResourceID,true);
		$criteria->compare('sShareImgUrl',$this->sShareImgUrl,true);
		$criteria->compare('sShareTitle',$this->sShareTitle,true);
		$criteria->compare('sShareDesc',$this->sShareDesc,true);
		$criteria->compare('sRingShareImgUrl',$this->sRingShareImgUrl,true);
		$criteria->compare('sRingShareTitle',$this->sRingShareTitle,true);
		$criteria->compare('sShareLinkUrl',$this->sShareLinkUrl,true);
		$criteria->compare('sBonusImageUrl',$this->sBonusImageUrl,true);
		$criteria->compare('iExt_1',$this->iExt_1);
		$criteria->compare('iExt_2',$this->iExt_2);
		$criteria->compare('sExt_1',$this->sExt_1,true);
		$criteria->compare('sExt_2',$this->sExt_2,true);
		$criteria->compare('sChannelName',$this->sChannelName,true);
		$criteria->compare('sChannelNo',$this->sChannelNo,true);
		$criteria->compare('iCinemaUseLimit',$this->iCinemaUseLimit,true);
		$criteria->compare('iSMpIdUseLimit',$this->iSMpIdUseLimit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db2;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BonusResource the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
