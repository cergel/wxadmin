<?php
class AppDayPush extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_day_push}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iId','unique','message'=>'当天推荐已存在'),
			array('sTitle, sText, sSource,sImages', 'required'),
			array('sText', 'length', 'max'=>1000),
			array('sTitle', 'length', 'max'=>18),
			array('sSource', 'length', 'max'=>100),
			array('sImages', 'length', 'max'=>100),
			array('sShareContent,sSharePic', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iId, sTitle, sText, sImages, sSource, sShareContent,sSharePic', 'safe', 'on'=>'search'),
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
			'iId' => 'I',
			'sTitle' => '标题',
			'sImages' => '封面图',
			'sText' => '内容',
			'sSource' => '来源',
			'sShareContent'=>'分享内容',
			'sSharePic'=>'分享图片',
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
		$criteria->compare('sText',$this->sText,true);
		$criteria->compare('sImages',$this->sImages,true);
		$criteria->compare('sSource',$this->sSource,true);
		$criteria->compare('sShareContent',$this->sShareContent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iId DESC',
            ),
					));
	}
	
	public function getDay($iId){
		$iId = intval($iId);
		$list = Yii::app()->db->createCommand()->select("*")->from("t_app_day_push")->where('iId=:id',array(':id'=>$iId))->queryRow();
		return empty($list)?false:$list;
	}
	
	public function getAll(){
		$list = Yii::app()->db->createCommand()->select("iId")->from("t_app_day_push")->queryAll();
		return empty($list)?false:$list;
	}
	/**
	 * 获取最新缓存存入memcache
	 */
	public function getMemcachePush()
	{
		$date = date('Ymd',time()+24*3600);
		$sql = " SELECT * FROM {$this->tableName()} WHERE iId< $date ORDER BY iId DESC";
		$list = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($list)){
			yii::app()->cache_app->set('day_push_caches_key', $list, 60*5);
		}
	}
	
	public function beforeSave()
    {
		//$this->iId = date("Ymd",time());
		return true;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppDayPush the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
}