<?php
class LiveShowTemp extends CActiveRecord
{
    public function tableName()
    {
        return 't_live_show_temp';
    }

    public function rules()
    {
        return array(
            array('title, actor, img,start_time, text_2,text_3,text_4,down_href,share_title,share_summary,share_img,end_time,temp_title,temp_url', 'required'),
//            array('share_title', 'length', 'max' => 255),
            array('id,title, actor, img,start_time, text_2,text_3,text_4,down_href,share_title,share_summary,share_img,css,created,updated,end_time,temp_title,temp_url', 'safe'),
            array('id,title, actor, img,start_time, text_2,text_3,text_4,down_href,share_title,share_summary,share_img,css,created,updated,end_time,temp_title,temp_url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [];
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'title' => '直播名称',
            'actor' => '直播人物',
            'img' => '直播头图',
            'start_time' => '直播开始时间',
            'end_time' => '直播结束时间',
            'text_2' => '文案第二行',
            'text_3' => '文案第三行',
            'text_4' => '文案第四行',
            'down_href' => '下载按钮链接',
            'share_title' => '分享标题',
            'share_summary' => '分享描述',
            'share_img' => '分享图',
            'css' => '样式',
            'created' => '创建时间',
            'updated' => '更新时间',
            'temp_title' => '预约提醒标题',
            'temp_url' => '预约提醒链接',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('actor', $this->actor, true);
        $criteria->compare('text_2', $this->text_2, true);
        $criteria->compare('text_3', $this->text_3, true);
        $criteria->compare('text_4', $this->text_4, true);

        $criteria->compare('temp_title', $this->temp_title, true);
        $criteria->compare('temp_url', $this->temp_url, true);
        $criteria->compare('share_title', $this->share_title, true);
        $criteria->compare('share_summary', $this->share_summary, true);
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
        return Yii::app()->db;
    }


    /**
     * 获取弹出框内容
     * @param $id
     * @return string
     */
    public function getDialogInfo($id)
    {
        $str ="";
//        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接：<a target ='_blank' href=\"" . Yii::app()->params['live_show_temp']['local_url'] . "/$id/index.html\">";
//        $str .= Yii::app()->params['live_show_temp']['local_url'] . "/$id/index.html</a></br>";
//        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['live_show_temp']['final_url'] . "/$id/index.html\">";
//        $str .= Yii::app()->params['live_show_temp']['final_url'] . "/$id/index.html</a>";

        $str .= "<a target ='_blank' href=\"" . Yii::app()->params['live_show_temp']['final_url'] . "/$id/index.html\">";
        $str .= Yii::app()->params['live_show_temp']['final_url'] . "/$id/index.html</a>";
        return $str;
    }

    /**
     * 获取默认css
     * @return string
     */
    public function getBaseCss()
    {
        $css = "
.contBox {
    background: #2e2f34;/* 图片下面内容块的背景颜色 */
}
a.btn {
    background: #ffac00;/* 下载app的按钮背景颜色 */
    color: #684B40;/* 下载app的按钮字体颜色 */
}
h1 {
    color: white;/* 主标题字体颜色 */
}
strong {
    color: white;/* 标题之下明星人名字体颜色 */
}
hgroup {
    border:#F28E56 solid 0.13rem; /* 边框颜色、边框粗细、边框风格 */
}
hgroup:after {
    border:#F28E56 solid 0.1rem; /* 边框下面播放icon的圆圈的边框颜色、边框粗细、边框风格 */
    background-color:#211C19; /* 播放icon的背景颜色 */
}
hgroup:before {
    border-left: 1.0rem solid #F28E56;/* 三角颜色 */
}
time {
    color: white;/* 时间整体字体颜色 */
}
time b {
    padding-right: 0;
    color: white;/* 时间月份字体颜色 */
}
time em {
    padding-left: 0;
    color: white;/* 时间小时分钟字体颜色 */
}
h2 {
    color: white;/* 《下载娱票儿APP》的文字的字体颜色 */
}
h3 {
    color: white;/* 《看明星现场》的文字的字体颜色 */
}
h4 {
    color: white;/* 《视频直播》的文字的字体颜色 */
}
";
        return $css;
    }

    /**
     * @tutorial 生成模板
     * @author liulong
     */
    public function saveFile($id='')
    {
        if(empty($id))
            return false;
        $info = LiveShowTemp::model()->findByPk($id);
        $this->strReplaceHtml($info);

    }

    private function strReplaceHtml($info)
    {
        $tempUrl = Yii::app()->params['live_show_temp']['template'];
        $localUrl = Yii::app()->params['live_show_temp']['local_dir'].'/'.$info->id.'/';
        $fileContent = file_get_contents($tempUrl.'/index.html');
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
            Yii::app()->params['live_show_temp']['template'],
            $localUrl
        );

        $fileContent = str_replace(
            [   '====pageTitle====',
                '====mainImg====',
                '====mainTitle====',
                '====mainName====',
                '====year====',
                '====month====',
                '====date====',
                '====hours====',
                '====minutes====',
                '====seconds====',
                '====txtH2====',
                '====txtH3====',
                '====txtH4====',
                '====btnText====',
                '====btnLink====',
                '====shareTitle====',
                '====shareDesc====',
                '====shareLink====',
                '====sharePic====',
                '====pageCss====',
                '====end_time====',
                '====temp_title====',
                '====temp_url====',
                '====start_time====',
            ],
            [
                $info->title,
                $info->img,
                $info->title,
                $info->actor,
                date('Y',$info->start_time),
                date('m',$info->start_time),
                date('d',$info->start_time),
                date('H',$info->start_time),
                date('i',$info->start_time),
                date('s',$info->start_time),
                $info->text_2,
                $info->text_3,
                $info->text_4,
                '下载娱票儿客户端',
                $info->down_href,
                $info->share_title,
                $info->share_summary,
                Yii::app()->params['live_show_temp']['final_url'].'/'.$info->id.'/index.html',
                $info->share_img,
                $info->css,
                $info->end_time,
                $info->temp_title,
                $info->temp_url,
                $info->start_time,
            ], $fileContent);
        file_put_contents($localUrl . "/index.html", $fileContent);
        @chmod(Yii::app()->params['live_show_temp']['local_dir'].'/',0777);
        // 写入推送日志
        Log::model()->logPath(Yii::app()->params['live_show_temp']['local_dir'].'/'.$info->id.'/');
        // return $fileContent;
    }

    public function saveCreateFileList()
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
        @chmod(Yii::app()->params['CMS_new']['local_dir'].'/',0777);

        // 写入日志
        Log::model()->logPath(Yii::app()->params['CMS_new']['local_dir'].'/'.$this->iActive_id.'/');
    }
    /**
     *  @tutorial 创建H5页面
     * @author liulong
     */
    public function createdWxActive($release)
    {

        $tempUrl = Yii::app()->params['CMS_new']['template'];
        $localUrl = Yii::app()->params['CMS_new']['local_dir'].'/'.$this->iActive_id.'/'.$release;
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
        //演出card链接
        $arrShowRelease=['3'=>'http://wechat.show.wepiao.com/detail/onlineId=<!--showId-->',
            '6'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
            '28'=>'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
            '8'=>'wxmovie://showdetail?onlineid=<!--showId-->',
            '9'=>'wxmovie://showdetail?onlineid=<!--showId-->',
            '55'=>'javascript:void(0);',
            '56'=>'javascript:void(0);',
        ];
        $path = Yii::app()->params['CMS_new']['img_cdn_url'];
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
        //版权内容替换
//        $strCopyright = self::getCopyright($this->iCopyright);
        //todo 替换版权
        $info = str_ireplace('src="/uploads', 'src="'.Yii::app()->params['CMS_new']['img_cdn_url'].'/uploads', $info);
        $info = str_ireplace('http://wxadmin.wepiao.com', Yii::app()->params['CMS_new']['img_cdn_url'], $info);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS_new']['template'].'/index.html');
        $fileContent = self::strReplace($fileContent,$path,$release,$info,$localUrl);
    }
    /**
     * @tutorial 音频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createAudio($localUrl,$path,$release)
    {
        //音频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/audio.html');
        $cardFile = str_replace(['<!--sAudio_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sAudio_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS_new']['template'].'/audio_index.html');
        $fileContent = self::strReplace($fileContent,$path,$release,$cardFile,$localUrl);
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
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/photo.html');
        $content = '';
        foreach ($info as $val){
            $content .= str_replace(['<!--sInfo-->','<!--sPhotoImg -->'], [$val['content'],$path.$val['path']], $cardFile);
        }
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS_new']['template'].'/index.html');
        $fileContent = self::strReplace($fileContent,$path,$release,$content,$localUrl);
    }
    /**
     * @tutorial 视频类型模版生成
     * @param unknown $localUrl
     * @author liulong
     */
    private function createVedio($localUrl,$path,$release)
    {
        //视频card替换
        $cardFile = file_get_contents(Yii::app()->params['CMS_new']['template'].'/vedio.html');
        $cardFile = str_replace(['<!--sVideo_link-->','<!--sTitle-->','<!--sSummary-->'], [$this->sVideo_link,$this->sTitle,$this->sSummary], $cardFile);
        //内容替换
        $fileContent=file_get_contents(Yii::app()->params['CMS_new']['template'].'/index.html');
        $fileContent = self::strReplace($fileContent,$path,$release,$cardFile,$localUrl);
    }
    private function strReplace($fileContent,$path,$release,$cardFile='',$localUrl)
    {
        $classifytitle = ActiveFind::model()->getTypeList();
        $classifytitle = json_encode($classifytitle);
        $iCopyright = $this->iType == 1?$this->iCopyright:0;
        $fileContent = str_replace(
            [   '<!--sTitle-->','<!--sSource_head-->', '<!--sSource_name-->', '<!--sSource_summary-->', '<!--content-->',
                '<!--goBack-->','<!--sShare_logo-->','<!--sShare_title-->','<!--sShare_summary-->','<!--sShare_link-->',
                '<!--sShare_otherLink-->','<!--activeId-->','<!--channelId-->','<!--shareData-->','<!--sCover-->',
                '<!--iCopyright-->','<!--classifytitle-->',
            ],
            [   $this->sTitle, $path.$this->sSource_head, $this->sSource_name, $this->sSource_summary, $cardFile,self::getGoBack($release),
                Yii::app()->params['CMS_new']['img_cdn_url'].$this->sShare_logo,$this->sShare_title,$this->sShare_summary,$this->sShare_link,
                $this->sShare_otherLink,$this->iActive_id,$release,$this->share,Yii::app()->params['CMS_new']['img_cdn_url'].$this->sCover,
                $iCopyright,$classifytitle,
            ], $fileContent);
        file_put_contents($localUrl . "/index.html", $fileContent);
       // return $fileContent;
    }
    private function getGoBack($release)
    {
        $info = '';
        if (in_array($release,[3,4,6]))
            $info = file_get_contents(Yii::app()->params['CMS_new']['template'].'/wxgoback.html');
        return $info;
    }
    /**
     *影片card
     */
    private static function cardMovie($info,$movieUrl)
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
                $movie_url = str_replace("<!--movieId-->", $movieData['MovieNo'],$movieUrl);
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
    private static function cardShow($info,$showUrl)
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
                $showUrlData = str_replace("<!--showId-->", $val,$showUrl);
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
