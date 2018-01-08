<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property integer $user_id
 * @property string $uri
 * @property string $data
 * @property string $ip
 * @property integer $created
 */
class Log extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iUserID, sUri, iCreated', 'required'),
			array('iUserID, iCreated', 'numerical', 'integerOnly'=>true),
			array('sUri', 'length', 'max'=>1000),
			array('sIp', 'length', 'max'=>20),
			array('sData', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iLogID, iUserID, sUri, sData, sIp, iCreated', 'safe', 'on'=>'search'),
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
			'iLogID' => 'ID',
			'iUserID' => '用户ID',
			'sUri' => '请求地址',
			'sData' => '数据',
			'sIp' => '请求IP',
			'iCreated' => '创建时间',
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

		$criteria->compare('iLogID',$this->iLogID);
		$criteria->compare('iUserID',$this->iUserID);
		$criteria->compare('sUri',$this->sUri,true);
		$criteria->compare('sData',$this->sData,true);
		$criteria->compare('sIp',$this->sIp,true);
		$criteria->compare('iCreated',$this->iCreated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'iLogID DESC',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 标准的日志记录--请使用这个
	 * @param $type
	 * @param $info
	 */
	public function sysLog($type,$info)
	{
		$fileName = '/data/logs/wxadmin/'.date('Ymd').'/'.$type.'.log';
		self::isPath($fileName);
		$info = date('Y-m-d H:i:s')."\n".(is_array($info)?implode("\n",$info):$info)."\n";
		error_log($info,3,$fileName);
		@chmod(dirname($fileName),0777);
	}


	/**记录日志
	 * @param $type
	 * @param $info
	 */
	public function logFile($type,$info)
	{
		$fileName = __DIR__.'/../runtime/'.$type.'/'.date('Ymd').'.log';
		self::isPath($fileName);
		$info = date('Y-m-d H:i:s')."\n".(is_array($info)?implode("\n",$info):$info)."\n";
		error_log($info,3,$fileName);
		@chmod(__DIR__.'/../runtime/'.$type.'/',0777);
	}

	/**
	 * 推送用的文件
	 * @param $path
	 */
	public function logPath($path)
	{
		$fileName = __DIR__.'/../runtime/rsync/'.date('YmdHi').'.log';
		self::isPath($fileName);
		error_log($path."\n",3,$fileName);
		@chmod(__DIR__.'/../runtime/rsync/',0777);
		@chmod($fileName,0777);

	}
	/**
	 * 路径是否存在
	 * @param $fileName
	 */
	private function isPath($fileName)
	{
		if(!is_file($fileName)){
			if(!is_dir($fileName)){
				$path = dirname($fileName);
				@mkdir($path);
				@chmod($path, 0777);
			}
			file_put_contents($fileName,"");
		}
	}

}
