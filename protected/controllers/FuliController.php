<?php

/**
 * 福利频道
 */
class FuliController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete','activeOnlineOffline','GetApplyInfoAjax'),
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
        $model = new Fuli('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Fuli']))
            $model->attributes = $_GET['Fuli'];
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
        $model = new Fuli();
        $selectedCitys = [];
        if (isset($_POST['Fuli'])) {
            $arrData = $this->getPost();
            $arrData['created'] = time();
            $arrData['updated'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                self::addChannel($model->id);
                self::addCity($model->id);
                Fuli::model()->saveFile('fulipindao');
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $model->status = 1;
        $this->render('create', array(
            'model' => $model,
            'selectedCitys' => $selectedCitys,
        ));
    }
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['Fuli'])) {
            $arrData = $this->getPost();
            $arrData['updated'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                self::addChannel($model->id);
                self::addCity($model->id);
                Fuli::model()->saveFile('fulipindao');
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        //分享平台s
        $channelUrl = [];
        if (is_array($model->channel)){
            $channel = [];
            foreach($model->channel as $result) {
                $channelUrl[$result['channel_id']] = $result['url'];
                $channel[] = $result['channel_id'];
            }
            $model->channel = $channel;
        }
        $selectedCitys = [];
        if (is_array($model->citys)){
            foreach($model->citys as $result) {
                if(!empty($result['city_id']))
                    $selectedCitys[] = $result['city_id'];
            }
        }
        $model->start_time =empty($model->start_time)?'': date('Y-m-d H:i:s',$model->start_time);
        $model->end_time = empty($model->end_time)?'': date('Y-m-d H:i:s',$model->end_time);
        $model->up_time = date('Y-m-d H:i:s',$model->up_time);
        $model->down_time = date('Y-m-d H:i:s',$model->down_time);
        $this->render('update',array(
            'model'=>$model,
            'channelUrl'=>$channelUrl,
            'selectedCitys' => $selectedCitys,
        ));
    }
    public function loadModel($id)
    {
        $model = Fuli::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 参数整理
     * @return mixed
     */
    private function getPost()
    {
        $arrData = $_POST['Fuli'];
        $arrData['start_time'] = empty($arrData['start_time'])?'':strtotime($arrData['start_time']);
        $arrData['end_time'] = empty($arrData['end_time'])?'':strtotime($arrData['end_time']);
        $arrData['up_time'] = empty($arrData['up_time'])?'':strtotime($arrData['up_time']);
        $arrData['down_time'] = empty($arrData['down_time'])?'':strtotime($arrData['down_time']);
        $arrData['start_time'] = empty($arrData['start_time'])?'':$arrData['start_time'];
        $arrData['end_time'] = empty($arrData['end_time'])?'':$arrData['end_time'];
        unset($arrData['channel']);
        return $arrData;
    }

    /**
     * 渠道入库
     * @param $id
     */
    private function addChannel($id)
    {
        $this->delChannel($id);
        if(isset($_POST['Fuli']['channel'])){
            foreach($_POST['Fuli']['channel'] as $val){
                if(isset($_POST['channelUrl'.$val])){
                    $objChannel = new FuliChannel();
                    $objChannel->f_id = $id;
                    $objChannel->url = $_POST['channelUrl'.$val];
                    $objChannel->channel_id = $val;
                    $objChannel->save();
                }
            }
        }
    }
    /**
     * 渠道删除
     * @param $id
     */
    private function delChannel($id)
    {
        FuliChannel::model()->deleteAllByAttributes(array('f_id' => $id));
    }

    /**添加城市
     * @param $id
     */
    private function addCity($id)
    {
        self::delCity($id);
        if(!empty($_POST['citys'])){
            foreach($_POST['citys'] as $val){
                if(!empty($val)){
                    $obj = new FuliCity();
                    $obj->f_id = $id;
                    $obj->city_id = $val;
                    $obj->save();
                }
            }
        }else{
            $obj = new FuliCity();
            $obj->f_id = $id;
            $obj->city_id = 0;
            $obj->save();
        }
    }
    /**
     * 城市删除
     * @param $id
     */
    private function delCity($id)
    {
        FuliCity::model()->deleteAllByAttributes(array('f_id' => $id));
    }


    public function actionDelete($id)
    {
        $this->delChannel($id);
        self::delCity($id);
        $this->loadModel($id)->delete();
        Fuli::model()->saveFile('fulipindao');
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }



    protected  function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 获取报名活动详细信息
     * @param $id
     */
    public function actionGetApplyInfoAjax($id)
    {
        $channel = [];
        $mode = ApplyActive::model()->findByPk($id);
        $arrData = [];
        if(!empty($mode)){
            if(is_array($mode->platform)){
                foreach($mode->platform as $val){
                    $url =Yii::app()->params['apply_active']['final_url']."/$id/{$val->platform}/index.html";
                    $channel[$val->platform] = ['c'=>$val->platform,'url'=>$url];
                }
            }
            $arrData['title'] = $mode->title;
            $arrData['photo'] = $mode->picture;
            $arrData['time_box'] = $mode->start_display.' - '.$mode->end_display;
            $arrData['start_time'] = $mode->start_apply;
            $arrData['end_time'] = $mode->end_apply;
            //$arrData = $mode->attributes;
            $arrData['channel'] = $channel;
            echo json_encode($arrData);
        }else echo 0;
        exit;
    }


}
