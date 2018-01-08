<?php

/**
 * This is the model class for table "{{question_set}}".
 *
 * The followings are the available columns in table '{{question_set}}':
 * @property string $id
 * @property string $name
 * @property integer $num
 * @property string $pic
 * @property string $create_time
 * @property string $update_time
 */
class QuestionSet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_question_set';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, num', 'required'),
			array('num', 'numerical', 'integerOnly'=>true),
			array('name, pic', 'length', 'max'=>255),
			array('create_time, update_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, num, pic, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'question' => array(self::HAS_MANY, 'QuestionSetQuestions', 'qs_id'),
		);

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '题集名称',
			'num' => '随机题目数量',
			'pic' => '答题底图',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'question'    =>'题目总数',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('num',$this->num);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_active;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuestionSet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 反向更新
	 * @param $id
	 */
	public function saveFileInfo($id)
	{
		$applyActive = ApplyActive::model()->findAll('question='.$id);
		if(!empty($applyActive))
		foreach($applyActive as $val){
			$model = ApplyActive::model()->findByPk($val->id);
			$share = [];
			if(is_array($model->share)){
				foreach($model->share as $sval){
					$share[] = $sval['share'];
				}
			}
			$platform = [];
			if(is_array($model->platform)){
				foreach ($model->platform as $val) {
					$platform[] = $val['platform'];
				}
			}
			$model->platform = $platform;

			$model->share = $share;
			$model->makeFile();
		}
	}
	public function getQuestion($str)
	{
		return count($str);
//		$str = json_decode($str);
//		if(!empty($str['question'])){
//			return count($str['question']);
//		}
	}
}
