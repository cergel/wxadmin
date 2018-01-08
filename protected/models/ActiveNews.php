<?php
Yii::import('ext.RedisManager', true);

class ActiveNews extends CActiveRecord
{
    const REDIS_KEY_NEWS_LIST ='cms_news_list_';//有序集合
    const REDIS_KEY_FIND_INFO_TIME = 2592000;

    private $redis;//在初始化时，为此redis赋值
    public function tableName()
    {
        return 't_active_news';
    }

    public function rules()
    {
        return array(
            array('a_id, status, movie_id,up_time', 'numerical', 'integerOnly' => true),
            array('id,a_id,status,movie_id,up_time,n_photo', 'safe'),
            array('id,a_id,status,movie_id,up_time,n_photo', 'safe', 'on' => 'search'),
        );
    }
    public function relations()
    {
        return array(
          //  'notifacation'=>array(self::BELONGS_TO, 'ActiveCms', 'a_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'a_id' => '内容ID',
            'movie_id' => '影片id',
            'f_title' => '文章标题',
            'up_time' => '上线时间',
            'status'  =>'状态',
            'n_photo' =>'图片',
        );
    }
    /**
     * @tutorial 查询页面
     * @author liulong
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('movie_id', $this->movie_id);
        $criteria->compare('up_time', $this->up_time);
        $criteria->compare('status', $this->status);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC',
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

    public function delCache($aid)
    {
        if(empty($aid))return false;
        $data = ActiveNews::model()->findByPk($aid);
        if(!empty($data))
        self::delListCache($data->movie_id,$data->a_id);

//        $data = ActiveNews::model()->findAllByAttributes(array('a_id' => $aid));
//        foreach($data as $val){
//            self::delListCache($val->movie_id,$val->a_id);
//        }

    }
    public function addCache($id)
    {
        if(empty($id))return false;
        $model = ActiveNews::model()->findByPk($id);
        if($model->up_time <= time()){
            self::addListCache($model->movie_id,$model->up_time,$model->a_id);
        }
    }

    private function  delListCache($modeiId,$aid)
    {
        self::getRedis();
        if(!$this->redis->exists(self::REDIS_KEY_NEWS_LIST.$modeiId)){
            return false;
        }
        $this->redis->zRem(self::REDIS_KEY_NEWS_LIST.$modeiId,$aid);
        $this->redis->zRem(self::REDIS_KEY_NEWS_LIST.$modeiId,'');
    }
    private function  addListCache($modeiId,$score,$aid)
    {

        self::getRedis();
        if(!$this->redis->exists(self::REDIS_KEY_NEWS_LIST.$modeiId)){
            return false;
        }
        $this->redis->zAdd(self::REDIS_KEY_NEWS_LIST.$modeiId,$score,$aid);
        $this->redis->zRem(self::REDIS_KEY_NEWS_LIST.$modeiId,'');
        $this->redis->expire(self::REDIS_KEY_NEWS_LIST.$modeiId,self::REDIS_KEY_FIND_INFO_TIME);
    }
    private function getRedis()
    {
        if(empty($this->redis)){
            $this->setRedis();
        }
    }
    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['cms_active_new']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    /**
     * 资讯自动上线
     */
    public function saveMovieNews()
    {
        $sql = "select id from t_active_news where `status`=1 and up_time <= ".time();
        $arrData = ActiveNews::model()->findAllBySql($sql);
        foreach($arrData as $val){
            $this->addCache($val->id);
        }
        echo 'news count:'.count($arrData)."\n";
    }



}
