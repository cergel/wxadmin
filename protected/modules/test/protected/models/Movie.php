<?php
class Movie extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_movie';
	}
	var $movieName ='暂不支持查询';
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('id', 'required'),
            array('id', 'unique'),
			array('id, baseScore, baseScoreCount, score, scoreCount, commentCount, baseWantCount, wantCount, seenCount, created, updated', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, baseScore, baseScoreCount, score, scoreCount, commentCount, baseWantCount, wantCount, seenCount, created, updated', 'safe', 'on'=>'search'),
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
			'id' => '影片ID',
			'movieName' =>'影片名称',
			'baseScore' => '基础分数',
			'baseScoreCount' => '基础评分人数',
			'score' => '计算过的评分',
			'scoreCount' => '参与评分的人数',
			'commentCount' => '参与评论人数',
            'baseWantCount' => '初始想看人数',
			'wantCount' => '想看人数',
			'seenCount' => '看过人数',
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
		$criteria->compare('baseScore',$this->baseScore);
		$criteria->compare('baseScoreCount',$this->baseScoreCount);
		$criteria->compare('score',$this->score);
		$criteria->compare('scoreCount',$this->scoreCount);
		$criteria->compare('commentCount',$this->commentCount);
        $criteria->compare('baseWantCount',$this->wantCount);
        $criteria->compare('wantCount',$this->wantCount);
		$criteria->compare('seenCount',$this->seenCount);
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
	 * @return Movie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave()
    {
        // 计算评分
        $this->calcScore();

        // 自动处理时间
        $this->getIsNewRecord() && $this->created = time();
        $this->updated = time();
        return parent::beforeSave();
    }

    public function getTotalScore()
    {
        $connection = Yii::app()->db_app;
        $command = $connection->createCommand("
            select sum(`score`) as total from t_score where movieId='{$this->id}'
        ");
        $row = $command->queryRow();
        return $row['total'];
    }

    public function getScoreCount()
    {
        $connection = Yii::app()->db_app;
        $command = $connection->createCommand("
            select count(*) as total from t_score where movieId='{$this->id}'
        ");
        $row = $command->queryRow();
        return $row['total'];
    }

    // 计算评分
    public function calcScore () {
        $totalScore = $this->getIsNewRecord() ? 0 : $this->getTotalScore();
        $totalCount = $this->getIsNewRecord() ? 0 : $this->getScoreCount();
        if ($totalCount + $this->baseScoreCount != 0) {
            $score = intval(($totalScore + ($this->baseScore * $this->baseScoreCount))/($totalCount + $this->baseScoreCount));
        } else {
            $score = 0;
        }
        $this->score = $score;
    }
}
