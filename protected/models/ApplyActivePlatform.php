<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 17:00
 */
class ApplyActivePlatform extends CActiveRecord{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_apply_active_platform';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('a_id,platform', 'required'),
            array('id,a_id,platform', 'safe', 'on' => 'search')
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
            'platform' => '生成页面平台',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('platform', $this->platform, true);
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