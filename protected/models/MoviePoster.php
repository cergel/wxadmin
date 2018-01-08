<?php
class MoviePoster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'movie_poster';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('movie_id,poster_type', 'required'),
			array('movie_id, root_id,poster_type, size, url,status', 'safe'),
			array('id,movie_id, root_id,poster_type,size,status', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'movie_id' =>'影片ID',
			'root_id'=>'根节点',
			'poster_type' => '影片类型',
			'size' => '影片大小',
			'url' => '影片地址',
			'status' => '状态',
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
// 		UploadFiles::movieFile('123123');exit;
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('movie_id',$this->movie_id);
		$criteria->compare('root_id',$this->root_id);
		$criteria->compare('poster_type',$this->poster_type);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('status',$this->status);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>50,
				),
						'sort'=>array(
                'defaultOrder'=>'movie_id DESC,id DESC',
            ),
					));
	}
	public function getPoster($key='')
	{
		$type = [''=>'全部'];
		foreach ($type = Yii::app()->params['movie_img_type'] as $k=>$val)
		{
			$type[$k] = $val['cn_name']?$val['cn_name']:'';
		}
		if (empty($key))
			unset($type['']);
		return !empty($type[$key])?$type[$key]:$type;
		
	}
	/**
	 * 获取指定节点的子节点（也可以用于获取所有的根节点图片）
	 * @param unknown $rootId
	 * @return array
	 */
	public function getChildPoster($rootId,$movie_id)
	{
		if (empty($rootId))return [];
		$where = "root_id = '$rootId'";
		$where .= empty($movie_id)?'':" AND movie_id = '$movie_id' ";
		$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE $where ORDER BY movie_id DESC,id DESC")->queryAll();
		return $info;
	}
	/**
	 * @tutorial 图片生成的物理路径
	 * @param unknown $arrPoster
	 * @return boolean|string
	 */
	public function getBasePosterPath($arrPoster,$imgUrl=FALSE)
	{
		if (empty($arrPoster['movie_id']) && empty($arrPoster['movie_no']))return FALSE;
		$arrPoster['movie_id'] = empty($arrPoster['movie_id'])?$arrPoster['movie_no']:$arrPoster['movie_id'];
		$arrPoster['poster_type'] = empty($arrPoster['poster_type'])?'':$arrPoster['poster_type'];
		//说明是裁剪图
		$path='';
		if (!empty($arrPoster['size']))
			$path =Yii::app()->params['movie_img_path']['local_dir']. Yii::app()->params['movie_img_path']['img_dir'].'/'.$arrPoster['movie_id'].'/'.$arrPoster['poster_type'].'/'.$arrPoster['size'];
		else 
			$path = Yii::app()->params['movie_img_path']['local_dir']. Yii::app()->params['movie_img_path']['img_dir_original'].'/'.$arrPoster['movie_id'].'/'.$arrPoster['poster_type'];
		if ($imgUrl && !empty($arrPoster['url']))
			$path .='/'.$arrPoster['url'];
		//灵思特殊处理
		if (!empty($arrPoster['url']) && strstr($arrPoster['url'],'/'))
			$path = Yii::app()->params['movie_img_path']['local_dir']. Yii::app()->params['movie_img_path']['img_dir_original'].$arrPoster['url'];
		$path = $imgUrl?$path:dirname($path);
		return $path;
	}
	
	public function getImgUrl($id,$itype='2')
	{
		$objArray = $itype == '1' ?MoviePoster::model()->findByPk($id):MoviePosterTemp::model()->findByPk($id);
		$url = MoviePoster::getLocalPostPath($objArray,true);
		$str ="";
		if (!empty($url) && !empty($id)){
			$str .="<a target ='_blank' href=\"".$url."\">";
			$str .=" <img src='$url' height=\"200\" /></a>";
		}
		return $str;
		
	}
	/**
	 * @author 获取本地访问地址
	 * @param unknown $arrPoster
	 * @return boolean|string
	 */
	public function getLocalPostPath($arrPoster,$imgUrl=FALSE)
	{
		if (empty($arrPoster['movie_id']))return FALSE;
		$arrPoster['poster_type'] = empty($arrPoster['poster_type'])?'':$arrPoster['poster_type'];
		//说明是裁剪图
		$path='';
		if (!empty($arrPoster['size']))
			$path = Yii::app()->params['movie_img_path']['local_url'].Yii::app()->params['movie_img_path']['img_dir'].'/'.$arrPoster['movie_id'].'/'.$arrPoster['poster_type'].'/'.$arrPoster['size'];
		else
			$path =Yii::app()->params['movie_img_path']['local_url'].Yii::app()->params['movie_img_path']['img_dir_original'].'/'.$arrPoster['movie_id'].'/'.$arrPoster['poster_type'];
		if ($imgUrl && !empty($arrPoster['url']))
			$path .='/'.$arrPoster['url'];
		//灵思特殊处理
		if (!empty($arrPoster['url']) && strstr($arrPoster['url'],'/')){
			$path =Yii::app()->params['movie_img_path']['local_url'].Yii::app()->params['movie_img_path']['img_dir_original'].$arrPoster['url'];
		}
		return $path;
	}
	/**
	 * @throws 获取指定影片下所有图片(每张图片显示一次，不论大小,并且按照类型显示)
	 * @param unknown $movieId
	 * @return array
	 */
	public function getMovieImg($movieId)
	{
		$arrMoviePoster = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE movie_id = '$movieId' GROUP BY root_id ORDER BY poster_type DESC,size DESC")->queryAll();
		$arrMoviePosterData = [];
		foreach ($arrMoviePoster as $poster)
		{
			if (!empty($poster['url'])){
				$poster['url'] = MoviePoster::getLocalPostPath($poster,true);
				$arrMoviePosterData[$poster['poster_type']][] = $poster;
			}
		}
		return $arrMoviePosterData;
	}
	
	/**
	 * @throws 获取指定影片下所有图片(每张图片显示一次，不论大小,并且按照类型显示)
	 * @param unknown $movieId
	 * @return array
	 */
	public function delImgData($movieId = '',$poster = '')
	{
		//if (empty($movieId) || !empty($poster))return TRUE;
		
		$arrMoviePoster = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE movie_id = '$movieId' AND poster_type = '$poster' ")->queryAll();
		foreach ($arrMoviePoster as $posterData)
		{
			if (!MoviePoster::model()->findByPk($posterData['id'])->delete()){
				return FALSE;
			}
		}
		return TRUE;
	}
	/**
	 * 整理tags格式
	 */
	public function getMovieTagsVersion($type='tag')
	{
		$info = $type =='tag'?Yii::app()->params['movie_tags']:Yii::app()->params['movie_version'];
		$infoData = [];
		foreach ($info as $tag)
		{
			if (!empty($tag))
				$infoData[$tag] = $tag;
		}
		return $infoData;
	}
	

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_movie;
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

}
