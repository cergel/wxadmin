<?php
class Ad extends CActiveRecord
{

    const TYPE_MOVIE_SELECTED = 1; // 衍生品类广告
    const TYPE_MOVIE_UNSELECTED = 2; // 影片类广告

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ad}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sTitle, sLink, iShowAt, iHideAt', 'required'),
			array('iType, iStatus, iCreated, iUpdated', 'numerical', 'integerOnly'=>true),
			array('sTitle, sPath, sLink', 'length', 'max'=>200),
            array('iShowAt, iHideAt', 'date', 'format'=>'yyyy-MM-dd hh:mm:ss'),
            array('sLink', 'url'),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            // 原因不明，还需要研究，暂时这样解决
            CUploadedFile::getInstance($this, 'sPath') || $this->isNewRecord ? array('sPath',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpg,png,gif',
                'maxSize'=>1024*512,    // 512kb
                'tooLarge'=>'大图大于512kb，上传失败！请上传小于512kb的文件！'
            ) : array('sPath', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('iAdID, sTitle, sPath, sLink, iType, iShowAt, iHideAt, iStatus, iCreated, iUpdated', 'safe', 'on'=>'search'),
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
            'cinemas'=>array(self::HAS_MANY, 'AdCinema', 'iAdID'),
            'movies'=>array(self::HAS_MANY, 'AdMovie', 'iAdID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iAdID' => 'ID',
			'sTitle' => '标题',
			'sPath' => '图片',
			'sLink' => '链接',
			'iType' => '类型',
			'iShowAt' => '开始',
			'iHideAt' => '结束',
			'iStatus' => '状态',
			'iCreated' => '创建时间',
			'iUpdated' => '更新时间',
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

		$criteria->compare('iAdID',$this->iAdID);
		$criteria->compare('sTitle',$this->sTitle,true);
		$criteria->compare('sPath',$this->sPath,true);
		$criteria->compare('sLink',$this->sLink,true);
		$criteria->compare('iType',$this->iType);
		$criteria->compare('iShowAt',$this->iShowAt);
		$criteria->compare('iHideAt',$this->iHideAt);
		$criteria->compare('iStatus',$this->iStatus);
		$criteria->compare('iCreated',$this->iCreated);
		$criteria->compare('iUpdated',$this->iUpdated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'iAdID DESC',
            ),
					));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 自动更新时间
	 */
    public function beforeSave()
    {
        if($this->iShowAt && !is_numeric($this->iShowAt))
            $this->iShowAt = strtotime($this->iShowAt);
        if($this->iHideAt && !is_numeric($this->iHideAt))
            $this->iHideAt = strtotime($this->iHideAt);
        $this->iUpdated = time();
        if ($this->isNewRecord)
            $this->iCreated = time();
        return true;
    }

    public function afterSave()
    {
        if($this->iShowAt)
            $this->iShowAt = date('Y-m-d H:i:s', $this->iShowAt);
        if($this->iHideAt)
            $this->iHideAt = date('Y-m-d H:i:s', $this->iHideAt);
    }

    public function afterFind()
    {
        if($this->iShowAt)
            $this->iShowAt = date('Y-m-d H:i:s', $this->iShowAt);
        if($this->iHideAt)
            $this->iHideAt = date('Y-m-d H:i:s', $this->iHideAt);
    }

    public function getTypeName()
    {
        switch ($this->iType) {
            case self::TYPE_MOVIE_SELECTED:
                return '指定影片';
            case self::TYPE_MOVIE_UNSELECTED:
                return '排除影片';
        }
    }

    public static function getTypeList()
    {
        return array(
            self::TYPE_MOVIE_SELECTED => '指定影片',
            self::TYPE_MOVIE_UNSELECTED => '排除影片',
        );
    }

    // 检查排期冲突
    public function checkConflict()
    {
        // 取出所有需要对比的广告
        $criteria = new CDbCriteria;
        $criteria->addCondition('iStatus=1');
        // 更新时排除本身
        if (!$this->isNewRecord)
            $criteria->addCondition('iAdID<>'.$this->iAdID);
        $criteria->order = 'iAdID DESC';

        $cinemas = isset($_POST['cinemas']) ? $_POST['cinemas'] : array();
        $movies  = isset($_POST['movies']) ? $_POST['movies'] : array();

        $ads = self::model()->findAll($criteria);
        // 遍历所有的有效广告进行检查
        foreach ($ads as $ad) {
            // 检查时间冲突
            $time_conflict = !($this->iHideAt<$ad->iShowAt || $this->iShowAt>$ad->iHideAt);// 时间是否有重合
            // 检查影城冲突
            if ($cinemas) {
                if ($ad->cinemas) {
                    $adCinemas = array();
                    foreach ($ad->cinemas as $key=>$value) {
                        $adCinemas[] = $value->iCinemaID;
                    }
                    $cinema_conflict = array_intersect($cinemas, $adCinemas);
                } else {
                    $cinema_conflict = true;
                }
            } else {
                $cinema_conflict = true;
            }
            // 检查影片冲突
            if ($movies) {
                if ($ad->movies) {
                    $adMovies = array();
                    foreach ($ad->movies as $key=>$value) {
                        $adMovies[] = $value->iMovieID;
                    }
                    $movie_conflict = (array_intersect($movies, $adMovies) && true) === ($this->iType == $ad->iType);
                } else {
                    $movie_conflict = true;
                }
            } else {
                $movie_conflict = true;
            }
            // 时间&影城&影片同时满足条件冲突成立
            if ($time_conflict && $cinema_conflict && $movie_conflict)
                return $ad->iAdID;
        }
        return false;
    }

    // 生成前端数据
    static public function createJson() {
        $writeJson = array();

        $criteria = new CDbCriteria;
        $criteria->addCondition('iStatus=1');
        $criteria->order = 'iAdID DESC' ;
        $ads = self::model()->findAll($criteria);
        foreach ($ads as $key => $ad) {
            $data = array(
                'hide_at' => date('YmdHis', strtotime($ad->iHideAt)),
                'show_at' => date('YmdHis', strtotime($ad->iShowAt)),
                'url' => $ad->sLink,
                'img' => Yii::app()->params['ad']['final_url'] .'/'.date('Y-m-d', $ad->iCreated) . '/' . $ad->sPath,
                //'title' => $ad->sTitle,
                'type' => $ad->iType,
                'cinemas' => array(),
                'movies' => array(),
            );
            if (is_array($ad['cinemas'])) {
                foreach ($ad['cinemas'] as $c) {
                    $data['cinemas'][] = $c->iCinemaID;
                }
            }
            if (is_array($ad['movies'])) {
                foreach ($ad['movies'] as $m) {
                    $data['movies'][] = $m->iMovieID;
                }
            }
            $writeJson[] = $data;
        }
        if (!file_exists(dirname(Yii::app()->params['ad']['target_dir'] . '/ad.json')))
            mkdir(dirname(Yii::app()->params['ad']['target_dir'] . '/ad.json'), 0777, true);
        file_put_contents(Yii::app()->params['ad']['target_dir'] . '/ad.json', 'callback('.json_encode(array_values($writeJson)).')');
    }
}
