<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 15:08
 */
Yii::import("ext.redisTools.*");
class StarActiveSche extends CActiveRecord
{
    private $redis;

    /**
     * @return string the associated database table sName
     */
    public function tableName()
    {
        return '{{star_active_sche}}';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('a_id,s_cinema_id,s_cinema_name,s_id,s_movie_id,s_movie_name,s_sche_id,s_start_time,s_end_time,s_sche_type,s_sche_room,s_created', 'safe'),
            array('a_id,s_cinema_id,s_cinema_name,s_id,s_movie_id,s_movie_name,s_sche_id,s_start_time,s_end_time,s_sche_type,s_sche_room,s_created', 'safe', 'on' => 'search')
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations(){
        return array(

        );
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels(){
        return array(
            'a_id'=>'活动ID',
            's_id'=>'ID',
            's_cinema_id'=>'影院ID',
            's_cinema_name'=>'影院名称',
            's_movie_id'=>'影片ID',
            's_movie_name'=>'影片名称',
            's_sche_id'=>'排期ID',
            's_start_time'=>'排期场次开始时间',
            's_end_time'=>'排期场次结束时间',
            's_sche_type'=>'排期类型',
            's_sche_room'=>'排期影厅',
            's_created'=>'创建时间'
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('a_id',$this->a_id);
        $criteria->compare('s_id',$this->s_id);
        $criteria->compare('s_cinema_id',$this->s_cinema_id);
        $criteria->compare('s_cinema_name',$this->s_cinema_name,true);
        $criteria->compare('s_movie_id',$this->s_movie_id);
        $criteria->compare('s_movie_name',$this->s_movie_name,true);
        $criteria->compare('s_sche_id',$this->s_sche_id);
        $criteria->compare('s_start_time',$this->s_start_time);
        $criteria->compare('s_end_time',$this->s_end_time);
        $criteria->compare('s_sche_type',$this->s_sche_type,true);
        $criteria->compare('s_sche_room',$this->s_sche_room,true);
        $criteria->compare('s_created',$this->s_created);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100000,
            ),
            'sort' => array(
                'defaultOrder' => 's_id DESC',
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $classsName active record class sName.
     * @return ActivePage the static model class
     */
    public static function model($classsName = __CLASS__)
    {
        return parent::model($classsName);
    }

    // 自动更新时间
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->s_created = time();
        }
        return true;
    }

    /**
     * @param $activeId 活动ID
     * @return string 返回点击查看的路径
     */
    public function getActiveDelDetail($activeId){
        $url = '/starActive/delDetail/'.$activeId.'?type=delete';
        return $url;
    }

    public function init()
    {
        $this->setRedis();
    }
    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_tool['star_meet'];
        $this->redis =  redisTool::getInstance($config,'common');
    }

    //查询明星见面会 影院 排期信息
    public function setStarActiveRedis()
    {
        if(!$this->redis)
            $this->setRedis();
        $now = time();
        //获取有效排期
        $sql = "select t_star_active_sche.s_cinema_id,t_star_active_sche.s_sche_id,t_star_active_sche.s_end_time,t_star_active.* from t_star_active_sche INNER join t_star_active on t_star_active_sche.`a_id` =t_star_active.a_id where t_star_active_sche.s_end_time >= '". $now."';";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
//      $data = Yii::app()->db_ceshi_xianshang->createCommand($sql)->queryAll();

        $cinemaData = [];
        foreach($data as $key=>$val) {
            $activeData = [
                'id' => $val['a_id'],
                'name' => $val['a_name'],
                'date' => $val['a_date'],
                'tag' => $val['a_tag'],
                'title' => $val['a_title'],
                'detail' => $val['a_detail'],
            ];
            $cinemaData[$val['s_cinema_id']][$val['s_sche_id']] = $activeData;
            //同一影院多个排期、获取最晚结束时间
            if (isset($cinemaData[$val['s_cinema_id']]['endtime'])) {
                $cinemaData[$val['s_cinema_id']]['endtime'] = $cinemaData[$val['s_cinema_id']]['endtime'] > $val['s_end_time'] ? $cinemaData[$val['s_cinema_id']]['endtime'] : $val['s_end_time'];
            }
            else{
                $cinemaData[$val['s_cinema_id']]['endtime'] = $val['s_end_time'];
            }
        }
        //循环写入redis
        foreach($cinemaData as $k=>$v) {
            $redisKey = $k;
            $endTime = $v['endtime'];
            $expireTime = $endTime + 86400 - time();
            unset($v['endtime']);
            $this->redis->WYset($redisKey,json_encode($v));
            $this->redis->WYexpire($redisKey,$expireTime);
        }
    }

    /**
     * redis连接测试
     * @return mixed
     */
    public function setRedisCeshi(){
        if(!$this->redis)
            $this->setRedis();
        $q = $this->redis->WYset('ceshi','testdata');
        $p = $this->redis->WYexpire('ceshi','30');
//        $q = $this->redis->WYget('1008');
        return $q;
    }

}