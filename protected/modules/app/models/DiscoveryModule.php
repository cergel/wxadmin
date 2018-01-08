<?php

/**
 * This is the model class for table "{{module_discovery}}".
 *
 * The followings are the available columns in table '{{module_discovery}}':
 * @property string $Module_Id
 * @property string $Title
 * @property string $Label
 * @property string $Link
 * @property string $Pic
 * @property string $Created_time
 * @property string $Updated_time
 * @property integer $Status
 * @property string $Platform
 * @property string $Start
 * @property string $End
 */
class DiscoveryModule extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{module_discovery}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Title,Module_Name,Label,Link,Pic,Platform,Start,End', 'required'),
			array('Status', 'numerical', 'integerOnly'=>true),
			array('Title, Label, Link, Pic ,Content', 'length', 'max'=>255),
			array('Created_time, Updated_time, Start, End', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Module_Id, Title, Label, Link, Pic,Created_time, Updated_time, Status, Platform, Start, End', 'safe', 'on'=>'search'),
			array('Platform', 'setPlatform'),
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
			'Module_Id' => '发现模块ID',
			'Module_Name' => '模块名称',
			'Title' => '发现标题',
			'Label' => '发现标签',
			'Link' => '发现连接',
			'Pic' => '发现图片',
			'Created_time' => '创建时间',
			'Updated_time' => '修改时间',
			'Status' => '状态',
			'Platform' => '渠道',
			'Start' => '开始日期',
			'End' => '结束日期',
			'Content' => '内容介绍',
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

		$criteria->compare('Module_Id',$this->Module_Id,true);
		$criteria->compare('Title',$this->Title,true);
		$criteria->compare('Label',$this->Label,true);
		$criteria->compare('Link',$this->Link,true);
		$criteria->compare('Pic',$this->Pic,true);
		$criteria->compare('Created_time',$this->Created_time,true);
		$criteria->compare('Updated_time',$this->Updated_time,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('Platform',$this->Platform,true);
		$criteria->compare('Start',$this->Start,true);
		$criteria->compare('End',$this->End,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DiscoveryModule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	*自定义验证规则判断各个渠道上线日期上是否有排期冲突
	*/
	public function setPlatform($attribute,$params){
		
		//首先转换和填充Model的数据
		if(!isset($_POST['DiscoveryModule']['Platform'])){
			$this->addError($attribute, '请选择模块投放的应用平台');
			return false;
		}
		$this->Platform = json_encode($_POST['DiscoveryModule']['Platform'],JSON_UNESCAPED_UNICODE);
		
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
		if($this->Module_Id){
			$rows = Yii::app()->db->createCommand("SELECT * FROM $table WHERE ( Module_Id != {$this->Module_Id} and STATUS = 1 AND START < '{$this->Start}' AND END > '{$this->Start}') OR (Module_Id != {$this->Module_Id} and STATUS = 1 AND START >= '{$this->Start}' AND END < '{$this->End}')OR ( Module_Id != {$this->Module_Id} and STATUS = 1 AND START < '{$this->End}' AND END > '{$this->Start}')")->queryAll();
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
		$is_exist = array_intersect($exist_Platform,$_POST['DiscoveryModule']['Platform']);
		$is_exist = array_values($is_exist);
		if($is_exist){
			$err_str = json_encode($is_exist,JSON_UNESCAPED_UNICODE);
			$this->addError($attribute, "{$err_str}在{$this->Start}至{$this->End}与排期 发生冲突请<a href='/app/discoverymodule/index'>检查排期</a>");
		}
			
	}
}
