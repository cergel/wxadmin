<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 15:48
 */
class Fuli extends CActiveRecord{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 't_fulipindao';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title,up_time,down_time,status,photo', 'required'),
            array('id,title,a_id,time_box,start_time,end_time,photo,up_time,down_time,status,created,updated', 'safe'),
            array('id,title,a_id,time_box,start_time,end_time,photo,up_time,down_time,status,created,updated', 'safe', 'on' => 'search')
        );
    }
    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
            'channel' => array(self::HAS_MANY, 'FuliChannel', 'f_id'),
            'citys' => array(self::HAS_MANY, 'FuliCity', 'f_id'),
        );
    }


    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'a_id'=>'活动ID',
            'title' => '活动名称',
            'time_box' => '活动时间段',
            'start_time' =>'报名活动开始时间',
            'end_time' => '活动报名结束时间',
            'photo' => '封面图',
            'up_time' => '上线时间',
            'down_time' => '下线时间',
            'status'=>'状态',
            'created'=>'创建时间',
            'updated'=>'更新时间',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('a_id', $this->a_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('time_box', $this->time_box,true);
        $criteria->compare('status', $this->status);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function getDbConnection()
    {
        return Yii::app()->db_active;
    }

    /**
     * @param string $id
     * @return array
     */
    public function getChannel($iType = 'list')
    {
        $arrType = ['' => '全部', '3' => '微信', '8' => 'IOS', '9' => 'Android', '28' => '手Q'];
        if($iType == 'list')
            unset($arrType['']);
        return empty($arrType[$iType])?$arrType:$arrType[$iType];
    }
//    /**
//     * 拼接div内容
//     * @param unknown $id
//     * @param unknown $wx
//     * @param unknown $qq
//     * @param unknown $mobile
//     */
//    public function getTemplateUrl($platform,$id)
//    {
//        $str ="";
//        if (is_array($platform)){
//            foreach($platform as $platforms) {
//                $str .= $this->getPlatformKey($platforms['platform'])."<br>";
//                $str .="&nbsp;&nbsp;&nbsp;&nbsp;本地测试链接:<a target ='_blank' href=\"".Yii::app()->params['apply_active']['local_url']."/$id/{$platforms['platform']}/index.html\">";
//                $str .=Yii::app()->params['apply_active']['local_url']."/$id/{$platforms['platform']}/index.html</a></br>";
//                $str .="&nbsp;&nbsp;&nbsp;&nbsp;线上发布链接：<a target ='_blank' href=\"".Yii::app()->params['apply_active']['final_url']."/$id/{$platforms['platform']}/index.html\">";
//                $str .=Yii::app()->params['apply_active']['final_url']."/$id/{$platforms['platform']}/index.html</a></br>";
//            }
//        }
//        if (empty($str)){
//            $str ="没有选择任何模板";
//        }
//        return $str;
//    }

    public function saveFile($type='fulipindao'){
        $upTime = time();
        $endTime = time()+60;
        $sql ="SELECT t_fulipindao.*,t_fulipindao_channel.channel_id,t_fulipindao_channel.url,t_fulipindao_city.city_id
                FROM t_fulipindao INNER JOIN t_fulipindao_channel ON t_fulipindao.id = t_fulipindao_channel.f_id
                INNER JOIN t_fulipindao_city ON t_fulipindao_city.f_id = t_fulipindao.id
                where t_fulipindao.status='1' AND t_fulipindao.`down_time` >{$endTime} AND t_fulipindao.up_time <= '$upTime'
                ORDER BY t_fulipindao.up_time DESC";
        Log::model()->logFile($type,$sql);
        //echo $sql;exit;
        $arrAllData = yii::app()->db_active->createCommand($sql)->queryAll();
        $arrData = [];
        foreach ($arrAllData as  $val){
            $lArr =[];
            $lArr['id']=$val['id'];
            $lArr['a_id']=$val['a_id'];
            $lArr['title']=$val['title'];
            $lArr['time_box']=$val['time_box'];
            if(empty($val['start_time']) || empty($val['end_time'])){
                $lArr['start_status'] = 3;
            }else{
                $lArr['start_status']=(time() >= $val['start_time'] && time() <= $val['end_time'])?1:((time() <= $val['start_time'])?0:2);
            }
            $lArr['photo']='https://appnfs.wepiao.com'.$val['photo'];
            $lArr['url'] = $val['url'];
            $lArr['up_time']=$val['up_time'];
            $arrData[$val['channel_id']][$val['city_id']][] = $lArr;
        }
        $arrChannel = $this->getChannel('list');
        foreach($arrChannel as $key=>$val){
            if(!empty($key))
            UploadFiles::unlinkFileDirIndo(Yii::app()->params['fulipindao'] .$key);
        }
        foreach($arrData as $channel=>$arrChannel){
            UploadFiles::unlinkFileDirIndo(Yii::app()->params['fulipindao'] .$channel);
            foreach($arrChannel as $city=>$info){
                $fileName = empty($city)?'fuli.json':'fuli_'.$city.'.json';
                $path =Yii::app()->params['fulipindao'] .$channel.'/';
                UploadFiles::createPath($path);
                file_put_contents($path.$fileName, 'callback('.json_encode($info).')');
            }
        }
        @chmod(Yii::app()->params['fulipindao'],0777);
        Log::model()->logFile($type.'_data',$sql);
        $sql = "update t_fulipindao set `status`=0 where down_time < $endTime";
        yii::app()->db_active->createCommand($sql)->execute();
        Log::model()->logFile($type,$sql);
//        echo json_encode($arrData);exit;
    }


}