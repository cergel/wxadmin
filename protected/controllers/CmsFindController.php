<?php

class CmsFindController extends Controller
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
     * 首页：查询页面
     */
    public function actionIndex()
    {
        $type = $this->getType();
        if(!empty($type)){
            $_GET['ActiveFind']['f_type'] = $type;
        }
        $model = new ActiveFind('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActiveFind']))
            $model->attributes = $_GET['ActiveFind'];
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
        $type = $this->getType();
        $model = new ActiveFind();
        if (isset($_POST['ActiveFind'])) {
            $arrData = $this->getPostInfo();
            $arrData['created'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                $model->f_writer = $arrData['f_writer'];
                $model->f_title = $arrData['f_title'];
                $model->save();
                self::saveChannelInfo($model->id);
                //内容写入缓存
                ActiveFind::model()->addRedisCacheForActiveFind2Id($model->id);
                self::getFindOther();//发现文章--推荐文章
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id,'type'=>$type));
            }
        }
        $model->f_type = !empty($type)?$type:1;
        $model->up_time = date('Y-m-d H:i:s',time());
        $channel =[];
        foreach (ActiveCms::model()->getChannel('list') as $k => $v)
            $channel[] = $k;
        $model->channel=array_slice($channel,0,5);
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 修改
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $type = $this->getType();
        $model = $this->loadModel($id);
        if (isset($_POST['ActiveFind'])) {
            $arrData = $this->getPostInfo();
            $model->attributes = $arrData;
            if ($model->save()) {
                $model->f_writer = $arrData['f_writer'];
                $model->f_title = $arrData['f_title'];
                $model->save();
                self::saveChannelInfo($model->id);
                //内容写入缓存
                ActiveFind::model()->addRedisCacheForActiveFind2Id($model->id);
                self::getFindOther();//发现文章--推荐文章
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id,'type'=>$type));
//                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        //分享平台s
        $channelUrl = [];
        if (is_array($model->channel)){
            $channel = [];
            foreach($model->channel as $result) {
                $channelUrl[$result['channel_id']] = $result['f_url'];
                $channel[] = $result['channel_id'];
            }
            $model->channel = $channel;
        }
        $model->up_time = date('Y-m-d H:i:s',$model->up_time);
        $this->render('update', array( 'model' => $model,'channelUrl'=>$channelUrl));
    }

    /**
     * 删除
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {

        //删除关联
        self::delChannelInfo($id);
        //删除内容
        ActiveFind::model()->delRedisCacheForActiveFindInfo($id);
        $this->loadModel($id)->delete();
        self::getFindOther();//发现文章--推荐文章
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function loadModel($id)
    {
        $model = ActiveFind::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 获取内容
     * @return mixed
     */
    private function getPostInfo()
    {
        $arrData = $_POST['ActiveFind'];
        $arrData['up_time'] = strtotime($arrData['up_time']);
        $arrData['updated'] = time();
        return $arrData;
    }

    /**
     * 处理渠道
     * @param $id
     */
    private function saveChannelInfo($id)
    {
        self::delChannelInfo($id);
        //插入
        if(isset($_POST['ActiveFind']['channel'])){
            $time = !empty($_POST['ActiveFind']['up_time'])?$_POST['ActiveFind']['up_time']:0;
            $status = !empty($_POST['ActiveFind']['status'])?$_POST['ActiveFind']['status']:0;
            $time = strtotime($time);
            foreach($_POST['ActiveFind']['channel'] as $val){
                $arrInfo = [];
                $arrInfo['f_url'] = isset($_POST['channelUrl'.$val])?$_POST['channelUrl'.$val]:'';
                $arrInfo['f_id'] = $id;
                $arrInfo['channel_id'] = $val;
                $arrInfo['up_time'] = $time;
                $arrInfo['status'] = $status;
                $activeFindChannelsModel=new ActiveFindChannels();
                $activeFindChannelsModel->attributes = $arrInfo;
                $activeFindChannelsModel->save();
            }
            self::addRedisCache($id);
        }
        return true;
    }

    private function addRedisCache($id)
    {
        $model = $this->loadModel($id);
        //更新缓存列表
        $channel =[];
        if (is_array($model->channel)){
            foreach($model->channel as $result) {
                if(!empty($result['status']))
                $channel[] = $result['channel_id'];
            }
            if(time() >= $model->up_time && !empty($channel))
                ActiveFind::model()->addRedisCacheForActiveFindList($id,$model->up_time,$channel,$model->f_type);
        }
        //缓存详细内容
    }

    /**
     * 删除
     * @param $id
     * @return int
     */
    private function delChannelInfo($id)
    {
        //清除缓存
        ActiveFind::model()->delRedisCacheForActiveFindList($id);
        //先删除
        return ActiveFindChannels::model()->deleteAllByAttributes(array(
            'f_id'=>$id
        ));
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

    //发现文章--推荐文章
    private function getFindOther()
    {
        ActiveFind::model()->saveFindCmsOther();//发现文章--推荐文章
    }
    private function getType()
    {
        $type = !empty($_GET['type'])?$_GET['type']:'';
        return $type == 19?19:'';
    }

}
