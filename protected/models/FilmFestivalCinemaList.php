<?php

/**
 * This is the model class for table "{{film_festival_cinema_list}}".
 *
 * The followings are the available columns in table '{{film_festival_cinema_list}}':
 * @property integer $id
 * @property integer $film_festival_id
 * @property integer $cinema_id
 * @property string $cinema_name
 * @property integer $sort_num
 * @property integer $city_id
 * @property integer $created
 * @property integer $updated
 */
class FilmFestivalCinemaList extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{film_festival_cinema_list}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('film_festival_id, cinema_id,city_id, cinema_name, sort_num, created, updated', 'required'),
            array('film_festival_id, cinema_id,city_id, sort_num, created, updated', 'numerical', 'integerOnly' => true),
            array('cinema_name', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, film_festival_id, cinema_id,city_id, cinema_name, sort_num, created, updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'film_festival_id' => 'Film Festival',
            'cinema_id' => 'Cinema',
            'cinema_name' => 'Cinema Name',
            'sort_num' => 'Sort Num',
            'city_id' => 'city_id',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('film_festival_id', $this->film_festival_id);
        $criteria->compare('cinema_id', $this->cinema_id);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('cinema_name', $this->cinema_name, true);
        $criteria->compare('sort_num', $this->sort_num);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FilmFestivalCinemaList the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 添加任务
     * @param $job_id
     * @param $film_festival_id
     * @param $cinema
     * @return array
     */
    public static function pushCinemaAdd($job_id, $film_festival_id, $cinema)
    {
        //判断是否有jobId需要删除
        if ($job_id) {
            $delRes = self::pushCinemaDel($job_id);
            if ($delRes['code'] != 0) {
                //return self::alert_info(1, '修改失败');
            }
        }
        $url = Yii::app()->params['delayQueue']['add'];
        $festival = FilmFestival::model()->findByPk($film_festival_id);
        if (time() <= $festival['ticket_time']) {
            $delay = $festival['ticket_time'] - time();
            $data = [
                'sponsor' => 'wp-film-festival',
                'delay' => $delay,
                'url' => Yii::app()->params['schedule'] . $cinema];
            $re = Https::getPost($data, $url);
            $res = json_decode($re, true);
            if (isset($res['ret']) && $res['ret'] == 0) {
                FilmFestivalCinemaList::model()->updateAll(['job_id' => $res['data']['job_id']], 'cinema_id=:cinema_id AND film_festival_id=:film_festival_id', [':cinema_id' => $cinema, ':film_festival_id' => $film_festival_id]);
                return self::alert_info(1, '更新成功');
            } else {
                return self::alert_info(1, '已过期');
            }
        } else {
            return self::alert_info(1, '已过期');
        }
    }

    /**
     * 撤消任务
     * @param $job_id
     * @return array
     */
    public static function pushCinemaDel($job_id)
    {
        $url = Yii::app()->params['delayQueue']['revoke'];
        $data = ['job_id' => $job_id];
        $re = Https::getPost($data, $url);
        $res = json_decode($re, true);
        if (isset($res['ret']) && $res['ret'] == 0) {
            return self::alert_info(0, '撤销成功');
        }
        return self::alert_info(1, '通知失败');
    }

    public static function pushCinemaByFilmFestivalId($filmFestivalId)
    {
        self::pushCommoncgi($filmFestivalId);
        $cinemas = FilmFestivalCinemaList::model()->findAll('film_festival_id=:film_festival_id', [':film_festival_id' => $filmFestivalId]);
        if (!empty($cinemas)) {
            foreach ($cinemas as $cinema) {
                self::pushCinemaAdd($cinema['job_id'], $cinema['film_festival_id'], $cinema['cinema_id']);
            }
        }
    }

    /**
     * 通知commoncgi
     * @param $filmFestivalId
     * @return array
     */
    public static function pushCommoncgi($filmFestivalId)
    {
        $url = Yii::app()->params['festivalEdit'] . $filmFestivalId;
        $data = [];
        $re = Https::getPost($data, $url);
        $res = json_decode($re, true);
        if (isset($res['ret']) && $res['ret'] == 0) {
            return self::alert_info(0, '通知成功');
        }
        return self::alert_info(1, '通知失败');
    }

    /**
     * @param $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    private static function alert_info($code, $msg = '', $data = [])
    {
        return ['code' => (int)$code, 'msg' => $msg, 'data' => $data];
    }
}
