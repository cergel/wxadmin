<?php
class MovieMusicInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_movie_music_info';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('id,m_id,song_id,singer_pic,song_name,singer_name,album_id,album_name,ws_play_url,cc_play_url,created,updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,m_id,song_id,singer_pic,song_name,singer_name,album_id,album_name,ws_play_url,cc_play_url,created,updated','safe', 'on'=>'search'),
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
			'pid' => 'ID',
			'm_id' =>'电影id',
			'song_id' =>'音乐id',
            'singer_pic'  =>'歌手图片',
			'song_name'  =>'歌曲名称',
			'singer_name'  =>'歌手名字',
			'album_name'  =>'专辑名',
			'ws_play_url'  =>'网速CDN播放地址',
			'cc_play_url'  =>'蓝汛CDN播放地址',
            'created'  =>'创建时间',
            'updated'  =>'更新时间',
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
		$criteria->compare('m_id',$this->m_id);
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Movie the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function saveSql($sql)
	{
		return Yii::app()->db->createCommand($sql)->execute();
	}
}
