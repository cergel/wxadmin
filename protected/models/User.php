<?php

/**
 * This is the model class for table "t_user".
 *
 * The followings are the available columns in table 't_user':
 * @property integer $id
 * @property string $email
 * @property string $username
 * @property string $nickname
 * @property string $password
 * @property string $last_ip
 * @property integer $is_deleted
 * @property integer $created
 * @property integer $updated
 * @property integer $sGroupId
 */
class User extends CActiveRecord
{
    public function getDbConnection() {

        return Yii::app()->db;
    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sEmail, sPassword', 'required'),
            array('sEmail, sUserName', 'unique'),
			array('sGroupId, iDeleted, iCreated, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sEmail, sUserName, sNickName, sPassword, sLastIp', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iUserID, sEmail, sUserName, sNickName, iDeleted, iCreated, iUpdated', 'safe', 'on'=>'search'),
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
			'iUserID' => 'ID',
			'sEmail' => '登陆邮箱',
			'sUserName' => '用户名',
			'sNickName' => '用户昵称',
			'sPassword' => '登陆密码',
			'sLastIp' => '最后登陆IP',
			'iDeleted' => '已删除',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
            'sGroupId' => '分组id',
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

		$criteria->compare('iUserID',$this->iUserID);
		$criteria->compare('sEmail',$this->sEmail,true);
		$criteria->compare('sUserName',$this->sUserName,true);
		$criteria->compare('sNickName',$this->sNickName,true);
		$criteria->compare('sLastIp',$this->sLastIp,true);
		$criteria->compare('iDeleted',$this->iDeleted);
		$criteria->compare('iCreated',strtotime($this->iCreated));
		$criteria->compare('iUpdated',strtotime($this->iUpdated));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'iUserID DESC',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    // 自动更新时间
    public function beforeSave()
    {
        $this->iUpdated = time();
        if ($this->isNewRecord)
            $this->iCreated = time();
        return true;
    }
}
