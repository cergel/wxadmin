<?php

/**
 * This is the model class for table "gold_seat_restrict_cinema".
 *
 * The followings are the available columns in table 'gold_seat_restrict_cinema':
 * @property integer $id
 * @property integer $cinema_no
 * @property integer $status
 * @property integer $update_time
 * @property string $cinema_name
 */
class SeatRestrictCinema extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gold_seat_restrict_cinema';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cinema_no, status, update_time, cinema_name', 'required'),
			array('cinema_no, status, update_time', 'numerical', 'integerOnly'=>true),
            array('cinema_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cinema_no, status, update_time, cinema_name', 'safe', 'on'=>'search'),
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
			'cinema_no' => 'Cinema No',
			'status' => 'Status',
			'update_time' => 'Update Time',
            'cinema_name' => 'Cinema Name',
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
		$criteria->compare('cinema_no',$this->cinema_no);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time);
        $criteria->compare('cinema_name',$this->cinema_name,true);

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
	 * @return SeatRestrictCinema the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getRestrictCinemaList()
    {
        $cinemaObjs = self::getAllRestrictCinema();
        $cinemas = [];
        foreach($cinemaObjs as $key => $value)
        {
            $cinemas[] = [
                'id' => $value->id,
                'cinema_no' => $value->cinema_no,
                'cinema_name' => $value->cinema_name,
            ];
        }
        return $cinemas;
    }

    public static function getAllRestrictCinema()
    {
        $model = new self();
        return $model->findAll("status = :status", [':status' => 1]);
    }

    public static function setRestrictCinemaNo($cinemaNos, $cinemaNames = [], $isDel = false)
    {
        $model = new self();
        $result = false;

        foreach( $cinemaNos as $key => $cinemaNo)
        {
            $cinema = $model->find('cinema_no = :cinema_no', [':cinema_no' => (int)$cinemaNo]);
            if(empty( $cinema)){
                $cinema = new self();
            }
            if(!$isDel){
                $cinema->cinema_name = $cinemaNames[$key];
                $cinema->cinema_no = (int)$cinemaNo;
            }
            $cinema->status = $isDel ? 0 : 1;
            $cinema->update_time = time();
            $result = $cinema->save();
        }
        return $result;
    }
}
