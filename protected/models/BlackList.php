<?php
Yii::import('ext.RedisManager', true);
class BlackList extends CActiveRecord
{
	private $redis;


	public function init()
	{
		$this->setRedis();

	}

	//redis初始化逻辑
	public function setRedis()
	{
		//初始化redis逻辑
		$config = Yii::app()->params->redis_data['movie_order']['write'];
		$this->redis = RedisManager::getInstance($config);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_black_list';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid,stype', 'required'),
			array('uid,create_name,created,create_uid,stype', 'safe'),
			// @todo Please remove those attributes that should not be searched.
			array('id,uid, create_name,stype', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'uid' => '用户UID',
			'stype' => '类型',
			'created_uid' => '管理员id',
			'create_name' => '封号操作人',
			'created' => '创建时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('stype',$this->stype);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'sort'=>array(
				'defaultOrder'=>'created DESC',
			),
		));
	}
	public function getStype($info=''){
		$data = [''=>'全部','1'=>'广告类','2'=>'政治类','3'=>'色情类','4'=>'辱骂类','5'=>'其他',];
		if (empty($info)){
			unset($data['']);
		}elseif (!empty($data[$info])){
			$data = $data[$info];
		}
		return $data;
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
     * @return bool
     */
    public function delete() {
    	$result = BlackList::model()->deleteByPk($this->id);
        return $result;
    }
    /**
     * (non-PHPdoc)
     * @see CActiveRecord::delete()
     */
    public function getOne($id) {
    	if (empty($id)) return true;
    	$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE `uid` = '$id'")->queryRow();
    	return !empty($info)?true:false;
    }
    
    public function saveRedis()
    {
		$blackListRedisKey = "black_list_ucid";
		if(empty($this->redis))
			$this->setRedis();
    	$info = $this->getDbConnection()->createCommand("SELECT `uid` FROM {$this->tableName()}")->queryAll();
    	$data = [];
    	foreach ($info as $val){
    		$data[] = $val['uid'];
    	}
		$data=json_encode($data);
		$this->redis->set($blackListRedisKey,$data);
		$this->redis->expire($blackListRedisKey,300);
    	return $data;
    }
    public function getRedis()
    {
		$blackListRedisKey = "black_list_ucid";
		if(empty($this->redis))
			$this->setRedis();
		$data = $this->redis->get($blackListRedisKey);
    	if (empty($data)){
    		$data = $this->saveRedis($blackListRedisKey);
    	}
    	return json_decode($data,1);
    }
	public function getUcidStr($ucid)
	{
		$data = $this->getRedis();
		if (!in_array($ucid, $data)){
			$ucid = '<a href="/blackList/create?uid='.$ucid.'">'.$ucid.'</a>';
		}
		return $ucid;
	}


}
