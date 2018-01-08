<?php

/**
 * This is the model class for table "{{film_festival_screening_unit}}".
 *
 * The followings are the available columns in table '{{film_festival_screening_unit}}':
 * @property integer $id
 * @property integer $film_festival_id
 * @property string $level1_unit
 * @property string $level2_unit
 * @property string $movie_id
 * @property integer $sort_num
 * @property integer $created
 * @property integer $updated
 */
class FilmFestivalScreeningUnit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{film_festival_screening_unit}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('film_festival_id, movie_id, created, updated', 'required'),
			array('film_festival_id, sort_num, created, updated', 'numerical', 'integerOnly'=>true),
			array('level1_unit, level2_unit', 'length', 'max'=>100),
			array('movie_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, film_festival_id, level1_unit, level2_unit, movie_id, sort_num, created, updated', 'safe', 'on'=>'search'),
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
			'film_festival_id' => 'Film Festival',
			'level1_unit' => 'Level1 Unit',
			'level2_unit' => 'Level2 Unit',
			'movie_id' => 'Movie',
			'sort_num' => 'Sort Num',
			'created' => 'Created',
			'updated' => 'Updated',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('film_festival_id',$this->film_festival_id);
		$criteria->compare('level1_unit',$this->level1_unit,true);
		$criteria->compare('level2_unit',$this->level2_unit,true);
		$criteria->compare('movie_id',$this->movie_id,true);
		$criteria->compare('sort_num',$this->sort_num);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FilmFestivalScreeningUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
