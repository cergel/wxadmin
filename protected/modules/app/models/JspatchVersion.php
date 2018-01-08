<?php

/**
 * This is the model class for table "{{jspatch_version}}".
 *
 * The followings are the available columns in table '{{jspatch_version}}':
 * @property string $id
 * @property string $app_version
 * @property integer $channelId
 * @property string $created_at
 * @property integer $updated_at
 * @property string $created_by
 * @property string $remark
 */
class JspatchVersion extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{jspatch_version}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('channelId', 'numerical', 'integerOnly' => true),
            array('app_version', 'length', 'max' => 10),
            array('created_by', 'length', 'max' => 11),
            array('remark', 'length', 'max' => 255),
            array('created_at,updated_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, app_version, channelId, created_at, updated_at, created_by, remark', 'safe', 'on' => 'search'),
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
            'id' => '主键ID',
            'app_version' => 'app版本',
            'channelId' => '渠道号',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'created_by' => '创建者',
            'remark' => '备注字段',
            'openId' => '定向用户',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('app_version', $this->app_version, true);
        $criteria->compare('channelId', $this->channelId);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->compare('created_by', $this->created_by, true);
        $criteria->compare('remark', $this->remark, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return JspatchVersion the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function getVerCount($appver)
    {
        $result = Yii::app()->db->createCommand()->select("max(patchver) as num")->from("t_jspatch_patch")->where('appver=:appver', array(':appver' => $appver))->queryRow();
        return $result;
    }

    public function addPatch($patch_link, $md5, $appVer, $patchVer, $openId)
    {

        $result = Yii::app()->db->createCommand()->insert("t_jspatch_patch", [
            'content' => $patch_link,
            'md5' => $md5,
            'created_at' => date("Y-m-d H:i:s"),
            'appver' => $appVer,
            'patchVer' => $patchVer + 1,
            'created_by' => Yii::app()->getUser()->getName(),
            'openId' => $openId,
        ]);
        return $result;
    }

    public function getAllPatch($appVer)
    {
        $result = Yii::app()->db->createCommand("select * from `t_jspatch_patch` WHERE status = 1 AND appver = '{$appVer}'")->queryAll();
        return $result;
    }

    public function getPatch($patchId)
    {
        $result = Yii::app()->db->createCommand("select * from `t_jspatch_patch` WHERE status = 1 AND id = '{$patchId}'")->queryAll();
        return $result;
    }

    public function delPatch($patchId)
    {
        $result = Yii::app()->db->createCommand()->update("t_jspatch_patch", ['status' => 0], "id = :Id", [':Id' => $patchId]);
        return $result;
    }

    public function updatePatch($patchId, $openId)
    {
        $result = Yii::app()->db->createCommand()->update("t_jspatch_patch", ['openId' => $openId], "id = :Id", [':Id' => $patchId]);
        return $result;
    }

    public function delVersion($appVer)
    {
        Yii::app()->db->createCommand()->delete("t_jspatch_version", "app_version = :appver", [':appver' => $appVer]);
        //因为删除版本后如果重建不能小于历史版本号所以不能删
        Yii::app()->db->createCommand()->update("t_jspatch_patch", ['status' => 0], "appver = :appver", [':appver' => $appVer]);
    }
}
