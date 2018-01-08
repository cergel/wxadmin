<?php
class CommentReply extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_comment_reply';
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commentId, channelId, content', 'required'),
			array('commentId, channelId, fromId, favorCount, status, created, updated', 'numerical', 'integerOnly'=>true),
//			array('uid', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, commentId, uid, channelId, fromId, content, favorCount, status, created, updated', 'safe', 'on'=>'search'),
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
//            'user' => array(self::BELONGS_TO, 'app.models.AppUser', 'uid'),
		);
	}
	public function primaryKey()
	{
		return 'id';
	}
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '回复ID',
            'commentId' => '评论ID',
            'uid' => '用户ID',
            'channelId' => '来源',
            'fromId' => '子渠道',
            'content' => '回复内容',
            'favorCount' => '喜欢人数',
            'status' => '状态 1 正常 0 删除',
            'created' => '创建时间',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('commentId',$this->commentId);
        $criteria->compare('uid',$this->uid,true);
        $criteria->compare('channelId',$this->channelId);
        $criteria->compare('fromId',$this->fromId);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('favorCount',$this->favorCount);
        $criteria->compare('status', 1);
        $criteria->compare('created',$this->created);
        $criteria->compare('updated',$this->updated);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'id DESC',
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

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CommentReply the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return bool
     */
    public function delete() {
        $this->status = 0;
        return $this->save();
    }

}