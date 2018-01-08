<?php
Yii::import('ext.RedisManager', true);

class ActiveFind extends CActiveRecord
{
    const REDIS_KEY_FIND_LIST ='cms_find_list_';//有序集合，各渠道的总集合【不区分分类】
    const REDIS_KEY_FIND_INFO ='cms_find_info_';//哈希
    const REDIS_KEY_FIND_INFO_TIME = 25920000;
    const REDIS_KEY_FIND_OTHER = 'cms_find_other_';
    const REDIS_KEY_FIND_OTHER_NEW = 'cms_find_other_new_';
    const REDIS_KEY_FIND_TYPE_LIST ='cms_find_list_channel_';//有序集合，各渠道各分类下的集合【区分分类，指定渠道指定分类下】

    private $redis;//在初始化时，为此redis赋值
    public function tableName()
    {
        return 't_active_find';
    }

    public function rules()
    {
        return array(
            array('f_type,up_time,status, created,updated', 'numerical', 'integerOnly' => true),
            array('id,a_id,status,f_type,f_writer，f_title, f_cover, up_time,created,updated', 'safe'),
            array('id,a_id,status,f_type,f_writer,f_title, f_cover, up_time, created,updated', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'channel' => array(self::HAS_MANY, 'ActiveFindChannels', 'f_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'a_id' => '内容ID',
            'f_type' => '分类',
            'f_writer' => '作者',
            'f_title' => '文章标题',
            'f_cover' => '封面图',
            'up_time' => '上线时间',
            'created' => '创建时间',
            'updated' => '修改时间',
            'channel' => '投放平台',
            'status'  =>'状态',
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
        if(empty($this->f_type)){
            $criteria->compare('f_type',"<> 19");
        }else{
            $criteria->compare('f_type', $this->f_type);
        }
        $criteria->compare('f_writer', $this->f_writer,true);
        $criteria->compare('f_title', $this->f_title, true);
        $criteria->compare('f_cover', $this->f_cover);
        $criteria->compare('up_time', $this->up_time);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);
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

    /**
     * @param string $type
     * @return array
     */
    public function getTypeList($type='')
    {
        $arrData = [''=>'全部','1'=>'新片看点','2'=>'趣评电影','3'=>'独家策划','4'=>'明星来了','5'=>'约看计划','6'=>'一场好戏','7'=>'一份片单','8'=>'新鲜预告片','9'=>'专题活动','10'=>'电影老出事',
            '11'=>'票房分析','12'=>'票房排行榜','13'=>'音乐正发声','14'=>'舞台聚光灯','15'=>'艺术现场','16'=>'大艺术家','17'=>'报名活动','18'=>'顺手投票','19'=>'电影头条',
        ];
        if($type != 'all') unset($arrData['']);
        return empty($arrData[$type])?$arrData:$arrData[$type];
    }

    /**
     * 哪些分类不列入全部分类--只在指定分类显示
     * @return array
     */
    public function getNoTypeAllList()
    {
        return ['19'];
    }

    /**
     * 获取指定数据
     * @param $arrData
     * @return string
     */
    public function getChannelStr($arrData){
        $str ='';
        foreach($arrData as $val){
            $ch =ActiveCms::model()->getChannel($val['channel_id']);
            if(is_string($ch))
                $str .=$ch.',';
        }
        return $str;
    }


    #################  以下为reids相关  ##################
    /**
     * 清除发现-redis集合[所有渠道]和各个渠道下的分类下的文件
     *
     */
    public function delRedisCacheForActiveFindList($id)
    {
        self::getRedis();
        $channel = ActiveCms::model()->getChannel('list');
        $typeList = ActiveFind::model()->getTypeList('list');
        foreach($channel as $k=>$val){
            if(empty($k))continue;
            $redisKey = self::REDIS_KEY_FIND_LIST.$k;
            $this->redis->zRem($redisKey,$id);
            foreach($typeList as $key=>$value){
                if(!empty($key))continue;
                $redisKey = self::REDIS_KEY_FIND_TYPE_LIST.$k.'_'.$key;
                $this->redis->zRem($redisKey,$id);
            }
        }
    }

    /**
     * 插入到各个渠道的发现列表
     * @param $id
     */
    public function addRedisCacheForActiveFindList($id,$score,$channel=[],$type='')
    {
        self::getRedis();
        foreach($channel as $k){
            if(empty($k))continue;
            $redisKey = self::REDIS_KEY_FIND_LIST.$k;

            //如果是单独分类才能显示的话，就直接排除全量列表
            if(!in_array($type,$this->getNoTypeAllList())){
                $this->redis->zAdd($redisKey,$score,$id);
            }
            if(!empty($type)){
                $redisTypeKey = self::REDIS_KEY_FIND_TYPE_LIST.$k.'_'.$type;
                $this->redis->zAdd($redisTypeKey,$score,$id);
            }


        }
    }
    //插入
    public function addRedisCacheForActiveFind2Id($id)
    {
        $model = ActiveFind::model()->findByPk($id);
        if(!empty($model)){
            //拼接字段
            $arrData = [];
            $arrData['id'] = $model->id;
            $arrData['type_name'] = $this->getTypeList($model->f_type);
            $arrData['a_id'] = $model->a_id;
            $arrData['f_type'] = $model->f_type;
            $arrData['f_title'] = $model->f_title;
            $arrData['f_cover'] = !empty($model->f_cover)? 'https://appnfs.wepiao.com'.$model->f_cover:'';
            $arrData['up_time'] = $model->up_time;
            $arrData['f_writer'] = $model->f_writer;
            if (is_array($model->channel)){
                foreach($model->channel as $result) {
                    $arrData['channel_url_'.$result['channel_id']] =$result['f_url'];
                }
            }
            $this->addRedisCacheForActiveFindInfo($id,json_encode($arrData));
        }else{
            $this->delRedisCacheForActiveFindInfo($id);
        }
    }
    /**
     * 插入到各个渠道的发现列表
     * @param $id
     */
    public function addRedisCacheForActiveFindInfo($id,$json)
    {
        if(empty($id))return false;
        self::getRedis();
        $redisKey = self::REDIS_KEY_FIND_INFO.$id;
        $this->redis->set($redisKey,$json);
//        $this->redis->expire($redisKey,self::REDIS_KEY_FIND_INFO_TIME);
    }
    /**
     * 删除发现内容
     */
    public function delRedisCacheForActiveFindInfo($id)
    {
        self::getRedis();
        $redisKey = self::REDIS_KEY_FIND_INFO.$id;
        $this->redis->del($redisKey);
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
     * 更新各渠道的指定类别的推荐文章
     */
    public function saveFindCmsOther()
    {
        self::getRedis();
        $time = time();
        $typeList = $this->getTypeList();
        $typeList = array_keys($typeList);
        $channelList = ActiveCms::model()->getChannel();
        $channelList = array_keys($channelList);
        if(!empty($channelList) && !empty($typeList)){
            foreach($typeList as $tid){
                foreach($channelList as $cid){
                    if(empty($tid) || empty($cid)){
                        continue;
                    }
                    $sql="SELECT t_active_find.a_id,t_active_find.f_cover as cover,t_active_find.f_title as title,t_active_find.f_writer,t_active_find_channel.f_url as url from t_active_find INNER JOIN t_active_find_channel ON t_active_find.id = t_active_find_channel.f_id
                WHERE t_active_find.`status`='1' AND t_active_find.up_time <= $time
                  AND t_active_find_channel.channel_id = '$cid'
                  AND t_active_find.f_type = '$tid'
                ORDER BY t_active_find.up_time DESC  LIMIT 5";
                    // Log::model()->logFile('find_sql',$sql."\n");//记录sql
                    $data = Yii::app()->db_active->createCommand($sql)->queryAll();
                    if(!empty($data)){
                        $arrData = [];
                        foreach($data as $val){
                            $val['cover'] = !empty($val['cover'])?'https://appnfs.wepiao.com'.$val['cover']:'';
                            $arrData[] = $val;

                        }
                        $arrData = json_encode($arrData);
                        $this->redis->set(self::REDIS_KEY_FIND_OTHER_NEW.$cid.'_'.$tid,$arrData);
                    }else{
                        $this->redis->set(self::REDIS_KEY_FIND_OTHER_NEW.$cid.'_'.$tid,'');
                    }
                }
            }
        }
    }

    /**
     * 插入到各个渠道的发现列表
     * @param $id
     */
    public function saveRedisCacheForActiveFindList()
    {
        self::getRedis();
//        $arrData = ActiveFindChannels::model()->findAll('status=1 and up_time <= '.time());
        $sql = "select f_id,channel_id,f_url,up_time from t_active_find_channel where `status`=1 and up_time <= ".time();
        $arrData = ActiveFindChannels::model()->findAllBySql($sql);
        if(!empty($arrData))
            foreach($arrData as $val){
                if(empty($val->channel_id))continue;
                $redisKey = self::REDIS_KEY_FIND_LIST.$val->channel_id;// 插入全量
                $activeFind = ActiveFind::model()->findByPk($val->f_id);
                if(!in_array($activeFind->f_type,$this->getNoTypeAllList())){
//                $this->redis->zAdd($redisKey,$score,$id);
                    $this->redis->zAdd($redisKey,$val->up_time,$val->f_id);
                }
                //更新到各个分类下的数据
                if(!empty($activeFind->f_type)){
                    $redisTypeKey = self::REDIS_KEY_FIND_TYPE_LIST.$val->channel_id.'_'.$activeFind->f_type;
                    $this->redis->zAdd($redisTypeKey,$val->up_time,$val->f_id);
                }
            }
//        $this->addRedisCacheForActiveFindList()
    }

    public function saveAllFindInfo()
    {
        $arrData = ActiveFind::model()->findAll('status=1');
        if(!empty($arrData))
            foreach($arrData as $val){
                $this->addRedisCacheForActiveFind2Id($val->id);
            }
        echo " count = ". count($arrData)."\n";
        exit;
    }






}