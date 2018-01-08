<?php

/**
 * This is the model class for table "{{star_greeting}}".
 *
 * The followings are the available columns in table '{{star_greeting}}':
 * @property integer $id
 * @property string $title
 * @property string $bg_img
 * @property integer $type
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $status
 * @property string $content
 * @property integer $created
 * @property integer $updated
 * @property integer $channel_id
 */
class StarGreeting extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_star_greeting';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['type,title,start_time,end_time,content', 'required'],
            array('type, start_time, end_time, status, created, updated', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 64),
            array('bg_img', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, bg_img, type, start_time, end_time, status, content, created, updated', 'safe', 'on' => 'search'),
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
            'id' => '问候ID',
            'title' => '问候名称',
            'bg_img' => '背景图片',
            'type' => '问候类型',
            'start_time' => '上线时间',
            'end_time' => '下线时间',
            'status' => '是否发布',
            'channel_id' => '渠道Id',
            'content' => '问候内容',
            'created' => '添加时间',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('bg_img', $this->bg_img, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20
            ),
            'sort' => array(
                'defaultOrder' => 'id DESC',
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
     * @return StarGreeting the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function scheduleCheck($start_time, $end_time, $id)
    {
        if (empty($start_time) || empty($end_time)) {
            return [];
        }
        $connection = Yii::app()->db_active;
        $sql = "SELECT * FROM t_star_greeting WHERE (
                (:start_time >= start_time AND end_time >=:start_time) OR
                (:end_time >= start_time AND :end_time <= end_time ) )AND id <> :id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":start_time", $start_time, PDO::PARAM_STR);
        $command->bindParam(":end_time", $end_time, PDO::PARAM_STR);
        $command->bindParam(":id", $id, PDO::PARAM_STR);
        return $command->queryRow();
    }

    public function pull($id, $is_del = 0)
    {
        if ($id <= 0) {
            return false;
        }
        $url = $is_del == 0 ? Yii::app()->params['starGreeting']['set'] : Yii::app()->params['starGreeting']['del'];
        $arrData = ['id' => $id];
        Https::getPost($arrData, $url);
    }
}
