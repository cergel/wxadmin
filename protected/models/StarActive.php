<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 10:34
 */
Yii::import('ext.RedisManager', true);
class StarActive extends CActiveRecord{
    const STAR_ACTIVE_KEY = "star_active_key";
    const STAR_ACTIVE_SCHE= "star_active_sche";
    const STAR_ACTIVE_DATA= "star_active_data";
    private $redis;
    /**
     * @return string the associated database table sName
     */
    public function tableName(){
        return '{{star_active}}';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules(){
        return array(
            array('a_name,a_date,a_tag,a_title,a_create_id,a_create_name,a_detail','required'),
            array('a_created,a_updated','numerical', 'integerOnly'=>true),
            array('a_tag','length','max'=>8),
            array('a_title','length','max'=>22),
            array('a_detail','length','max'=>100),
            array('a_id,a_name,a_date,a_tag,a_title,a_create_id,a_create_name,a_created,a_updated','safe','on'=>'search')
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations(){
        return array(
            'sche'=>array(self::HAS_MANY,'StarActiveSche','a_id'),
        );
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels(){
        return array(
            'a_id'=>'活动ID',
            'a_name'=>'活动名称',
            'a_date'=>'活动日期',
            'a_tag'=>'活动标签',
            'a_title'=>'活动标题',
            'a_detail'=>'活动详情',
            'a_create_id'=>'创建人ID',
            'a_create_name'=>'创建人名称',
            'a_created'=>'创建时间',
            'a_updated'=>'修改时间',
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search(){
        $criteria = new CDbCriteria;

        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('a_name', $this->a_name, true);
        $criteria->compare('a_date', $this->a_date);
        $criteria->compare('a_tag',$this->a_tag,true);
        $criteria->compare('a_title', $this->a_title, true);
        $criteria->compare('a_create_id', $this->a_create_id);
        $criteria->compare('a_create_name',$this->a_create_name);
        $criteria->compare('a_created', strtotime($this->a_created));
        $criteria->compare('a_updated', strtotime($this->a_updated));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'a_id DESC',
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
        if($this->a_date && !is_numeric($this->a_date))
            $this->a_date = strtotime($this->a_date);
        $this->a_updated = time();
        if ($this->isNewRecord) {
            $this->a_created = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if($this->a_date)
            $this->a_date = $this->int2date( $this->a_date);
    }
    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d", $time);
    }

    /**
     * @param $activeId 活动ID
     * @return string 返回点击查看的路径
     */
    public function getActiveDetail($activeId){
        $url = '/starActive/detail/'.$activeId.'?type=view';
        return $url;
    }

    /**
     * @param $activeId 活动ID
     * @return string 返回点击编辑的路径
     */
    public function getUpdateUrl($activeId){
        $url = '/starActive/update/'.$activeId.'?type=update';
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
        $config = Yii::app()->params->redis_data['star_active']['write'];
        $this->redis = RedisManager::getInstance($config);

    }

    /**
     * 变更的影院，写入reids队列
     * @param $cinemaId
     */
    public function saveCache($cinemaId ='')
    {
        $arrData = [];
        //if(empty($cinemaId)){
        //    return false;
       // }
        if(!$this->redis){
            $this->setRedis();
        }
        //$this->redis->listPush(self::STAR_ACTIVE_PUSH_KEY,$cinemaId,'L');
        $arrData[self::STAR_ACTIVE_SCHE] = self::getActiveAllSche();
        $arrData[self::STAR_ACTIVE_DATA] = self::getActiveAll();
        $this->redis->set(self::STAR_ACTIVE_KEY,json_encode($arrData));
    }

    private function getActiveAllSche()
    {
        $scheData = StarActiveSche::model()->findAll();
        $arrData = [];
        foreach($scheData as $val){
            $arrData[$val->s_cinema_id][$val->s_sche_id] = $val->a_id;
        }
        return $arrData;

    }

    /**
     * 获取所有的内容
     */
    private function getActiveAll()
    {
        $activeData = StarActive::model()->findAll();
        $arrData = [];
        foreach($activeData as $val){
            //$arrData[$val->a_id] = $val->attributes;
            $arrData[$val->a_id] = ['id'=>$val->a_id,'name'=>$val->a_name,'tag'=>$val->a_tag,'title'=>$val->a_title,'detail'=>$val->a_detail];
        }
        return $arrData;
    }

}