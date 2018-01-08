<?php

/**
 * This is the model class for table "t_cinema_hall_feature".
 *
 * The followings are the available columns in table 't_cinema_hall_feature':
 * @property integer $id
 * @property string $cinema_no
 * @property string $cinema_name
 * @property string $hall_no
 * @property string $hall_name
 * @property integer $base_zan_num
 * @property integer $zan_num
 * @property integer $step_num
 * @property integer $created
 * @property integer $updated
 */
class CinemaHallFeature extends CActiveRecord
{
    public $count_num;
    public $count_zan;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_cinema_hall_feature';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            ['cinema_no,cinema_name,hall_no,hall_name,feature_ext,created', 'required', 'on' => 'create'],
            ['id,cinema_no,cinema_name,hall_no,hall_name,feature_ext', 'required', 'on' => 'update'],
            ['specific_description', 'length', 'max' => 140],
            ['feature_type', 'length', 'max' => 3],
            array('cinema_no, cinema_name, hall_no, hall_name, base_zan_num, zan_num, step_num,updated,feature_ext', 'required'),
            array('base_zan_num, zan_num, step_num, created, updated', 'numerical', 'integerOnly' => true),
            array('cinema_no, cinema_name, hall_name', 'length', 'max' => 30),
            array('hall_no', 'length', 'max' => 35),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, cinema_no, cinema_name, hall_no, hall_name, base_zan_num, zan_num, step_num, created, updated', 'safe', 'on' => 'search'),
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
            'cinema_no' => '影院ID',
            'cinema_name' => '影院名称',
            'hall_no' => '影厅ID',
            'hall_name' => '影厅名称',
            'base_zan_num' => '注水赞',
            'zan_num' => '赞人数',
            'step_num' => '踩人数',
            'created' => '创建时间',
            'updated' => '更新时间',
            'specific_description' => '特效描述',
            'feature_ext' => '特效',
            'count_num' => '总人数',
            'count_zan' => '合计赞',
            'feature_type' => 'type'
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
        $criteria->compare('cinema_no', $this->cinema_no, true);
        $criteria->compare('cinema_name', $this->cinema_name, true);
        $criteria->compare('hall_no', $this->hall_no, true);
        $criteria->compare('hall_name', $this->hall_name, true);
        $criteria->compare('base_zan_num', $this->base_zan_num);
        $criteria->compare('zan_num', $this->zan_num);
        $criteria->compare('step_num', $this->step_num);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
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
     * @return CinemaHallFeature the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function get_pull_ids()
    {
        $connection = Yii::app()->db_app;
        $sql = "SELECT * FROM t_cinema_hall_feature WHERE cinema_name ='' or hall_name= ''";
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }
}
