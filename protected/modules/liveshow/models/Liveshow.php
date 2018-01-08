<?php

/**
 * This is the model class for table "{{star_liveshow}}".
 *
 * The followings are the available columns in table '{{star_liveshow}}':
 * @property string $id
 * @property string $name
 * @property string $star_name
 * @property string $avatar_link
 * @property string $movie_id
 * @property string $show_start_t
 * @property string $show_end_t
 * @property string $lanch_start_t
 * @property string $lanch_end_t
 * @property string $ad_title
 * @property string $ad_link
 * @property integer $gift_type
 * @property string $gift_id
 * @property string $real_pv
 * @property string $wx_share_title
 * @property string $qzone_share_title
 * @property string $share_description
 * @property string $forbid_word
 * @property integer $online_status
 * @property string $c_t
 * @property string $u_t
 */

Yii::import('ext.RedisManager', true);

class Liveshow extends CActiveRecord
{

    public function tableName()
    {
        return '{{star_liveshow}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//            array('show_start_t, show_end_t, lanch_start_t, lanch_end_t', 'required'),
            array('gift_type, online_status, is_app',  'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 64),
            array('star_name', 'length', 'max' => 32),
            array('avatar_link, ad_title, ad_link, wx_share_title, qzone_share_title, share_description, forbid_word, beforelive_piclink, sharepic_link, pre_banner_pic,live_banner_pic,pre_page_link,live_page_link', 'length', 'max' => 255),
            array('gift_id, pv', 'length', 'max' => 20),
            array('c_t, u_t, liveshow_content', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, star_name, avatar_link,  show_start_t, show_end_t, lanch_start_t, lanch_end_t, ad_title, ad_link, gift_type, gift_id, wx_share_title, qzone_share_title, share_description, forbid_word, online_status, is_app, pre_banner_pic,live_banner_pic,pre_page_link,live_page_link ,start_time,end_time, c_t, u_t, pv,  liveshow_content, beforelive_piclink, sharepic_link', 'safe', 'on' => 'search'),
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
            'id' => '直播ID',
            'name' => '直播名称',
            'star_name' => '明星',
            'avatar_link' => '头像链接',
            'ad_title' => '推广标题',
            'ad_link' => '推广图片链接',
            'gift_type' => '礼物类型：0选座券，1红包',
            'gift_id' => '选座券ID或者红包ID',
            'wx_share_title' => '微信分享主标题',
            'qzone_share_title' => 'QQ空间分享主标题',
            'share_description' => '分享描述',
            'forbid_word' => '敏感词',
            'online_status' => '上线下线状态：0，上线1，下线,3删除',
            'is_app' => '是否投放APP',
            'pre_banner_pic' => '预热banner图片',
            'live_banner_pic'=>'直播banner图片',
            'pre_page_link' => '预热链接',
            'live_page_link' =>'直播链接',
            'start_time' =>'开始时间',
            'end_time' => '结束时间',
            'c_t' => '创建时间',
            'u_t' => '更新时间',
            'pv' => '观众展示数量',
            'liveshow_content' => '直播介绍',
            'beforelive_piclink' => 'Beforelive Piclink',
            'sharepic_link' => 'Sharepic Link',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('star_name', $this->star_name, true);
        $criteria->compare('avatar_link', $this->avatar_link, true);
        $criteria->compare('ad_title', $this->ad_title, true);
        $criteria->compare('ad_link', $this->ad_link, true);
        $criteria->compare('gift_type', $this->gift_type);
        $criteria->compare('gift_id', $this->gift_id, true);
        $criteria->compare('wx_share_title', $this->wx_share_title, true);
        $criteria->compare('qzone_share_title', $this->qzone_share_title, true);
        $criteria->compare('share_description', $this->share_description, true);
        $criteria->compare('forbid_word', $this->forbid_word, true);
        $criteria->compare('online_status', 0);
//        $criteria->compare('is_app', 0);
        $criteria->compare('c_t', $this->c_t, true);
        $criteria->compare('u_t', $this->u_t, true);
        $criteria->compare('pv', $this->pv, true);
        $criteria->compare('liveshow_content', $this->liveshow_content, true);
        $criteria->compare('beforelive_piclink', $this->beforelive_piclink, true);
        $criteria->compare('sharepic_link', $this->sharepic_link, true);
//        $criteria->compare('pre_banner_pic', $this->pre_banner_pic, 0);
//        $criteria->compare('live_banner_pic', $this->live_banner_pic, 0);
//        $criteria->compare('pre_page_link', $this->pre_page_link, 0);
//        $criteria->compare('live_page_link', $this->live_page_link, 0);
//        $criteria->compare('start_time', $this->start_time, 0);
//        $criteria->compare('end_time', $this->end_time, 0);
        $criteria->order = 'id desc';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Liveshow the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

//    protected function afterSave()
//    {
//        parent::afterSave();
//        echo $this->id;
//    }

    public function getInfoUrl($id)
    {
        return '/liveshow/liveshow/edit?id=' . $id;
    }

    public function getOfflineUrl($id)
    {
        return '/liveshow/liveshow/offline?id=' . $id;
    }

    public function delete($id)
    {
        return $this->deleteByPk($id);
    }

    public function setShareInfo($value)
    {
        //初始化redis逻辑
        $id = $value['streamId'];
        unset($value['streamId']);
        $value['share_url'] = Yii::app()->params['live_show']['liveshowUrl'].$id;
        $config = Yii::app()->params->redis_data['liveshow']['write'];
        $redis = RedisManager::getInstance($config);
        $key = 'streamlive_shareinfo';
        $fild = 'streamId:xinyingshi';
        $redis->setObjectInfo($key, $fild , json_encode($value));
        $redis->set('streamlive_banner',json_encode($value));
//        $redis->hashDel('liveshow_movie', 'liveshow_id:xinyingshi');
    }

}
