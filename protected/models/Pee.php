<?php
Yii::import('ext.RedisManager', true);

class Pee extends CActiveRecord
{
    const MOVIE_CACHE_KEY = "movie_info";
    const PEE_CACHE_KEY = "pid_user_";
    const MOVIE_PEE_CACHE_KEY = "movie_pee_";
    private $redis;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_pee';
    }

    public function init()
    {
        $this->setRedis();
    }

    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['pee']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    public function getDbConnection()
    {
        return Yii::app()->db_pee;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id,mobile_no,open_id', 'required'),
            array('id', 'unique'),
            array('id,mobile_no,nick_name, open_id, head_img ,recommend_pee,  movie_name, is_pee, pee_num, eggs, created,status,recommend_words, updated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,recommend_pee,  movie_name, is_pee, pee_num, eggs,status,recommend_words, created, updated', 'safe', 'on' => 'search'),
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
            'id' => '影片ID',
            'movie_name' => '影片名称',
            'mobile_no' => '手机号',
            'open_id' => '尿评人open id',
            'nick_name' => '尿评人昵称',
            'head_img' => '尿评人头像',
            'is_pee' => '是否有尿点',
            'pee_num' => '尿点数',
            'eggs' => '彩蛋',
            'recommend_words' => '无尿点影片推荐语',
            'created' => '创建时间',
            'updated' => '更新时间',
            'status' => '状态',
            'recommend_pee' => '推荐尿点',
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
        $criteria->compare('movie_name', $this->movie_name, true);
        $criteria->compare('open_id', $this->open_id);
        $criteria->compare('nick_name', $this->nick_name);
        $criteria->compare('is_pee', $this->is_pee);
        $criteria->compare('pee_num', $this->pee_num);
        $criteria->compare('eggs', $this->eggs, true);
        $criteria->compare('recommend_words', $this->recommend_words, true);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
            'sort' => array(
                'defaultOrder' => 'created DESC',
            ),
        ));
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Movie the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 更新后,数据写入redis
     */
    public function saveCache($id = '', $type = '')
    {
        $res = false;
        if (empty($id)) return $res;
        $arrData = self::model()->findByPk($id)->attributes;
        if (!empty($arrData) && $arrData['status'] == 1 && $type !== 'del') {
            $res = $this->savePeeCache($id);
        } else {
            $res = $this->delPeeCache($id);
        }
        //更新指定影片-尿点集合
        $res = $this->saveMoviePee($id);
        return $res;
    }

    private function saveMoviePee($id)
    {
        if (empty($id)) return false;
        $peeInfo = PeeInfo::model()->findAll("t_id=:t_id", ['t_id' => $id]);
        foreach ($peeInfo as &$val) {
            $val = $val->attributes;
            $val = $val['p_id'];
        }
        //先删再加，但不是完全删除，是先删除不存在的
        if (empty($peeInfo)) {
            $this->redis->del(self::MOVIE_PEE_CACHE_KEY . $id);
        } else {
            //排除
            $cacheInfo = $this->redis->zRange(self::MOVIE_PEE_CACHE_KEY . $id, 0, -1);
            if (!empty($cacheInfo))
                foreach ($cacheInfo as $cache) {
                    if (!in_array($cache, $peeInfo)) {
                        $this->redis->zRem(self::MOVIE_PEE_CACHE_KEY . $id, $cache);
                    }
                }
            foreach ($peeInfo as $pee) {
                $this->redis->zAdd(self::MOVIE_PEE_CACHE_KEY . $id, time(), $pee);
            }
        }
        return true;

    }


    /**
     * 更新尿点，同时获取时长
     * @param $id
     * @return mixed
     */
    private function savePeeCache($id)
    {
        $arrData = self::model()->findByPk($id)->attributes;
        $movieLongs = Movie::model()->getMovieInfo($id);
        if (!empty($movieLongs['Duration'])) {
            $arrData['longs'] = intval($movieLongs['Duration']);
        } else {
            $arrData['longs'] = 0;
        }
        if (!empty($arrData['is_pee'])) {
            $peeInfo = PeeInfo::model()->findAll("t_id=:t_id", ['t_id' => $id]);
            foreach ($peeInfo as $key => &$val) {
                $val = $val->attributes;
                $val['recommend_pee'] = 0;
                if (!empty($arrData['recommend_pee']) && $arrData['recommend_pee'] == $key + 1) {
                    $val['recommend_pee'] = 1;
                }
            }
            $arrData['peeInfo'] = $peeInfo;
        }
        return $this->redis->hashMset(self::MOVIE_CACHE_KEY, [$id => json_encode($arrData)]);
    }

    /**
     * @param $id
     */
    private function delPeeCache($id)
    {
        //先读缓存
        $arrData = $this->redis->hashGet(self::MOVIE_CACHE_KEY, $id);
        $arrData = json_decode($arrData, true);
        if (!empty($arrData)) {
            if (!empty($arrData['peeInfo'])) {
                foreach ($arrData['peeInfo'] as $peeId) {
                    $this->redis->del(self::PEE_CACHE_KEY . $peeId['p_id']);
                }
            }
            $this->redis->hashDel(self::MOVIE_CACHE_KEY, $arrData['id']);
        }
        return true;
    }

}
