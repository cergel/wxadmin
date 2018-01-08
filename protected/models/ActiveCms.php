<?php
Yii::import('ext.RedisManager', true);

class ActiveCms extends CActiveRecord
{
    public $news_time;
    public $news_photo;
    public $is_news;
    private $redis;//在初始化时，为此redis赋值
    const REDIS_KEY_INFO_TIME = 2592000;
    const CMS_CMS_INFO = 'cms_cms_info_';  //内容
    const CMS_LIKE_LIST = 'cms_like_list_';  //喜欢集合：
    const CMS_READ_NUM = 'cms_read_num_';  //阅读总数
    public function tableName()
    {
        return 't_active';
    }

    public function rules()
    {
        return array(
            array('iReads,sSource_id, iFill,iFillRead, iIsonline,iStatus,iDirect_city', 'numerical', 'integerOnly' => true),
            array('sSource_qr,iType, sTitle, sCover, sSource_name, iCopyright,sSource_head, sSource_link, sShare_logo, sShare_title, sShare_summary, sShare_link , sShare_otherLink, sShare_platform, sVideo_link,sVideo_time, sAudio_link, sAudio_time,create_time,update_time,iOnline_time, iOffline_time,', 'length', 'max' => 255),
            array('sSource_qr,ShortTitle,sSource_id,sSummary, sTag, sSource_summary,iCopyright, sContent,sPicture', 'safe'),
            array('sSource_qr,ShortTitle,iActive_id,sSource_id,iReads, sType,iLikes, sTitle, iCopyright,sSummary, sCover, sTag, sSource_name, sSource_head, sSource_summary, sSource_link, sShare_logo, sShare_title, sShare_summary, sShare_link, sShare_platform, real_reads, iOnline_time, iOffline_time, iIsonline, sContent, sVideo_link, iVideo_times, sPicture, sAudio_link, iAudio_times', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'share' => array(self::HAS_MANY, 'ActiveShare', 'iActive_id'),
            'movie_id' => array(self::HAS_MANY, 'ActiveNews', 'a_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'ShortTitle'=>'短标题',
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
            'share' =>'分享平台',
            #### 以下为资讯 ####
            'is_news'  =>'设为资讯',
            'news_time'  =>'上线时间',
            'movie_id' =>'有关联影片id',
            'news_photo' =>'上传图片',

            'iCopyright'=>'版权声明',
            'sSource_id'=>'作者id',
            'sSource_qr'=>'来源平台二维码',
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
        $criteria->compare('ShortTitle', $this->ShortTitle);
        $criteria->compare('sSource_id', $this->sSource_id);
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
        $criteria->compare('iReads', $this->iReads);
        $criteria->compare('iLikes', $this->iLikes);
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

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDbConnection()
    {
        return Yii::app()->db_active;
    }
    /**
     * @tutorial 获取type或者获取指定type所代表的汉字
     * @author liulong
     * @param string | int $iType
     * @return Ambigous <multitype:string , unknown>
     */
    public function getIType($iType = 'list')
    {
        $arrType = ['' => '全部', '1' => '图文', '2' => '视频', '3' => '相册', '4' => '原声'];
        if($iType == 'list')
            unset($arrType['']);
        return empty($arrType[$iType])?$arrType:$arrType[$iType];
    }
    /**
     * 定义所有的渠道
     * @param string $channelType
     * @return array
     */
    public function getChannel($channelType='list')
    {
        $channelData = [''=>'全部','3'=>'微信电影票','8'=>'IOS','9'=>'Android','28'=>'手Q', '6'=>'M站'];
        if($channelType == 'list')
            unset($channelData['']);
        return empty($channelData[$channelType])?$channelData:$channelData[$channelType];
    }
    public function getShare($type='list')
    {
        $data = [''=>'全部','6'=>'微信好友', '7'=>'微信朋友圈','1'=>'新浪微博','2'=>'QQ空间',];
        if($type == 'list')
            unset($data['']);
        return empty($data[$type])?$data:$data[$type];
    }

    /**
     * 获取弹出框内容
     * @param $id
     * @return string
     */
    public function getDialogInfo($id)
    {
        $str ="";
        $str .= "<br>各端共用链接</br>";
        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接：<a target ='_blank' href=\"" . Yii::app()->params['CMS_new']['local_url'] . "/$id/index.html\">";
        $str .= Yii::app()->params['CMS_new']['local_url'] . "/$id/index.html</a></br>";
        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['CMS_new']['final_url'] . "/$id/index.html\">";
        $str .= Yii::app()->params['CMS_new']['final_url'] . "/$id/index.html</a>";
        if (empty($str)){
            $str ="没有生成选择任何模板";
        }
        return $str;
    }

    /**
     * 获取活动的标题
     * @param $id
     */
    public function getCmsTitle($id)
    {
        $model = ActiveCms::model()->findByPk($id);
        return empty($model->sTitle)?'':$model->sTitle;
    }

    /**
     * @tutorial 生成模板
     * @author liulong
     */
    public function createFileList($id='')
    {
        if(!empty($id))
            ActiveCms::model()->findByPk($id)->saveCreateFileList();
        else $this->saveCreateFileList();
    }
    public function saveCreateFileList()
    {
        $arrShare =[];
        foreach($this->share as $k=>$v){
            $arrShare[]=$v->share;
        }
        $this->share = implode(',',$arrShare);
        $arrRelease = array_keys(Yii::app()->params['release_platform']);
//        foreach($arrRelease as $v){
//            $this->createdWxActive($v);
//        }
        //生成H5页面
        $this->createdWxActive('');
        @chmod(Yii::app()->params['CMS_new']['local_dir'].'/',0777);

        // 写入日志用于rsync推送
        Log::model()->logPath(Yii::app()->params['CMS_new']['local_dir'].'/'.$this->iActive_id.'/');
    }
    /**
     *  @tutorial 创建H5页面
     * @author liulong
     */
    public function createdWxActive($release)
    {

        //新的模板路径
//        $tempUrl = Yii::app()->params['CMS_new']['template'];
        $localUrl = Yii::app()->params['CMS_new']['local_dir'].'/'.$this->iActive_id.'/';
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
            Yii::app()->params['CMS_new']['template'],
            $localUrl
        );
        //电影card链接
        $arrRelease=['3'=>'http://wx.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
            '6'=>'http://m.wepiao.com/#/movies/<!--movieId-->',
            '28'=>'http://mqq.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
            '8'=>'wxmovie://filmdetail?movieid=<!--movieId-->',
            '9'=>'wxmovie://filmdetail?movieid=<!--movieId-->',
            '55'=>'javascript:void(0);',
            '56'=>'javascript:void(0);',
        ];
        $arrRelease = Yii::app()->params['movie_card_url'];
        //演出card链接
        $arrShowRelease=['3'=>'http://wechat.show.wepiao.com/detail/onlineId=<!--showId-->',
            '6'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
            '28'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
            '8'=>'wxmovie://showdetail?onlineid=<!--showId-->',
            '9'=>'wxmovie://showdetail?onlineid=<!--showId-->',
            '55'=>'javascript:void(0);',
            '56'=>'javascript:void(0);',
        ];
        $arrShowRelease = Yii::app()->params['show_card_url'];
        $path = Yii::app()->params['CMS_new']['img_cdn_url'];
        //图文的单独处理
        if ($this->iType == 1){
            self::createArticle($localUrl,$path);
        }elseif ($this->iType ==2){
            self::createVedio($localUrl,$path);
        }elseif ($this->iType ==3){
            self::createPhoto($localUrl,$path);
        }elseif ($this->iType ==4){
            self::createAudio($localUrl,$path);
        }
    }

    /**
     * @param $id
     * 更新数据
     */
    public function saveCache($id)
    {
        $model = ActiveCms::model()->findByPk($id);
        if(!$model){
            return false;
        }
        $this->getRedis();
        $arrData['a_id'] = $model->iActive_id;//cms id
        $arrData['cover'] = !empty($model->sCover)?'https://appnfs.wepiao.com'.$model->sCover:'';//cms 封面图
        $arrData['title'] = $model->sTitle;//cms 标题
        $arrData['source_name'] = $model->sSource_name; //作者
        $arrData['base_read'] = $model->iFillRead;// cms 阅读注水数
        $arrData['base_fill'] = $model->iFill; //cms点赞注水数
        $arrData['likes'] = $model->iLikes; //cms点赞注水数
        $arrData['reads'] = $model->iReads; //cms点赞注水数
        $arrData['short_title'] = $model->ShortTitle; //cms点赞注水数
        foreach($model->movie_id as $v){
            $arrData['up_time'] = $v->up_time;
            $arrData['n_photo'] = !empty($v->n_photo)?'https://appnfs.wepiao.com'.$v->n_photo:'';
        }
        if(!empty($model->iStatus)){
            $arrData = json_encode($arrData);
        }else $arrData = json_encode([]);
        $this->redis->set(self::CMS_CMS_INFO.$id,$arrData);
        $this->redis->expire(self::CMS_CMS_INFO.$id,self::REDIS_KEY_INFO_TIME);
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
     * 版权声明--暂时未使用
     * @param $key
     * @return array
     */
//    private function getCopyright($key){
//        $arrData = [];
//        $arrData['1'] = ['c'=>'©原创声明','s'=>'本文为娱票儿原创，版权归娱票儿所有，任何媒体、网站或个人未经授权不得以任何形式转载。'];
//        $arrData['2'] = ['c'=>'©转载声明','s'=>'本文经原发布平台授权转载至娱票儿。版权归本文作者所有。任何媒体、网站或个人未经授权，不得以任何形式转载。'];
//        if(!empty($arrData[$key]))
//            return $arrData[$key];
//        else return $arrData['1'];
//    }

    /**
     * @tutorial 图文类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createArticle($localUrl,$path)
    {
        $info = $this->sContent;
        //$info ="asga{movieId:6031}asdkljo{movieId:5859}sad";
        $info = self::cardMovie($info);
        $info = self::cardShow($info);
        //版权内容替换
//        $strCopyright = self::getCopyright($this->iCopyright);
        $info = str_ireplace('src="/uploads', 'src="'.Yii::app()->params['CMS_new']['img_cdn_url'].'/uploads', $info);
        $info = str_ireplace('http://wxadmin.wepiao.com', Yii::app()->params['CMS_new']['img_cdn_url'], $info);
        //内容替换
        $fileContent = self::strReplace($path,$info,$localUrl);
    }
    /**
     * @tutorial 音频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createAudio($localUrl,$path)
    {
        //音频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/audio.html');
        $cardFile = str_replace(['<!--sAudio_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sAudio_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent = self::strReplace($path,$cardFile,$localUrl);
    }

    /**
     * @tutorial  相册类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createPhoto($localUrl,$path)
    {
        $info = json_decode($this->sPicture,true);
        //电影card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/photo.html');
        $content = '';
        foreach ($info as $val){
            $content .= str_replace(['<!--sInfo-->','<!--sPhotoImg -->'], [$val['content'],$path.$val['path']], $cardFile);
        }
        //内容替换
        $fileContent = self::strReplace($path,$content,$localUrl);
    }
    /**
     * @tutorial 视频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createVedio($localUrl,$path)
    {
        //视频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/vedio.html');
        $cardFile = str_replace(['<!--sVideo_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sVideo_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent = self::strReplace($path,$cardFile,$localUrl);
    }

    /**
     * 写入内容
     * @param $fileContent
     * @param $path
     * @param string $cardFile
     * @param $localUrl
     */
    private function strReplace($path,$cardFile='',$localUrl)
    {
        $sShare_logo = substr($this->sShare_logo,0,4) == 'http'?$this->sShare_logo:Yii::app()->params['CMS_new']['img_cdn_url'].$this->sShare_logo;
        $sCover = substr($this->sCover,0,4) == 'http'?$this->sCover:Yii::app()->params['CMS_new']['img_cdn_url'].$this->sCover;
        $sSource_head = substr($this->sSource_head,0,4) == 'http'?$this->sSource_head:Yii::app()->params['CMS_new']['img_cdn_url'].$this->sSource_head;
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS_new']['template'].'/index.html');
        $classifytitle = ActiveFind::model()->getTypeList();
        $classifytitle = json_encode($classifytitle);
        $iCopyright = $this->iType == 1?$this->iCopyright:0;
        $fileContent = str_replace(
            [   '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->',
                '<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->',
                '<!--sShare_otherLink-->','<!--activeId-->','<!--shareData-->','<!--sCover-->',
                '<!--iCopyright-->','<!--classifytitle-->','<!--sSource_qr-->','<!--sSource_id-->',
            ],
            [   $this->sTitle, $sSource_head, $this->sSource_name, $this->sSource_summary, $cardFile,
                $sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,
                $this->sShare_otherLink,$this->iActive_id,$this->share,$sCover,
                $iCopyright,$classifytitle,$this->sSource_qr,$this->sSource_id,
            ], $fileContent);
        file_put_contents($localUrl . "/index.html", $fileContent);
       // return $fileContent;
    }
    //取消goback 前端判断
//    private function getGoBack($release)
//    {
//        $info = '';
//        if (in_array($release,[3,4,6]))
//            $info = file_get_contents(Yii::app()->params['CMS_new']['template'].'/wxgoback.html');
//        return $info;
//    }
    /**
     *影片card
     */
    private static function cardMovie($info)
    {
        preg_match_all('/{movieId:(.*?)}/',$info,$arrMovie);
        //电影card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/movieCard.html');
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
//                $movie_url = str_replace("<!--movieId-->", $movieData['MovieNo'],$movieUrl);
                $movie_url = $movieData['MovieNo'];
                $movie_name = $movieData['MovieNameChs'];
                $movie_img = !empty($movieData['IMG_COVER'][0])?reset($movieData['IMG_COVER'][0]):'https://picture-msdb.wepiao.com/movieDataImages/images/5/7.jpg';
                $movie_img = str_replace('http://','https://',$movie_img);
                //替换card
                $card = str_replace(array( '<!--movie_url-->','<!--movie_name-->','<!--movie_img-->','<!--movie_info-->','<!--movie_type-->','<!--movie_first-->',),
                    array($movie_url,$movie_name,$movie_img, $movie_info, $movie_type, $movie_first, ), $card);
                // 替换详细内容
                $info=str_replace("{movieId:$val}", $card,$info);
            }
        }
        return $info;
    }
    /**
     *演出card
     */
    private static function cardShow($info)
    {
        preg_match_all('/{showId:(.*?)}/',$info,$arrData);
        //影片card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/showCard.html');
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
//                $showUrlData = str_replace("<!--showId-->", $val,$showUrl);
                $showUrlData = $val;
                //替换card
                $card = str_replace(array( '<!--show_img-->', '<!--show_title-->','<!--show_time-->','<!--show_addr-->','<!--show_price-->','<!--show_url-->',),
                    array($show_img,$show_title, $show_time, $show_addr,$show_price,$showUrlData,), $card);
                // 替换详细内容
                $info=str_replace("{showId:$val}", $card,$info);
            }
        }
        return $info;
    }




