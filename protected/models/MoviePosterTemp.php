<?php
class MoviePosterTemp extends CActiveRecord
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('movie_id,url,poster_type', 'required'),
			array('movie_no,movie_id, root_id,source_type,poster_type, size, url,status', 'safe'),
			array('id,movie_id,source_type, root_id,poster_type, size, url,status', 'safe', 'on'=>'search'),
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
			'poster_type' => '图片类型',
			'source_type'=>'来源、操作',
			'size' => '影片大小',
			'url' => '影片地址',
			'status' => '状态',
			'source_type'=>'来源、操作',
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
		$criteria->compare('source_type',$this->source_type);
		$criteria->compare('status',$this->status);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
				'pagination'=>array(
						'pageSize'=>50,
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
		return Yii::app()->db_movie_temp;
	}
	/**
	 * @tutorial 组装数组
	 * @param unknown $model
	 * @return string
	 */
	public function getMoviePosterArray($model)
	{
		$movieImgType = !empty(Yii::app()->params['movie_img_type'][$model->poster_type]['is_type'])?Yii::app()->params['movie_img_type'][$model->poster_type]:'';
		$moviePosterArray = [];
		if ($movieImgType){
			//新图名称
			$imgName = $movieImgType['is_one']?'1.jpg':date('YmdHis').rand(1000,9999).'.jpg';
			foreach ($movieImgType['is_type'] as $imgType)
			{
				//组装数组
				$moviePoster = [];
				$moviePoster['movie_id'] = $model->movie_id;
				$moviePoster['root_id'] = $model->id;
				$moviePoster['poster_type'] = $model->poster_type;
				$moviePoster['size'] = $imgType;
				$moviePoster['url'] = $imgName;
				$moviePoster['status'] = 1;
				$moviePosterArray[] = $moviePoster;
				//裁剪图片
			}
		}
		return $moviePosterArray;
	}
	/**
	 * 按比例裁剪图片或保存原图
	 * @param unknown $id
	 * @return boolean
	 */
	public function makeImg($id)
	{
		//开始裁剪图片
		$model = MoviePosterTemp::model()->findByPk($id);
		$moviePosterArray = MoviePosterTemp::model()->getMoviePosterArray($model);
		$res = FALSE;
		if ($moviePosterArray){
			//判断是否删除原图,如果只是一张的话，则删除原图数据
			if (!empty(Yii::app()->params['movie_img_type'][$model->poster_type]['is_type']) && Yii::app()->params['movie_img_type'][$model->poster_type]['is_one'])
			{
				MoviePoster::model()->delImgData($model->movie_id,$model->poster_type);
			}
			
			$imgOriginal = MoviePoster::model()->getBasePosterPath(MoviePosterTemp::model()->findByPk($id),TRUE);
			foreach ($moviePosterArray as $info)
			{	//开始截取图片//图片名称
				$baseImg = MoviePoster::model()->getBasePosterPath($info,TRUE);
				$size = explode('X', $info['size']);
				if (count($size) == 2){
					$statusImg = UploadFiles::make_thumb($imgOriginal, $baseImg,$size[0],$size[1],false);//自己去看注释
				}else {
					$statusImg = UploadFiles::copyFile($imgOriginal, $baseImg,false);
				}
				if ($statusImg){
					$moviePostModel = new MoviePoster();
					$moviePostModel->setAttributes($info);
					$moviePostModel->save();
				}
			}
			$model->status = 3;
			$model->audit_time = time();
			$model->audit_id = Yii::app()->getUser()->getId();
			if ($model->save())
				$res = true;
		} 
		return $res;
	}
	/**
	 * 获取待审核图片表内数据：指定影片的
	 * @param unknown $movie_id
	 * @param number $type
	 * @return multitype:|Ambigous <multitype:, array>
	 */
	public function getMovieAllPoster($movie_id,$source_type = 0)
	{
		if (empty($movie_id))return [];
		$where = " movie_id = '$movie_id' ";
		if (!empty($source_type))
			$where .= " AND source_type = '$source_type' ";
		$info = $this->getDbConnection()->createCommand("SELECT * FROM {$this->tableName()} WHERE $where ORDER BY id DESC")->queryAll();
		return empty($info)?[]:$info;
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
