<?php

/**
 * This is the model class for table "t_comment_tag".
 *
 * The followings are the available columns in table 't_comment_tag':
 * @property integer $id
 * @property string $tag_name
 * @property string $tag_content
 * @property integer $comment_type
 * @property integer $created
 * @property integer $updated
 */
class CommentTag extends CActiveRecord
{
	private  $arrTag = [];
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_comment_tag_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tag_name, tag_content, comment_type, created, updated', 'required'),
			array('comment_type, created, updated', 'numerical', 'integerOnly'=>true),
			array('tag_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tag_name, tag_content, comment_type, created, updated', 'safe', 'on'=>'search'),
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
			'tag_name' => '标签',
			'tag_content' => '映射关键字',
			'comment_type' => '标签属性',
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
		$criteria->compare('tag_name',$this->tag_name,true);
		$criteria->compare('tag_content',$this->tag_content,true);
		$criteria->compare('comment_type',$this->comment_type);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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
	 * @return CommentTag the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 获取tag列表
	 */
	public function getTagList()
	{
		if(empty($this->arrTag)){
			$arrTags = [];
			$tags = CommentTag::model()->findAll();
			foreach($tags as $val){
				$arrTags[$val->id] = $val->tag_name;
			}
			$this->arrTag = $arrTags;
		}
		return $this->arrTag;

	}
    public function saveCommentTagList($id, $type)
    {
        switch ($type) {
            case 1:
                $type = 'tag_add';
                break;
            case 2:
                $type = 'tag_edit';
                break;
            case 3:
                $type = 'tag_del';
                break;
			case 4:
				$type = 'comment_tag_edit';
				break;
        }

        $url = Yii::app()->params['comment']['comment_tags'];
        $arrData = ['id' => $id, 'type' => $type, 'channelId' => 3];
        $arrData = Https::getPost($arrData, $url);
        $arrData = json_decode($arrData, true);
        return !empty($arrData['data']) ? $arrData['data'] : false;
    }
}
