<?php

/**
 * This is the model class for table "{{promotion_sharing}}".
 *
 * The followings are the available columns in table '{{promotion_sharing}}':
 * @property integer $id
 * @property string $activity_name
 * @property integer $for_user
 * @property string $bonus_id
 * @property string $bonus_url
 * @property string $background_url
 * @property integer $state_type
 * @property integer $updated
 * @property integer $created
 * @property string $create_user
 * @property string $update_user
 * @property integer $start_time
 * @property integer $end_time
 */
class PromotionSharing extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{promotion_sharing}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('activity_name, background_url, state_type, updated, created, create_user, update_user, start_time, end_time', 'required'),
            array('for_user, state_type, updated, created, start_time, end_time', 'numerical', 'integerOnly' => true),
            array('activity_name, bonus_id, bonus_url, background_url', 'length', 'max' => 500),
            array('create_user, update_user', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, activity_name, for_user, bonus_id, bonus_url, background_url, state_type, updated, created, create_user, update_user, start_time, end_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'activity_name' => '活动名称',
            'for_user' => '针对用户',
            'bonus_id' => '红包ID',
            'bonus_url' => '链接地址',
            'background_url' => '背景图片',
            'state_type' => '发布状态',
            'updated' => '更新时间',
            'created' => '创建时间',
            'create_user' => '创建人',
            'update_user' => '更新人',
            'start_time' => '上线时间',
            'end_time' => '下线时间',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('activity_name', $this->activity_name, true);
        $criteria->compare('for_user', $this->for_user);
        $criteria->compare('bonus_id', $this->bonus_id, true);
        $criteria->compare('bonus_url', $this->bonus_url, true);
        $criteria->compare('background_url', $this->background_url, true);
        $criteria->compare('state_type', $this->state_type);
        $criteria->compare('updated', $this->updated);
        $criteria->compare('created', $this->created);
        $criteria->compare('create_user', $this->create_user, true);
        $criteria->compare('update_user', $this->update_user, true);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PromotionSharing the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * 获取可配置渠道
     */
    public static function getChannel()
    {
        return [3 => '微信电影票', 28 => 'QQ电影票'];
    }
}
