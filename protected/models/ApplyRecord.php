<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 17:10
 */
class ApplyRecord extends CActiveRecord{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_apply_record';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('a_id,create_time,open_id,user_name,phone,remark_content,channel_id', 'required'),
            array('id,a_id,create_time,open_id,user_name,phone,remark_content', 'safe'),
            array('id,a_id,create_time,open_id,user_name,phone,remark_content', 'safe', 'on' => 'search')
        );
    }
    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
        );
    }


    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'a_id'=>'活动ID',
            'create_time' => '报名时间',
            'open_id' => '用户id',
            'user_name' => '姓名',
            'phone' => '手机号',
            'remark_content' => '备注',
            'channel_id' => '渠道ID',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('open_id', $this->open_id);
        $criteria->compare('user_name', $this->user_name,true);
        $criteria->compare('phone', $this->phone);
        $criteria->compare('remark_content', $this->remark_content,true);
        $criteria->compare('channel_id', $this->channel_id);

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
//将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }
}