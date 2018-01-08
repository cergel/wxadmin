<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 11:36
 */

class BankInfo extends CActiveRecord
{
    /**
     * @return tableName
     */
    public function tableName()
    {
        return '{{bank_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('num,name,image,status', 'required'),
            array('num,name', 'unique'),
            array('id,num,name,image,status', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
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
            'num' => '银行编码',
            'name' => '银行名称',
            'image' => '银行图片',
            'status' => '状态'
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
        $criteria->compare('num',$this->num,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('image',$this->image);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            //'sort'=>array(
            //'defaultOrder'=>'city_id DESC',
            //),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActiveCity the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

}
