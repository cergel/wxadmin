<?php

/**
 * This is the model class for table "{{film_festival}}".
 *
 * The followings are the available columns in table '{{film_festival}}':
 * @property integer $id
 * @property string $filmfest_name
 * @property string $open_abnormal
 * @property string $url_param
 * @property string $create_user
 * @property integer $open_top
 * @property string $top_info
 * @property string $top_bar_chart
 * @property integer $open_introduce
 * @property string $introduce
 * @property integer $open_schedule
 * @property string $schedule
 * @property string $share_icon
 * @property string $visual_color
 * @property string $share_title
 * @property string $share_describe
 * @property string $abnormal_SMS
 * @property integer $ticket_time
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $created
 * @property integer $updated
 */
class FilmFestival extends CActiveRecord
{
    private static $movieInfo;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{film_festival}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('filmfest_name,city_name,city_ids,bisServerid,url_param, create_user, open_top, open_introduce, open_schedule,open_abnormal,visual_color,share_icon, share_title, share_describe, ticket_time, start_time, end_time, created, updated', 'required'),
            array('open_top, open_introduce, open_schedule,open_abnormal, ticket_time, start_time, end_time, created, updated', 'numerical', 'integerOnly' => true),
            array('filmfest_name,city_name', 'length', 'max' => 150),
            array('url_param,city_ids, top_bar_chart, share_describe', 'length', 'max' => 255),
            array('city_ids, top_bar_chart, share_describe', 'length', 'max' => 255),
            array('create_user, share_icon,visual_color, share_title', 'length', 'max' => 100),
            array('abnormal_SMS', 'length', 'max' => 500),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,abnormal_SMS,city_name,filmfest_name,city_ids,bisServerid, url_param, create_user, open_top, top_info, top_bar_chart, open_introduce, introduce, open_schedule,open_abnormal, schedule,visual_color,share_icon, share_title, share_describe, ticket_time, start_time, end_time, created, updated', 'safe', 'on' => 'search'),
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
            'city_name'=>'city_name',
            'filmfest_name' => '电影节名称',
            'url_param' => '参数',
            'city_ids' => '城市id',
            'bisServerid' => 'bisServerid',
            'create_user' => '创建人',
            'open_top' => '是否展示顶部推荐',
            'top_info' => 'Top Info',
            //'category_recommend' => '分类推荐',
            'top_bar_chart' => '顶部权限',
            'open_introduce' => '是否开启电影节介绍',
            'introduce' => '电影节介绍',
            'open_schedule' => '是否展示电影节日程',
            'schedule' => '日程',
            'share_icon' => '分享图标',
            'share_title' => '分享标题',
            'share_describe' => '分享描述',
            'visual_color' => '主视觉色',
            'ticket_time' => '购票时间',
            'open_abnormal' => '是否开启异常短信',
            'abnormal_SMS' => '异常短信详情',
            'start_time' => '上线时间',
            'end_time' => '结束时间',
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
        $criteria->compare('filmfest_name', $this->filmfest_name, true);
        $criteria->compare('url_param', $this->url_param, true);
        $criteria->compare('create_user', $this->create_user, true);
        $criteria->compare('open_top', $this->open_top);
        $criteria->compare('top_info', $this->top_info, true);
        $criteria->compare('top_bar_chart', $this->top_bar_chart, true);
        $criteria->compare('open_introduce', $this->open_introduce);
        $criteria->compare('introduce', $this->introduce, true);
        $criteria->compare('open_schedule', $this->open_schedule);
        $criteria->compare('open_abnormal', $this->open_abnormal);
        $criteria->compare('schedule', $this->schedule, true);
        $criteria->compare('share_icon', $this->share_icon, true);
        $criteria->compare('visual_color', $this->visual_color, true);
        $criteria->compare('share_title', $this->share_title, true);
        $criteria->compare('share_describe', $this->share_describe, true);
        $criteria->compare('ticket_time', $this->ticket_time);
        $start_time = $this->start_time;
        $now = time();
        if ($start_time == 1) {
            //上线
            $criteria->addCondition("`start_time` <= $now AND `end_time` >= $now");
        } else if ($start_time == 2) {
            //下线
            $criteria->addCondition(" $now < `start_time` OR `end_time` < $now");
        } else {
            $criteria->compare('start_time', strtotime($this->start_time));
        }
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FilmFestival the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
        echo '<div class="' . FilmFestival::model()->getStatus($data->start_time, $data->end_time) . '">' . date('Y-m-d H:i:s', $data->start_time) . '</div>';
    }

    public function getStatus($start_time, $end_time)
    {
        if (empty($start_time) || empty($end_time)) {
            return '未知';
        } else if ($start_time < time() && time() < $end_time) {
            return '上线';
        } else {
            return '下线';
        }
    }

    public function getStatusList($start_time, $end_time)
    {
        if (empty($start_time) || empty($end_time)) {
            return '';
        } else if ($start_time > time()) {
            return 'error';
        } else {
            return '';
        }
    }

    /**
     * 异步获取影片信息
     * @param $movieId
     * @return array
     */
    public static function getMovieInfo($movieId)
    {
        if (isset(self::$movieInfo[$movieId])) {
            return ['code' => 0, 'msg' => '', 'data' => self::$movieInfo[$movieId]];
        }
        $commonCgiUrl = Yii::app()->params['movie']['getMovieName'];
        $url = $commonCgiUrl . $movieId . ".json";
        $data = Https::curlGetPost($url);
        $res = json_decode($data, true);
        if ($res['ret'] != 0 || empty($res['data']) || !isset($res['data']['name'])) {
            return ['code' => 1, 'msg' => '未找到影片信息', 'data' => []];
        }
        $movieInfo = ['movieId' => $movieId, 'movieName' => $res['data']['name']];
        self::$movieInfo[$movieId] = $movieInfo;
        return ['code' => 0, 'msg' => '', 'data' => $movieInfo];
    }
}