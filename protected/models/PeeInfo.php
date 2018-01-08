<?php
class PeeInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_pee_info';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
          //  array('pid', 'required'),
          //  array('pid', 'unique'),
            array('pid,  t_id, p_order, start_time, end_time,pee_start_info,pee_reason,pee_error_info,pee_count,base_pee_count,created,update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pid,  t_id, p_order, start_time, end_time,pee_start_info,pee_reason,pee_error_info,pee_count,base_pee_count,created,update', 'safe', 'on'=>'search'),
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
			't_id' =>'尿点id',
			'p_order'=>'排序',
			'start_time' => '开始时间',
			'end_time' => '尿点持续时长',
			'pee_start_info' => '尿点开始剧情',
			'pee_reason' => '更新时间',
			'pee_error_info'  =>'状态',
            'pee_count'  =>'真实尿尿人数',
            'base_pee_count'  =>'注水尿尿数',
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
		$criteria->compare('t_id',$this->t_id,true);
		$criteria->compare('is_pee',$this->is_pee,true);
		$criteria->compare('pee_start_info',$this->pee_start_info);
		$criteria->compare('pee_reason',$this->pee_reason);
		$criteria->compare('pee_error_info',$this->pee_error_info);
		$criteria->compare('base_pee_count',$this->base_pee_count);

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
		return Yii::app()->db_pee->createCommand($sql)->execute();
	}
	public function getDbConnection()
	{
		return Yii::app()->db_pee;
	}






}
