<?php
Yii::import('ext.RedisManager', true);

class CinemaNotification extends CActiveRecord
{
    const SHOW_CINEMA = 1;
    const SHOW_SEAT = 2;
    const SHOW_HOME = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{cinema_notification}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sName, sContent, iShow', 'required'),
            array('iShow, iStatus, iCreated,allChannel, iUpdated,movieId', 'numerical', 'integerOnly' => true),
            array('iStartAt, iEndAt', 'date', 'format' => 'yyyy-mm-dd HH:mm:ss'),
            array('sName', 'length', 'max' => 100),
            array('sContent', 'length', 'max' => 60),
            array('appver', 'length', 'max' => 10),
            array('iNotificationID,allChannel, iShow, sName,movieId, sContent, sInfo,iStartAt, iEndAt, iStatus, iCreated, iUpdated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('iNotificationID, iShow, sName, sContent, sInfo,iStartAt, iEndAt, iStatus, iCreated, iUpdated', 'safe', 'on' => 'search'),
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
            'cinemas' => array(self::HAS_MANY, 'CinemaNotificationCinema', 'iNotificationID'),
            'channel' => array(self::HAS_MANY, 'CinemaNotificationChannel', 'iNotificationID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'iNotificationID' => 'ID',
            'sName' => '公告位名称',
            'sContent' => '公告位文案',
            'sInfo' => '公告详情',
            'iShow' => '定向页面',
            'iStartAt' => '开始时间',
            'iEndAt' => '结束时间',
            'iStatus' => '状态',
            'iCreated' => '创建时间',
            'iUpdated' => '修改时间',
            'appver' => '客户端版本',
            'movieId' => '影片ID',
            'allChannel'=>'是否全部影院'
        );
    }

    /**
     * @tutorial 展示页面
     * @param string $id
     * @return Ambigous <string>|string|multitype:string
     */
    public function getShow($id = '')
    {
        $array = [
            '22' => '电影—影片页',
            '3' => '电影-首页',
            '1' => '电影-影院详情',
            '2' => '电影-选座页',
            '4' => '电影-我的订单页',
            '5' => '电影-订单详情页',
            '6' => '电影-出票结果页',
            '7' => '电影-待支付页面',
            '8' => '影院列表页',
            '9' => '我的',
            '10' => '电影订单列表页',
            '11' => '演出订单列表页',
            '12' => '红包列表页',
            '13' => '选座劵列表页',
            '14' => '优惠码通兑页',
            '15' => '演出-列表页',
            '16' => '演出-选座页',
            '17' => '演出-待支付页面',
            '18' => '演出-订单详细页',
            '19' => '演出-首页',
            '20' => 'APP周边商城-首页',
            '21' => 'APP周边商城-商品页',
        ];

        if (!empty($id)) {
            if (!empty($array[$id])) return $array[$id];
            else return '';
        } else {
            return $array;
        }
    }

