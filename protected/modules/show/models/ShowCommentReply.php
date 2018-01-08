<?php

/**
 * This is the model class for table "t_show_comment_reply".
 *
 * The followings are the available columns in table 't_show_comment_reply':
 * @property integer $id
 * @property string $openId
 * @property integer $commentId
 * @property integer $channelId
 * @property string $fromId
 * @property string $content
 * @property integer $checkstatus
 * @property integer $created
 */
class ShowCommentReply extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_show_comment_reply';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('openId, commentId, channelId, created', 'required'),
            array('commentId, channelId, checkstatus,status_type, created', 'numerical', 'integerOnly' => true),
            array('openId', 'length', 'max' => 128),
            array('fromId', 'length', 'max' => 64),
            array('content', 'length', 'max' => 256),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, openId, commentId, channelId, fromId, content, status_type,checkstatus, created', 'safe', 'on' => 'search'),
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
            'id' => 'Id',
            'openId' => 'openId',
            'commentId' => '评论Id',
            'channelId' => '来源',
            'fromId' => '子渠道',
            'content' => '回复内容',
            'status_type' => '状态',
            'checkstatus' => '是否包含敏感词',
            'created' => '创建时间',
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
        $criteria->compare('openId', $this->openId, true);
        $criteria->compare('commentId', $this->commentId);
        $criteria->compare('channelId', $this->channelId);
        $criteria->compare('fromId', $this->fromId, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('checkstatus', $this->checkstatus);
        $status_type = $this->status_type;
        if (is_numeric($status_type)) {
            if ($status_type == 1) {
                $criteria->addCondition("`status_type` = 1");
            } else {
                $criteria->addCondition("`status_type` <> 1");
            }
        }
        $criteria->compare('created', '>=' . strtotime($this->created));
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
            'sort' => array(
                'defaultOrder' => 'created DESC',
            ),
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_app;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ShowCommentReply the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function status_type($type)
    {
        $this->status_type = $type;
        $result = $this->save();
        $this->push($this->id, 'show-manage-reply', ['channelId' => $this->channelId, 'status' => $type]);
        return $result;
    }

    /**
     * 内部接口调用
     * @param $id
     * @param $param
     * @param $arrData
     */
    public function push($id, $param, $arrData)
    {
        //修改状态show-manage-comment、注水评论show-edit-comment,回复show-manage-reply
        $url = Yii::app()->params['showComment']['showEditComment'];
        $url .= '/' . $id . '/' . $param;
        $re = Https::getPost($arrData, $url);
    }
}
