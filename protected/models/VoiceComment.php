<?php

/**
 * This is the model class for table "t_voice_comment".
 *
 * The followings are the available columns in table 't_voice_comment':
 * @property integer $id
 * @property integer $movie_id
 * @property string $movie_name
 * @property string $actor_id
 * @property string $nick_name
 * @property integer $channel_id
 * @property string $from
 * @property string $voice_url
 * @property string $times
 * @property integer $order
 * @property integer $status
 * @property integer $created
 */
class VoiceComment extends CActiveRecord
{
    use AlertMsg;
    public $voice = '';
    public $whenLong = '';
    public $fileName = '';
    private $app_url = 'http://commoncgi.pre.wepiao.com/';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_voice_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('movie_id,actor_id,tips', 'required', 'on' => 'create,update'),
            array('movie_id, channel_id, order, created', 'numerical', 'integerOnly' => true),
            ['movie_id,actor_id', 'numerical', 'integerOnly' => true, 'min' => 1],
            ['times', 'numerical', 'integerOnly' => true, 'max' => 60],
            array('movie_name', 'length', 'max' => 255),
            array('actor_id', 'length', 'max' => 64),
            array('nick_name', 'length', 'max' => 32),
            array('from', 'length', 'max' => 20),
            array('tips', 'length', 'max' => 150),
            array('voice_url', 'length', 'max' => 100),
            array('times,base_clicks,base_favor', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, movie_id, movie_name, actor_id, nick_name, channel_id, from, voice_url, times, order, created,status', 'safe', 'on' => 'search'),
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
            'movie_id' => '影片id',
            'movie_name' => '影片名称',
            'actor_id' => '明星id',
            'nick_name' => '明星名称',
            'channel_id' => '渠道id',
            'from' => 'From',
            'voice_url' => 'Voice Url',
            'times' => '音频时长',
            'order' => 'Order',
            'created' => '添加时间',
            'voice' => '音频文件',
            'whenLong' => '音频时长',
            'clicks' => '播放次数',
            'tips' => '文字',
            'base_clicks' => '初始播放次数',
            'base_favor' => '初始点赞人数',
            'order' => '排序',
            'status' => '是否发布',
            'favor_count'=>'真实点赞数'
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
        $criteria->compare('movie_id', $this->movie_id, true);
        $criteria->compare('movie_name', $this->movie_name, true);
        $criteria->compare('actor_id', $this->actor_id, true);
        $criteria->compare('nick_name', $this->nick_name, true);
        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('from', $this->from, true);
        $criteria->compare('voice_url', $this->voice_url, true);
        $criteria->compare('times', $this->times, true);
        $criteria->compare('order', $this->order);
        $criteria->compare('status', $this->status);
        $criteria->compare('created', $this->created);
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
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_app;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return VoiceComment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 接口 影人影片验证
     * @param $movie_id
     * @param $actor_id
     * @return array|bool
     *
     */

    public function actorCheck($movie_id, $actor_id)
    {
        if ($movie_id < 0 || $actor_id < 0) {
            return false;
        }
        //接口获取影片信息
        $url = $this->app_url . 'msdb/getMovieInfo.php';
        $arrData = ['movieId' => $movie_id, 'channelId' => 8, 'actorInfo' => 1];
        $res = Https::getPost($arrData, $url);
        $res = json_decode($res, true);
        if ($res['ret'] != 0 || $res['sub'] != 0) {
            return false;
        }
        $actorInfo = array_filter($res['data']['actorInfo'], function ($val) use ($actor_id) {
            if ($val['actor_id'] == $actor_id) {
                return $val;
            }
        });
        if (empty($actorInfo)) {
            return false;
        }
        return ['moveId' => $res['data']['MovieNo'],
            'moveName' => $res['data']['MovieNameChs'] ? $res['data']['MovieNameChs'] : $res['data']['MovieNameEng'],
            'actorId' => $actorInfo[0]['actor_id'],
            'actorName' => $actorInfo[0]['actor_name_chs'] ? $actorInfo[0]['actor_name_chs'] : $actorInfo[0]['actor_name_chs']];
    }

    /**
     * 添加(修改)语音评论缓存
     * @param $movie_id
     * @param $actor_id
     * @param $voice_url
     * @param $times
     * @param $tips
     * @param $clicks
     * @param $favors
     * @param $order
     * @param $status
     * @param null $id
     * @return bool
     */

    public function apiAddVoiceComments($movie_id, $actor_id, $voice_url, $times, $clicks, $favors, $order, $status, $tips, $id = null)
    {
        //获取影片明星信息
        $movieInfo = $this->getMovieInfo($movie_id);
        if (!$movieInfo) {
            return $this->alert_info(1, '未找到对应的影片');
        }
        $movie_actor_name = [];
        $actorInfo = array_filter($movieInfo['actorInfo'], function ($val) use ($actor_id, &$movie_actor_name) {
            if ($val['actor_id'] == $actor_id) {
                $movie_actor_name[] = $val['movie_actor_name'];
                return $val;
            }
        });
        if (empty($actorInfo)) {
            return $this->alert_info(1, '该影人不是该电影主创');
        }
        $movie_actor_name = implode(' | ', $movie_actor_name);
        $ActorInfo = $this->getActorInfo($actor_id);
        if (!$ActorInfo) {
            return $this->alert_info(1, '未找到对应的影人');
        }
        $arrData = ['uid' => 0,
            'movieId' => $movieInfo['moveId'],
            'movieName' => $movieInfo['moveName'],
            'actorId' => $ActorInfo['id'],
            'nickName' => $ActorInfo['actorNameChs'] ? $ActorInfo['actorNameChs'] : $ActorInfo['actorNameEng'],
            'photo' => $ActorInfo['headImage'],
            'tag' => $movie_actor_name,
            'channelId' => 1,
            'from' => 0,
            'tips' => $tips,
            'times' => $times,
            'order' => $order,
            'status' => $status
        ];
        if ($voice_url) {
            $arrData['voiceUrl'] = $voice_url;
        }
        if ($clicks > 0) {
            $arrData['baseClicks'] = $clicks;
        }
        if ($favors > 0) {
            $arrData['base_favor'] = $favors;
        }
        if (!$id) {
            $url = Yii::app()->params['comment']['start_voice_comment_add'];
            $res = Https::getPost($arrData, $url);
        } else {
            $arrData['commentId'] = $id;
            $url = Yii::app()->params['comment']['start_voice_comment_update'];
            $res = Https::getPost($arrData, $url);
        }
        $res = json_decode($res, true);
        if ($res['ret'] != 0 || $res['sub'] != 0) {
            return $this->alert_info(1, '创建失败');
        }
        return $this->alert_info(0, '创建成功');
    }

    /**
     * 删除信息
     * @param $commentId
     * @return bool
     */
    public function apiDelVoiceComments($commentId)
    {
        if ($commentId < 0) {
            return false;
        }
        $url = Yii::app()->params['comment']['start_voice_comment_del'];
        $res = Https::getPost(['commentId' => $commentId, 'channelId' => 1], $url);
        return json_decode($res, true);
    }

    /**
     * 获取影片信息
     * @param $movie_id
     * @return bool
     */
    public function getMovieInfo($movie_id)
    {
        $url = Yii::app()->params['movie']['getMovieInfo'];
        $arrData = ['movieId' => $movie_id, 'channelId' => 8, 'actorInfo' => 1];
        $res = Https::getPost($arrData, $url);
        $res = json_decode($res, true);
        if ($res['ret'] != 0 || empty($res['data'])) {
            return false;
        }
        return [
            'actorInfo' => $res['data']['actorInfo'],
            'moveId' => $res['data']['MovieNo'],
            'moveName' => $res['data']['MovieNameChs'] ? $res['data']['MovieNameChs'] : $res['data']['MovieNameEng']
        ];
    }

    /**
     * 获取 明星信息
     * @param $actor_id
     * @return array|mixed|null
     */
    public function getActorInfo($actor_id)
    {
        $url = Yii::app()->params['actor']['getActorInfo'];
        $arrData = ['actorId' => $actor_id, 'channelId' => 8, 'movieInfo' => 0];
        $arrData = Https::getPost($arrData, $url);
        $arrData = json_decode($arrData, true);
        return !empty($arrData['data']) && empty($arrData['data']['actor_id']) ? $arrData['data'] : false;
    }

    /**
     * 全量刷新数据
     */
    public function saveAllData()
    {
        $arrData = VoiceComment::model()->findAll();
        if(!empty($arrData)){
            foreach($arrData as $val){
                $this->apiAddVoiceComments($val->movie_id, $val->actor_id, $val->voice_url, $val->times, $val->base_clicks, $val->base_favor, $val->order, $val->status, $val->tips, $val->id);
            }
        }
        echo count($arrData);exit;
    }
}
