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
 * @property string $real_gift_number
 * @property string $real_quest_number
 * @property string $wx_share_title
 * @property string $qzone_share_title
 * @property string $share_description
 * @property string $forbid_word
 * @property integer $online_status
 * @property string $c_t
 * @property string $u_t
 */
class StarLiveshow extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_star_liveshow';
		//return '{{star_liveshow}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gift_type, online_status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('star_name', 'length', 'max'=>32),
			array('avatar_link, ad_title, ad_link, wx_share_title, qzone_share_title, share_description, forbid_word', 'length', 'max'=>255),
			array('movie_id, gift_id, real_pv, real_gift_number, real_quest_number', 'length', 'max'=>20),
			array('show_start_t, show_end_t, lanch_start_t, lanch_end_t, c_t, u_t', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, star_name, avatar_link, movie_id, show_start_t, show_end_t, lanch_start_t, lanch_end_t, ad_title, ad_link, gift_type, gift_id, real_pv, real_gift_number, real_quest_number, wx_share_title, qzone_share_title, share_description, forbid_word, online_status, c_t, u_t', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键ID',
			'name' => '直播名称',
			'star_name' => '明星',
			'avatar_link' => '头像链接',
			'movie_id' => '影片ID',
			'show_start_t' => '直播开始时间',
			'show_end_t' => '直播结束时间',
			'lanch_start_t' => '投放开始时间',
			'lanch_end_t' => '投放结束时间',
			'ad_title' => '推广标题',
			'ad_link' => '推广图片链接',
			'gift_type' => '礼物类型：0选座券，1红包',
			'gift_id' => '选座券ID或者红包ID',
			'real_pv' => '实际观众数',
			'real_gift_number' => '实际礼物数',
			'real_quest_number' => '实际提问人数',
			'wx_share_title' => '微信分享主标题',
			'qzone_share_title' => 'QQ空间分享主标题',
			'share_description' => '分享描述',
			'forbid_word' => '敏感词',
			'online_status' => '上线下线状态：0，上线1，下线,3删除',
			'c_t' => '创建时间',
			'u_t' => '更新时间',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('star_name',$this->star_name,true);
		$criteria->compare('avatar_link',$this->avatar_link,true);
		$criteria->compare('movie_id',$this->movie_id,true);
		$criteria->compare('show_start_t',$this->show_start_t,true);
		$criteria->compare('show_end_t',$this->show_end_t,true);
		$criteria->compare('lanch_start_t',$this->lanch_start_t,true);
		$criteria->compare('lanch_end_t',$this->lanch_end_t,true);
		$criteria->compare('ad_title',$this->ad_title,true);
		$criteria->compare('ad_link',$this->ad_link,true);
		$criteria->compare('gift_type',$this->gift_type);
		$criteria->compare('gift_id',$this->gift_id,true);
		$criteria->compare('real_pv',$this->real_pv,true);
		$criteria->compare('real_gift_number',$this->real_gift_number,true);
		$criteria->compare('real_quest_number',$this->real_quest_number,true);
		$criteria->compare('wx_share_title',$this->wx_share_title,true);
		$criteria->compare('qzone_share_title',$this->qzone_share_title,true);
		$criteria->compare('share_description',$this->share_description,true);
		$criteria->compare('forbid_word',$this->forbid_word,true);
		$criteria->compare('online_status',$this->online_status);
		$criteria->compare('c_t',$this->c_t,true);
		$criteria->compare('u_t',$this->u_t,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StarLiveshow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
