<?php
class GoodsUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'luck_goods_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iDay', 'required'),
			array('iActivityId, iType, iGoodsId, iGet, iDay, iCreateTime, iSubmitUserInfo', 'numerical', 'integerOnly'=>true),
			array('sKudoName', 'length', 'max'=>300),
			array('sOpenId', 'length', 'max'=>30),
			array('iCode', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, iActivityId, sKudoName, iType, sOpenId, iGoodsId, iGet, iCode, iDay, iCreateTime, iSubmitUserInfo', 'safe', 'on'=>'search'),
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
			'sKudoName' => 'S Kudo Name',
			'iType' => 'I Type',
			'sOpenId' => 'S Open',
			'iGoodsId' => 'I Goods',
			'iGet' => 'I Get',
			'iCode' => 'I Code',
			'iDay' => 'I Day',
			'iCreateTime' => 'I Create Time',
			'iSubmitUserInfo' => 'I Submit User Info',
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
		$criteria->compare('sKudoName',$this->sKudoName,true);
		$criteria->compare('iType',$this->iType);
		$criteria->compare('sOpenId',$this->sOpenId,true);
		$criteria->compare('iGoodsId',$this->iGoodsId);
		$criteria->compare('iGet',$this->iGet);
		$criteria->compare('iCode',$this->iCode,true);
		$criteria->compare('iDay',$this->iDay);
		$criteria->compare('iCreateTime',$this->iCreateTime);
		$criteria->compare('iSubmitUserInfo',$this->iSubmitUserInfo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iId DESC',
            ),
					));
	}
	
	
	/**
	 * 根据活动id查询中奖用户信息
	 */
	public function getActivityUserGoodsList($aid){
		$sql = "SELECT iGoodsId,iDay AS 'year_day',COUNT(1) AS 'num' FROM luck_goods_user WHERE 1=1 GROUP BY year_day,iGoodsId ORDER BY iCreateTime DESC";
		$list = Yii::app()->db_luck->createCommand($sql)->queryAll();
		return $list;
	}
	
	/**
	 * 查询实物奖品
	 */
	public function getGoodsPhysical($id,$time,$goodsId){
		$sql = "SELECT `user`.*,info.sUserName,info.sMobile,sAddress,sCode FROM {$this->tableName()} as user  LEFT JOIN luck_user_info as info ON user.iId = info.iGoodsUserId
				WHERE user.iDay=$time AND user.iType=1 AND user.iGoodsId={$goodsId}";
		$list = Yii::app()->db_luck->createCommand($sql)->queryAll();
		return $list;
	}
	
	public function getGoodsPlacepush($id,$time,$goodsId){
		$sql = "SELECT `user`.*,info.sUserName,info.sMobile,sAddress,sCode FROM {$this->tableName()} as user  LEFT JOIN luck_user_info as info ON user.iId = info.iGoodsUserId
		WHERE user.iDay=$time AND user.iType=2 AND user.iGoodsId={$goodsId}";
		$list = Yii::app()->db_luck->createCommand($sql)->queryAll();
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
	 * @return GoodsUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
