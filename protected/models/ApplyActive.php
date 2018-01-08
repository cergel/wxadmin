<?php
Yii::import('ext.RedisManager', true);

/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 15:48
 */
class ApplyActive extends CActiveRecord
{

    const APPLY_ACTIVE_KEY = "apply_active_data_";
    const APPLY_ACTIVE_KEY_TIME = 864000;
    private $redis;
    public $apply_status = '';
    private $movieInfoMore = []; //影片信息

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_apply_active';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('type,title,picture,platform,start_display,end_display,is_form,detail,start_apply,end_apply,share_icon,share_title,share_describe,share', 'required'),
            array('question,id,type,title,picture,tags,p_type,price,address,is_remark,remark,support,start_display,end_display,is_form,detail,start_apply,end_apply,share_icon,share_title,share_describe,create_time,update_time,support_count', 'safe'),
            array('question,id,type,title,picture,tags,p_type,price,address,is_remark,remark,support,start_display,end_display,is_form,detail,start_apply,end_apply,share_icon,share_title,share_describe,create_time,update_time,support_count', 'safe', 'on' => 'search')
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
            'platform' => array(self::HAS_MANY, 'ApplyActivePlatform', 'a_id'),
            'share' => array(self::HAS_MANY, 'ApplyActiveShare', 'a_id'),
        );
    }


    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => '活动分类',
            'title' => '活动标题',
            'picture' => '图片',
            'tags' => '标签',
            'p_type' => '价格',
            'start_display' => '活动开始展示时间',
            'end_display' => '活动结束展示时间',
            'address' => '活动地点',
            'is_form' => '报名设置',
            'detail' => '活动详情',
            'start_apply' => '活动报名开始时间',
            'end_apply' => '活动报名结束时间',
            'support' => '注水数',
            'platform' => '生成页面平台',
            'share_icon' => '分享图标',
            'share_title' => '分享标题',
            'share_describe' => '分享内容',
            'share' => '分享平台',
            'apply_status' => '报名状态',
            'support_count' => '真实报名人数',
            'question'=>'报名资格(答题)',
        );
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('picture', $this->picture);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('p_type', $this->p_type);
        $criteria->compare('price', $this->price);
        $criteria->compare('address', $this->address);
        $criteria->compare('is_remark', $this->is_remark);
        $criteria->compare('remark', $this->remark);
        $criteria->compare('support', $this->support);
        $criteria->compare('start_display', $this->start_display);
        $criteria->compare('end_display', $this->end_display);
        $criteria->compare('is_form', $this->is_form);
        $criteria->compare('detail', $this->detail);
        $criteria->compare('start_apply', $this->start_apply);
        $criteria->compare('end_apply', $this->end_apply);
        $criteria->compare('share_icon', $this->share_icon);
        $criteria->compare('share_title', $this->share_title);
        $criteria->compare('share_describe', $this->share_describe);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('support_count', $this->update_time);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
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

    public function beforeSave()
    {
        if ($this->p_type == 0) {
            $this->price = '';
        } else {
            $this->price = json_encode($this->price);
        }
        if ($this->start_display && !is_numeric($this->start_display)) {
            $this->start_display = strtotime($this->start_display);
        }
        if ($this->end_display && !is_numeric($this->end_display)) {
            $this->end_display = strtotime($this->end_display);
        }
        if ($this->start_apply && !is_numeric($this->start_apply)) {
            $this->start_apply = strtotime($this->start_apply);
        }
        if ($this->end_apply && !is_numeric($this->end_apply)) {
            $this->end_apply = strtotime($this->end_apply);
        }
        $this->update_time = time();
        if ($this->isNewRecord) {
            $this->create_time = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if ($this->start_apply) {
            $this->start_apply = $this->int2date($this->start_apply);
        }
        if ($this->end_apply) {
            $this->end_apply = $this->int2date($this->end_apply);
        }
        if ($this->start_display) {
            $this->start_display = $this->int2date($this->start_display);
        }
        if ($this->end_display) {
            $this->end_display = $this->int2date($this->end_display);
        }
    }

    /**
     *    自动删除关联
     */
    public function afterDelete()
    {
        parent::afterDelete();
        ApplyActivePlatform::model()->deleteAllByAttributes(
            array('a_id' => $this->id)
        );
        ApplyActiveShare::model()->deleteAllByAttributes(
            array('a_id' => $this->id)
        );
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i", $time);
    }

    public function getAllType()
    {
        return array(
            '1' => '福利活动',
            '2' => '有奖活动',
            '3' => '粉丝招募',
            '4' => '影展观影',
        );
    }

    public function getType($type = '')
    {
        $array = $this->getAllType();
        return !empty($array[$type]) ? $array[$type] : $array;
    }

    /**
     * @param string $id
     * @return array
     */
    public function getPlatformKey($id = '')
    {
        $array = ['3' => '微信电影票', '28' => '手q', '8' => 'IOS', '9' => 'Android'];
        return !empty($array[$id]) ? $array[$id] : $array;
    }

    /**
     * @param string $id
     * @return array
     */
    public function getShareKey($id = '')
    {
        $array = ['1' => '新浪微博', '2' => 'QQ空间', '6' => '微信好友', '7' => '微信朋友圈'];
        return !empty($array[$id]) ? $array[$id] : $array;
    }

    /**
     * @param $support
     * @return int
     */
    public function getApplyNum($id)
    {
        $sql = "select support_count from t_apply_active where id=" . $id;
        $list = Yii::app()->db_active->createCommand($sql)->queryAll();
        foreach ($list as $key => $num) {
            $applyNum = $num['support_count'];
        }
        return $applyNum;
    }

    public function getApplyRecord($id)
    {
        $url = '/applyRecord/index/' . $id . '?type=view';
        return $url;
    }

    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['cms_active_new']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    public function saveCache()
    {
        $this->setRedis();
        $sql = "select id,start_apply,end_apply,end_display,support,is_form,is_remark,remark from t_apply_active order by update_time desc";
        $info = Yii::app()->db_active->createCommand($sql)->queryAll();
        $sql = "select a_id,platform from t_apply_active_platform";
        $platform = Yii::app()->db_active->createCommand($sql)->queryAll();
        $arrData = [];

        foreach ($info as $key => $val) {
            $arrPlatform = [];
            foreach ($platform as $k => $value) {
                if ($val['id'] == $value['a_id']) {
                    $arrPlatform[] = $value['platform'];
                }
            }
            $arrData[$val['id']] = ['id' => $val['id'], 'start_apply' => $val['start_apply'], 'end_apply' => $val['end_apply'], 'end_display' => $val['end_display'], 'support' => $val['support'], 'is_form' => $val['is_form'], 'is_remark' => $val['is_remark'], 'remark' => $val['remark'], 'platform' => $arrPlatform];
        }
        //写入缓存
        foreach ($arrData as $key => $val) {
            $this->redis->set(self::APPLY_ACTIVE_KEY . $key, json_encode($val));
            $this->redis->expire(self::APPLY_ACTIVE_KEY . $key, self::APPLY_ACTIVE_KEY_TIME);
        }
        return $arrData;
    }

    /**
     * @tutorial 生成模板文件
     * @author liulong
     */
    public function makeFile()
    {
        $arrData = [];
        if(!empty($this->question)){
            $questionModel = QuestionSet::model()->findByPk($this->question);
            if(!empty($questionModel) && !empty($questionModel->question)){
                if(is_array($questionModel->question)){
                    foreach($questionModel->question as $val){
                        $lin = [];
                        $lin['title'] = $val->title;
                        $lin['option1'] = $val->option1;
                        $lin['option2'] = $val->option2;
                        $lin['option3'] = $val->option3;
                        $lin['true_radio'] = $val->true_radio;
                        $arrData['question'][] = $lin;
                    }
                }
                $arrData['image'] =   'https://appnfs.wepiao.com'.$questionModel->pic;
                $arrData['question_num'] =  $questionModel->num;
            }
        }
        $str = json_encode($arrData);
        $localPath = Yii::app()->params['apply_active']['local_dir'] . '/' . $this->id;
        if (is_dir($localPath)) {
            $this->delTemplateFile($localPath);
        }
        $postfix=[0=>"/index.html",1=>"/share.html"];
        if ($this->platform) {
            foreach ($this->platform as $val) {
                self::saveFile($val,$postfix,$str);
            }
        }
        self::saveFile(0,$postfix,$str);
        // 写入日志
        Log::model()->logPath(Yii::app()->params['apply_active']['local_dir'] . '/' . $this->id . '/');
    }

    public function delTemplateFile($localPath, $dir = "/")
    {
        $localDir = $localPath . $dir;
//        echo $localDir;
        //是否是文件夹
        if (is_dir($localDir)) {
            //打开目录句柄返回目录句柄的 resource
            if ($handle = opendir($localDir)) {
                //从目录句柄中的 resource读取条目
                //返回目录中下一个文件的文件名
                while (($file = readdir($handle)) !== false) {
                    if ($file != "." && $file != "..") {
                        $localUrl = $localDir . $file;
                        if ((is_dir($localUrl))) {
                            $this->delTemplateFile($localPath, $dir . $file . "/");
                        } else {
                            unlink($localPath . $dir . $file);
                        }
                    }
                }
                rmdir($localDir);
            }
            closedir($handle);
        }
    }

    private function saveFile($channelId,$postfixs,$str)
    {
        $targetDir = Yii::app()->params['apply_active']['local_dir'] . '/' . $this->id . '/' . $channelId;
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
            Yii::app()->params['apply_active']['template'],
            $targetDir
        );
        $targetDirs=[0=>$targetDir.$postfixs[0],1=>$targetDir.$postfixs[1]];

        //处理内容
        $detail = str_replace(['src="/uploads/'], ['src="https://appnfs.wepiao.com/uploads/'], $this->detail);
        //处理card
        if(!empty($channelId)){
            $detail = self::cardMovie($detail,Yii::app()->params['movie_card_url'][$channelId]);
        }else{
            $detail = self::cardMovie($detail,'');
        }
        foreach($targetDirs as $key=>$val){
            $targetDir = $val;
            $modalConfig = $this->isShowForm($this->is_form, $this->is_remark);
            $channelType = $this->getChannel($channelId);
            $type = $this->getType($this->type);
            $arrPrice = json_decode($this->price);
            $share = implode(',', $this->share);
            if ($this->p_type == 0) {
                $price = 0;
            } else if ($this->p_type == 1) {
                $price = $arrPrice[0];
            } else {
                $price = $arrPrice[1] . "-" . $arrPrice[2];
            }
            $start_display = explode(' ', $this->start_display);
            $end_display = explode(' ', $this->end_display);
            if ($start_display[0] == $end_display[0]) {
                $display = $start_display[0] . " " . $start_display[1] . " - " . $end_display[1];
            } else {
                $display = $this->start_display . " - " . $this->end_display;
            }
            // 替换变量
            $fileContent = file_get_contents($targetDir);
            $fileContent = str_replace(array(
                '[[[sTitle]]]',
                '[[[channelType]]]',
                '[[[sSharePic]]]',
                '[[[sShareTitle]]]',
                '[[[sShareContent]]]',
                '[[[modalConfig]]]',
                '[[[activeId]]]',
                '[[[channelId]]]',
                '[[[activPrice]]]',
                '[[[activeDate]]]',
                '[[[activePlace]]]',
                '[[[activeType]]]',
                '[[[activeTags]]]',
                '[[[activeTitle]]]',
                '[[[acitveCover]]]',
                '[[[activeNeedUserInfo]]]',
                '[[[appShareChannel]]]',
                '[[[articleContent]]]',
                '[[[question]]]',
            ), array(
                '娱票儿 - 活动报名',
                $channelType,
                'https://appnfs.wepiao.com' . $this->share_icon,
                $this->share_title,
                $this->share_describe,
                $modalConfig,
                $this->id,
                $channelId,
                $price,
                $display,
                $this->address,
                $type,
                $this->tags,
                $this->title,
                'https://appnfs.wepiao.com' . $this->picture,
                $this->is_form,
                $share,
                $detail,
                $str
            ), $fileContent);
            // 将变量写入静态模板
//            if(empty($channelId)){
//                echo $fileContent;exit;
//            }
            file_put_contents($targetDir, $fileContent);
        }
    }

    public function getChannel($channel)
    {
        switch ($channel) {
            case 3:
                return 'weixin';
            case 8:
                return 'app';
            case 9:
                return 'app';
            case 28:
                return 'qq';
            case 0:
                return '0';
        }
    }

    public function isShowForm($is_form, $is_remark)
    {
        if ($is_form && $is_remark) {
            $formConfig = [
                'name' => $this->remark,
                'type' => 'textArea',
                'defaultValue' => '',
                'placeHolder' => '请填写'
            ];
            $formConfig = '[' . json_encode($formConfig) . ']';
        } else {
            $formConfig = '[' . '' . ']';
        }
        return $formConfig;
    }

    /**
     * 拼接div内容
     * @param unknown $id
     * @param unknown $wx
     * @param unknown $qq
     * @param unknown $mobile
     */
    public function getTemplateUrl($platform, $id)
    {
        $str = "";
        if (is_array($platform)) {
            foreach ($platform as $platforms) {
                $str .= $this->getPlatformKey($platforms['platform']) . "<br>";
                $str .= "&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接:<a target ='_blank' href=\"" . Yii::app()->params['apply_active']['local_url'] . "/$id/{$platforms['platform']}/index.html\">";
                $str .= Yii::app()->params['apply_active']['local_url'] . "/$id/{$platforms['platform']}/index.html</a></br>";
                $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['apply_active']['final_url'] . "/$id/{$platforms['platform']}/index.html\">";
                $str .= Yii::app()->params['apply_active']['final_url'] . "/$id/{$platforms['platform']}/index.html</a></br>";
            }
        }
        if (count($platform)) {
            $str .= "落地页<br>";
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接:<a target ='_blank' href=\"" . Yii::app()->params['apply_active']['local_url'] . "/$id/0/index.html\">";
            $str .= Yii::app()->params['apply_active']['local_url'] . "/$id/0/index.html</a></br>";
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['apply_active']['final_url'] . "/$id/0/index.html\">";
            $str .= Yii::app()->params['apply_active']['final_url'] . "/$id/0/index.html</a></br>";
        }
        if (empty($str)) {
            $str = "没有选择任何模板";
        }
        return $str;
    }


    public function getApplyStatus($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        if ($start > time()) {
            return '未开始';
        } elseif (time() > $end) {
            return '已结束';
        } else {
            return '进行中';
        }
    }

    public static function saveSql($sql)
    {
        return Yii::app()->db_active->createCommand($sql)->execute();
    }

    /**
     * 答题
     * @param $str
     * @return int|string
     */
    public function getQuestion($id)
    {
        $questionModel = QuestionSet::model()->findByPk($id);
        if(!empty($questionModel->num)){
            echo $questionModel->num;
        }else {
            return '-';
        }
    }

    /**
     *影片card
     */
    private function cardMovie($info,$movieUrl)
    {
        //电影card替换
        $cardFile = file_get_contents(Yii::app()->params['apply_active']['template'].'/movieCard.html');
        if(empty($this->movieInfoMore)){
            preg_match_all('/{movieId:(.*?)}/',$info,$arrMovie);
            if (count($arrMovie[1]) >1)
                $movieDataAll = Movie::model()->getMovieInfo(implode('|',$arrMovie[1]));
            elseif(count($arrMovie[1]) == 1) $movieDataAll[$arrMovie[1][0]] =Movie::model()->getMovieInfo($arrMovie[1][0]);
            else $movieDataAll =[];

            $this->movieInfoMore[1] = $arrMovie[1];
            $this->movieInfoMore[2] = $movieDataAll;
        }else{
            $arrMovie[1] = $this->movieInfoMore[1];
            $movieDataAll = $this->movieInfoMore[2];
        }

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
                $movie_url = '#'.$movieData['MovieNo'];
                $movie_name = $movieData['MovieNameChs'];
                $movie_img = !empty($movieData['IMG_COVER'][0])?reset($movieData['IMG_COVER'][0]):'https://picture-msdb.wepiao.com/movieDataImages/images/5/7.jpg';
                $movie_img = str_replace('http://','https://',$movie_img);
                //替换card
                $card = str_replace(array( '[[[movie_url]]]','[[[movie_name]]]','[[[movie_img]]]','[[[movie_info]]]','[[[movie_type]]]','[[[movie_first]]]',),
                    array($movie_url,$movie_name,$movie_img, $movie_info, $movie_type, $movie_first, ), $card);
                // 替换详细内容
                $info=str_replace("{movieId:$val}", $card,$info);
            }
        }
        return $info;
    }
}