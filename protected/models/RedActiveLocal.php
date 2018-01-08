<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 10:58
 */
class RedActiveLocal extends CActiveRecord{
    /**
     * @return string the associated database table sName
     */
    public function tableName(){
        return '{{red_active_local}}';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules(){
        return array(
            array('a_id,l_local_id','required'),
            array('id,a_id,l_local_id','safe','on'=>'search')
        );
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'localActive'=>array(self::BELONGS_TO, 'RedActive', 'a_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'a_id' => '红点活动ID',
            'l_local_id' => '红点配置位置',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('a_id',$this->a_id);
        $criteria->compare('l_local_id',$this->l_local_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}