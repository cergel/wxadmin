<?php
class DiscoveryBanner extends CActiveRecord
{

    /**
     * todo:
     *      add iBaseCount
     *      add 来源
     *      add content & description
     *      自定义标签
     */

    const TYPE_ACTIVITY = 1; // 活动
    const TYPE_TOPIC    = 2; // 专题
    const TYPE_GALLERY  = 3; // 图册
    const TYPE_VIDEO    = 4; // 视频
    const TYPE_CONTENT  = 5; // 图文


    const CATEGORY_1 = 1001; // 本周约啥
    const CATEGORY_2 = 1002; // 单片推荐
    const CATEGORY_3 = 1003; // 图册-眼保健操
    const CATEGORY_4 = 1004; // 文-干货特供
    const CATEGORY_5 = 1005; // 视频-正在缓冲
    const CATEGORY_6 = 1006; // 片单
    const CATEGORY_7 = 1007; // 特价活动



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_discovery_channel}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iType, sTitle,iCategory, iShowAt, iHideAt', 'required'),
			array('iType, iCategory, iTop, iStatus, iSort, iAndroid, iIOS, iBaseCount, iCount, iCreated, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sTitle', 'length', 'max'=>100),
			array('sLink', 'length', 'max'=>500),
			array('sVideo', 'length', 'max'=>500),
            array('iStartAt, iEndAt, iShowAt, iHideAt', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss'),
            //array('sLink', 'url'),
			array('sDescription, sTag,  sFrom, sShareContent,sSharePic', 'safe'),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            // 原因不明，还需要研究，暂时这样解决
            CUploadedFile::getInstance($this, 'sPicture') && $this->isNewRecord ? array('sPicture',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpg,png,gif',
                'maxSize'=>1024*512,    // 512kb
                'tooLarge'=>'大图大于512kb，上传失败！请上传小于512kb的文件！'
            ) : array('sPicture', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iBannerID, iType, sTitle, sDescription, iCategory, sPicture, iUserUpCount, iStartAt, iEndAt, sLink, iShowAt, iHideAt, iTop, iStatus, iSort, iCreated, iUpdated, sShareContent,sSharePic', 'safe', 'on'=>'search'),
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
            'cities'=>array(self::HAS_MANY, 'DiscoveryBannerCity', 'iBannerID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iBannerID' => 'ID',
			'iType' => '活动类型',
			'sTitle' => '标题',
			'sVideo'=>'视频',
            'sContent' => '内容',
			'sDescription' => '摘要',
			'iCategory' => '分类',
			'sPicture' => '封面',
			'iStartAt' => '活动开始时间',
			'iEndAt' => '活动结束时间',
			'sLink' => '跳转链接',
			'iShowAt' => '前台显示时间',
			'iHideAt' => '前台显示结束时间',
			'iTop' => '置顶',
			'iStatus' => '状态',
            'iSort' => '排序',
            'iAndroid' => 'Android',
            'iIOS' => 'IOS',
            'sTag' => '自定义标签',
            'sFrom' => '来源',
            'iBaseCount' => '基础关注数',
			'iUserUpCount'=>'用户基础关注数',
            'iCount' => '关注数',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
			'sShareContent'=>'分享内容',
			'sSharePic'=>'分享图片',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
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

		$criteria=new CDbCriteria;

		$criteria->compare('iBannerID',$this->iBannerID);
		$criteria->compare('iType',$this->iType);
		$criteria->compare('sTitle',$this->sTitle,true);
        $criteria->compare('sContent',$this->sContent,true);
		$criteria->compare('sDescription',$this->sDescription,true);
		$criteria->compare('iCategory',$this->iCategory);
		$criteria->compare('sShareContent',$this->sShareContent,true);
		$criteria->compare('iStartAt',$this->iStartAt);
		$criteria->compare('iEndAt',$this->iEndAt);
		$criteria->compare('sVideo',$this->sVideo,true);
		$criteria->compare('sLink',$this->sLink,true);
		$criteria->compare('iShowAt',$this->iShowAt);
		$criteria->compare('iHideAt',$this->iHideAt);
		$criteria->compare('iTop',$this->iTop);
        $criteria->compare('iStatus',$this->iStatus);
        $criteria->compare('sTag',$this->sTag);
        $criteria->compare('iBaseCount',$this->iBaseCount);
        $criteria->compare('iUserUpCount',$this->iUserUpCount);
        $criteria->compare('iCount',$this->iCount);
        $criteria->compare('sFrom',$this->sFrom);
		//$criteria->compare('iCity',$this->iCity);
		$criteria->compare('iSort',$this->iSort);
		$criteria->compare('iCreated',$this->iCreated);
		$criteria->compare('iUpdated',$this->iUpdated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
                'defaultOrder'=>'iBannerID DESC',
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DiscoveryBanner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 自动更新时间
	 */
    public function beforeSave()
    {
        if($this->iStartAt && !is_numeric($this->iStartAt))
            $this->iStartAt = strtotime($this->iStartAt);
        if($this->iEndAt && !is_numeric($this->iEndAt))
            $this->iEndAt = strtotime($this->iEndAt);
        if($this->iShowAt && !is_numeric($this->iShowAt))
            $this->iShowAt = strtotime($this->iShowAt);
        if($this->iHideAt && !is_numeric($this->iHideAt))
            $this->iHideAt = strtotime($this->iHideAt);
        $this->iUpdated = time();
        if ($this->isNewRecord)
            $this->iCreated = time();
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if($this->iStartAt)
            $this->iStartAt = date('Y-m-d H:i:s', $this->iStartAt);
        if($this->iEndAt)
            $this->iEndAt = date('Y-m-d H:i:s', $this->iEndAt);
        if($this->iShowAt)
            $this->iShowAt = date('Y-m-d H:i:s', $this->iShowAt);
        if($this->iHideAt)
            $this->iHideAt = date('Y-m-d H:i:s', $this->iHideAt);
    }

    public function getTypeName()
    {
        switch ($this->iType) {
            case self::TYPE_ACTIVITY:
                return '活动';
            case self::TYPE_TOPIC:
                return '专题';
            case self::TYPE_GALLERY:
                return '图册';
            case self::TYPE_VIDEO:
                return '视频';
            case self::TYPE_CONTENT:
                return '图文';
        }
    }

    public static function getTypeList()
    {
        return array(
            self::TYPE_ACTIVITY => '活动',
            self::TYPE_TOPIC    => '专题',
            self::TYPE_GALLERY  => '图册',
            self::TYPE_VIDEO    => '视频',
//             self::TYPE_CONTENT  => '图册',
        );
    }

    public function getCategoryName()
    {
        switch ($this->iCategory) {
            case self::CATEGORY_1:
                return '合集-本周约啥';
            case self::CATEGORY_2:
                return '单片-高能预警';
            case self::CATEGORY_3:
                return '图-眼保健操';
            case self::CATEGORY_4:
                return '文-干货特供';
            case self::CATEGORY_5:
                return '视频-正在缓冲';
            case self::CATEGORY_6:
                return '片单-保持队形';
            case self::CATEGORY_7:
                return '福利活动';
        }

    }

    public static function getCategoryList()
    {
        //return array();
        return array(
            self::CATEGORY_1 => '合集-本周约啥',
            self::CATEGORY_2 => '单片-高能预警',
            self::CATEGORY_3 => '图-眼保健操',
            self::CATEGORY_4 => '文-干货特供',
            self::CATEGORY_5 => '视频-正在缓冲',
            self::CATEGORY_6 => '片单-保持队形',
            self::CATEGORY_7 => '福利活动',
        );
    }

    public function afterDelete() {
        parent::afterDelete();
        // 自动删除关联
        DiscoveryBannerCity::model()->deleteAllByAttributes(array(
            'iBannerID'=>$this->iBannerID
        ));
    }

    public function saveCities() {
        // 保存关联
        DiscoveryBannerCity::model()->deleteAllByAttributes(array('iBannerID'=>$this->iBannerID));
        if (isset($_POST['cities'])) {
            foreach ($_POST['cities'] as $k=>$city) {
            	if (empty($city))continue;
                $cnc = new DiscoveryBannerCity();
                $cnc->iBannerID = $this->iBannerID;
                $cnc->iRegionNum = $city;
                $cnc->save();
            }
        }
    }
    
    /**
     * @tutorial 更新前端缓存：按照一定的顺序进行更新
     * @author lioulong
     */
    public function saveMemcache($memcacheKey,$where)
    {
    	$sql ="SELECT banner.*,city.iRegionNum FROM {$this->tableName()} AS banner LEFT JOIN {{app_discovery_channel_city}} AS city ON banner.iBannerID = city.iBannerID WHERE $where ORDER BY city.iRegionNum ASC,banner.iSort DESC,banner.iBannerID DESC;";
    	$arrBannerData = yii::app()->db->createCommand($sql)->queryAll();
    	$arrData = [];
    	foreach ($arrBannerData as  $bannerList){
    		$bannerList['iRegionNum'] = empty($bannerList['iRegionNum'])?0:$bannerList['iRegionNum'];
    		$bannerData = [
    				'ymdMax' => $bannerList['iHideAt'],
    				'ymdMin' => $bannerList['iShowAt'],
    				'iType'=>$bannerList['iType'],
    				'url' => $bannerList['sLink'],
    				'img' => date('Y-m-d', $bannerList['iCreated']) . '/' . $bannerList['sPicture'],
    				'ios' => $bannerList['iIOS'],
    				'android' => $bannerList['iAndroid'],
    				'title'    => $bannerList['sTitle'],
    				'desc'    => $bannerList['sDescription'],
    				'sort'    => $bannerList['iSort'],
    				'iBannerID'=>$bannerList['iBannerID'],
    				'sVideo'=>$bannerList['sVideo'],
    				'iCategory'=>$bannerList['iCategory'],
    				'iStartAt'=>$bannerList['iStartAt'],
    				'iEndAt'=>$bannerList['iEndAt'],
    				'sContent'=>$bannerList['sContent'],
    				'iCreated'=>$bannerList['iCreated'],
    				'shareContent'=>$bannerList['sShareContent'],
    				'sharePic' =>!empty($bannerList['sSharePic'])?date('Y-m-d', $bannerList['iCreated']) . '/'.$bannerList['sSharePic']:'',
    		];
    		
    		$arrData[$bannerList['iRegionNum']]['id'] = $bannerList['iRegionNum'];
    		$arrData[$bannerList['iRegionNum']]['data'][] =$bannerData;
    	}
    	yii::app()->cache_app->set($memcacheKey, $arrData, 60*5);
    }
    /**
     * @tutorial json还原--图册
     * @param array $value
     * @return Ambigous <string, mixed>
     */
//     function getContentInfo($value)
//     {
//     	$sContent = '';
//     	if (!empty($value['sContent']) && $value['iType'] == '3'){
//     		$sContent = json_decode($value['sContent'],true);
//     		if (is_array($sContent)){
//     			foreach ($sContent as &$val)
//     			{
//     				if (empty($val['img'])){
//     					$val ='';
//     				}else {
//     					$val['img'] = date('Y-m-d',$value['iCreated']).'/'.$value['iBannerID'].'/'. $val['img'];
//     				}
//     			}
//     			$sContent = array_filter($sContent);
//     			$sContent = array_values($sContent);
//     		}else $sContent=[];
    
//     	}else $sContent =$value['sContent'];
//     	return $sContent;
    	 
//     }

    // 生成前端数据
    static public function createJson() {
        // 已改成动态接口, 该方法废弃
    }
}

