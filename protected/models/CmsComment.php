<?php

class CmsComment extends CActiveRecord
{
	public $a_info='';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_cms_comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('a_id, content', 'required'),
				array('a_id, id, channel_id, checkstatus, favor_count, status, created, hot_order', 'numerical', 'integerOnly' => true),
				array('id, a_id,open_id,channel_id, content, from, status, content, checkstatus, base_favor_count, favor_count, hot_order, created', 'safe'),
			//array('content','HtmlEncode'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('id, a_id,open_id,channel_id, content, from, status, content, checkstatus, base_favor_count, favor_count, hot_order, created', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => '评论ID',
				'a_id' => '内容ID',
				'open_id' => 'openId',
				'channel_id' => '来源',
				'content' => '评论内容',
				'from' => '子渠道',
				'base_favor_count' => '注水数',
				'favor_count' => '喜欢数',
				'status' => '状态',
				'created' => '创建时间',
				'checkstatus' => '审核状态',
				'hot_order' => '排序',
				'a_info'=>'CMS标题',
		);
	}

	public function getchannelId($id = '')
	{
		$array = ['' => '全部', '3' => '微信电影票', '8' => 'IOS', '9' => '安卓', '10' => 'PC'];
		if (!empty($id)) {
			if (!empty($array[$id])) return $array[$id];
			else return '';
		} else {
			return $array;
		}

	}

	public function getCheckstatus($id = '')
	{
		$data = ['' => '全部', '0' => '未审核', '1' => '已审核', '3' => '含敏感词'];
		if ($id === '') {
			unset($data['']);
		} else if (!empty($data["$id"])) {
			$data = $data["$id"];
		}
		return $data;
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
		$criteria->compare('a_id', $this->a_id);
		$criteria->compare('channel_id', $this->channel_id);
		$criteria->compare('open_id', $this->open_id);
		$criteria->compare('from', $this->from);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('base_favor_count', $this->base_favor_count);
		$criteria->compare('hot_order', $this->hot_order);
		$criteria->compare('favor_count', $this->favor_count);
		$criteria->compare('status', $this->status);
		if (empty($this->content)) {
			if ($this->checkstatus === 0) {
				$criteria->addInCondition('checkstatus', ['0', '3']);
			} else {
				$criteria->compare('checkstatus', $this->checkstatus);
			}
		}
		if ($this->checkstatus != '1') {
			$criteria->addCondition("checkstatus != 1 ");
		}
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
				'pagination' => array(
						'pageSize' => 100,
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
		return Yii::app()->db_app;
	}
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return bool
	 */
	public function delete($id='')
	{
		$arrData = ['commentId' => $this->id];
		$arrData = Https::getPost($arrData, Yii::app()->params['comment']['cms_comment_del']);
		$arrData = json_decode($arrData, true);
		return $arrData;
	}
	/**
	 * @return bool
	 */
	public function checkstatus()
	{
		$this->checkstatus = 1;
		$result = $this->save();
		return $result;
	}

	/**
	 * 修改
	 * @return bool
	 */
	public function saveComment()
	{
		$arrData = ['commentId' => $this->id];
		$arrData['baseFavorCount'] = $this->base_favor_count;
		$arrData['content'] = $this->content;
		$arrData['checkstatus'] = $this->checkstatus;
		$arrData = Https::getPost($arrData, Yii::app()->params['comment']['cms_comment_save']);
		return json_decode($arrData, true);

	}


}
