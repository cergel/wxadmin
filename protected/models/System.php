<?php
/**
 * 暂时无用
 * @author Administrator
 *
 */
class System extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_admin_system';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('key_name,start_value,status,stype', 'required'),
            array('key_name', 'unique'),
			array('start_value, end_value, content, status, stype, updated', 'safe'),
			array('id,  status, stype, updated', 'numerical', 'integerOnly'=>true),
			array('id, key_name,start_value, end_value, content, status, stype, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'key_name' =>'键名',
			'start_value'=>'开始值',
			'end_value' => '结束值',
			'content' => '说明',
			'status' => '状态',
			'stype' => '类型',
			'updated' => '更新时间',
		);
	}

	/**
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('key_name',$this->key_name,true);
		$criteria->compare('start_value',$this->start_value,true);
		$criteria->compare('end_value',$this->end_value,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('stype',$this->stype);
		$criteria->compare('updated',strtotime($this->updated));

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
	/**
	 * 获取类型
	 * @param string $info
	 * @return Ambigous <multitype:string , string>
	 */
	public function getStype($info=''){
		$data = [''=>'全部','1'=>'单个值','2'=>'区间随机值'];
		if (empty($info)){
			unset($data['']);
		}
		return !empty($data[$info])?$data[$info]:$data;
	}
	/**
	 * 获取状态
	 * @param string $info
	 * @return Ambigous <multitype:string , string>
	 */
	public function getStatus($info=''){
		$data = [''=>'全部','0'=>'禁用','1'=>'生效'];
		if ($info === ''){
			unset($data['']);
		}
		return !empty($data[$info])?$data[$info]:$data;
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
	 * @return Movie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave()
    {
        // 自动处理时间
        $this->updated = time();
        return parent::beforeSave();
    }
    public function saveMemcache()
    {
    	$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE status = '1'")->queryAll();
    	$data = [];
    	foreach ($info as $val)
    	{
    		$data[$val['key_name']] = $val;
    	}
    	yii::app()->cache_app->set("baymax_admin_system_info", $data, 60*10);
    	return $data;
    }
    public function getMemcache()
    {
    	$data = yii::app()->cache_app->get("baymax_admin_system_info");
    	if (empty($data)){
    		$data = $this->saveMemcache();
    	}
    	return $data;
    }
    
    /**
     * @tutorial注水后的想看数
     * @param unknown $baseWantCount
     * @param unknown $wantCount
     * @return Ambigous <number, int>
     * @author liulong
     */
    public function getBaseWantCountNo($baseWantCount,$wantCount)
    {
    	$key_name = 'wantCount';
    	$data = $this->getMemcache();
    	$baseWantCountNo = 0;
    	if (!empty($data[$key_name])){
    		
    		if ($data[$key_name]['stype'] == 1){
    			//单个值
    			$baseWantCountNo = intval($baseWantCount + $wantCount * $data[$key_name]['start_value']);
    		}elseif (!empty($data[$key_name]['start_value']) && !empty($data[$key_name]['end_value']) && $data[$key_name]['end_value'] > $data[$key_name]['start_value']) {
    			//区间随机,并且合法
    				$baseWantCountNo = intval($baseWantCount + $wantCount * rand($data[$key_name]['start_value'],$data[$key_name]['end_value']));
    		}else {
    			//特殊情况：不变
    			$baseWantCountNo = intval( $baseWantCount + $wantCount);
    		}
    		
    	}else {
    		$baseWantCountNo = intval( $baseWantCount + $wantCount);
    	}
    	return $baseWantCountNo;
    	
    }

}
