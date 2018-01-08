<?php

/**
 * Class LiveShowTempController
 * liulong
 */
class LiveShowTempController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxUpload', 'activeOnlineOffline', 'getCmsInfoAjax'),
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
        $model = new LiveShowTemp('search');
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
        $model = new LiveShowTemp;
        if (isset($_POST['LiveShowTemp'])) {
            $_POST['LiveShowTemp']['start_time'] = isset($_POST['LiveShowTemp']['start_time'])?strtotime($_POST['LiveShowTemp']['start_time']):'';
            $_POST['LiveShowTemp']['end_time'] = isset($_POST['LiveShowTemp']['end_time'])?strtotime($_POST['LiveShowTemp']['end_time']):'';
            $model->attributes = $_POST['LiveShowTemp'];
            $model->created = $model->updated = time();
            if ($model->save()) {
                $model->saveFile($model->id);
                //更新CDN
//                 FlushCdn::setUrlToRedis(Yii::app()->params['LiveShowTemp']['final_url'] . '/' . $model->id.'/index.html');
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        if(empty($model->css)){
            $model->css = $model->getBaseCss();
        }
        $model->start_time = $model->end_time = '';
        $model->text_2 = '下载娱票儿APP';
        $model->text_3 = '看明星现场';
        $model->text_4 = '视频直播';
        $model->down_href = 'https://promotion.wepiao.com/down/mobilelist/download.html?_wepiao_spm=0.0.0.t3ks&redirect=1';
        $model->temp_title = '您预约的XXX直播开始了，快来围观！';
        $model->temp_url = 'wxmovie://home';
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 更新
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['LiveShowTemp'])) {
            $_POST['LiveShowTemp']['start_time'] = isset($_POST['LiveShowTemp']['start_time'])?strtotime($_POST['LiveShowTemp']['start_time']):'';
            $_POST['LiveShowTemp']['end_time'] = isset($_POST['LiveShowTemp']['end_time'])?strtotime($_POST['LiveShowTemp']['end_time']):'';
            $model->attributes = $_POST['LiveShowTemp'];
            $model->updated = time();
            if ($model->save()) {
                $model->saveFile($model->id);
                //更新CDN
                 FlushCdn::setUrlToRedis(Yii::app()->params['live_show_temp']['final_url'] . '/' . $model->id.'/index.html');
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $model->start_time = !empty($model->start_time)?date('Y-m-d H:i:s',$model->start_time):'';
        $model->end_time = !empty($model->end_time)?date('Y-m-d H:i:s',$model->end_time):'';
        $this->render('update', array('model' => $model,));
    }

    public function loadModel($id)
    {
        $model = LiveShowTemp::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }



}
