<?php
Yii::import('ext.RedisManager', true);
class SensitiveWords extends CActiveRecord
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
		$config = Yii::app()->params->redis_data['cache']['write'];
		$this->redis = RedisManager::getInstance($config);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_sensitive_words';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			//array('id', 'integerOnly'=>true),
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '敏感词ID',
			'name' => '敏感词名称',
			'created' => '创建时间',
			'updated' => '更新时间',
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
		$criteria->compare('name',$this->name,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>20,
				),
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}

// 	public function updateMovieName($movieId,$movieName)
// 	{
// 		if (empty($movieId) || empty($movieName)) return ;
// 		$this->getDbConnection()->createCommand("UPDATE {$this->tableName()} SET movieName = '$movieName' WHERE movieId = '$movieId'")->execute();
// 		return ;
// 	}
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
     * @return bool
     */
    public function delete() {
    	$result = SensitiveWords::model()->deleteByPk($this->id);
        return $result;
    }
    /**
     * (non-PHPdoc)
     * @see CActiveRecord::delete()
     */
    public function getOne($name) {
    	$name = trim($name);
    	if (empty($name)) return true;
    	$word = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE `name` = '$name'")->queryRow();
    	return !empty($word)?true:false;
    }
    
    public function isSensitiveWordInfo($name)
    {
    	if (empty($name))return $name;
    	$word = $this->getMemcache();
    	foreach ($word as $val)
    	{
    		$name = str_ireplace($val['name'],"<font color='red'>".$val['name'].'</font>',$name);
    	}
    	return $name;
    }
    /**
     * @tutorial 更新缓存
     * @author liulong
     * @return array
     */
    public function saveMemcache()
    {
		$this->getRedis();
    	$word = $this->getDbConnection()->createCommand("SELECT `name` FROM {$this->tableName()}")->queryAll();
		$this->redis->set("baymax_sensitive_word_info",json_encode($word));
		$this->redis->expire("baymax_sensitive_word_info",300);
    	//yii::app()->cache_app->set("baymax_sensitive_word_info", $word, 60*5);
    	return $word;
    }
    /**
     * @tutorial 获取缓存
     * @author liulong
     * @return array
	 * 已修改为redis
     */
    public function getMemcache()
    {
		$this->getRedis();
    	//$data = yii::app()->cache_app->get("baymax_sensitive_word_info");
		$this->redis = RedisManager::getInstance(Yii::app()->params->redis_data['cache']['write']);
		$data = $this->redis->get("baymax_sensitive_word_info");
		$data = json_decode($data,true);
    	if (empty($data)){
    		$data = $this->saveMemcache();
    	}
    	return $data;
    }
	private function getRedis()
	{
		if(empty($this->redis)){
			$this->setRedis();
		}
	}

}
