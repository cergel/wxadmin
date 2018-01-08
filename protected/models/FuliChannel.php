<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 15:48
 */
class FuliChannel extends CActiveRecord{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_fulipindao_channel';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('f_id', 'required'),
            array('id,f_id,url,channel_id', 'safe'),
            array('id,f_id,url,channel_id', 'safe', 'on' => 'search')
        );
    }
    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
           // 'platform' => array(self::HAS_MANY, 'ApplyActivePlatform', 'a_id'),
           // 'share' => array(self::HAS_MANY, 'ApplyActiveShare', 'a_id'),
        );
    }


    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'f_id'=>'活动ID',
            'url' => '地址',
            'channel_id'=>'渠道',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('f_id', $this->f_id);
        $criteria->compare('url', $this->url);
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

}