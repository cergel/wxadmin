<?php

class Comment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_comment';
	}

	public $tag;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('movieId, uid', 'required'),
				array('movieId, channelId, fromId, baseFavorCount, favorCount, replyCount, status, created, updated,comment_order', 'numerical', 'integerOnly' => true),
				array('uid', 'length', 'max' => 64),
				array('id, movieId,checkstatus,movieName, uid, channelId, fromId, content, baseFavorCount, favorCount, replyCount, status, created, updated,comment_order', 'safe'),
			//array('content','HtmlEncode'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('id, movieId,checkstatus,movieName, uid, channelId, fromId, content, baseFavorCount, favorCount, replyCount, status, created, updated,comment_order', 'safe', 'on' => 'search'),
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
				'id' => '评论ID',
				'movieId' => '影片ID',
				'movieName' => '影片名称',
				'checkstatus' => '审核状态',
				'uid' => '用户ID',
				'channelId' => '来源',
				'fromId' => '子渠道',
				'content' => '评论内容',
				'baseFavorCount' => '初始喜欢人数',
				'favorCount' => '喜欢数',
				'replyCount' => '回复数',
				'status' => '状态',
				'created' => '创建时间',
				'updated' => '更新时间',
				'comment_order' => '排序',
				'score'=>'评分',
				'tag'=>'标签',
		);
	}

	public function getchannelId($id = '')
	{
		$array = ['' => '全部', '3' => '微信电影票', '8' => 'IOS', '9' => '安卓', '28' => '手Q'];
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
		$criteria->compare('movieId', $this->movieId);
//		$criteria->compare('movieName', $this->movieName, true);
		$criteria->compare('ucid', $this->ucid);
		$criteria->compare('channelId', $this->channelId);
//		$criteria->compare('fromId', $this->fromId);
		$criteria->compare('content', $this->content, true);
//		$criteria->compare('baseFavorCount', $this->baseFavorCount);
		$criteria->compare('favorCount', $this->favorCount);
		$criteria->compare('replyCount', $this->replyCount);
		$criteria->compare('status', $this->status);
//		$criteria->addCondition("status != 2 ");
		if (empty($this->content)) {
//			if ($this->checkstatus === 0) {
//				$criteria->addInCondition('checkstatus', ['0', '3']);
//			} else {
//				$criteria->compare('checkstatus', $this->checkstatus);
//			}
			$criteria->compare('checkstatus', $this->checkstatus);
		}
//		if ($this->checkstatus != '1') {
//			$criteria->addCondition("checkstatus != 1 ");
//		}

//		$criteria->compare('created', $this->created);
//		$criteria->compare('updated', '>=' . strtotime($this->updated));
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
				'pagination' => array(
						'pageSize' => 20,
				),
				'sort' => array(
						'defaultOrder' => 'id DESC',
				),
		));
	}

	public function updateMovieName($movieId, $movieName)
	{
		if (empty($movieId) || empty($movieName)) return;
		$this->getDbConnection()->createCommand(" UPDATE {$this->tableName()} SET movieName = '$movieName' WHERE movieId = '$movieId'")->execute();
		return;
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_app;
	}

	public function getCommentContent($id)
	{
		$model = $this->model()->findByPk($id);
		return empty($model->content) ? '' : $model->content;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Comment the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		$this->status = 0;
		$arrData = ['channelId' => $this->channelId, 'commentId' => $this->id, 'fromId' => '123456789'];
		$arrData = Https::getPost($arrData, Yii::app()->params['comment']['comment_del']);
		$arrData = json_decode($arrData, true);
		if (!empty($arrData['ret'])) {
			return false;
		}
		$result = $this->save();
		if ($result) {

			$movie = Movie::model()->findByPk($this->movieId);
			$movie->saveCounters(array('commentCount' => -1));
		}
		return $result;
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
	 * 根据条件查询出指定数据（字段）
	 * @param string $find
	 * @param string $where
	 * @return array
	 */
	public function getWhereList($find = 'id', $where = '1=1')
	{
		$sql = "SELECT $find FROM {$this->tableName()}  WHERE $where ;";
		return $this->getDbConnection()->createCommand($sql)->queryAll();
	}

	/**
	 * @tutorial接口获取影片名称,
	 * @param unknown $filmId
	 * @return CActiveDataProvider
	 * @author liulong
	 * 临时静态类
	 */
	public function getFilmName($filmId = 0, $type = '')
	{
		$arrPostData = [
				'cityId' => '10',
				'movieId' => $filmId,
		];
		$url = 'http://androidcgi.wepiao.com/movie/info';
		$data = $this->getApp($arrPostData, $url);
		if (empty($type)) {
			$data = empty($data['data']['name']) ? '暂无数据' : $data['data']['name'];
		} else {
			$resData['name'] = empty($data['data']['name']) ? '暂无数据' : $data['data']['name'];
			$resData['date'] = empty($data['data']['date']) ? '' : $data['data']['date'];
			$data = $resData;
		}
		return $data;
	}

	/**
	 *
	 * @param number $filmId
	 * @param string $type
	 * @return Ambigous <string, mixed, Ambigous>
	 */
	public function getFilmInfo($filmId = 0)
	{
		$arrPostData = [
				'cityId' => '10',
				'movieId' => $filmId,
		];
		$url = 'http://androidcgi.wepiao.com/movie/info';
		$data = $this->getApp($arrPostData, $url);
		if (!empty($data) && $data['ret'] == 0) {
			$data = $data['data'];
		} else $data = false;
		return $data;
	}

	/**
	 * @tutorial接口获取用户信息,
	 * @param unknown $uid
	 * @return CActiveDataProvider
	 * @author liulong
	 * 临时静态类
	 */
	public function getUserInfo($uid = 0)
	{
		if (empty($uid)) return false;
		$arrPostData = [
				'uid' => $uid,
		];
		$url = 'http://ioscgi.wepiao.com/user/get-userinfo-by-uid';
		$data = $this->getApp($arrPostData, $url);
		if (!empty($data) && $data['ret'] == 0) {
			$data = $data['data'];
		} else $data = false;
		return $data;
	}

	/**
	 * @tutorial 获取指定城市内相应的影院
	 * @param number $cityId
	 * @param unknown $status
	 * @param unknown $data
	 * @return unknown
	 * @author liulong
	 */
	public function getAllFilmName($cityId = 0, $status, $data = [])
	{
		$status = ($status == '2') ? 2 : 1;
		$arrPostData = [
				'cityId' => $cityId,
				'status' => $status,
		];
		$url = 'http://androidcgi.wepiao.com/movie/list';
		$datas = $this->getApp($arrPostData, $url);
		if ($datas['ret'] == '0') {
			$datas = $datas['data'];
			foreach ($datas as $val) {
				$data[$val['id']] = $val['name'];
			}
		}
		return $data;
	}

	/**
	 * @tutorial 接口获取资源
	 * @author liulong
	 * @param unknown $arrPostData
	 * @param unknown $url
	 * @return mixed|Ambigous <mixed, string>
	 */
	public static function getApp($arrPostData, $url)
	{
		//统一拼凑必传字段
		$arrPostData['appkey'] = '10';
		$arrPostData['v'] = '2015061201';
		$arrPostData['from'] = '9876543210';
		$arrPostData['t'] = time();
		$arrPostData['platForm'] = '1';
		//生成sign签名
		ksort($arrPostData);
		$strKey = urldecode(http_build_query($arrPostData));
		$strMd5 = MD5('jsIa9jL10Vxa9HMlEb9E4Fa15f' . $strKey);
		$arrPostData['sign'] = strtoupper($strMd5);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//CURLOPT_HTTPHEADER => $headers,
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostData);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data, true);
		return $data;

