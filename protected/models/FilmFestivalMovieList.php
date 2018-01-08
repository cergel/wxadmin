<?php

/**
 * This is the model class for table "{{film_festival_movie_list}}".
 *
 * The followings are the available columns in table '{{film_festival_movie_list}}':
 * @property integer $id
 * @property integer $film_festival_id
 * @property integer $movie_id
 * @property string $movie_name
 * @property integer $sort_num
 * @property string $row_piece_time
 * @property integer $created
 * @property integer $updated
 */
class FilmFestivalMovieList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{film_festival_movie_list}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('film_festival_id, movie_id, movie_name, created, updated', 'required'),
			array('film_festival_id,status_type, movie_id, sort_num, created, updated', 'numerical', 'integerOnly'=>true),
			array('movie_name', 'length', 'max'=>100),
			array('row_piece_time', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status_type,film_festival_id, movie_id, movie_name, sort_num, row_piece_time, created, updated', 'safe', 'on'=>'search'),
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
			'movie_id' => 'Movie',
			'movie_name' => 'Movie Name',
			'sort_num' => 'Sort Num',
			'row_piece_time' => 'Row Piece Time',
			'created' => 'Created',
			'updated' => 'Updated',
            'status_type'=>'status_type',
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
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('movie_name',$this->movie_name,true);
		$criteria->compare('sort_num',$this->sort_num);
		$criteria->compare('row_piece_time',$this->row_piece_time,true);
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
	 * @return FilmFestivalMovieList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
