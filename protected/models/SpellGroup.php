<?php
Yii::import('ext.RedisManager', true);

/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/8/11
 * Time: 10:25
 */
class SpellGroup extends CActiveRecord
{

    const SPELL_GROUP_KEY = "active_data_";
    const SPELL_GROUP_KEY_TIME = 84600;
    const SPELL_GROUP_ACTIVE_TEAM='active_team_';//active_team:{#activeId}';//set结构，活动下成功和正在进行的拼团，失败的删除，用于统计余量
    const SPELL_GROUP_USER_ACTIVE='user_active_';//user_active:{#openId}';//set 用户已参与的活动set，删除失败的
    const SPELL_GROUP_TEAM_INFO='team_info_';//team_info:{#teamId};//hash结构  拼团活动的团信息 包括参与用户 成团时限 及团的活动id

    private $redis;
    public $team_status = '';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_spell_group_active';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
             array('movie_id,movie_name,title,start_time,end_time,group_count,people_num,keep_hour,bonus_type,bonus_id,bonus_value,status', 'required'),
            array('movie_id,movie_name,title,start_time,end_time,group_count,people_num,keep_hour,static_channel,bonus_id,bonus_value,status,team_status,created_time,update_time', 'safe'),
            array('active_id,movie_id,movie_name,movie_post,title,start_time,end_time,group_count,people_num,keep_hour,bonus_id,bonus_value,status,team_status,created_time,update_time', 'safe', 'on' => 'search')
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'active_id' => '活动ID',
            'movie_id' => '拼团电影id',
            'movie_name' => '拼团电影',
            'movie_post' => '电影海报',
            'title' => '拼团标题',
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'group_count' => '成团数量',
            'people_num' => '拼团人数',
            'keep_hour' => '拼团有效时长',
            'bonus_type' => '红包渠道',
            'static_channel'=>'统计渠道',
            'bonus_id' => '红包id',
            'bonus_value' => '红包金额',
            'status' => '状态',
            'team_status' => '  进行中 / 已成团 / 总团数',
            'created_time' => '创建时间',
            'update_time' => '更新时间',
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('active_id', $this->active_id);
        $criteria->compare('movie_id', $this->movie_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('group_count', $this->group_count);
        $criteria->compare('people_num', $this->people_num);
        $criteria->compare('keep_hour', $this->keep_hour);
        $criteria->compare('bonus_type', $this->bonus_type);
        $criteria->compare('static_channel', $this->static_channel);
        $criteria->compare('bonus_id', $this->bonus_id);
        $criteria->compare('bonus_value', $this->bonus_value);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_time', $this->created_time);
        $criteria->compare('update_time', $this->update_time);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'active_id DESC',
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDbConnection()
    {
        return Yii::app()->db_active;
    }

    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['pintuan']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    public function beforeSave()
    {
        if ($this->start_time && !is_numeric($this->start_time)) {
            $this->start_time = strtotime($this->start_time);
        }
        if ($this->end_time && !is_numeric($this->end_time)) {
            $this->end_time = strtotime($this->end_time);
        }
        $this->update_time = time();
        if ($this->isNewRecord) {
            $this->created_time = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if ($this->start_time) {
            $this->start_time = $this->int2date($this->start_time);
        }
        if ($this->end_time) {
            $this->end_time = $this->int2date($this->end_time);
        }
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }

    /*获取进行中/已成团/总团数
     * @param $id
     * @return string
     */
    public function getTeamStatusCount($id)
    {
        $sql = 'select undone_count,success_count,group_count from t_spell_group_active where active_id=' . $id;
        $list = $this->getDbConnection()->createCommand($sql)->queryAll();
        $teamStatus = implode('/', $list[0]);
        return $teamStatus;
    }

    /**
     * 生成redis数据
     */
    public function redisData()
    {
        $sql = "select active_id,movie_id,movie_name,title,group_count,bonus_value,bonus_id,status from t_spell_group_active order by update_time desc";
        $info = Yii::app()->db_active->createCommand($sql)->queryAll();
        $arrData=[];
        foreach($info as $key=>$value){
            $arrData[$value['active_id']]=['active_id'=>$value['active_id'],'movie_id'=>$value['movie_id'],'movie_name'=>$value['movie_name'],'title'=>$value['title'],'group_count'=>$value['group_count'],'bonus_value'=>$value['bonus_value'],'bonus_id'=>$value['bonus_id'],'status'=>$value['status']];
        }
        return $arrData;
    }

    /**
     * 执行sql，进行落地
     * @param $sql
     * @return mixed
     */
    public static function saveSql($sql, $behavior)
    {
        if ($behavior == 'select') {
            return Yii::app()->db_active->createCommand($sql)->queryAll();
        }
        if ($behavior == 'update') {
            return Yii::app()->db_active->createCommand($sql)->execute();
        }
    }
}