<?php

class CmsController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxUpload', 'activeOnlineOffline', 'getCmsInfoAjax','saveCdn'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 首页：查询页面
     */
    public function actionIndex()
    {
        $model = new ActiveCms('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActiveCms']))
            $model->attributes = $_GET['ActiveCms'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * 创建
     * @throws CHttpException
     */
    public function actionCreate()
    {
        $model = new ActiveCms;
        $selectedCities = array();
        if (isset($_POST['ActiveCms'])) {
            $arrData = $this->makeFormData();
            $arrData['iDirect_city'] = (isset($_POST['cities']) && count($_POST['cities'])) > 0 ? 1 : 0;
            $arrData['iOnline_time'] = isset($arrData['iOnline_time']) ? strtotime($arrData['iOnline_time']) : '';
            $arrData['iOffline_time'] = isset($arrData['iOffline_time']) ? strtotime($arrData['iOffline_time']) : '';
            $arrData['create_time'] = time();
            $arrData['update_time'] = time();
            $arrData['iStatus'] = 1;//1标志位正常，0为删除
            $model->attributes = $arrData;
            if ($model->save()) {
                self::saveOther($model->iActive_id);
                self::addShare($model->iActive_id);
                self::addNews($model->iActive_id);
                ActiveCms::model()->saveCache($model->iActive_id);
                //$model->saveOthers();
                $this->loadModel($model->iActive_id)->createFileList();
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->iActive_id));
            }
        }
        $model->iIsonline = 1;
        $model->iType = 1;
        $model->is_news = 1;
        $model->movie_id = '';
        $model->news_time = date('Y-m-d H:i:s', time());
        $model->news_photo = '';
        $this->render('create', array(
            'model' => $model,
            'selectedCities' => $selectedCities,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->sPicture = json_decode($model->sPicture, true);
        if (isset($_POST['ActiveCms'])) {
            $_POST['ActiveCms']['iType'] = $model->iType;
            $arrData = $this->makeFormData();
            $arrData['update_time'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                self::saveOther($model->iActive_id);
                self::addShare($model->iActive_id);
                self::addNews($model->iActive_id);
                ActiveCms::model()->saveCache($model->iActive_id);
                $this->loadModel($model->iActive_id)->createFileList();

                //刷新CDN
                FlushCdn::setUrlToRedis(Yii::app()->params['CMS_new']['final_url'] . '/' . $model->iActive_id.'/index.html');
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->iActive_id));
            }
        }
        //分享平台s
        $arrShare = [];
        foreach ($model->share as $k => $v) {
            $arrShare[] = $v->share;
        }
        $model->share = $arrShare;

        $model->news_time = date('Y-m-d H:i:s', time());
        $model->news_photo = '';
        $model->is_news = 0;
        $strMovie = '';
        foreach ($model->movie_id as $k => $v) {
            $strMovie .= $v->movie_id . ',';
            $model->is_news = 1;
            $model->news_time = date('Y-m-d H:i:s', $v->up_time);
            $model->news_photo = $v->n_photo;
        }

        if(!empty($model->sSource_id)){
            $authorModel = Author::model()->findByPk($model->sSource_id);
            if(!empty($authorModel)){
                $model->sSource_name = $authorModel->name_author;
                $model->sSource_head = $authorModel->head_img;
                $model->sSource_qr = $authorModel->qr_img;
                $model->sSource_summary = $authorModel->summary;
            }else{
                $model->sSource_id = '';
            }
        }
        if(empty($model->sSource_id)){
            $model->sSource_id = '';
            $model->sSource_name = '';
            $model->sSource_head = '';
            $model->sSource_qr = '';
            $model->sSource_summary = '';
        }
        $model->movie_id = $strMovie;
        $this->render('update', array('model' => $model,));
    }

    public function loadModel($id)
    {
        $model = ActiveCms::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    private function makeFormData()
    {
        if(!empty($_POST['ActiveCms']['sSource_id'])){
            $authorModel = Author::model()->findByPk($_POST['ActiveCms']['sSource_id']);
            if(!empty($authorModel)){
                $_POST['ActiveCms']['sSource_name'] = $authorModel->name_author;
                $_POST['ActiveCms']['sSource_head'] = $authorModel->head_img;
                $_POST['ActiveCms']['sSource_qr'] = $authorModel->qr_img;
                $_POST['ActiveCms']['sSource_summary'] = $authorModel->summary;
            }else{
                $_POST['ActiveCms']['sSource_id'] = '';
            }
        }
        if(empty($_POST['ActiveCms']['sSource_id'])){
            $_POST['ActiveCms']['sSource_name'] = '';
            $_POST['ActiveCms']['sSource_head'] = '';
            $_POST['ActiveCms']['sSource_qr'] = '';
            $_POST['ActiveCms']['sSource_summary'] = '';
        }
        $arrReturn = $_POST['ActiveCms'];
        if (isset($arrReturn['iType'])) {
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
        unset($arrReturn['is_news']);
        unset($arrReturn['movie_id']);
        unset($arrReturn['news_photo']);
        unset($arrReturn['news_time']);
        $arrReturn['iFill'] = intval($arrReturn['iFill']);
        $arrReturn['iFillRead'] = intval($arrReturn['iFillRead']);
        //发布平台
        $arrReturn['iIsonline'] = 1;
        return $arrReturn;
    }

    //创建相册的json
    private function createAlbumColum()
    {
        $arrContent = [];//用于存放相册图片的数组
        $arrAlbum = [];//用于存放相册图片链接
        $arrJson = [];//最终返回的json
        if (isset($_POST['ActiveCms']['album_content']) && isset($_POST['ActiveCms']['album_pic'])) {
            foreach ($_POST['ActiveCms']['album_content'] as $v) {
                $arrContent[] = $v;
            }
            foreach ($_POST['ActiveCms']['album_pic'] as $k => $v) {
                $arrAlbum[] = $v;
            }
            foreach ($arrAlbum as $k => $v) {
                $arrJson[] = ['path' => $v, 'content' => $arrContent[$k]];
            }
        }
        return json_encode($arrJson);
    }

    /**
     * 图片异步上传接口
     */
    public function actionAjaxUpload()
    {
        $tmpFileName = $_FILES['UpLoadFile']['tmp_name'];
        $fileName = $_FILES['UpLoadFile']['name'];
        $r = $this->uploadFile($tmpFileName, $fileName);
        if ($r) {
            $arrReturn['success'] = 1;
            $arrReturn['path'] = $r;
        } else {
            $arrReturn['success'] = 0;
        }
        echo json_encode($arrReturn);
    }

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
     * 添加分享
     * @param $activeId
     */
    private function  addShare($activeId)
    {
        self::delShare($activeId);
        if (!empty($_POST['ActiveCms']['share'])) {
            foreach ($_POST['ActiveCms']['share'] as $k => $share) {
                if (empty($share)) continue;
                $cnc = new ActiveShare();
                $cnc->iActive_id = $activeId;
                $cnc->share = $share;
                $cnc->save();
            }
        }
    }

    /**
     * 删除分享关联
     * @param $activeId
     */
    private function delShare($activeId)
    {
        ActiveShare::model()->deleteAllByAttributes(array('iActive_id' => $activeId));
    }

    /**
     * 添加
     * @param $activeId
     */
    private function  addNews($id)
    {
        if (empty($id)) return false;
        self::delNews($id);
        if (isset($_POST['ActiveCms']['movie_id']) && !empty($_POST['ActiveCms']['movie_id'])) {
            $arrMovie = explode(',', $_POST['ActiveCms']['movie_id']);
            $url = !empty($_POST['ActiveCms']['news_photo']) ? $_POST['ActiveCms']['news_photo'] : '';
            $status = !empty($_POST['ActiveCms']['is_news']) ? 1 : 0;
            $time = !empty($_POST['ActiveCms']['news_time']) ? $_POST['ActiveCms']['news_time'] : '';
            $time = strtotime($time);
            foreach ($arrMovie as $val) {
                $val = intval(trim($val));
                if (empty($val)) continue;
                $cnc = new ActiveNews();
                $cnc->a_id = $id;
                $cnc->movie_id = $val;
                $cnc->status = $status;
                $cnc->n_photo = $url;
                $cnc->up_time = $time;
                $cnc->save();
                $cnc->addCache($cnc->id);
                //写入缓存
            }
        }
        ActiveFind::model()->saveFindCmsOther();
    }

    /**
     * 删除
     * @param $id
     */
    private function delNews($id)
    {
        ActiveNews::model()->delCache($id);
        ActiveNews::model()->deleteAllByAttributes(array('a_id' => $id));
    }

    /*
     * 获取内容
     */
    public function actionGetCmsInfoAjax($id)
    {
        $model = $this->loadModel($id);
        $arrData = $model->attributes;
        $channel = ActiveCms::model()->getChannel('list');
        foreach ($channel as $k => $v) {
            if (!empty($k)) {
                $arrData['channelUrl' . $k] = Yii::app()->params['CMS_new']['final_url'] . '/' . $id .'/index.html';
            }
        }
        $arrData['channelUrl'] = Yii::app()->params['CMS_new']['final_url'] . '/' . $id .'/index.html';
        $arrData['channelType'] = $channel;
        $arrData['coverInput'] = $arrData['sCover'];
        if (empty($arrData['iIsonline']))
            $arrData = false;
        $arrData['f_writer'] = $arrData['sSource_name'];
        echo json_encode($arrData);
        exit;
    }


    public function actionDelete($id)
    {
        self::delShare($id);
        $this->loadModel($id)->delete();
        ActiveCms::model()->saveCache($id);

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @author liulong
     * 修改分享链接
     * @param $id
     * @throws CHttpException
     */
    private function saveOther($id)
    {
        $model= $this->loadModel($id);
        if(!empty($model)){
            $t = 0;
            if(empty($model->sShare_link)){
                $t =1;
                $model->sShare_link = Yii::app()->params['CMS_new']['final_url'] . "/$id/index.html";
            }
            if(empty($model->sShare_otherLink)){
                $t =1;
                $model->sShare_otherLink = Yii::app()->params['CMS_new']['final_url'] . "/$id/index.html";
            }
            if(!empty($t)){
                $model->save();
            }

        }
    }

    /**
     * 手动刷新CDN
     * @return string
     */
    public function actionSaveCdn()
    {
        FlushCdn::getUrlFromRedis(1,'flush_cdn_shell_user');
        echo 'CDN刷新生完成，请静等生效';

//        if(in_array(Yii::app()->user->getId(),Yii::app()->params['cdn_user'])){
//            FlushCdn::getUrlFromRedis(1);
//            echo 'CDN刷新生完成，请静等生效';
//        }else{
//            echo '你没有权限，请联系相关人员申请单独的CDN权限';
//        }

    }


}