//     	if (empty($type)){
//     		$data = empty($data['data']['name'])?'暂无数据':$data['data']['name'];
//     	}else {
//     		$resData['name'] =  empty($data['data']['name'])?'暂无数据':$data['data']['name'];
//     		$resData['date'] =  empty($data['data']['date'])?'':$data['data']['date'];
//     		$data = $resData;
//     	}
		return $data;
	}

	/**
	 * 这个不要用，为新用户填坑用得
	 */
	public static function getDeleteCommentLimit()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("status=1 AND uid > '200000000'");
		$comment = self::model()->findAll($criteria);
		foreach ($comment as $val) {
			$val->status = 0;
			$val->save();
		}
	}

	/**评论统计
	 * @param $start_time
	 * @param $end_time
	 * @param $channel
	 * @return array
	 */
	public function searchData($start_time, $end_time, $channel){
		$arrSql = $this->getSql($start_time, $end_time, $channel);
		$scoreResult = Yii::app()->db_app->createCommand($arrSql[0])->queryAll();
		$wantResult = Yii::app()->db_app->createCommand($arrSql[1])->queryAll();
		$seenResult = Yii::app()->db_app->createCommand($arrSql[2])->queryAll();
		$commentResult = Yii::app()->db_app->createCommand($arrSql[3])->queryAll();
		$purchaseResult = Yii::app()->db_app->createCommand($arrSql[4])->queryAll();
		$pushResult = Yii::app()->db_app->createCommand($arrSql[5])->queryAll();
		$replyResult = Yii::app()->db_app->createCommand($arrSql[6])->queryAll();
		$favorResult = Yii::app()->db_app->createCommand($arrSql[7])->queryAll();
		$arrResult = $this->collectionDt([$wantResult , $seenResult , $commentResult,$purchaseResult,$pushResult,$scoreResult]);
		$arrResult = $this->fillData($arrResult , $wantResult , 'wantCount');
		$arrResult = $this->fillData($arrResult , $seenResult , 'seenCount');
		$arrResult = $this->fillData($arrResult , $commentResult , 'commentCount');
		$arrResult = $this->fillData($arrResult , $purchaseResult , 'purchaseCount');
		$arrResult = $this->fillData($arrResult , $pushResult , 'pushCount');
		$arrResult = $this->fillData($arrResult , $scoreResult , 'scoreCount');
		$arrResult = $this->fillData($arrResult , $replyResult , 'replyCount');
		$arrResult = $this->fillData($arrResult , $favorResult , 'favorCount');
		foreach($arrResult as $key => $result) {
			// 防止后续出现当天可能未评分的
			for($j=1 ; $j <= 6 ; $j++) {
				$v='score_'.$j;
				$arrResult[$key][$v] = 0;
			}
			foreach ($scoreResult as $score) {
				// 当时间相等时
				if ($key == $score['dt']) {
					if ($score['score'] == 100) {
						$arrResult[$key]['score_1'] = $score['scoreCount'];
					}if ($score['score'] == 80) {
						$arrResult[$key]['score_2'] = $score['scoreCount'];
					}else if ($score['score'] == 60) {
						$arrResult[$key]['score_3'] = $score['scoreCount'];
					}else if ($score['score'] == 40) {
						$arrResult[$key]['score_4'] = $score['scoreCount'];
					}else if ($score['score'] == 20) {
						$arrResult[$key]['score_5'] = $score['scoreCount'];
					}else if ($score['score'] == 0) {
						$arrResult[$key]['score_6'] = $score['scoreCount'];
					}
				}
			}
			$score_sum = 0;
			for($j=1 ; $j <= 6 ; $j++) {
				$v='score_'.$j;
				$score_sum +=  $arrResult[$key][$v];
			}
			$arrResult[$key]['scoreCount'] = $score_sum;
		}
		ksort($arrResult);
		return $arrResult;

	}

	/**拼接以天为单位的查询数据
	 * @param array $arrResult  查询到的所有日期数组
	 * @param array $queryResult sql查询结果
	 * @param $keyName 列名
	 * @return array
	 */
	private function fillData(array $arrResult ,array $queryResult ,  $keyName) {
		$flag = false;
		foreach($arrResult as $key => $result) {
			for ($i=0;$i < count($queryResult); $i++) {
				if($queryResult[$i]['dt'] == $key) {
					$index = $i;
					$flag = true;
					break;
				}
			}
			if($flag) {
				$arrResult[$key][$keyName] = $queryResult[$i][$keyName];
			}else {
				$arrResult[$key][$keyName] = 0;
			}
			$flag = false;
		}
		return $arrResult;
	}

	/**
	 * 日期收集，收集所有日期
	 * @param array $results
	 * @return array
	 */
	private function collectionDt(array $results ) {
		$arrResult =[];
		foreach($results as $result) {
			foreach($result as $e) {
				if(!array_key_exists($e['dt'] , $arrResult)) {
					$arrResult[$e['dt']] = [];
				}
			}
		}
		return $arrResult;
	}

	/**获取每条完整的查询sql
	 * @param $start_time
	 * @param $end_time
	 * @param $channel
	 */
	public function getSql($start_time, $end_time, $channel)
	{
		$sql = [];
		//评分表情
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as scoreCount', 'score'], 't_score', [], ['score']);
		// 得到想看数的sql
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as wantCount'], 't_want', [], []);
		//看过数
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as seenCount'], 't_seen', [], []);
		//评论数
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as commentCount'], 't_comment', [], []);
		//购票用户评论数
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as purchaseCount', 'seen_movie'], 't_comment', ["seen_movie='1'"], []);
		//push评论量
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as pushCount', 'fromId'], 't_comment', ["fromId='2046000001'"], []);
		//回复量
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as replyCount'], 't_comment_reply', [], []);
		//点赞
		$sql[] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as favorCount'], 't_favor_comment', [], []);
		return $sql;
	}

	/**拼接sql语句
	 * @param $start_time 开始时间
	 * @param $end_time   结束时间
	 * @param $channel    渠道号
	 * @param array $querys 查找的列名
	 * @param $table  表名
	 * @param array $conditions where 条件
	 * @param array $groupConditions 分组条件
	 * @return string
	 */
	public function getSqlByTemplate($start_time, $end_time, $channel, array $querys, $table, array $conditions, array $groupConditions)
	{
		$sql = "select FROM_UNIXTIME(created,'%Y%m%d' ) as dt ";
		// 拼接查询出来的值
		$sql = $this->sqlJoin($sql, ',', $querys);
		if (!empty($table)) {
			$sql .= ' from ' . $table;
		}
		$sql .= " where FROM_UNIXTIME(created,'%Y%m%d' ) >= " . $start_time . " and FROM_UNIXTIME(created,'%Y%m%d' ) <=" . $end_time;
		if (!empty($channel)) {
			$sql .= " and channelId=" . $channel;
		}
		// 拼接and条件
		$sql = $this->sqlJoin($sql, ' and ', $conditions);

		$sql .= " GROUP BY dt";
		// 拼接group by条件
		$sql = $this->sqlJoin($sql, ',', $groupConditions);
		return $sql;
	}

	/**
	 * 通过传入的连接符连接sql
	 * @param $sql
	 * @param $join
	 * @param array $conditions
	 */
	public function sqlJoin($sql, $join, array $conditions)
	{
		if (!empty($conditions)) {
			foreach ($conditions as $condition) {
				$sql .= $join . $condition;
			}
		}
		return $sql;
	}

	/**
	 * 显示评分
	 * @param $movieId
	 * @param $ucid
	 * @return string
	 */
	public function getScoreInfo($movieId,$ucid)
	{
		$arrList = ['0'=>'烂片','20'=>'失望','40'=>'睡着','60'=>'一般','80'=>'不错','100'=>'超赞',];
		$return = '-';
		$sql = "select score from t_score where movieId='$movieId' AND  ucid='$ucid'";
		$arrData = $this->getDbConnection()->createCommand($sql)->queryAll();
		if(!empty($arrData)){
			$return = !empty($arrList[$arrData[0]['score']])?$arrList[$arrData[0]['score']]:'-';
		}
		return $return;
	}

	/**评论统计
	 * @param $start_time
	 * @param $end_time
	 * @param $channel
	 * @return array
	 */
	public function searchVoicsData($start_time, $end_time, $channel){

		//回复量
		$arrSql['replyCount'] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as replyCount'], 't_comment_reply', ['commentId > 460000','commentId < 500000'], []);
		//点赞
		$arrSql['favorCount'] = $this->getSqlByTemplate($start_time, $end_time, $channel, ['count(1) as favorCount'], 't_favor_comment', ['commentId > 460000','commentId < 500000'], []);
		$replyResult = Yii::app()->db_app->createCommand($arrSql['replyCount'])->queryAll();
		$favorResult = Yii::app()->db_app->createCommand($arrSql['favorCount'])->queryAll();
		$arrResult = $this->collectionDt([$replyResult , $favorResult]);
		$arrResult = $this->fillData($arrResult , $replyResult , 'replyCount');
		$arrResult = $this->fillData($arrResult , $favorResult , 'favorCount');
		ksort($arrResult);
		return $arrResult;

	}

}
