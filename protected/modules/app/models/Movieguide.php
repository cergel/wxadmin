<?php

/**
 * This is the model class for table "{{movieguide}}".
 *
 * The followings are the available columns in table '{{movieguide}}':
 * @property integer $id
 * @property integer $movieId
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $baseGetCount
 * @property integer $getCount
 * @property integer $basePvCount
 * @property integer $pvCount
 * @property string $config
 */
class Movieguide extends CActiveRecord
{
    public function getDbConnection()
    {
        return Yii::app()->db_app;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_movieguide';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('movieId, title, config', 'required'),
            array('movieId, status, is_index, baseGetCount, getCount, basePvCount, pvCount', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, movieId, title, is_index, created_at, updated_at, status, baseGetCount, getCount, basePvCount, pvCount, config', 'safe', 'on' => 'search'),
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
            'movieId' => '影片ID',
            'title' => '标题',
            'is_index' => '目录',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态',
            'baseGetCount' => '领取注水',
            'getCount' => '真实领取人数',
            'basePvCount' => 'PV注水人数',
            'pvCount' => '真实PV',
            'config' => 'Config',
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
        $criteria->compare('movieId', $this->movieId);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('is_index', $this->is_index, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('baseGetCount', $this->baseGetCount);
        $criteria->compare('getCount', $this->getCount);
        $criteria->compare('basePvCount', $this->basePvCount);
        $criteria->compare('pvCount', $this->pvCount);
        $criteria->compare('config', $this->config, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC'
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Movieguide the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