    //重写删除函数
    public function delete(){
        $this->iStatus=0;
        $this->save();
    }

    /**
     * 落地赞过总数和阅读总数
     */
    public function saveReadAndReply($id)
    {
        $this->getRedis();
        $readNum = 0;
        if($this->redis->exists(self::CMS_READ_NUM.$id))
            $readNum = $this->redis->get(self::CMS_READ_NUM.$id);
        $likeNum = 0;
        if($this->redis->exists(self::CMS_LIKE_LIST.$id)){
            $this->redis->setsDel(self::CMS_LIKE_LIST.$id,'');
//            $likeNum = $this->redis->sCard(self::CMS_LIKE_LIST.$id);
        }
        $readNum = empty($readNum)?0:$readNum;
        $likeNum = empty($likeNum)?0:$likeNum;
        $model = ActiveCms::model()->findByPk($id);
        if($model && ( !empty($readNum)  )){
            if($model->iReads < $readNum)
                $model->iReads = $readNum;
//            if($model->iLikes < $likeNum)
//                $model->iLikes = $likeNum;
            $model->save();
        }
    }

    /**
     * 执行sql，进行落地
     * @param $sql
     * @return mixed
     */
    public static function saveSql($sql)
    {
        return Yii::app()->db_active->createCommand($sql)->execute();
    }


}
