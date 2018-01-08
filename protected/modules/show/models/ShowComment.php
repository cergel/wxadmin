<?php

/**
 * This is the model class for table "t_show_comment".
 *
 * The followings are the available columns in table 't_show_comment':
 * @property integer $id
 * @property string $openId
 * @property string $project_id
 * @property string $content
 * @property integer $score
 * @property integer $channelId
 * @property integer $reply_count
 * @property integer $favor_count
 * @property integer $base_favor_count
 * @property string $project_name
 * @property string $type_name
 * @property string $fromId
 * @property string $channel_type
 * @property string $ip
 * @property integer $checkstatus
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 */
class ShowComment extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_show_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('openId, project_id, score, channelId, channel_type,content, created,base_favor_count, updated', 'required'),
            array('score, channelId, reply_count, favor_count, base_favor_count, checkstatus, status_type, created, updated', 'numerical', 'integerOnly' => true),
            array('openId, project_name, type_name', 'length', 'max' => 128),
            array('project_id, content', 'length', 'max' => 256),
            array('fromId', 'length', 'max' => 64),
            array('channel_type, ip', 'length', 'max' => 32),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, openId, project_id, content, score, channelId, reply_count, favor_count, base_favor_count, project_name, type_name, fromId, channel_type, ip, checkstatus, status_type, created, updated', 'safe', 'on' => 'search'),
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
            'id' => '评论id',
            'openId' => 'openid',
            'project_id' => '项目id',
            'content' => '评论内容',
            'score' => '评分',
            'channelId' => '来源',
            'reply_count' => '回复数',
            'favor_count' => '点赞数',
            'base_favor_count' => '注水点赞数',
            'project_name' => '项目名称',
            'type_name' => '类型',
            'fromId' => '来源',
            'channel_type' => '用途渠道',
            'ip' => '用户ip',
            'checkstatus' => '是否包含敏感词',
            'status_type' => '审核状态',
            'created' => '创建时间',
            'updated' => '更新时间',
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
    public function search($page = 50)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('openId', $this->openId);
        $criteria->compare('project_id', $this->project_id);
        $criteria->compare('content', $this->content, true);
        $score = $this->score;
        if ($score) {
            $score_max = $score + 20;
            $criteria->addCondition("`score` >= '$score' AND score <'$score_max'");
        }
        $criteria->compare('channelId', $this->channelId);
        $criteria->compare('reply_count', $this->reply_count);
        $criteria->compare('favor_count', $this->favor_count);
        $criteria->compare('base_favor_count', $this->base_favor_count);
        $criteria->compare('project_name', $this->project_name, true);
        $criteria->compare('type_name', $this->type_name);
        $criteria->compare('fromId', $this->fromId);
        $criteria->compare('channel_type', $this->channel_type);
        $criteria->compare('ip', $this->ip);
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
        $criteria->compare('updated', '>=' . strtotime($this->updated));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $page,
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
     * @return ShowComment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function statusName($status)
    {
        if ($status == 0) {
            return '待审核';
        } elseif ($status == 1) {
            return '上线';
        } elseif ($status == 2) {
            return '待审核';
        }
    }

    public function getChannelName($id)
    {
        $array = ['' => '全部', '3' => '微信', '8' => 'IOS', '9' => '安卓', '10' => 'PC'];
        if (!empty($id)) {
            if (!empty($array[$id])) return $array[$id];
            else return '';
        } else {
            return $array;
        }
    }

    public function status_type($type)
    {
        $this->status_type = $type;
        $result = self::model()->updateByPk($this->id, ['status_type' => $type]);
        $this->push($this->id, 'show-manage-comment', ['channelId' => $this->channelId, 'status' => $type]);
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
