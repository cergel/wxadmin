<?php

/**
 * This is the model class for table "active_page".
 */
class ActivePageForQq extends CActiveRecord
{
    public function getDbConnection()
    {
        return Yii::app()->db;
    }

    /**
     * @return string the associated database table sName
     */
    public function tableName()
    {
        return 't_qq_active_page';
    }

    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, pic, sharePic,shareTitle,shareContent,link,status', 'safe'),
            array('title, link, status', 'required'),
            array('status, created, updated', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('link, shareContent', 'length', 'max' => 200),
            // 下面这种写法是为了防止不更新图片的时候value被validator清空
            // 原因不明，还需要研究，暂时这样解决
//             CUploadedFile::getInstance($this, 'pic') || $this->isNewRecord ? array(
//                 'pic',
//                 'file',
//                 'allowEmpty' => !$this->isNewRecord,
//                 'types' => 'jpg,png,gif',
//                 'maxSize' => 1024 * 512,    // 512kb
//                 'tooLarge' => '大图大于512kb，上传失败！请上传小于512kb的文件！'
//             ) : array('pic', 'length', 'max' => 200),
//             CUploadedFile::getInstance($this, 'sharePic') || $this->isNewRecord ? array(
//                 'sharePic',
//                 'file',
//                 'allowEmpty' => !$this->isNewRecord,
//                 'types' => 'jpg,png,gif',
//                 'maxSize' => 1024 * 512,    // 512kb
//                 'tooLarge' => '分享图片大于512kb，上传失败！请上传小于512kb的文件！'
//             ) : array('sharePic', 'length', 'max' => 200),
            array('link', 'url'),
            // The following sRule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id,title, pic, sharePic,shareTitle,shareContent,link,status,created,updated',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational sRules.
     */

    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '页面标题',
            'pic' => '背景图',
            'sharePic' => '分享图片',
            'shareTitle' => '分享标题',
            'shareContent' => '分享内容',
            'link' => '红包链接',
            'status' => '状态',
            'created' => '创建时间',
        );
    }

    /**
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('pic', $this->pic, true);
        $criteria->compare('status',$this->status);
        $criteria->compare('shareContent',$this->shareContent,true);
        
        $criteria->compare('sharePic', $this->sharePic, true);
        $criteria->compare('shareTitle', $this->shareTitle, true);
        $criteria->compare('link',$this->link);
        $criteria->compare('created', strtotime($this->created));
        $criteria->compare('updated', strtotime($this->updated));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $classsName active record class sName.
     * @return ActivePage the static model class
     */
    public static function model($classsName = __CLASS__)
    {
        return parent::model($classsName);
    }

    // 自动更新时间
    public function beforeSave()
    {
        $this->updated = time();
        if ($this->isNewRecord) {
            $this->created = time();
        }

        return true;
    }
    /**
     * 拼接div内容
     * @param unknown $id
     * @author liulong
     */
    public function getDialogInfo($id)
    {
    	$str ="";
    	$str .="本地测试链接带分享：<a target ='_blank' href=\"".Yii::app()->params['active_page_for_QQ']['target_url']."/$id/index.html\">";
    	$str .=Yii::app()->params['active_page_for_QQ']['target_url']."/$id/index.html</a></br>";
    	$str .="本地测试链接无分享：<a target ='_blank' href=\"".Yii::app()->params['active_page_for_QQ']['target_url']."/$id/index.html?_wv=3\">";
    	$str .=Yii::app()->params['active_page_for_QQ']['target_url']."/$id/index.html?_wv=3</a></br>";
    	$str .="线上发布链接带分享：<a target ='_blank' href=\"".Yii::app()->params['active_page_for_QQ']['final_url']."/$id/index.html\">";
    	$str .=Yii::app()->params['active_page_for_QQ']['final_url']."/$id/index.html</a></br>";
    	$str .="线上发布链接无分享：<a target ='_blank' href=\"".Yii::app()->params['active_page_for_QQ']['final_url']."/$id/index.html?_wv=3\">";
    	$str .=Yii::app()->params['active_page_for_QQ']['final_url']."/$id/index.html?_wv=3</a></br>";
    	return $str;
    }

    /**
     * @tutorial 生成模板文件
     * @author liulong
     */
    public function makeFile()
    {
        $targetDir = Yii::app()->params['active_page_for_QQ']['target_dir'] . '/' . $this->id;
        $targetUrl = Yii::app()->params['active_page_for_QQ']['final_url'] . '/' . $this->id;
        // 从静态模板复制内容
//         if (!is_dir($targetDir))
        CFileHelper::copyDirectory(
            Yii::app()->params['active_page_for_QQ']['template'],
            $targetDir
        );
        // 替换变量
        $fileContent = file_get_contents($targetDir . "/index.html");
        $fileContent = str_replace(array(
            '<!--title-->',
            '<!--pic-->',
        	'<!--link-->',
            '<!--sharePic-->',
            '<!--shareTitle-->',
            '<!--shareContent-->',
        ), array(
            $this->title,
           'images/' . $this->pic,
            $this->link,
            $this->sharePic,
             'images/' . $this->sharePic,
            $this->shareTitle,
            $this->shareContent,
        ), $fileContent);
        // 将变量写入静态模板
        file_put_contents($targetDir . "/index.html", $fileContent);
        // 写入日志
        Log::model()->logPath(Yii::app()->params['active_page_for_QQ']['target_dir'] . '/' . $this->id.'/');
    }
}
