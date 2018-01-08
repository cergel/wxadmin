<?php

/**
 * This is the model class for table "gold_seat_restrict_movie".
 *
 * The followings are the available columns in table 'gold_seat_restrict_movie':
 * @property integer $id
 * @property integer $movie_id
 * @property integer $status
 * @property integer $update_time
 */
class SeatRestrictMovie extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gold_seat_restrict_movie';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('movie_id, status, update_time', 'required'),
			array('movie_id, status, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movie_id, status, update_time', 'safe', 'on'=>'search'),
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
			'movie_id' => 'Movie',
			'status' => 'Status',
			'update_time' => 'Update Time',
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
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_gold_seat;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SeatRestrictMovie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getAllRestrictMovie()
    {
        $movieNos = self::getAllRestrictMovieNo();
        if (empty($movieNos))
            return [];
        $result = [];
        foreach($movieNos as $key => $value)
        {
            $movieId = $value;
            $url = API_MOVIEDATABASE.'/movie/info';
            $sendData=[
                'movieId'=>$movieId,
                'from'=>7000100003,
            ];
            $strJson = Https::getPost($sendData,$url);
            $obj = json_decode($strJson);
            if($obj->ret==0 && $obj->sub==0){
                $result[] = ['MovieNo' => $obj->data->MovieNo, 'MovieNameChs'=>$obj->data->MovieNameChs];
            }
        }

        return $result;
    }

    /**
     * 获取限制列表的影片NOs
     * @return array or []
     */
    public static function getAllRestrictMovieNo()
    {
        $model = new self();
        $result = $model->findAll('status = :status', [':status' => 1]);

        if( !empty($result) ){
            $movieNos = [];
            foreach( $result as $key => $movie){
                $movieNos[] = $movie->movie_id;
            }

            return $movieNos;
        }else{
            return [];
        }
    }

    public static function setRestrictMovieNo($movieNos, $isDel = false)
    {
        $result = false;
        $model = new self();
        foreach($movieNos as $key => $movieNo){
            $movie = $model->find('movie_id = :movieId', [':movieId' => (int)$movieNo]);
            if(empty($movie)){
                $movie = new self();
            }
            $movie->movie_id = $movieNo;
            $movie->status = $isDel ? 0 : 1;
            $movie->update_time = time();
            $result = $movie->save();
        }
        return $result;
    }
}
