<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 10:58
 */
class RedActiveChannel extends CActiveRecord{
    /**
     * @return string the associated database table sName
     */
    public function tableName(){
        return '{{red_active_channel}}';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules(){
        return array(
            array('a_id,c_channelId','required'),
            array('id,a_id,c_channelId','safe','on'=>'search')
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'channelActive'=>array(self::BELONGS_TO, 'RedActiveChannel', 'a_id'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'a_id' => '红点活动id',
            'c_channelId' => '定向平台',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('a_id',$this->iActive_id);
        $criteria->compare('c_channelId',$this->share);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}