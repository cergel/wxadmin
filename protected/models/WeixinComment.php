<?php
class WeixinComment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_weixin_comment';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commentId, movieId, uid, showTime', 'required'),
			array('movieId, commentId, uid, editTime', 'numerical', 'integerOnly'=>true),
			array('uid', 'length', 'max'=>64),
			array('content', 'length', 'max'=>120),
			array('id, movieId,commentId,movieName,movieImg, uid, content, uname, showTime, status, editTime, editUser', 'safe'),
			//array('content','HtmlEncode'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movieId,commentId,movieName, uid, content, uname, showTime, status, editTime, editUser', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'commentId'=>'评论ID',
			'content'=>'评论内容',
			'movieId' => '影片ID',
			'movieName'=>'影片名称',
			'movieImg'=>'影片图片',
			'uid' => '用户ID',
			'uname' => '用户昵称',
			'showTime' => '显示时间',
			'status' => '状态',
			'editTime' => '编辑时间',
			'editUser' => '编辑人',
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('movieId',$this->movieId);
		$criteria->compare('movieName',$this->movieName,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('uname',$this->uname,true);
        $criteria->compare('showTime',$this->showTime);
        $criteria->compare('status',$this->status);
		$criteria->compare('editTime',$this->editTime);
		$criteria->compare('editUser', $this->editUser);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
					'pageSize'=>20,
				),
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
		));
	}
	/**
	 * @tutorial 生成json文件
	 * @author liulong
	 */
	public function saveJson()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('status=1');
		$criteria->order = 'showTime DESC' ;
		$arrData = self::model()->findAll($criteria)->toArray();
		print_r($arrData);exit;
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @author liulong
     * 临时静态类
     */
    public function getInfo($whereData = [])
    {
    	if (empty($whereData) || !is_array($whereData)) return false;
    	$where = "";
    	foreach ($whereData as $key=>$val)
    	{
    		if ($where)
    			$where .= " AND `$key` = '$val'";
    		else 
    			$where .= " `$key` = '$val'";
    	}
    	$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE $where")->queryRow();
    	return !empty($info)?$info:false;
    }
    
    // 自动更新时间
    public function beforeSave()
    {
    	$this->editTime = time();
    	$this->editUser = Yii::app()->getUser()->getId();
    	if($this->showTime && !is_numeric($this->showTime))
    		$this->showTime = strtotime($this->showTime);
    	return true;
    }
    public function afterSave()
    {
    	if($this->showTime)
    		$this->showTime = date('Y-m-d H:i:s', $this->showTime);
    }

}
