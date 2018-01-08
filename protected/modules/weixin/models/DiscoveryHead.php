<?php
class DiscoveryHead extends CActiveRecord
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
		return '{{weixin_discovery_head}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iType, sTitle,iChannel,sSecondTitle, iShowAt, iHideAt', 'required'),
			array('iType, iStatus,  iCreated,iTypeStatus, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sTitle', 'length', 'max'=>9),
			array('sSecondTitle', 'length', 'max'=>13),
			array('sPicture', 'length', 'max'=>200),
			array('sLink', 'length', 'max'=>500),
            array('iShowAt, iHideAt', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss'),
            array('sLink', 'url'),
			array('iType, sTitle, sSecondTitle,iTypeStatus,sDescription,sPicture,sLink,iShowAt,iHideAt,iStatus,iChannel,sPriceTag,sRecommendTag', 'safe'),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            // 原因不明，还需要研究，暂时这样解决
            CUploadedFile::getInstance($this, 'sPicture') || $this->isNewRecord ? array('sPicture',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpg,png,gif,jpeg',
                'maxSize'=>1024*512,    // 512kb
                'tooLarge'=>'大图大于512kb，上传失败！请上传小于512kb的文件！'
            ) : array('sPicture', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iHeadId,iType, sTitle, iTypeStatus,sSecondTitle,sDescription,sPicture,sLink,iShowAt,iHideAt,iStatus, iCreated, iUpdated,iChannel,sPriceTag,sRecommendTag', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @tutorial 关联更新表
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'cities'=>array(self::HAS_MANY, 'DiscoveryHeadCity', 'iHeadId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iHeadID' => 'HeadId',
			'iType' => '分类',
			'sTitle' => '主标题',
			'sSecondTitle'=>'副标题',
			'sDescription' => '描述',
			'sPicture' => '图片',
			'sLink' => '跳转链接',
			'iShowAt' => '前台上线时间',
			'iHideAt' => '前台下线时间',
			'iStatus' => '状态',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
			'iTypeStatus'=>'后台分类',
            'iChannel'=>'生效渠道',
            'sPriceTag'=>'价格标签',
            'sRecommendTag'=>'推荐标签',
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

		$criteria->compare('iHeadId',$this->iHeadId);
		$criteria->compare('iType',$this->iType);
		$criteria->compare('sTitle',$this->sTitle,true);
		$criteria->compare('sSecondTitle',$this->sSecondTitle,true);
		$criteria->compare('sDescription',$this->sDescription,true);
		$criteria->compare('sLink',$this->sLink,true);
		$criteria->compare('iShowAt',$this->iShowAt);
		$criteria->compare('iHideAt',$this->iHideAt);
		$criteria->compare('iStatus',$this->iStatus);
		$criteria->compare('iCreated',$this->iCreated);
		$criteria->compare('iUpdated',$this->iUpdated);
		$criteria->compare('iTypeStatus',$this->iTypeStatus);
        $criteria->compare('iChannel',$this->iChannel);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>20,
				),
			'sort'=>array(
                'defaultOrder'=>'iHeadId DESC',
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
        if($this->iShowAt)
            $this->iShowAt = date('Y-m-d H:i:s', $this->iShowAt);
        if($this->iHideAt)
            $this->iHideAt = date('Y-m-d H:i:s', $this->iHideAt);
    }
    /**
     * @tutorial 时间段校验
     * @param unknown $start
     * @param unknown $end
     * @param unknown $type
     * @param string $id
     */
    public function getTime($start,$end,$type,$id='',$channel)
    {
    	$dataArray = 1;
    	if ($start && $end && $type ){
    		$start =strtotime($start);
    		$end = strtotime($end);
    		$sql = "iChannel ='$channel' and iType = $type AND ((iShowAt < $start AND $start < iHideAt) OR (iShowAt < $end AND $end < iHideAt))";
    		if (!empty($id)){
    			$sql.= " AND iHeadId != $id";
    		}
//     		echo $sql;exit;
    		$criteria = new CDbCriteria;
    		$criteria->addCondition($sql);
    		$dataArray = self::model()->findAll($criteria);
    	}
    	return empty($dataArray)?true:false;
		    	
    }
	
    public function getType($type=0)
    {
    	$arrType=[''=>'全部','1'=>"一级入口",'2'=>'二级入口1','3'=>'二级入口2'];
    	if ($type =='list')
    		unset($arrType['']);
    	return !empty($arrType[$type])?$arrType[$type]:$arrType;
    }
    public function getChannelList($type='')
    {
        $arrData = [''=>'全部','8'=>'IOS客户端','9'=>'Android客户端','3'=>'微信电影票','26'=>'手Q电影票'];
        if($type != 'all') unset($arrData['']);
        return empty($arrData[$type])?$arrData:$arrData[$type];
    }
    
    public function getTypeStatus($type=0)
    {
    	$arrType=[''=>'全部','1'=>"运营活动",'2'=>'团体采购','3'=>'衍生品'];
    	if ($type =='list')
    		unset($arrType['']);
    	return !empty($arrType[$type])?$arrType[$type]:$arrType;
    }

    public function afterDelete() {
        parent::afterDelete();
        // 自动删除关联
        DiscoveryHeadCity::model()->deleteAllByAttributes(array(
            'iHeadId'=>$this->iHeadId
        ));
    }

    public function saveCities() {
        // 保存关联
        DiscoveryHeadCity::model()->deleteAllByAttributes(array('iHeadId'=>$this->iHeadId));
        if (isset($_POST['cities'])) {
            foreach ($_POST['cities'] as $k=>$city) {
            	if (empty($city)) continue;
                $cnc = new DiscoveryHeadCity();
                $cnc->iHeadId = $this->iHeadId;
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
        $criteria->order = 'iHeadId DESC' ;
        $dataArray = self::model()->findAll($criteria);
        foreach ($dataArray as $key => $dataRow) {
            $data = array(
            	'iHideAt' => strtotime($dataRow->iHideAt),
            	'iShowAt' => strtotime($dataRow->iShowAt),
                'sLink' => $dataRow->sLink,
                'sPicture' => Yii::app()->params['weixin_discovery_head']['final_url'].'/'.$dataRow->iHeadId . '/' . $dataRow->sPicture,
                'sTitle' => $dataRow->sTitle,
            	'sSecondTitle' => $dataRow->sSecondTitle,
                'sDescription' => $dataRow->sDescription,
                'iType' => $dataRow->iType,
            	'iHeadId'=>$dataRow->iHeadId,
                'sRecommendTag'=>$dataRow->sRecommendTag,
                'sPriceTag'=>$dataRow->sPriceTag,

            );
            if (!empty($dataRow['cities'])) {
                foreach ($dataRow['cities'] as $city) {
                    if (isset($writeJson[$dataRow->iChannel][$city['iRegionNum']])) {
                        $writeJson[$dataRow->iChannel][$city['iRegionNum']]['data'][] = $data;
                    } else {
                        $regionName = '';
                        foreach ($regions as $region) {
                            if ($region['id'] == $city->iRegionNum)
                                $regionName = $region['name'];
                            break;
                        }
                        $writeJson[$dataRow->iChannel][$city['iRegionNum']] = array(
                            'id' => $city['iRegionNum'],
                            'name' => $regionName,
                            'data' => array(
                                $data
                            )
                        );
                    }
                }
            } else {
                if (isset($writeJson[$dataRow->iChannel][0])) {
                    $writeJson[$dataRow->iChannel][0]['data'][] = $data;
                } else {
                    $writeJson[$dataRow->iChannel][0] = array(
                        'id' => '0',
                        'name' => '全国',
                        'data' => array(
                            $data
                        )
                    );
                }
            }
        }
//         echo json_encode($writeJson);exit;
        $writeJson['8'] = !empty($writeJson['8'])?$writeJson['8']:[];
        $writeJson['9'] = !empty($writeJson['9'])?$writeJson['9']:[];
        $writeJson['3'] = !empty($writeJson['3'])?$writeJson['3']:[];
        $writeJson['26'] = !empty($writeJson['26'])?$writeJson['26']:[];

        #微信
        if (!file_exists(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/head.json')))
            mkdir(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/head.json'), 0777, true);
        file_put_contents(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/head.json', 'callbackhead('.json_encode(array_values($writeJson['3'])).')');
        #手Q
        if (!file_exists(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/qq_head.json')))
            mkdir(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/qq_head.json'), 0777, true);
        file_put_contents(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/qq_head.json', 'callbackhead('.json_encode(array_values($writeJson['26'])).')');
        #IOS
        if (!file_exists(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/ios_head.json')))
            mkdir(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/ios_head.json'), 0777, true);
        file_put_contents(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/ios_head.json', 'callbackhead('.json_encode(array_values($writeJson['8'])).')');
        #Android
        if (!file_exists(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/android_head.json')))
            mkdir(dirname(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/android_head.json'), 0777, true);
        file_put_contents(Yii::app()->params['weixin_discovery_head']['target_dir'] . '/android_head.json', 'callbackhead('.json_encode(array_values($writeJson['9'])).')');

    }
}

