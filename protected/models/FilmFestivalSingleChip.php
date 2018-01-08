<?php

/**
 * This is the model class for table "{{film_festival_single_chip}}".
 *
 * The followings are the available columns in table '{{film_festival_single_chip}}':
 * @property integer $id
 * @property integer $film_festival_id
 * @property string $movie_id
 * @property string $title
 * @property string $author_name
 * @property string $cover_map
 * @property string $author_portrait
 * @property integer $sort_num
 * @property integer $created
 * @property integer $updated
 */
class FilmFestivalSingleChip extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{film_festival_single_chip}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('film_festival_id, movie_id, title, author_name, cover_map, author_portrait, sort_num, brief, created, updated', 'required'),
			array('film_festival_id, sort_num, created, updated', 'numerical', 'integerOnly'=>true),
			array('movie_id, cover_map, author_portrait', 'length', 'max'=>255),
			array('title, author_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, film_festival_id, movie_id, title, brief, author_name, cover_map, author_portrait, sort_num, created, updated', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
            'brief' => 'Brief',
			'author_name' => 'Author Name',
			'cover_map' => 'Cover Map',
			'author_portrait' => 'Author Portrait',
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
		$criteria->compare('movie_id',$this->movie_id,true);
		$criteria->compare('title',$this->title,true);
        $criteria->compare('brief',$this->brief,true);
        $criteria->compare('author_name',$this->author_name,true);
		$criteria->compare('cover_map',$this->cover_map,true);
		$criteria->compare('author_portrait',$this->author_portrait,true);
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
	 * @return FilmFestivalSingleChip the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
