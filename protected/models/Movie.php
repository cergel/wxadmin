<?php
Yii::import('ext.RedisManager', true);
class Movie extends CActiveRecord
{


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_movie';
	}
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
			array('id,  baseScore, baseScoreCount, scoreFillNum0,scoreFillNum20,scoreFillNum40,scoreFillNum60,scoreFillNum80,scoreFillNum100,scoreRealNum0,scoreRealNum20,scoreRealNum40,scoreRealNum60,scoreRealNum80,scoreRealNum100, score, scoreCount, commentCount, baseWantCount, wantCount, seenCount, created, updated', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movieName,movieDate, baseScore, baseScoreCount, score, scoreCount, commentCount, baseWantCount, wantCount, seenCount, created, updated', 'safe', 'on'=>'search'),
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
			'movieDate'=>'上映日期',
			'baseScore' => '基础分数',
			'baseScoreCount' => '基础评分人数',
            'scoreFillNum0' => '烂片<span style="color:red">0</span>分',
            'scoreFillNum20' => '失望<span style="color:red">2</span>分',
            'scoreFillNum40' => '睡着<span style="color:red">4</span>分',
            'scoreFillNum60' => '一般<span style="color:red">6</span>分',
            'scoreFillNum80' => '不错<span style="color:red">8</span>分',
            'scoreFillNum100' => '超赞<span style="color:red">10</span>分',
			'score' => '当前评分',
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
		$criteria->compare('movieName',$this->movieName,true);
		$criteria->compare('movieDate',$this->movieDate,true);
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
				'pagination'=>array(
						'pageSize'=>20,
				),
						'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
					));
	}

    /**
     * 提供给controller和model使用的影片搜索
     */
    public function searchMovieByNameForCtrl($movieName)
    {
        $sql = "select * from {$this->tableName()} where movieName like '%{$movieName}%'";
        $obj = $this->getDbConnection()->createCommand($sql)->query();
        $result = $obj->readAll();
        return $result;
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

    //保存后执行，执行保存缓存
    public function afterSave(){
        $this->setCache();
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
//        $totalScore = $this->getIsNewRecord() ? 0 : $this->getTotalScore();
//        $totalCount = $this->getIsNewRecord() ? 0 : $this->getScoreCount();
//        if ($totalCount + $this->baseScoreCount != 0) {
//            $score = intval(($totalScore + ($this->baseScore * $this->baseScoreCount))/($totalCount + $this->baseScoreCount));
//        } else {
//            $score = 0;
//        }
//        $this->score = $score;

        //新版计算评分
        $scoreRealNum0 = $this->scoreRealNum0;
        $scoreRealNum20 = $this->scoreRealNum20;
        $scoreRealNum40 = $this->scoreRealNum40;
        $scoreRealNum60 = $this->scoreRealNum60;
        $scoreRealNum80 = $this->scoreRealNum80;
        $scoreRealNum100 = $this->scoreRealNum100;

        $scoreFillNum0 = $this->scoreFillNum0;
        $scoreFillNum20 = $this->scoreFillNum20;
        $scoreFillNum40 = $this->scoreFillNum40;
        $scoreFillNum60 = $this->scoreFillNum60;
        $scoreFillNum80 = $this->scoreFillNum80;
        $scoreFillNum100 = $this->scoreFillNum100;

        $totalScore = 0 + 20*($scoreRealNum20+$scoreFillNum20) + 40*($scoreRealNum40+$scoreFillNum40) + 60*($scoreRealNum60+$scoreFillNum60) + 80*($scoreRealNum80+$scoreFillNum80) + 100*($scoreRealNum100+$scoreFillNum100);
        $totalNum = $scoreRealNum0+$scoreFillNum0+$scoreRealNum20+$scoreFillNum20+$scoreRealNum40+$scoreFillNum40+$scoreRealNum60+$scoreFillNum60+$scoreRealNum80+$scoreFillNum80+$scoreRealNum100+$scoreFillNum100;
        if($totalNum<0){
            header("Content-type: text/html; charset=utf-8");
            echo '错误！！总人数不可以为负数';die;
        }
        if($totalNum==0){
            header("Content-type: text/html; charset=utf-8");
            echo '错误！！总人数不可以为0';die;
        }
        if($totalScore<0){
            header("Content-type: text/html; charset=utf-8");
            echo '错误！！总分数不可以为负数';die;
        }

        $newScore = ceil($totalScore/$totalNum);
        if($newScore>100){
            header("Content-type: text/html; charset=utf-8");
            echo '错误！！最终分数不可以大于100';die;
        }

        $this->score = $newScore;
    }



    /**
     * 切换接口地址
     * @tutorial 通过接口，获取影片信息－－－新接口
     * @param unknown $mivieId
     */
    public function getMovieInfo($movieId)
    {
        $arrPostData = ['movieId'=>$movieId,'from'=>'100'];
        $url = Yii::app()->params['movie']['getMovieInfo']."?";
        $arrData = ['movieId'=>$movieId,'channelId'=>3,'actorInfo'=>1,'from'=>110112];
        $url .=http_build_query($arrData);
        $arrData =  file_get_contents($url);
        $arrData = json_decode($arrData,true);
        return !empty($arrData['data'])?$arrData['data']:false;
    }
    
    /**
     * @tutorial 接口－post请求,都是post请求
     * @author liulong
     * @param unknown $arrPostData
     * @param unknown $url
     * @return mixed|Ambigous <mixed, string>
     */
    public static function getApp($arrPostData,$url)
    {
        //生成sign签名
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //CURLOPT_HTTPHEADER => $headers,
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data,true);
        return $data;
    }

    //评论注水设置缓存
    protected function setCache(){
//        $config = Yii::app()->params->redis_data['comment']['write'];
//        $redis = RedisManager::getInstance($config);
//        $redisKey = "newcomment_score_fill_number_movie:{$this->id}";
        $hashKey = array(
            '0'=>$this->scoreFillNum0,
            '20'=>$this->scoreFillNum20,
            '40'=>$this->scoreFillNum40,
            '60'=>$this->scoreFillNum60,
            '80'=>$this->scoreFillNum80,
            '100'=>$this->scoreFillNum100
        );
        $hashKey = json_encode($hashKey);
        $arrData = [];
        $arrData['movieId'] = $this->id;
        $arrData['channelId'] = 8;
        $arrData['content'] = $hashKey;
        $arrData['from'] = '123123132';
        Log::model()->logFile('movie_base_score',json_encode($arrData));
        $arrData = Https::getPost($arrData,Yii::app()->params['comment']['save_movie_base_score']);
        Log::model()->logFile('movie_base_score',json_encode($arrData));
//        $redis->hashMset($redisKey,$hashKey);
    }
}