    /**
     * @tutorial 渠道编号
     * @param string $id
     * @return Ambigous <multitype:string , string>
     */
    public function getAppkey($id = '')
    {
        $array = ['0' => '全部', '3' => '微信电影票', '8' => 'IOS', '9' => '安卓', '28' => '手Q', '10' => 'PC'];
        return !empty($array[$id]) ? $array[$id] : $array;
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

        $criteria->compare('iNotificationID', $this->iNotificationID);
        $criteria->compare('sName', $this->sName, true);
        $criteria->compare('sContent', $this->sContent, true);
        $criteria->compare('iShow', $this->iShow);
        $criteria->compare('iStartAt', strtotime($this->iStartAt));
        $criteria->compare('iEndAt', strtotime($this->iEndAt));
        $criteria->compare('iStatus', $this->iStatus);
        $criteria->compare('iCreated', strtotime($this->iCreated));
        $criteria->compare('iUpdated', strtotime($this->iUpdated));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'iNotificationID DESC',
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CinemaNotification the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // 自动更新时间
    public function beforeSave()
    {
        if ($this->iStartAt)
            $this->iStartAt = strtotime($this->iStartAt);
        if ($this->iEndAt)
            $this->iEndAt = strtotime($this->iEndAt);
        $this->iUpdated = time();
        if ($this->isNewRecord)
            $this->iCreated = time();
        return true;
    }

    public function afterFind()
    {
        if ($this->iStartAt)
            $this->iStartAt = date('Y-m-d H:i:s', $this->iStartAt);

        if ($this->iEndAt)
            $this->iEndAt = date('Y-m-d H:i:s', $this->iEndAt);
    }

    /**
     * 保存和新建之后同步memcache
     */
    public function afterSave()
    {
        // $this->saveCache();
    }

    /**
     * 同步数据到memcache
     */
    public function saveCache()
    {
        // $redis = RedisManager::getInstance(Yii::app()->params->redis_data['cache']['write']);//redis配置
        // 同步到memcache
        $value = array();
        //$key = 'cinema_notification';
        $key = Yii::app()->params['cinema_notification_memcache_Key'];
        $criteria = new CDbCriteria;
        $criteria->condition = 'iStatus=1 AND (iEndAt>:iEndAt OR iEndAt IS NULL) ';
        $criteria->params = array(':iEndAt' => time());
        $criteria->order = "iNotificationID ASC";
        $notifications = $this->model()->findAll($criteria);
        $keyArray = ['1' => 'detail', '2' => 'seat', '3' => 'homepage', '4' => 'myorder', '5' => 'orderdetail', '6' => 'ticket'];
        foreach ($notifications as $k => &$n) {
            $selectedCinemas = [];
            if (is_array($n->cinemas))
                foreach ($n->cinemas as $result) {
                    $selectedCinemas[] = $result['iCinemaID'];
                }
            $selectedChannel = [];
            if (is_array($n->channel)) {
                foreach ($n->channel as $results) {
                    $selectedChannel[] = $results['iChannelID'];
                }
                if (count($selectedChannel) == count($n->getAppkey()) - 1)
                    $selectedChannel[] = '0';
            }
            if (in_array('3', $selectedChannel))
                if (in_array($n->iShow, ['3', '4', '5', '6'])) {
                    $value[$keyArray[$n->iShow]] = array(
                        'content' => $n->sContent,
                        'start_at' => $n->iStartAt,
                        'end_at' => $n->iEndAt,
                    );
                } elseif (in_array($n->iShow, ['1', '2'])) {
                    foreach ($n->cinemas as $cinema) {
                        $value[$keyArray[$n->iShow]][$cinema->iCinemaID] = array(
                            'content' => $n->sContent,
                            'start_at' => $n->iStartAt,
                            'end_at' => $n->iEndAt,
                        );
                    }
                }
            $n->iStartAt = strtotime($n->iStartAt);
            $n->iEndAt = strtotime($n->iEndAt);

            $n = $n->attributes;
            $n['cinemas'] = $selectedCinemas;
            $n['channel'] = $selectedChannel;
        }
        // $redis->set('t_cinema_notification', json_encode($notifications));
        //  $redis->expire('t_cinema_notification', 31*24*3600);
        //  yii::app()->cache->set($key, $value, 0);
    }

    /**
     * 自动删除关联
     */
    public function afterDelete()
    {
        parent::afterDelete();
        // 自动删除影院关联
        CinemaNotificationCinema::model()->deleteAllByAttributes(array(
            'iNotificationID' => $this->iNotificationID
        ));
        //删除渠道
        CinemaNotificationChannel::model()->deleteAllByAttributes(array(
            'iNotificationID' => $this->iNotificationID
        ));
        // 重新生成缓存
        //  $this->saveCache();
    }

    /**
     * 复制公告
     * @return bool
     */
    public function duplicate()
    {
        $new = clone $this;
        $new->iStatus = 0;
        unset($new->iNotificationID);
        $new->isNewRecord = true;
        if ($new->save()) {
            // 复制关联影院
            $cinemas = CinemaNotificationCinema::model()->findAllByAttributes(array(
                'iNotificationID' => $this->iNotificationID
            ));
            foreach ($cinemas as $cinema) {
                $cnc = new CinemaNotificationCinema();
                $cnc->iNotificationID = $new->iNotificationID;
                $cnc->iCinemaID = $cinema->iCinemaID;
                $cnc->save();
            }
            // 复制渠道
            $channel = CinemaNotificationChannel::model()->findAllByAttributes(array(
                'iNotificationID' => $this->iNotificationID
            ));
            foreach ($channel as $channelValue) {
                $cnc = new CinemaNotificationChannel();
                $cnc->iNotificationID = $new->iNotificationID;
                $cnc->iChannelID = $channelValue->iChannelID;
                $cnc->save();
            }
            return $new;
        } else {
            return false;
        }
    }

    /**
     *
     */
    public function isTXKwyWord()
    {
        $content = empty($this->sName) ? '' : $this->sName . ',';
        $content .= empty($this->sContent) ? '' : $this->sContent . '';
        $content .= empty($this->sInfo) ? '' : $this->sInfo;
        $res = Https::getUrl(['content' => $content], Yii::app()->params['tx_key_word_path']);
        Yii::log("tx_key_word_path" . $res, "log", 'other');
        $res = json_decode($res, true);
        return empty($res['level']) ? false : true;

    }
}