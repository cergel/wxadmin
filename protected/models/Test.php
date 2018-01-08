<?php
Yii::import("ext.redisTools.*");
class Test extends CActiveRecord
{
    private $redis;//在初始化时，为此redis赋值
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{active}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('iReads, iFill,iFillRead, iIsonline,iStatus,iDirect_city', 'numerical', 'integerOnly' => true),
            array('sSummary, sTag, sSource_summary, sContent,sPicture', 'safe'),
            array('iActive_id, sType, sTitle, sSummary, sCover, sTag, sSource_name, sSource_head, sSource_summary, sSource_link, sShare_logo, sShare_title, sShare_summary, sShare_link, sShare_platform, real_reads, iOnline_time, iOffline_time, iIsonline, sContent, sVideo_link, iVideo_times, sPicture, sAudio_link, iAudio_times', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
    }

    /**
     * @tutorial attributeLabels
     * @author liulong
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'iActive_id' => 'ID',
        );
    }



    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Active the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }



    public function init()
    {
        //parent::init();
        $this->setRedis();
    }


    //redis初始化逻辑
    public function setRedis()
    {
        //初始化redis逻辑
        if(empty($this->redis)){
//            include_once BASE_PRO_URL .'/vendor/redisTools/redisManager.php';
            $this->redis =  redisTool::getInstance(Yii::app()->params->redis_tool['test_test'],'common');
        }
    }

    public function setRedisInfo($key,$val)
    {
        $this->setRedis();
        return $this->redis->WYset($key,$val);
    }

    public function getRedisInfo($key)
    {
        $this->setRedis();
        return $this->redis->WYget($key);
    }


}
