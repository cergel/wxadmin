<?php

/**
 * This is the model class for table "{{user_group}}".
 *
 * The followings are the available columns in table '{{user_group}}':
 * @property integer $id
 * @property string $groupName
 * @property string $authList
 * @property integer $created
 * @property integer $updated
 * @property integer $blackOrWhite
 * @property integer $createUser
 */
class UserGroup extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_user_group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('groupName, authList, created, updated, blackOrWhite, createUser', 'required'),
            array('created, updated, blackOrWhite, createUser', 'numerical', 'integerOnly' => true),
            array('groupName', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, groupName, authList, created, updated, blackOrWhite, createUser', 'safe', 'on' => 'search'),
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
            'id' => '分组Id',
            'groupName' => '分组名称',
            'authList' => '权限列表',
            'created' => '创建时间',
            'updated' => '更新时间',
            'blackOrWhite' => '黑名单/白名单',
            'createUser' => '创建人Id',
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
        $criteria->compare('groupName', $this->groupName, true);
        $criteria->compare('authList', $this->authList, true);
        $criteria->compare('created', strtotime($this->created));
        $criteria->compare('updated', strtotime($this->updated));
        $criteria->compare('blackOrWhite', $this->blackOrWhite);
        $criteria->compare('createUser', $this->createUser);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserGroup the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getGroupName($groupId)
    {
        if ($groupId <= 0) {
            return '超级管理员';
        }
        $data = self::model()->findByPk($groupId);
        return $data ? $data->groupName : '超级管理员';
    }
}
