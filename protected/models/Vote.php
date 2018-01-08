<?php

/**
 * This is the model class for table "{{vote}}".
 *
 * The followings are the available columns in table '{{vote}}':
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $picture
 * @property string $share_picture
 * @property string $share_title
 * @property string $share_content
 * @property string $share_platform
 * @property string $end_time
 * @property integer $end_flag
 * @property string $created
 * @property string $updated
 */
class Vote extends CActiveRecord
{

    public $voteNum;
    public $voteStatus;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, share_content,share_picture,share_title,share_platform, end_time, created, updated', 'required'),
			array('end_flag', 'numerical', 'integerOnly'=>true),
			array('name, type, picture, share_picture, share_platform', 'length', 'max'=>255),
                        array('share_title','length','max'=>15),
//                        array('option1,option2','length','max'=>35),
			array('end_time, created', 'length', 'max'=>10),                   
			array('updated', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, picture, share_picture, share_title, share_content, share_platform, end_time, end_flag, created, updated', 'safe', 'on'=>'search'),
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
//                'cinemas'=>array(self::HAS_MANY, 'AdCinema', 'iAdID'),
//                'movies'=>array(self::HAS_MANY, 'AdMovie', 'iAdID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '投票名称',
			'type' => '类型',
			'picture' => '图片',
                        'movie_id' => '关联影片',
			'share_picture' => '分享图片',
			'share_title' => '分享标题',
			'share_content' => '分享内容',
			'share_platform' => '分享平台',
			'end_time' => '结束时间',
			'end_flag' => '结束标志',
			'created' => '创建时间',
			'updated' => '更新时间',
<<<<<<< Updated upstream
            'voteNum'=>'投票人数',
            'voteStatus'=>'投票状态',
//                    'option1' => '选项1',
//                    'option2' => '选项2',
=======
                        'voteNum'=>'投票人数',
                        'voteStatus'=>'投票状态',
>>>>>>> Stashed changes
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('share_picture',$this->share_picture,true);
		$criteria->compare('share_title',$this->share_title,true);
		$criteria->compare('share_content',$this->share_content,true);
		$criteria->compare('share_platform',$this->share_platform,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('end_flag',$this->end_flag);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                            'defaultOrder'=>'id DESC',
                        )
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_active;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getVoteNum($voteId){
        return VoteMembers::model()->count("vote_id = :vote_id",array(":vote_id"=>$voteId));
    }

    public function getVoteStatus($endTime){
        return (time()<$endTime) ? '进行中' : '截止';
    }

    public function getInfoUrl($id){
        return '/vote/update?id='.$id;
    }
    
    /**
     * 获取弹出框内容
     * @param $id
     * @return string
     */
    public function getDialogInfo($id)
    {
        $str ="";
        //现在显示全部的
        $str .= "&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"" . Yii::app()->params['vote_template']['final_url'] . "?voteId={$id}\">";
        $str .= Yii::app()->params['vote_template']['final_url'] . "?voteId={$id}</a>";
        if (empty($str)){
            $str ="没有生成选择任何模板";
        }
        return $str;
    }
}
