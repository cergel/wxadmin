<?php

/**
 * This is the model class for table "{{module_video}}".
 *
 * The followings are the available columns in table '{{module_video}}':
 * @property string $Video_Module_Id
 * @property string $Module_Title
 * @property string $Video1_Title
 * @property string $Video1_Vid
 * @property string $Video1_Movie
 * @property string $Video1_Pic
 * @property string $Video2_Title
 * @property string $Video2_Vid
 * @property string $Video2_Pic
 * @property string $Video2_Movie
 * @property string $Created_time
 * @property string $Updated_time
 * @property integer $Status
 * @property integer $End
 * @property integer $Start
 * @property integer $Platform
 */
class VideoModule extends CActiveRecord
{
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{module_video}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Module_Title,Video1_Movie,Video2_Movie,Video1_Title,Video2_Title,Video1_Pic,Video2_Pic,Start,End,Platform,Status', 'required'),
			array('Status', 'numerical', 'integerOnly'=>true),
			array('Module_Title, Video1_Title, Video1_Vid, Video1_Movie, Video1_Pic, Video2_Title, Video2_Vid, Video2_Pic, Video2_Movie', 'length', 'max'=>255),
			array('Created_time, Updated_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Video_Module_Id, Module_Title, Video1_Title, Video1_Vid, Video1_Movie, Video1_Pic, Video2_Title, Video2_Vid, Video2_Pic, Video2_Movie, Created_time, Updated_time, Status', 'safe', 'on'=>'search'),
			//自定义验证规则
			array('Platform', 'setPlatform'),
		);
	}

	
	/**
	*自定义验证规则判断各个渠道上线日期上是否有排期冲突
	*/
	public function setPlatform($attribute,$params){
		//首先转换和填充Model的数据
		if(!isset($_POST['VideoModule']['Platform'])){
			$this->addError($attribute, '请选择模块投放的应用平台');
			return false;
		}
		$this->Platform = json_encode($_POST['VideoModule']['Platform'],JSON_UNESCAPED_UNICODE);
		
		//判断是否勾选上线属性
		if (!$this->Status){
			return false;
		}
		//查询指定日期内上线的数据
		if (strtotime($this->End) <= strtotime($this->Start)){
			$this->addError($attribute, '起始日期不能小于等于结束日期');
			return false;
		}
		$table = $this->tableName();
		
		//判断排期是否冲突
		if($this->Video_Module_Id){
			$rows = Yii::app()->db->createCommand("SELECT * FROM $table WHERE ( Video_Module_Id != {$this->Video_Module_Id} and STATUS = 1 AND START < '{$this->Start}' AND END > '{$this->Start}') OR (Video_Module_Id != {$this->Video_Module_Id} and STATUS = 1 AND START >= '{$this->Start}' AND END < '{$this->End}')OR ( Video_Module_Id != {$this->Video_Module_Id} and STATUS = 1 AND START < '{$this->End}' AND END > '{$this->Start}')")->queryAll();
		}else{
			$rows = Yii::app()->db->createCommand("SELECT * FROM $table WHERE ( STATUS = 1 AND START < '{$this->Start}' AND END > '{$this->Start}') OR (STATUS = 1 AND START >= '{$this->Start}' AND END < '{$this->End}')OR (STATUS = 1 AND START < '{$this->End}' AND END > '{$this->Start}')")->queryAll();
		}
		$Platform = [];
		foreach($rows as $value){
			$select_Platform = json_decode($value['Platform'],true);
			$Platform = array_merge($Platform,$select_Platform);
		}
		$exist_Platform = array_unique($Platform);
		//判断提交过来的渠道数据和数据库查询出来的数据有没有交集
		$is_exist = array_intersect($exist_Platform,$_POST['VideoModule']['Platform']);
		$is_exist = array_values($is_exist);
		if($is_exist){
			$err_str = json_encode($is_exist,JSON_UNESCAPED_UNICODE);
			$this->addError($attribute, "{$err_str}在{$this->Start}至{$this->End}与排期 发生冲突请<a href='/app/videomodule/index'>检查排期</a>");
		}
			
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
			'Video_Module_Id' => '视频模块ID',
			'Module_Title' => '模块标题',
			'Video1_Title' => '第一推荐位标题',
			'Video1_Vid' => '第一推荐位预告片',
			'Video1_Movie' => '第一推荐位电影ID',
			'Video1_Pic' => '第一推荐位图片',
			'Video2_Title' => '第二推荐位标题',
			'Video2_Vid' => '第二推荐位预告片',
			'Video2_Pic' => '第二推荐位图片',
			'Video2_Movie' => '第二推荐位电影ID',
			'Created_time' => '创建时间',
			'Updated_time' => '修改时间',
			'Status' => '发布状态',
			'Platform' => '发布渠道',
			'Start' => '开始日期',
			'End' => '结束日期',
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

		$criteria->compare('Video_Module_Id',$this->Video_Module_Id,true);
		$criteria->compare('Module_Title',$this->Module_Title,true);
		$criteria->compare('Video1_Title',$this->Video1_Title,true);
		$criteria->compare('Video1_Vid',$this->Video1_Vid,true);
		$criteria->compare('Video1_Movie',$this->Video1_Movie,true);
		$criteria->compare('Video1_Pic',$this->Video1_Pic,true);
		$criteria->compare('Video2_Title',$this->Video2_Title,true);
		$criteria->compare('Video2_Vid',$this->Video2_Vid,true);
		$criteria->compare('Video2_Pic',$this->Video2_Pic,true);
		$criteria->compare('Video2_Movie',$this->Video2_Movie,true);
		$criteria->compare('Created_time',$this->Created_time,true);
		$criteria->compare('Updated_time',$this->Updated_time,true);
		$criteria->compare('Start',$this->Start,true);
		$criteria->compare('End',$this->End,true);
		$criteria->compare('Platform',$this->Status);
		$criteria->compare('Start',$this->Status);
		$criteria->compare('End',$this->Status);
		$criteria->compare('Status',$this->Status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return videomodule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
