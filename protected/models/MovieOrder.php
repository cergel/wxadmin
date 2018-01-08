<?php
Yii::import('ext.RedisManager', true);
/**
 * This is the model class for table "{{movie_order}}".
 *
 * The followings are the available columns in table '{{movie_order}}':
 * @property integer $id
 * @property integer $movie_id
 * @property string $movie_name
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $pos
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 * @property string $content
 */
class MovieOrder extends CActiveRecord
{

    private $redis;


    public function init()
    {
        $this->setRedis();
    }


    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['movie_order']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{movie_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('movie_id, movie_name, start_time,end_time,pos,status,created, updated, ', 'required'),
			array('movie_id, start_time, end_time, pos, status, created, updated', 'numerical', 'integerOnly'=>true),
			array('movie_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, movie_id, movie_name, start_time, end_time, pos, status, created, updated, content', 'safe', 'on'=>'search'),
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
			'movie_id' => '影片ID',
			'movie_name' => '影片名称',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
			'pos' => '排序',
			'status' => '状态',
			'created' => '创建时间',
			'updated' => '修改时间',
			'content' => '内容',
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
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('movie_name',$this->movie_name,true);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MovieOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //格式化时间
    public function formatData($timeStamp){
        return date('Y-m-d H:i:s',$timeStamp);
    }

    //显示状态
    public function formatStatus($iStatus){
        return $iStatus==1?'有效':'无效';
    }

    //生成缓存文件
    public function saveRedis(){
		$redisKey = "movie_order_data";
		$arrData  = $this->redis->get($redisKey);
		$arrData = json_decode($arrData,true);
        $sql='select * from t_movie_order where status = 1 and end_time> '.time();
        $dbRe = self::model()->findAllBySql($sql);
		$arr = [];
        if($dbRe){
            foreach($dbRe as $v){
                $tmpArr = [
                    'id'=>$v->movie_id,
                    'name'=>$v->movie_name,
                    'start_time'=>date('Y-m-d H:i:s',$v->start_time),
                    'end_time'=>date('Y-m-d H:i:s',$v->end_time),
                    'pos'=>$v->pos,
                ];
                $arr[]=$tmpArr;
            }
        }
		$arrData['sortCondition'] = $arr;
		$arrData = json_encode($arrData);
		return $this->redis->set($redisKey,$arrData);
    }


	/**
	 * @param int $iNum
	 * @return int
	 * 
	 */
	public function getValidNum($iNum=0)
	{
		$movieShcedNumRedisKey = "movie_order_data";
		$arrData  = $this->redis->get($movieShcedNumRedisKey);
		$arrData = json_decode($arrData,true);
		if(empty($iNum)){
			if(empty($arrData['sortNum'])){
				$arrData['sortNum'] =$iNum =  10;
				$arrData = json_encode($arrData);
				$this->redis->set($movieShcedNumRedisKey,$arrData);
			}else{
				$iNum = $arrData['sortNum'];
			}
		}else{
			$arrData['sortNum'] = $iNum;
			$arrData = json_encode($arrData);
			$this->redis->set($movieShcedNumRedisKey,$arrData);
		}
		return $iNum;
	}
/*
	public function getRedisInfo($iNum=0)
	{
		$movieShcedNumRedisKey = "movie_order_data";
		$arrData  = $this->redis->get($movieShcedNumRedisKey);
		$arrData = json_decode($arrData,true);
		if(empty($iNum)){
			if(empty($arrData['movie_order_num'])){
				$arrData['movie_order_num'] =$iNum =  10;
				$arrData = json_encode($arrData);
				$this->redis->set($movieShcedNumRedisKey,$arrData);
			}else{
				$iNum = $arrData['movie_order_num'];
			}
		}else{
			$arrData['movie_order_num'] = $iNum;
			$arrData = json_encode($arrData);
			$this->redis->set($movieShcedNumRedisKey,$arrData);
		}
		return $iNum;
	}
*/
}
