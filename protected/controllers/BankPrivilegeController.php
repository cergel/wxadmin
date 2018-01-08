<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 11:40
 */
class BankPrivilegeController extends Controller{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/main';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array(
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('update','delete','create','index','_status_update','_sort_update'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * ajax编辑排序
     */
    public function action_sort_update($id){
        $model=$this->loadModel($id);
        $model->sort = intval($_POST['sort']);
        $model->save();
        echo $model->sort;
    }
    /**
     * ajax上下线
     */
    public function action_status_update($id){
        $model=$this->loadModel($id);
        if ($model->status == '1')
            $model->status = '0';
        else
            $model->status = '1';
        $model->save();
        echo $model->status;
    }

    /**
     * 新增活动
     */
    public function actionCreate(){
        $model=new BankPrivilege();
        $selectedCities = array();
        if(isset($_POST['BankPrivilege'])){

//            if(empty($_POST['BankPrivilege']['detail']) && empty($_POST['BankPrivilege']['link'])){
//                header("Content-type: text/html; charset=utf-8");
//                echo "活动详情和办卡链接两个务必且只能填其一";die;
//            }
//            if(!empty($_POST['BankPrivilege']['detail']) && !empty($_POST['BankPrivilege']['link'])){
//                header("Content-type: text/html; charset=utf-8");
//                echo "活动详情和办卡链接两个务必且只能填其一";die;
//            }

            $photo=CUploadedFile::getInstance($model,'photo');
            if($photo) {
                $fileName = date('YmdHis').rand(10000,99999).'.'.$photo->getExtensionName();
                $_POST['BankPrivilege']['photo'] ='/uploads/bank_img/'.$fileName;
            }else{
                $_POST['BankPrivilege']['photo'] = '';
               // Yii::app()->user->setFlash('error','图片为必传字段');
               // $this->redirect(array('create'));
               // return false;
            }
            $model->attributes = $_POST['BankPrivilege'];
//            $cinemas=$_POST['BankPrivilege']['cinemas'];
//            $movies=$_POST['BankPrivilege']['movies'];
            if (isset($_POST['cities']))
                $selectedCities = $_POST['cities'];
            if (count($selectedCities) >1){
                foreach ($selectedCities as $key=>$cityId)
                {
                    if (empty($cityId)) unset($selectedCities[$key]);
                }
            }
            if( $model->save()){
                if($photo) {
                    $uploadDir = Yii::app()->basePath . '/../uploads/bank_img/';
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $photo->saveAs($uploadDir . '/' .$fileName, true);
                }
                // 处理城市关联
                $model->saveCities();
                // 处理影院关联
                $model->saveCinemas(0);
                // 处理影片关联
                $model->saveMovies(0);
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('update','id'=>$model->id));
            }else{
                $model->movies='';
                $model->cinemas='';
            }
        }else{
            $model->movies='';
            $model->cinemas='';
        }
        $this->render('create',array(
            'model'=>$model,
            'selectedCities' => $selectedCities,
        ));
    }

    /**
     * 更新
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id){
        $model=$this->loadModel($id);
        $selectedCities=array();
        $selectedCinemas=array();
        $selectedMovies=array();

        if (is_array($model->cities)){
            foreach($model->cities as $result) {
                if(!empty($result['city_id'])){
                    $selectedCities[] = $result['city_id'];
                }
            }
            if (count($selectedCities) >1){
                foreach ($selectedCities as $key=>$cityId)
                {
                    if (empty($cityId)) unset($selectedCities[$key]);
                }
            }
        }
        if (is_array($model->cinemas)){
            foreach($model->cinemas as $result) {
                if(!empty($result['cinemas_id'])){
                    $selectedCinemas[] = $result['cinemas_id'];
                }
            }
            if (count($selectedCinemas) >1){
                foreach ($selectedCinemas as $key=>$cinemaId)
                {
                    if (empty($cinemaId)) unset($selectedCinemas[$key]);
                }
            }
        }
        $model->cinemas=implode(',',$selectedCinemas);
        if (is_array($model->movies)){
            foreach($model->movies as $result) {
                if(!empty($result['movies_id'])){
                    $selectedMovies[] = $result['movies_id'];
                }
            }
            if (count($selectedMovies) >1){
                foreach ($selectedMovies as $key=>$moviesId)
                {
                    if (empty($moviesId)) unset($selectedMovies[$key]);
                }
            }
        }
        $model->movies=implode(',',$selectedMovies);
        if(isset($_POST['BankPrivilege'])){
//            if(empty($_POST['BankPrivilege']['detail']) && empty($_POST['BankPrivilege']['link'])){
//                header("Content-type: text/html; charset=utf-8");
//                echo "活动详情和办卡链接两个务必且只能填其一";die;
//            }
//            if(!empty($_POST['BankPrivilege']['detail']) && !empty($_POST['BankPrivilege']['link'])){
//                header("Content-type: text/html; charset=utf-8");
//                echo "活动详情和办卡链接两个务必且只能填其一";die;
//            }
//            $cinemas = $_POST['BankPrivilege']['cinemas'];
//            $movies = $_POST['BankPrivilege']['movies'];
            $photo=CUploadedFile::getInstance($model,'photo');
            if($photo) {
                $fileName = date('YmdHis').rand(10000,99999).'.'.$photo->getExtensionName();
                $_POST['BankPrivilege']['photo'] ='/uploads/bank_img/'.$fileName;
            }else{
                unset($_POST['BankPrivilege']['photo']);
            }
            $model->attributes = $_POST['BankPrivilege'];
            if ($model->save()) {
                if($photo) {
                    $uploadDir = Yii::app()->basePath . '/../uploads/bank_img/';
                    if (!file_exists($uploadDir))
                        mkdir($uploadDir, 0777, true);
                    $photo->saveAs($uploadDir . '/' .$fileName, true);
                }
                // 处理城市关联
                $model->saveCities();
                // 处理影院关联
                $model->saveCinemas(0);
                // 处理影片关联
                $model->saveMovies(0);
                Yii::app()->user->setFlash('success','更新成功');
                $this->redirect(array('update','id'=>$model->id));
            }
        }
        $this->render('update',array(
            'model'=>$model,
            'selectedCities' => $selectedCities,
        ));
    }

    /**
     * 首页
     */
    public function actionIndex(){
        $model=new BankPrivilege('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['BankPrivilege']))
            $model->attributes=$_GET['BankPrivilege'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);
        $model->delete();
        // if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DiscoveryBanner the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=BankPrivilege::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DiscoveryBanner $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='bank-privilege-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}