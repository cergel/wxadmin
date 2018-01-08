<?php

/**
 * This is the model class for table "{{vote_answer}}".
 *
 * The followings are the available columns in table '{{vote_answer}}':
 * @property string $id
 * @property string $vote_id
 * @property string $answer
 * @property integer $fill
 * @property string $picture
 */
class VoteAnswer extends CActiveRecord
{

    public $answerRealNum;
    public $answerRatio;
    public $answerRatioReally;

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_vote_answer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vote_id, answer', 'required'),
			array('fill', 'numerical', 'integerOnly'=>true),
			array('vote_id', 'length', 'max'=>10),
			array('answer, picture', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vote_id, answer, fill, picture', 'safe', 'on'=>'search'),
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
			'vote_id' => 'Vote',
			'answer' => 'Answer',
			'fill' => 'Fill',
			'picture' => 'Picture',
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
		$criteria->compare('vote_id',$this->vote_id,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('fill',$this->fill);
		$criteria->compare('picture',$this->picture,true);

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
	 * @return VoteAnswer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
