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
            array('commentId, uid, channelId, fromId, content', 'required'),
            array('commentId, channelId, fromId, favorCount, status, created, updated', 'numerical', 'integerOnly'=>true),
            array('uid', 'length', 'max'=>64),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, commentId, uid,checkstatus, channelId, fromId, content, favorCount, status, created, updated', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'app.models.AppUser', 'uid'),
        );
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
            'favorCount' => '喜欢数',
            'status' => '状态 1 正常 0 删除',
            'checkstatus'=>'审核状态',
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
        $criteria->compare('status', '1');
        //$criteria->compare('created',$this->created);
        if(empty($this->content)){
            if ($this->checkstatus === 0){
                $criteria->addInCondition('checkstatus', ['0','3']);
            }else {
                $criteria->compare('checkstatus',$this->checkstatus);
            }
        }
        if($this->checkstatus != '1'){
            $criteria->addCondition("checkstatus != 1 ");
        }

        //var_dump($this->checkstatus);exit;
        //$criteria->compare('updated','>='. strtotime ($this->updated));

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>100,
            ),
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
        $arrData = ['channelId'=>$this->channelId,'replyId'=>$this->id,'fromId'=>'123456789'];
        $arrData = Https::getPost($arrData,Yii::app()->params['comment']['reply_del']);
        $arrData = json_decode($arrData,true);
        if(!empty($arrData['ret'])){
            return false;
        }
        $sql = "update t_comment_reply set status=0 where id = ". $this->id;
        $result = CommentReply::model()->saveSql($sql);
        $comment = Comment::model()->findByPk($this->commentId);
        $comment->saveCounters(array('replyCount'=>-1));
        return $result;
    }
    /**
     * @return bool
     */
    public function checkstatus() {

        $this->checkstatus = 1;
        $result = $this->save();
        print_r($result);exit;
        return $result;
    }
    /**
     * 根据条件查询出指定数据（字段）
     * @param string $find
     * @param string $where
     * @return array
     */
    public function getWhereList($find='id',$where = '1=1')
    {
        $sql ="SELECT $find FROM {$this->tableName()}  WHERE $where ;";
        return $this->getDbConnection()->createCommand($sql)->queryAll();
    }


    /**
     * 这个不要用，为新用户填坑用得
     */
    public static function getDeleteCommentReplyLimit()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition("status=1 AND uid > '200000000'");
        $reply = self::model()->findAll($criteria);
        foreach ($reply as $val)
        {
            $val->status = 0;
            $val->save();
        }
    }

    public function saveSql($sql)
    {
        $this->getDbConnection()->createCommand($sql)->execute();
    }

}