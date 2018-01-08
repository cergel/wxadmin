<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:51
 */

class DaySign extends CActiveRecord
{
    const TYPE_DIALOGUE = 1;     // 经典台词
    const TYPE_MOVIE = 2;     // 影片推荐
    const TYPE_ANA = 3;    // 影人语录
    const TYPE_CREDITS = 4;    // 花絮
    const TYPE_POSTER = 5;    // 海报

    const TYPE_DETAILS = 1;    // 详情
    const TYPE_PRIVILEGE = 2;     // 优惠
    const TYPE_PURCHASE = 3;     // 购票
    const TYPE_NULL = -1;        //未设置

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{app_day_sign}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('iID', 'unique', 'message' => '当天推荐已存在'),
            array('iType,iTitle, iBackground', 'required'),
            array('iSupport', 'numerical'),
            array('iNimage, iImage,iContent,iFfilm,iMovietime,iTitle,iDirectname,iMdecribe,iActorName,iActorprofession,iTitlecontent,iProduct,iPushfilm,iActorsay,iPushtext,iDecribe,iContenttext', 'length', 'max' => 200),
//限制上传大小
            CUploadedFile::getInstance($this, 'iBackground') || $this->isNewRecord ? array('iBackground',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpeg,jpg,png,gif',
                'maxSize'=>1024*250,    // 200kb
                'tooLarge'=>'背景图大于250kb，上传失败！请上传小于250kb的背景图！'
            ) : array('iImage', 'length', 'max'=>2000),
            array('iID,iContenttext,iDecribe,sVideoType,iTitle,iLinkType,iContent,iTitlecontent,iPushtext,iActorsay,iMusic,iMdecribe,iSupport,iAddress,sOtherDay,sVideo,sShareShowTitle,sShareTitle,sShareSecondTitle,sShareUrl,sShareImg', 'safe'),
            // The following rule is used by search().下面的属性可以用来查询
            // @todo Please remove those attributes that should not be searched.
            //array('iID, iTitle, iType,iContent,iFfilm,iPushfilm,iDirectname,iDecribe,iAddress,iTitlecontent', 'safe', 'on' => 'search'),
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
            'iID' => '时间日期',
            'iTitle' => '标题',
            'iNimage' => '入口未点击',
            'iImage' => '入口已点击',
            'iBackground' => '背景图',
            'iContent' => '台词文本',
            'iFfilm' => '选自电影',
            'iMovietime' => '电影年代',
            'iMusic' => '音频播放',
            'iMdecribe'=>'音频描述',
            'iSupport' => '点赞注水',
            'iLinkType'=>'链接类型',
            'iAddress' => '跳转链接',
            'iType' => '推送类型',
            'iPushfilm' => '推荐电影',
            'iDirectname' => '导演名称',
            'iActorName' => '影人名称',
            'iActorprofession' => '影人身份',
            'iProduct' => '代表作品',
            'iTitlecontent' => '标题文字',
            'iActorsay' => '影人语录',
            'iPushtext' => '推荐语',
            'iDecribe' => '描述内容',
            'iContenttext' => '内容文本',
            'sOtherDay' => '特殊节日',
            'sVideo' => '视频连接',
            'sVideoType' => '视频连接类型',
            'sShareShowTitle' => '分享显示标题',
            'sShareTitle' => '分享标题',
            'sShareSecondTitle' => '分享副标题',
            'sShareUrl' => '分享连接',
            'sShareImg' => '分享图标',

        );
    }

    /**
     * 视频连接类型
     * @param int $key
     * @return array
     */
    public static function getVideoType($key=0)
    {
        $arrData = [''=>'全部','1'=>'视频','2'=>'GIF'];
        if($key != 'all'){
            unset($arrData['']);
        }
        return !empty($arrData[$key])?$arrData[$key]:$arrData;
    }

    public function getDay($iID)
    {
        $iID = intval($iID);
        //创建数据库命令  Yii::app()->db->createCommand()
        $list = Yii::app()->db->createCommand()->select("*")->from("t_app_day_sign")->where('iID=:iID', array(':iID' => $iID))->queryRow();

        return empty($list) ? false : $list;
    }

    public function getAll()
    {
        $list = Yii::app()->db->createCommand()->select("iID")->from("t_app_day_sign")->queryAll();
        return empty($list) ? false : $list;
    }

    /**
     * 获取最新缓存存入memcache
     * 该方法已取消
     */
   // public function getMemcachePush()
   // {
    //    return true;
      //  $date = date('Ymd', time() + 24 * 3600);
      //  $sql = " SELECT * FROM {$this->tableName()} WHERE iID< $date ORDER BY iID DESC";
      //  $list = Yii::app()->db->createCommand($sql)->queryRow();
      //  if (!empty($list)) {
         //   yii::app()->cache_app->set('day_push_caches_key', $list, 60 * 5);
      //  }
   // }

    public function beforeSave()
    {

        return true;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DaySign the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getTypeName()
    {
        switch ($this->iType) {
            case self::TYPE_DIALOGUE:
                return '经典台词';
            case self::TYPE_MOVIE:
                return '影片推荐';
            case self::TYPE_ANA:
                return '影人语录';
            case self::TYPE_CREDITS:
                return '花絮';
            case self::TYPE_POSTER:
                return '海报';
        }
    }

    public static function getTypeList()
    {
        return array(
            self::TYPE_DIALOGUE => '经典台词',
            self::TYPE_MOVIE => '影片推荐',
            self::TYPE_ANA => '影人语录',
            self::TYPE_CREDITS => '花絮',
            self::TYPE_POSTER => '海报',
        );
    }
    public static function getLinkTypeList()
    {
        return array(
            self::TYPE_DETAILS => '详情',
            self::TYPE_PRIVILEGE => '优惠',
            self::TYPE_PURCHASE => '购票',
            self::TYPE_NULL => '未设置'
        );
    }
    /**
     * 获取推荐类型对应栏目
     * @param $iType
     * @return string
     */
    public static function getPartList($iType)
    {
        switch ($iType) {
            case self::TYPE_DIALOGUE:
                return 'iContent,iFfilm,iMovietime';break;
            case self::TYPE_MOVIE:
                return 'iPushfilm,iDirectname,iPushtext';break;
            case self::TYPE_ANA:
                return 'iActorName,iActorprofession,iProduct,iActorsay';break;
            case self::TYPE_CREDITS:
                return 'iTitlecontent,iDecribe';break;
            case self::TYPE_POSTER:
                return 'iContenttext';break;
            default:
                return '';
        }
    }

    /**
     * 对输入的图片进行验证
     * @param $fileImg $_FILES 上传的图片文件
     * @return array 包含所有验证通过的图片名的数组
     * 引用示例 $validatedImgs = $model->validateImg($_FILES['DaySign']);
     */
    public function validateImg($fileImg) {
        $validatedImgs = [];
        foreach ($fileImg['name'] as $imgKey => $imgName) {
            if (!empty($imgName)) {
                $file['name'] = $_FILES['DaySign']['name'][$imgKey];
                $file['type'] = $_FILES['DaySign']['type'][$imgKey];
                $file['size'] = $_FILES['DaySign']['size'][$imgKey];
                $file['error'] = $_FILES['DaySign']['error'][$imgKey];

                $postfix = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                //验证图片后缀名与文件类型是否正确
                if (in_array($postfix, ['gif', 'jpg', 'png']) ||
                    in_array($file['type'], ['image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'])
                ) {
                    //根据图片是否存在生成对应三个变量$iBackground,$iNimage,$iImage
                    $validatedImgs[] = $imgKey;
                }
            }
        }
        return $validatedImgs;
    }

    /**
     * 保存图片
     * @param $model
     * @param $objImg array 图片名对应图片对象的数组,如 ['img1' => $objImg1 ,]
     */
    public function saveImg($model,$objImg) {
        foreach ($objImg as $k => $v) {
            if (!empty($objImg[$k])) {
                $model->$k = $k . $model->iID . rand(10000, 99999) . '.' . $objImg[$k]->getExtensionName();
                $uploadDir = Yii::app()->basePath . '/../uploads/app_daySign';
                if (!file_exists(dirname($uploadDir . '/' . $model->$k)))
                    mkdir(dirname($uploadDir . '/' . $model->$k), 0777, true);
                $objImg[$k]->saveAs($uploadDir . '/' . $model->$k, true);
            }
        }
    }

    /**
     * 压缩图片
     */
    public function processImang($image){
        if(strstr($image,'http')) return $image;
        $uploadDir = Yii::app()->basePath . '/../uploads/app_daySign/'.$image;
        //$size = filesize($uploadDir);
        //$data = fread(fopen($uploadDir,"r"),$size);
        $data = file_get_contents($uploadDir);
        $data = base64_encode($data);
        $arrData = [];
        $arrData['channelId'] = 8;
        $arrData['fileName'] = $image;
        $arrData['content'] = $data;
        $url = "http://img-handle.wepiao.com/common/sohu-image/img-process";
        $res = Https::getPost($arrData,$url);
        $res = json_decode($res,true);
        if(!empty($res['data']['imgUrl'])){
            $info = file_get_contents($res['data']['imgUrl']);
            //$image = 'new'.$image;
            //$uploadDir = Yii::app()->basePath . '/../uploads/app_daySign/'.$image;
            file_put_contents($uploadDir,$info);
            return $image;
        }else return '';
    }


}
