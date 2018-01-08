<?php

/**
 * This is the model class for table "{{movie_list}}".
 *
 * The followings are the available columns in table '{{movie_list}}':
 * @property string $id
 * @property string $title
 * @property string $brief
 * @property string $author
 * @property string $author_image
 * @property string $author_desc
 * @property integer $movie_num
 * @property integer $collect_num
 * @property integer $collect_num_really
 * @property integer $read_num
 * @property integer $read_num_really
 * @property string $share_image
 * @property string $share_desc
 * @property string $share_platform
 * @property integer $state
 * @property integer $online_time
 * @property integer $create_time
 * @property integer $update_time
 */
class MovieList extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{movie_list}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, brief, author, author_image, author_desc, share_image, share_title, share_desc, share_platform, create_time, update_time', 'required'),
            array('movie_num, collect_num, collect_num_really, read_num, read_num_really, state, online_time, create_time, update_time', 'numerical', 'integerOnly' => true),
            array('title, brief, author_image, author_desc, share_image, share_title, share_desc', 'length', 'max' => 255),
            array('author, share_platform', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, brief, author, author_image, author_desc, movie_num, collect_num, collect_num_really, read_num, read_num_really, share_image, share_desc, share_title, share_platform, state, online_time, create_time, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'brief' => '描述',
            'author' => '作者-名称',
            'author_image' => '作者-头像',
            'author_desc' => '作者-描述',
            'movie_num' => '影片数量',
            'collect_num' => '注水收藏',
            'collect_num_really' => '真实收藏',
            'read_num' => '注水阅读',
            'read_num_really' => '真实阅读',
            'share_image' => '分享-图标',
            'share_title' => '分享-标题',
            'share_desc' => '分享-描述',
            'share_platform' => '分享-平台',
            'state' => '状态',
            'online_time' => '定时上线',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('brief', $this->brief, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('author_image', $this->author_image, true);
        $criteria->compare('author_desc', $this->author_desc, true);
        $criteria->compare('movie_num', $this->movie_num);
        $criteria->compare('collect_num', $this->collect_num);
        $criteria->compare('collect_num_really', $this->collect_num_really);
        $criteria->compare('read_num', $this->read_num);
        $criteria->compare('read_num_really', $this->read_num_really);
        $criteria->compare('share_title', $this->share_title, true);
        $criteria->compare('share_image', $this->share_image, true);
        $criteria->compare('share_desc', $this->share_desc, true);
        $criteria->compare('share_platform', $this->share_platform, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('online_time', $this->online_time);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('is_del', 1);

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
     * @return MovieList the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getSharePlatForm($iType = 'list') {
        
        $arrType = Yii::app()->params['share_platform'];
        return $arrType;
    }

    public function getStatus($id){
        return isset(MovieList::model()->state)? '上线':'下线';
    }
}
