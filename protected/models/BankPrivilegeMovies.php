<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 15:09
 */
class BankPrivilegeMovies extends CActiveRecord{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bank_privilege_movies}}';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id', 'unique'),
            array('p_id,movies_id,movies_name', 'required'),
            array('id,p_id,movies_id,movies_name', 'safe', 'on' => 'search')
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
            'p_id'=>'活动ID',
            'movies_id' => '影院id',
            'movies_name' => '影院名称',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('p_id', $this->p_id);
        $criteria->compare('movies_id', $this->movies_id);
        $criteria->compare('movies_name', $this->movies_name,true);
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
}