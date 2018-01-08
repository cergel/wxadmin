<?php

/**
 * This is the model class for table "{{message_notice}}".
 *
 * The followings are the available columns in table '{{message_notice}}':
 * @property string $id
 * @property integer $msg_type
 * @property string $title
 * @property string $content
 * @property string $msg_pic
 * @property integer $task_id
 * @property string $push_msg
 * @property integer $state
 * @property integer $push_type
 * @property integer $is_push
 * @property string $user_file
 * @property integer $push_date
 * @property integer $create_time
 * @property integer $update_time
 */
Yii::import('ext.RedisManager', true);
class MessageNotice extends CActiveRecord {
    
    private $redis;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{message_notice}}';
    }

    public function init() {
        $this->setRedis();
    }
    
    public function setRedis(){
        $config = Yii::app()->params->redis_data['cache']['write'];
        $this->redis = RedisManager::getInstance($config);
    }
    
    public function saveRedis($key, $value){
        $this->redis->set($key,$value);
        $this->redis->expire($key,300);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, content, push_date', 'required'),
            array('msg_type, task_id, push_type, is_push, create_time, update_time', 'numerical', 'integerOnly' => true),
            array('title, content, msg_pic, push_msg, user_file', 'length', 'max' => 255),
//                    //限制上传大小
//			CUploadedFile::getInstance($this, 'msg_pic') || $this->isNewRecord ? array('msg_pic',
//				'file',
//				'allowEmpty'=>!$this->isNewRecord,
//				'types'=>'jpeg,jpg,png,gif',
//				'maxSize'=>1024*32,    // 200kb
//				'tooLarge'=>'分享图最大为32kb，上传失败！请上传小于32kb的图片！'
//			) : array('photo', 'length', 'max'=>2000),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, msg_type, title, content, msg_pic, is_all, task_id, is_push, push_msg, state, push_date, create_time, update_time, channel,user_file', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'channel' => array(self::HAS_MANY, 'MessageNoticeChannel', 'message_notice_id','select'=>'channel'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'msg_type' => '消息类型',
            'title' => '标题',
            'content' => '消息内容',
            'msg_pic' => '配图',
            'task_id' => '任务ID',
            'push_msg' => 'push文案',
            'state' => '状态',
            'push_date' => '定时推送',
            'push_type' => '推送方式',
            'user_file' => '文件用户openid',
            'is_push' => '同时推送客户端',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
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
        $criteria->compare('msg_type', $this->msg_type);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('msg_pic', $this->msg_pic, true);
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('push_type', $this->push_type);
        $criteria->compare('user_file', $this->user_file);
        $criteria->compare('is_push', $this->is_push);
        $criteria->compare('push_msg', $this->push_msg, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('push_date', $this->push_date);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
//        var_dump($criteria);die;
//        $criteria->together = TRUE;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }
    

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MessageNotice the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getChannel($iType = 'list') {
        $arrType = ['' => '全部', '3' => '微信', '8' => 'IOS', '9' => 'Android', '28' => '手Q'];
        if ($iType == 'list')
            unset($arrType['']);
        return empty($arrType[$iType]) ? $arrType : $arrType[$iType];
    }
    


}
