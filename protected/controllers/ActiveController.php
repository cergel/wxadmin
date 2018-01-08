<?php

class ActiveController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete','ajaxUpload','activeOnlineOffline'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
        
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Active;
        $selectedCities = array();
        $model->setRedisJson();
        $model->iIsonline=0;//初始给一个0，如果有值再赋值
        if (isset($_POST['Active'])) {
            $arrData = $this->makeFormData();
            if (isset($_POST['cities']))
                $selectedCities = $_POST['cities'];
            if (count($selectedCities) >1){
                foreach ($selectedCities as $key=>$cityId)
                {
                    if (empty($cityId)) unset($selectedCities[$key]);
                }
            }

            $arrData['iDirect_city']=(isset($_POST['cities'])&&count($_POST['cities']))>0?1:0;
            $arrData['iOnline_time']=isset($arrData['iOnline_time'])?strtotime($arrData['iOnline_time']):'';
            $arrData['iOffline_time']=isset($arrData['iOffline_time'])?strtotime($arrData['iOffline_time']):'';
            $arrData['create_time'] = time();
            $arrData['update_time'] = time();
            $arrData['iStatus'] = 1;//1标志位正常，0为删除
            $model->attributes = $arrData;
            $model->post_cities=isset($_POST['cities'])?$_POST['cities']:'';
            $model->post_release=isset($_POST['release'])?$_POST['release']:'';
            $model->post_share=isset($_POST['share'])?$_POST['share']:'';
            #$model->sShare_link = !empty($arrData['sShare_link'])?$arrData['sShare_link']:$this->getShareLink($model->iActive_id);
            #$model->sShare_otherLink = !empty($arrData['sShare_otherLink'])?$arrData['sShare_otherLink']:$this->getShareOtherLink($model->iActive_id);

            $model->updateType='edit';
            if ($model->save()) {
                $model->saveOthers();
                $this->loadModel($model->iActive_id)->createFileList();
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->iActive_id,'updateType'=>'edit'));
            }
        }
        $this->render('create', array(
            'model' => $model,
            'selectedCities' => $selectedCities,
            'updateType'=>'edit',
        ));
    }

    //整理要写入model的数据
    private function makeFormData()
    {
        $arrReturn = $_POST['Active'];
        if($_POST['updateType']=='edit'){
            if(isset($arrReturn['iType'])){
                switch ($arrReturn['iType']) {
                    case 1://图文
                        $arrReturn['sVideo_link'] = '';
                        $arrReturn['sVideo_time'] = '';
                        $arrReturn['sPicture'] = '';
                        $arrReturn['sAudio_link'] = '';
                        $arrReturn['sAudio_time'] = '';
                        break;
                    case 2://视频
                        $arrReturn['sContent'] = '';
                        $arrReturn['sPicture'] = '';
                        $arrReturn['sAudio_link'] = '';
                        $arrReturn['sAudio_time'] = '';
                        break;
                    case 3://相册
                        $arrReturn['sContent'] = '';
                        $arrReturn['sVideo_link'] = '';
                        $arrReturn['sVideo_time'] = '';
                        $arrReturn['sAudio_link'] = '';
                        $arrReturn['sAudio_time'] = '';
                        $arrReturn['sPicture'] = $this->createAlbumColum();
                        break;
                    case 4://音频
                        $arrReturn['sVideo_link'] = '';
                        $arrReturn['sVideo_time'] = '';
                        $arrReturn['sPicture'] = '';
                        $arrReturn['sVideo_link'] = '';
                        $arrReturn['sVideo_time'] = '';
                        break;
                    default://默认不会用到，用到的话走图文
                        $arrReturn['sVideo_link'] = '';
                        $arrReturn['sVideo_time'] = '';
                        $arrReturn['sPicture'] = '';
                        $arrReturn['sAudio_link'] = '';
                        $arrReturn['sAudio_time'] = '';
                        break;
                }
            }
        }
        return $arrReturn;
    }



    //创建相册的json
    private function createAlbumColum()
    {
        $arrContent = [];//用于存放相册图片的数组
        $arrAlbum = [];//用于存放相册图片链接
        $arrJson = [];//最终返回的json
        if(isset($_POST['Active']['album_content']) && isset($_POST['Active']['album_pic'])){
            foreach ($_POST['Active']['album_content'] as $v) {
                $arrContent[] = $v;
            }
            foreach ($_POST['Active']['album_pic'] as $k => $v) {
                $arrAlbum[] =  $v;
            }
            foreach ($arrAlbum as $k => $v) {
                $arrJson[] = ['path' => $v, 'content' => $arrContent[$k]];
            }
        }
        return json_encode($arrJson);
    }

    //对上传的文件进行处理
    /**
     * @param $tmpFileName $_FILES中的tmp_name
     * @param $fileName $_FILES中的name
     * @return string
     */
    private function uploadFile($tmpFileName, $fileName)
    {
        //保存在数据库里的路径，/uploads开头
        $Path = '/uploads/active/' . date("Ymd") . '/' . date('H');
        //本地文件路径,上传后要将文件移动到的地方
        $localPath = dirname(Yii::app()->basePath) . $Path;
        if (!is_dir($localPath)) {
            mkdir($localPath, 755, true);//第三个参数，递归创建
        }
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName));
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        if (move_uploaded_file($tmpFileName, $localPath . '/' . $fileName)) {
            return $Path . '/' . $fileName;
        } else {
            return false;
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->updateType=$_GET['updateType'];
        $selectedCities = array();
        $model->iOnline_time=date("Y-m-d H:i:s",!empty($model->iOnline_time)?$model->iOnline_time:time());
        $model->iOffline_time=date("Y-m-d H:i:s",!empty($model->iOffline_time)?$model->iOffline_time:time());
        //定向城市
        if(isset($model->city) && !empty($model->city)){
            foreach($model->city as $v){
                $selectedCities[]=$v->city_id;
            }
        }

        //发布平台
        $arrRelease=array();
        foreach($model->release as $k=>$v){
            $arrRelease[$v->release]=1;
        }
        //分享平台
        $arrShare=array();
        foreach($model->share as $k=>$v){
            $arrShare[$v->share]=1;
        }

        $model->sPicture = json_decode($model->sPicture, true);
        if (isset($_POST['Active'])) {
            $model->sPicture = json_encode([]);
            if (isset($_POST['cities']))
                $selectedCities = $_POST['cities'];
            if (count($selectedCities) >1){
                foreach ($selectedCities as $key=>$cityId)
                {
                    if (empty($cityId)) unset($selectedCities[$key]);
                }
            }
            $arrData = $this->makeFormData();
            $arrData['update_time'] = time();
            if($_POST['updateType']=='edit'){
                $model->iType = $arrData['iType'];
                $model->sTitle = $arrData['sTitle'];
                $model->sSummary = $arrData['sSummary'];
                $model->sSource_name = $arrData['sSource_name'];
                $model->sSource_head = $arrData['sSource_head'];
                $model->sSource_summary = $arrData['sSource_summary'];
                $model->sSource_link = $arrData['sSource_link'];
                $model->sShare_logo = $arrData['sShare_logo'];
                $model->sShare_title = $arrData['sShare_title'];
                $model->sShare_summary = $arrData['sShare_summary'];

                $model->sContent = $arrData['sContent'];
                $model->sVideo_link = $arrData['sVideo_link'];
                $model->sVideo_time = $arrData['sVideo_time'];
                $model->sPicture = $arrData['sPicture'];
                $model->sAudio_link = $arrData['sAudio_link'];
                $model->sAudio_time = $arrData['sAudio_time'];
                $model->iType = $arrData['iType'];

                $model->post_share=isset($_POST['share'])?$_POST['share']:'';
                $model->sShare_link = !empty($arrData['sShare_link'])?$arrData['sShare_link']:$this->getShareLink($model->iActive_id);
                $model->sShare_otherLink = !empty($arrData['sShare_otherLink'])?$arrData['sShare_otherLink']:$this->getShareOtherLink($model->iActive_id);
                unset($model->iOnline_time);
                unset($model->iOffline_time);
            }
            if($_POST['updateType']=='release'){
                unset($model->sPicture);
                $model->sTitle = $arrData['sTitle'];
                $model->sCover = $arrData['sCover'];
                $model->sTag = $arrData['sTag'];
                $model->iFill = $arrData['iFill'];
                $model->iFillRead = $arrData['iFillRead'];
                $model->iOnline_time=isset($arrData['iOnline_time'])?strtotime($arrData['iOnline_time']):'';
                $model->iOffline_time=isset($arrData['iOffline_time'])?strtotime($arrData['iOffline_time']):'';
                $model->iDirect_city=(isset($_POST['cities'])&&count($_POST['cities']))>0?1:0;
                $model->post_cities=isset($_POST['cities'])?$_POST['cities']:'';
                $model->post_release=isset($_POST['release'])?$_POST['release']:'';
            }
            if ($model->save()) {
                $this->loadModel($model->iActive_id)->createFileList();
//                FlushCdn::setUrlToRedis(Yii::app()->params['CMS']['final_url'] . '/' . $model->iActive_id, Yii::app()->params['CMS']['local_dir'] . '/' . $model->iActive_id);
                Yii::app()->user->setFlash('success', '更新成功');
                $updateType = isset($_POST['updateType'])?$_POST['updateType']:'edit';
                $this->redirect(array('update', 'id' => $model->iActive_id,'updateType'=>$updateType));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'selectedCities' => $selectedCities,
            'release'=>$arrRelease,
            'share'=>$arrShare,
            'updateType'=>isset($_GET['updateType'])?$_GET['updateType']:'edit',
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Active('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Active']))
            $model->attributes = $_GET['Active'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Active the loaded model
     * @throws CHttpException
     */
    public
    function loadModel($id)
    {
        $model = Active::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Active $model the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     * 图片异步上传接口
     */
    public function actionAjaxUpload(){
        $tmpFileName=$_FILES['UpLoadFile']['tmp_name'];
        $fileName=$_FILES['UpLoadFile']['name'];
        $r=$this->uploadFile($tmpFileName, $fileName);
        if($r){
            $arrReturn['success']=1;
            $arrReturn['path']=$r;
        }else{
            $arrReturn['success']=0;
        }
        echo json_encode($arrReturn);
    }

    //活动上线/下线接口
    public function actionActiveOnlineOffline(){

        $activeId = $_GET['activeId'];
        $model = $this->loadModel($activeId);
        $model->iIsonline = ($model->iIsonline==1)?0:1;

        $cityAttributes = array('iActive_id'=>$activeId);
        $activeCityResult = ActiveCity::model()->findAllByAttributes($cityAttributes);
        if($activeCityResult){
            foreach($activeCityResult as $v){
                $post_cities[] = $v->city_id;
            }
        }else{
            $post_cities = array();
        }


        $releaseAttributes = array('iActive_id'=>$activeId);
        $activeReleaseResult = ActiveReleaseOld::model()->findAllByAttributes($releaseAttributes);
        if($activeReleaseResult){
            foreach($activeReleaseResult as $v){
                $post_release[] = $v->release;
            }
        }else{
            $post_release = array();
        }
        $model->post_cities = $post_cities;
        $model->post_release = $post_release;
        $model->updateType='ajaxOnline';
        $r=$model->save();
        if($r){
            $return = [
                'succ'=>1,
                'online'=>$model->iIsonline,
            ];
        }else{
            $return = [
                'succ'=>0,
                'msg'=>'数据库访问失败',
            ];
        }
        echo json_encode($return);
    }


    //获取分享到微信的链接
    private function getShareLink($id){
        $url = Yii::app()->params['CMS']['final_url'] . "/{$id}/3/index.html?appdl=3&cid=8";
        return $url;
    }

    //获取分享到非微信的链接
    private function getShareOtherLink($id){
        $url = Yii::app()->params['CMS']['final_url'] . "/{$id}/6/index.html?appdl=3&cid=8";
        return $url;
    }
}
