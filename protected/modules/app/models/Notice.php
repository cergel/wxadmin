<?php
require_once 'protected/vendor/autoload.php';
use JPush\Model as M;
use JPush\JPushClient;
use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;
class Notice extends CActiveRecord
{
	const STATUSTYPE_0     = 0; //开启客户端
	const STATUSTYPE_1     = 1; //电影详情
	const STATUSTYPE_2     = 2; //影院详情
	const STATUSTYPE_3     = 3; //活动
	public $iSendOn=0;           //是否发送
	public $andriodTest;		//安卓推送用户
	public $iosTest;			//苹果推送用户
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_app_notice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iSendtime', 'numerical', 'integerOnly'=>true),
			array('sTitle, sUrl', 'length', 'max'=>255),
			array('iPushid', 'length', 'max'=>50),
			array('iType, iIsdel, iIssend', 'length', 'max'=>1),
			array('sContext', 'safe'),
			array('sTitle, sContext,', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, sTitle, sContext, iPushid, sUrl, iType, iIsdel, iIssend, iSendtime', 'safe', 'on'=>'search'),
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
			'iId' => 'ID',
			'sTitle' => '标题',
			'sContext' => '内容',
			'iPushid' => '推送ID',
			'sUrl' => '链接',
			'iType' => '推送类型',
			'iIsdel' => '是否删除',
			'iIssend' => '是否发布',
			'iSendtime' => '发布时间',
			'iSendOn'   =>'推送到',
			'andriodTest'=>'推送安卓测试用户的 TAG',
			'iosTest'    =>'推送苹果测试用户TOKEN，TOKEN以逗号隔开(留空则推送全部、逗号是英文的 "," )'
		);
	}
	
	public static function getCategoryList()
	{
		//return array();
		return array(
				self::STATUSTYPE_0 => '开启客户端',
				self::STATUSTYPE_1 => '电影详情',
				self::STATUSTYPE_2 => '影院详情',
				self::STATUSTYPE_3 => '活动',
		);
	}
	
	public static function getSendOn(){
		return array(
			'0'=>'全部',
			'1'=>'安卓',
			'2'=>'苹果',		
		);
	}

	public static function checkVal($val=0){
		$result = '全部';
		switch($val){
			case 0:
				$result = '开启客户端';
				break;
			case 1:
				$result ='电影详情';
				break;
			case 2:
				$result ='影院详情';
				break;
			case 3:
				$result = '活动';
				break; 
		}
		return $result;
	}
	public static function getIssend(){
		return array(
					'0'=>'未发布',
					'1'=>'发布',
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
		$criteria->compare('sTitle',$this->sTitle,true);
		$criteria->compare('sContext',$this->sContext,true);
		$criteria->compare('iPushid',$this->iPushid,true);
		$criteria->compare('sUrl',$this->sUrl,true);
		$criteria->compare('iType',$this->iType,true);
		$criteria->compare('iIsdel',$this->iIsdel,true);
		$criteria->compare('iIssend',$this->iIssend,true);
		$criteria->compare('iSendtime',$this->iSendtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iId DESC',
            ),
					));
	}
	
	/**
	 * 自动更新时间
	 */
    public function beforeSave()
    {
        if ($this->isNewRecord)
            $this->iSendtime = time();
        return true;
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
	 * @return Notice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 发送消息
	 */
	public function push(){
		
		//苹果
		if(empty($_POST['Notice']['iSendOn']) || $_POST['Notice']['iSendOn']==2)
			$this->pushinfoIos();
		//安卓
		if(empty($_POST['Notice']['iSendOn']) || $_POST['Notice']['iSendOn']==1)
			$this->pushinfoAndroid();
	}
	
	
	/**
	 * @todu 推送key暂时写死在这里
	 * 推送消息 ios
	 */
	private function pushinfoIos(){
		//如果为定点发送
		if(!empty($_POST['Notice']['iosTest'])){
			$tokens = explode(',',$_POST['Notice']['iosTest']);
			$body['aps'] = array(
					'alert' => $this->attributes['sContext'],
					'sound' => 'default',
					'openType' => $this->attributes['iType'],
					'openId'	=> strval($this->attributes['iPushid']),
					'link'		=> $this->attributes['sUrl']
			);
			$payload = json_encode($body);
			foreach($tokens as $token){
				if(!empty($token)){
					$iosObj = $this->getOpenIosObj();
					$msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
					$result = fwrite($iosObj, $msg, strlen($msg));
					fclose($iosObj);
				}
			}
		}else{
			//将消息放入队列、crontab跑
			$pushKey = 'ios_push_user';
			Yii::import('ext.RedisManager',true);
			$objRedis = RedisManager::getInstance(array('host'=>Yii::app()->params->redis['host'],'port'=>Yii::app()->params->redis['port']));
			if($objRedis){
				$tokenList = $command = Yii::app()->db_user->createCommand('SELECT * FROM t_weiying_users_token order by id ASC ')->queryAll();
				foreach($tokenList as $info){
					$info = array_merge($info,$this->attributes);
					$objRedis->listPush($pushKey,json_encode($info));
				}
			}
		}
	}
	
	/**
	 * 推送消息Android
	 */
	private function pushinfoAndroid(){
		$app_key = Yii::app()->params->jpush['appkey'];
		$master_secret = Yii::app()->params->jpush['masterSecret'];
		$client = new JPushClient($app_key, $master_secret);
		$data = array(
				'openType'=>intval($this->attributes['iType']),
				'openId' => $this->attributes['iPushid'],
				'link' => $this->attributes['sUrl']
		);
		
		//推送给指定标签安卓用户
		if(!empty($_POST['Notice']['andriodTest'])){
				$result=$client->push()
				->setPlatform(M\all)
				->setAudience(M\audience(M\tag(array($_POST['Notice']['andriodTest']))))
				->setNotification(M\notification(null, M\android( $this->attributes['sContext'],null,null,$data)))
				->send();
		}else{
		//推送给全部安卓用户
			$result=$client->push()
			->setPlatform(M\all)
			->setAudience(M\all)
			->setNotification(M\notification(null, M\android( $this->attributes['sContext'],null,null,$data)))
			->send();
		}
	}
	
	
	/**
	 * 获取apple推送对象
	 * @return resource
	 */
	private function getOpenIosObj(){
		$check_pem = __DIR__.'/push-dev2.pem';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $check_pem);
		stream_context_set_option($ctx, 'ssl', 'passphrase', '123456');
		
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195',$err,$errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		return $fp;
	}
	
}
