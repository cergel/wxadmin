<?php
Yii::import('ext.RedisManager', true);

class UserTagKol extends CActiveRecord
{
    public function tableName()
    {
        return 't_user_kol_tag';
    }

    public function rules()
    {
        return array(
            array('id,k_id,tag_id', 'safe'),
            array('id,k_id,tag_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
        'tag'=>array(self::BELONGS_TO, 'UserTag', 'k_id'),
		);
    }


    public function attributeLabels()
    {
        return array(
            'id'=>'主键id',
            'k_id' => 'kolid',
            'tag_id' => 'tagid',
        );
    }

    /**
     * @tutorial 查询
     * @author liulong
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('k_id', $this->k_id);
        $criteria->compare('tag_id', $this->tag_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDbConnection()
    {
        return Yii::app()->db;
    }




}
