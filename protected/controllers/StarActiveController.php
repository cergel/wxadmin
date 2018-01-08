<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 17:39
 */
class StarActiveController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'update', 'delete','detail','sche','SearchSche','DelDetail','ceshi'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 新增活动
     */
    public function actionCreate(){

        $model=new StarActive();

        if(isset($_POST['StarActive'])){
            if(!empty($_POST['StarActive']['a_detail'])){
                $_POST['StarActive']['a_detail'] = $this->_formatDetail($_POST['StarActive']['a_detail']);
            }
            $model->attributes=$_POST['StarActive'];
            $model->a_created = time();
            $model->a_create_id = Yii::app()->getUser()->getId();
            $model->a_create_name=Yii::app()->getUser()->getName();
            if($model->save()){
                StarActive::model()->saveCache();
                //写入共享redis 影院排期信息
                StarActiveSche::model()->setStarActiveRedis();
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     * 更新活动
     */
    public function actionUpdate($id){
        $model=$this->loadModel($id);
        $model->a_detail = str_replace('<br/>','',$model->a_detail);
        if(isset($_POST['StarActive']))
        {
            if(!empty($_POST['StarActive']['a_detail'])){
                $_POST['StarActive']['a_detail'] = $this->_formatDetail($_POST['StarActive']['a_detail']);
            }
            $model->attributes=$_POST['StarActive'];
            if($model->save()) {
                StarActive::model()->saveCache();
                //更新影院排期redis
                StarActiveSche::model()->setStarActiveRedis();
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->a_id));
            }
        }
        $this->render('update',array(
            'model'=>$model,
        ));
    }
    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model=new StarActive('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['StarActive']))
            $model->attributes=$_GET['StarActive'];
        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     * 删除活动
     */

    public function actionDelete($id){

        $model =$this->loadModel($id);
        $objData = StarActiveSche::model()->findAll("a_id=:a_id",['a_id'=>$id]);
        foreach($objData as $val){
            $val->delete();
        }
        $model=$this->loadModel($id)->delete();
        StarActive::model()->saveCache();
        StarActiveSche::model()->setStarActiveRedis();
        Yii::app()->user->setFlash('success', '删除成功');
        $this->redirect(array('index'));
    }
    /**
     * @param $id
     * @throws CHttpException
     * 删除排期
     */

    public function actionDelDetail($id){
        $model = StarActiveSche::model()->findByPk($id);
        $cinemaId = $model->s_cinema_id;
        if($model->delete()){
            StarActive::model()->saveCache();
            StarActiveSche::model()->setStarActiveRedis();
            Yii::app()->user->setFlash('success', '删除成功');
        }
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));

    }

    /**
     * @param $id
     * 活动详情列表
     */
    public function actionDetail($id){
        $model=new StarActiveSche('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['StarActiveSche'])){
            $_GET['StarActiveSche']['a_id'] = $id;
            $model->attributes=$_GET['StarActiveSche'];
        }else {
            $model->a_id = $id;
        }

        $this->render('detail',array(
            'model' => $model,
            'a_id'=>$id,
        ));
    }

    /**
     * 新增排期
     */
    public function actionSche(){
        if(!empty($_POST['filmId']))
        {
            $aid = !empty($_POST['aid'])?$_POST['aid']:'';
            $cinemaId = !empty($_POST['cinemaId'])?$_POST['cinemaId']:'';
            $cinemaName = !empty($_POST['cinemaName'])?$_POST['cinemaName']:'';
            $filmId = !empty($_POST['filmId'])?$_POST['filmId']:'';
            $filmName = !empty($_POST['filmName'])?$_POST['filmName']:'';
            $arrMpid =!empty( $_POST['mpid'])? $_POST['mpid']:'';
            $arrRoom = !empty($_POST['room'])?$_POST['room']:'';
            $arrSType = !empty($_POST['sType'])?$_POST['sType']:'';
            $arrStartTime = !empty($_POST['startTime'])?$_POST['startTime']:'';
            $arrEndTime = !empty($_POST['endTime'])?$_POST['endTime']:'';
            if(empty($aid) || empty($cinemaId) || empty($cinemaName) || empty($filmId) || empty($filmName) || empty($arrMpid) || empty($arrRoom) || empty($arrSType) || empty($arrStartTime) || empty($arrEndTime)){
                echo json_encode(['ret'=>-1,'msg'=>'参数不全，请重新检查']);exit;
            }
            $strError = '';
            foreach($arrMpid as $key =>$val){
                if(empty($val)){
                    continue;
                }
                $arrInstallData = [];
                $arrInstallData['a_id'] = $aid;
                $arrInstallData['s_cinema_id'] = $cinemaId;
                $arrInstallData['s_cinema_name'] = $cinemaName;
                $arrInstallData['s_movie_id'] = $filmId;
                $arrInstallData['s_movie_name'] = $filmName;
                $arrInstallData['s_sche_id'] = $val;
                $arrInstallData['s_start_time'] = @$arrStartTime[$key];
                $arrInstallData['s_end_time'] = @$arrEndTime[$key];
                $arrInstallData['s_sche_type'] = @$arrSType[$key];
                $arrInstallData['s_sche_room'] = @$arrRoom[$key];
                $arrInstallData['s_created'] = time();
                $starActiveScheModel = new StarActiveSche();
                $starActiveScheModel->setAttributes($arrInstallData);
                if(! $starActiveScheModel->save()){
                    $strError .= $val.',';
                }
            }
            //更新缓存信息
            StarActive::model()->saveCache();
            StarActiveSche::model()->setStarActiveRedis();

            if(empty($strError)){
                $strError = '插入成功';
            }
            echo json_encode(['ret'=>0,'msg'=>$strError]);exit;


        }else{
            $aid = !empty($_GET['aid'])?$_GET['aid']:'';
            if(empty($aid)){
                Yii::app()->user->setFlash('error', '没有活动ID');
                $this->redirect(array('index'));
            }else{
                $this->render('sche',array(
                    'aid'=>$aid,
                ));
            }

        }

    }

    /**
     * 测试使用
     */
    public  function  actionCeshi(){
        $aa = StarActiveSche::model()->setRedisCeshi();
        var_dump($aa);
    }

    /**
     * 查询排期(其实就是拉一下排期)
     */
    public function actionSearchSche()
    {
        $resData = ['ret'=>0,'msg'=>''];
        $cinemaId = $_GET['cinemaId'];
        $filmId = $_GET['filmId'];
        $date = empty($_GET['dateK'])?'':$_GET['dateK'];
        //获取城市id
        $cinemaInfo = Https::getCinemaInfoByCinemaId($cinemaId);
        $cityId = !empty($cinemaInfo['city_id'])?$cinemaInfo['city_id']:10;
        $arrData = [];
        $arrData['cityId'] = $cityId;
        $arrData['cinemaId'] = $cinemaId;
        $arrData['movieId'] = $filmId;
        $cinemaName =!empty($cinemaInfo['name'])?$cinemaInfo['name']:'';
//        $url = Yii::app()->params['commoncgi']['cinema_sche'];
        $url = "http://commoncgi.wepiao.com/channel/sche/get-cinema-sche";
        $resData = Https::getPost($arrData,$url);
        $cinemaSche = [];
        $scheData = [];
        if(preg_match("/^MovieData\.set\(\"\w+\",(.*)\);/ius", $resData, $arr)){
            $cinemaSche = json_decode($arr[1],1);
        }
        if(isset($cinemaSche['data'])) {
            echo json_encode(array('ret'=> -1,'msg'=>'没有排期'));exit;
        }
        foreach ($cinemaSche as $k => $v) {
            if ($v['id'] == $filmId) {
                $scheData = $cinemaSche[$k];
            }
        }
        if(empty($scheData)) {
            echo json_encode(array('ret'=> -1,'msg'=>'没有排期'));exit;
        }
        $scheData = self::getCinemaScheInfo($scheData,$date);
        $scheData['cinemaId'] = $cinemaId;
        $scheData['cinemaName'] = $cinemaName;
        echo json_encode($scheData);exit;
    }

    public static function getCinemaScheInfo($arrData,$date)
    {
        $resData = ['ret'=>0,'msg'=>''];
        if(empty($arrData['sche'])){
            $resData['ret'] = -1;
            $resData['msg'] = '没有排期';
            return $resData;
        }
        $movieName =$arrData['name'];
        $movieId = $arrData['id'];
        //获取指定天的排期或者所有的排期
        $arrSche = [];
        if(!empty($date)){
            $date = date('Ymd',strtotime($date));
            foreach($arrData['sche'] as $scheDate=>$dateTime){                
                if($scheDate == $date){
                    foreach ($dateTime as $k=>$v){
                        $arrSche = array_merge_recursive($arrSche,$v['seat_info']);
                    }
                }
            }
        }else{
            foreach($arrData['sche'] as $dateTime){
                foreach ($dateTime as $k=>$v){
                        $arrSche = array_merge_recursive($arrSche,$v['seat_info']);
                }
            }
        }
        $returnData = [];
        foreach ($arrSche as $key=>$val) {
            $returnData[$key]['start_unixtime'] = $val['start_unixtime'];
            $returnData[$key]['start_time'] = date('Ymd H:is',$val['start_unixtime']);
            $returnData[$key]['end_unixtime'] = $val['end_unixtime'];
            $returnData[$key]['end_time'] = date('Ymd H:is',$val['end_unixtime']);
            $returnData[$key]['mpid'] = $val['mpid'];
            $returnData[$key]['roomname'] = $val['roomname'];
            $returnData[$key]['type'] = $val['type'];
            $returnData[$key]['lagu'] = $val['lagu'];
        }
        $resData['movieId'] = $movieId;
        $resData['movieName'] = $movieName;
        $resData['data'] = $returnData;
        return $resData;
    }

    /**
     * 提取排期
     * @param $arrData
     * @param $date
     * @param $cinemaId
     * @param $cinemaName
     * @return array
     */
    private static function getScheInFo($arrData,$date)
    {
        $resData = ['ret'=>0,'msg'=>''];
        if($arrData['ret'] != 0){
            $resData['ret'] = $arrData['ret'];
            $resData['msg'] = $arrData['msg'];
            return $resData;
        }
        if(empty($arrData['data'][0]['sche'])){
            $resData['ret'] = -1;
            $resData['msg'] = '没有排期';
            return $resData;
        }
        $arrData = $arrData['data'][0];
        $movieName =$arrData['name'];
        $movieId = $arrData['id'];
        //获取指定天的排期或者所有的排期
        $arrSche = [];
        if(!empty($date)){
            $date = date('Ymd',strtotime($date));
            foreach($arrData['sche'] as $dateTime){
                if($dateTime['date'] == $date){
                    $arrSche = $dateTime['info'];
                }
            }
        }else{
            foreach($arrData['sche'] as $dateTime){
                $arrSche = array_merge_recursive($arrSche,$dateTime['info']);
            }
        }
        $resData['movieId'] = $movieId;
        $resData['movieName'] = $movieName;
        $resLData = self::scheList($arrSche);
        $resData = array_merge($resData,$resLData);
        return $resData;
    }

    /**
     * 格式化排期
     * @param $arrSche
     * @return array
     */
    private static function scheList($arrSche)
    {
        $resData = ['ret'=>0,'msg'=>''];
        if(empty($arrSche)){
            $resData['ret'] = -1;
            $resData['msg'] = '没有排期';
            return $resData;
        }
        $arrNew = [];
        foreach($arrSche as $val){
            $arrNew = array_merge_recursive($arrNew,$val['seat_info']);
        }
        foreach($arrNew as &$sche){
            $arrData =[];
            $arrData['start_unixtime'] = $sche['start_unixtime'];
            $arrData['start_time'] = date('Ymd H:is',$sche['start_unixtime']);
            $arrData['end_unixtime'] = $sche['end_unixtime'];
            $arrData['end_time'] = date('Ymd H:is',$sche['end_unixtime']);
            $arrData['mpid'] = $sche['mpid'];
            $arrData['roomname'] = $sche['roomname'];
            $arrData['type'] = $sche['type'];
            $arrData['lagu'] = $sche['lagu'];
            $sche = $arrData;
        }
        $resData['data'] = $arrNew;
        return $resData;
    }

    /**
     * @param $id
     * @return array|mixed|null
     * @throws CHttpException
     */
    public function loadModel($id){
        $model = StarActive::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    /**
     * Performs the AJAX validation.
     * @param DiscoveryBanner $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'star-active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //替换函数，将\r替换成\r<br/>
    protected function _formatDetail($detail){
        return str_replace("\r","\r".'<br/>',$detail);
    }
}