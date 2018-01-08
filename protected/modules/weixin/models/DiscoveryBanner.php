<?php
class DiscoveryBanner extends CActiveRecord
{

    const TYPE_CONTENT  = 1; // 内容类
    const TYPE_ACTIVITY = 2; // 活动类


    const CONTENT_CATEGORY_1 = 1001; // 本周约啥
    const CONTENT_CATEGORY_2 = 1002; // 单片推荐
    const CONTENT_CATEGORY_3 = 1003; // 图册-眼保健操
    const CONTENT_CATEGORY_4 = 1004; // 文-干货特供
    const CONTENT_CATEGORY_5 = 1005; // 视频-正在缓冲
    const CONTENT_CATEGORY_6 = 1006; // 片单

    const ACTIVITY_CATEGORY_1 = 2001; // 特价活动

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{weixin_discovery_channel}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iType, sTitle, iCategory, sLink, iShowAt, iHideAt', 'required'),
			array('iType, iCategory, iTop, iStatus, iSort, iCreated, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sTitle', 'length', 'max'=>100),
			array('sPicture', 'length', 'max'=>200),
			array('sLink', 'length', 'max'=>500),
            array('iStartAt, iEndAt, iShowAt, iHideAt', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss'),
            array('sLink', 'url'),
			array('sDescription, sPicture, sTag', 'safe'),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            // 原因不明，还需要研究，暂时这样解决
            CUploadedFile::getInstance($this, 'sPicture') || $this->isNewRecord ? array('sPicture',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpg,png,gif',
                'maxSize'=>1024*512,    // 512kb
                'tooLarge'=>'大图大于512kb，上传失败！请上传小于512kb的文件！'
            ) : array('sPicture', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iBannerID, iType, sTitle, sDescription, iCategory, sPicture, iStartAt, iEndAt, sLink, iShowAt, iHideAt, iTop, iStatus, iSort, iCreated, iUpdated', 'safe', 'on'=>'search'),
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
			'sDescription' => '描述',
            'iCategory' => '分类',
            'sTag' => '自定义标签',
			'sPicture' => '封面',
			'iStartAt' => '活动开始时间',
			'iEndAt' => '活动结束时间',
			'sLink' => '跳转链接',
			'iShowAt' => '前台显示时间',
			'iHideAt' => '前台显示结束时间',
			'iTop' => '置顶',
			'iStatus' => '状态',
			'iSort' => '排序',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
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
		$criteria->compare('sDescription',$this->sDescription,true);
        $criteria->compare('iCategory',$this->iCategory);
        $criteria->compare('sTag',$this->sTag);
		//$criteria->compare('sPicture',$this->sPicture,true);
		$criteria->compare('iStartAt',$this->iStartAt);
		$criteria->compare('iEndAt',$this->iEndAt);
		$criteria->compare('sLink',$this->sLink,true);
		$criteria->compare('iShowAt',$this->iShowAt);
		$criteria->compare('iHideAt',$this->iHideAt);
		$criteria->compare('iTop',$this->iTop);
		$criteria->compare('iStatus',$this->iStatus);
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

    public function getCategoryName()
    {
        switch ($this->iCategory) {
            case self::CONTENT_CATEGORY_1:
                return '本周约啥';
            case self::CONTENT_CATEGORY_2:
                return '单片推荐';
            case self::CONTENT_CATEGORY_3:
                return '图册-眼保健操';
            case self::CONTENT_CATEGORY_4:
                return '文-干货特供';
            case self::CONTENT_CATEGORY_5:
                return '视频-正在缓冲';
            case self::CONTENT_CATEGORY_6:
                return '片单';
            case self::ACTIVITY_CATEGORY_1:
                return '特价活动';
        }
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

    // 生成前端数据
    static public function createJson() {
        $writeJson = array();

//        $regions = BsonBaseRegionInfo::model()->findAll();
        $regions = Https::getCityList();
        $criteria = new CDbCriteria;
        $criteria->addCondition('iStatus=1');
        $criteria->order = 'iTop DESC,iSort DESC,iBannerID DESC' ;
        $banners = self::model()->findAll($criteria);
        foreach ($banners as $key => $banner) {
            $data = array(
                'start_at' => date('YmdHis', strtotime($banner->iStartAt)),
                'end_at' => date('YmdHis', strtotime($banner->iEndAt)),
                'hide_at' => date('YmdHis', strtotime($banner->iHideAt)),
                'show_at' => date('YmdHis', strtotime($banner->iShowAt)),
                'url' => $banner->sLink,
                'img' => Yii::app()->params['weixin_discovery_banner']['final_url'].'/'.date('Y-m-d', $banner->iCreated) . '/' . $banner->sPicture,
                'title' => $banner->sTitle,
                'desc' => $banner->sDescription,
                'category' => $banner->getCategoryName(),
                'tag' => $banner->sTag,
                'type' => $banner->iType,
            	'iTop'=>$banner->iTop,
            	'iSort'=>$banner->iSort,
            	'iBannerID'=>$banner->iBannerID,
            );
            if ($banner->sTag)
                $data['tag'] = $banner->sTag;
            if ($banner['cities']) {
                foreach ($banner['cities'] as $city) {
                    if (isset($writeJson[$city['iRegionNum']])) {
                        $writeJson[$city['iRegionNum']]['data'][] = $data;
                    } else {
                        $regionName = '';
                        foreach ($regions as $region) {
                            if ($region['id'] == $city->iRegionNum)
                                $regionName = $region['name'];
                            break;
                        }
                        $writeJson[$city['iRegionNum']] = array(
                            'id' => $city['iRegionNum'],
                            'name' => $regionName,
                            'data' => array(
                                $data
                            )
                        );
                    }
                }
            } else {
                if (isset($writeJson[0])) {
                    $writeJson[0]['data'][] = $data;
                } else {
                    $writeJson[0] = array(
                        'id' => '0',
                        'name' => '全国',
                        'data' => array(
                            $data
                        )
                    );
                }
            }
        }
        //echo json_encode(array_values($writeJson));exit;
        if (!file_exists(dirname(Yii::app()->params['weixin_discovery_banner']['target_dir'] . '/banner.json')))
            mkdir(dirname(Yii::app()->params['weixin_discovery_banner']['target_dir'] . '/banner.json'), 0777, true);
        file_put_contents(Yii::app()->params['weixin_discovery_banner']['target_dir'] . '/banner.json', 'callback('.json_encode(array_values($writeJson)).')');
    }
}

