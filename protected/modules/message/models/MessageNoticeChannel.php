<?php

/**
 * This is the model class for table "{{message_notice_channel}}".
 *
 * The followings are the available columns in table '{{message_notice_channel}}':
 * @property string $id
 * @property integer $message_notice_id
 * @property string $push_id
 * @property integer $channel
 * @property string $push_url
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class MessageNoticeChannel extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{message_notice_channel}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('message_notice_id, push_id, channel, push_url, create_time, update_time', 'required'),
            array('message_notice_id, channel, state, create_time, update_time', 'numerical', 'integerOnly' => true),
            array('push_id', 'length', 'max' => 20),
            array('push_url', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, message_notice_id, push_id, channel, push_url, state, create_time, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'channl_id' => array(self::BELONGS_TO, 'MessageNotice', 'channel'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'message_notice_id' => 'Message Notice',
            'push_id' => 'Push',
            'channel' => 'Channel',
            'push_url' => 'Push Url',
            'state' => 'State',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        );
    }
    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_message;
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('message_notice_id', $this->message_notice_id);
        $criteria->compare('push_id', $this->push_id, true);
        $criteria->compare('channel', $this->channel);
        $criteria->compare('push_url', $this->push_url, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MessageNoticeChannel the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getChannelById($id) {
        $sql = "SELECT channel FROM {$this->tableName()}  WHERE message_notice_id={$id}";
        $arrQueryData = yii::app()->db->createCommand($sql)->queryAll();
        $ch = '';
        foreach ($arrQueryData as $k => $v) {
            if($v['channel'] == 8) {
                $ch .= "IOS".',';
            }elseif ($v['channel'] == 9) {
                $ch .= "Android,";
            } elseif ($v['channel'] == 11) {
                $ch .= "微信电影票,";
            } elseif ($v['channel'] == 28) {
                $ch .= "手Q电影票,";
            }else {
                $ch .= '异常,';
            }
        }
        return rtrim($ch, ',');
    }

}
