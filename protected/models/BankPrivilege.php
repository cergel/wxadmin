<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 10:31
 */
class BankPrivilege extends CActiveRecord{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bank_privilege}}';
    }
    /**
     * @return array validation sRules for model attributes.
     */
    public function rules()
    {
        return array(
            array('b_id,title,summary,start_time,end_time,status,detail', 'required'),
            CUploadedFile::getInstance($this, 'photo') && $this->isNewRecord ? array('photo',
                'file',
                'allowEmpty'=>!$this->isNewRecord,
                'types'=>'jpg,png,gif',
                'maxSize'=>1024*10,    // 10kb
                'tooLarge'=>'大图大于10kb，上传失败！请上传小于10kb的文件！'
            ) : array('photo', 'length', 'max'=>200),
            array('id,b_id,title,summary,detail,start_time,end_time,status,sort,create_time,update_time,photo,link', 'safe'),
            array('id,b_id,title,summary,detail,start_time,end_time,status,sort,create_time,update_time,photo', 'safe', 'on' => 'search'),
        );
    }
    /**
     * @return array relational sRules.
     */
    public function relations()
    {
        return array(
            'cities' => array(self::HAS_MANY, 'BankPrivilegeCity', 'p_id'),
            'bankInfo' => array(self::BELONGS_TO, 'BankInfo', 'id'),
            'cinemas'=>array(self::HAS_MANY, 'BankPrivilegeCinemas', 'p_id'),
            'movies'=>array(self::HAS_MANY, 'BankPrivilegeMovies', 'p_id'),
        );
    }


    /**
     * @return array customized attribute labels (sName=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'b_id'=>'选择银行',
            'title' => '活动标题',
            'summary' => '活动简介',
            'detail' =>'活动详情',
            'start_time' => '投放开始时间',
            'end_time' => '投放结束时间',
            'status' => '状态',
            'sort' => '排序',
            'cities'=>'投放城市',
            'cinemas'=>'投放影院',
            'movies'=>'投放影片',
            'photo' =>'图片',
            'link'=>'办卡链接'
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('b_id', $this->b_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('summary', $this->summary,true);
        $criteria->compare('detail', $this->detail,true);
        $criteria->compare('start_time', $this->start_time);
        $criteria->compare('end_time', $this->end_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('sort', $this->sort);
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

    public function beforeSave()
    {
        if ($this->start_time && !is_numeric($this->start_time))
            $this->start_time = strtotime($this->start_time);
        if ($this->end_time && !is_numeric($this->end_time))
            $this->end_time = strtotime($this->end_time);
        $this->update_time = time();
        if ($this->isNewRecord) {
            $this->create_time = time();
        }
        return true;
    }

    public function afterSave()
    {
        $this->afterFind();
    }

    public function afterFind()
    {
        if ($this->start_time){
            $this->start_time = $this->int2date($this->start_time);
        }
        if ($this->end_time){
            $this->end_time = $this->int2date($this->end_time);
        }
    }

    //将存储的时间戳转换为日期格式
    public function int2date($time)
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     *    自动删除关联
     */
    public function afterDelete() {
        parent::afterDelete();
        BankPrivilegeCity::model()->deleteAllByAttributes(array(
            'p_id'=>$this->id
        ));
        BankPrivilegeCinemas::model()->deleteAllByAttributes(array(
            'p_id'=>$this->id
        ));
        BankPrivilegeMovies::model()->deleteAllByAttributes(array(
            'p_id'=>$this->id
        ));
    }
    public function saveCities(){
        // 保存城市关联
        BankPrivilegeCity::model()->deleteAllByAttributes(array('p_id'=>$this->id));
        if (isset($_POST['cities'])) {
            foreach ($_POST['cities'] as $k=>$city) {
                if (empty($city))continue;
                $bpc = new BankPrivilegeCity();
                $bpc->p_id = $this->id;
                $bpc->city_id= $city;
                $bpc->save();
            }
        }else{
            $bpc = new BankPrivilegeCity();
            $bpc->p_id = $this->id;
            $bpc->city_id= 0;
            $bpc->save();
        }
    }
    public function saveCinemas($cinemas){
        $cinemas = 0;
        // 保存影院关联
        BankPrivilegeCinemas::model()->deleteAllByAttributes(array('p_id'=>$this->id));
        if ($cinemas) {
            $cinemas=explode(',',$cinemas);
            foreach ($cinemas as $k => $cinemaId) {
                if (empty($cinemaId)) continue;
                $bpcs = new BankPrivilegeCinemas();
                $bpcs->p_id = $this->id;
                $bpcs->cinemas_id = $cinemaId;
                $bpcs->cinemas_name = $this->getCinemaNameByCinemaId($cinemaId);
                $bpcs->save();
            }
        }else{
            $bpcs = new BankPrivilegeCinemas();
            $bpcs->p_id = $this->id;
            $bpcs->cinemas_id = 0;
            $bpcs->cinemas_name = 'null';
            $bpcs->save();
        }
    }
    public function saveMovies($movies){
        $movies = 0;
        // 保存影片关联
        BankPrivilegeMovies::model()->deleteAllByAttributes(array('p_id'=>$this->id));
        if ($movies) {
            $movies=explode(',',$movies);
            foreach ($movies as $k => $moviesId) {
                if (empty($moviesId)) continue;
                $bpms = new BankPrivilegeMovies();
                $bpms->p_id = $this->id;
                $bpms->movies_id = $moviesId;
                $bpms->movies_name = $this->getMovieNameByMovieId($moviesId);
                $bpms->save();
            }
        }else{
            $bpms = new BankPrivilegeMovies();
            $bpms->p_id = $this->id;
            $bpms->movies_id = 0;
            $bpms->movies_name = 'null';
            $bpms->save();
        }
    }
    /**
     * 根据cinemaID获取cinemaName
     * @param $cinemaId
     */
    public function getCinemaNameByCinemaId($cinemaId){
        $strContent = file_get_contents("http://commoncgi.wepiao.com/data/v5/cinemas/100/info_cinema_$cinemaId.json");
        $cinema = [];
        $cinemaName = '';
        if(preg_match("/^MovieData\.set\(\"\w+\",(.*)\);/ius", $strContent, $arr)){
            $cinema = json_decode($arr[1],1);
        }
        if (!empty($cinema['info'])){
            $cinemaInfo =$cinema['info'];
            $cinemaName=$cinemaInfo['name'];
        }else {
            //这里是个bug，如果没有影院，返回个数就错误了@君琳有时间看下
          //  return  $jsonOut = [
          //      'succ'=>0,
          //      'msg'=>'无此id对应的影院',
          //  ];
        }
        return $cinemaName;

    }
    /**
     * 获取选中影院名称
     * @param $id
     * @return bool|string
     */
    public function getSelectCinemas($id){
        $arrCinemasName=[];
        $list = Yii::app()->db->createCommand()->select("cinemas_name")->from("t_bank_privilege_cinemas")->where('p_id=:id', array(':id' => $id))->queryAll();
        foreach ($list as $key => $cinemas) {
            if($cinemas['cinemas_name']==='null'){
                $list="全部影院";
            }else{
                $list="非全部影院";
                /*之后首页显示改为展示部分影院名称时恢复使用
                 * $arrCinemasName[]=$cinemas['cinemas_name'];
                $list = implode(",", $arrCinemasName);
                */
            }
        }
        return empty($list) ? false : $list;
    }

    /**
     * 获取选中影片名称
     * @param $id
     * @return bool|string
     */
    public function getSelectMovies($id){
        $arrMoviesName=[];
        $list = Yii::app()->db->createCommand()->select("movies_name")->from("t_bank_privilege_movies")->where('p_id=:id', array(':id' => $id))->queryAll();
        foreach ($list as $key => $movies) {
            if ($movies['movies_name'] === 'null') {
                $list = "全部影片";
            } else {
                /*之后首页显示改为展示部分影片名称时恢复使用
                 * foreach ($list as $key => $movies) {
                    $arrMoviesName[]=$movies['movies_name'];
                }
                $list = implode(",", $arrMoviesName);
                */
                $list = "非全部影片";
            }
        }
        return empty($list) ? false : $list;
    }
    /**
     * 根据影片id获取影片名称
     * @param $movieId
     * @return string
     */
    public function getMovieNameByMovieId($movieId){
        $url = API_MOVIEDATABASE.'/movie/info';
        $sendData=[
            'movieId'=>$movieId,
            'from'=>7000100003,
            'channelId'=>'99'
        ];
        $strJson = Https::getPost($sendData,$url);
        $obj = json_decode($strJson);
        if($obj->ret==0 && $obj->sub==0){
            if(!empty($obj->data->MovieNameChs)){
                return $obj->data->MovieNameChs;
            }else{
                $jsonOut = [
                    'succ'=>0,
                    'msg'=>'无此id对应的影片',
                ];
            }
        }else{
            $jsonOut = [
                'succ'=>0,
                'msg'=>'媒资库请求失败请重试',
            ];
        }
        return json_encode($jsonOut);
    }
    /**
     * 获取选中城市名称
     * @param $id
     * @return bool|string
     */
    public function getSelectCity($id){
        $arrCity=[];
        $list = Yii::app()->db->createCommand()->select("city_id")->from("t_bank_privilege_city")->where('p_id=:id', array(':id' => $id))->queryAll();
        foreach ($list as $key => $city) {
            foreach ( $city as $key => $cityId) {
                if($cityId==0){
                    $list='全国';
                    return $list;
                }else{
                    //之后首页显示改为展示部分城市名称时恢复使用
                    //$arrCity[] = $this->getCityNameByCityId($cityId);
                    //$list = implode(",", $arrCity);
                    return $list='非全部';
                }

            }
        }
        return empty($list) ? false : $list;
    }



    /**
     * 根据城市id获取城市名称
     * @param $cityId
     * @return array
     */
    public function getCityNameByCityId($cityId){
        $strContent = file_get_contents("http://wx.wepiao.com/data/v5/city.json");
        $city = [];
        if(preg_match("/^MovieData\.set\(\"\w+\",(.*)\);/ius", $strContent, $arr)){
            $city = json_decode($arr[1],1);
        }
        if (!empty($city['list'])){
            $cityInfo =$city['list'][$cityId];
            $cityName=$cityInfo['name'];
        }else {
            return  $jsonOut = [
                'succ'=>0,
                'msg'=>'无此id对应的城市',
            ];
        }
        return $cityName;
    }

    /**
     * 获取所有的银行名称
     * @return array|mixed|null
     */
    public function getAllBankInfo(){
        $arrBankName=[];
        $arrBankInfo=BankInfo::model()->findAll();
        foreach($arrBankInfo as $k => $v){
            if($v->status==1){
                $key=$v->id;
                $arrBankName[$key]= $v->name;
            }
        }
        return $arrBankName;
    }
    /**
     * 根据id获取银行名称
     * @return array|mixed|null
     */
    public function getBankNameById($id){
        $list = Yii::app()->db->createCommand()->select("name")->from("t_bank_info")->where('id=:id', array(':id' => $id))->queryAll();
        foreach ($list as $key => $bank) {
            $bankName=$bank['name'];
        }
        return empty($bankName) ? false : $bankName;
    }
}