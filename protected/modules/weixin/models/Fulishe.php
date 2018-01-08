<?php

/**
 * This is the model class for table "active_page".
 */
class Fulishe extends CActiveRecord
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
        return 't_qq_fulishe';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('sImages, sContent, sText,iTag,iOrder,sLinke,iStatus,iCreated,onLineTime,offLineTime,iUpdated', 'safe'),
            array('sImages', 'required'),
            array('iOrder', 'numerical', 'integerOnly' => true),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            CUploadedFile::getInstance($this, 'sImages') || $this->isNewRecord ? array(
                'sImages',
                'file',
                'allowEmpty' => !$this->isNewRecord,
                'types' => 'jpg,png,gif',
                //'maxSize' => 1024 * 1024,    // 512kb
                'tooLarge' => '大图大于1M，上传失败！请上传小于1M的文件！'
            ) : array('sImages', 'length', 'max' => 200),
            array(
                'iId,iTag,sImages, sContent, sText,iOrder,sLinke,iStatus,onLineTime,offLineTime,iCreated,iUpdated',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'iId' => 'ID',
            'sImages' => '图片',
            'sContent' => '优惠文案',
            'iTag' => '标签',
            'sText' => '活动说明',
            'iOrder' => '排序',
            'sLinke' => '链接地址',
            'iStatus' => '状态',
            'onLineTime' => '上线时间',
            'offLineTime' => '下线时间',
            'iCreated' => '创建时间',
            'iUpdated' => '更新时间',
        );
    }
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('iId', $this->iId);
        $criteria->compare('sContent', $this->sContent, true);
        $criteria->compare('iTag',$this->iTag);
        $criteria->compare('sText', $this->sText, true);
        $criteria->compare('iStatus',$this->iStatus);
        $criteria->compare('onLineTime',$this->onLineTime);
        $criteria->compare('offLineTime',$this->offLineTime);
        $criteria->compare('iCreated', strtotime($this->iCreated));
        $criteria->compare('iUpdated', strtotime($this->iUpdated));
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'iOrder DESC',
            )
        ));
    }
    /**
     *
     * @param unknown $minTag
     * @return Ambigous <multitype:string , string>
     */
    public static function getTagList($minTag)
    {
        $arrTag =[''=>'全部','1'=>'无推荐','2'=>'手慢无','3'=>'今日主打','4'=>'腾酱推荐',];
        if($minTag == 'list'){
            unset($arrTag['']);
        }elseif (!empty($arrTag[$minTag])){
            $arrTag = $arrTag[$minTag];
        }
        return $arrTag;
    }

    public static function model($classsName = __CLASS__)
    {
        return parent::model($classsName);
    }

    // 自动更新时间
    public function beforeSave()
    {
        if ($this->onLineTime && !is_numeric($this->onLineTime)){
            $this->onLineTime = strtotime($this->onLineTime);
        }
        if ($this->offLineTime && !is_numeric($this->offLineTime)){
            $this->offLineTime = strtotime($this->offLineTime);
        }

        $this->iUpdated = time();
        if ($this->isNewRecord) {
            $this->iCreated = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->createJsonFile();
        $this->afterFind();
    }
    public function afterFind()
    {
        if ($this->onLineTime)
            $this->onLineTime = $this->int2date($this->onLineTime);
        if ($this->offLineTime)
            $this->offLineTime = $this->int2date($this->offLineTime);
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }
    public function afterDelete()
    {
        $this->createJsonFile();
    }
    /**
     * 生成json文件
     */
    public function createJsonFile()
    {
        $criteria = new CDbCriteria;
        $criteria->compare("onLineTime",'<='.time(),'AND');
        $criteria->compare("offLineTime",">".time(),'AND');
        $criteria->compare("iStatus","=1");
        $criteria->order = 'iOrder DESC,iId desc' ;
        $dataArray = Fulishe::model()->findAll($criteria);
        $arrData = [];
        foreach ($dataArray as $val)
        {
            $arrData[] = [
                'sImages'=>Yii::app()->params['Fulishe']['cdn'].$val->iId.'/'.$val->sImages,
                'iId'=>$val->iId,
                'sContent'=>$val->sContent,
                'sText'=>$val->sText,
                'iTag'=>$val->iTag,
                'iOrder'=>$val->iOrder,
                'sLinke'=>$val->sLinke,
                'onLineTime'=>strtotime($val->onLineTime),
                'offLineTime'=>strtotime($val->offLineTime),
            ];
        }
        $criteriaStatus = new CDbCriteria;
        $criteriaStatus->compare("offLineTime","<".time(),'AND');
        $criteriaStatus->compare("iStatus","=1");
        $objData = Fulishe::model()->findAll($criteriaStatus);
        foreach ($objData as $obj)
        {
            $obj->iStatus = 0;
            $obj->save();
        }
        if (!file_exists(dirname(Yii::app()->params['Fulishe']['target_dir'] . '/fulishe.json')))
            mkdir(dirname(Yii::app()->params['Fulishe']['target_dir'] . '/fulishe.json'), 0777, true);
        file_put_contents(Yii::app()->params['Fulishe']['target_dir'] . '/fulishe.json', 'callbackfulishe('.json_encode($arrData).')');
        return count($arrData);
    }


}
