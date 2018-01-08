<?php
class VersionFrom extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_version_from';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('version, fromId,status', 'required'),
			array('created, updated, fromId', 'numerical', 'integerOnly'=>true),
			array('version', 'length', 'max'=>40),
            array('path', 'length', 'max'=>200),
			array('id, version,description, path, created,status,fromId, updated', 'safe'),
			array('id, version, path,created,status,fromId, updated', 'safe', 'on'=>'search'),
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
			'status' => '状态',
			'version' => '版本号',
			'path' => '下载地址',
            'fromId' => '渠道号',
            'description' => '版本说明',
            'created' => '创建时间',
            'updated' => '更新时间',
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
		$criteria->compare('version',$this->version,true);
        $criteria->compare('path',$this->path,true);
        $criteria->compare('fromId',$this->fromId,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('created',$this->created);
        $criteria->compare('updated',$this->updated);

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
	public function getPath($path,$id)
	{
		if (!empty($path) && !empty($id))
			$path = 'http://'.$_SERVER["HTTP_HOST"].'/uploads/app_version_from/'.$id.'/'.$path;
		else $path ='';
		$path = '<a href="'.$path.'" target="_blank" >'.$path.'</a>';
		return $path;
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
     * @tutorial 更新json文件
     * @author liulong
     * @return array
     */
    public function saveJson()
    {
    	$data = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} Where status = '1'  order by id desc")->queryAll();
    	foreach ($data as &$val)
    	{
    		$val['path'] =Yii::app()->params['app_version_from']['final_url'].'/'.$val['id'].'/'.$val['path'];
    	}
    	if (!file_exists(dirname(Yii::app()->params['app_version_from']['target_dir'] . '/version.json')))
    		mkdir(dirname(Yii::app()->params['app_version_from']['target_dir'] . '/version.json'), 0777, true);
    	file_put_contents(Yii::app()->params['app_version_from']['target_dir'] . '/version.json', 'callbackversion('.json_encode(array_values($data)).')');
    }
}
