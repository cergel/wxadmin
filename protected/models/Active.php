<?php
Yii::import('ext.RedisManager', true);

/**
 * This is the model class for table "{{active}}".
 *
 * The followings are the available columns in table '{{active}}':
 * @property string $iActive_id
 * @property string $sType
 * @property string $sTitle
 * @property string $sSummary
 * @property string $sCover
 * @property string $sTag
 * @property string $sSource_name
 * @property string $sSource_head
 * @property string $sSource_summary
 * @property string $sSource_link
 * @property string $sShare_logo
 * @property string $sShare_title
 * @property string $sShare_summary
 * @property string $sShare_link
 * @property string $sShare_platform
 * @property integer $iFill
 * @property integer $iOnline_time
 * @property integer $iOffline_time
 * @property integer $iIsonline
 * @property string $sContent
 * @property string $sVideo_link
 * @property integer $iVideo_times
 * @property string $sPicture
 * @property string $sAudio_link
 * @property integer $iAudio_times
 */
class Active extends CActiveRecord
{
    private $redis;//在初始化时，为此redis赋值

    const  cache_expire_time = 86400;

    //发布城市列表，发布平台列表
    public $post_cities;
    public $post_release;
    public $post_share;

    public $arrAllActiveCities =array();//保存活动更新以前的投放城市和更新以后的投放城市，在活动下线的时候，就可以用这个属性做索引删除

    public $iLikes;

