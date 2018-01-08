<?php
class AppUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_weiying_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid', 'required'),
			array('otherId, platForm, isDel', 'numerical', 'integerOnly'=>true),
			array('uid, openId, photo', 'length', 'max'=>64),
			array('mobileNo', 'length', 'max'=>11),
			array('password', 'length', 'max'=>32),
			array('nikeName', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, mobileNo, password, openId, nikeName, photo, otherId, platForm, isDel', 'safe', 'on'=>'search'),
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
			'uid' => '唯一标识',
			'mobileNo' => '手机号',
			'password' => '密码',
			'openId' => '第三方UID(唯一）',
			'nikeName' => '昵称',
			'photo' => '头像',
			'otherId' => '第三方平台id（自己定义好的编号如：5代表新浪微博）',
			'platForm' => '应用平台（1：电影票，2：演出票）',
			'isDel' => '是否删除:(1:禁用，0：正常)',
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

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('mobileNo',$this->mobileNo,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('openId',$this->openId,true);
		$criteria->compare('nikeName',$this->nikeName,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('otherId',$this->otherId);
		$criteria->compare('platForm',$this->platForm);
		$criteria->compare('isDel',$this->isDel);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'uid DESC',
            ),
					));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_user;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}
