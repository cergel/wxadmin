<?php
class ShieldingWords extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_shielding_words';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name , stype', 'required'),
			array('name', 'unique'),
			// @todo Please remove those attributes that should not be searched.
			array('id, name,stype', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '屏蔽词ID',
			'name' => '屏蔽词名称',
			'stype' => '类型',
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
		$criteria->compare('stype',$this->stype);
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
    	$result = ShieldingWords::model()->deleteByPk($this->id);
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
    
    public function saveMemcache()
    {
    	$word = $this->getDbConnection()->createCommand("SELECT `name`,`stype` FROM {$this->tableName()}")->queryAll();
    	yii::app()->cache_app->set("baymax_shielding_word_info", $word, 60*60*24);
    	return $word;
    }
    public function getMemcache()
    {
    	$data = yii::app()->cache_app->get("baymax_shielding_word_info");
    	if (empty($data)){
    		$data = $this->saveMemcache();
    	}
    	return $data;
    }
   

}