    public $updateType;//可以的值是 edit,release

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{active}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('iReads, iFill,iFillRead, iIsonline,iStatus,iDirect_city', 'numerical', 'integerOnly' => true),
            array('iType, sTitle, sCover, sSource_name, sSource_head, sSource_link, sShare_logo, sShare_title, sShare_summary, sShare_link , sShare_otherLink, sShare_platform, sVideo_link,sVideo_time, sAudio_link, sAudio_time,create_time,update_time,iOnline_time, iOffline_time,', 'length', 'max' => 255),
            array('sSummary, sTag, sSource_summary, sContent,sPicture', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('iActive_id, sType, sTitle, sSummary, sCover, sTag, sSource_name, sSource_head, sSource_summary, sSource_link, sShare_logo, sShare_title, sShare_summary, sShare_link, sShare_platform, real_reads, iOnline_time, iOffline_time, iIsonline, sContent, sVideo_link, iVideo_times, sPicture, sAudio_link, iAudio_times', 'safe', 'on' => 'search'),
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
            'city' => array(self::HAS_MANY, 'ActiveCity', 'iActive_id'),
            'release' => array(self::HAS_MANY, 'ActiveReleaseOld', 'iActive_id'),
            'share' => array(self::HAS_MANY, 'ActiveShareOld', 'iActive_id'),
        );
    }

    /**
     * @tutorial attributeLabels
     * @author liulong
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'iActive_id' => 'ID',
            'iType' => '类型',
            'sTitle' => '标题',
            'sSummary' => '内容描述',
            'sCover' => '封面图',
            'sTag' => '内容标签',
            'sSource_name' => '来源平台-名称',
            'sSource_head' => '来源平台-头像',
            'sSource_summary' => '来源平台-简介',
            'sSource_link' => '来源平台-跳转链接',
            'sShare_logo' => '分享-图标',
            'sShare_title' => '分享-标题',
            'sShare_summary' => '分享-描述',
            'sShare_link' => '分享-链接（微信）',
            'sShare_otherLink' => '分享-链接（非微信）',
            'sShare_platform' => 'S Share Platform',
            'iFill' => '热度注水',
            'iFillRead' => '阅读数注水',
            'iOnline_time' => '上线时间',
            'iOffline_time' => '下线时间',
            'iIsonline' => '上线状态',
            'sContent' => '图文内容',
            'sVideo_link' => '视频链接',
            'sVideo_time' => '视频时长',
            'sPicture' => '相册内容',
            'sAudio_link' => '音频链接',
            'sAudio_time' => '音频时长',
            'iDirect_city' => '定向城市',
            'iReads'=>'真实阅读',
            'iLikes'=>'真实点赞',
        );
    }

    /**
     * @tutorial 查询页面
     * @author liulong
     * @return CActiveDataProvider
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('iActive_id', $this->iActive_id);
        $criteria->compare('iType', $this->iType);
        $criteria->compare('sTitle', $this->sTitle, true);
        $criteria->compare('sSummary', $this->sSummary, true);
        $criteria->compare('sCover', $this->sCover, true);
        $criteria->compare('sTag', $this->sTag, true);
        $criteria->compare('sSource_name', $this->sSource_name, true);
        $criteria->compare('sSource_head', $this->sSource_head, true);
        $criteria->compare('sSource_summary', $this->sSource_summary, true);
        $criteria->compare('sSource_link', $this->sSource_link, true);
        $criteria->compare('sShare_logo', $this->sShare_logo, true);
        $criteria->compare('sShare_title', $this->sShare_title, true);
        $criteria->compare('sShare_summary', $this->sShare_summary, true);
        $criteria->compare('sShare_link', $this->sShare_link, true);
        $criteria->compare('sShare_platform', $this->sShare_platform, true);
        $criteria->compare('iFill', $this->iFill);
        $criteria->compare('iOnline_time', $this->iOnline_time);
        $criteria->compare('iOffline_time', $this->iOffline_time);
        $criteria->compare('iIsonline', $this->iIsonline);
        $criteria->compare('sContent', $this->sContent, true);
        $criteria->compare('sVideo_link', $this->sVideo_link, true);
        $criteria->compare('sVideo_time', $this->sVideo_time);
        $criteria->compare('sPicture', $this->sPicture, true);
        $criteria->compare('sAudio_link', $this->sAudio_link, true);
        $criteria->compare('sAudio_time', $this->sAudio_time);
        $criteria->compare('iStatus', 1);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder'=>'iActive_id DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Active the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

//    public function __construct(){
//        parent::__construct();
//    }


    public function init()
    {
        //parent::init();
        $this->setRedis();
    }


    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['cms_active']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    //拿到此活动投放在哪些城市
    public function getActiveCities(){
        $arrOldCities=ActiveCity::model()->findAllByAttributes(array('iActive_id'=>$this->iActive_id));
        if(!empty($arrOldCities)){
            foreach($arrOldCities as $v){
                if(!in_array($v->city_id,$this->arrAllActiveCities)){
                    $this->arrAllActiveCities[]=$v->city_id;
                }
            }
        }
    }

    //存储前要执行的事件
    protected function beforeSave(){
//        $this->tmpDel();//todo 测试删除函数，提测前删除
//        die;

        parent::beforeSave();
        if(!$this->isNewRecord){//如果是更新记录，先要把以前的城市列表保存下来
            $this->getActiveCities();
        }
        return true;
    }

    //存储后要执行的事件
    public function afterSave()
    {
        $this->saveOthers();//关联表的存储

        if(!$this->isNewRecord){//如果是更新记录，才走清除缓存逻辑
            if($this->updateType=='release'){
                $this->remActiveCityAll();
                $this->remActiveCityDirect();
                $this->remActiveRelease();
            }
            if($this->updateType=='edit'){
                $this->remActiveShare();
            }
            //由于在saveOther函数里也有相关操作，所以要单独写一个类型出来
            if($this->updateType=='ajaxOnline'){
                $this->remActiveCityAll();
                $this->remActiveCityDirect();
                $this->remActiveRelease();
            }
            $this->remActiveInfo();
        }

        if ($this->iIsonline == 1 && $this->iStatus==1) {//如果此活动要上线，则在各个缓存表中追加信息
            if($this->updateType=='release') {
                $this->addActiveCityAll();//集合-存放全国的活动id
                $this->addActiveCityDirect();//集合-存放每个定向城市里的active_id
                $this->addActiveRelease();//发布平台的逻辑
            }
            if($this->updateType=='edit'){
                $this->addActiveShare();//分享平台的逻辑
            }
            //由于在saveOther函数里也有相关操作，所以要单独写一个类型出来
            if($this->updateType=='ajaxOnline') {
                $this->addActiveCityAll();//集合-存放全国的活动id
                $this->addActiveCityDirect();//集合-存放每个定向城市里的active_id
                $this->addActiveRelease();//发布平台的逻辑
            }
        }
        return true;
    }

    //todo 测试用删除函数
    public function tmpDel()
    {
        $keys = $this->redis->keys('*activeCms*');
        foreach ($keys as $v) {
            $strKey = str_replace('baymax_', '', $v);
            $this->redis->del($strKey);
        }
    }

    //将各个符合条件的_全国_活动存入redis
    //已废，稳定运行后可删除他
//    private function setActiveInfo()
//    {
////    信息为被动拉取
//        $preKey="activeCms_info_";
//        $info['title'] = $this->sTitle;
//        $info['cover'] = $this->sCover;
//        $info['online_time'] = !empty($this->iOnline_time)?$this->iOnline_time:'';
//        $info['offline_time'] = !empty($this->iOffline_time)?$this->iOffline_time:'';
//        $info['create_time'] =!empty($this->create_time)?$this->create_time:'';
//        $info['url'] = '';//todo h5页面的url
//        $jsonData=json_encode($info);
//
//        $strKey=$preKey.$this->iActive_id;
//        $this->redis->set($strKey,$jsonData);
//        $this->redis->expire($strKey,604800);
//    }

    //删除活动信息的缓存数据
    public function remActiveInfo(){
        $preKey="activeCms_info_";
        $strKey=$preKey.$this->iActive_id;

        $preKeyFill='activeCms_fill_';//注水数的key
        $strKeyFill=$preKeyFill.$this->iActive_id;

        $preKeyFillRead='activeCms_fillRead_';
        $strKeyFillRead=$preKeyFillRead.$this->iActive_id;

        //实际阅读数，点赞数没有删除，因为阅读数和注水数是每天脚本保存的
        $this->redis->del($strKey);
        $this->redis->del($strKeyFill);
        $this->redis->del($strKeyFillRead);
    }

    private function delActiveInfo(){
        $preKey="activeCms_info_";
        $strKey=$preKey.$this->iActive_id;
        $this->redis->del($strKey);
    }

    //存储没有定向城市的活动id
    private function addActiveCityAll()
    {

        if ($this->iDirect_city == 0) {
            $redisKey = 'activeCms_actives_city_all';
            $this->redis->zAdd($redisKey,$this->iOnline_time ,$this->iActive_id);
            $this->redis->expire($redisKey, 604800);
        }
    }

    //删除没有定向城市的活动id
    private function remActiveCityAll()
    {
        $redisKey = 'activeCms_actives_city_all';
        $this->redis->zRem($redisKey, $this->iActive_id);
    }

    //定向城市_活动存入redis
    private function addActiveCityDirect()
    {
        if ($this->iDirect_city == 1) {
            $preKey = 'activeCms_actives_city_';
            $arrRedisKey = array();
            if (!empty($this->post_cities)) {
                //写入缓存
                foreach ($this->post_cities as $v) {
                    $arrRedisKey[] = $preKey . $v;
                    $strKey = $preKey . $v;
                    $this->redis->zAdd($strKey, $this->iOnline_time , $this->iActive_id);
                }
                //对每个key设置过期
                foreach ($arrRedisKey as $v) {
                    $this->redis->expire($v, 604800);
                }
            }
        }
    }

    //定向城市_活动 删除
    private function remActiveCityDirect()
    {
        if (!empty($this->arrAllActiveCities)) {
            $preKey="activeCms_actives_city_";
            foreach ($this->arrAllActiveCities as $v) {
                $strKey = $preKey . $v;
                $this->redis->zRem($strKey, $this->iActive_id);
            }
        }
    }


    //redis存储发布平台
    private function addActiveRelease()
    {
        if (!empty($this->post_release)) {
            $preKey = 'activeCms_release_';
            $arrRedisKey = array();
            foreach ($this->post_release as $v) {
                $strKey = $preKey . $v;
                $arrRedisKey[] = $strKey;
                $this->redis->setsAdd($strKey, $this->iActive_id);
            }
            foreach ($arrRedisKey as $v) {
                $this->redis->expire($v, 604800);
            }
        }
    }


    //redis删除发布平台
    private function remActiveRelease()
    {
        $arrRelease=array_keys(Yii::app()->params['release_platform']);
        $preKey='activeCms_release_';
        foreach($arrRelease as $v){
            $strKey=$preKey.$v;
            $this->redis->setsDel($strKey,$this->iActive_id);
        }
    }

    //分享的缓存
    private function addActiveShare(){
        if(!empty($this->post_share)){
            $preKey="activeCms_share_";
            $strKey=$preKey.$this->iActive_id;
            foreach($this->post_share as $v){
                $this->redis->setsAdd($strKey,$v);
            }
        }
    }

    private function remActiveShare(){
        $preKey="activeCms_share_";
        $strKey=$preKey.$this->iActive_id;
        $this->redis->del($strKey);
    }

    /**
     * @tutorial 获取type或者获取指定type所代表的汉字
     * @author liulong
     * @param string | int $iType
     * @return Ambigous <multitype:string , unknown>
     */
    public function getIType($iType = 'all')
    {
        $arrType = ['' => '全部', '1' => '图文', '2' => '视频', '3' => '相册', '4' => '原声'];
        if ($iType == '')
            unset($arrType['']);
        if (!empty($iType) && !empty($arrType[$iType]))
            $arrType = $arrType[$iType];
        return $arrType;
    }

    /**
     * @tutorial 保存关联
     */
    public function saveOthers()
    {
        //删除
        $this->afterDelete();
        // 保存关联城市
        if($this->updateType=='edit'){
            //保存关联分享平台
            if (isset($_POST['share'])) {
                foreach ($_POST['share'] as $k => $share) {
                    if (empty($share)) continue;
                    $cnc = new ActiveShareOld();
                    $cnc->iActive_id = $this->iActive_id;
                    $cnc->share = $share;
                    $cnc->save();
                }
            }
        }
        if($this->updateType=='release'){
            if (isset($_POST['cities'])) {
                foreach ($_POST['cities'] as $k => $city) {
                    if (empty($city)) continue;
                    $cnc = new ActiveCity();
                    $cnc->iActive_id = $this->iActive_id;
                    $cnc->city_id = $city;
                    $cnc->save();
                }
            }
            //保存关联发布平台
            if (isset($_POST['release'])) {
                foreach ($_POST['release'] as $k => $realse) {
                    if (empty($realse)) continue;
                    $cnc = new ActiveReleaseOld();
                    $cnc->iActive_id = $this->iActive_id;
                    $cnc->release = $realse;
                    $cnc->save();
                }
            }
        }
    }

    /**
     * @tutorial 删除关联:自动完成
     * @see CActiveRecord::afterDelete()
     * @tutorial liulong
     */
    public function afterDelete()
    {

        parent::afterDelete();
        // 自动删除关联城市
        if($this->updateType == 'edit'){
            // 自动删除关联分享平台
            ActiveShareOld::model()->deleteAllByAttributes(array(
                'iActive_id' => $this->iActive_id
            ));
        }

        if($this->updateType == 'release'){
            ActiveCity::model()->deleteAllByAttributes(array(
                'iActive_id' => $this->iActive_id
            ));
            // 自动删除关联发布平台
            ActiveReleaseOld::model()->deleteAllByAttributes(array(
                'iActive_id' => $this->iActive_id
            ));
        }
    }

    /**
     * 更新redis:给客户端使用的
     */
    public function setRedisCache()
    {
        $time = time();
        //$sql ="SELECT iActive_id FROM {$this->tableName()}  WHERE iIsonline='1' AND iOnline_time >'$time' AND  iOffline_time < '$time' ORDER BY update_time DESC";
        $sql = "SELECT iActive_id FROM {$this->tableName()}  WHERE iIsonline='1'  ORDER BY update_time DESC";
        $arrQueryData = yii::app()->db->createCommand($sql)->queryAll();
        foreach ($arrQueryData as $active) {

        }
        $targetDir = Yii::app()->params[$active]['target_dir'];
    }

    public function setRedisJson()
    {
        //$sql ="SELECT iActive_id FROM {$this->tableName()}  WHERE iIsonline='1' AND iOnline_time >'$time' AND  iOffline_time < '$time' ORDER BY update_time DESC";
        $sql = "SELECT iActive_id FROM {$this->tableName()}  WHERE iIsonline='1'  ORDER BY update_time DESC";
        $arrQueryData = yii::app()->db->createCommand($sql)->queryAll();
        $arrData = [];
        foreach ($arrQueryData as $active) {
            $active = Active::model()->findByPk($active['iActive_id']);
            $arrActive = self::getObj2Array($active);
            //城市提取
            $arrCity = [];
            if (!empty($active->city) && is_array($active->city)) {
                foreach ($active->city as $city)
                    $arrCity[] = $city->city_id;
            }
            $arrActive['city'] = $arrCity;
            //分享平台提取
            $arrShare = [];
            if (!empty($active->share) && is_array($active->share)) {
                foreach ($active->share as $share)
                    $arrShare[] = $share->share;
            }
            $arrActive['share'] = $arrShare;
            //生成渠道数据数组
            $arrRealse = [];
            if (!empty($active->realse) && is_array($active->realse)) {
                foreach ($active->realse as $realse) {
// 					$active->realse = $this->getRealse($share->realse);
                    $arrRealse[] = $realse->realse;
                }
            }
            $arrActive['releasese'] = $arrRealse;
// 			foreach ()
            $arrData[] = $arrActive;
        }
    }

    /**
     * @tutorial 生成JSON等数据的字段
     * @param unknown $obj
     */
    private static function getObj2Array($obj)
    {
        $arrData = [];
        $arrData['iActive_id'] = $obj['iActive_id'];
        $arrData['iType'] = $obj['iType'];
        $arrData['sTitle'] = $obj['sTitle'];
        $arrData['sSummary'] = $obj['sSummary'];
        $arrData['sCover'] = $obj['sCover'];
        $arrData['sTag'] = $obj['sTag'];
        $arrData['sSource_name'] = $obj['sSource_name'];
        $arrData['sSource_head'] = $obj['sSource_head'];
        $arrData['sSource_summary'] = $obj['sSource_summary'];
        $arrData['iOnline_time'] = $obj['iOnline_time'];
        $arrData['iOffline_time'] = $obj['iOffline_time'];
        return $arrData;
    }


    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }

    //模板显示用的
    public function getOnlineStatus($iIsonline){
        return $iIsonline==1?'上线':'待发布';
    }


    //重写删除函数
    public function delete(){
        $this->iStatus=0;
        $this->save();
    }
    /**
     * @tutorial
     * @author liulong
     */
    public function createFileList()
    {
        $arrShare =[];
        foreach($this->share as $k=>$v){
            $arrShare[]=$v->share;
        }
        $this->share = implode(',',$arrShare);
        $arrRelease = array_keys(Yii::app()->params['release_platform']);
        foreach($arrRelease as $v){
            $this->createdWxActive($v);
        }
        // 写入日志
        Log::model()->logPath(Yii::app()->params['CMS']['local_dir'].'/'.$this->iActive_id.'/');
    }
    /**
     *  @tutorial 创建H5页面
     * @author liulong
     */
    public function createdWxActive($release)
    {

        $tempUrl = Yii::app()->params['CMS']['template'];
        $localUrl = Yii::app()->params['CMS']['local_dir'].'/'.$this->iActive_id.'/'.$release;
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
        Yii::app()->params['CMS']['template'],
        $localUrl
        );
        $arrRelease=['3'=>'http://wx.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
                    '6'=>'http://m.wepiao.com/#/movies/<!--movieId-->',
                    '28'=>'http://mqq.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
                    '8'=>'wxmovie://filmdetail?movieid=<!--movieId-->',
                    '9'=>'wxmovie://filmdetail?movieid=<!--movieId-->',
                    '55'=>'javascript:void(0);',
                    '56'=>'javascript:void(0);',

        ];
        $arrShowRelease=['3'=>'http://wechat.show.wepiao.com/detail/onlineId=<!--showId-->',
                    '6'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
                    '28'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
                    '8'=>'wxmovie://showdetail?onlineid=<!--showId-->',
                    '9'=>'wxmovie://showdetail?onlineid=<!--showId-->',
                    '55'=>'javascript:void(0);',
                    '56'=>'javascript:void(0);',
        ];
        $path = Yii::app()->params['CMS']['img_cdn_url'];
        //图文的单独处理
        if ($this->iType == 1){
            self::createArticle($localUrl,$arrRelease[$release]?$arrRelease[$release]:'',$path,$release,$arrShowRelease[$release]?$arrShowRelease[$release]:'');
        }elseif ($this->iType ==2){
            self::createVedio($localUrl,$path,$release);
        }elseif ($this->iType ==3){
            self::createPhoto($localUrl,$path,$release);
        }elseif ($this->iType ==4){
            self::createAudio($localUrl,$path,$release);
        }
    }
    /**
     * @tutorial 图文类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createArticle($localUrl,$movieUrl,$path,$release,$showUrl)
    {
        $info = $this->sContent;
        //$info ="asga{movieId:6031}asdkljo{movieId:5859}sad";
        $info = self::cardMovie($info,$movieUrl);
        $info = self::cardShow($info, $showUrl);
        $info = str_ireplace('src="/uploads', 'src="'.Yii::app()->params['CMS']['img_cdn_url'].'/uploads', $info);
        $info = str_ireplace('http://wxadmin.wepiao.com', Yii::app()->params['CMS']['img_cdn_url'], $info);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS']['template'].'/index.html');
        $fileContent = str_replace(
            array( '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->', '<!--goBack-->',
                '<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->','<!--sShare_otherLink-->','<!--activeId-->','<!--channelId-->','<!--shareData-->',
                '<!--sCover-->',
            ),
            array( $this->sTitle, $path.$this->sSource_head, $this->sSource_name, $this->sSource_summary, $info,self::getGoBack($release),
                Yii::app()->params['CMS']['img_cdn_url'].$this->sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,$this->sShare_otherLink,$this->iActive_id,$release,$this->share,
                Yii::app()->params['CMS']['img_cdn_url'].$this->sCover,
        ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($localUrl . "/index.html", $fileContent);
    }
    /**
     *演出card
     */
    private static function cardShow($info,$showUrl)
    {
        preg_match_all('/{showId:(.*?)}/',$info,$arrData);
        //影片card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS']['template'].'/showCard.html');
        $showObj =showOauthClient::Instance(3,Yii::app()->params['cache']);
        $showDataAll =[];
        if (count($arrData[1]) >=1){
            foreach ($arrData[1] as $showid){
                $showData = $showObj->call('Java/item/querySingleItem', ['onlineId'=>$showid]);
                if (!empty($showData['item']))
                    $showDataAll[$showid] = $showData['item'];
            }
        }
        foreach ($arrData[1] as $val){
            $card = $cardFile;
            $showData = !empty($showDataAll[$val])?$showDataAll[$val]:'';
            if (!empty($showData)){
                $show_img = $showData['itemPicUrl'];
                $show_time = $showData['itemShowTime'];
                $show_addr = $showData['venueName'];
                $show_price = $showData['priceinfo'];
                $show_title = $showData['itemTitleCN'];
                $showUrlData = str_replace("<!--showId-->", $val,$showUrl);
                //替换card
                $card = str_replace(array(
                    '<!--show_img-->',
                    '<!--show_title-->',
                    '<!--show_time-->',
                    '<!--show_addr-->',
                    '<!--show_price-->',
                    '<!--show_url-->',
                ), array(
                    $show_img,
                    $show_title,
                    $show_time,
                    $show_addr,
                    $show_price,
                    $showUrlData,
                ), $card);
                // 替换详细内容
                $info=str_replace("{showId:$val}", $card,$info);
            }
        }
        return $info;
    }
    /**
     *影片card
     */
    private static function cardMovie($info,$movieUrl)
    {
        preg_match_all('/{movieId:(.*?)}/',$info,$arrMovie);
        //电影card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS']['template'].'/movieCard.html');
        if (count($arrMovie[1]) >1)
            $movieDataAll = Movie::model()->getMovieInfo(implode('|',$arrMovie[1]));
        elseif(count($arrMovie[1]) == 1) $movieDataAll[$arrMovie[1][0]] =Movie::model()->getMovieInfo($arrMovie[1][0]);
        else $movieDataAll =[];
        foreach ($arrMovie[1] as $val){
            $card = $cardFile;
            $movie_info= [];
            $movieData = !empty($movieDataAll[$val])?$movieDataAll[$val]:'';
            if (!empty($movieData)){
                $movie_info[] = $movieData['Director'];
                $Starring = @explode('/', $movieData['Starring']);
                if (!empty($Starring[0]))
                    $movie_info[] = $Starring[0];
                if (!empty($Starring[1]))
                    $movie_info[] = $Starring[1];
                //$movie_info[] = $movieData['Starring'];
                $movie_type =  $movieData['MovieType'];
                $movie_first = empty($movieData['FirstTime'])?'':date('Y.n.d',$movieData['FirstTime']);
                $movie_info = array_filter($movie_info);
                $movie_info = implode(' / ',$movie_info);
                $movie_url = str_replace("<!--movieId-->", $movieData['MovieNo'],$movieUrl);
                $movie_name = $movieData['MovieNameChs'];
                $movie_img = !empty($movieData['IMG_COVER'][0])?reset($movieData['IMG_COVER'][0]):'http://picture-msdb.wepiao.com/movieDataImages/images/5/7.jpg';
                //替换card
                $card = str_replace(array(
                    '<!--movie_url-->',
                    '<!--movie_name-->',
                    '<!--movie_img-->',
                    '<!--movie_info-->',
                    '<!--movie_type-->',
                    '<!--movie_first-->',
                ), array(
                    $movie_url,
                    $movie_name,
                    $movie_img,
                    $movie_info,
                    $movie_type,
                    $movie_first,
                ), $card);
                // 替换详细内容
                $info=str_replace("{movieId:$val}", $card,$info);
            }
        }
        return $info;
    }
    /**
     * @tutorial 音频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createAudio($localUrl,$path,$release)
    {
        //音频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS']['template'].'/audio.html');
        $cardFile = str_replace(['<!--sAudio_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sAudio_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS']['template'].'/audio_index.html');
        $fileContent = str_replace(
            array( '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->', '<!--goBack-->',
                '<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->','<!--sShare_otherLink-->','<!--activeId-->','<!--channelId-->','<!--shareData-->',
                '<!--sCover-->',
            ),
            array( $this->sTitle, $path.$this->sSource_head, $this->sSource_name, $this->sSource_summary, $cardFile,self::getGoBack($release),
                Yii::app()->params['CMS']['img_cdn_url'].$this->sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,$this->sShare_otherLink,$this->iActive_id,$release,$this->share,
                Yii::app()->params['CMS']['img_cdn_url'].$this->sCover,
            ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($localUrl . "/index.html", $fileContent);
    }
    /**
     * @tutorial 视频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createVedio($localUrl,$path,$release)
    {
        //视频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS']['template'].'/vedio.html');
        $cardFile = str_replace(['<!--sVideo_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sVideo_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS']['template'].'/index.html');
        $fileContent = str_replace(
            array( '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->', '<!--goBack-->',
                '<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->','<!--sShare_otherLink-->','<!--activeId-->','<!--channelId-->','<!--shareData-->',
            ),
            array( $this->sTitle, $path.$this->sSource_head, $this->sSource_name, $this->sSource_summary, $cardFile,self::getGoBack($release),
                Yii::app()->params['CMS']['img_cdn_url'].$this->sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,$this->sShare_otherLink,$this->iActive_id,$release,$this->share,
            ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($localUrl . "/index.html", $fileContent);
    }
    /**
     * @tutorial  相册类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createPhoto($localUrl,$path,$release)
    {
        $info = json_decode($this->sPicture,true);
        //电影card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS']['template'].'/photo.html');
        $content = '';
        foreach ($info as $val){
            $content .= str_replace(['<!--sInfo-->','<!--sPhotoImg -->'], [$val['content'],$path.$val['path']], $cardFile);
        }
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS']['template'].'/index.html');
        $fileContent = str_replace(
            array( '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->', '<!--goBack-->',
                '<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->','<!--sShare_otherLink-->','<!--activeId-->','<!--channelId-->','<!--shareData-->',
            ),
            array( $this->sTitle, $path.$this->sSource_head, $this->sSource_name, $this->sSource_summary, $content,self::getGoBack($release),
                Yii::app()->params['CMS']['img_cdn_url'].$this->sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,$this->sShare_otherLink,$this->iActive_id,$release,$this->share,
            ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($localUrl . "/index.html", $fileContent);
    }
    private function getGoBack($release)
    {
        $info = '';
        if (in_array($release,[3,4,6,55,56]))
            $info = file_get_contents(Yii::app()->params['CMS']['template'].'/wxgoback.html');
        return $info;
    }


    //将redis中的阅读数保存进db
    public function saveReadNum(){
        $dbResult=Active::model()->findAll();
        if($dbResult){
            $arrActiveId=array();
            foreach($dbResult as $v){
                $arrActiveId[]=$v->iActive_id;
            }
            foreach($arrActiveId as $v){
                $preKey='activeCms_reads_';
                $strKey=$preKey.$v;
                $redisNum=$this->redis->get($strKey);
                if(!empty($redisNum)){
                    $r=Active::model()->updateByPk($v,array('iReads'=>$redisNum));
                    if($r){
                        //更新key的时间
                        $this->redis->expire($strKey,604800);
                        echo "update read succ activeId:{$v}\n";
                    }else{
                        echo "update read fail activeId:{$v}\n";
                    }
                }
            }
        }else{
            echo 'no active';
        }
    }
    public function getDialogInfo($id)
    {
       $str ="";
        //现在显示全部的
        $release=array_keys(Yii::app()->params['release_platform']);
       foreach($release as $v ) {

           if (!empty($id) && !empty($v)) {
               $release_platform=Yii::app()->params['release_platform'];
               $str .= "<br>".$release_platform[$v]."</br>";

               $str .= "&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接：<a target ='_blank' href=\"" . Yii::app()->params['CMS']['local_url'] . "/$id/".$v."/index.html\">";
              $str .= Yii::app()->params['CMS']['local_url'] . "/$id/".$v."/index.html</a></br>";
                $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['CMS']['final_url'] . "/$id/".$v."/index.html\">";
                $str .= Yii::app()->params['CMS']['final_url'] . "/$id/".$v."/index.html</a>";
           }

        }
      if (empty($str)){
           $str ="没有选择任何模板";
      }
       return $str;
    }


    //从缓存中获取真实的阅读数
    public function getRealReadNum($iActive_id){
        $preKey='activeCms_reads_';
        $strKey=$preKey.$iActive_id;
        $redisValue = $this->redis->get($strKey);
        if(!empty($redisValue)){
            return $redisValue;
        }else{
            $dbRe = self::model()->findByPk($iActive_id);
            if($dbRe){
                $iReads = $dbRe->iReads;
                $this->redis->set($iReads,self::cache_expire_time);
                return $iReads;
            }else{
                return 0;//这种情况理论上不可能出现，除非有脏数据
            }
        }
    }

    //获取真实的点赞数
    public function getRealLikesNum($iActive_id){
        $preKey='activeCms_like_nums_';
        $strKey=$preKey.$iActive_id;
        $nums=$this->redis->get($strKey);

        if(empty($nums)){
            $sql = "select count(1) as total from t_active_like where IActive_id  = $iActive_id";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->queryRow();
            if(!empty($result['total'])){
                $nums = $result['total'];
            }else{
                $nums = 0;
            }
            $this->redis->set($strKey,$nums);
        }
        return !empty($nums)?$nums:0;
    }

    public function getEditUrl($activeId){
        $url = '/active/update/'.$activeId.'?updateType=edit';
        return $url;
    }

    public function getReleaseUrl($activeId){
        $url = '/active/update/'.$activeId.'?updateType=release';
        return $url;
    }



}
