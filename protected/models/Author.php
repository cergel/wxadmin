<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
 */

class Author extends CActiveRecord
{
    /**
     * @return tableName
     */
    public function tableName()
    {
        return 't_author';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('head_img,name_author,summary,qr_img', 'required'),
            array('id,head_img,name,summary,qr_img,created,updated', 'safe', 'on'=>'search'),
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
            'name_author' => '作者',
            'summary' => '简介',
            'head_img' => '头像',
            'qr_img' => '二维码',
            'created' => '创建时间',
            'updated' => '更新时间',
        );
    }

    /**
     * 查询
     * @return CActiveDataProvider
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name_author',$this->name_author,true);
        $criteria->compare('summary',$this->summary,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
        ));
    }

    /**
     * 二维码识别加生成
     * @param $imgPath logo图片地址  cdn路径
     * @param $qrImg  二维码图片地址  cdn地址
     */
    public function saveQrCodeImg($imgPath,$qrImg)
    {
        //下载原图
        $localImgPath = Yii::app()->params['base_url'].'/protected/runtime/qr_code_img/'.md5($imgPath).rand(1000,9999).rand(1000,9999).'.'.substr(strrchr($imgPath, '.'), 1);
        UploadFiles::createPath(Yii::app()->params['base_url'].'/protected/runtime/qr_code_img/');
        file_put_contents($localImgPath,file_get_contents($imgPath));
        //下载原图二维码
        $localQrImg = Yii::app()->params['base_url'].'/protected/runtime/qr_code_img/'.md5($qrImg).rand(1000,9999).rand(1000,9999).'.'.substr(strrchr($qrImg, '.'), 1);
        file_put_contents($localQrImg,file_get_contents($qrImg));
        //识别二维码
        $strCode = QrCodeBase::getInfo($localQrImg);
        if(!empty($strCode)){
            //生成二维码地址
            $localQrImgNew = Yii::app()->params['base_url'].'/protected/runtime/qr_code_img/1001'.md5($qrImg).rand(1000,9999).rand(1000,9999).'.'.substr(strrchr($qrImg, '.'), 1);
            QrCodeBase::setInfo($strCode,$localQrImgNew,$imgPath);
            if(is_file($localQrImgNew)){
                //上传到COS
                $path = '/uploads/active/' . date("Ymd") . '/' . date('Hi').'/';
                $fileName = md5(file_get_contents($localQrImgNew).rand(1000,9999).time()).'.'.substr(strrchr($imgPath, '.'), 1);
                $url = CosUpload::upload($localQrImgNew,$path.$fileName);
                return $url;
            }
        }
        return false;
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
