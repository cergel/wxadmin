<?php

/**
 * This is the model class for table "{{actor}}".
 *
 * The followings are the available columns in table '{{actor}}':
 * @property integer $id
 * @property string $name_chs
 * @property string $name_eng
 * @property integer $sex
 * @property integer $base_like
 * @property integer $like
 * @property integer $created
 * @property integer $updated
 */
class Actor extends CActiveRecord
{
    private $actor_info = null;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_actor';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            array('id, sex, base_like, like, created, updated', 'numerical', 'integerOnly' => true),
            array('name_chs, name_eng', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name_chs, name_eng, sex, base_like, like, created, updated', 'safe', 'on' => 'search'),
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
            'id' => '影人ID',
            'name_chs' => '影人中文名称',
            'name_eng' => '影人英文名称',
            'sex' => '性别',
            'base_like' => '注水喜欢',
            'like' => '真实喜欢',
            'created' => '创建时间',
            'updated' => '更新时间',
            'actor_head' => '影人头像',
            'count_like' => '喜欢总数',
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

        $criteria->compare('`id`', $this->id);
        $criteria->compare('`name_chs`', $this->name_chs, true);
        $criteria->compare('`name_eng`', $this->name_eng, true);
        $criteria->compare('`sex`', $this->sex);
        $criteria->compare('`base_like`', $this->base_like, true);
        $criteria->compare('`like`', $this->like);
        $criteria->compare('`created`', $this->created);
        $criteria->compare('`updated`', $this->updated);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20
            ),
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_active;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Actor the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取影人详情(自动更新基本信息)
     * @param $actorId
     * @param $isUpdate
     * @return array|mixed|null
     */
    public function getActorInfoByDb($actorId, $isUpdate = false)
    {
        $actor_info = $this->model()->find('id=:id', array(':id' => $actorId));
        if (empty($actor_info)) {
            return $this->ActorInsert($actorId);
        }
        if ((is_null($actor_info->created) && !empty($actor_info)) || $isUpdate == true) {
            $this->actor_info = $actor_info;
            return $this->ActorToUpate($actorId);
        }
        return $actor_info;
    }

    /**
     * 插入影人信息
     * @param $actorId
     * @return array|mixed|null
     */
    public function ActorInsert($actorId)
    {
        $api_actor_info = $this->getActorInfo($actorId);
        if (empty($api_actor_info)) {
            return false;
        }
        $this->setIsNewRecord(true);
        $this->id = (int)$api_actor_info['id'];
        $this->name_chs = $api_actor_info['actorNameChs'];
        $this->name_eng = $api_actor_info['actorNameEng'];
        $this->sex = $api_actor_info['gender'] == 'MALE' ? 1 : 2;
        $this->like = 0;
        $this->created = time();
        $this->updated = time();
        $this->base_like = $this->base_like > 0 ? $this->base_like : 0;
        $base_like = $this->base_like;
        $this->save();
        $this->pushActorBaseLike($actorId, $base_like);
        $actor_info = $this->model()->find('id=:id', array(':id' => $actorId));
        $actor_info = $actor_info->attributes;
        $actor_info['headImage'] = $api_actor_info['headImage'];
        return $actor_info;
    }

    /**
     * 更新影人信息
     * @param $actorId
     * @return array|mixed|null
     */
    public function ActorToUpate($actorId)
    {
        $api_actor_info = $this->getActorInfo($actorId);
        if (empty($api_actor_info)) {
            return false;
        }
        $this->actor_info->id = $api_actor_info['id'];
        $this->actor_info->name_chs = $api_actor_info['actorNameChs'];
        $this->actor_info->name_eng = $api_actor_info['actorNameEng'];
        $this->actor_info->sex = $api_actor_info['gender'] == 'MALE' ? 1 : 2;
        $this->actor_info->created = time();
        $this->actor_info->updated = time();
        if ($this->base_like) {
            $this->actor_info->base_like = (int)$this->base_like;
            $this->pushActorBaseLike($actorId, $this->base_like);
        }
        $this->actor_info->save();
        $actor_info = $this->model()->find('id=:id', array(':id' => $actorId));
        $actor_info = $actor_info->attributes;
        $actor_info['headImage'] = $api_actor_info['headImage'];
        return $actor_info;
    }

    /**
     * 接口获取影人信息
     * @param $actorId
     * @return bool
     */
    public function getActorInfo($actorId, $channelId = 8)
    {
        $url = Yii::app()->params['actor']['getActorInfo'];
        $arrData = ['actorId' => $actorId, 'channelId' => $channelId];
        $arrData = Https::getPost($arrData, $url);
        $arrData = json_decode($arrData, true);
        return !empty($arrData['data']) && empty($arrData['data']['actor_id']) ? $arrData['data'] : false;
    }

    /**
     * 通知影人注水
     * @param $actorId
     * @param $baseLike
     * @return bool
     */
    public function pushActorBaseLike($actorId, $baseLike)
    {
        if ($baseLike < 0) {
            return false;
        }
        $url = Yii::app()->params['actor']['manageBaseActorLike'];
        $arrData = ['actorId' => $actorId, 'channelId' => 8, 'base_like' => $baseLike];
        $arrData = Https::getPost($arrData, $url);
        $arrData = json_decode($arrData, true);
        return !empty($arrData['data']) ? $arrData['data'] : false;
    }
}
