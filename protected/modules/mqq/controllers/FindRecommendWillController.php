<?php

class FindRecommendWillController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $title = '';
        $link = '';
        $startTime = '';
        $endTime = '';
        $pic = '';

        if (!empty($_POST['form'])) {
            $sendCommoncgiData = [
                'title'=>$_POST['form']['title'],
                'link'=>$_POST['form']['link'],
                'startTime'=>$_POST['form']['startTime'],
                'endTime'=>$_POST['form']['endTime'],
                'pic' => ''
            ];
            //更新图片
            if(!empty($_FILES['UpLoadFile']['name'])){
                $path = "/uploads/findRecommend";
                $uploadRe = $this->commonUploadFile($path, $_FILES['UpLoadFile']['tmp_name'], $_FILES['UpLoadFile']['name']);
                if($uploadRe){
                    $sendCommoncgiData['pic'] = $uploadRe;
                }
            }
            //访问commoncgi更新数据
            $this->request_common_cgi("mqq_recommend_find_set",$sendCommoncgiData);
        }

        $r = $this->request_common_cgi("mqq_recommend_find_get");

        if (!empty($r['data'])) {
            $title = $r['data']['title'];
            $link = $r['data']['link'];
            $startTime = $r['data']['startTime'];
            $endTime = $r['data']['endTime'];
            $pic = $r['data']['pic'];
        }


        $this->render('index', array(
            'title' => $title,
            'link' => $link,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'pic' => $pic,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RecommendWill the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = RecommendWill::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param RecommendWill $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recommend-will-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //对commoncgi发起请求更新缓存
    protected function request_common_cgi($type, $arrData = [])
    {
        $commonCgiUrl = Yii::app()->params['commoncgi'][$type];
        $commonCgiData = $arrData;
        Log::model()->logFile($type, $commonCgiUrl);
        Log::model()->logFile($type, json_encode($commonCgiData));
        $r = Https::getPost($commonCgiData, $commonCgiUrl);
        Log::model()->logFile($type, json_encode($r));
        $r = json_decode($r, true);
        return $r;
    }
}
