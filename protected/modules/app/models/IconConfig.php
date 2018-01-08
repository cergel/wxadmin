<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 11:29
 */
class IconConfig extends CActiveRecord{
   /**
    * @return string the associated database table name
    */
    public function tableName()
    {
        return '{{app_icon}}';
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules(){
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,start_time, end_time,platform, status, type', 'required'),
            array('created_time,modified_time','numerical'),
            array('id,title,start_time, end_time,platform, status, type,icon1,icon1_on,icon2,icon2_on,icon3,icon3_on,icon4,icon4_on,icon5,icon5_on,loading,icon_color,created_time,modified_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id,title,start_time, end_time,platform, status, type,icon1,icon1_on,icon2,icon2_on,icon3,icon3_on,icon4,icon4_on,icon5,icon5_on,loading,icon_color,created_time,modified_time', 'safe', 'on'=>'search')
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '活动主题',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'platform'=>'平台',
            'status' => '状态',
            'type' => '资源分类',
            'icon1' => '图标1',
            'icon1_on' => '图标1按下',
            'icon2' => '图标2',
            'icon2_on' => '图标2按下',
            'icon3' => '图标3',
            'icon3_on' => '图标3_按下',
            'icon4' => '图标4',
            'icon4_on' => '图标4-按下',
            'icon5' => '图标5',
            'icon5_on' => '图标5-按下',
            'icon_color' => 'icon名称选中颜色',
            'platform' => '平台',
            'loading' => 'loading图',
            'created_time' => '创建时间',
            'modified_time' => '修改时间',

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


        $criteria->compare('id',$this->id);
        $criteria->compare('title',$this->title);
        $criteria->compare('start_time',$this->start_time);
        $criteria->compare('end_time',$this->end_time);
        $criteria->compare('platform',$this->platform);
        $criteria->compare('status',$this->status);
        $criteria->compare('type',$this->type);
        $criteria->compare('icon1',$this->icon1);
        $criteria->compare('icon1_on',$this->icon1_on);
        $criteria->compare('icon2',$this->icon2);
        $criteria->compare('icon2_on',$this->icon2_on);
        $criteria->compare('icon3',$this->icon3);
        $criteria->compare('icon3_on',$this->icon3_on);
        $criteria->compare('icon4',$this->icon4);
        $criteria->compare('icon4_on',$this->icon4_on);
        $criteria->compare('icon5',$this->icon5);
        $criteria->compare('icon5_on',$this->icon5_on);
        $criteria->compare('icon_color',$this->icon_color);
        $criteria->compare('platform',$this->platform);
        $criteria->compare('loading',$this->loading);
        $criteria->compare('created_time',$this->created_time);
        $criteria->compare('modified_time',$this->modified_time);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
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
    public function beforeSave()
    {
        if ($this->start_time && !is_numeric($this->start_time))
            $this->start_time = strtotime($this->start_time);
        if ($this->end_time && !is_numeric($this->end_time))
            $this->end_time = strtotime($this->end_time);
        $this->modified_time = time();
        if ($this->isNewRecord) {
            $this->created_time = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if ($this->start_time){
            $this->start_time = $this->int2date($this->start_time);
        }
        if ($this->end_time){
            $this->end_time = $this->int2date($this->end_time);
        }
//        if ($this->created_time){
//            $this->created_time = $this->int2date($this->created_time);
//        }
//        if ($this->modified_time){
//            $this->modified_time = $this->int2date($this->modified_time);
//        }
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     * 获取资源类型对应栏目
     * @param $type
     * @return string
     */
    public static function getPartList($type)
    {
        switch ($type) {
            case 1:
                return 'icon1,icon1_on,icon2,icon2_on,icon3,icon3_on,icon4';break;
            case 2:
                return 'loading';break;
        }
    }
    public function getTypeName($type){
        switch ($type) {
            case 1:
                return 'ICON组合';break;
            case 2:
                return 'Loading图';break;
        }
    }
    public function getPlatform($platform){
        $arrPlatform=[];
        $arr=explode(',',$platform);
        foreach($arr as $platform){
            if($platform==8){
                $arrPlatform[]='IOS';
            }
            if($platform==9){
                $arrPlatform[]='Android';
            }
            if($platform==3){
                $arrPlatform[]='微信';
            }
 	        if($platform==28){
                $arrPlatform[]='手Q';
            }
        }
        $arrPlatform=implode(',',$arrPlatform);
        return $arrPlatform;
    }
}
