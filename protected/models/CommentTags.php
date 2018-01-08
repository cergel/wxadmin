<?php

/**
 *
 * 评论-标签关联表
 */
class CommentTags extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_comment_tags';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commentId, tagId', 'required'),
//			array('id, created, commentId,tagId', 'numerical', 'integerOnly'=>true),
			array('id, created, commentId,tagId', 'safe'),
			array('id, created, commentId,tagId', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'commentId' => '评论ID',
			'tagId' => '标签id',
			'created' => '创建时间',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('commentId',$this->commentId);
		$criteria->compare('tagId',$this->tagId);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	 * @return CommentTag the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 获取标签信息
	 * @param string $commentId
	 * @param bool|false $isArray
	 * @return array|CDbDataReader|string
	 */
	public function getCommentTags($commentId='',$isArray = false)
	{
		$str = '';
		$arrTagList = CommentTag::model()->getTagList();
		$sql = "select commentId,tagId from {$this->tableName()} WHERE  commentId = '$commentId'";
		$commentTag = $this->getDbConnection()->createCommand($sql)->queryAll();
		if($isArray){
			if(!empty($commentTag)){
				foreach($commentTag as &$val){
					$val = $val['tagId'];
				}
				$str = $commentTag;
			}else{
				$str =[];
			}
		}else{
			if(!empty($commentTag)){
				foreach ($commentTag as $val) {
					$str .=isset($arrTagList[$val['tagId']])?$arrTagList[$val['tagId']].'、':'';
				}
			}
		}
		return $str;
	}

	public function saveCommentTagList($id,$type){

	}

}
