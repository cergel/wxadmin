<?php

Yii::import('ext.RedisManager', true);
class Version extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('version, description,status,itype, version_type, version_match', 'required'),
			array('created, updated, forceUpdate', 'numerical', 'integerOnly'=>true),
			array('version, version_match', 'length', 'max'=>40),
            array('path, md5', 'length', 'max'=>200),
			array('id, version, version_match, title, img, path, created,status, itype, updated, version_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, version, path,title,img, created,status,itype, updated,version_type, version_match', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'status' => '状态',
			'itype' => '渠道',
			'title' => '标题',
			'img' => '图片',
			'version' => '版本号',
			'path' => '下载地址',
            'forceUpdate' => '强制更新',
            'md5' => '校验码',
            'description' => '版本说明',
            'created' => '创建时间',
            'updated' => '更新时间',
            'version_type'=>'版本配置类型',
            'version_match' => '匹配版本',
		);
	}

	/**
	 * @tutorial 查询
	 * @author liulong
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('status',$this->status);
		$criteria->compare('title',$this->title);
		$criteria->compare('img',$this->img);
		$criteria->compare('itype',$this->itype);
		$criteria->compare('version',$this->version,true);
        $criteria->compare('path',$this->path,true);
        $criteria->compare('forceUpdate',$this->forceUpdate,true);
        $criteria->compare('md5',$this->md5,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('created',$this->created);
        $criteria->compare('updated',$this->updated);
        $criteria->compare('version_type', $this->version_type);
        $criteria->compare('version_match', $this->version_match);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
					));
	}
	/**
	 * @author 生成路径
	 * @author liulong
	 * @param unknown $path
	 * @param unknown $type
	 */
	public function getPath($pathUrl,$id,$type)
	{
		if ($type == 9){
    		$path = Yii::app()->params['version']['path'] . $pathUrl;
		}elseif ($type == 8){
			$path = $pathUrl;
		}else {
			$path ="";
		}
		$path = '<a href="'.$path.'" target="_blank" >'.$path.'</a>';
		return $path;
	}
	/**
	 * @tutorial 生成路径
	 * @author liulong
	 * @param unknown $path
	 * @param unknown $type
	 */
	public function getImg($path,$id)
	{
		if ($path){
			$path = 'http://'.$_SERVER["HTTP_HOST"].'/uploads/app_version/'.$id.'/'.$path;
			$path = '<a href="'.$path.'" target="_blank" >'.$path.'</a>';
		}else $path ='';
		
		return $path;
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_app;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Version the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 自动更新时间
     * 
     */
    public function beforeSave()
    {
        $this->updated = time();
        if ($this->isNewRecord)
            $this->created = time();
        return true;
    }
    /**
     * @tutorial 更新缓存
     * @author liulong
     * @return array
     */
    public function saveMemcache()
    {
    	$data['9'] = $this->getDbConnection()->createCommand("SELECT id,title,img,version,path,forceUpdate,md5,description FROM {$this->tableName()} Where status = '1' AND itype = '9' order by id desc")->queryRow();
    	if ($data['9']['path']){
    		$data['9']['path'] = Yii::app()->params['version']['path'] . $data['9']['path'];
    	}
    	if ($data['9']['img']){
    		$data['9']['img'] = Yii::app()->params['version']['path'] . $data['9']['id'].'/'.$data['9']['img'];
    	}
    	$data['8'] = $this->getDbConnection()->createCommand("SELECT id,title,img,version,path,forceUpdate,md5,description FROM {$this->tableName()} Where status = '1' AND itype = '8' order by id desc")->queryRow();
    	if ($data['8']['img']){
    		$data['8']['img'] = Yii::app()->params['version']['path'] . $data['8']['id'].'/'.$data['8']['img'];
    	}
//    	yii::app()->cache_app->set("app_version", $data, 60*5);
    	return $data;
    }


    public function saveRedis($channelid, $version)
    {
        $redisConf = Yii::app()->params->redis_data['version']['write'];
        $redis = RedisManager::getInstance($redisConf);
        $key = 'version_recently_version';
        $fild = 'app_version_channel:';
        $redis->setObjectInfo($key, $fild . $channelid, $version);
    }
    /**
     * @tutorial 获取缓存
     * @author liulong
     * @return array
     */
    public function getMemcache()
    {
    	$data = yii::app()->cache_app->get("app_version");
    	if (empty($data)){
    		$data = $this->saveMemcache();
    	}
    	return $data;
    }
}
