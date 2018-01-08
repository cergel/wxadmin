<?php
class Banner extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{app_banner}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sTitle, sLink, iShowAt, iHideAt', 'required'),
			array('iStatus, iSort, iAndroid, iIOS,iWX,iCreated, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sTitle', 'length', 'max'=>100),
			array('sPicture', 'length', 'max'=>200),
			array('sLink', 'length', 'max'=>500),
            array('iShowAt, iHideAt', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss'),
            //array('sLink', 'url'),
			array('sDescription, sPicture, sShareContent,sSharePic', 'safe'),
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
			array('iBannerID, sTitle, sDescription, sShareContent,sSharePic,iWX,iIOS,iAndroid, sPicture, sLink, iShowAt, iHideAt, iStatus, iSort, iCreated, iUpdated', 'safe', 'on'=>'search'),
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
            'cities'=>array(self::HAS_MANY, 'BannerCity', 'iBannerID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iBannerID' => 'ID',
			'sTitle' => '标题',
			'sDescription' => '摘要',
			'sPicture' => '封面',
			'sLink' => '跳转链接',
			'iShowAt' => '前台显示时间',
			'iHideAt' => '前台显示结束时间',
			'iStatus' => '状态',
            'iSort' => '排序',
            'iAndroid' => 'Android',
            'iIOS' => 'IOS',
			'iWX'=>'微信',
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
		$criteria->compare('sTitle',$this->sTitle,true);
		$criteria->compare('sDescription',$this->sDescription,true);
		$criteria->compare('sShareContent',$this->sShareContent,true);
		$criteria->compare('sLink',$this->sLink,true);
		$criteria->compare('iShowAt',$this->iShowAt);
		$criteria->compare('iHideAt',$this->iHideAt);
        $criteria->compare('iStatus',$this->iStatus);
        $criteria->compare('iSort',$this->iSort);
        $criteria->compare('iWX',$this->iWX);
        $criteria->compare('iIOS',$this->iIOS);
		$criteria->compare('iAndroid',$this->iAndroid);
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


    public function afterDelete() {
        parent::afterDelete();
        // 自动删除关联
        BannerCity::model()->deleteAllByAttributes(array(
            'iBannerID'=>$this->iBannerID
        ));
    }

    public function saveCities() {
        // 保存关联
        BannerCity::model()->deleteAllByAttributes(array('iBannerID'=>$this->iBannerID));
        if (isset($_POST['cities'])) {
            foreach ($_POST['cities'] as $k=>$city) {
            	if (count($_POST['cities']) > 1 && empty($city)) continue;
                $cnc = new BannerCity();
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
    public function saveMemcache()
    {
    	$sql ="SELECT banner.*,city.iRegionNum FROM {$this->tableName()} AS banner LEFT JOIN {{app_banner_city}} AS city ON banner.iBannerID = city.iBannerID WHERE banner.iStatus ='1' ORDER BY city.iRegionNum ASC,banner.iSort DESC,banner.iBannerID DESC;";
    	$arrBannerData = yii::app()->db->createCommand($sql)->queryAll();
    	$arrData = [];
    	foreach ($arrBannerData as  $bannerList){
    		$bannerList['iRegionNum'] = empty($bannerList['iRegionNum'])?0:$bannerList['iRegionNum'];
    		$bannerData = [
    				'ymdMax' => $bannerList['iHideAt'],
    				'ymdMin' => $bannerList['iShowAt'],
    				'url' => $bannerList['sLink'],
    				'img' => date('Y-m-d', $bannerList['iCreated']) . '/' . $bannerList['sPicture'],
    				'ios' => $bannerList['iIOS'],
    				'android' => $bannerList['iAndroid'],
    				'title'    => $bannerList['sTitle'],
    				'desc'    => $bannerList['sDescription'],
    				'sort'    => $bannerList['iSort'],
    				'shareContent'=>$bannerList['sShareContent'],
    				'sharePic' =>!empty($bannerList['sSharePic'])?date('Y-m-d', $bannerList['iCreated']) . '/'.$bannerList['sSharePic']:'',
    		];
    		$arrData[$bannerList['iRegionNum']]['id'] = $bannerList['iRegionNum'];
    		$arrData[$bannerList['iRegionNum']]['data'][] =$bannerData;
    	}
 //   	yii::app()->cache_app->set('app_banner', $arrData, 60*5);
//     	echo json_encode($arrData);exit;
    }

		// 生成前端数据
		static public function createJson() {
			$writeJson = array();
			$wexinJson=[];
			$IOSJson = [];
			$AndroidJson =[];
			$criteria = new CDbCriteria;
			$criteria->addCondition(time() . ' <= iHideAt  AND iStatus=1');

			$criteria->order = 'iBannerID DESC' ;
			$banners = self::model()->findAll($criteria);
			foreach ($banners as $key => $banner) {
				if($banner->iWX){

				}
				$data = array(
					'hide_at' => strtotime($banner->iHideAt),
					'show_at' => strtotime($banner->iShowAt),
					'url' => $banner->sLink,
					'img' => Yii::app()->params['weixin_banner']['final_url'].'/app_banner/'.date('Y-m-d', $banner->iCreated) . '/' . $banner->sPicture,
					'title' => $banner->sTitle,
					'desc' => $banner->sDescription,
					'iBannerID'=>$banner->iBannerID,
					'iSort'=>$banner->iSort,
					'iStatus'=>$banner->iStatus,
					'sShareContent'=>$banner->sShareContent,
					'sSharePic'=>Yii::app()->params['weixin_banner']['final_url'].'/app_banner/'.date('Y-m-d',strtotime( $banner->iCreated)).'/'.$banner->sSharePic
				);
				if ($banner['cities']) {
					foreach ($banner['cities'] as $city) {
						if(!empty($banner->iWX))
							$wexinJson[$city['iRegionNum']]['data'][] = $data;
						if(!empty($banner->iIOS))
							$IOSJson[$city['iRegionNum']]['data'][] = $data;
						if(!empty($banner->iAndroid))
							$AndroidJson[$city['iRegionNum']]['data'][] = $data;
					}
				} else {
					if(!empty($banner->iWX)){
						if (isset($wexinJson['0'])) $wexinJson['0']['data'][] = $data;
						else $wexinJson['0'] = array('id' => '0','name' => '全国','data' => array($data));
					}
					if(!empty($banner->iIOS)){
						if (isset($IOSJson['0'])) $IOSJson['0']['data'][] = $data;
						else $IOSJson['0'] = array('id' => '0','name' => '全国','data' => array($data));
					}
					if(!empty($banner->iAndroid)){
						if (isset($AndroidJson['0'])) $AndroidJson['0']['data'][] = $data;
						else $AndroidJson['0'] = array('id' => '0','name' => '全国','data' => array($data));
					}
				}
			}
			//写入微信的
			if (!file_exists(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/banner.json')))
				mkdir(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/banner.json'), 0777, true);
			file_put_contents(Yii::app()->params['weixin_banner']['target_dir'] . '/banner.json', 'callback_banner('.json_encode($wexinJson).')');
			//IOS
			if (!file_exists(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/banner.json')))
				mkdir(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/ios_banner.json'), 0777, true);
			file_put_contents(Yii::app()->params['weixin_banner']['target_dir'] . '/ios_banner.json', 'callback_banner('.json_encode($IOSJson).')');
			//
			if (!file_exists(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/banner.json')))
				mkdir(dirname(Yii::app()->params['weixin_banner']['target_dir'] . '/android_banner.json'), 0777, true);
			file_put_contents(Yii::app()->params['weixin_banner']['target_dir'] . '/android_banner.json', 'callback_banner('.json_encode($AndroidJson).')');
		}

}

