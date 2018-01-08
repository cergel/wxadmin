<?php

class CmsNewsController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete','ajaxUpload','activeOnlineOffline','getCmsInfoAjax'),
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
        $model = new ActiveNews('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActiveNews']))
            $model->attributes = $_GET['ActiveNews'];
        $this->render('index', array(
            'model' => $model,
        ));
    }
    public function loadModel($id)
    {
        $model = ActiveNews::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }


    /**
     * 删除分享关联
     * @param $activeId
     */
    private function delShare($activeId)
    {
        ActiveShare::model()->deleteAllByAttributes(array('iActive_id' => $activeId));
    }

    /*
     * 获取内容
     */
    public function actionGetCmsInfoAjax($id)
    {
        $model = $this->loadModel($id);
        $arrData = $model->attributes;
        $channel = ActiveCms::model()->getChannel('list');
        foreach($channel as $k=>$v){
            if(!empty($k)){
                $arrData['channelUrl'.$k] = Yii::app()->params['CMS_new']['final_url'].'/'.$id.'/'.$k.'/index.html';
            }
        }
        $arrData['channelType'] = $channel ;
        $arrData['coverInput'] = $arrData['sCover'];
        if(empty($arrData['iIsonline']))
            $arrData =false;
        echo json_encode($arrData);exit;
    }

    public function actionDelete($id)
    {
//        $model = $this->loadModel($id);
        //self::delShare($id);
        ActiveNews::model()->delCache($id);
//        $this->loadModel($id)->delCache($model->a_id);
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }



    /**
     * Performs the AJAX validation.
     * @param Active $model the model to be validated
     */
    protected  function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}
