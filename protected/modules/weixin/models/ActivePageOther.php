<?php

/**
 * This is the model class for table "active_page".
 */
class ActivePageOther extends CActiveRecord
{

    public function getDbConnection()
    {
        return Yii::app()->db;
    }

    /**
     * @return string the associated database table sName
     */
    public function tableName()
    {
        return 't_weixin_active_page';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('sPic,sSharePic,iChannel,sStyle,iType,iTime,iMovieId,sContent, sExtend, sNotice,iwx,iqq,imobile,iPreheatEndTime,iEndTime', 'safe'),
            array('sName, sTitle, sRule, sShareTitle, sShareContent,iPreheatEndTime,iEndTime', 'required'),
            array('iDeleted, iCreated, iUpdated,iPreheatEndTime,iEndTime', 'numerical', 'integerOnly' => true),
            array('sName, sTitle, sButtonText', 'length', 'max' => 100),
            array('sFooterText, sFooterLink', 'length', 'max' => 200),
            // @todo Please remove those attributes that should not be searched.
            array(
                'sPic,sSharePic,iChannel,sStyle,iType, sExtend, sNotice,iTime, iActivePageID, sName, sTitle, sRule, iActiveID, sShareTitle, sShareContent, sStyle, sExtend, iDeleted, iCreated, iUpdated,iwx,iqq,imobile,iPreheatEndTime,iEndTime',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation sName and the related
        // class sName for the relations automatically generated below.
        return array(
            'activities' => array(
                self::MANY_MANY,
                'BonusResource',
                't_weixin_active_page_bonus_resource(iAcitvePageID, iResourceID)'
            ),
            'cinemas'=>array(self::HAS_MANY, 'ActivePageCinema', 'iActivePageID'),
        );
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'iActivePageID' => 'ID',
            'sName' => '模板名称',
            'sTitle' => '页面标题',
            'sPic' => '大图',
            'sRule' => '活动规则',
            'sSharePic' => '分享图片',
            'sShareTitle' => '分享标题',
            'sShareContent' => '分享内容',
            'sButtonText' => '按钮文字',
            'sFooterText' => '底部文字 ',
            'sFooterLink' => '底部链接',
            'sStyle' => '样式表',
            'sExtend' => '扩展内容',
            'sNotice' => '注意事项',
            'iDeleted' => '已删除',
            'iCreated' => '创建时间',
            'iUpdated' => '更新时间',
        	'iwx'=>'微信',
        	'iqq'=>'手机QQ',
        	'imobile'=>'移动客户端',
        	'sTempurl'=>'分享链接',
        	'iType'=>'类型',
        	'sContent'=>'内容',
            'iTime'=>'生效时间',
            'iMovieId'=>'影片ID',
            'iPreheatEndTime'=>'预热结束时间',
            'iEndTime'=>'活动结束时间',
            'iChannel'=>'创建渠道',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * TysPical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('iActivePageID', $this->iActivePageID);
        $criteria->compare('sName', $this->sName, true);
        $criteria->compare('sName', $this->sTitle, true);
        $criteria->compare('iType',$this->iType);
        //$criteria->compare('sRule',$this->sRule,true);
        //$criteria->compare('sSharePic',$this->sSharePic,true);
        
        $criteria->compare('sShareTitle', $this->sShareTitle, true);
        $criteria->compare('sShareContent', $this->sShareContent, true);
        //$criteria->compare('iDeleted',$this->iDeleted);
        $criteria->compare('iCreated', strtotime($this->iCreated));
        $criteria->compare('iUpdated', strtotime($this->iUpdated));
        $criteria->compare('iChannel',1);
		
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'iActivePageID DESC',
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
        $this->iUpdated = time();
        if ($this->isNewRecord) {
            $this->iCreated = time();
        }

        return true;
    }
    /**
     * 拼接div内容
     * @param unknown $id
     * @param unknown $wx
     * @param unknown $qq
     * @param unknown $mobile
     */
    public function getDialogInfo($id,$iType,$wx,$qq,$mobile)
    {
    	$type = $iType == '1'?'':'_More';
    	$str ="";

    	if (!empty($id) && !empty($wx)){
    		$str .= "<br>微信促销模板</br>";

    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接:<a target ='_blank' href=\"".Yii::app()->params['active_page']['target_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page']['target_url']."$type/$id/index.html</a></br>";
    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"".Yii::app()->params['active_page']['final_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page']['final_url']."$type/$id/index.html</a>";
    	}
    	if (!empty($id) && !empty($qq)){
    		$str .= "<br>手Q促销模板</br>";
    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接：<a target ='_blank' href=\"".Yii::app()->params['active_page_QQ']['target_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page_QQ']['target_url']."$type/$id/index.html</a></br>";
    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"".Yii::app()->params['active_page_QQ']['final_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page_QQ']['final_url']."$type/$id/index.html</a>";
    	}
    	if (!empty($id) && !empty($mobile)){
    		$str .= "<br>客户端促销模板</br>";
    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接：<a target ='_blank' href=\"".Yii::app()->params['active_page_Mobile']['target_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page_Mobile']['target_url']."$type/$id/index.html</a></br>";
    		$str .="&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"".Yii::app()->params['active_page_Mobile']['final_url']."$type/$id/index.html\">";
    		$str .=Yii::app()->params['active_page_Mobile']['final_url']."$type/$id/index.html</a>";
    	}
    	if (empty($str)){
    		$str ="没有选择任何模板";
    	}
    	return $str;
    }

    // 压缩js文件
    public static function jsCompress($sourceFiles, $targetFile) {
        $strCodes = "// ".date('Y-m-d H:i:s')."\n";
        foreach ($sourceFiles as $key => $value) {
            $strCodes .= "// " . basename($value) . "\n" ;
            $strCodes .= file_get_contents($value) . "\n";
        }
        file_put_contents($targetFile, $strCodes);
    }


    // 生成模板文件:单影片
    public function makeFile($active ='active_page',$json ='')
    {
        $channelType = '';
        if($active == "active_page_QQ")
            $channelType = "qq";
        elseif($active =="active_page_Mobile")
            $channelType = "app";
        else $channelType ="weixin";

        $targetDir = Yii::app()->params[$active]['target_dir'] . '/' . $this->iActivePageID;
        $targetUrl = Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID;
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
            Yii::app()->params['active_page']['template'],
            $targetDir
        );
        // 替换变量
        $fileContent = file_get_contents($targetDir . "/index.html");

        // 活动规则
        $rules = explode("\n", $this->sRule);
        $ruleStr = [];
        foreach ($rules as $r) {
            $ruleStr[] = $r;
        }
        $ruleStr = json_encode($ruleStr);
        // 底部内容
        if ($this->sFooterLink) {
            $sFooterContent = '<a href="' . $this->sFooterLink . '">' . $this->sFooterText . '</a>';
        } else {
            $sFooterContent = $this->sFooterText;
        }

        // 置顶影城
        $arrTop = array();
        if ($this->cinemas) {
            foreach ($this->cinemas as $key => $value) {
//                $bson = BsonBaseCinema::model()->findByAttributes(array('CinemaNo' => $value->iCinemaID));
                $bson =false;
                if ($bson) {
                    $arrTop[] = $bson->CinemaName;
                }
            }
        }
        //新代码：需要区分城市等信息
        $arrData = [];
        $arrCityData=[];
        $arrActiveType=[];
        $movieId='';
        $activeId = [];

        foreach (ActivePageBonusResource::model()->findAllByAttributes(array(
            'iActivePageID' => $this->iActivePageID
        )) as $resource){
            $activeId[] =  $resource->iResourceID;
        }
        $activeId = implode(',',$activeId);
        //写入html
        $fileContent = str_replace(array(
            '[[[movieId]]]',
            '[[[sTitle]]]',
            '[[[sStyle]]]',
            '[[[sPic]]]',
            '[[[sRule]]]',
            '[[[sExtend]]]',
            '[[[sSharePic]]]',
            '[[[sShareTitle]]]',
            '[[[sShareContent]]]',
            '[[[sButtonText]]]',
            '[[[sFooterContent]]]',
            '[[[sNotice]]]',
            '[[[Top]]]',
            '[[[sTempurl]]]',
            '[[[channelType]]]',
            '[[[sDate]]]',
            '[[[iMovieId]]]',
            '[[[sActiveId]]]',
            '[[[iPreheatEndTime]]]',
            '[[[iEndTime]]]',
        ), array(
            $this->iMovieId,
            $this->sTitle,
            $this->sStyle,
            'images/'.$this->sPic,
            $ruleStr,
            $this->sExtend,
            $targetUrl . '/images/' . $this->sSharePic,
            $this->sShareTitle,
            $this->sShareContent,
            $this->sButtonText,
            $sFooterContent,
            $this->sNotice ? $this->sNotice : '',
            json_encode($arrTop),
            $this->sTempurl,
            $channelType,
           date('m月d日 H:i:s',$this->iTime),
            $this->iMovieId,
            $activeId,
            $this->iPreheatEndTime,
            $this->iEndTime,
        ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($targetDir . "/index.html", $fileContent);


        //开始拆分储存文件
        foreach($arrCityData as $keys=>&$valcity){
            $valcity['movieId'] = $movieId;
        }
        //储存所有城市信息
        $arrCityData = self::getCityPinYin($arrCityData);
        file_put_contents($targetDir . "/cinema_city.json", json_encode($arrCityData));
        //储存活动信息
        file_put_contents($targetDir . "/cinema_active.json", json_encode($arrActiveType));

        //储存每个城市的影院（活动信息）
        if(!empty($arrData))
            foreach($arrData as $cityKey => $cityVal){
                file_put_contents($targetDir . "/cinema_data_".$cityKey.".json", json_encode($cityVal));
            }
        $json = '';
        // 写入日志
        Log::model()->logPath(Yii::app()->params[$active]['target_dir'] . '/' . $this->iActivePageID.'/');
        //记录-刷新CDN
        $arrCdn = [
            Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID.'/index.html',
            Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID.'/images/'.$this->sSharePic,
            Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID.'/images/'.$this->sPic,
        ];
        FlushCdn::setUrlToRedis($arrCdn);
//        FlushCdn::setUrlToRedis(Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID, Yii::app()->params[$active]['target_dir'] . '/' . $this->iActivePageID);
        return $json;
    }


    // 生成模板文件:单影片
    public function makeFileOld($active ='active_page',$json ='')
    {
       //echo Yii::app()->params['active_page']['template'];exit;
        $channelType = '';
        if($active == "active_page_QQ")
            $channelType = "qq";
        elseif($active =="active_page_Mobile")
            $channelType = "app";
        else $channelType ="weixin";

        $targetDir = Yii::app()->params[$active]['target_dir'] . '/' . $this->iActivePageID;
        $targetUrl = Yii::app()->params[$active]['final_url'] . '/' . $this->iActivePageID;
        // 从静态模板复制内容
        CFileHelper::copyDirectory(
           Yii::app()->params['active_page']['template'],
            $targetDir
        );
        // 替换变量
        $fileContent = file_get_contents($targetDir . "/index.html");

        // 活动规则
        $rules = explode("\n", $this->sRule);
        $ruleStr = "";
        foreach ($rules as $r) {
            $ruleStr .= "<li>{$r}</li>";
        }
        // 底部内容
        if ($this->sFooterLink) {
            $sFooterContent = '<a href="' . $this->sFooterLink . '">' . $this->sFooterText . '</a>';
        } else {
            $sFooterContent = $this->sFooterText;
        }

        // 置顶影城
        $arrTop = array();
        if ($this->cinemas) {
            foreach ($this->cinemas as $key => $value) {
//                $bson = BsonBaseCinema::model()->findByAttributes(array('CinemaNo' => $value->iCinemaID));
                $bson =false;
                if ($bson) {
                    $arrTop[] = $bson->CinemaName;
                }
            }
        }
        //新代码：需要区分城市等信息
        $arrData = [];
        $arrCityData=[];
        $arrActiveType=[];
        $movieId='';
        foreach (ActivePageBonusResource::model()->findAllByAttributes(array(
            'iActivePageID' => $this->iActivePageID
        )) as $iActiveId) {
            //每个活动下面的数据（包含影院）
            $jsonContent = static::getActivePageInfo($iActiveId->iResourceID);
            //echo json_encode($jsonContent);exit;
            if(empty($movieId)){
                if(!empty($jsonContent['activity_info']['movieList'][0]))
                    $movieId = $jsonContent['activity_info']['movieList'][0];
            }
            if(!empty($jsonContent['cinemas_city']) && is_array($jsonContent['cinemas_city'])){
                foreach($jsonContent['cinemas_city'] as $key=>$valCinema){
                   // if($iActiveId->iResourceID == '646'){
                   //     echo json_encode($jsonContent['cinemas_city']);exit;
                   // }
                    $arrData[$valCinema['city_id']][$iActiveId->iResourceID]['active_id'] = $iActiveId->iResourceID;
                    $arrData[$valCinema['city_id']][$iActiveId->iResourceID]['cinemas'][] = $valCinema['cinemas'];
                    $arrCityData[$valCinema['city_id']] = ['city_id'=>$valCinema['city_id'],'city_name'=>$valCinema['city_name'],'city_longitude'=>$valCinema['city_longitude'],'city_latitude'=>$valCinema['city_latitude']];
                }
            }
            if(!empty($jsonContent['activity_info']))
                $arrActiveType[$iActiveId->iResourceID] = $jsonContent['activity_info'];
        }


        sleep(2);

        //写入html
        $fileContent = str_replace(array(
            '[[[movieId]]]',
            '[[[sTitle]]]',
            '[[[sStyle]]]',
            '[[[sPic]]]',
            '[[[sRule]]]',
            '[[[sExtend]]]',
            '[[[sSharePic]]]',
            '[[[sShareTitle]]]',
            '[[[sShareContent]]]',
            '[[[sButtonText]]]',
            '[[[sFooterContent]]]',
            '[[[sNotice]]]',
            '[[[Top]]]',
            '[[[sTempurl]]]',
            '[[[channelType]]]',
            '[[[iTime]]]',
            '[[[iMovieId]]]',
        ), array(
            $movieId,
            $this->sTitle,
            $this->sStyle,
            $this->sPic,
            $ruleStr,
            $this->sExtend,
            $targetUrl . '/images/' . $this->sSharePic,
            $this->sShareTitle,
            $this->sShareContent,
            $this->sButtonText,
            $sFooterContent,
            $this->sNotice ? ('<div class="notice"><b>注意事项：</b>' . $this->sNotice . '</div>') : '',
            json_encode($arrTop),
            $this->sTempurl,
            $channelType,
            $this->iTime,
            $this->iMovieId,
        ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($targetDir . "/index.html", $fileContent);


        //开始拆分储存文件
        foreach($arrCityData as $keys=>&$valcity){
            $valcity['movieId'] = $movieId;
        }
        //储存所有城市信息
        $arrCityData = self::getCityPinYin($arrCityData);
        file_put_contents($targetDir . "/cinema_city.json", json_encode($arrCityData));
        //储存活动信息
        file_put_contents($targetDir . "/cinema_active.json", json_encode($arrActiveType));

        //储存每个城市的影院（活动信息）
        if(!empty($arrData))
        foreach($arrData as $cityKey => $cityVal){
        file_put_contents($targetDir . "/cinema_data_".$cityKey.".json", json_encode($cityVal));
        }
            $json = '';
        return $json;
    }
    // 生成模板文件
    public function makeFileMore($active ='active_page',$json='')
    {
        $channelType = '';
        if($active == "active_page_QQ")
            $channelType = "qq";
        elseif($active =="active_page_Mobile")
            $channelType = "app";
        else $channelType ="weixin";
        $content = json_decode($this->sContent,true);
    	$targetDir = Yii::app()->params[$active]['target_dir'] . '_More/' . $this->iActivePageID;
    	$targetUrl = Yii::app()->params[$active]['final_url'] . '_More/' . $this->iActivePageID;
    	// 从静态模板复制内容
    	CFileHelper::copyDirectory(
    			Yii::app()->params['active_page']['template']. '_More/',
    			$targetDir
    	);
     	$fileContent = file_get_contents($targetDir . "/index.html");
//     	// 活动规则
     	$rules = explode("\n", $this->sRule);
     	$ruleStr = "";
     	foreach ($rules as $r) {
     		$ruleStr .= "<li>{$r}</li>";
     	}

//     	// 底部内容
     	if ($this->sFooterLink) {
     		$sFooterContent = '<a href="' . $this->sFooterLink . '">' . $this->sFooterText . '</a>';
     	} else {
     		$sFooterContent = $this->sFooterText;
     	}
    
//     	// 置顶影城
     	$arrTop = array();

     	$fileContent = str_replace(array(
       			'[[[sTitle]]]',
     			'[[[sStyle]]]',
     			'[[[sPic]]]',
     			'[[[sRule]]]',
     			'[[[sExtend]]]',
     			'[[[sSharePic]]]',
     			'[[[sShareTitle]]]',
     			'[[[sShareContent]]]',
     			'[[[sButtonText]]]',
     			'[[[sFooterContent]]]',
     			'[[[sNotice]]]',
     			'[[[Top]]]',
     			'[[[sTempurl]]]',
                '[[[channelType]]]',
     	), array(
     			$this->sTitle,
     			$this->sStyle,
     			$this->sPic,
     			$ruleStr,
     			$this->sExtend,
     			$targetUrl . '/images/' . $this->sSharePic,
     			$this->sShareTitle,
   			$this->sShareContent,
     			$this->sButtonText,
     			$sFooterContent,
     			$this->sNotice ? ('<div class="notice"><b>注意事项：</b>' . $this->sNotice . '</div>') : '',
     			json_encode($arrTop),
     			$this->sTempurl,
                $channelType,
     	), $fileContent);
//     	// 将变量写入静态模板
     	file_put_contents($targetDir . "/index.html", $fileContent);
    	// 写入影城数据
    	if (!is_file($json)){
	    	foreach (ActivePageBonusResource::model()->findAllByAttributes(array(
	    			'iActivePageID' => $this->iActivePageID
	    	)) as $ab) {
	    		// 从api取得影城数据
	    		//$jsonContent = file_get_contents(
	    		//		WX_APIHOST . '/wx/banner/getCinemasByActivity?iActivityId=' . $ab->iResourceID
	    		//);
	    		//$jsonContent = json_decode($jsonContent, true);
	    		//新数据源
	    		$jsonContent = static::getActivePageInfo($ab->iResourceID);
	    		if ($jsonContent) {
                    if(!empty($content))
                        foreach ($content as &$movie){
                            if (empty($jsonContent['activity_info']['movieList']) || in_array($movie['movie_id'], $jsonContent['activity_info']['movieList'])){
                                $activePage= $jsonContent['activity_info'];
                                $activePage['cinemas_city']= !empty($jsonContent['cinemas_city'])?array_values($jsonContent['cinemas_city']):'';
                                $movie['active'][] = $activePage;
                                //$movie['cinemas_city'] = $jsonContent['cinemas_city'];
                                $movie['starTime'] = $jsonContent['activity_info']['starTime'] < $movie['starTime']?$jsonContent['activity_info']['starTime']:$movie['starTime'];
                                $movie['endTime'] = $jsonContent['activity_info']['endTime'] > $movie['endTime']?$jsonContent['activity_info']['endTime']:$movie['endTime'];
                                //$movie['img'] = $movie['img']['img'];
                                unset($movie['imgData']);
                            }
                        }
	    		}
	    	}
            $this->getCityArray($content);
	    	file_put_contents($targetDir . "/cinema_data.json", json_encode($content));
	    	if (is_file($targetDir . "/cinema_data.json"))
	    		$json = $targetDir . "/cinema_data.json";
    	}
    	else {
    		UploadFiles::copyFile($json, $targetDir . "/cinema_data.json");
    		if (is_file($targetDir . "/cinema_data.json"))
    			$json = $targetDir . "/cinema_data.json";
    	}
    	return $json;
    }

    /**
     * @param $arrData
     * @return array
     */
    public function getCityArray(&$arrData)
    {
        if(empty($arrData))return[];
        //循环影片
        foreach($arrData as &$movieVal){
            //循环活动
            if(!empty($movieVal['active']))
                foreach($movieVal['active'] as &$active){
                    //循环影院
                    $cityData = [];
                    if(!empty($active['cinemas_city']))
                    foreach($active['cinemas_city'] as &$cinema){
                        if(empty($cityData[$cinema['city_id']])) {
                            $cityData[$cinema['city_id']] = $cinema;
                        }else{
                            $cityData[$cinema['city_id']]['cinemas'][] = $cinema['cinemas'][0];
                        }
                    }
                    $cityData = array_values($cityData);
                    //echo json_encode($cityData);exit;
                    $active['cinemas_city'] = $cityData;
                    //echo json_encode($active);exit;
                }
        }
    }

    /**
     * 拉数据
     * @param unknown $resId
     * @return Ambigous <multitype:, Ambigous, multitype:unknown >
     */
    public static function getActivePageInfo($resId)
    {
        //活动ID，获取活动的内容
        $arrActive = static::getActiveInfo($resId);
        //if($resId == '646'){
           // echo json_encode($arrActive);exit;
        //}
        $arrData = [];
        if ($arrActive)
        {
            $arrData['activity_info']['starTime'] = $arrActive['startTime'];
            $arrData['activity_info']['endTime'] = $arrActive['endTime'];
            $arrData['activity_info']['type'] = $arrActive['type'];
            $arrData['activity_info']['status'] = $arrActive['status'];
            $arrData['activity_info']['price'] = self::getPrice($arrActive['priceList'], $arrActive['type']);
            $arrData['activity_info']['movieList'] = $arrActive['movieList'];
            foreach ($arrActive['cinemaList'] as $val){
                $cinema = self::getCinemaInfo($val);
                if ($cinema){
                    $arrData['cinemas_city'][$val]['cinemas'][] = self::getCityInfo($cinema);
                    if (empty($arrData['cinemas_city'][$val]['city_name'])){
                        $arrData['cinemas_city'][$val]['city_id'] = $cinema['cityId'];
                        $arrData['cinemas_city'][$val]['city_name'] = $cinema['cityName'];
                        $arrData['cinemas_city'][$val]['city_longitude'] = $cinema['longitude'];
                        $arrData['cinemas_city'][$val]['city_latitude'] = $cinema['latitude'];
                    }
                }
            }
        }
        //if($resId == '646'){
        //     echo json_encode($arrData);exit;
        // }

        return $arrData;
    }
    /**
     * 获取活动内容
     * @param unknown $resId
     * @return multitype:|Ambigous <multitype:, mixed, unknown>
     */
    public static function getActiveInfo($resId)
    {
        if(!$resId) return [];
        $url = Yii::app()->params['baymax_active_java'].$resId;
        $arrData = file_get_contents($url);
        $arrData = json_decode($arrData,true);
        if ($arrData['ret'] == 0)
            $arrData = $arrData['data'];
        else $arrData =[];
        return $arrData;
    }
    /**
     * 获得相应的价格
     * @param unknown $arrPrice
     * @param unknown $iType
     * @return mixed
     */
    private static function getPrice($arrPrice,$iType)
    {
        foreach ($arrPrice as &$val)
            $val = intval($val['price']);
        if ($iType == 1)
            $arrPrice = max($arrPrice);
        else 
            $arrPrice = min($arrPrice);
        return $arrPrice;
    }
    /**
     * 获取影院信息
     * @param unknown $cinemaId
     * @return Ambigous <multitype:, mixed>
     */
    private static function getCinemaInfo($cinemaId)
    {
        $strContent = file_get_contents("http://commoncgi.wepiao.com/data/v5/cinemas/100/info_cinema_$cinemaId.json");
        $cinema = [];
        if(preg_match("/^MovieData\.set\(\"\w+\",(.*)\);/ius", $strContent, $arr)){
            $cinema = json_decode($arr[1],1);
        }
        if (!empty($cinema['info'])){
            $cinema =$cinema['info'];
            $cinema['cityId'] = !empty($cinema['city_id'])?$cinema['city_id']:10;
            $cinema['cityName'] =explode('>>',$cinema['area_name']);
            $cinema['cityName'] = !empty($cinema['cityName'][1])?$cinema['cityName'][1]:'';
            if (empty($cinema['cityId']))
                $cinema['cityId'] =10;
            if (empty($cinema['cityName']))
                $cinema['cityName'] ='北京';
        }else $cinema =[];
        return $cinema;
    }
    /**
     * 字段整理
     * @param unknown $arrCinema
     */
    private static function getCityInfo($arrCinema)
    {
        $area_name = explode('>>',$arrCinema['area_name']);
        $area_name = $area_name[count($area_name)-1];
        $arrData = [];
        $arrData['cinema_id'] = $arrCinema['id'];
        $arrData['cinema_name'] = $arrCinema['name'];
        $arrData['cinema_addr'] = $arrCinema['addr'];
        $arrData['dis_name'] = $area_name;
        $arrData['cinema_longitude'] = $arrCinema['longitude'];
        $arrData['cinema_latitude'] = $arrCinema['latitude'];
        return $arrData;
    }

    /**
     * @param $arrData
     * @return mixed
     */
    private static function getCityPinYin($arrData)
    {
        if(empty($arrData))
            return$arrData;
        $cityList = self::getAllCity();
        foreach($arrData as $key=>&$val){
            $val['pinyin'] = !empty($cityList[$val['city_id']]['pingyin'])?$cityList[$val['city_id']]['pingyin']:'';
        }
        return $arrData;
    }

    /**
     * @param $arrData
     * @return mixed|string
     */
    public static function getAllCity()
    {
        $url ="http://commoncgi.wepiao.com/common/city/list";
        $postData=['channelId'=>500];
        $arrData = Https::getPost($postData,$url);
        $arrData = json_decode($arrData,1);
        $arrData = !empty($arrData['data']['list'])?$arrData['data']['list']:'';
        return $arrData;
    }
}
