<?php

/**
 * This is the model class for table "{{movie_biz}}".
 *
 * The followings are the available columns in table '{{movie_biz}}':
 * @property string $id
 * @property string $movieId
 * @property string $config
 * @property string $start
 * @property string $end
 * @property integer $status
 */
class MovieBiz extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{movie_biz}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('config,platform', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('movieId', 'length', 'max' => 11),
            array('start, end', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, movieId, config, start, end, status', 'safe', 'on' => 'search'),
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
            'movieId' => '影片ID',
            'config' => '配置信息',
            'start' => '开始时间',
            'end' => '结束时间',
            'platform' => '平台',
            'status' => '影片商业化详情状态',
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
        $criteria->compare('movieId', $this->movieId, true);
        $criteria->compare('config', $this->config, true);
        $criteria->compare('start', $this->start, true);
        $criteria->compare('end', $this->end, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('platform', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MovieBiz the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
