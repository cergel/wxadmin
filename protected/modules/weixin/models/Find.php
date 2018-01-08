<?php
/**
 * This is the model class for table "{{movie_order}}".
 *
 * The followings are the available columns in table '{{movie_order}}':
 * @property integer $id
 * @property integer $movie_id
 * @property string $movie_name
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $pos
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 * @property string $content
 */
class Find extends CActiveRecord
{

    private $redis;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{weixin_find}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, startTime, endTime,content,status,', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('content', 'length', 'max'=>100),

			CUploadedFile::getInstance($this, 'picture') || $this->isNewRecord ? array('picture',
				'file',
				'allowEmpty'=>!$this->isNewRecord,
				'types'=>'jpg,png,gif',
				'tooLarge'=>'大图大于512kb，上传失败！请上传小于512kb的文件！'
			) : array('picture', 'length', 'max'=>200),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, startTime, endTime,picture,content, status, created, updated', 'safe', 'on'=>'search'),
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
			'title' => '活动标题',
			'startTime' => '开始生效时间',
			'endTime' => '结束生效时间',
			'picture' => '导流图片',
			'status' => '状态',
			'created' => '创建时间',
			'updated' => '修改时间',
			'content' => '导流文案',
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
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('endTime',$this->endTime);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MovieOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return bool时间更新
	 */
	public function beforeSave()
	{
		if($this->startTime && !is_numeric($this->startTime))
			$this->startTime = strtotime($this->startTime);
		if($this->endTime && !is_numeric($this->endTime))
			$this->endTime = strtotime($this->endTime);
		$this->updated = time();
		if ($this->isNewRecord)
			$this->created = time();
		if($this->updated && !is_numeric($this->updated))
			$this->updated = strtotime($this->updated);
		if($this->created && !is_numeric($this->created))
			$this->created = strtotime($this->created);
		return true;
	}

	public function afterSave()
	{
		$this->afterFind();
	}
	public function afterFind()
	{
		if($this->startTime)
			$this->startTime = date('Y-m-d H:i:s', $this->startTime);
		if($this->endTime)
			$this->endTime = date('Y-m-d H:i:s', $this->endTime);
		if($this->updated)
			$this->updated = date('Y-m-d H:i:s', $this->updated);
		if($this->created)
			$this->created = date('Y-m-d H:i:s', $this->created);
	}


    //生成缓存文件
    public function saveJson(){
		return true;
        $sql='select * from t_weixin_find where status = 1 and endTime > '.time();
        $dbRe = self::model()->findAllBySql($sql);
		$arrData = [];
        if($dbRe){
            foreach($dbRe as $v){
                $tmpArr = [
                    'id'=>$v->id,
                    'title'=>$v->title,
                    'startTime'=>$v->startTime,
                    'endTime'=>$v->endTime,
                    'content'=>$v->content,
					'picture'=>Yii::app()->params['WeiXinFind']['cdn'].$v->id.'/'.$v->picture,
                ];
				$arrData[]=$tmpArr;
            }
        }
		if (!file_exists(dirname(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json')))
			mkdir(dirname(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json'), 0777, true);
		file_put_contents(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json', 'callbackfaxian('.json_encode($arrData).')');
    }
	//生成缓存文件
	public static function saveJsonS(){
		$sql='select * from t_weixin_find where `status` = 1 and endTime > '.time();
		$dbRe = self::model()->findAllBySql($sql);
		$arrData = [];
		if($dbRe){
			foreach($dbRe as $v){
				$tmpArr = [
					'id'=>$v->id,
					'title'=>$v->title,
					'startTime'=>$v->startTime,
					'endTime'=>$v->endTime,
					'content'=>$v->content,
					'picture'=>Yii::app()->params['WeiXinFind']['cdn'].$v->id.'/'.$v->picture,
				];
				$arrData[]=$tmpArr;
			}

		}
		if (!file_exists(dirname(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json')))
			mkdir(dirname(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json'), 0777, true);
		file_put_contents(Yii::app()->params['WeiXinFind']['target_dir'] . '/weixinfind.json', 'callbackfaxian('.json_encode($arrData).')');
	}
}
