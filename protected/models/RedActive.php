<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 10:20
 */
Yii::import('ext.RedisManager', true);
class RedActive extends CActiveRecord
{
    const RED_KEY = "red_active_channel_";
    const RED_KEY_TIME = 86400;
    private $redis;
    /**
     * @return string the associated database table sName
     */
    public function tableName()
    {
        return '{{red_active}}';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('a_id', 'unique'),
            array('a_name,local,channel,a_start_release,a_end_release,a_start_time,a_end_time', 'required'),
            array('a_id,a_name,a_start_release,a_end_release,a_start_time,a_end_time,a_status,a_create_time，a_update_time', 'safe', 'on' => 'search')
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
            'channel' => array(self::HAS_MANY, 'RedActiveChannel', 'a_id'),
            'local' => array(self::HAS_MANY, 'RedActiveLocal', 'a_id'),
        );
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'a_id' => '活动ID',
            'a_name' => '活动名称',
            'local' => '红点配置位置',
            'channel' =>'定向平台',
            'a_start_release' => '开始定向版本',
            'a_end_release' => '结束定向版本',
            'a_start_time' => '投放开始时间',
            'a_end_time' => '投放结束时间',
            'a_status' => '状态',
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('a_name', $this->a_name, true);
        $criteria->compare('local', $this->local);
        $criteria->compare('a_start_release', $this->a_start_release);
        $criteria->compare('a_end_release', $this->a_end_release);
        $criteria->compare('a_start_time', $this->a_start_time);
        $criteria->compare('a_end_time', $this->a_end_time);
        $criteria->compare('a_status', $this->a_status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'a_id DESC',
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->a_start_time && !is_numeric($this->a_start_time))
            $this->a_start_time = strtotime($this->a_start_time);
        if ($this->a_end_time && !is_numeric($this->a_end_time))
            $this->a_end_time = strtotime($this->a_end_time);
        $this->a_update_time = time();
        if ($this->isNewRecord) {
            $this->a_create_time = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if ($this->a_start_time)
            $this->a_start_time = $this->int2date($this->a_start_time);
        if ($this->a_end_time)
            $this->a_end_time = $this->int2date($this->a_end_time);
      //  $this->channel=Yii::app()->db->createCommand()->select("c_channelId")->from("t_red_active_channel")->where('a_id=:a_id', array(':a_id' => $this->a_id))->queryAll();
      //  $this->local=Yii::app()->db->createCommand()->select("l_local_id")->from("t_red_active_local")->where('a_id=:a_id', array(':a_id' => $this->a_id))->queryAll();
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }
    /**
     *    自动删除关联
     */
    public function afterDelete() {
        parent::afterDelete();
        RedActiveLocal::model()->deleteAllByAttributes(array(
            'a_id'=>$this->a_id
        ));
        RedActiveChannel::model()->deleteAllByAttributes(array(
            'a_id'=>$this->a_id
        ));
    }

    /**
     * @tutorial 位置编号
     * @param string $id
     * @return Ambigous <multitype:string , string>
     */
    public function getLocalkey($id = '')
    {
        $array = ['0' => '全部', '1' => '电影TAB', '2' => '演出TAB', '3' => '发现TAB', '4' => '我的TAB', '5' => '首页顶部电影TAB', '6' => '首页顶部影院TAB'];
        return !empty($array[$id]) ? $array[$id] : $array;
    }

    /**渠道编号
     * @param string $id
     * @return array
     */
    public function getChannelkey($id = '')
    {
        $array = ['0'=>'全部','3'=>'微信电影票','8' => 'IOS', '9' => 'Android'];
        return !empty($array[$id]) ? $array[$id] : $array;
    }

    /**
     * 通过a_id查询local字段显示在index首页
     * @param $a_id
     * @return bool|string
     */
    public function findLocal($a_id)
    {
        $arrLocal=[];
        $list = Yii::app()->db->createCommand()->select("l_local_id")->from("t_red_active_local")->where('a_id=:a_id', array(':a_id' => $a_id))->queryAll();
        foreach ($list as $key => $local) {
            foreach ($local as $key => $localId) {
                $arrLocal[] = $this->getLocalkey($localId);
            }
        }
        $list = implode(",", $arrLocal);
        return empty($list) ? false : $list;
    }
    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['pee']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    public function saveCache()
    {
        $this->setRedis();
        $time = time();
        $sql = "select t_red_active_channel.c_channelId,t_red_active_local.l_local_id,t_red_active.a_id, t_red_active.a_name,t_red_active.a_start_release,t_red_active.a_end_release,t_red_active.a_start_time,t_red_active.a_end_time from t_red_active_channel
INNER JOIN t_red_active ON t_red_active.a_id = t_red_active_channel.a_id
LEFT JOIN t_red_active_local ON t_red_active_channel.a_id = t_red_active_local.a_id WHERE t_red_active.a_status ='1' and t_red_active.a_end_time >= '$time' AND t_red_active.a_start_time <= '$time'  order by t_red_active.a_update_time desc";
        $info = $this->getDbConnection()->createCommand($sql)->queryAll();
        $arrData = [];
        /*foreach($this->getChannelkey() as $channelKey => $channel){
            foreach($this->getLocalkey() as $localKey => $local){
                if(!empty($channelKey) && !empty($localKey)){
                    $arrData[$channelKey][$localKey] = [];
                }
            }
        }
        */
        foreach($info as $val){
            if(empty($val['a_id']))continue;
            $arrData[$val['c_channelId']][] =['local_type'=>$val['l_local_id'],'id'=>$val['a_id'],'name'=>$val['a_name'],'start_release'=>$val['a_start_release'],'end_release'=>$val['a_end_release'],'start_time'=>$val['a_start_time'],'end_time'=>$val['a_end_time']];
        }
        //写入缓存
        foreach($arrData as $key=> $val){
            $this->redis->set(self::RED_KEY.$key,json_encode($val));
            $this->redis->expire(self::RED_KEY.$key,self::RED_KEY_TIME);
        }
        return $arrData;
    }

}